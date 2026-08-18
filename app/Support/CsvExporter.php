<?php

namespace App\Support;

use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExporter
{
    /**
     * Download a CSV file with a header row and uniform data rows.
     */
    public static function download(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return self::stream($filename, function ($handle) use ($headers, $rows) {
            fputcsv($handle, $headers);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
        });
    }

    /**
     * Download a CSV file with arbitrary row structures (e.g. multi-section reports).
     */
    public static function downloadRows(string $filename, array $rows): StreamedResponse
    {
        return self::stream($filename, function ($handle) use ($rows) {
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
        });
    }

    private static function stream(string $filename, callable $writer): StreamedResponse
    {
        return response()->streamDownload(function () use ($writer) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            $writer($handle);
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
