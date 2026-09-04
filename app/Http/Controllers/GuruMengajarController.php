<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JadwalPelajaran;
use App\Models\JurnalMengajar;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use App\Support\CsvExporter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuruMengajarController extends Controller
{
    /**
     * Resolve the id_guru of the currently authenticated user.
     * Falls back to matching by name if id_guru is not linked directly,
     * following the same pattern used by GuruPiketController.
     */
    private function resolveGuruId(): ?int
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        if ($user->id_guru) {
            return $user->id_guru;
        }

        if ($user->guru) {
            return $user->guru->id_guru;
        }

        if (!empty($user->name)) {
            $matched = Guru::where('nama_guru', $user->name)->first();
            if ($matched) {
                return $matched->id_guru;
            }
        }

        return null;
    }

    private function kelasLabel(?Kelas $kelas): string
    {
        if (!$kelas) {
            return '-';
        }

        return trim($kelas->tingkat . ' ' . optional($kelas->jurusan)->kode_jurusan . ' ' . $kelas->rombel);
    }

    /**
     * Base query of jadwal (teaching schedule) belonging to the logged-in teacher.
     */
    private function jadwalQuery(?int $idGuru)
    {
        return JadwalPelajaran::with(['kelas' => fn ($q) => $q->withCount('siswa'), 'kelas.jurusan', 'mapel', 'jamPelajaran'])
            ->when($idGuru, fn ($q) => $q->where('id_guru', $idGuru), fn ($q) => $q->whereRaw('1 = 0'));
    }

    /* ==========================================================================
       1. BERANDA (Dashboard)
       ========================================================================== */
    public function dashboard(Request $request)
    {
        $idGuru = $this->resolveGuruId();
        $guru = $idGuru ? Guru::find($idGuru) : null;

        $now = Carbon::now('Asia/Jakarta');
        $hariMap = ['Minggu' => null, 'Senin' => 'Senin', 'Selasa' => 'Selasa', 'Rabu' => 'Rabu', 'Kamis' => 'Kamis', 'Jumat' => 'Jumat', 'Sabtu' => 'Sabtu'];
        $hariIniName = $now->translatedFormat('l');
        $hariIniKey = $hariMap[$hariIniName] ?? null;

        $todayStr = $now->toDateString();

        // Jadwal hari ini
        $jadwalHariIni = $hariIniKey
            ? $this->jadwalQuery($idGuru)->where('hari', $hariIniKey)
                ->orderByRaw('COALESCE((select jam_mulai from jam_pelajaran where jam_pelajaran.id_jam = jadwal_pelajaran.id_jam), "00:00:00") asc')
                ->orderBy('jam_ke')
                ->get()
            : collect();

        $jurnalHariIniByJadwal = JurnalMengajar::whereIn('id_jadwal', $jadwalHariIni->pluck('id_jadwal'))
            ->whereDate('tanggal', $todayStr)
            ->get()
            ->keyBy('id_jadwal');

        $jadwalHariIni = $jadwalHariIni->map(function ($jadwal) use ($jurnalHariIniByJadwal) {
            $existing = $jurnalHariIniByJadwal->get($jadwal->id_jadwal);
            $jadwal->is_filled = (bool) $existing;
            $jadwal->existing_jurnal = $existing;
            return $jadwal;
        });

        $totalJadwalHariIni = $jadwalHariIni->count();
        $jurnalTerisiHariIni = $jadwalHariIni->where('is_filled', true)->count();
        $persentaseHariIni = $totalJadwalHariIni > 0 ? round(($jurnalTerisiHariIni / $totalJadwalHariIni) * 100) : 0;

        // Jadwal jam berikutnya (schedule after current time today)
        $jamSekarang = $now->format('H:i:s');
        $jadwalBerikutnya = $jadwalHariIni
            ->filter(fn ($j) => optional($j->jamPelajaran)->jam_mulai && $j->jamPelajaran->jam_mulai > $jamSekarang)
            ->sortBy(fn ($j) => $j->jamPelajaran->jam_mulai)
            ->first();

        // Jurnal bulan ini (across all this guru's jadwal)
        $allJadwalIds = $this->jadwalQuery($idGuru)->pluck('id_jadwal');
        $jurnalBulanIni = JurnalMengajar::whereIn('id_jadwal', $allJadwalIds)
            ->whereMonth('tanggal', $now->month)
            ->whereYear('tanggal', $now->year)
            ->count();

        $totalJamMinggu = $this->jadwalQuery($idGuru)->count();

        $stats = [
            'total_jadwal_hari_ini'  => $totalJadwalHariIni,
            'jurnal_terisi_hari_ini' => $jurnalTerisiHariIni,
            'persentase_hari_ini'    => $persentaseHariIni,
            'jurnal_bulan_ini'       => $jurnalBulanIni,
            'total_jam_minggu'       => $totalJamMinggu,
        ];

        return view('guru_mengajar.dashboard', compact(
            'guru', 'jadwalHariIni', 'jadwalBerikutnya', 'stats', 'hariIniName', 'now'
        ));
    }

    /* ==========================================================================
       2. JADWAL MENGAJAR (Matriks Mingguan)
       ========================================================================== */
    public function jadwal(Request $request)
    {
        $idGuru = $this->resolveGuruId();
        $guru = $idGuru ? Guru::find($idGuru) : null;

        $daysList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        $allJadwal = $this->jadwalQuery($idGuru)
            ->orderByRaw('COALESCE((select jam_mulai from jam_pelajaran where jam_pelajaran.id_jam = jadwal_pelajaran.id_jam), "00:00:00") asc')
            ->orderBy('jam_ke')
            ->get();

        $weeklySchedules = $allJadwal->groupBy('hari');

        $totalJamMengajar = $allJadwal->count();
        $totalKelasDiampu = $allJadwal->pluck('id_kelas')->unique()->count();
        $totalMapelDiampu = $allJadwal->pluck('id_mapel')->unique()->count();

        return view('guru_mengajar.jadwal', compact(
            'guru', 'daysList', 'weeklySchedules', 'totalJamMengajar', 'totalKelasDiampu', 'totalMapelDiampu'
        ));
    }

    /* ==========================================================================
       3. JURNAL HARIAN
       ========================================================================== */
    public function jurnal(Request $request)
    {
        $idGuru = $this->resolveGuruId();
        $guru = $idGuru ? Guru::find($idGuru) : null;

        $jadwalIds = $this->jadwalQuery($idGuru)->pluck('id_jadwal');

        $query = JurnalMengajar::with(['jadwal.kelas.jurusan', 'jadwal.mapel', 'jadwal.jamPelajaran', 'detailKetidakhadiran'])
            ->whereIn('id_jadwal', $jadwalIds);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('materi', 'like', "%{$search}%")
                  ->orWhereHas('jadwal.mapel', fn ($m) => $m->where('nama_mapel', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->input('tanggal'));
        }

        if ($request->filled('id_kelas')) {
            $query->whereHas('jadwal', fn ($q) => $q->where('id_kelas', $request->input('id_kelas')));
        }

        if ($request->filled('id_mapel')) {
            $query->whereHas('jadwal', fn ($q) => $q->where('id_mapel', $request->input('id_mapel')));
        }

        $jurnalHistory = $query->orderByDesc('tanggal')->paginate(10)->withQueryString();

        // Jadwal yang bisa diisi (untuk dropdown "Isi Jurnal Baru")
        $jadwalList = $this->jadwalQuery($idGuru)->get();

        $allKelas = Kelas::whereIn('id_kelas', $jadwalList->pluck('id_kelas')->unique())
            ->with('jurusan')->orderBy('tingkat')->orderBy('rombel')->get();
        $allMapel = Mapel::whereIn('id_mapel', $jadwalList->pluck('id_mapel')->unique())
            ->orderBy('nama_mapel')->get();

        return view('guru_mengajar.jurnal', compact(
            'guru', 'jurnalHistory', 'jadwalList', 'allKelas', 'allMapel'
        ));
    }

    /**
     * Map Carbon/PHP day-of-week number to Indonesian day name.
     * Carbon uses: 0=Sunday, 1=Monday, ..., 6=Saturday
     */
    private function hariFromDate(string $tanggal): string
    {
        $map = [0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu'];
        return $map[Carbon::parse($tanggal)->dayOfWeek] ?? '';
    }

    /**
     * Dedicated Page for Inputting/Editing Jurnal KBM & Presensi Siswa.
     */
    public function inputJurnal(Request $request)
    {
        $idGuru = $this->resolveGuruId();
        $guru = $idGuru ? Guru::find($idGuru) : null;
        $idJadwal = $request->input('id_jadwal');
        $tanggal = $request->input('tanggal', Carbon::now('Asia/Jakarta')->toDateString());

        if (!$idJadwal) {
            return redirect()->route('guru-mengajar.dashboard')->with('error', 'Pilih jadwal mengajar terlebih dahulu.');
        }

        $jadwal = $this->jadwalQuery($idGuru)->where('id_jadwal', $idJadwal)->first();

        if (!$jadwal) {
            return redirect()->route('guru-mengajar.dashboard')->with('error', 'Jadwal tidak ditemukan atau bukan milik Anda.');
        }

        // Validate that the date's day matches the schedule's day
        $hariTanggal = $this->hariFromDate($tanggal);
        if ($jadwal->hari && $hariTanggal !== $jadwal->hari) {
            return redirect()->route('guru-mengajar.dashboard')
                ->with('error', "Tanggal yang dipilih ({$hariTanggal}, {$tanggal}) tidak sesuai dengan hari jadwal mengajar ini ({$jadwal->hari}).");
        }

        $siswaList = Siswa::where('id_kelas', $jadwal->id_kelas)->orderBy('nama_siswa')->get();
        $siswaIds = $siswaList->pluck('id_siswa');

        // Fetch Surat Izin & Surat Dispensasi for students in this class on $tanggal
        $izinList = \App\Models\PermohonanIzin::where('tipe_pemohon', 'siswa')
            ->whereIn('id_siswa', $siswaIds)
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->where('status', '!=', 'rejected')
            ->get()
            ->keyBy('id_siswa');

        $dispenList = \App\Models\SuratDispensasi::where('tipe_pemohon', 'siswa')
            ->whereIn('id_siswa', $siswaIds)
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->where('status_approval', 'disetujui')
            ->get()
            ->keyBy('id_siswa');

        // Fetch existing journal (if editing)
        $jurnal = JurnalMengajar::where('id_jadwal', $jadwal->id_jadwal)
            ->whereDate('tanggal', $tanggal)
            ->with('detailKetidakhadiran')
            ->first();

        $existingDetails = [];
        if ($jurnal) {
            $statusReverse = ['S' => 'Sakit', 'I' => 'Izin', 'A' => 'Alpa'];
            foreach ($jurnal->detailKetidakhadiran as $d) {
                $existingDetails[$d->id_siswa] = [
                    'status' => $statusReverse[$d->status] ?? 'Alpa',
                    'keterangan' => $d->catatan ?? '',
                ];
            }
        }

        $waktuStr = optional($jadwal->jamPelajaran)->jam_mulai
            ? (Carbon::parse($jadwal->jamPelajaran->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($jadwal->jamPelajaran->jam_selesai)->format('H:i'))
            : '-';
        $kelasName = $this->kelasLabel($jadwal->kelas);
        $mapelName = optional($jadwal->mapel)->nama_mapel ?? '-';
        $jamStr = optional($jadwal->jamPelajaran)->keterangan ?? ('Jam Ke-' . $jadwal->jam_ke);

        return view('guru_mengajar.input_jurnal', compact(
            'guru', 'jadwal', 'tanggal', 'jurnal', 'siswaList', 'izinList', 'dispenList',
            'existingDetails', 'waktuStr', 'kelasName', 'mapelName', 'jamStr'
        ));
    }

    /**
     * AJAX: get siswa list of a jadwal's kelas, plus existing jurnal (if any) for the given date.
     */
    public function getSiswaForJadwal(Request $request, $idJadwal)
    {
        $idGuru = $this->resolveGuruId();

        $jadwal = $this->jadwalQuery($idGuru)->where('id_jadwal', $idJadwal)->first();

        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan atau bukan milik Anda.'], 404);
        }

        $tanggal = $request->input('tanggal', Carbon::now('Asia/Jakarta')->toDateString());

        // Validate that the submitted date's day-of-week matches the jadwal's hari
        $hariTanggal = $this->hariFromDate($tanggal);
        if ($jadwal->hari && $hariTanggal !== $jadwal->hari) {
            return response()->json([
                'message' => "Tanggal yang dipilih ({$hariTanggal}, {$tanggal}) tidak sesuai dengan hari jadwal mengajar ini ({$jadwal->hari}). Pilih tanggal yang jatuh pada hari {$jadwal->hari}.",
            ], 422);
        }

        $siswaList = Siswa::where('id_kelas', $jadwal->id_kelas)->orderBy('nama_siswa')->get();
        $siswaIds = $siswaList->pluck('id_siswa');

        // Deteksi otomatis jika siswa sudah ada surat izin fisik/piket untuk tanggal ini
        $izinList = \App\Models\PermohonanIzin::where('tipe_pemohon', 'siswa')
            ->whereIn('id_siswa', $siswaIds)
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->where('status', '!=', 'rejected')
            ->get()
            ->keyBy('id_siswa');

        // Deteksi otomatis jika siswa memiliki surat dispensasi aktif untuk tanggal ini
        $dispenList = \App\Models\SuratDispensasi::where('tipe_pemohon', 'siswa')
            ->whereIn('id_siswa', $siswaIds)
            ->whereDate('tanggal_mulai', '<=', $tanggal)
            ->whereDate('tanggal_selesai', '>=', $tanggal)
            ->where('status_approval', 'disetujui')
            ->get()
            ->keyBy('id_siswa');

        $jurnal = JurnalMengajar::where('id_jadwal', $jadwal->id_jadwal)
            ->whereDate('tanggal', $tanggal)
            ->with('detailKetidakhadiran')
            ->first();

        $waktuStr = optional($jadwal->jamPelajaran)->jam_mulai
            ? (Carbon::parse($jadwal->jamPelajaran->jam_mulai)->format('H:i') . ' - ' . Carbon::parse($jadwal->jamPelajaran->jam_selesai)->format('H:i'))
            : '-';

        return response()->json([
            'jadwal' => [
                'id_jadwal' => $jadwal->id_jadwal,
                'kelas' => $this->kelasLabel($jadwal->kelas),
                'mapel' => optional($jadwal->mapel)->nama_mapel ?? '-',
                'waktu' => $waktuStr,
                'jam' => optional($jadwal->jamPelajaran)->keterangan ?? ('Jam Ke-' . $jadwal->jam_ke),
            ],
            'siswa' => $siswaList->map(function ($s) use ($izinList, $dispenList) {
                $izin = $izinList->get($s->id_siswa);
                $dispen = $dispenList->get($s->id_siswa);
                $autoStatus = null;
                $autoKet = null;
                $badge = null;

                if ($izin) {
                    $autoStatus = strtolower($izin->jenis_izin) === 'sakit' ? 'Sakit' : 'Izin';
                    $autoKet = '[Surat Piket: ' . $izin->jenis_izin . ']';
                    $badge = 'Surat ' . $izin->jenis_izin . ' (Piket)';
                } elseif ($dispen) {
                    $autoStatus = 'Izin';
                    $autoKet = '[Dispensasi] ' . ($dispen->nama_kegiatan ?? 'Dispensasi');
                    $badge = 'Dispensasi';
                }

                return [
                    'id_siswa' => $s->id_siswa,
                    'nama_siswa' => $s->nama_siswa,
                    'nisn' => $s->nisn,
                    'auto_status' => $autoStatus,
                    'auto_ket' => $autoKet,
                    'badge' => $badge,
                ];
            }),
            'jurnal' => $jurnal ? [
                'id_jurnal' => $jurnal->id_jurnal,
                'tanggal' => $jurnal->tanggal,
                'materi' => $jurnal->materi,
                'catatan' => $jurnal->catatan,
                'status_kehadiran' => $jurnal->status_kehadiran,
                'detail_ketidakhadiran' => $jurnal->detailKetidakhadiran->map(fn ($d) => [
                    'id_siswa' => $d->id_siswa,
                    'status' => $d->status,
                    'keterangan' => $d->catatan,
                ]),
            ] : null,
        ]);
    }

    /**
     * Store or update a jurnal + presensi entry submitted by the teacher.
     */
    public function storeJurnal(Request $request)
    {
        $idGuru = $this->resolveGuruId();

        $validated = $request->validate([
            'id_jadwal' => 'required|exists:jadwal_pelajaran,id_jadwal',
            'tanggal' => 'required|date',
            'materi' => 'required|string|max:255',
            'catatan' => 'nullable|string|max:255',
            'presensi' => 'nullable|array',
            'presensi.*.id_siswa' => 'required_with:presensi|exists:siswa,id_siswa',
            'presensi.*.status' => 'required_with:presensi|in:Hadir,Sakit,Izin,Alpa',
            'presensi.*.keterangan' => 'nullable|string|max:255',
        ]);

        // Pastikan jadwal ini benar-benar milik guru yang login
        $jadwal = $this->jadwalQuery($idGuru)->where('id_jadwal', $validated['id_jadwal'])->first();
        if (!$jadwal) {
            return back()->with('error', 'Akses Ditolak: Jadwal tidak ditemukan atau bukan milik Anda.');
        }

        // Validasi hari: tanggal yang diinput harus sesuai dengan hari jadwal
        $hariTanggal = $this->hariFromDate($validated['tanggal']);
        if ($jadwal->hari && $hariTanggal !== $jadwal->hari) {
            return back()->withInput()->with('error',
                "Pengisian Ditolak: Jadwal ini adalah hari {$jadwal->hari}, " .
                "sedangkan tanggal yang Anda masukkan ({$validated['tanggal']}) jatuh pada hari {$hariTanggal}. " .
                "Pilih tanggal yang memang jatuh pada hari {$jadwal->hari}."
            );
        }

        $presensi = collect($validated['presensi'] ?? []);
        $jumlahHadir = $presensi->where('status', 'Hadir')->count();
        $jumlahTidakHadir = $presensi->count() - $jumlahHadir;

        $jurnal = JurnalMengajar::firstOrNew([
            'id_jadwal' => $validated['id_jadwal'],
            'tanggal' => $validated['tanggal'],
        ]);

        $jurnal->status_kehadiran = 'Hadir';
        $jurnal->materi = $validated['materi'];
        $jurnal->catatan = $validated['catatan'] ?? null;
        $jurnal->jumlah_hadir = $jumlahHadir;
        $jurnal->jumlah_tidak_hadir = $jumlahTidakHadir;
        $jurnal->save();

        $jurnal->detailKetidakhadiran()->delete();

        $statusMap = ['Sakit' => 'S', 'Izin' => 'I', 'Alpa' => 'A'];
        foreach ($presensi as $p) {
            if ($p['status'] === 'Hadir') {
                continue;
            }
            $jurnal->detailKetidakhadiran()->create([
                'id_siswa' => $p['id_siswa'],
                'status' => $statusMap[$p['status']] ?? 'A',
                'kategori' => $p['status'] === 'Sakit' ? 'sakit' : ($p['status'] === 'Izin' ? 'izin_ortu' : 'alpa'),
                'catatan' => $p['keterangan'] ?? null,
                'waktu_input' => Carbon::now('Asia/Jakarta'),
            ]);
        }

        return redirect()->route('guru-mengajar.dashboard')->with('success', 'Jurnal & presensi berhasil disimpan.');
    }


    public function exportCsv(Request $request)
    {
        $idGuru = $this->resolveGuruId();
        $jadwalIds = $this->jadwalQuery($idGuru)->pluck('id_jadwal');

        $records = JurnalMengajar::with(['jadwal.kelas.jurusan', 'jadwal.mapel'])
            ->whereIn('id_jadwal', $jadwalIds)
            ->orderByDesc('tanggal')
            ->get();

        $rows = $records->map(function ($j) {
            return [
                Carbon::parse($j->tanggal)->format('d/m/Y'),
                optional($j->jadwal)->hari ?? '-',
                optional(optional($j->jadwal)->mapel)->nama_mapel ?? '-',
                $this->kelasLabel(optional($j->jadwal)->kelas),
                $j->materi ?? '-',
                $j->jumlah_hadir ?? 0,
                $j->jumlah_tidak_hadir ?? 0,
                $j->catatan ?? '-',
            ];
        });

        $filename = 'jurnal-mengajar-saya-' . Carbon::now('Asia/Jakarta')->format('Y-m-d') . '.csv';

        return CsvExporter::download($filename, [
            'Tanggal', 'Hari', 'Mata Pelajaran', 'Kelas', 'Materi', 'Jumlah Hadir', 'Jumlah Tidak Hadir', 'Catatan',
        ], $rows);
    }
}