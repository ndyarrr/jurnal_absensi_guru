<?php

namespace App\Http\Controllers;

use App\Models\IzinGuru;
use App\Models\JadwalPelajaran;
use App\Models\JurnalMengajar;
use App\Models\SuratDispensasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Dashboard & halaman persetujuan izin untuk Waka dan Waka Kurikulum.
 *
 * - Izin Guru  : model IzinGuru
 * - Izin Siswa : model SuratDispensasi (tipe siswa)
 *
 * Aksi setujui / tolak / batalkan tetap memakai ApproverDashboardController
 * (route approver.izin.* dan approver.dispensasi.*).
 */
class WakaDashboardController extends Controller
{
    private const HARI = [
        1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu',
    ];

    /** Kolom status & tanggal keputusan milik role yang sedang login. */
    private function tahap(): array
    {
        if (auth()->user()->role === 'waka') {
            return [
                'key' => 'waka',
                'label' => 'Waka',
                'status' => 'status_waka',
                'tgl' => 'tgl_disetujui_waka',
            ];
        }

        return [
            'key' => 'kur',
            'label' => 'Waka Kurikulum',
            'status' => 'status_waka_kurikulum',
            'tgl' => 'tgl_disetujui_waka_kurikulum',
        ];
    }

    /** Permohonan yang belum diputuskan tahap saya dan belum ditolak siapa pun. */
    private function scopeMenungguSaya($query, array $t)
    {
        return $query->where($t['status'], 'pending')->where('status_approval', '!=', 'ditolak');
    }

    /* ==========================================================================
       DASHBOARD
       ========================================================================== */
    public function index()
    {
        $t = $this->tahap();
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $awalBulan = $now->copy()->startOfMonth();
        $akhirBulan = $now->copy()->endOfMonth();

        /* ---- Statistik ---- */
        $stat = [
            'izin_guru_menunggu' => $this->scopeMenungguSaya(IzinGuru::query(), $t)->count(),
            'izin_siswa_menunggu' => $this->scopeMenungguSaya(SuratDispensasi::query(), $t)->count(),
            'disetujui_bulan_ini' => IzinGuru::where($t['status'], 'disetujui')->whereBetween($t['tgl'], [$awalBulan, $akhirBulan])->count()
                + SuratDispensasi::where($t['status'], 'disetujui')->whereBetween($t['tgl'], [$awalBulan, $akhirBulan])->count(),
            'ditolak_bulan_ini' => IzinGuru::where($t['status'], 'ditolak')->whereBetween($t['tgl'], [$awalBulan, $akhirBulan])->count()
                + SuratDispensasi::where($t['status'], 'ditolak')->whereBetween($t['tgl'], [$awalBulan, $akhirBulan])->count(),
        ];

        /* ---- Perlu keputusan (5 terbaru) ---- */
        $izinGuruPerlu = $this->scopeMenungguSaya(IzinGuru::with('guru'), $t)
            ->orderBy('tanggal_mulai')->limit(5)->get();

        $izinSiswaPerlu = $this->scopeMenungguSaya(
            SuratDispensasi::with(['siswa.kelas', 'siswaList', 'kelas']), $t
        )->orderByDesc('created_at')->limit(5)->get();

        /* ---- Guru izin & siswa dispensasi hari ini ---- */
        $izinHariIni = IzinGuru::with('guru')->berlakuPada($today)->orderBy('tanggal_selesai')->get();
        $idGuruIzin = $izinHariIni->pluck('id_guru')->unique()->all();

        $dispensasiHariIni = SuratDispensasi::with(['siswa.kelas', 'siswaList', 'kelas'])
            ->where('status_approval', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->orderBy('jam_mulai')
            ->get();

        /* ---- Kehadiran & jurnal mengajar hari ini ---- */
        $hariIni = self::HARI[$now->dayOfWeek] ?? null; // null = Minggu
        $jadwalHariIni = collect();
        $jurnalByJadwal = collect();

        if ($hariIni) {
            $jadwalHariIni = JadwalPelajaran::with(['guru', 'kelas.jurusan', 'mapel', 'jamPelajaran'])
                ->where('hari', $hariIni)->get();

            $jurnalByJadwal = JurnalMengajar::whereDate('tanggal', $today)
                ->whereIn('id_jadwal', $jadwalHariIni->pluck('id_jadwal'))
                ->get()->keyBy('id_jadwal');
        }

        $jamSekarang = $now->format('H:i:s');
        $jadwalHariIni = $jadwalHariIni->map(function ($j) use ($jurnalByJadwal, $jamSekarang, $idGuruIzin) {
            $j->terisi = $jurnalByJadwal->has($j->id_jadwal);
            $selesai = optional($j->jamPelajaran)->jam_selesai;
            $j->sudah_lewat = $selesai ? ($selesai <= $jamSekarang) : false;
            $j->guru_izin = in_array($j->id_guru, $idGuruIzin, true);
            return $j;
        });

        $sesiTotal = $jadwalHariIni->count();
        $sesiTerisi = $jadwalHariIni->where('terisi', true)->count();
        $sesiIzin = $jadwalHariIni->where('guru_izin', true)->where('terisi', false)->count();
        $sesiBelum = $jadwalHariIni->filter(fn ($j) => ! $j->terisi && ! $j->guru_izin && $j->sudah_lewat)->count();

        $kehadiran = [
            'guru_terjadwal' => $jadwalHariIni->pluck('id_guru')->unique()->count(),
            'guru_izin' => $jadwalHariIni->where('guru_izin', true)->pluck('id_guru')->unique()->count(),
            'sesi_total' => $sesiTotal,
            'sesi_terisi' => $sesiTerisi,
            'sesi_izin' => $sesiIzin,
            'sesi_belum' => $sesiBelum,
            'sesi_akan_datang' => max($sesiTotal - $sesiTerisi - $sesiIzin - $sesiBelum, 0),
            'persen_terisi' => $sesiTotal > 0 ? (int) round(($sesiTerisi / $sesiTotal) * 100) : 0,
        ];

        $statusJurnal = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Tanpa Keterangan' => 0];
        foreach ($jurnalByJadwal as $jurnal) {
            if (isset($statusJurnal[$jurnal->status_kehadiran])) {
                $statusJurnal[$jurnal->status_kehadiran]++;
            }
        }

        $jurnalBelumDiisi = $jadwalHariIni
            ->filter(fn ($j) => ! $j->terisi && ! $j->guru_izin && $j->sudah_lewat)
            ->sortBy(fn ($j) => optional($j->jamPelajaran)->jam_mulai ?? '99:99:99')
            ->values();

        return view('approver.waka_dashboard', [
            'tahap' => $t,
            'now' => $now,
            'hariIni' => $hariIni,
            'stat' => $stat,
            'izinGuruPerlu' => $izinGuruPerlu,
            'izinSiswaPerlu' => $izinSiswaPerlu,
            'izinHariIni' => $izinHariIni,
            'dispensasiHariIni' => $dispensasiHariIni,
            'kehadiran' => $kehadiran,
            'statusJurnal' => $statusJurnal,
            'jurnalBelumDiisi' => $jurnalBelumDiisi,
            'tren' => $this->trenEnamBulan($now),
        ]);
    }

    /** Jumlah pengajuan izin guru & dispensasi siswa per bulan (6 bulan terakhir). */
    private function trenEnamBulan(Carbon $now): array
    {
        $mulai = $now->copy()->startOfMonth()->subMonths(5);

        $tren = [];
        for ($i = 0; $i < 6; $i++) {
            $bulan = $mulai->copy()->addMonths($i);
            $tren[$bulan->format('Y-m')] = ['label' => $bulan->translatedFormat('M Y'), 'guru' => 0, 'siswa' => 0];
        }

        foreach (IzinGuru::where('tanggal_mulai', '>=', $mulai->toDateString())->get(['tanggal_mulai']) as $row) {
            $key = $row->tanggal_mulai->format('Y-m');
            if (isset($tren[$key])) {
                $tren[$key]['guru']++;
            }
        }

        foreach (SuratDispensasi::where('tanggal_mulai', '>=', $mulai->toDateString())->get(['tanggal_mulai']) as $row) {
            $key = Carbon::parse($row->tanggal_mulai)->format('Y-m');
            if (isset($tren[$key])) {
                $tren[$key]['siswa']++;
            }
        }

        return array_values($tren);
    }

    /* ==========================================================================
       IZIN GURU
       ========================================================================== */
    public function izinGuruIndex(Request $request)
    {
        $t = $this->tahap();
        $filter = $request->input('status', 'perlu'); // perlu | disetujui | ditolak | semua
        $search = trim((string) $request->input('search', ''));

        $counts = [
            'perlu' => $this->scopeMenungguSaya(IzinGuru::query(), $t)->count(),
            'disetujui' => IzinGuru::where('status_approval', 'disetujui')->count(),
            'ditolak' => IzinGuru::where('status_approval', 'ditolak')->count(),
            'semua' => IzinGuru::count(),
        ];

        $query = IzinGuru::with(['guru', 'approverWaka', 'approverWakaKurikulum', 'approverKepsek']);

        match ($filter) {
            'perlu' => $this->scopeMenungguSaya($query, $t),
            'disetujui' => $query->where('status_approval', 'disetujui'),
            'ditolak' => $query->where('status_approval', 'ditolak'),
            default => null,
        };

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('guru', fn ($g) => $g->where('nama_guru', 'like', "%{$search}%"))
                  ->orWhere('alasan_izin', 'like', "%{$search}%")
                  ->orWhere('kategori_izin', 'like', "%{$search}%");
            });
        }

        $izinList = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('approver.izin_guru_index', compact('izinList', 'filter', 'search', 'counts', 't'));
    }

    public function izinGuruShow($id)
    {
        $t = $this->tahap();

        $izin = IzinGuru::with(['guru', 'approverWaka', 'approverWakaKurikulum', 'approverKepsek'])
            ->where('id_izin_guru', $id)
            ->firstOrFail();

        $riwayat = IzinGuru::where('id_guru', $izin->id_guru)
            ->where('id_izin_guru', '!=', $izin->id_izin_guru)
            ->orderByDesc('tanggal_mulai')
            ->limit(5)
            ->get();

        $hariIzinTahunIni = IzinGuru::where('id_guru', $izin->id_guru)
            ->where('status_approval', 'disetujui')
            ->whereYear('tanggal_mulai', $izin->tanggal_mulai->year)
            ->get()
            ->sum(fn ($i) => $i->durasi_hari);

        return view('approver.izin_guru_show', compact('izin', 'riwayat', 'hariIzinTahunIni', 't'));
    }

    /* ==========================================================================
       IZIN SISWA (dispensasi)
       ========================================================================== */
    public function izinSiswaIndex(Request $request)
    {
        $t = $this->tahap();
        $filter = $request->input('status', 'perlu');
        $search = trim((string) $request->input('search', ''));

        $counts = [
            'perlu' => $this->scopeMenungguSaya(SuratDispensasi::query(), $t)->count(),
            'disetujui' => SuratDispensasi::where('status_approval', 'disetujui')->count(),
            'ditolak' => SuratDispensasi::where('status_approval', 'ditolak')->count(),
            'semua' => SuratDispensasi::count(),
        ];

        $query = SuratDispensasi::with(['siswa.kelas', 'siswaList', 'kelas', 'approverWaka', 'approverWakaKurikulum']);

        match ($filter) {
            'perlu' => $this->scopeMenungguSaya($query, $t),
            'disetujui' => $query->where('status_approval', 'disetujui'),
            'ditolak' => $query->where('status_approval', 'ditolak'),
            default => null,
        };

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama_kegiatan', 'like', "%{$search}%")
                  ->orWhere('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('alasan_dispensasi', 'like', "%{$search}%")
                  ->orWhereHas('siswa', fn ($s) => $s->where('nama_siswa', 'like', "%{$search}%"))
                  ->orWhereHas('siswaList.siswa', fn ($s) => $s->where('nama_siswa', 'like', "%{$search}%"));
            });
        }

        $dispenList = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('approver.izin_siswa_index', compact('dispenList', 'filter', 'search', 'counts', 't'));
    }

    public function izinSiswaShow($id)
    {
        $t = $this->tahap();

        $dispen = SuratDispensasi::with([
            'siswa.kelas', 'siswaList', 'kelas', 'guru',
            'approverWaka', 'approverWakaKurikulum',
        ])->where('id_dispen', $id)->firstOrFail();

        return view('approver.izin_siswa_show', compact('dispen', 't'));
    }
}