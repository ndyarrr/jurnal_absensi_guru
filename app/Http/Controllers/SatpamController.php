<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\SuratDispensasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SatpamController extends Controller
{
    /* ==========================================================================
       1. DASHBOARD
       ========================================================================== */
    public function dashboard(Request $request)
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $dispenHariIni = SuratDispensasi::with(['siswa.kelas.jurusan'])
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->orderByDesc('created_at')
            ->get();

        $totalDispen = $dispenHariIni->count();
        $disetujuiCount = $dispenHariIni->where('status_approval', 'disetujui')->count();
        $pendingCount = $dispenHariIni->where('status_approval', 'pending')->count();
        $ditolakCount = $dispenHariIni->where('status_approval', 'ditolak')->count();

        // Aktivitas gerbang terbaru: daftar permohonan dispensasi hari ini
        $aktivitasGerbang = $dispenHariIni->take(6)->map(function ($dispen) {
            $isDisetujui = $dispen->status_approval === 'disetujui';
            $jamMulaiStr = $dispen->jam_mulai ? Carbon::parse($dispen->jam_mulai)->format('H:i') : '-';
            $jamSelesaiStr = $dispen->jam_selesai ? Carbon::parse($dispen->jam_selesai)->format('H:i') : '-';

            return [
                'nama_siswa' => optional($dispen->siswa)->nama_siswa ?? '-',
                'kelas' => $this->kelasLabel(optional($dispen->siswa)->kelas),
                'aktivitas' => $isDisetujui ? 'Dispensasi Keluar - ' . ($dispen->nama_kegiatan ?? 'Tugas/Kegiatan') : 'Pengajuan Dispensasi',
                'keterangan' => $dispen->alasan_dispensasi ?? $dispen->nama_kegiatan ?? '-',
                'waktu' => $jamMulaiStr . ($jamSelesaiStr !== '-' ? " s/d {$jamSelesaiStr}" : ''),
                'status' => $dispen->status_approval,
            ];
        });

        // Daftar siswa yang DISETUJUI dispensasi keluar hari ini (Read-only monitoring)
        $siswaIzinKeluarHariIni = $dispenHariIni->filter(fn ($d) => $d->status_approval === 'disetujui')->take(6);

        $stats = [
            'total_dispen' => $totalDispen,
            'disetujui' => $disetujuiCount,
            'pending' => $pendingCount,
            'ditolak' => $ditolakCount,
        ];

        return view('satpam.dashboard', compact('stats', 'aktivitasGerbang', 'siswaIzinKeluarHariIni'));
    }

    /* ==========================================================================
       2. CEK DISPENSASI SISWA
       ========================================================================== */
    public function cekIzin(Request $request)
    {
        $query = SuratDispensasi::with(['siswa.kelas.jurusan']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('siswa', function ($sq) use ($search) {
                    $sq->where('nama_siswa', 'like', "%{$search}%");
                })->orWhere('nama_kegiatan', 'like', "%{$search}%")
                  ->orWhere('alasan_dispensasi', 'like', "%{$search}%");
            });
        }

        $filterStatus = $request->input('status', 'semua');
        if ($filterStatus && $filterStatus !== 'semua') {
            $query->where('status_approval', $filterStatus);
        }

        $daftarIzin = $query->orderByDesc('tanggal_mulai')->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('satpam.cek_izin', compact('daftarIzin', 'filterStatus'));
    }

    /* ==========================================================================
       Helper
       ========================================================================== */
    private function kelasLabel($kelas): string
    {
        if (!$kelas) {
            return '-';
        }

        return trim(($kelas->tingkat ?? '') . ' ' . optional($kelas->jurusan)->kode_jurusan . ' ' . ($kelas->rombel ?? ''));
    }
}