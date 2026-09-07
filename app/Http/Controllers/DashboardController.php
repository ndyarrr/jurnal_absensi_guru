<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Support\CsvExporter;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\JadwalPelajaran;
use App\Models\JurnalMengajar;

class DashboardController extends Controller
{
    /**
     * Display the Admin Dashboard for Admin users.
     */
    public function index(Request $request)
    {
        // Redirect non-admin users to their dedicated Coming Soon dashboard
        if (! auth()->user()->isAdmin()) {
            return redirect()->route('role.dashboard');
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
        $todayDayName = $dayMap[Carbon::now('Asia/Jakarta')->format('l')] ?? 'Senin';
        $todayStr     = Carbon::now('Asia/Jakarta')->toDateString();

        // 1. Real Counts for Today
        $totalPengguna      = User::count();
        $totalSiswa         = Siswa::count();
        $totalGuru          = Guru::count();
        $totalKelas         = Kelas::count();
        $totalJadwalHariIni = JadwalPelajaran::where('hari', $todayDayName)->count();
        $sudahMengisi       = JurnalMengajar::whereDate('tanggal', $todayStr)->count();
        $belumMengisi       = max(0, $totalJadwalHariIni - $sudahMengisi);

        $persentase = $totalJadwalHariIni > 0 
            ? round(($sudahMengisi / $totalJadwalHariIni) * 100) 
            : ($sudahMengisi > 0 ? 100 : 0);

        $stats = [
            'total_pengguna'  => $totalPengguna,
            'total_siswa'     => $totalSiswa,
            'total_guru'      => $totalGuru,
            'total_kelas'     => $totalKelas,
            'total_jadwal'    => $totalJadwalHariIni,
            'sudah_mengisi'   => $sudahMengisi,
            'belum_mengisi'   => $belumMengisi,
            'persentase'      => min(100, $persentase),
            'hari_ini'        => $todayDayName,
            'is_ada_jadwal'   => $totalJadwalHariIni > 0,
        ];

        // 2. Real Aktivitas List (Strictly Today's Jurnal Entries)
        $todayJurnals = JurnalMengajar::with(['jadwal.guru', 'jadwal.kelas.jurusan', 'jadwal.mapel', 'jadwal.jamPelajaran'])
            ->whereDate('tanggal', $todayStr)
            ->orderBy('id_jurnal', 'desc')
            ->take(5)
            ->get();

        $gradients = [
            'linear-gradient(135deg, #3b82f6, #1d4ed8)',
            'linear-gradient(135deg, #6366f1, #4338ca)',
            'linear-gradient(135deg, #f59e0b, #d97706)',
            'linear-gradient(135deg, #10b981, #047857)',
            'linear-gradient(135deg, #ec4899, #be185d)',
        ];

        $aktivitasList = [];
        foreach ($todayJurnals as $idx => $jurnal) {
            $waktu = optional(optional($jurnal->jadwal)->jamPelajaran)->jam_mulai
                ? Carbon::parse($jurnal->jadwal->jamPelajaran->jam_mulai)->format('H:i')
                : '07:' . str_pad($idx * 2 + 3, 2, '0', STR_PAD_LEFT);

            $namaGuru = optional(optional($jurnal->jadwal)->guru)->nama_guru ?? 'Guru';

            $kelasStr = trim(
                optional(optional($jurnal->jadwal)->kelas)->tingkat
                . ' ' . optional(optional(optional($jurnal->jadwal)->kelas)->jurusan)->kode_jurusan
                . ' ' . optional(optional($jurnal->jadwal)->kelas)->rombel
            );

            $mapelStr = optional(optional($jurnal->jadwal)->mapel)->nama_mapel ?? '';

            $aktivitasList[] = [
                'waktu'  => $waktu,
                'nama'   => $namaGuru,
                'detail' => trim($kelasStr . ' - ' . $mapelStr, ' -'),
                'bg'     => $gradients[$idx % count($gradients)],
            ];
        }

        // 3. Real Guru Belum Mengisi HARI INI (Filtered strictly by Today's Scheduled Classes)
        $guruBelumMengisi = [];
        if ($totalJadwalHariIni > 0) {
            $filledJadwalIds = JurnalMengajar::whereDate('tanggal', $todayStr)->pluck('id_jadwal');

            $unfilledJadwals = JadwalPelajaran::with(['guru', 'mapel'])
                ->where('hari', $todayDayName)
                ->whereNotIn('id_jadwal', $filledJadwalIds)
                ->take(5)
                ->get();

            foreach ($unfilledJadwals as $jadwal) {
                if ($jadwal->guru) {
                    $guruBelumMengisi[] = [
                        'nama'  => $jadwal->guru->nama_guru,
                        'mapel' => optional($jadwal->mapel)->nama_mapel ?? 'Mata Pelajaran',
                    ];
                }
            }
        }

        // 4. Real Dynamic Chart Data
        $days = in_array((int) $request->input('days'), [7, 14, 30]) ? (int) $request->input('days') : 7;
        $chartData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now('Asia/Jakarta')->subDays($i);
            $count = JurnalMengajar::whereDate('tanggal', $date->toDateString())->count();
            $chartData[] = [
                'label'  => $date->format('n/j'),
                'val'    => $count,
                'active' => $i === 0,
            ];
        }

        $maxVal = max(array_column($chartData, 'val')) ?: 10;

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'stats'            => $stats,
                'aktivitasList'    => $aktivitasList,
                'guruBelumMengisi' => $guruBelumMengisi,
                'chartData'        => $chartData,
                'maxVal'           => $maxVal,
                'days'             => $days,
            ]);
        }

        return view('admin.dashboard', compact('stats', 'aktivitasList', 'guruBelumMengisi', 'chartData', 'maxVal', 'days'));
    }

    /**
     * Display clean "Halaman Role $role" for non-admin roles.
     */
    public function roleDashboard()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('dashboard');
        }

        // 0. If user explicitly switched role via session('active_role')
        if (session()->has('active_role')) {
            $active = session('active_role');
            if ($active === 'guru_mengajar') {
                return redirect()->route('guru-mengajar.dashboard');
            } elseif ($active === 'wali_kelas') {
                return redirect()->route('wali-kelas.dashboard');
            } elseif ($active === 'guru_piket') {
                return redirect()->route('guru-piket.dashboard');
            }
        }

        // 1. Default for Guru / Guru Mengajar
        if ($user->isGuruMengajar() || $user->id_guru || $user->guru) {
            return redirect()->route('guru-mengajar.dashboard');
        }

        // 2. Check if user is assigned as Guru Piket or scheduled for Piket Duty today
        if ($user->isGuruPiket() || $this->isTeacherDutyToday($user)) {
            return redirect()->route('guru-piket.dashboard');
        }

        // 3. Check if user is assigned as Wali Kelas
        if ($user->isWaliKelas()) {
            $guru = $user->guru;
            if ($guru && \App\Models\Kelas::where('id_guru_wali', $guru->id_guru)->exists()) {
                return redirect()->route('wali-kelas.dashboard');
            }
        }

        // 4. Fallback for roles that do not have a dedicated dashboard implemented yet
        return view('admin.dashboard.role-coming-soon');
    }

    /**
     * Dedicated Dashboard for Guru Mengajar Role matching app theme.
     */
    public function guruMengajarDashboard(Request $request)
    {
        return redirect()->route('guru-mengajar.dashboard');
    }

    /**
     * Helper to verify if user is scheduled on duty today (guru piket).
     */
    private function isTeacherDutyToday($user): bool
    {
        if (!$user) return false;
        if ($user->isAdmin()) return false;

        $idGuru = $user->id_guru;
        if (!$idGuru && $user->guru) {
            $idGuru = $user->guru->id_guru;
        }

        if (!$idGuru && !empty($user->name)) {
            $matchedGuru = \App\Models\Guru::where('nama_guru', $user->name)->first();
            if ($matchedGuru) {
                $idGuru = $matchedGuru->id_guru;
            }
        }

        if (!$idGuru) {
            return false;
        }

        if (!\Illuminate\Support\Facades\Schema::hasTable('jadwal_piket')) {
            return false;
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

        return \App\Models\JadwalPiket::where('hari', $todayName)
            ->where('id_guru', $idGuru)
            ->exists();
    }

    /**
     * Export dashboard summary as CSV.
     */
    public function exportCsv()
    {
        $dayMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $todayDayName = $dayMap[Carbon::now('Asia/Jakarta')->format('l')] ?? 'Senin';
        $todayStr     = Carbon::now('Asia/Jakarta')->toDateString();
        $todayLabel   = Carbon::now('Asia/Jakarta')->translatedFormat('d F Y');

        $totalPengguna      = User::count();
        $totalSiswa         = Siswa::count();
        $totalGuru          = Guru::count();
        $totalKelas         = Kelas::count();
        $totalJadwalHariIni = JadwalPelajaran::where('hari', $todayDayName)->count();
        $sudahMengisi       = JurnalMengajar::whereDate('tanggal', $todayStr)->count();
        $belumMengisi       = max(0, $totalJadwalHariIni - $sudahMengisi);
        $persentase         = $totalJadwalHariIni > 0 ? round(($sudahMengisi / $totalJadwalHariIni) * 100) : ($sudahMengisi > 0 ? 100 : 0);

        $rows = [
            ['Laporan Dashboard Admin'],
            ['Tanggal Ekspor', $todayLabel],
            ['Hari Ini', $todayDayName],
            [],
            ['Ringkasan Statistik'],
            ['Total Pengguna', $totalPengguna],
            ['Total Siswa', $totalSiswa],
            ['Total Guru', $totalGuru],
            ['Total Kelas', $totalKelas],
            ['Total Jadwal Hari Ini (' . $todayDayName . ')', $totalJadwalHariIni],
            ['Rekap Jurnal Hari Ini'],
            ['Sudah Mengisi', $sudahMengisi],
            ['Belum Mengisi', $belumMengisi],
            ['Persentase Penyelesaian', min(100, $persentase) . '%'],
            [],
            ['Grafik Jurnal (9 Hari Terakhir)'],
            ['Tanggal', 'Jumlah Jurnal'],
        ];

        for ($i = 8; $i >= 0; $i--) {
            $date = Carbon::now('Asia/Jakarta')->subDays($i);
            $count = JurnalMengajar::whereDate('tanggal', $date->toDateString())->count();
            $rows[] = [$date->format('d/m/Y'), $count];
        }

        $rows[] = [];
        $rows[] = ['Aktivitas Hari Ini'];
        $rows[] = ['Waktu', 'Guru', 'Detail'];

        $todayJurnals = JurnalMengajar::with(['jadwal.guru', 'jadwal.kelas.jurusan', 'jadwal.mapel', 'jadwal.jamPelajaran'])
            ->whereDate('tanggal', $todayStr)
            ->orderByDesc('id_jurnal')
            ->take(20)
            ->get();

        if ($todayJurnals->isEmpty()) {
            $rows[] = ['-', 'Belum ada aktivitas jurnal hari ini', '-'];
        } else {
            foreach ($todayJurnals as $idx => $jurnal) {
                $waktu = optional(optional($jurnal->jadwal)->jamPelajaran)->jam_mulai
                    ? Carbon::parse($jurnal->jadwal->jamPelajaran->jam_mulai)->format('H:i')
                    : '-';

                $kelasStr = trim(
                    optional(optional($jurnal->jadwal)->kelas)->tingkat . ' '
                    . optional(optional(optional($jurnal->jadwal)->kelas)->jurusan)->kode_jurusan . ' '
                    . optional(optional($jurnal->jadwal)->kelas)->rombel
                );
                $mapelStr = optional(optional($jurnal->jadwal)->mapel)->nama_mapel ?? '';
                $detail = trim($kelasStr . ' - ' . $mapelStr, ' -');

                $rows[] = [
                    $waktu,
                    optional(optional($jurnal->jadwal)->guru)->nama_guru ?? '-',
                    $detail ?: '-',
                ];
            }
        }

        $rows[] = [];
        $rows[] = ['Guru Belum Mengisi Hari Ini (' . $todayDayName . ')'];
        $rows[] = ['Nama Guru', 'Mata Pelajaran'];

        if ($totalJadwalHariIni == 0) {
            $rows[] = ['Tidak ada jadwal pelajaran hari ini (' . $todayDayName . ')', '-'];
        } else {
            $filledJadwalIds = JurnalMengajar::whereDate('tanggal', $todayStr)->pluck('id_jadwal');
            $unfilledJadwals = JadwalPelajaran::with(['guru', 'mapel'])
                ->where('hari', $todayDayName)
                ->whereNotIn('id_jadwal', $filledJadwalIds)
                ->get();

            if ($unfilledJadwals->isEmpty()) {
                $rows[] = ['Semua guru jadwal hari ini sudah mengisi jurnal', '-'];
            } else {
                foreach ($unfilledJadwals as $jadwal) {
                    if ($jadwal->guru) {
                        $rows[] = [
                            $jadwal->guru->nama_guru,
                            optional($jadwal->mapel)->nama_mapel ?? '-',
                        ];
                    }
                }
            }
        }

        $filename = 'laporan-dashboard-' . Carbon::now('Asia/Jakarta')->format('Y-m-d') . '.csv';

        return CsvExporter::downloadRows($filename, $rows);
    }
}
