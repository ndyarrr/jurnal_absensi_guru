<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Support\CsvExporter;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SiswaController extends Controller
{
    /**
     * Display Master Data - Siswa page matching the exact mockup design.
     */
    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $siswa = $query->orderBy('nama_siswa', 'asc')->paginate(8)->withQueryString();
        $totalSiswaCount = Siswa::count();

        // Master lists for filter dropdowns and create/edit selects
        $kelasList   = Kelas::with('jurusan')->orderBy('tingkat')->get();
        $jurusanList = Jurusan::orderBy('nama_jurusan')->get();
        $tingkatList = ['X', 'XI', 'XII'];

        // Dynamic Rombel list fetched from database
        $dbRombels  = Kelas::select('rombel')->distinct()->whereNotNull('rombel')->pluck('rombel')->map(fn($r) => (int)$r)->unique()->sort()->values()->toArray();
        $rombelList = !empty($dbRombels) ? $dbRombels : [1, 2, 3, 4, 5];

        return view('admin.siswa.index', compact(
            'siswa',
            'totalSiswaCount',
            'kelasList',
            'jurusanList',
            'tingkatList',
            'rombelList'
        ));
    }

    /**
     * Store a newly created Siswa.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn'          => 'nullable|digits:10|unique:siswa,nisn',
            'nama_siswa'    => 'required|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_telepon'    => 'nullable|string|max:20',
            'id_kelas'      => 'required|exists:kelas,id_kelas',
        ], [
            'nisn.digits'         => 'NISN harus berisi tepat 10 digit angka.',
            'nisn.unique'         => 'NISN sudah terdaftar.',
            'nama_siswa.required' => 'Nama siswa wajib diisi.',
            'id_kelas.required'   => 'Kelas wajib dipilih.',
        ]);

        Siswa::create($validated);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan');
    }

    /**
     * Show details of a specific Siswa via JSON AJAX.
     */
    public function show(Siswa $siswa)
    {
        $siswa->load('kelas.jurusan');
        return response()->json([
            'id_siswa'      => $siswa->id_siswa,
            'nisn'          => $siswa->nisn,
            'nama_siswa'    => $siswa->nama_siswa,
            'jenis_kelamin' => $siswa->jenis_kelamin ?? '-',
            'jk_label'      => $siswa->jenis_kelamin === 'L' ? 'Laki-laki (L)' : ($siswa->jenis_kelamin === 'P' ? 'Perempuan (P)' : '-'),
            'no_telepon'    => $siswa->no_telepon,
            'kelas_str'     => optional($siswa->kelas)->tingkat
                            . ' ' . optional(optional($siswa->kelas)->jurusan)->kode_jurusan
                            . ' ' . optional($siswa->kelas)->rombel,
            'id_kelas'      => $siswa->id_kelas,
        ]);
    }

    /**
     * Update the specified Siswa in database.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nisn'          => 'nullable|digits:10|unique:siswa,nisn,' . $siswa->id_siswa . ',id_siswa',
            'nama_siswa'    => 'required|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_telepon'    => 'nullable|string|max:20',
            'id_kelas'      => 'required|exists:kelas,id_kelas',
        ], [
            'nisn.digits'   => 'NISN harus berisi tepat 10 digit angka.',
            'nisn.unique'   => 'NISN ini sudah digunakan oleh siswa lain.',
        ]);

        $siswa->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => 'Data siswa berhasil diperbarui']);
        }

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * Remove the specified Siswa.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus');
    }

    /**
     * Export filtered siswa data as CSV.
     */
    public function exportCsv(Request $request)
    {
        $records = $this->buildFilteredQuery($request)
            ->orderBy('nama_siswa')
            ->get();

        $rows = $records->map(function ($s) {
            $kelasStr = '-';
            if ($s->kelas && !$s->kelas->trashed()) {
                $kelasStr = trim(
                    $s->kelas->tingkat . ' '
                    . optional($s->kelas->jurusan)->kode_jurusan . ' '
                    . $s->kelas->rombel
                );
            }

            $jkText = '-';
            if ($s->jenis_kelamin === 'L') {
                $jkText = 'Laki-laki (L)';
            } elseif ($s->jenis_kelamin === 'P') {
                $jkText = 'Perempuan (P)';
            }

            return [
                $s->nisn,
                $s->nama_siswa,
                $jkText,
                $s->no_telepon ?? '-',
                $kelasStr,
                'Aktif',
            ];
        });

        $filename = 'data-siswa-' . Carbon::now('Asia/Jakarta')->format('Y-m-d') . '.csv';

        return CsvExporter::download($filename, [
            'NISN',
            'Nama Siswa',
            'Jenis Kelamin',
            'No. Telepon',
            'Kelas',
            'Status',
        ], $rows);
    }

    /**
     * Download sample CSV template for Siswa import.
     */
    public function downloadTemplate()
    {
        $headers = ['NISN', 'Nama Siswa', 'Jenis Kelamin (L/P)', 'No. Telepon', 'Kelas'];
        $sampleData = [
            ['1234567891', 'Ahmad Subagja', 'L', '081234567891', 'X RPL 1'],
            ['1234567892', 'Siti Aminah', 'P', '081234567892', 'X RPL 1'],
        ];

        $output = implode(',', $headers) . "\n";
        foreach ($sampleData as $row) {
            $output .= implode(',', array_map(fn($v) => '"' . str_replace('"', '""', $v) . '"', $row)) . "\n";
        }

        return response($output, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template-import-siswa.csv"',
        ]);
    }

    /**
     * Import siswa data from CSV or XLSX file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file'     => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
            'id_kelas' => 'nullable|exists:kelas,id_kelas',
        ], [
            'file.required' => 'Silakan pilih file CSV atau XLSX untuk diunggah.',
            'file.mimes'    => 'Format file harus berupa CSV atau XLSX.',
            'file.max'      => 'Ukuran file maksimal 10MB.',
        ]);

        try {
            $file = $request->file('file');
            $rows = \App\Support\SpreadsheetReader::read($file->getRealPath(), $file->getClientOriginalName());

            if (empty($rows)) {
                return back()->with('error', 'File yang diunggah kosong atau tidak memiliki data.');
            }

            // 1. Detect headers or determine column indices
            $headerIndexMap = $this->detectHeaderIndices($rows[0]);
            $startRowIndex = 1;

            // If row 0 does not look like a header, fallback to default positions
            if ($headerIndexMap['nisn'] === null && $headerIndexMap['nama_siswa'] === null) {
                $headerIndexMap = [
                    'nisn'          => 0,
                    'nama_siswa'    => 1,
                    'jenis_kelamin' => 2,
                    'no_telepon'    => 3,
                    'kelas'         => 4,
                ];
                $startRowIndex = 0;
            }

            // 1. Build Kelas lookup map
            $kelasMap = $this->buildKelasLookupMap();
            $defaultIdKelas = $request->input('id_kelas');

            // Initial detection from top rows
            $currentMetaKelasId = $this->extractMetaKelasId($rows, $kelasMap) ?: $defaultIdKelas;

            $headerRowIndex = $this->findTableHeaderRow($rows);
            $currentHeaderMap = $this->detectHeaderIndices($rows[$headerRowIndex]);

            // Fallback if no header row found
            if ($currentHeaderMap['nisn'] === null && $currentHeaderMap['nama_siswa'] === null) {
                $currentHeaderMap = [
                    'nisn'          => 0,
                    'nama_siswa'    => 1,
                    'jenis_kelamin' => 2,
                    'no_telepon'    => 3,
                    'kelas'         => 4,
                ];
            }

            $successCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;
            $errors = [];

            for ($i = 0; $i < count($rows); $i++) {
                $row = $rows[$i];
                $rowNum = $i + 1;

                // 1. Skip completely empty rows
                $nonEmptyCells = array_filter($row, fn($v) => trim((string)$v) !== '');
                if (empty($nonEmptyCells)) {
                    continue;
                }

                $fullRowText = implode(' ', array_map('trim', $row));
                $fullRowLower = strtolower($fullRowText);

                // 2. Dynamic Class Header Check (e.g. "Kelas : XII DKV 1", "Kelas: X RPL 2")
                if (preg_match('/kelas\s*[:=]\s*(.+)/i', $fullRowText, $matches)) {
                    $extracted = trim($matches[1]);
                    if (preg_match('/^([^\:\=]+?)(?:\s+(?:wali|guru|mata|materi|kompetensi)|$)/i', $extracted, $sub)) {
                        $extracted = trim($sub[1]);
                    }
                    $detectedId = $this->matchKelasId($extracted, $kelasMap, null);
                    if ($detectedId) {
                        $currentMetaKelasId = $detectedId;
                    }
                    continue; // Skip metadata line itself
                }

                // 3. Dynamic Table Header Check (contains NISN and NAMA)
                $detectedHeaderMap = $this->detectHeaderIndices($row);
                if ($detectedHeaderMap['nisn'] !== null || $detectedHeaderMap['nama_siswa'] !== null) {
                    $currentHeaderMap = $detectedHeaderMap;
                    continue; // Skip header row repeat
                }

                // 4. Non-Student Footer / Instruction / Header / Spacing Filtering
                if (
                    preg_match('/(apabila|melaporkan|keterangan|laki\s*-\s*laki|perempuan|wali\s*kelas|jumlah\s*siswa|mengetahui|kepala\s*sekolah|tanda\s*tangan|pemerintah|dinas\s*pendidikan|smk|sma|smp|daftar\s*siswa|tahun\s*ajaran|semester|kompetensi\s*keahlian)/i', $fullRowText)
                ) {
                    continue; // Skip template headers / footers / notes silently
                }

                // Extract values using current header map
                $rawNisn  = ($currentHeaderMap['nisn'] !== null && isset($row[$currentHeaderMap['nisn']])) ? $row[$currentHeaderMap['nisn']] : ($row[1] ?? '');
                $rawNama  = ($currentHeaderMap['nama_siswa'] !== null && isset($row[$currentHeaderMap['nama_siswa']])) ? $row[$currentHeaderMap['nama_siswa']] : ($row[2] ?? '');
                $rawJk    = ($currentHeaderMap['jenis_kelamin'] !== null && isset($row[$currentHeaderMap['jenis_kelamin']])) ? $row[$currentHeaderMap['jenis_kelamin']] : '';
                $rawNoHp  = ($currentHeaderMap['no_telepon'] !== null && isset($row[$currentHeaderMap['no_telepon']])) ? $row[$currentHeaderMap['no_telepon']] : '';
                $rawKelas = ($currentHeaderMap['kelas'] !== null && isset($row[$currentHeaderMap['kelas']])) ? $row[$currentHeaderMap['kelas']] : '';

                $namaSiswa  = trim((string)$rawNama);
                $nisnRawStr = trim((string)$rawNisn);
                $namaLower  = strtolower($namaSiswa);
                $nisnLower  = strtolower($nisnRawStr);

                if (empty($namaSiswa) && empty($nisnRawStr)) {
                    continue;
                }

                // Check repeating header strings in cell values
                if (in_array($nisnLower, ['nisn', 'no nisn', 'n.i.s.n', 'no', 'niss', 'no.'], true) ||
                    in_array($namaLower, ['nama', 'nama siswa', 'nama lengkap', 'siswa', 'no', 'no.'], true)) {
                    continue;
                }

                // Clean NISN (digits only)
                $nisn = preg_replace('/[^0-9]/', '', $nisnRawStr);
                if (strlen($nisn) > 0 && strlen($nisn) < 10) {
                    $nisn = str_pad($nisn, 10, '0', STR_PAD_LEFT);
                }

                // Detect Jenis Kelamin (handles L/P, LAKI-LAKI, PEREMPUAN, etc.)
                $jk = $this->normalizeJenisKelamin($rawJk);

                // Detect Kelas (priority: row kelas column -> active section meta kelas -> default dropdown)
                $idKelas = $this->matchKelasId($rawKelas, $kelasMap, $currentMetaKelasId);

                // Check NISN format (must be 10 digits if provided)
                $effectiveNisn = null;
                if (!empty($nisn) && strlen($nisn) === 10) {
                    $effectiveNisn = $nisn;
                } else {
                    // NISN is missing/blank or not 10 digits.
                    // If namaSiswa is empty, too short (< 3 chars), or non-student template text -> skip silently
                    if (
                        empty($namaSiswa) ||
                        strlen($namaSiswa) < 3 ||
                        !preg_match('/[a-zA-Z]/', $namaSiswa) ||
                        strlen($namaSiswa) > 60 ||
                        preg_match('/(apabila|keterangan|melaporkan|wali|kesiswaan|jumlah|total|laki|perempuan|smk|sma|smp|sekolah|pemerintah|dinas|daftar|tahun|ajaran|semester|kompetensi|mengetahui|kepala)/i', $namaSiswa)
                    ) {
                        continue;
                    }

                    // If NISN string was present but invalid format (e.g. "12345" or "ABC"), log warning
                    if (!empty($nisnRawStr) && !in_array($nisnLower, ['-', 'null', '0', 'nisn', 'no', 'niss'], true)) {
                        $skippedCount++;
                        $errors[] = "Baris {$rowNum}: NISN ('{$nisnRawStr}') untuk siswa '{$namaSiswa}' tidak valid (harus 10 digit angka atau kosong).";
                        continue;
                    }

                    // NISN is genuinely empty -> allow insertion with NULL NISN!
                    $effectiveNisn = null;
                }

                if (empty($namaSiswa)) {
                    continue;
                }

                if (!$idKelas) {
                    $skippedCount++;
                    $errors[] = "Baris {$rowNum}: Kelas ('{$rawKelas}') untuk siswa '{$namaSiswa}' tidak ditemukan.";
                    continue;
                }

                // Phone number validation: Must be at least 8 digits to avoid mistaking NISS (e.g. 23700) for phone numbers
                $noTelepon = null;
                if (!empty($rawNoHp)) {
                    $cleanedHp = preg_replace('/[^0-9\+]/', '', trim((string)$rawNoHp));
                    if (strlen(preg_replace('/[^0-9]/', '', $cleanedHp)) >= 8) {
                        $noTelepon = $cleanedHp;
                    }
                }

                // Upsert logic:
                // 1. If effectiveNisn exists -> search by NISN first
                // 2. If effectiveNisn is null -> search by nama_siswa within the same class
                $existing = null;
                if ($effectiveNisn) {
                    $existing = Siswa::where('nisn', $effectiveNisn)->first();
                }

                if (!$existing) {
                    $existing = Siswa::where('id_kelas', $idKelas)
                        ->whereRaw('LOWER(nama_siswa) = ?', [strtolower($namaSiswa)])
                        ->first();
                }

                if ($existing) {
                    // Update existing student record
                    $existing->update([
                        'nama_siswa'    => $namaSiswa,
                        'nisn'          => $effectiveNisn ?? $existing->nisn,
                        'jenis_kelamin' => $jk ?? $existing->jenis_kelamin,
                        'no_telepon'    => $noTelepon ?? $existing->no_telepon,
                        'id_kelas'      => $idKelas,
                    ]);
                    $updatedCount++;
                    continue;
                }

                // Create new student
                Siswa::create([
                    'nisn'          => $effectiveNisn,
                    'nama_siswa'    => $namaSiswa,
                    'jenis_kelamin' => $jk,
                    'no_telepon'    => $noTelepon,
                    'id_kelas'      => $idKelas,
                ]);

                $successCount++;
            }

            $summaryParts = [];
            if ($successCount > 0) $summaryParts[] = "{$successCount} siswa baru";
            if ($updatedCount > 0) $summaryParts[] = "{$updatedCount} diperbarui/ditimpa";
            if ($skippedCount > 0) $summaryParts[] = "{$skippedCount} baris dilewati/gagal";

            $message = "Hasil Import: " . (implode(", ", $summaryParts) ?: "Tidak ada data yang diproses") . ".";

            if ($successCount > 0 || $updatedCount > 0) {
                return back()
                    ->with('success', $message)
                    ->with('import_summary', [
                        'success' => $successCount,
                        'updated' => $updatedCount,
                        'skipped' => $skippedCount,
                        'total'   => $successCount + $updatedCount + $skippedCount,
                    ])
                    ->with('import_errors', $errors);
            } else {
                return back()
                    ->with('error', "Gagal mengimpor data siswa. " . (count($errors) > 0 ? implode(" | ", array_slice($errors, 0, 5)) : "Format data tidak valid."))
                    ->with('import_errors', $errors);
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memproses file: ' . $e->getMessage());
        }
    }

    private function findTableHeaderRow(array $rows): int
    {
        $scanLimit = min(15, count($rows));

        for ($r = 0; $r < $scanLimit; $r++) {
            $map = $this->detectHeaderIndices($rows[$r]);
            if ($map['nisn'] !== null || $map['nama_siswa'] !== null) {
                return $r;
            }
        }

        return 0;
    }

    private function extractMetaKelasId(array $rows, array $kelasMap): ?int
    {
        $scanLimit = min(15, count($rows));

        for ($r = 0; $r < $scanLimit; $r++) {
            foreach ($rows[$r] as $cellValue) {
                $cellStr = trim((string)$cellValue);
                if (empty($cellStr)) {
                    continue;
                }

                if (preg_match('/kelas\s*[:=]\s*(.+)/i', $cellStr, $matches)) {
                    $extracted = trim($matches[1]);

                    if (preg_match('/^([^\:\=]+?)(?:\s+(?:wali|guru|mata|materi|kompetensi)|$)/i', $extracted, $sub)) {
                        $extracted = trim($sub[1]);
                    }

                    $matchedId = $this->matchKelasId($extracted, $kelasMap, null);
                    if ($matchedId) {
                        return $matchedId;
                    }
                }
            }
        }

        return null;
    }

    private function detectHeaderIndices(array $headerRow): array
    {
        $map = [
            'nisn'          => null,
            'nama_siswa'    => null,
            'jenis_kelamin' => null,
            'no_telepon'    => null,
            'kelas'         => null,
        ];

        foreach ($headerRow as $index => $colName) {
            $normalized = strtolower(preg_replace('/[^a-z0-9]/i', '', (string)$colName));

            if (in_array($normalized, ['nisn', 'nonisn', 'nomornisn', 'nisnsiswa', 'nis'], true)) {
                $map['nisn'] = $index;
            } elseif (in_array($normalized, ['namasiswa', 'nama', 'namalengkap', 'namamurid', 'siswa', 'fullname'], true)) {
                $map['nama_siswa'] = $index;
            } elseif (in_array($normalized, ['jeniskelamin', 'jk', 'lp', 'gender', 'sex', 'jeniskelaminlp', 'jklp'], true)) {
                $map['jenis_kelamin'] = $index;
            } elseif (in_array($normalized, ['kelas', 'idkelas', 'namakelas', 'tingkatkelas', 'rombel', 'tingkat'], true)) {
                $map['kelas'] = $index;
            } elseif (in_array($normalized, ['notelepon', 'nohp', 'telepon', 'hp', 'nowa', 'whatsapp', 'notelp', 'teleponhp'], true)) {
                $map['no_telepon'] = $index;
            }
        }

        return $map;
    }

    private function normalizeJenisKelamin(string $val): ?string
    {
        $v = strtolower(trim($val));
        if (empty($v)) {
            return null;
        }

        $clean = preg_replace('/[^a-z]/', '', $v);

        if ($clean === 'l' || str_starts_with($clean, 'lak') || str_starts_with($clean, 'pria') || $clean === 'male' || $clean === 'm') {
            return 'L';
        }

        if ($clean === 'p' || str_starts_with($clean, 'per') || str_starts_with($clean, 'wan') || $clean === 'female' || $clean === 'f') {
            return 'P';
        }

        return null;
    }

    private function buildKelasLookupMap(): array
    {
        $map = [];
        $kelases = Kelas::withTrashed()->with(['jurusan' => function($q) { $q->withTrashed(); }])->get();

        foreach ($kelases as $k) {
            $id = $k->id_kelas;
            $map[(string)$id] = $id;

            $tingkat = $k->tingkat;
            $kodeJurusan = optional($k->jurusan)->kode_jurusan ?? '';
            $namaJurusan = optional($k->jurusan)->nama_jurusan ?? '';
            $rombel = $k->rombel;

            $variations = [
                "{$tingkat} {$kodeJurusan} {$rombel}",
                "{$tingkat}-{$kodeJurusan}-{$rombel}",
                "{$tingkat}{$kodeJurusan}{$rombel}",
                "{$tingkat} {$namaJurusan} {$rombel}",
            ];

            $numTingkat = match(strtoupper($tingkat)) {
                'X' => '10',
                'XI' => '11',
                'XII' => '12',
                default => $tingkat
            };

            if ($numTingkat !== $tingkat) {
                $variations[] = "{$numTingkat} {$kodeJurusan} {$rombel}";
                $variations[] = "{$numTingkat}-{$kodeJurusan}-{$rombel}";
                $variations[] = "{$numTingkat}{$kodeJurusan}{$rombel}";
            }

            foreach ($variations as $var) {
                $norm = strtolower(preg_replace('/[^a-z0-9]/i', '', $var));
                if (!empty($norm)) {
                    $map[$norm] = $id;
                }
            }
        }

        return $map;
    }

    private function matchKelasId(string $rawKelas, array &$kelasMap, $defaultIdKelas): ?int
    {
        $raw = trim($rawKelas);
        if (empty($raw)) {
            return $defaultIdKelas ? (int)$defaultIdKelas : null;
        }

        $matchedId = null;
        if (is_numeric($raw) && isset($kelasMap[$raw])) {
            $matchedId = (int)$kelasMap[$raw];
        } else {
            $norm = strtolower(preg_replace('/[^a-z0-9]/i', '', $raw));
            if (isset($kelasMap[$norm])) {
                $matchedId = (int)$kelasMap[$norm];
            }
        }

        if ($matchedId) {
            $targetKelas = Kelas::withTrashed()->find($matchedId);
            if ($targetKelas && $targetKelas->trashed()) {
                $targetKelas->restore();
            }
            return $matchedId;
        }

        // Try auto-creating / restoring missing class from string like "XII DKV 2", "X RPL 1", "10 PPLG 3"
        $autoId = $this->autoCreateKelas($raw, $kelasMap);
        if ($autoId) {
            return $autoId;
        }

        return $defaultIdKelas ? (int)$defaultIdKelas : null;
    }

    private function autoCreateKelas(string $rawKelas, array &$kelasMap): ?int
    {
        // Pattern: "XII DKV 2", "X PPLG 1", "10 RPL 3", "XI TKJ 2"
        if (preg_match('/^(X|XI|XII|10|11|12)[\s\-]+([A-Z0-9]+)(?:[\s\-]+([0-9]+))?$/i', trim($rawKelas), $matches)) {
            $tingkatRaw  = strtoupper($matches[1]);
            $kodeJurusan = strtoupper($matches[2]);
            $rombel      = isset($matches[3]) ? (int)$matches[3] : 1;

            $tingkat = match($tingkatRaw) {
                '10' => 'X',
                '11' => 'XI',
                '12' => 'XII',
                default => $tingkatRaw
            };

            // Find or restore Jurusan
            $jurusan = Jurusan::withTrashed()->where('kode_jurusan', $kodeJurusan)->first();
            if ($jurusan) {
                if ($jurusan->trashed()) {
                    $jurusan->restore();
                }
            } else {
                $jurusan = Jurusan::create([
                    'kode_jurusan' => $kodeJurusan,
                    'nama_jurusan' => $kodeJurusan,
                ]);
            }

            // Find or restore Kelas
            $kelas = Kelas::withTrashed()
                ->where('tingkat', $tingkat)
                ->where('id_jurusan', $jurusan->id_jurusan)
                ->where('rombel', $rombel)
                ->first();

            if ($kelas) {
                if ($kelas->trashed()) {
                    $kelas->restore();
                }
            } else {
                $kelas = Kelas::create([
                    'tingkat'      => $tingkat,
                    'id_jurusan'   => $jurusan->id_jurusan,
                    'rombel'       => $rombel,
                    'jumlah_siswa' => 0,
                ]);
            }

            $id = $kelas->id_kelas;

            // Register newly created class into lookup map so next rows pick it up instantly
            $kelasMap[strtolower(preg_replace('/[^a-z0-9]/i', '', $rawKelas))] = $id;
            $kelasMap[(string)$id] = $id;

            return $id;
        }

        return null;
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = Siswa::with(['kelas.jurusan']);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('nisn', 'like', "%{$search}%")
                  ->orWhere('nama_siswa', 'like', "%{$search}%")
                  ->orWhereHas('kelas', function ($qk) use ($search) {
                      $qk->where('tingkat', 'like', "%{$search}%")
                        ->orWhere('rombel', 'like', "%{$search}%")
                        ->orWhereHas('jurusan', function ($qj) use ($search) {
                            $qj->where('kode_jurusan', 'like', "%{$search}%")
                               ->orWhere('nama_jurusan', 'like', "%{$search}%");
                        });
                  });
            });
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->input('jenis_kelamin'));
        }

        if ($request->filled('tingkat')) {
            $tingkat = $request->input('tingkat');
            $query->whereHas('kelas', function ($q) use ($tingkat) {
                $q->where('tingkat', $tingkat);
            });
        }

        if ($request->filled('id_jurusan')) {
            $idJurusan = $request->input('id_jurusan');
            $query->whereHas('kelas', function ($q) use ($idJurusan) {
                $q->where('id_jurusan', $idJurusan);
            });
        }

        if ($request->filled('rombel')) {
            $rombel = $request->input('rombel');
            $query->whereHas('kelas', function ($q) use ($rombel) {
                $q->where('rombel', $rombel);
            });
        }

        return $query;
    }
}