<?php

namespace App\Http\Controllers;

use App\Models\SuratDispensasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SatpamController extends Controller
{
    /**
     * Dashboard Satpam: hanya untuk pemantauan.
     */
    public function dashboard(Request $request)
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $dispenHariIni = SuratDispensasi::with([
            'siswa.kelas.jurusan',
            'siswaList.siswa.kelas.jurusan',
        ])
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->orderByDesc('created_at')
            ->get();

        // Jumlah di bawah ini adalah jumlah SURAT, bukan jumlah siswa.
        $stats = [
            'total_dispen' => $dispenHariIni->count(),
            'disetujui' => $dispenHariIni
                ->where('status_approval', 'disetujui')->count(),
            'pending' => $dispenHariIni
                ->where('status_approval', 'pending')->count(),
            'ditolak' => $dispenHariIni
                ->where('status_approval', 'ditolak')->count(),
        ];

        // Aktivitas hanya untuk pemantauan status surat.
        $aktivitasGerbang = $dispenHariIni->take(6)->map(
            function ($dispen) {
                $daftarSiswa = $this->ambilDaftarSiswa($dispen);

                $namaSiswa = $daftarSiswa->pluck('nama_siswa')
                    ->implode(', ');

                $kelas = $daftarSiswa->map(function ($siswa) {
                    return $this->kelasLabel($siswa->kelas);
                })->unique()->implode(', ');

                $jamMulai = $dispen->jam_mulai
                    ? Carbon::parse($dispen->jam_mulai)->format('H:i')
                    : '-';

                $jamSelesai = $dispen->jam_selesai
                    ? Carbon::parse($dispen->jam_selesai)->format('H:i')
                    : '-';

                return [
                    'nama_siswa' => $namaSiswa ?: 'Data siswa belum tersedia',
                    'kelas' => $kelas ?: '-',
                    'aktivitas' => 'Status surat dispensasi',
                    'keterangan' => $dispen->nama_kegiatan
                        ?? $dispen->alasan_dispensasi
                        ?? '-',
                    'waktu' => $jamMulai . (
                        $jamSelesai !== '-' ? " s/d {$jamSelesai}" : ''
                    ),
                    'status' => $dispen->status_approval,
                ];
            }
        );

        // Hanya surat yang telah disetujui yang masuk daftar pemeriksaan.
        $siswaIzinKeluarHariIni = $dispenHariIni
            ->where('status_approval', 'disetujui')
            ->take(6);

        return view('satpam.dashboard', compact(
            'stats',
            'aktivitasGerbang',
            'siswaIzinKeluarHariIni'
        ));
    }

    /**
     * Halaman pemeriksaan surat dispensasi.
     */
    public function cekIzin(Request $request)
    {
        $query = SuratDispensasi::with([
            'siswa.kelas.jurusan',
            'siswaList.siswa.kelas.jurusan',
        ]);

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->whereHas('siswa', function ($sq) use ($search) {
                    $sq->where(
                        'nama_siswa',
                        'like',
                        "%{$search}%"
                    );
                })
                ->orWhereHas('siswaList.siswa', function ($sq) use ($search) {
                    $sq->where(
                        'nama_siswa',
                        'like',
                        "%{$search}%"
                    );
                })
                ->orWhere('nama_kegiatan', 'like', "%{$search}%")
                ->orWhere(
                    'alasan_dispensasi',
                    'like',
                    "%{$search}%"
                );
            });
        }

        $filterStatus = $request->input('status', 'semua');

        if (in_array($filterStatus, [
            'disetujui',
            'pending',
            'ditolak',
        ], true)) {
            $query->where('status_approval', $filterStatus);
        } else {
            $filterStatus = 'semua';
        }

        $daftarIzin = $query
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('satpam.cek_izin', compact(
            'daftarIzin',
            'filterStatus'
        ));
    }

    /**
     * Ambil semua siswa dalam surat, dengan fallback untuk surat lama.
     */
    private function ambilDaftarSiswa($dispen)
    {
        $daftarSiswa = $dispen->siswaList
            ->map(fn ($detail) => $detail->siswa)
            ->filter()
            ->unique('id_siswa')
            ->values();

        if ($daftarSiswa->isEmpty() && $dispen->siswa) {
            $daftarSiswa = collect([$dispen->siswa]);
        }

        return $daftarSiswa;
    }

    private function kelasLabel($kelas): string
    {
        if (!$kelas) {
            return '-';
        }

        return trim(
            ($kelas->tingkat ?? '') . ' ' .
            (optional($kelas->jurusan)->kode_jurusan ?? '') . ' ' .
            ($kelas->rombel ?? '')
        );
    }
}

