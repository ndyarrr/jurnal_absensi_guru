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

        $todayStr = Carbon::now()->toDateString();

        // 1. Real Counts
        $totalPengguna = User::count();
        $totalSiswa    = Siswa::count();
        $totalGuru     = Guru::count();
        $totalKelas    = Kelas::count();
        $totalJadwal   = JadwalPelajaran::count();
        $sudahMengisi  = JurnalMengajar::whereDate('tanggal', $todayStr)->count();

        $stats = [
            'total_pengguna' => $totalPengguna,
            'total_siswa'    => $totalSiswa,
            'total_guru'     => $totalGuru,
            'total_kelas'    => $totalKelas,
            'total_jadwal'   => $totalJadwal,
            'sudah_mengisi'  => $sudahMengisi,
            'belum_mengisi'  => max(0, $totalJadwal - $sudahMengisi),
            'persentase'     => $totalJadwal > 0 ? round(($sudahMengisi / $totalJadwal) * 100) : 0,
        ];

        // 2. Real Aktivitas List (Latest Jurnal Entries)
        $recentJurnals = JurnalMengajar::with(['jadwal.guru', 'jadwal.kelas.jurusan', 'jadwal.mapel', 'jadwal.jamPelajaran'])
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
        foreach ($recentJurnals as $idx => $jurnal) {
            $waktu = optional($jurnal->jadwal->jamPelajaran)->jam_mulai
                ? Carbon::parse($jurnal->jadwal->jamPelajaran->jam_mulai)->format('H:i')
                : '07:' . str_pad($idx * 2 + 3, 2, '0', STR_PAD_LEFT);

            $namaGuru = optional($jurnal->jadwal->guru)->nama_guru ?? 'Guru';

            $kelasStr = optional($jurnal->jadwal->kelas)->tingkat
                . ' ' . optional(optional($jurnal->jadwal->kelas)->jurusan)->kode_jurusan
                . ' ' . optional($jurnal->jadwal->kelas)->rombel;

            $mapelStr = optional($jurnal->jadwal->mapel)->nama_mapel ?? '';

            $aktivitasList[] = [
                'waktu'  => $waktu,
                'nama'   => $namaGuru,
                'detail' => trim($kelasStr . ' - ' . $mapelStr, ' -'),
                'bg'     => $gradients[$idx % count($gradients)],
            ];
        }

        // 3. Real Guru Belum Mengisi Hari Ini
        $filledJadwalIds = JurnalMengajar::whereDate('tanggal', $todayStr)->pluck('id_jadwal');

        $unfilledJadwals = JadwalPelajaran::with(['guru', 'mapel'])
            ->whereNotIn('id_jadwal', $filledJadwalIds)
            ->take(5)
            ->get();

        $guruBelumMengisi = [];
        foreach ($unfilledJadwals as $jadwal) {
            if ($jadwal->guru) {
                $guruBelumMengisi[] = [
                    'nama'  => $jadwal->guru->nama_guru,
                    'mapel' => optional($jadwal->mapel)->nama_mapel ?? 'Mata Pelajaran',
                ];
            }
        }

        // 4. Real Dynamic Chart Data
        $days = in_array((int) $request->input('days'), [7, 14, 30]) ? (int) $request->input('days') : 7;
        $chartData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
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
     * Display clean "Halaman Role $role Segera Hadir" for non-admin roles.
     */
    public function roleDashboard()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('dashboard');
        }

        // 1. Check if user is scheduled for Piket Duty today or has guru_piket role
        if ($this->isTeacherDutyToday($user)) {
            return redirect()->route('guru-piket.dashboard');
        }

         // 2. Check if user has the Guru Mengajar role
        if ($user->isGuruMengajar()) {
            return redirect()->route('guru-mengajar.dashboard');
        }
        
        // 2. Check if user is assigned as Wali Kelas
        if ($user->isWaliKelas()) {
            $guru = $user->guru;
            if ($guru && \App\Models\Kelas::where('id_guru_wali', $guru->id_guru)->exists()) {
                return redirect()->route('wali-kelas.dashboard');
            }
        }

        return view('admin.dashboard.role-coming-soon');
    }

    /**
     * Helper to check if current user is scheduled for Piket Duty today.
     */
    private function isTeacherDutyToday($user): bool
    {
        if (!$user) return false;
        if ($user->isAdmin()) return true;

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

        if (!\Illuminate\Support\Facades\Schema::hasTable('jadwal_piket') || \App\Models\JadwalPiket::count() === 0) {
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

        return \App\Models\JadwalPiket::where('hari', $todayName)
            ->where('id_guru', $idGuru)
            ->exists();
    }

    /**
     * Export dashboard summary as CSV.
     */
    public function exportCsv()
    {
        $todayStr = Carbon::now('Asia/Jakarta')->toDateString();
        $todayLabel = Carbon::now('Asia/Jakarta')->translatedFormat('d F Y');

        $totalPengguna = User::count();
        $totalSiswa    = Siswa::count();
        $totalGuru     = Guru::count();
        $totalKelas    = Kelas::count();
        $totalJadwal   = JadwalPelajaran::count();
        $sudahMengisi  = JurnalMengajar::whereDate('tanggal', $todayStr)->count();
        $belumMengisi  = max(0, $totalJadwal - $sudahMengisi);
        $persentase    = $totalJadwal > 0 ? round(($sudahMengisi / $totalJadwal) * 100) : 0;

        $rows = [
            ['Laporan Dashboard Admin'],
            ['Tanggal Ekspor', $todayLabel],
            [],
            ['Ringkasan Statistik'],
            ['Total Pengguna', $totalPengguna],
            ['Total Siswa', $totalSiswa],
            ['Total Guru', $totalGuru],
            ['Total Kelas', $totalKelas],
            ['Total Jadwal', $totalJadwal],
            ['Rekap Jurnal Hari Ini'],
            ['Sudah Mengisi', $sudahMengisi],
            ['Belum Mengisi', $belumMengisi],
            ['Persentase Penyelesaian', $persentase . '%'],
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
        $rows[] = ['Aktivitas Terbaru'];
        $rows[] = ['Waktu', 'Guru', 'Detail'];

        $recentJurnals = JurnalMengajar::with(['jadwal.guru', 'jadwal.kelas.jurusan', 'jadwal.mapel', 'jadwal.jamPelajaran'])
            ->orderByDesc('id_jurnal')
            ->take(20)
            ->get();

        foreach ($recentJurnals as $idx => $jurnal) {
            $waktu = optional($jurnal->jadwal->jamPelajaran)->jam_mulai
                ? Carbon::parse($jurnal->jadwal->jamPelajaran->jam_mulai)->format('H:i')
                : '-';

            $kelasStr = trim(
                optional($jurnal->jadwal->kelas)->tingkat . ' '
                . optional(optional($jurnal->jadwal->kelas)->jurusan)->kode_jurusan . ' '
                . optional($jurnal->jadwal->kelas)->rombel
            );
            $mapelStr = optional($jurnal->jadwal->mapel)->nama_mapel ?? '';
            $detail = trim($kelasStr . ' - ' . $mapelStr, ' -');

            $rows[] = [
                $waktu,
                optional($jurnal->jadwal->guru)->nama_guru ?? '-',
                $detail ?: '-',
            ];
        }

        $filledJadwalIds = JurnalMengajar::whereDate('tanggal', $todayStr)->pluck('id_jadwal');
        $unfilledJadwals = JadwalPelajaran::with(['guru', 'mapel'])
            ->whereNotIn('id_jadwal', $filledJadwalIds)
            ->get();

        $rows[] = [];
        $rows[] = ['Guru Belum Mengisi Hari Ini'];
        $rows[] = ['Nama Guru', 'Mata Pelajaran'];

        foreach ($unfilledJadwals as $jadwal) {
            if ($jadwal->guru) {
                $rows[] = [
                    $jadwal->guru->nama_guru,
                    optional($jadwal->mapel)->nama_mapel ?? '-',
                ];
            }
        }

        $filename = 'laporan-dashboard-' . Carbon::now('Asia/Jakarta')->format('Y-m-d') . '.csv';

        return CsvExporter::downloadRows($filename, $rows);
    }
}
