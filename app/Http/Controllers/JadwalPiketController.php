<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Models\JadwalPiket;
use App\Models\Guru;
use Barryvdh\DomPDF\Facade\Pdf;

class JadwalPiketController extends Controller
{
    private const BULAN = [
        'januari' => 1, 'jan' => 1, 'februari' => 2, 'feb' => 2, 'maret' => 3, 'mar' => 3,
        'april' => 4, 'apr' => 4, 'mei' => 5, 'juni' => 6, 'jun' => 6, 'juli' => 7, 'jul' => 7,
        'agustus' => 8, 'agu' => 8, 'agt' => 8, 'september' => 9, 'sep' => 9, 'oktober' => 10,
        'okt' => 10, 'november' => 11, 'nov' => 11, 'desember' => 12, 'des' => 12,
    ];

    public function __construct()
    {
        Carbon::setLocale('id');
    }

    /** Pastikan tabel & kolom tanggal/peran ada (aman kalau migrate belum dijalankan). */
    private function ensureTableExists()
    {
        if (!Schema::hasTable('jadwal_piket')) {
            Schema::create('jadwal_piket', function (Blueprint $table) {
                $table->integer('id_piket')->autoIncrement();
                $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']);
                $table->date('tanggal')->nullable();
                $table->integer('id_guru');
                $table->string('peran', 50)->nullable();
                $table->string('keterangan')->nullable();
                $table->timestamps();

                $table->index('tanggal');
                $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
            });
            return;
        }

        if (!Schema::hasColumn('jadwal_piket', 'tanggal') || !Schema::hasColumn('jadwal_piket', 'peran')) {
            Schema::table('jadwal_piket', function (Blueprint $table) {
                if (!Schema::hasColumn('jadwal_piket', 'tanggal')) {
                    $table->date('tanggal')->nullable()->after('hari');
                    $table->index('tanggal');
                }
                if (!Schema::hasColumn('jadwal_piket', 'peran')) {
                    $table->string('peran', 50)->nullable()->after('id_guru');
                }
            });
        }
    }

    /** Halaman kelola jadwal piket per minggu (Senin - Jumat), dikelompokkan per peran. */
    public function index(Request $request)
    {
        $this->ensureTableExists();

        $today = Carbon::now('Asia/Jakarta')->startOfDay();
        try {
            $acuan = $request->filled('minggu') ? Carbon::parse($request->query('minggu'), 'Asia/Jakarta') : $today->copy();
        } catch (\Throwable $e) {
            $acuan = $today->copy();
        }
        $senin = $acuan->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();

        $hariList = [];
        for ($i = 0; $i < 5; $i++) {
            $tgl = $senin->copy()->addDays($i);

            $rows = JadwalPiket::with('guru')
                ->berlakuPada($tgl)
                ->orderBy('id_piket')
                ->get();

            $perPeran = [];
            foreach (array_keys(JadwalPiket::PERAN) as $peran) {
                $perPeran[$peran] = $rows->where('peran', $peran)->values();
            }
            // Baris lama tanpa peran / peran tidak dikenal
            $lainnya = $rows->filter(fn ($r) => !array_key_exists((string) $r->peran, JadwalPiket::PERAN))->values();

            $hariList[] = [
                'tanggal'   => $tgl,
                'hari'      => JadwalPiket::namaHari($tgl),
                'is_today'  => $tgl->isSameDay($today),
                'per_peran' => $perPeran,
                'lainnya'   => $lainnya,
                'total'     => $rows->count(),
            ];
        }

        $guruList = Guru::orderBy('nama_guru')->get();
        $totalPetugas = collect($hariList)->sum('total');

        return view('admin.jadwal_piket.index', [
            'hariList'     => $hariList,
            'guruList'     => $guruList,
            'totalPetugas' => $totalPetugas,
            'peranList'    => JadwalPiket::PERAN,
            'senin'        => $senin,
            'jumat'        => $senin->copy()->addDays(4),
            'prevMinggu'   => $senin->copy()->subWeek()->toDateString(),
            'nextMinggu'   => $senin->copy()->addWeek()->toDateString(),
            'importReport' => session('import_report'),
        ]);
    }

    /** Simpan satu penugasan piket (tanggal + peran + guru). */
    public function store(Request $request)
    {
        $this->ensureTableExists();

        $request->validate([
            'tanggal'    => 'required|date',
            'id_guru'    => 'required|exists:guru,id_guru',
            'peran'      => 'required|in:' . implode(',', array_keys(JadwalPiket::PERAN)),
            'keterangan' => 'nullable|string|max:255',
        ], [
            'tanggal.required' => 'Pilih tanggal tugas piket.',
            'id_guru.required' => 'Pilih guru yang bertugas.',
            'id_guru.exists'   => 'Guru yang dipilih tidak valid.',
            'peran.required'   => 'Pilih peran piket.',
            'peran.in'         => 'Peran piket tidak valid.',
        ]);

        $tanggal = Carbon::parse($request->tanggal, 'Asia/Jakarta')->startOfDay();
        if ($tanggal->isWeekend()) {
            return back()->with('error', 'Jadwal piket hanya untuk hari Senin - Jumat.');
        }

        $guru = Guru::findOrFail($request->id_guru);

        if (JadwalPiket::where('tanggal', $tanggal->toDateString())->where('id_guru', $guru->id_guru)->exists()) {
            return back()->with('error', "{$guru->nama_guru} sudah terdaftar piket pada {$tanggal->translatedFormat('l, d F Y')}.");
        }

        JadwalPiket::create([
            'hari'       => JadwalPiket::namaHari($tanggal),
            'tanggal'    => $tanggal->toDateString(),
            'id_guru'    => $guru->id_guru,
            'peran'      => $request->peran,
            'keterangan' => $request->keterangan ?: $request->peran,
        ]);

        $this->syncUserAccountForPiket($guru);

        return redirect()->route('jadwal-piket.index', ['minggu' => $tanggal->toDateString()])
            ->with('success', "Berhasil menambahkan {$guru->nama_guru} sebagai {$request->peran} ({$tanggal->translatedFormat('l, d F Y')}).");
    }

    /**
     * Impor massal dari teks tempel / CSV.
     * Format per baris: TANGGAL <pemisah> PERAN <pemisah> NAMA GURU
     * Pemisah: Tab, titik koma ";" atau garis tegak "|". Aman diulang (yang sudah ada dilewati).
     */
    public function import(Request $request)
    {
        $this->ensureTableExists();

        $request->validate([
            'data'     => 'nullable|string|max:200000',
            'file_csv' => 'nullable|file|mimes:csv,txt|max:2048',
        ], [
            'file_csv.mimes' => 'File harus berformat .csv atau .txt.',
        ]);

        $teks = (string) $request->input('data', '');
        if ($request->hasFile('file_csv')) {
            $teks .= "\n" . file_get_contents($request->file('file_csv')->getRealPath());
        }
        $teks = preg_replace('/^\xEF\xBB\xBF/', '', $teks); // buang BOM

        if (trim($teks) === '') {
            return back()->with('error', 'Data impor kosong. Tempel data atau pilih file CSV.');
        }

        // Indeks nama guru sekali saja
        $exact = [];
        $core = [];
        foreach (Guru::all(['id_guru', 'nama_guru', 'nip']) as $g) {
            $exact[$this->keyNama($g->nama_guru)][] = $g;
            $core[$this->keyCore($g->nama_guru)][] = $g;
        }

        $errors = [];
        $valid = [];   // key "tanggal|id_guru" => row
        $dupInFile = 0;
        $tanggalPertama = null;

        $baris = preg_split('/\r\n|\r|\n/', $teks);
        foreach ($baris as $i => $line) {
            $no = $i + 1;
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (str_contains($line, "\t")) {
                $kolom = explode("\t", $line);
            } elseif (str_contains($line, '|')) {
                $kolom = explode('|', $line);
            } else {
                $kolom = explode(';', $line);
            }
            $kolom = array_map('trim', $kolom);

            // Lewati baris judul
            if (count($kolom) >= 3 && stripos($kolom[0], 'tanggal') !== false && stripos($kolom[2], 'nama') !== false) {
                continue;
            }

            if (count($kolom) < 3) {
                $errors[] = "Baris {$no}: format salah, butuh 3 kolom (tanggal, peran, nama guru) — \"{$line}\"";
                continue;
            }

            [$rawTgl, $rawPeran, $rawNama] = $kolom;

            $tgl = $this->parseTanggal($rawTgl);
            if (!$tgl) {
                $errors[] = "Baris {$no}: tanggal \"{$rawTgl}\" tidak dikenali (contoh: 2026-09-28 atau 28 September 2026).";
                continue;
            }
            if ($tgl->isWeekend()) {
                $errors[] = "Baris {$no}: {$tgl->translatedFormat('l, d F Y')} jatuh di akhir pekan (piket hanya Senin - Jumat).";
                continue;
            }

            $peran = $this->normalisePeran($rawPeran);
            if (!$peran) {
                $errors[] = "Baris {$no}: peran \"{$rawPeran}\" tidak dikenali (pakai: " . implode(', ', array_keys(JadwalPiket::PERAN)) . ').';
                continue;
            }

            $keyExact = $this->keyNama($rawNama);
            $keyCore = $this->keyCore($rawNama);
            $guru = null;
            if (isset($exact[$keyExact])) {
                if (count($exact[$keyExact]) === 1) {
                    $guru = $exact[$keyExact][0];
                } else {
                    $errors[] = "Baris {$no}: nama \"{$rawNama}\" cocok dengan lebih dari satu guru, lengkapi nama/gelarnya.";
                    continue;
                }
            } elseif (isset($core[$keyCore]) && $keyCore !== '') {
                if (count($core[$keyCore]) === 1) {
                    $guru = $core[$keyCore][0];
                } else {
                    $errors[] = "Baris {$no}: nama \"{$rawNama}\" cocok dengan lebih dari satu guru, lengkapi nama/gelarnya.";
                    continue;
                }
            }

            if (!$guru) {
                $errors[] = "Baris {$no}: guru \"{$rawNama}\" tidak ditemukan di Data Guru — tambahkan dulu lewat menu Data Guru.";
                continue;
            }

            $key = $tgl->toDateString() . '|' . $guru->id_guru;
            if (isset($valid[$key])) {
                $dupInFile++;
                continue;
            }

            $valid[$key] = [
                'tanggal' => $tgl,
                'guru'    => $guru,
                'peran'   => $peran,
            ];
            $tanggalPertama = $tanggalPertama && $tanggalPertama->lte($tgl) ? $tanggalPertama : $tgl;
        }

        $tambah = 0;
        $sudahAda = 0;

        DB::transaction(function () use ($valid, &$tambah, &$sudahAda) {
            foreach ($valid as $row) {
                $tgl = $row['tanggal'];
                $guru = $row['guru'];

                $ada = JadwalPiket::where('tanggal', $tgl->toDateString())
                    ->where('id_guru', $guru->id_guru)
                    ->exists();
                if ($ada) {
                    $sudahAda++;
                    continue;
                }

                JadwalPiket::create([
                    'hari'       => JadwalPiket::namaHari($tgl),
                    'tanggal'    => $tgl->toDateString(),
                    'id_guru'    => $guru->id_guru,
                    'peran'      => $row['peran'],
                    'keterangan' => $row['peran'],
                ]);
                $this->syncUserAccountForPiket($guru);
                $tambah++;
            }
        });

        session()->flash('import_report', [
            'tambah'    => $tambah,
            'sudah_ada' => $sudahAda + $dupInFile,
            'errors'    => $errors,
        ]);

        $redirect = redirect()->route('jadwal-piket.index', $tanggalPertama ? ['minggu' => $tanggalPertama->toDateString()] : []);

        if ($tambah > 0 && empty($errors)) {
            return $redirect->with('success', "Impor selesai: {$tambah} penugasan piket ditambahkan.");
        }
        if ($tambah > 0) {
            return $redirect->with('success', "{$tambah} penugasan ditambahkan, tetapi " . count($errors) . ' baris gagal (lihat rincian di bawah).');
        }

        return $redirect->with('error', empty($errors)
            ? 'Tidak ada penugasan baru — semua data sudah ada sebelumnya.'
            : 'Tidak ada penugasan yang ditambahkan. Periksa rincian error di bawah.');
    }

    /**
     * Ekspor jadwal ke CSV.
     * ?minggu=YYYY-MM-DD -> hanya minggu itu (Senin-Jumat), termasuk jadwal mingguan lama yang berlaku.
     * ?semua=1           -> seluruh data di tabel jadwal_piket, urut tanggal lalu peran.
     */

    public function exportPdf(Request $request, string $shift)
{
    $this->ensureTableExists();

    $peranShift = [
        'pagi' => ['Petugas KBM Pagi', 'Koordinator KBM Pagi'],
        'siang' => ['Petugas KBM Siang', 'Koordinator KBM Siang'],
        'waka' => ['Piket Waka'],
    ];

    abort_unless(isset($peranShift[$shift]), 404);

    try {
        $acuan = $request->filled('minggu')
            ? Carbon::parse($request->query('minggu'), 'Asia/Jakarta')
            : Carbon::now('Asia/Jakarta');
    } catch (\Throwable $e) {
        $acuan = Carbon::now('Asia/Jakarta');
    }

    $senin = $acuan->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();
    $jumat = $senin->copy()->addDays(4);
    $rows = collect();

    for ($i = 0; $i < 5; $i++) {
        $tgl = $senin->copy()->addDays($i);

        $harian = JadwalPiket::with('guru')
            ->berlakuPada($tgl)
            ->whereIn('peran', $peranShift[$shift])
            ->get()
            ->map(function ($r) use ($tgl) {
                $r->tanggal_tampil = $tgl->copy();
                return $r;
            });

        $rows = $rows->merge($harian);
    }

    $rows = $rows->sortBy(function ($r) use ($peranShift, $shift) {
        return $r->tanggal_tampil->format('Y-m-d')
            . '-' . array_search($r->peran, $peranShift[$shift]);
    })->values();

    $judulShift = [
        'pagi' => 'Piket Pagi',
        'siang' => 'Piket Siang',
        'waka' => 'Piket Waka',
    ][$shift];

    $pdf = Pdf::loadView('admin.jadwal_piket.pdf', [
        'rows' => $rows,
        'senin' => $senin,
        'jumat' => $jumat,
        'judulShift' => $judulShift,
    ])->setPaper('a4', 'landscape');

    return $pdf->download(
        'jadwal-' . $shift . '-' . $senin->format('Y-m-d') . '.pdf'
    );
}

    public function exportCsv(Request $request)
    {
        $this->ensureTableExists();

        $urutanPeran = array_flip(array_keys(JadwalPiket::PERAN));

        if ($request->boolean('semua')) {
            $rows = JadwalPiket::with('guru')->get()->map(function ($r) {
                $r->tanggal_tampil = $r->tanggal;
                return $r;
            });
            $filename = 'jadwal-piket-semua-' . Carbon::now('Asia/Jakarta')->format('Y-m-d') . '.csv';
        } else {
            $today = Carbon::now('Asia/Jakarta')->startOfDay();
            try {
                $acuan = $request->filled('minggu') ? Carbon::parse($request->query('minggu'), 'Asia/Jakarta') : $today->copy();
            } catch (\Throwable $e) {
                $acuan = $today->copy();
            }
            $senin = $acuan->copy()->startOfWeek(Carbon::MONDAY)->startOfDay();

            $rows = collect();
            for ($i = 0; $i < 5; $i++) {
                $tgl = $senin->copy()->addDays($i);
                $rows = $rows->merge(
                    JadwalPiket::with('guru')->berlakuPada($tgl)->get()->map(function ($r) use ($tgl) {
                        $r->tanggal_tampil = $tgl;
                        return $r;
                    })
                );
            }

            $filename = 'jadwal-piket-' . $senin->format('Y-m-d') . '-sd-' . $senin->copy()->addDays(4)->format('Y-m-d') . '.csv';
        }

        $sorted = $rows->sort(function ($a, $b) use ($urutanPeran) {
            $ta = $a->tanggal_tampil;
            $tb = $b->tanggal_tampil;
            if ($ta && $tb) {
                $cmp = $ta->timestamp <=> $tb->timestamp;
            } else {
                $cmp = $ta ? -1 : ($tb ? 1 : 0);
            }
            if ($cmp !== 0) {
                return $cmp;
            }
            $pa = $urutanPeran[$a->peran] ?? 99;
            $pb = $urutanPeran[$b->peran] ?? 99;
            return $pa <=> $pb;
        })->values();

        $csvRows = $sorted->map(function ($r) {
            $tgl = $r->tanggal_tampil;
            return [
                $tgl ? $tgl->translatedFormat('l, d F Y') : ('Mingguan: ' . $r->hari),
                $r->hari,
                $r->peran ?? '-',
                optional($r->guru)->nama_guru ?? '-',
                optional($r->guru)->nip ?? '-',
                $r->keterangan ?? '-',
            ];
        });

        // Delimiter titik koma supaya kolom terpisah rapi saat dibuka di Excel
        // dengan region Indonesia (pemisah daftar default ";", bukan ",").
        return response()->streamDownload(function () use ($csvRows) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8
            fputcsv($handle, ['Tanggal', 'Hari', 'Peran', 'Nama Guru', 'NIP', 'Keterangan'], ';', '"', '\\');
            foreach ($csvRows as $row) {
                fputcsv($handle, $row, ';', '"', '\\');
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /** Hapus penugasan piket. */
    /** Ubah penugasan piket (guru, peran, keterangan). Tanggal tidak diubah. */
    public function update(Request $request, $id)
    {
        $this->ensureTableExists();

        $jadwal = JadwalPiket::findOrFail($id);

        $request->validate([
            'id_guru'    => 'required|exists:guru,id_guru',
            'peran'      => 'required|in:' . implode(',', array_keys(JadwalPiket::PERAN)),
            'keterangan' => 'nullable|string|max:255',
        ], [
            'id_guru.required' => 'Pilih guru yang bertugas.',
            'id_guru.exists'   => 'Guru yang dipilih tidak valid.',
            'peran.required'   => 'Pilih peran piket.',
            'peran.in'         => 'Peran piket tidak valid.',
        ]);

        $guru = Guru::findOrFail($request->id_guru);
        $tanggal = $jadwal->tanggal;

        if ($tanggal && JadwalPiket::where('tanggal', $tanggal->toDateString())
            ->where('id_guru', $guru->id_guru)
            ->where('id_piket', '!=', $jadwal->id_piket)
            ->exists()) {
            return back()->with('error', "{$guru->nama_guru} sudah terdaftar piket pada {$tanggal->translatedFormat('l, d F Y')}.");
        }

        $jadwal->update([
            'id_guru'    => $guru->id_guru,
            'peran'      => $request->peran,
            'keterangan' => $request->keterangan ?: $request->peran,
        ]);

        $this->syncUserAccountForPiket($guru);

        $label = $tanggal ? $tanggal->translatedFormat('l, d F Y') : "hari {$jadwal->hari}";

        return redirect()->route('jadwal-piket.index', $tanggal ? ['minggu' => $tanggal->toDateString()] : [])
            ->with('success', "Penugasan piket {$guru->nama_guru} ({$request->peran}) pada {$label} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $this->ensureTableExists();

        $jadwal = JadwalPiket::with('guru')->findOrFail($id);
        $namaGuru = optional($jadwal->guru)->nama_guru ?? 'Guru';
        $tanggal = $jadwal->tanggal;
        $label = $tanggal ? $tanggal->translatedFormat('l, d F Y') : "hari {$jadwal->hari}";

        $jadwal->delete();

        return redirect()->route('jadwal-piket.index', $tanggal ? ['minggu' => $tanggal->toDateString()] : [])
            ->with('success', "Penugasan piket {$namaGuru} pada {$label} telah dihapus.");
    }

    /** Pastikan guru yang ditugaskan piket punya akun login (username & password default = NIP). */
    private function syncUserAccountForPiket($guru)
    {
        $user = \App\Models\User::where('id_guru', $guru->id_guru)->first();

        if (!$user) {
            $kredensial = $guru->nip ?: ('guru' . $guru->id_guru);
            \App\Models\User::create([
                'name'     => $guru->nama_guru,
                'username' => $kredensial,
                'password' => \Illuminate\Support\Facades\Hash::make($kredensial),
                'role'     => 'guru_mengajar',
                'id_guru'  => $guru->id_guru,
            ]);
        }
    }

    // ------------------------------------------------------------ helper impor

    /** Kunci pencocokan nama: huruf kecil, tanpa tanda baca, spasi rapat. */
    private function keyNama(string $nama): string
    {
        $s = mb_strtolower($nama);
        $s = str_replace(["'", '’', '`'], '', $s);
        $s = preg_replace('/[^\p{L}\p{N}]+/u', ' ', $s);
        return trim(preg_replace('/\s+/', ' ', $s));
    }

    /** Nama inti tanpa gelar belakang (setelah koma) dan gelar depan (Dra., Drs., dst). */
    private function keyCore(string $nama): string
    {
        $inti = explode(',', $nama)[0];
        $inti = preg_replace('/^\s*(dra|drs|dr|ir|hj|h|prof)\.?\s+/iu', '', $inti);
        return $this->keyNama($inti);
    }

    private function normalisePeran(string $raw): ?string
    {
        $s = mb_strtolower(trim($raw));
        if ($s === '') {
            return null;
        }
        if (str_contains($s, 'waka')) {
            return 'Piket Waka';
        }

        $pagi = str_contains($s, 'pagi');
        $siang = str_contains($s, 'siang');
        if ($pagi === $siang) {
            return null; // dua-duanya atau tidak ada -> ambigu
        }

        $koordinator = str_contains($s, 'koor');
        $sesi = $pagi ? 'Pagi' : 'Siang';

        return ($koordinator ? 'Koordinator' : 'Petugas') . " KBM {$sesi}";
    }

    /** Terima: 2026-09-28, 28/09/2026, "Senin, 28 September 2026", "28 Sep 2026". */
    private function parseTanggal(string $raw): ?Carbon
    {
        $s = trim(preg_replace('/\s+/u', ' ', $raw));
        $s = preg_replace('/^(senin|selasa|rabu|kamis|jum[\'’`]?at|sabtu|minggu)\s*,?\s*/iu', '', $s);
        if ($s === '') {
            return null;
        }

        $y = $m = $d = null;

        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $s, $x)) {
            [$y, $m, $d] = [(int) $x[1], (int) $x[2], (int) $x[3]];
        } elseif (preg_match('/^(\d{1,2})[\/\-.](\d{1,2})[\/\-.](\d{4})$/', $s, $x)) {
            [$d, $m, $y] = [(int) $x[1], (int) $x[2], (int) $x[3]];
        } elseif (preg_match('/^(\d{1,2})\s+([a-z]+)\.?(?:\s+(\d{4}))?$/iu', $s, $x)) {
            $bulan = self::BULAN[mb_strtolower($x[2])] ?? null;
            if (!$bulan) {
                return null;
            }
            [$d, $m] = [(int) $x[1], $bulan];
            $y = isset($x[3]) && $x[3] !== '' ? (int) $x[3] : (int) Carbon::now('Asia/Jakarta')->year;
        } else {
            return null;
        }

        if (!checkdate($m, $d, $y)) {
            return null;
        }

        return Carbon::create($y, $m, $d, 0, 0, 0, 'Asia/Jakarta');
    }
}