<?php

namespace App\Support;

use ZipArchive;
use Exception;

class SpreadsheetReader
{
    /**
     * Read rows from a CSV or XLSX file path.
     * Returns array of rows, where each row is an array of cell string values.
     *
     * @param string $filePath
     * @param string|null $originalName
     * @return array<int, array<int, string>>
     */
    public static function read(string $filePath, ?string $originalName = null): array
    {
        $extension = strtolower(pathinfo($originalName ?? $filePath, PATHINFO_EXTENSION));

        if ($extension === 'xlsx') {
            return self::readXlsx($filePath);
        }

        return self::readCsv($filePath);
    }

    /**
     * Parse CSV file with auto-detected delimiter and BOM handling.
     */
    public static function readCsv(string $filePath): array
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new Exception("File CSV tidak dapat dibaca: {$filePath}");
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            return [];
        }

        // Remove UTF-8 BOM if present
        if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
            $content = substr($content, 3);
        }

        // Normalize line endings
        $content = str_replace(["\r\n", "\r"], "\n", $content);

        // Auto-detect delimiter from first line
        $firstLine = strtok($content, "\n");
        $delimiter = ',';
        if ($firstLine !== false) {
            $commaCount     = substr_count($firstLine, ',');
            $semicolonCount = substr_count($firstLine, ';');
            $tabCount       = substr_count($firstLine, "\t");

            if ($semicolonCount > $commaCount && $semicolonCount >= $tabCount) {
                $delimiter = ';';
            } elseif ($tabCount > $commaCount && $tabCount > $semicolonCount) {
                $delimiter = "\t";
            }
        }

        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $content);
        rewind($stream);

        $rows = [];
        while (($data = fgetcsv($stream, 0, $delimiter)) !== false) {
            // Trim whitespace from cell values
            $rows[] = array_map(function ($val) {
                return trim((string) $val);
            }, $data);
        }

        fclose($stream);

        return $rows;
    }

    /**
     * Parse XLSX file using ZipArchive and SimpleXML without external libraries.
     * Supports multi-tab / multi-worksheet XLSX files.
     */
    public static function readXlsx(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new Exception("File XLSX tidak ditemukan.");
        }

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new Exception("Gagal membuka file XLSX.");
        }

        // 1. Load Shared Strings
        $sharedStrings = [];
        $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedStringsXml !== false) {
            $xml = @simplexml_load_string($sharedStringsXml);
            if ($xml && isset($xml->si)) {
                foreach ($xml->si as $val) {
                    if (isset($val->t)) {
                        $sharedStrings[] = (string) $val->t;
                    } elseif (isset($val->r)) {
                        $str = '';
                        foreach ($val->r as $run) {
                            $str .= (string) $run->t;
                        }
                        $sharedStrings[] = $str;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        // 2. Load Sheet Names and Map to Target Files
        $sheetNamesMap = [];
        $workbookXml = $zip->getFromName('xl/workbook.xml');
        $relsXml = $zip->getFromName('xl/_rels/workbook.xml.rels');

        if ($workbookXml !== false && $relsXml !== false) {
            $relDoc = @simplexml_load_string($relsXml);
            $wbDoc = @simplexml_load_string($workbookXml);

            $rIdToTarget = [];
            if ($relDoc) {
                foreach ($relDoc->Relationship as $rel) {
                    $rIdToTarget[(string)$rel['Id']] = (string)$rel['Target'];
                }
            }

            if ($wbDoc && isset($wbDoc->sheets->sheet)) {
                foreach ($wbDoc->sheets->sheet as $s) {
                    $name = (string)$s['name'];
                    $rId = (string)$s->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id'];
                    if (isset($rIdToTarget[$rId])) {
                        $target = 'xl/' . ltrim($rIdToTarget[$rId], 'xl/');
                        $sheetNamesMap[$target] = $name;
                    }
                }
            }
        }

        // 3. Find All Worksheet Files
        $sheetFiles = [];
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            if (preg_match('/xl\/worksheets\/sheet(\d+)\.xml$/i', $filename, $m)) {
                $sheetFiles[(int)$m[1]] = $filename;
            }
        }

        if (empty($sheetFiles)) {
            $zip->close();
            throw new Exception("Lembar kerja (worksheet) tidak ditemukan di dalam XLSX.");
        }

        ksort($sheetFiles);

        $allRows = [];

        foreach ($sheetFiles as $sheetFile) {
            $tabName = $sheetNamesMap[$sheetFile] ?? null;

            // Prepend a virtual class metadata row with the tab name if available
            if (!empty($tabName) && !in_array(strtolower($tabName), ['sheet1', 'sheet2', 'sheet3', 'sheet'], true)) {
                $allRows[] = ['Kelas : ' . $tabName];
            }

            $sheetXml = $zip->getFromName($sheetFile);
            if ($sheetXml === false) {
                continue;
            }

            $xml = @simplexml_load_string($sheetXml);
            if (!$xml || !isset($xml->sheetData)) {
                continue;
            }

            foreach ($xml->sheetData->row as $rowNode) {
                $rowCells = [];
                $maxCol = 0;

                foreach ($rowNode->c as $cell) {
                    $cellRef = (string) $cell['r'];
                    $colIndex = self::coordinateToColumnIndex($cellRef);
                    $type = (string) $cell['t'];

                    $val = '';
                    if ($type === 's') { // Shared string index
                        $idx = (int) $cell->v;
                        $val = $sharedStrings[$idx] ?? '';
                    } elseif ($type === 'inlineStr') {
                        $val = (string) $cell->is->t;
                    } else {
                        $val = (string) $cell->v;
                    }

                    $val = trim($val);

                    // Format scientific notation floats (e.g. 1.23456789E9 or 1234567890.0)
                    if (preg_match('/^[0-9]+\.0+$/', $val)) {
                        $val = substr($val, 0, strpos($val, '.'));
                    } elseif (preg_match('/^[0-9]+(\.[0-9]+)?[eE]\+[0-9]+$/', $val)) {
                        $val = sprintf('%.0f', (float) $val);
                    }

                    $rowCells[$colIndex] = $val;
                    if ($colIndex > $maxCol) {
                        $maxCol = $colIndex;
                    }
                }

                // Fill empty gaps for columns
                $flatRow = [];
                for ($c = 0; $c <= $maxCol; $c++) {
                    $flatRow[$c] = $rowCells[$c] ?? '';
                }

                if (!empty(array_filter($flatRow, fn($v) => $v !== ''))) {
                    $allRows[] = $flatRow;
                }
            }
        }

        $zip->close();

        return $allRows;
    }

    /**
     * Convert cell coordinate like 'A1' or 'BC12' to 0-based column index.
     */
    private static function coordinateToColumnIndex(string $coordinate): int
    {
        preg_match('/^([A-Z]+)/i', $coordinate, $matches);
        if (empty($matches[1])) {
            return 0;
        }

        $column = strtoupper($matches[1]);
        $length = strlen($column);
        $index = 0;

        for ($i = 0; $i < $length; $i++) {
            $index = $index * 26 + (ord($column[$i]) - ord('A') + 1);
        }

        return $index - 1;
    }
}
