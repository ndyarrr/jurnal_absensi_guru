<?php

namespace App\Http\Controllers;

use App\Models\DetailKetidakhadiran;
use App\Models\JadwalPelajaran;
use App\Models\JurnalMengajar;
use App\Models\PermohonanIzin;
use App\Models\Siswa;
use App\Models\SuratDispensasi;
use App\Models\SuratIzinMasuk;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Portal Orang Tua (read-only).
 *
 * Semua data selalu dibatasi ke anak yang ditautkan ke akun yang sedang login
 * (tabel orang_tua_siswa). Parameter ?anak=<id_siswa> yang bukan milik akun
 * ini ditolak dengan 403, sehingga orang tua tidak bisa membuka data siswa lain.
 */
class OrangTuaController extends Controller
{
    private const HARI = [
        'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
    ];

    /* ==========================================================================
       1. DASHBOARD
       ========================================================================== */
    public function dashboard(Request $request)
    {
        [$anakList, $anak] = $this->resolveAnak($request);

        if (! $anak) {
            return view('orang_tua.dashboard', ['anakList' => $anakList, 'anak' => null]);
        }

        Carbon::setLocale('id');
        $now = Carbon::now('Asia/Jakarta');
        $todayStr = $now->toDateString();
        $namaHari = self::HARI[$now->format('l')] ?? 'Senin';

        // Jadwal hari ini + status kehadiran anak di tiap jam pelajaran
        $jadwalHariIni = JadwalPelajaran::with(['mapel', 'guru', 'jamPelajaran'])
            ->where('id_kelas', $anak->id_kelas)
            ->where('hari', $namaHari)
            ->orderBy('jam_ke')
            ->get();

        $jurnalHariIni = JurnalMengajar::with(['detailKetidakhadiran' => function ($q) use ($anak) {
                $q->where('id_siswa', $anak->id_siswa);
            }])
            ->whereIn('id_jadwal', $jadwalHariIni->pluck('id_jadwal'))
            ->whereDate('tanggal', $todayStr)
            ->get()
            ->keyBy('id_jadwal');

        $pelajaranHariIni = $jadwalHariIni->map(function ($jadwal) use ($jurnalHariIni) {
            $jurnal = $jurnalHariIni->get($jadwal->id_jadwal);
            $detail = $jurnal ? $jurnal->detailKetidakhadiran->first() : null;

            if (! $jurnal) {
                $status = ['key' => 'belum', 'label' => 'Belum diisi guru'];
            } elseif ($detail) {
                $status = $this->labelKetidakhadiran($detail);
            } else {
                $status = ['key' => 'hadir', 'label' => 'Hadir'];
            }

            return [
                'jam' => $this->jamLabel($jadwal),
                'mapel' => optional($jadwal->mapel)->nama_mapel ?? '-',
                'guru' => optional($jadwal->guru)->nama_guru ?? '-',
                'materi' => $jurnal->materi ?? null,
                'status' => $status,
            ];
        });

        $rekapBulanIni = $this->rekapBulan($anak, $now->year, $now->month);

        // Ringkasan status hari ini
        $adaJurnalHariIni = $jurnalHariIni->isNotEmpty();
        $absenHariIni = $pelajaranHariIni->filter(fn ($p) => ! in_array($p['status']['key'], ['hadir', 'belum'], true));
        if (! $adaJurnalHariIni) {
            $statusHariIni = ['key' => 'belum', 'label' => 'Belum ada data', 'detail' => 'Guru belum mengisi jurnal hari ini.'];
        } elseif ($absenHariIni->isEmpty()) {
            $statusHariIni = ['key' => 'hadir', 'label' => 'Hadir', 'detail' => 'Tercatat hadir di semua jam pelajaran yang sudah terisi.'];
        } else {
            $statusHariIni = [
                'key' => 'absen',
                'label' => 'Tidak hadir di ' . $absenHariIni->count() . ' jam pelajaran',
                'detail' => $absenHariIni->pluck('mapel')->unique()->implode(', '),
            ];
        }

        $aktivitasTerbaru = $this->aktivitasTerbaru($anak);

        return view('orang_tua.dashboard', compact(
            'anakList', 'anak', 'now', 'namaHari', 'pelajaranHariIni',
            'rekapBulanIni', 'statusHariIni', 'aktivitasTerbaru'
        ));
    }

    /* ==========================================================================
       2. REKAP KEHADIRAN BULANAN
       ========================================================================== */
    public function kehadiran(Request $request)
    {
        [$anakList, $anak] = $this->resolveAnak($request);

        if (! $anak) {
            return view('orang_tua.kehadiran', ['anakList' => $anakList, 'anak' => null]);
        }

        Carbon::setLocale('id');
        $nowDate = Carbon::now('Asia/Jakarta');

        $month = max(1, min(12, (int) $request->input('month', $nowDate->month)));
        $year = max(2000, min(2100, (int) $request->input('year', $nowDate->year)));

        $dateObj = Carbon::create($year, $month, 1, 0, 0, 0, 'Asia/Jakarta');
        $prev = $dateObj->copy()->subMonth();
        $next = $dateObj->copy()->addMonth();

        $rekap = $this->rekapBulan($anak, $year, $month);

        // Kelompokkan ketidakhadiran per tanggal
        $riwayatPerTanggal = $rekap['details']
            ->groupBy(fn ($d) => $d->jurnal->tanggal instanceof Carbon
                ? $d->jurnal->tanggal->toDateString()
                : (string) $d->jurnal->tanggal)
            ->map(function ($items, $tanggal) {
                return [
                    'tanggal' => Carbon::parse($tanggal, 'Asia/Jakarta'),
                    'items' => $items->map(function ($d) {
                        $jadwal = optional($d->jurnal)->jadwal;

                        return [
                            'jam' => $jadwal ? $this->jamLabel($jadwal) : '-',
                            'mapel' => optional(optional($jadwal)->mapel)->nama_mapel ?? 'Mata Pelajaran',
                            'status' => $this->labelKetidakhadiran($d),
                            'catatan' => $d->catatan,
                        ];
                    })->values(),
                ];
            })
            ->sortByDesc(fn ($row) => $row['tanggal']->timestamp)
            ->values();

        $monthLabel = $dateObj->translatedFormat('F Y');
        $prevParams = ['month' => $prev->month, 'year' => $prev->year, 'anak' => $anak->id_siswa];
        $nextParams = ['month' => $next->month, 'year' => $next->year, 'anak' => $anak->id_siswa];
        $isBulanIni = $dateObj->isSameMonth($nowDate);

        return view('orang_tua.kehadiran', compact(
            'anakList', 'anak', 'rekap', 'riwayatPerTanggal',
            'monthLabel', 'prevParams', 'nextParams', 'isBulanIni'
        ));
    }

    /* ==========================================================================
       3. SURAT IZIN, DISPENSASI & IZIN MASUK KELAS
       ========================================================================== */
    public function surat(Request $request)
    {
        [$anakList, $anak] = $this->resolveAnak($request);

        if (! $anak) {
            return view('orang_tua.surat', ['anakList' => $anakList, 'anak' => null]);
        }

        Carbon::setLocale('id');

        $permohonan = PermohonanIzin::where('id_siswa', $anak->id_siswa)
            ->orderByDesc('id_permohonan')
            ->limit(50)
            ->get()
            ->map(fn ($p) => [
                'jenis' => ucfirst($p->jenis_izin ?? 'Izin'),
                'tanggal' => $this->rentangTanggal($p->tanggal_mulai, $p->tanggal_selesai),
                'alasan' => $p->alasan,
                'status' => $this->labelStatusIzin($p->status),
                'diajukan' => $p->created_at ? Carbon::parse($p->created_at)->timezone('Asia/Jakarta')->translatedFormat('d M Y, H:i') : '-',
            ]);

        $dispensasi = SuratDispensasi::where('id_siswa', $anak->id_siswa)
            ->orderByDesc('id_dispen')
            ->limit(50)
            ->get()
            ->map(fn ($d) => [
                'kegiatan' => $d->nama_kegiatan ?? 'Dispensasi',
                'lokasi' => $d->lokasi_kegiatan,
                'tanggal' => $this->rentangTanggal($d->tanggal_mulai, $d->tanggal_selesai),
                'jam' => ($d->jam_mulai ? Carbon::parse($d->jam_mulai)->format('H:i') : null)
                    . ($d->jam_selesai ? ' - ' . Carbon::parse($d->jam_selesai)->format('H:i') : ''),
                'alasan' => $d->alasan_dispensasi,
                'status' => $this->labelStatusDispensasi($d->status_approval),
            ]);

        $izinMasuk = SuratIzinMasuk::where('id_siswa', $anak->id_siswa)
            ->orderByDesc('tanggal')
            ->orderByDesc('id_surat_izin_masuk')
            ->limit(50)
            ->get()
            ->map(fn ($s) => [
                'nomor' => $s->nomor_surat,
                'tanggal' => $s->tanggal ? $s->tanggal->translatedFormat('d M Y') : '-',
                'jam_ke' => $s->jam_pelajaran_ke,
                'alasan' => $s->alasan,
            ]);

        return view('orang_tua.surat', compact('anakList', 'anak', 'permohonan', 'dispensasi', 'izinMasuk'));
    }

    /* ==========================================================================
       Helpers
       ========================================================================== */

    /**
     * Ambil daftar anak milik akun ini dan tentukan anak yang sedang dilihat.
     *
     * @return array{0: \Illuminate\Support\Collection, 1: ?Siswa}
     */
    private function resolveAnak(Request $request): array
    {
        $anakList = $request->user()
            ->anak()
            ->with('kelas.jurusan')
            ->orderBy('nama_siswa')
            ->get();

        if ($request->filled('anak')) {
            $anak = $anakList->firstWhere('id_siswa', (int) $request->input('anak'));
            abort_if(! $anak, 403, 'Anda tidak memiliki akses ke data siswa tersebut.');

            return [$anakList, $anak];
        }

        return [$anakList, $anakList->first()];
    }

    /**
     * Rekap kehadiran satu anak untuk satu bulan.
     * Satu baris detail_ketidakhadiran = satu jam pelajaran yang tidak dihadiri.
     */
    private function rekapBulan(Siswa $anak, int $year, int $month): array
    {
        $start = Carbon::create($year, $month, 1, 0, 0, 0, 'Asia/Jakarta')->startOfMonth();
        $end = $start->copy()->endOfMonth();

        $jurnalIds = JurnalMengajar::whereBetween('tanggal', [$start->toDateString(), $end->toDateString()])
            ->whereHas('jadwal', fn ($q) => $q->where('id_kelas', $anak->id_kelas))
            ->pluck('id_jurnal');

        $details = $jurnalIds->isEmpty()
            ? collect()
            : DetailKetidakhadiran::with(['jurnal.jadwal.mapel', 'jurnal.jadwal.jamPelajaran'])
                ->where('id_siswa', $anak->id_siswa)
                ->whereIn('id_jurnal', $jurnalIds)
                ->get();

        $count = ['sakit' => 0, 'izin' => 0, 'dispensasi' => 0, 'alpa' => 0];
        foreach ($details as $d) {
            $key = $this->labelKetidakhadiran($d)['key'];
            if (isset($count[$key])) {
                $count[$key]++;
            }
        }

        $total = $jurnalIds->count();
        $tidakHadir = array_sum($count);
        $hadir = max(0, $total - $tidakHadir);

        return [
            'total_pertemuan' => $total,
            'hadir' => $hadir,
            'sakit' => $count['sakit'],
            'izin' => $count['izin'],
            'dispensasi' => $count['dispensasi'],
            'alpa' => $count['alpa'],
            'persen' => $total > 0 ? (int) round(($hadir / $total) * 100) : null,
            'details' => $details,
        ];
    }

    /** Gabungan aktivitas terbaru anak (absen, izin, dispensasi, izin masuk kelas). */
    private function aktivitasTerbaru(Siswa $anak, int $limit = 8)
    {
        $items = collect();

        $absen = DetailKetidakhadiran::with(['jurnal.jadwal.mapel'])
            ->where('id_siswa', $anak->id_siswa)
            ->orderByDesc('id_detail')
            ->limit(6)
            ->get();
        foreach ($absen as $d) {
            if (! $d->jurnal) {
                continue;
            }
            $status = $this->labelKetidakhadiran($d);
            $items->push([
                'waktu' => Carbon::parse($d->jurnal->tanggal, 'Asia/Jakarta')->startOfDay(),
                'judul' => $status['label'] . ' - ' . (optional(optional($d->jurnal->jadwal)->mapel)->nama_mapel ?? 'Mata Pelajaran'),
                'detail' => $d->catatan ?: 'Dicatat dalam jurnal mengajar.',
                'badge' => $status['key'],
                'badge_label' => $status['label'],
            ]);
        }

        $izin = PermohonanIzin::where('id_siswa', $anak->id_siswa)->orderByDesc('id_permohonan')->limit(4)->get();
        foreach ($izin as $p) {
            $st = $this->labelStatusIzin($p->status);
            $items->push([
                'waktu' => Carbon::parse($p->created_at ?? $p->tanggal_mulai, 'Asia/Jakarta'),
                'judul' => 'Pengajuan ' . ucfirst($p->jenis_izin ?? 'izin'),
                'detail' => $p->alasan,
                'badge' => $st['key'],
                'badge_label' => $st['label'],
            ]);
        }

        $dispen = SuratDispensasi::where('id_siswa', $anak->id_siswa)->orderByDesc('id_dispen')->limit(4)->get();
        foreach ($dispen as $d) {
            $st = $this->labelStatusDispensasi($d->status_approval);
            $items->push([
                'waktu' => Carbon::parse($d->created_at ?? $d->tanggal_mulai, 'Asia/Jakarta'),
                'judul' => 'Dispensasi: ' . ($d->nama_kegiatan ?? 'Kegiatan'),
                'detail' => $d->alasan_dispensasi ?: ($d->lokasi_kegiatan ?: '-'),
                'badge' => $st['key'],
                'badge_label' => $st['label'],
            ]);
        }

        $izinMasuk = SuratIzinMasuk::where('id_siswa', $anak->id_siswa)->orderByDesc('id_surat_izin_masuk')->limit(4)->get();
        foreach ($izinMasuk as $s) {
            $items->push([
                'waktu' => ($s->created_at ?? $s->tanggal)->copy()->timezone('Asia/Jakarta'),
                'judul' => 'Surat izin masuk kelas (jam ' . $s->jam_pelajaran_ke . ')',
                'detail' => $s->alasan,
                'badge' => 'info',
                'badge_label' => 'Diterbitkan',
            ]);
        }

        return $items->sortByDesc(fn ($i) => $i['waktu']->timestamp)->take($limit)->values();
    }

    /**
     * Terjemahkan satu catatan ketidakhadiran menjadi key + label.
     * Kolom `kategori` adalah sumber utama; `status` (S/I/A) hanya cadangan.
     */
    private function labelKetidakhadiran(DetailKetidakhadiran $d): array
    {
        $kategori = strtolower((string) $d->kategori);

        return match ($kategori) {
            'sakit' => ['key' => 'sakit', 'label' => 'Sakit'],
            'izin_ortu' => ['key' => 'izin', 'label' => 'Izin'],
            'dispensasi' => [
                'key' => 'dispensasi',
                'label' => $d->jenis_dispen ? 'Dispensasi ' . $d->jenis_dispen : 'Dispensasi',
            ],
            'alpa' => ['key' => 'alpa', 'label' => 'Alpa'],
            default => match (strtoupper((string) $d->status)) {
                'S' => ['key' => 'sakit', 'label' => 'Sakit'],
                'I' => ['key' => 'izin', 'label' => 'Izin'],
                default => ['key' => 'alpa', 'label' => 'Alpa'],
            },
        };
    }

    private function labelStatusIzin(?string $status): array
    {
        return match (true) {
            $status === 'pending' => ['key' => 'pending', 'label' => 'Menunggu'],
            $status === 'rejected' => ['key' => 'ditolak', 'label' => 'Ditolak'],
            str_starts_with((string) $status, 'approved') => ['key' => 'disetujui', 'label' => 'Disetujui'],
            default => ['key' => 'pending', 'label' => ucfirst((string) $status) ?: 'Menunggu'],
        };
    }

    private function labelStatusDispensasi(?string $status): array
    {
        return match (true) {
            $status === 'disetujui' => ['key' => 'disetujui', 'label' => 'Disetujui'],
            $status === 'ditolak' => ['key' => 'ditolak', 'label' => 'Ditolak'],
            default => ['key' => 'pending', 'label' => 'Menunggu persetujuan'],
        };
    }

    private function jamLabel(JadwalPelajaran $jadwal): string
    {
        $jam = $jadwal->jamPelajaran;
        if ($jam && $jam->jam_mulai) {
            $label = Carbon::parse($jam->jam_mulai)->format('H:i');
            if ($jam->jam_selesai) {
                $label .= ' - ' . Carbon::parse($jam->jam_selesai)->format('H:i');
            }

            return $label;
        }

        return $jadwal->jam_ke ? 'Jam ke-' . $jadwal->jam_ke : '-';
    }

    private function rentangTanggal($mulai, $selesai): string
    {
        if (! $mulai) {
            return '-';
        }

        $a = Carbon::parse($mulai);
        $b = $selesai ? Carbon::parse($selesai) : $a;

        return $a->isSameDay($b)
            ? $a->translatedFormat('d M Y')
            : $a->translatedFormat('d M') . ' - ' . $b->translatedFormat('d M Y');
    }
}