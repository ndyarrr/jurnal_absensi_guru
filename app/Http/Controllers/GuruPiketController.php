<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\DetailKetidakhadiran;
use App\Models\JurnalMengajar;
use App\Models\PermohonanIzin;
use App\Models\SuratDispensasi;
use App\Support\CsvExporter;

class GuruPiketController extends Controller
{
    /**
     * Ensure dummy data exists for Piket digital inbox if table is empty.
     */
    private function ensureDataExist()
    {
        $todayStr = Carbon::now('Asia/Jakarta')->toDateString();

        // Ensure at least one kelas & student exist
        $kelas = Kelas::first();

        if (!$kelas) {
            $kelas = Kelas::create([
                'tingkat' => 'XI',
                'rombel' => 1,
                'wali_kelas' => 'Aily Cantika, S.Pd',
                'jumlah_siswa' => 32,
            ]);
        }

        $siswaList = Siswa::where('id_kelas', $kelas->id_kelas)->get();

        if ($siswaList->isEmpty()) {
            $defaultNames = [
                'Azzura Atasya',
                'Felix Fernandez',
                'Megan Fernita',
                'Bella Sutanto',
                'Canva Narendra',
                'Ilona Lovita',
            ];

            foreach ($defaultNames as $idx => $name) {
                Siswa::create([
                    'nisn' => '0056789'
                        . str_pad($kelas->id_kelas, 2, '0', STR_PAD_LEFT)
                        . str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
                    'nama_siswa' => $name,
                    'id_kelas' => $kelas->id_kelas,
                ]);
            }

            $siswaList = Siswa::where('id_kelas', $kelas->id_kelas)->get();
        }

        // Seed sample SuratDispensasi if empty
        if (SuratDispensasi::count() === 0 && $siswaList->isNotEmpty()) {
            $sampleDispen = [
                [
                    'nomor_surat' => 'DISPEN/2026/08/001',
                    'siswa' => $siswaList[0],
                    'kegiatan' => 'Lomba O2SN Tingkat Kota (Futsal)',
                    'lokasi' => 'GOR Tri Dharma',
                    'tanggal_mulai' => $todayStr,
                    'tanggal_selesai' => $todayStr,
                    'jam_mulai' => '08:00',
                    'jam_selesai' => '14:00',
                    'alasan' => 'Mewakili sekolah dalam Kejuaraan O2SN 2026',
                    'status' => 'disetujui',
                ],
                [
                    'nomor_surat' => 'DISPEN/2026/08/002',
                    'siswa' => $siswaList[1] ?? $siswaList[0],
                    'kegiatan' => 'Olimpiade Sains Nasional (OSN) Kebumian',
                    'lokasi' => 'SMA Negeri 1 Kota',
                    'tanggal_mulai' => $todayStr,
                    'tanggal_selesai' => $todayStr,
                    'jam_mulai' => '07:30',
                    'jam_selesai' => '12:00',
                    'alasan' => 'Mengikuti babak final OSN Kebumian',
                    'status' => 'disetujui',
                ],
            ];

            foreach ($sampleDispen as $sd) {
                SuratDispensasi::create([
                    'nomor_surat' => $sd['nomor_surat'],
                    'tipe_pemohon' => 'siswa',
                    'id_siswa' => $sd['siswa']->id_siswa,
                    'id_kelas' => $sd['siswa']->id_kelas,
                    'nama_kegiatan' => $sd['kegiatan'],
                    'lokasi_kegiatan' => $sd['lokasi'],
                    'tanggal_mulai' => $sd['tanggal_mulai'],
                    'tanggal_selesai' => $sd['tanggal_selesai'],
                    'jam_mulai' => $sd['jam_mulai'],
                    'jam_selesai' => $sd['jam_selesai'],
                    'alasan_dispensasi' => $sd['alasan'],
                    'status_approval' => $sd['status'],
                    'barcode_token' => (string) Str::uuid(),
                ]);
            }
        }

        // Seed sample PermohonanIzin
        if (PermohonanIzin::count() === 0 && $siswaList->isNotEmpty()) {
            $sampleIzin = [
                [
                    'siswa' => $siswaList[2] ?? $siswaList[0],
                    'jenis' => 'Sakit',
                    'alasan' => 'Surat dokter / orang tua fisik diserahkan ke meja piket',
                    'status' => 'approved_piket',
                    'bukti' => 'sample_surat_ortu_sakit.jpg',
                    'mulai' => $todayStr,
                    'selesai' => $todayStr,
                ],
                [
                    'siswa' => $siswaList[3] ?? $siswaList[0],
                    'jenis' => 'Izin',
                    'alasan' => 'Surat izin orang tua fisik diserahkan ke meja piket',
                    'status' => 'approved_piket',
                    'bukti' => 'sample_surat_ortu_izin.jpg',
                    'mulai' => $todayStr,
                    'selesai' => $todayStr,
                ],
            ];

            foreach ($sampleIzin as $si) {
                PermohonanIzin::create([
                    'tipe_pemohon' => 'siswa',
                    'id_siswa' => $si['siswa']->id_siswa,
                    'jenis_izin' => $si['jenis'],
                    'tanggal_mulai' => $si['mulai'],
                    'tanggal_selesai' => $si['selesai'],
                    'alasan' => $si['alasan'],
                    'bukti_surat' => $si['bukti'],
                    'status' => $si['status'],
                    'created_at' => Carbon::now('Asia/Jakarta')->subHours(2),
                ]);
            }
        }
    }

    /**
     * Dashboard Utama Guru Piket.
     */
    public function dashboard(Request $request)
    {
        $this->ensureDataExist();

        $user = auth()->user();
        $guru = $user ? $user->guru : null;

        $namaGuruPiket = $guru
            ? $guru->nama_guru
            : ($user->name ?? 'Guru Piket Hari Ini');

        Carbon::setLocale('id');

        $todayStr = Carbon::now('Asia/Jakarta')->toDateString();

        $todayFormatted = Carbon::now('Asia/Jakarta')
            ->translatedFormat('l, d F Y');

        $suratMasukHariIni =
            PermohonanIzin::whereDate('created_at', $todayStr)->count()
            + SuratDispensasi::whereDate('created_at', $todayStr)->count();

        $totalDispensasiCount = SuratDispensasi::count();

        $dispenDisetujuiCount = SuratDispensasi::where(
            'status_approval',
            'disetujui'
        )->count();

        $siswaIzinSakitCount = PermohonanIzin::whereDate(
            'tanggal_mulai',
            '<=',
            $todayStr
        )
            ->whereDate('tanggal_selesai', '>=', $todayStr)
            ->count();

        $recentPermohonan = PermohonanIzin::with([
            'siswa.kelas.jurusan'
        ])
            ->orderBy('id_permohonan', 'desc')
            ->take(6)
            ->get();

        $recentDispensasi = SuratDispensasi::with([
            'siswa.kelas.jurusan',
            'siswaList.siswa.kelas.jurusan',
        ])
            ->orderBy('id_dispen', 'desc')
            ->take(6)
            ->get();

        return view('guru_piket.dashboard', compact(
            'user',
            'namaGuruPiket',
            'todayFormatted',
            'suratMasukHariIni',
            'totalDispensasiCount',
            'dispenDisetujuiCount',
            'siswaIzinSakitCount',
            'recentPermohonan',
            'recentDispensasi'
        ));
    }

    /**
     * Check apakah guru sedang bertugas sebagai Guru Piket hari ini.
     */
    private function isTeacherDutyToday($user): bool
    {
        if (!$user) {
            return false;
        }

        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }

        $dayMap = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        $englishDay = Carbon::now('Asia/Jakarta')->format('l');
        $todayName = $dayMap[$englishDay] ?? 'Senin';

        $idGuru = $user->id_guru;

        if (!$idGuru && $user->guru) {
            $idGuru = $user->guru->id_guru;
        }

        if (!$idGuru && !empty($user->name)) {
            $matchedGuru = Guru::where('nama_guru', $user->name)
                ->orWhere('nama_guru', 'like', '%' . $user->name . '%')
                ->first();

            if ($matchedGuru) {
                $idGuru = $matchedGuru->id_guru;
            }
        }

        if (
            !\Illuminate\Support\Facades\Schema::hasTable('jadwal_piket')
            || \App\Models\JadwalPiket::count() === 0
        ) {
            return true;
        }

        if ($idGuru) {
            return \App\Models\JadwalPiket::bertugasHariIni()
                ->where('id_guru', $idGuru)
                ->exists();
        }

        return false;
    }

    /**
     * Form Input Surat Izin / Sakit.
     */
    public function inputSuratIzin(Request $request)
    {
        $this->ensureDataExist();

        $user = auth()->user();
        $guru = $user ? $user->guru : null;

        $namaGuruPiket = $guru
            ? $guru->nama_guru
            : ($user->name ?? 'Guru Piket Hari Ini');

        Carbon::setLocale('id');

        $todayName = Carbon::now('Asia/Jakarta')
            ->translatedFormat('l');

        $isDutyToday = $this->isTeacherDutyToday($user);

        $kelasList = Kelas::with('jurusan')
            ->orderBy('tingkat')
            ->orderBy('rombel')
            ->get();

        $siswaList = Siswa::with('kelas.jurusan')
            ->orderBy('nama_siswa')
            ->get();

        return view(
            'guru_piket.input_surat',
            compact(
                'user',
                'namaGuruPiket',
                'kelasList',
                'siswaList',
                'isDutyToday',
                'todayName'
            )
        );
    }

    /**
     * Simpan Surat Izin / Sakit.
     */
    public function storeSuratIzin(Request $request)
    {
        $user = auth()->user();

        Carbon::setLocale('id');

        $todayName = Carbon::now('Asia/Jakarta')
            ->translatedFormat('l');

        if (!$this->isTeacherDutyToday($user)) {
            return back()->with(
                'error',
                "Akses Ditolak: Anda tidak terdaftar sebagai Guru Piket bertugas untuk hari {$todayName}."
            );
        }

        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'jenis_izin' => 'required|in:Sakit,Izin',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'foto_surat' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $siswa = Siswa::with('kelas')
            ->findOrFail($request->id_siswa);

        $fotoPath = null;

        if ($request->hasFile('foto_surat')) {
            $fotoPath = $request
                ->file('foto_surat')
                ->store('bukti_surat', 'public');
        }

        $autoAlasan =
            "Surat {$request->jenis_izin} fisik diserahkan ke meja piket";

        PermohonanIzin::create([
            'tipe_pemohon' => 'siswa',
            'id_siswa' => $siswa->id_siswa,
            'jenis_izin' => $request->jenis_izin,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'alasan' => $autoAlasan,
            'bukti_surat' => $fotoPath ?? 'foto_surat_piket_default.jpg',
            'status' => 'approved_piket',
            'created_at' => Carbon::now('Asia/Jakarta'),
        ]);

        $todayStr = Carbon::now('Asia/Jakarta')->toDateString();

        if (
            $request->tanggal_mulai <= $todayStr
            && $request->tanggal_selesai >= $todayStr
        ) {
            $jurnalsToday = JurnalMengajar::whereDate(
                'tanggal',
                $todayStr
            )
                ->whereHas('jadwal', function ($q) use ($siswa) {
                    $q->where('id_kelas', $siswa->id_kelas);
                })
                ->get();

            $idPiket = $user->id_guru
                ?: optional($user->guru)->id_guru;

            if (!$idPiket && !empty($user->name)) {
                $matchedGuru = Guru::where(
                    'nama_guru',
                    $user->name
                )->first();

                if ($matchedGuru) {
                    $idPiket = $matchedGuru->id_guru;
                }
            }

            foreach ($jurnalsToday as $jurnal) {
                DetailKetidakhadiran::updateOrCreate(
                    [
                        'id_jurnal' => $jurnal->id_jurnal,
                        'id_siswa' => $siswa->id_siswa,
                    ],
                    [
                        'status' => strtolower($request->jenis_izin),
                        'kategori' =>
                            strtolower($request->jenis_izin) === 'sakit'
                                ? 'sakit'
                                : 'izin_ortu',
                        'bukti_surat' => $fotoPath,
                        'catatan' => '[Guru Piket] ' . $autoAlasan,
                        'id_guru_piket' => $idPiket,
                        'waktu_input' => Carbon::now('Asia/Jakarta'),
                    ]
                );
            }
        }

        return redirect()
            ->route('guru-piket.digital-surat')
            ->with(
                'success',
                "Surat {$request->jenis_izin} digital untuk {$siswa->nama_siswa} berhasil di-input dan terdaftar di rekap absensi kelas secara langsung!"
            );
    }

    /**
     * Generate nomor surat dispensasi unik.
     */
    private function generateUniqueNomorSurat(): string
    {
        $prefix = 'DISPEN/' . date('Y/m/');

        $latest = SuratDispensasi::withTrashed()
            ->where('nomor_surat', 'like', $prefix . '%')
            ->orderBy('id_dispen', 'desc')
            ->first();

        $seq = 1;

        if ($latest) {
            $parts = explode('/', $latest->nomor_surat);
            $lastNum = (int) end($parts);
            $seq = max(1, $lastNum + 1);
        }

        do {
            $nomorSurat = $prefix . str_pad(
                $seq,
                3,
                '0',
                STR_PAD_LEFT
            );

            $exists = SuratDispensasi::withTrashed()
                ->where('nomor_surat', $nomorSurat)
                ->exists();

            if ($exists) {
                $seq++;
            }
        } while ($exists);

        return $nomorSurat;
    }

    /**
     * Form Input / Edit Dispensasi Siswa.
     */
    public function inputDispensasi(Request $request)
    {
        $this->ensureDataExist();

        $user = auth()->user();
        $guru = $user ? $user->guru : null;

        $namaGuruPiket = $guru
            ? $guru->nama_guru
            : ($user->name ?? 'Guru Piket Hari Ini');

        Carbon::setLocale('id');

        $todayName = Carbon::now('Asia/Jakarta')
            ->translatedFormat('l');

        $isDutyToday = $this->isTeacherDutyToday($user);

        $kelasList = Kelas::with('jurusan')
            ->orderBy('tingkat')
            ->orderBy('rombel')
            ->get();

        $siswaList = Siswa::with('kelas.jurusan')
            ->orderBy('nama_siswa')
            ->get();

        $surat = null;
        $isVerified = false;

        if ($request->filled('id')) {
            $surat = SuratDispensasi::with([
                'siswa.kelas.jurusan',
                'siswaList.siswa.kelas.jurusan',
            ])->find($request->input('id'));

            if ($surat) {
                $isVerified =
                    $surat->status_approval === 'disetujui';
            }
        }

        if ($surat) {
            $autoNomorSurat = $surat->nomor_surat;
        } else {
            $autoNomorSurat = $this->generateUniqueNomorSurat();
        }

        return view(
            'guru_piket.input_dispensasi',
            compact(
                'user',
                'namaGuruPiket',
                'kelasList',
                'siswaList',
                'autoNomorSurat',
                'isDutyToday',
                'todayName',
                'surat',
                'isVerified'
            )
        );
    }

    /**
     * Simpan Dispensasi Siswa beserta TTD Digital.
     *
     * 1 kelas = 1 surat
     * 1 surat bisa memiliki banyak siswa
     */
    public function storeDispensasi(Request $request)
    {
        $user = auth()->user();

        Carbon::setLocale('id');

        $todayName = Carbon::now('Asia/Jakarta')
            ->translatedFormat('l');

        if (!$this->isTeacherDutyToday($user)) {
            return back()->with(
                'error',
                "Akses Ditolak: Anda tidak terdaftar sebagai Guru Piket bertugas untuk hari {$todayName}."
            );
        }

        // Block edit jika surat sudah disetujui
        if ($request->filled('id_dispen')) {
            $existing = SuratDispensasi::find(
                $request->input('id_dispen')
            );

            if (
                $existing
                && $existing->status_approval === 'disetujui'
            ) {
                return back()->with(
                    'error',
                    'Perubahan Ditolak: Surat dispensasi yang telah terverifikasi & disetujui tidak dapat diubah kembali.'
                );
            }
        }

        $request->validate([
            'id_siswa' => 'required|array|min:1',
            'id_siswa.*' => 'required|exists:siswa,id_siswa',
            'nama_kegiatan' => 'required|string|max:255',
            'lokasi_kegiatan' => 'nullable|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'alasan_dispensasi' => 'required|string|max:500',
            'file_surat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'ttd_siswa_data' => 'nullable|string',
            'ttd_guru_data' => 'nullable|string',
        ]);

        $siswaList = Siswa::with('kelas')
            ->whereIn('id_siswa', $request->id_siswa)
            ->get();

        if ($siswaList->isEmpty()) {
            return back()->with(
                'error',
                'Belum ada siswa yang dipilih.'
            );
        }

        $existingDispen = $request->filled('id_dispen')
            ? SuratDispensasi::find($request->input('id_dispen'))
            : null;

        $filePath = $existingDispen
            ? $existingDispen->file_surat
            : null;

        if ($request->input('hapus_file_surat') == '1') {
            if (
                $existingDispen
                && $existingDispen->file_surat
                && Storage::disk('public')->exists(
                    $existingDispen->file_surat
                )
            ) {
                Storage::disk('public')->delete(
                    $existingDispen->file_surat
                );
            }

            $filePath = null;
        }

        if ($request->hasFile('file_surat')) {
            if (
                $existingDispen
                && $existingDispen->file_surat
                && Storage::disk('public')->exists(
                    $existingDispen->file_surat
                )
            ) {
                Storage::disk('public')->delete(
                    $existingDispen->file_surat
                );
            }

            $filePath = $request
                ->file('file_surat')
                ->store('surat_dispensasi', 'public');
        }

        $guru = $user ? $user->guru : null;

        $namaGuruPiket = $guru
            ? $guru->nama_guru
            : ($user->name ?? 'Guru Piket');

        // 1 kelas = 1 surat
        foreach (
            $siswaList->groupBy('id_kelas')
            as $idKelas => $siswaGroup
        ) {
            $nomorSurat = $this->generateUniqueNomorSurat();

            $dispen = SuratDispensasi::create([
                'nomor_surat' => $nomorSurat,
                'tipe_pemohon' => 'siswa',

                // Multi siswa menggunakan tabel penghubung
                'id_siswa' => null,

                'id_kelas' => $idKelas,
                'nama_kegiatan' => $request->nama_kegiatan,
                'lokasi_kegiatan' =>
                    $request->lokasi_kegiatan
                    ?? 'Lingkungan Sekolah/Luar',

                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'alasan_dispensasi' =>
                    $request->alasan_dispensasi,

                'file_surat' => $filePath,

                'status_approval' => 'disetujui',
                'disetujui_oleh' => $user->id,
                'barcode_token' => (string) Str::uuid(),
                'created_at' => Carbon::now('Asia/Jakarta'),
            ]);

            // Simpan semua siswa ke tabel penghubung
            foreach ($siswaGroup as $siswa) {
                \App\Models\SuratDispensasiSiswa::create([
                    'id_dispen' => $dispen->id_dispen,
                    'id_siswa' => $siswa->id_siswa,
                ]);
            }

            /*
             * TTD SISWA
             *
             * Satu surat = satu tempat tanda tangan.
             * Nama yang ditampilkan:
             * Muhammad, Budi, Citra
             */
            if (
                $siswaGroup->isNotEmpty()
                && $request->filled('ttd_siswa_data')
            ) {
                $namaDepanSemua = $siswaGroup
                    ->map(function ($siswa) {
                        $namaLengkap = trim(
                            $siswa->nama_siswa
                        );

                        return explode(
                            ' ',
                            $namaLengkap
                        )[0];
                    })
                    ->filter()
                    ->implode(', ');

                $ttdData = $request->ttd_siswa_data;

                if (
                    str_starts_with(
                        $ttdData,
                        'data:image/png;base64,'
                    )
                ) {
                    $parts = explode(',', $ttdData, 2);

                    if (count($parts) === 2) {
                        $imageData = base64_decode(
                            $parts[1],
                            true
                        );

                        if (
                            $imageData !== false
                            && strlen($imageData) > 100
                        ) {
                            $fileName =
                                'ttd_surat_dispensasi/siswa_'
                                . $dispen->id_dispen
                                . '_'
                                . Carbon::now('Asia/Jakarta')
                                    ->format('Ymd_His')
                                . '.png';

                            Storage::disk('public')->put(
                                $fileName,
                                $imageData
                            );

                            $dispen->update([
                                'ttd_siswa_path' => $fileName,
                                'ttd_siswa_signed_at' =>
                                    Carbon::now('Asia/Jakarta'),
                                'ttd_siswa_signed_name' =>
                                    $namaDepanSemua,
                            ]);
                        }
                    }
                }
            }

            // TTD GURU
            if ($request->filled('ttd_guru_data')) {
                $base64Guru = preg_replace(
                    '/^data:image\/png;base64,/',
                    '',
                    $request->input('ttd_guru_data')
                );

                $binaryGuru = base64_decode(
                    $base64Guru,
                    true
                );

                if (
                    $binaryGuru !== false
                    && strlen($binaryGuru) > 100
                ) {
                    $filenameGuru =
                        'ttd_surat_dispensasi/guru_'
                        . $dispen->id_dispen
                        . '_'
                        . Carbon::now('Asia/Jakarta')
                            ->format('Ymd_His')
                        . '.png';

                    Storage::disk('public')->put(
                        $filenameGuru,
                        $binaryGuru
                    );

                    $dispen->update([
                        'ttd_guru_path' => $filenameGuru,
                        'ttd_guru_signed_at' =>
                            Carbon::now('Asia/Jakarta'),
                        'ttd_guru_signed_name' =>
                            $namaGuruPiket,
                    ]);
                }
            }

            // Notifikasi satpam
            $this->kirimPengumumanKeSatpam(
                $dispen,
                $siswaGroup->first()
            );
        }

        return redirect()
            ->route('guru-piket.digital-surat')
            ->with(
                'success',
                "{$siswaList->count()} siswa berhasil dibuatkan surat dispensasi ({$request->nama_kegiatan}) dan terverifikasi!"
            );
    }

    /**
     * Halaman Digitalisasi Surat Piket.
     */
    public function digitalisasiSurat(Request $request)
    {
        $this->ensureDataExist();

        $user = auth()->user();
        $guru = $user ? $user->guru : null;

        $namaGuruPiket = $guru
            ? $guru->nama_guru
            : ($user->name ?? 'Guru Piket Hari Ini');

        // Permohonan izin
        $permQuery = PermohonanIzin::with([
            'siswa.kelas.jurusan'
        ]);

        if ($request->filled('search')) {
            $search = $request->input('search');

            $permQuery->whereHas(
                'siswa',
                function ($q) use ($search) {
                    $q->where(
                        'nama_siswa',
                        'like',
                        "%{$search}%"
                    );
                }
            );
        }

        if ($request->filled('jenis')) {
            $permQuery->where(
                'jenis_izin',
                'like',
                '%' . $request->input('jenis') . '%'
            );
        }

        $permohonanList = $permQuery
            ->orderBy('id_permohonan', 'desc')
            ->get();

        // Surat dispensasi
        $dispenQuery = SuratDispensasi::with([
            'siswa.kelas.jurusan',
            'siswaList.siswa.kelas.jurusan',
        ]);

        if ($request->filled('search')) {
            $search = $request->input('search');

            $dispenQuery->where(function ($q) use ($search) {
                $q->whereHas(
                    'siswa',
                    function ($sq) use ($search) {
                        $sq->where(
                            'nama_siswa',
                            'like',
                            "%{$search}%"
                        );
                    }
                )
                    ->orWhereHas(
                        'siswaList.siswa',
                        function ($sq) use ($search) {
                            $sq->where(
                                'nama_siswa',
                                'like',
                                "%{$search}%"
                            );
                        }
                    )
                    ->orWhere(
                        'nama_kegiatan',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        $dispensasiList = $dispenQuery
            ->orderBy('id_dispen', 'desc')
            ->get();

        // Surat izin masuk
        $suratMasukQuery =
            \App\Models\SuratIzinMasuk::with([
                'siswa.kelas.jurusan'
            ]);

        if ($request->filled('search')) {
            $search = $request->input('search');

            $suratMasukQuery->where(function ($q) use ($search) {
                $q->where(
                    'nama_siswa',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'kelas_str',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'nomor_surat',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        $suratMasukList = $suratMasukQuery
            ->orderBy('id_surat_izin_masuk', 'desc')
            ->paginate(15);

        return view(
            'guru_piket.digital_surat',
            compact(
                'user',
                'namaGuruPiket',
                'permohonanList',
                'dispensasiList',
                'suratMasukList'
            )
        );
    }

    /**
     * Ekspor Rekap Surat Piket ke CSV.
     */
    public function exportCsv(Request $request)
    {
        $todayStr = Carbon::now('Asia/Jakarta')
            ->translatedFormat('d F Y');

        $rows = [
            ['Laporan Surat Piket Digital Guru Piket'],
            ['Tanggal Ekspor', $todayStr],
            [],
            ['Daftar Surat Izin & Sakit Siswa (Dari Piket)'],
            [
                'No',
                'Nama Siswa',
                'Kelas',
                'Jenis Izin',
                'Tanggal Mulai',
                'Tanggal Selesai',
                'Status'
            ],
        ];

        $permohonanList = PermohonanIzin::with([
            'siswa.kelas.jurusan'
        ])
            ->orderBy('id_permohonan', 'desc')
            ->get();

        foreach ($permohonanList as $idx => $p) {
            $namaKelas = optional($p->siswa)->kelas
                ? (
                    optional($p->siswa->kelas)->tingkat
                    . ' '
                    . optional(
                        optional($p->siswa->kelas)->jurusan
                    )->kode_jurusan
                    . ' '
                    . optional($p->siswa->kelas)->rombel
                )
                : '-';

            $rows[] = [
                $idx + 1,
                optional($p->siswa)->nama_siswa ?? 'Siswa',
                $namaKelas,
                $p->jenis_izin,
                $p->tanggal_mulai,
                $p->tanggal_selesai,
                $p->status,
            ];
        }

        $rows[] = [];
        $rows[] = ['Daftar Permohonan Dispensasi Siswa'];

        $rows[] = [
            'No',
            'Nomor Surat',
            'Nama Siswa',
            'Kelas',
            'Kegiatan',
            'Lokasi',
            'Tanggal',
            'Jam',
            'Status'
        ];

        $dispensasiList = SuratDispensasi::with([
            'siswa.kelas.jurusan',
            'siswaList.siswa.kelas.jurusan',
        ])
            ->orderBy('id_dispen', 'desc')
            ->get();

        foreach ($dispensasiList as $idx => $d) {
            // Ambil semua siswa dari pivot
            $namaSemuaSiswa = $d->siswaList
                ->map(function ($item) {
                    return optional($item->siswa)->nama_siswa;
                })
                ->filter()
                ->implode(', ');

            // Fallback surat lama
            if (!$namaSemuaSiswa && $d->siswa) {
                $namaSemuaSiswa = $d->siswa->nama_siswa;
            }

            $kelas = $d->siswaList->first()
                ? optional(
                    $d->siswaList->first()->siswa
                )->kelas
                : optional($d->siswa)->kelas;

            $namaKelas = $kelas
                ? (
                    ($kelas->tingkat ?? '')
                    . ' '
                    . (optional($kelas->jurusan)->kode_jurusan ?? '')
                    . ' '
                    . ($kelas->rombel ?? '')
                )
                : '-';

            $rows[] = [
                $idx + 1,
                $d->nomor_surat,
                $namaSemuaSiswa ?: 'Siswa',
                $namaKelas,
                $d->nama_kegiatan,
                $d->lokasi_kegiatan ?? '-',
                $d->tanggal_mulai
                    . ' s/d '
                    . $d->tanggal_selesai,
                $d->jam_mulai
                    . ' - '
                    . $d->jam_selesai,
                ucfirst($d->status_approval),
            ];
        }

        $rows[] = [];
        $rows[] = [
            'Daftar Surat Ijin Masuk / Meninggalkan Kelas'
        ];

        $rows[] = [
            'No',
            'Nomor Surat',
            'Tanggal',
            'Nama Siswa',
            'Kelas',
            'Jam Ke',
            'Alasan',
            'Guru Piket'
        ];

        $suratMasukAll =
            \App\Models\SuratIzinMasuk::orderBy(
                'id_surat_izin_masuk',
                'desc'
            )->get();

        foreach ($suratMasukAll as $idx => $s) {
            $rows[] = [
                $idx + 1,
                $s->nomor_surat,
                $s->tanggal
                    ? $s->tanggal->format('d/m/Y')
                    : '-',
                $s->nama_siswa,
                $s->kelas_str,
                $s->jam_pelajaran_ke,
                $s->alasan,
                $s->nama_guru_piket ?? '-',
            ];
        }

        $filename =
            'rekap-surat-piket-'
            . Carbon::now('Asia/Jakarta')->format('Y-m-d')
            . '.csv';

        return CsvExporter::downloadRows(
            $filename,
            $rows
        );
    }

    /**
     * Simpan tanda tangan siswa.
     *
     * Satu surat = satu tanda tangan.
     * Nama tanda tangan = nama depan seluruh siswa.
     */
    public function simpanTtdSiswa(Request $request, $id)
    {
        $request->validate([
            'signature' => [
                'required',
                'string',
                'regex:/^data:image\/png;base64,/',
            ],
            'nama_siswa' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        $surat = SuratDispensasi::with(
            'siswaList.siswa'
        )->findOrFail($id);

        if ($surat->tipe_pemohon !== 'siswa') {
            return response()->json([
                'success' => false,
                'message' => 'Surat ini bukan untuk siswa.',
            ], 422);
        }

        // Ambil nama semua siswa
        $namaSiswa = $surat->siswaList
            ->map(function ($item) {
                return optional($item->siswa)->nama_siswa;
            })
            ->filter()
            ->map(function ($nama) {
                $nama = trim($nama);

                return explode(' ', $nama)[0];
            })
            ->filter()
            ->implode(', ');

        // Fallback surat lama
        if (!$namaSiswa && $surat->id_siswa) {
            $siswa = Siswa::find($surat->id_siswa);

            if ($siswa) {
                $namaLengkap = trim($siswa->nama_siswa);

                $namaSiswa = explode(
                    ' ',
                    $namaLengkap
                )[0];
            }
        }

        if (!$namaSiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa pada surat tidak ditemukan.',
            ], 422);
        }

        $ttdData = $request->input('signature');

        // Hapus prefix Base64
        $base64 = preg_replace(
            '/^data:image\/png;base64,/',
            '',
            $ttdData
        );

        $binary = base64_decode(
            $base64,
            true
        );

        if (
            $binary === false
            || strlen($binary) < 100
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Tanda tangan tidak valid.',
            ], 422);
        }

        if (
            strlen($binary)
            > 2 * 1024 * 1024
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Ukuran tanda tangan terlalu besar (maks 2MB).',
            ], 413);
        }

        // Hapus tanda tangan lama
        if (
            $surat->ttd_siswa_path
            && Storage::disk('public')->exists(
                $surat->ttd_siswa_path
            )
        ) {
            Storage::disk('public')->delete(
                $surat->ttd_siswa_path
            );
        }

        // Simpan tanda tangan baru
        $filename =
            'ttd_surat_dispensasi/'
            . $surat->id_dispen
            . '_'
            . Carbon::now('Asia/Jakarta')
                ->format('Ymd_His')
            . '.png';

        Storage::disk('public')->put(
            $filename,
            $binary
        );

        // Update data
        $surat->update([
            'ttd_siswa_path' => $filename,
            'ttd_siswa_signed_at' =>
                Carbon::now('Asia/Jakarta'),
            'ttd_siswa_signed_name' =>
                $namaSiswa,
        ]);

        $suratFresh = $surat->fresh();

        return response()->json([
            'success' => true,
            'url' => $suratFresh->ttd_siswa_url,
            'signed_at' =>
                optional(
                    $suratFresh->ttd_siswa_signed_at
                )->format('d/m/Y H:i'),
            'signed_name' =>
                $suratFresh->ttd_siswa_signed_name,
        ]);
    }

    /**
     * Simpan tanda tangan guru.
     */
    public function simpanTtdGuru(Request $request, $id)
    {
        $request->validate([
            'signature' => [
                'required',
                'string',
                'regex:/^data:image\/png;base64,/',
            ],
            'nama_guru' => [
                'required',
                'string',
                'max:100',
            ],
        ]);

        $surat = SuratDispensasi::findOrFail($id);

        $base64 = preg_replace(
            '/^data:image\/png;base64,/',
            '',
            $request->input('signature')
        );

        $binary = base64_decode(
            $base64,
            true
        );

        if (
            $binary === false
            || strlen($binary) < 100
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Tanda tangan guru tidak valid.',
            ], 422);
        }

        if (
            strlen($binary)
            > 2 * 1024 * 1024
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Ukuran tanda tangan terlalu besar (maks 2MB).',
            ], 413);
        }

        if (
            $surat->ttd_guru_path
            && Storage::disk('public')->exists(
                $surat->ttd_guru_path
            )
        ) {
            Storage::disk('public')->delete(
                $surat->ttd_guru_path
            );
        }

        $filename =
            'ttd_surat_dispensasi/guru_'
            . $surat->id_dispen
            . '_'
            . Carbon::now('Asia/Jakarta')
                ->format('Ymd_His')
            . '.png';

        Storage::disk('public')->put(
            $filename,
            $binary
        );

        $surat->update([
            'ttd_guru_path' => $filename,
            'ttd_guru_signed_at' =>
                Carbon::now('Asia/Jakarta'),
            'ttd_guru_signed_name' =>
                $request->input('nama_guru'),
        ]);

        $fresh = $surat->fresh();

        return response()->json([
            'success' => true,
            'url' => $fresh->ttd_guru_url,
            'signed_at' =>
                optional(
                    $fresh->ttd_guru_signed_at
                )->format('d/m/Y H:i'),
        ]);
    }

    /**
     * Kirim notifikasi WA ke Satpam.
     */
    private function kirimPengumumanKeSatpam(
        $dispen,
        $siswa
    ): void {
        try {
            if (!$siswa) {
                return;
            }

            $nomorSatpam =
                \App\Models\WaSetting::getByKey(
                    'wa_nomor_satpam',
                    ''
                );

            if (empty($nomorSatpam)) {
                return;
            }

            $waEnabled =
                \App\Models\WaSetting::getByKey(
                    'wa_enabled',
                    '1'
                ) === '1';

            if (!$waEnabled) {
                return;
            }

            $waBotService =
                app(\App\Services\WaBotService::class);

            $botStatus =
                $waBotService->getStatus();

            if (
                !isset($botStatus['status'])
                || $botStatus['status'] !== 'connected'
            ) {
                return;
            }

            $kelasLabel = '';

            if ($siswa->kelas) {
                $kelasLabel = trim(
                    ($siswa->kelas->tingkat ?? '')
                    . ' '
                    . (optional(
                        $siswa->kelas->jurusan
                    )->kode_jurusan ?? '')
                    . ' '
                    . ($siswa->kelas->rombel ?? '')
                );
            }

            $jamKeluar = $dispen->jam_mulai
                ? Carbon::parse(
                    $dispen->jam_mulai
                )->format('H:i')
                : Carbon::now(
                    'Asia/Jakarta'
                )->format('H:i');

            $jamKembali = $dispen->jam_selesai
                ? Carbon::parse(
                    $dispen->jam_selesai
                )->format('H:i')
                : '-';

            $user = auth()->user();

            $namaGuru = $user && $user->guru
                ? $user->guru->nama_guru
                : ($user->name ?? 'Guru Piket');

            $pesan =
                \App\Models\WaTemplate::renderMessage(
                    'pengumuman_gerbang_satpam',
                    [
                        'nama_siswa' =>
                            $siswa->nama_siswa,
                        'nama_kelas' =>
                            $kelasLabel ?: '-',
                        'nama_kegiatan' =>
                            $dispen->nama_kegiatan ?? '-',
                        'jam_keluar' =>
                            $jamKeluar,
                        'jam_kembali' =>
                            $jamKembali,
                        'nama_piket' =>
                            $namaGuru,
                    ]
                );

            $phone = preg_replace(
                '/[^0-9]/',
                '',
                $nomorSatpam
            );

            if (str_starts_with($phone, '0')) {
                $phone =
                    '62' . substr($phone, 1);
            }

            $waBotService->sendMessage(
                $phone,
                $pesan
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning(
                'Gagal kirim pengumuman gerbang satpam: '
                . $e->getMessage()
            );
        }
    }

    /**
     * Halaman Surat Ijin Masuk / Meninggalkan Kelas.
     */
    public function suratIzinMasuk(Request $request)
    {
        $this->ensureDataExist();

        $user = auth()->user();
        $guru = $user ? $user->guru : null;

        $namaGuruPiket = $guru
            ? $guru->nama_guru
            : ($user->name ?? 'Guru Piket Hari Ini');

        Carbon::setLocale('id');

        $todayName = Carbon::now('Asia/Jakarta')
            ->translatedFormat('l');

        $todayFormatted = Carbon::now('Asia/Jakarta')
            ->translatedFormat('l, d F Y');

        $isDutyToday =
            $this->isTeacherDutyToday($user);

        $kelasList = Kelas::with('jurusan')
            ->orderBy('tingkat')
            ->orderBy('rombel')
            ->get();

        $siswaList = Siswa::with('kelas.jurusan')
            ->orderBy('nama_siswa')
            ->get();

        $todayJadwals =
            \App\Models\JadwalPelajaran::with(['mapel'])
                ->where('hari', $todayName)
                ->get();

        $jamSlots =
            \App\Models\JamPelajaran::where(
                'is_istirahat',
                0
            )
                ->orderBy('jam_ke')
                ->get();

        $suratMasukList =
            \App\Models\SuratIzinMasuk::with([
                'siswa.kelas.jurusan'
            ])
                ->orderBy(
                    'id_surat_izin_masuk',
                    'desc'
                )
                ->paginate(15);

        return view(
            'guru_piket.surat_izin_masuk',
            compact(
                'user',
                'namaGuruPiket',
                'todayFormatted',
                'todayName',
                'isDutyToday',
                'kelasList',
                'siswaList',
                'suratMasukList',
                'todayJadwals',
                'jamSlots'
            )
        );
    }

    /**
     * Simpan Surat Ijin Masuk Kelas / Meninggalkan Kelas.
     */
    public function storeSuratIzinMasuk(
        Request $request
    ) {
        $user = auth()->user();

        Carbon::setLocale('id');

        $todayName = Carbon::now('Asia/Jakarta')
            ->translatedFormat('l');

        if (!$this->isTeacherDutyToday($user)) {
            return back()->with(
                'error',
                "Akses Ditolak: Anda tidak terdaftar sebagai Guru Piket bertugas untuk hari {$todayName}."
            );
        }

        $request->validate([
            'id_siswa' =>
                'nullable|exists:siswa,id_siswa',
            'nama_siswa' =>
                'required|string|max:255',
            'kelas_str' =>
                'required|string|max:255',
            'jam_pelajaran_ke' =>
                'required|string|max:100',
            'alasan' =>
                'required|string',
            'nama_piket_wakasek' =>
                'nullable|string|max:255',
            'nama_guru_piket' =>
                'nullable|string|max:255',
        ]);

        $todayNow =
            Carbon::now('Asia/Jakarta');

        $todayStr =
            $todayNow->toDateString();

        $countToday =
            \App\Models\SuratIzinMasuk::whereYear(
                'tanggal',
                $todayNow->year
            )->count() + 1;

        $nomorSurat = sprintf(
            "SIM/%s/%s/%03d",
            $todayNow->format('Y'),
            $todayNow->format('m'),
            $countToday
        );

        $guru = $user ? $user->guru : null;

        $namaGuruPiket =
            $request->nama_guru_piket
            ?: (
                $guru
                    ? $guru->nama_guru
                    : ($user->name ?? 'Guru Piket')
            );

        $idKelas = null;

        if ($request->id_siswa) {
            $siswa = Siswa::find(
                $request->id_siswa
            );

            if ($siswa) {
                $idKelas = $siswa->id_kelas;
            }
        }

        $surat =
            \App\Models\SuratIzinMasuk::create([
                'nomor_surat' => $nomorSurat,
                'id_siswa' => $request->id_siswa,
                'id_kelas' => $idKelas,
                'nama_siswa' => $request->nama_siswa,
                'kelas_str' => $request->kelas_str,
                'jam_pelajaran_ke' =>
                    $request->jam_pelajaran_ke,
                'alasan' => $request->alasan,
                'jenis_surat' =>
                    'SURAT IJIN MASUK KELAS / MENINGGALKAN KELAS',
                'tanggal' => $todayStr,
                'nama_piket_wakasek' =>
                    $request->nama_piket_wakasek,
                'nama_guru_piket' =>
                    $namaGuruPiket,
                'id_user_piket' =>
                    $user->id ?? null,
            ]);

        return back()
            ->with(
                'success',
                'Surat Ijin Masuk berhasil diterbitkan dan dicatat.'
            )
            ->with(
                'auto_print_surat_id',
                $surat->id_surat_izin_masuk
            );
    }

    /**
     * Hapus Data Surat Ijin Masuk.
     */
    public function destroySuratIzinMasuk($id)
    {
        $surat =
            \App\Models\SuratIzinMasuk::findOrFail($id);

        $surat->delete();

        return back()->with(
            'success',
            'Data Surat Ijin Masuk berhasil dihapus.'
        );
    }
}