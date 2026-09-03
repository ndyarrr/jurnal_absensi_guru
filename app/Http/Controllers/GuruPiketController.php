<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
                'rombel'  => 1,
                'wali_kelas' => 'Aily Cantika, S.Pd',
                'jumlah_siswa' => 32
            ]);
        }

        $siswaList = Siswa::where('id_kelas', $kelas->id_kelas)->get();
        if ($siswaList->isEmpty()) {
            $defaultNames = [
                'Azzura Atasya', 'Felix Fernandez', 'Megan Fernita',
                'Bella Sutanto', 'Canva Narendra', 'Ilona Lovita'
            ];
            foreach ($defaultNames as $idx => $name) {
                Siswa::create([
                    'nisn' => '0056789' . str_pad($kelas->id_kelas, 2, '0', STR_PAD_LEFT) . str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
                    'nama_siswa' => $name,
                    'id_kelas' => $kelas->id_kelas
                ]);
            }
            $siswaList = Siswa::where('id_kelas', $kelas->id_kelas)->get();
        }

        // Seed sample SuratDispensasi if empty (Status directly approved by Guru Piket)
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

        // Seed sample PermohonanIzin for Guru Piket if empty
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
        $namaGuruPiket = $guru ? $guru->nama_guru : ($user->name ?? 'Guru Piket Hari Ini');

        Carbon::setLocale('id');
        $todayStr = Carbon::now('Asia/Jakarta')->toDateString();
        $todayFormatted = Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y');

        // Metrics calculations
        $suratMasukHariIni = PermohonanIzin::whereDate('created_at', $todayStr)->count()
            + SuratDispensasi::whereDate('created_at', $todayStr)->count();

        $totalDispensasiCount = SuratDispensasi::count();

        $dispenDisetujuiCount = SuratDispensasi::where('status_approval', 'disetujui')->count();

        $siswaIzinSakitCount = PermohonanIzin::whereDate('tanggal_mulai', '<=', $todayStr)
            ->whereDate('tanggal_selesai', '>=', $todayStr)
            ->count();

        // Recent piket submissions for main table
        $recentPermohonan = PermohonanIzin::with(['siswa.kelas.jurusan'])
            ->orderBy('id_permohonan', 'desc')
            ->take(6)
            ->get();

        $recentDispensasi = SuratDispensasi::with(['siswa.kelas.jurusan'])
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
     * Helper to check if current user (Guru Mapel / Wali Kelas / Guru Piket) is scheduled for Piket Duty today (or is admin).
     */
    private function isTeacherDutyToday($user): bool
    {
        if (!$user) {
            return false;
        }

        // 1. Admin & Super Admin always have full access
        if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
            return true;
        }

        $dayMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $englishDay = Carbon::now('Asia/Jakarta')->format('l');
        $todayName = $dayMap[$englishDay] ?? 'Senin';

        // 3. Resolve id_guru via user relation, direct attribute, or name matching
        $idGuru = $user->id_guru;
        if (!$idGuru && $user->guru) {
            $idGuru = $user->guru->id_guru;
        }

        if (!$idGuru && !empty($user->name)) {
            $matchedGuru = \App\Models\Guru::where('nama_guru', $user->name)
                ->orWhere('nama_guru', 'like', '%' . $user->name . '%')
                ->first();
            if ($matchedGuru) {
                $idGuru = $matchedGuru->id_guru;
            }
        }

        // 4. Fallback: If table doesn't exist or table has 0 records, allow access
        if (!\Illuminate\Support\Facades\Schema::hasTable('jadwal_piket') || \App\Models\JadwalPiket::count() === 0) {
            return true;
        }

        // 5. Check if id_guru is scheduled for today in JadwalPiket
        if ($idGuru) {
            return \App\Models\JadwalPiket::where('hari', $todayName)
                ->where('id_guru', $idGuru)
                ->exists();
        }

        return false;
    }

    /**
     * Form Input Surat Izin / Sakit dari orang tua (Foto Surat Fisik Terlambat Jam 8/9 Pagi).
     */
    public function inputSuratIzin(Request $request)
    {
        $this->ensureDataExist();

        $user = auth()->user();
        $guru = $user ? $user->guru : null;
        $namaGuruPiket = $guru ? $guru->nama_guru : ($user->name ?? 'Guru Piket Hari Ini');

        Carbon::setLocale('id');
        $todayName = Carbon::now('Asia/Jakarta')->translatedFormat('l');
        $isDutyToday = $this->isTeacherDutyToday($user);

        $kelasList = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('rombel')->get();
        $siswaList = Siswa::with('kelas.jurusan')->orderBy('nama_siswa')->get();

        return view('guru_piket.input_surat', compact('user', 'namaGuruPiket', 'kelasList', 'siswaList', 'isDutyToday', 'todayName'));
    }

    /**
     * Simpan Surat Izin / Sakit Fisik ke Sistem & Auto-Update Rekap Kelas.
     */
    public function storeSuratIzin(Request $request)
    {
        $user = auth()->user();
        Carbon::setLocale('id');
        $todayName = Carbon::now('Asia/Jakarta')->translatedFormat('l');

        if (!$this->isTeacherDutyToday($user)) {
            return back()->with('error', "Akses Ditolak: Anda tidak terdaftar sebagai Guru Piket bertugas untuk hari {$todayName}. Pengisian surat izin hanya dibuka untuk Guru Piket bertugas hari ini.");
        }

        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'jenis_izin' => 'required|in:Sakit,Izin',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'foto_surat' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $siswa = Siswa::with('kelas')->findOrFail($request->id_siswa);
        $fotoPath = null;

        if ($request->hasFile('foto_surat')) {
            $fotoPath = $request->file('foto_surat')->store('bukti_surat', 'public');
        }

        // Auto-generate description label since user input alasan was removed
        $autoAlasan = "Surat {$request->jenis_izin} fisik diserahkan ke meja piket";

        // Save into PermohonanIzin
        $permohonan = PermohonanIzin::create([
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

        // Auto-update DetailKetidakhadiran for today's active class journals
        $todayStr = Carbon::now('Asia/Jakarta')->toDateString();
        if ($request->tanggal_mulai <= $todayStr && $request->tanggal_selesai >= $todayStr) {
            $jurnalsToday = JurnalMengajar::whereDate('tanggal', $todayStr)
                ->whereHas('jadwal', function($q) use ($siswa) {
                    $q->where('id_kelas', $siswa->id_kelas);
                })->get();

            $idPiket = $user->id_guru ?: optional($user->guru)->id_guru;
            if (!$idPiket && !empty($user->name)) {
                $matchedGuru = \App\Models\Guru::where('nama_guru', $user->name)->first();
                if ($matchedGuru) {
                    $idPiket = $matchedGuru->id_guru;
                }
            }

            foreach ($jurnalsToday as $jurnal) {
                DetailKetidakhadiran::updateOrCreate(
                    [
                        'id_jurnal' => $jurnal->id_jurnal,
                        'id_siswa'  => $siswa->id_siswa,
                    ],
                    [
                        'status'        => strtolower($request->jenis_izin),
                        'kategori'      => strtolower($request->jenis_izin) === 'sakit' ? 'sakit' : 'izin_ortu',
                        'bukti_surat'   => $fotoPath,
                        'catatan'       => '[Guru Piket] ' . $autoAlasan,
                        'id_guru_piket' => $idPiket,
                        'waktu_input'   => Carbon::now('Asia/Jakarta'),
                    ]
                );
            }
        }

        return redirect()->route('guru-piket.digital-surat')
            ->with('success', "Surat {$request->jenis_izin} digital untuk {$siswa->nama_siswa} berhasil di-input dan terdaftar di rekap absensi kelas secara langsung!");
    }

    /**
     * Helper to generate a unique document number for Surat Dispensasi.
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
            $nomorSurat = $prefix . str_pad($seq, 3, '0', STR_PAD_LEFT);
            $exists = SuratDispensasi::withTrashed()->where('nomor_surat', $nomorSurat)->exists();
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
        $namaGuruPiket = $guru ? $guru->nama_guru : ($user->name ?? 'Guru Piket Hari Ini');

        Carbon::setLocale('id');
        $todayName = Carbon::now('Asia/Jakarta')->translatedFormat('l');
        $isDutyToday = $this->isTeacherDutyToday($user);

        $kelasList = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('rombel')->get();
        $siswaList = Siswa::with('kelas.jurusan')->orderBy('nama_siswa')->get();

        $surat = null;
        $isVerified = false;
        if ($request->filled('id')) {
            $surat = SuratDispensasi::with(['siswa.kelas.jurusan'])->find($request->input('id'));
            if ($surat) {
                $isVerified = ($surat->status_approval === 'disetujui');
            }
        }

        // Generate automatic unique document number
        if ($surat) {
            $autoNomorSurat = $surat->nomor_surat;
        } else {
            $autoNomorSurat = $this->generateUniqueNomorSurat();
        }

        return view('guru_piket.input_dispensasi', compact(
            'user',
            'namaGuruPiket',
            'kelasList',
            'siswaList',
            'autoNomorSurat',
            'isDutyToday',
            'todayName',
            'surat',
            'isVerified'
        ));
    }

    /**
     * Simpan Dispensasi Siswa beserta TTD Digital oleh Guru Piket.
     */
    public function storeDispensasi(Request $request)
    {
        $user = auth()->user();
        Carbon::setLocale('id');
        $todayName = Carbon::now('Asia/Jakarta')->translatedFormat('l');

        if (!$this->isTeacherDutyToday($user)) {
            return back()->with('error', "Akses Ditolak: Anda tidak terdaftar sebagai Guru Piket bertugas untuk hari {$todayName}. Pengisian dispensasi siswa hanya dibuka untuk Guru Piket bertugas hari ini.");
        }

        // Block edit if dispensasi letter is already verified / approved
        if ($request->filled('id_dispen')) {
            $existing = SuratDispensasi::find($request->input('id_dispen'));
            if ($existing && $existing->status_approval === 'disetujui') {
                return back()->with('error', 'Perubahan Ditolak: Surat dispensasi yang telah terverifikasi & disetujui tidak dapat diubah kembali.');
            }
        }

        $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
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

        $siswa = Siswa::with('kelas')->findOrFail($request->id_siswa);

        $existingDispen = $request->filled('id_dispen') ? SuratDispensasi::find($request->input('id_dispen')) : null;
        $filePath = $existingDispen ? $existingDispen->file_surat : null;

        if ($request->input('hapus_file_surat') == '1') {
            if ($existingDispen && $existingDispen->file_surat && Storage::disk('public')->exists($existingDispen->file_surat)) {
                Storage::disk('public')->delete($existingDispen->file_surat);
            }
            $filePath = null;
        }

        if ($request->hasFile('file_surat')) {
            if ($existingDispen && $existingDispen->file_surat && Storage::disk('public')->exists($existingDispen->file_surat)) {
                Storage::disk('public')->delete($existingDispen->file_surat);
            }
            $filePath = $request->file('file_surat')->store('surat_dispensasi', 'public');
        }

        $guru = $user ? $user->guru : null;
        $namaGuruPiket = $guru ? $guru->nama_guru : ($user->name ?? 'Guru Piket');

        // Resolve guaranteed unique document number
        if ($request->filled('id_dispen')) {
            $existing = SuratDispensasi::find($request->input('id_dispen'));
            $nomorSurat = $existing ? $existing->nomor_surat : $this->generateUniqueNomorSurat();
        } else {
            $nomorSurat = $request->input('nomor_surat');
            if (empty($nomorSurat) || SuratDispensasi::withTrashed()->where('nomor_surat', $nomorSurat)->exists()) {
                $nomorSurat = $this->generateUniqueNomorSurat();
            }
        }

        $dispen = SuratDispensasi::create([
            'nomor_surat' => $nomorSurat,
            'tipe_pemohon' => 'siswa',
            'id_siswa' => $siswa->id_siswa,
            'id_kelas' => $siswa->id_kelas,
            'nama_kegiatan' => $request->nama_kegiatan,
            'lokasi_kegiatan' => $request->lokasi_kegiatan ?? 'Lingkungan Sekolah/Luar',
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'alasan_dispensasi' => $request->alasan_dispensasi,
            'file_surat' => $filePath,
            'status_approval' => 'disetujui',
            'disetujui_oleh' => $user->id,
            'barcode_token' => (string) Str::uuid(),
            'created_at' => Carbon::now('Asia/Jakarta'),
        ]);

        // Process Base64 TTD Siswa
        if ($request->filled('ttd_siswa_data')) {
            $base64Siswa = preg_replace('/^data:image\/png;base64,/', '', $request->input('ttd_siswa_data'));
            $binarySiswa = base64_decode($base64Siswa, true);
            if ($binarySiswa !== false && strlen($binarySiswa) > 100) {
                $filenameSiswa = 'ttd_surat_dispensasi/siswa_' . $dispen->id_dispen . '_' . Carbon::now('Asia/Jakarta')->format('Ymd_His') . '.png';
                Storage::disk('public')->put($filenameSiswa, $binarySiswa);
                $dispen->update([
                    'ttd_siswa_path' => $filenameSiswa,
                    'ttd_siswa_signed_at' => Carbon::now('Asia/Jakarta'),
                    'ttd_siswa_signed_name' => $siswa->nama_siswa,
                ]);
            }
        }

        // Process Base64 TTD Guru
        if ($request->filled('ttd_guru_data')) {
            $base64Guru = preg_replace('/^data:image\/png;base64,/', '', $request->input('ttd_guru_data'));
            $binaryGuru = base64_decode($base64Guru, true);
            if ($binaryGuru !== false && strlen($binaryGuru) > 100) {
                $filenameGuru = 'ttd_surat_dispensasi/guru_' . $dispen->id_dispen . '_' . Carbon::now('Asia/Jakarta')->format('Ymd_His') . '.png';
                Storage::disk('public')->put($filenameGuru, $binaryGuru);
                $dispen->update([
                    'ttd_guru_path' => $filenameGuru,
                    'ttd_guru_signed_at' => Carbon::now('Asia/Jakarta'),
                    'ttd_guru_signed_name' => $namaGuruPiket,
                ]);
            }
        }

        return redirect()->route('guru-piket.digital-surat')
            ->with('success', "Surat dispensasi untuk {$siswa->nama_siswa} ({$request->nama_kegiatan}) beserta TTD Digital berhasil diterbitkan dan terverifikasi!");
    }

    /**
     * Halaman Digitalisasi Surat Piket ("Mengatasi Surat Numpuk").
     */
    public function digitalisasiSurat(Request $request)
    {
        $this->ensureDataExist();

        $user = auth()->user();
        $guru = $user ? $user->guru : null;
        $namaGuruPiket = $guru ? $guru->nama_guru : ($user->name ?? 'Guru Piket Hari Ini');

        // Query for PermohonanIzin
        $permQuery = PermohonanIzin::with(['siswa.kelas.jurusan']);
        if ($request->filled('search')) {
            $search = $request->input('search');
            $permQuery->whereHas('siswa', function($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%");
            });
        }
        if ($request->filled('jenis')) {
            $permQuery->where('jenis_izin', 'like', "%" . $request->input('jenis') . "%");
        }
        $permohonanList = $permQuery->orderBy('id_permohonan', 'desc')->get();

        // Query for SuratDispensasi
        $dispenQuery = SuratDispensasi::with(['siswa.kelas.jurusan']);
        if ($request->filled('search')) {
            $search = $request->input('search');
            $dispenQuery->where(function($q) use ($search) {
                $q->where('nama_kegiatan', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function($sq) use ($search) {
                      $sq->where('nama_siswa', 'like', "%{$search}%");
                  });
            });
        }
        $dispensasiList = $dispenQuery->orderBy('id_dispen', 'desc')->get();

        return view('guru_piket.digital_surat', compact('user', 'namaGuruPiket', 'permohonanList', 'dispensasiList'));
    }

    /**
     * Ekspor Rekap Surat Piket ke CSV.
     */
    public function exportCsv(Request $request)
    {
        $todayStr = Carbon::now('Asia/Jakarta')->translatedFormat('d F Y');

        $rows = [
            ['Laporan Surat Piket Digital Guru Piket'],
            ['Tanggal Ekspor', $todayStr],
            [],
            ['Daftar Surat Izin & Sakit Siswa (Dari Piket)'],
            ['No', 'Nama Siswa', 'Kelas', 'Jenis Izin', 'Tanggal Mulai', 'Tanggal Selesai', 'Status'],
        ];

        $permohonanList = PermohonanIzin::with(['siswa.kelas.jurusan'])->orderBy('id_permohonan', 'desc')->get();
        foreach ($permohonanList as $idx => $p) {
            $namaKelas = optional($p->siswa)->kelas
                ? (optional($p->siswa->kelas)->tingkat . ' ' . optional(optional($p->siswa->kelas)->jurusan)->kode_jurusan . ' ' . optional($p->siswa->kelas)->rombel)
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
        $rows[] = ['No', 'Nomor Surat', 'Nama Siswa', 'Kelas', 'Kegiatan', 'Lokasi', 'Tanggal', 'Jam', 'Status Status'];

        $dispensasiList = SuratDispensasi::with(['siswa.kelas.jurusan'])->orderBy('id_dispen', 'desc')->get();
        foreach ($dispensasiList as $idx => $d) {
            $namaKelas = optional($d->siswa)->kelas
                ? (optional($d->siswa->kelas)->tingkat . ' ' . optional(optional($d->siswa->kelas)->jurusan)->kode_jurusan . ' ' . optional($d->siswa->kelas)->rombel)
                : '-';

            $rows[] = [
                $idx + 1,
                $d->nomor_surat,
                optional($d->siswa)->nama_siswa ?? 'Siswa',
                $namaKelas,
                $d->nama_kegiatan,
                $d->lokasi_kegiatan ?? '-',
                $d->tanggal_mulai . ' s/d ' . $d->tanggal_selesai,
                $d->jam_mulai . ' - ' . $d->jam_selesai,
                ucfirst($d->status_approval),
            ];
        }

        $filename = 'rekap-surat-piket-' . Carbon::now('Asia/Jakarta')->format('Y-m-d') . '.csv';

        return CsvExporter::downloadRows($filename, $rows);
    }

    public function simpanTtdSiswa(Request $request, $id)
    {
        $request->validate([
            'signature'   => ['required', 'string', 'regex:/^data:image\/png;base64,/'],
            'nama_siswa'  => ['required', 'string', 'max:100'],
        ]);

        $surat = SuratDispensasi::findOrFail($id);

        if ($surat->tipe_pemohon !== 'siswa' || !$surat->id_siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Surat ini bukan untuk siswa.',
            ], 422);
        }

        $base64 = preg_replace('/^data:image\/png;base64,/', '', $request->input('signature'));
        $binary = base64_decode($base64, true);

        if ($binary === false || strlen($binary) < 100) {
            return response()->json([
                'success' => false,
                'message' => 'Tanda tangan tidak valid.',
            ], 422);
        }

        if (strlen($binary) > 2 * 1024 * 1024) {
            return response()->json([
                'success' => false,
                'message' => 'Ukuran tanda tangan terlalu besar (maks 2MB).',
            ], 413);
        }

        if ($surat->ttd_siswa_path && Storage::disk('public')->exists($surat->ttd_siswa_path)) {
            Storage::disk('public')->delete($surat->ttd_siswa_path);
        }

        $filename = 'ttd_surat_dispensasi/' . $surat->id_dispen . '_' . Carbon::now('Asia/Jakarta')->format('Ymd_His') . '.png';
        Storage::disk('public')->put($filename, $binary);

        $surat->update([
            'ttd_siswa_path'       => $filename,
            'ttd_siswa_signed_at'  => Carbon::now('Asia/Jakarta'),
            'ttd_siswa_signed_name'=> $request->input('nama_siswa'),
        ]);

        return response()->json([
            'success'    => true,
            'url'        => $surat->fresh()->ttd_siswa_url,
            'signed_at'  => optional($surat->fresh()->ttd_siswa_signed_at)->format('d/m/Y H:i'),
        ]);
    }

    public function simpanTtdGuru(Request $request, $id)
    {
        $request->validate([
            'signature'  => ['required', 'string', 'regex:/^data:image\/png;base64,/'],
            'nama_guru' => ['required', 'string', 'max:100'],
        ]);

        $surat = SuratDispensasi::findOrFail($id);

        $base64 = preg_replace('/^data:image\/png;base64,/', '', $request->input('signature'));
        $binary = base64_decode($base64, true);

        if ($binary === false || strlen($binary) < 100) {
            return response()->json([
                'success' => false,
                'message' => 'Tanda tangan guru tidak valid.',
            ], 422);
        }

        if (strlen($binary) > 2 * 1024 * 1024) {
            return response()->json([
                'success' => false,
                'message' => 'Ukuran tanda tangan terlalu besar (maks 2MB).',
            ], 413);
        }

        if ($surat->ttd_guru_path && Storage::disk('public')->exists($surat->ttd_guru_path)) {
            Storage::disk('public')->delete($surat->ttd_guru_path);
        }

        $filename = 'ttd_surat_dispensasi/guru_' . $surat->id_dispen . '_' . Carbon::now('Asia/Jakarta')->format('Ymd_His') . '.png';
        Storage::disk('public')->put($filename, $binary);

        $surat->update([
            'ttd_guru_path'        => $filename,
            'ttd_guru_signed_at'   => Carbon::now('Asia/Jakarta'),
            'ttd_guru_signed_name' => $request->input('nama_guru'),
        ]);

        return response()->json([
            'success'    => true,
            'url'        => $surat->fresh()->ttd_guru_url,
            'signed_at'  => optional($surat->fresh()->ttd_guru_signed_at)->format('d/m/Y H:i'),
        ]);
    }
}
