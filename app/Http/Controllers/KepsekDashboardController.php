<?php

namespace App\Http\Controllers;

use App\Models\IzinGuru;
use App\Models\JadwalPelajaran;
use App\Models\JurnalMengajar;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class KepsekDashboardController extends Controller
{
    private const HARI = [
        1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu',
    ];

    /* ==========================================================================
       DASHBOARD KEPALA SEKOLAH
       ========================================================================== */
    public function index()
    {
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();
        $awalBulan = $now->copy()->startOfMonth();
        $akhirBulan = $now->copy()->endOfMonth();

        /* ---- Statistik izin ---- */
        $stat = [
            'menunggu_saya' => IzinGuru::menungguKepsek()->count(),
            'disetujui_bulan_ini' => IzinGuru::where('status_kepsek', 'disetujui')
                ->whereBetween('tgl_disetujui_kepsek', [$awalBulan, $akhirBulan])->count(),
            'ditolak_bulan_ini' => IzinGuru::where('status_kepsek', 'ditolak')
                ->whereBetween('tgl_disetujui_kepsek', [$awalBulan, $akhirBulan])->count(),
        ];

        /* ---- Izin yang perlu diputuskan (5 terbaru) ---- */
        $perluKeputusan = IzinGuru::with('guru')
            ->menungguKepsek()
            ->orderBy('tanggal_mulai')
            ->limit(5)
            ->get();

        /* ---- Guru yang sedang izin hari ini ---- */
        $izinHariIni = IzinGuru::with('guru')
            ->berlakuPada($today)
            ->orderBy('tanggal_selesai')
            ->get();
        $idGuruIzin = $izinHariIni->pluck('id_guru')->unique()->all();

        /* ---- Kehadiran & jurnal mengajar hari ini ---- */
        $hariIni = self::HARI[$now->dayOfWeek] ?? null; // null = hari Minggu
        $jadwalHariIni = collect();
        $jurnalByJadwal = collect();

        if ($hariIni) {
            $jadwalHariIni = JadwalPelajaran::with(['guru', 'kelas.jurusan', 'mapel', 'jamPelajaran'])
                ->where('hari', $hariIni)
                ->get();

            $jurnalByJadwal = JurnalMengajar::whereDate('tanggal', $today)
                ->whereIn('id_jadwal', $jadwalHariIni->pluck('id_jadwal'))
                ->get()
                ->keyBy('id_jadwal');
        }

        $jamSekarang = $now->format('H:i:s');

        // Tandai tiap sesi: sudah diisi? sudah lewat? guru izin?
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
        $sesiAkanDatang = $sesiTotal - $sesiTerisi - $sesiIzin - $sesiBelum;

        $persenTerisi = $sesiTotal > 0 ? (int) round(($sesiTerisi / $sesiTotal) * 100) : 0;

        $kehadiran = [
            'guru_terjadwal' => $jadwalHariIni->pluck('id_guru')->unique()->count(),
            'guru_izin' => $jadwalHariIni->where('guru_izin', true)->pluck('id_guru')->unique()->count(),
            'sesi_total' => $sesiTotal,
            'sesi_terisi' => $sesiTerisi,
            'sesi_izin' => $sesiIzin,
            'sesi_belum' => $sesiBelum,
            'sesi_akan_datang' => max($sesiAkanDatang, 0),
            'persen_terisi' => $persenTerisi,
        ];

        // Rincian status kehadiran guru dari jurnal yang sudah masuk hari ini
        $statusJurnal = [
            'Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Tanpa Keterangan' => 0,
        ];
        foreach ($jurnalByJadwal as $jurnal) {
            if (isset($statusJurnal[$jurnal->status_kehadiran])) {
                $statusJurnal[$jurnal->status_kehadiran]++;
            }
        }

        // Sesi yang sudah lewat tetapi jurnalnya belum diisi (dan gurunya tidak izin)
        $jurnalBelumDiisi = $jadwalHariIni
            ->filter(fn ($j) => ! $j->terisi && ! $j->guru_izin && $j->sudah_lewat)
            ->sortBy(fn ($j) => optional($j->jamPelajaran)->jam_mulai ?? '99:99:99')
            ->values();

        /* ---- Tren izin 6 bulan terakhir ---- */
        $tren = $this->trenIzinEnamBulan($now);

        return view('kepsek.dashboard', [
            'now' => $now,
            'hariIni' => $hariIni,
            'stat' => $stat,
            'perluKeputusan' => $perluKeputusan,
            'izinHariIni' => $izinHariIni,
            'kehadiran' => $kehadiran,
            'statusJurnal' => $statusJurnal,
            'jurnalBelumDiisi' => $jurnalBelumDiisi,
            'tren' => $tren,
        ]);
    }

    private function trenIzinEnamBulan(Carbon $now): array
    {
        $mulai = $now->copy()->startOfMonth()->subMonths(5);

        $rows = IzinGuru::where('tanggal_mulai', '>=', $mulai->toDateString())->get(['tanggal_mulai', 'status_approval']);

        $tren = [];
        for ($i = 0; $i < 6; $i++) {
            $bulan = $mulai->copy()->addMonths($i);
            $key = $bulan->format('Y-m');
            $tren[$key] = [
                'label' => $bulan->translatedFormat('M Y'),
                'total' => 0,
                'disetujui' => 0,
                'ditolak' => 0,
            ];
        }

        foreach ($rows as $row) {
            $key = $row->tanggal_mulai->format('Y-m');
            if (! isset($tren[$key])) {
                continue;
            }
            $tren[$key]['total']++;
            if ($row->status_approval === 'disetujui') {
                $tren[$key]['disetujui']++;
            } elseif ($row->status_approval === 'ditolak') {
                $tren[$key]['ditolak']++;
            }
        }

        return array_values($tren);
    }

    /* ==========================================================================
       DAFTAR PERSETUJUAN IZIN GURU
       ========================================================================== */
    public function izinIndex(Request $request)
    {
        $filter = $request->input('status', 'perlu'); // perlu | disetujui | ditolak | semua
        $search = trim((string) $request->input('search', ''));

        $counts = [
            'perlu' => IzinGuru::menungguKepsek()->count(),
            'disetujui' => IzinGuru::where('status_approval', 'disetujui')->count(),
            'ditolak' => IzinGuru::where('status_approval', 'ditolak')->count(),
            'semua' => IzinGuru::count(),
        ];

        $query = IzinGuru::with(['guru', 'approverWaka', 'approverWakaKurikulum', 'approverKepsek']);

        match ($filter) {
            'perlu' => $query->menungguKepsek(),
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

        return view('kepsek.izin_index', compact('izinList', 'filter', 'search', 'counts'));
    }

    /* ==========================================================================
       DETAIL IZIN GURU
       ========================================================================== */
    public function izinShow($id)
    {
        $izin = IzinGuru::with(['guru', 'approverWaka', 'approverWakaKurikulum', 'approverKepsek'])
            ->where('id_izin_guru', $id)
            ->firstOrFail();

        // Riwayat izin guru yang sama (selain permohonan ini) — bahan pertimbangan
        $riwayat = IzinGuru::where('id_guru', $izin->id_guru)
            ->where('id_izin_guru', '!=', $izin->id_izin_guru)
            ->orderByDesc('tanggal_mulai')
            ->limit(5)
            ->get();

        $izinTahunIni = IzinGuru::where('id_guru', $izin->id_guru)
            ->where('status_approval', 'disetujui')
            ->whereYear('tanggal_mulai', $izin->tanggal_mulai->year)
            ->get();
        $hariIzinTahunIni = $izinTahunIni->sum(fn ($i) => $i->durasi_hari);

        // Jadwal mengajar yang terdampak selama periode izin (maks. 60 hari agar ringan)
        $terdampak = $this->jadwalTerdampak($izin);

        return view('kepsek.izin_show', compact(
            'izin', 'riwayat', 'hariIzinTahunIni', 'terdampak'
        ));
    }

    private function jadwalTerdampak(IzinGuru $izin): array
    {
        $hariDalamRentang = [];
        $akhir = $izin->tanggal_selesai->copy();
        $mulai = $izin->tanggal_mulai->copy();
        if ($mulai->diffInDays($akhir) > 60) {
            $akhir = $mulai->copy()->addDays(60);
        }

        foreach (CarbonPeriod::create($mulai, $akhir) as $tgl) {
            $nama = self::HARI[$tgl->dayOfWeek] ?? null;
            if ($nama) {
                $hariDalamRentang[$nama] = ($hariDalamRentang[$nama] ?? 0) + 1;
            }
        }

        if (empty($hariDalamRentang) || ! $izin->id_guru) {
            return ['sesi_per_hari' => collect(), 'total_pertemuan' => 0];
        }

        $jadwal = JadwalPelajaran::with(['kelas.jurusan', 'mapel', 'jamPelajaran'])
            ->where('id_guru', $izin->id_guru)
            ->whereIn('hari', array_keys($hariDalamRentang))
            ->get()
            ->groupBy('hari');

        $total = 0;
        foreach ($jadwal as $hari => $items) {
            $total += $items->count() * $hariDalamRentang[$hari];
        }

        $urut = array_values(self::HARI);
        $sesiPerHari = $jadwal->sortBy(fn ($items, $hari) => array_search($hari, $urut, true));

        return ['sesi_per_hari' => $sesiPerHari, 'total_pertemuan' => $total];
    }
}