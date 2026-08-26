<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Mapel;
use App\Models\Ruangan;
use App\Support\CsvExporter;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JadwalPelajaranController extends Controller
{
    /**
     * Display Master Data & Real-time Timeline Schedule.
     */
    public function index(Request $request)
    {
        // Get current system day name in Indonesian (Asia/Jakarta timezone)
        $dayMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];
        $englishDay = \Carbon\Carbon::now('Asia/Jakarta')->format('l');
        $todayDayName = $dayMap[$englishDay] ?? 'Senin';

        // Filter requested by user
        $selectedHari = $request->input('hari');

        // ── Main table query (respects all filters, paginated) ──────────────────
        $query = $this->buildFilteredQuery($request);
        $jadwal = $query->orderBy('hari', 'asc')->orderBy('jam_ke', 'asc')->paginate(10)->withQueryString();

        // ── "Sedang Berlangsung" timeline: schedules whose jam range covers now ─
        $nowTime      = \Carbon\Carbon::now('Asia/Jakarta')->format('H:i:s');
        $nowDayName   = $todayDayName; // always today
        $nowHariKat   = in_array($nowDayName, ['Jumat']) ? 'Jumat' : 'Senin-Kamis';

        // Check if current time falls into ANY JamPelajaran slot
        $currentJamPelajaran = JamPelajaran::where('hari_kategori', $nowHariKat)
            ->where('jam_mulai', '<=', $nowTime)
            ->where('jam_selesai', '>=', $nowTime)
            ->first();

        $todayJadwal    = collect();
        $activeDayTitle = 'Sedang Berlangsung — ' . date('H:i', strtotime($nowTime));

        if ($currentJamPelajaran) {
            $isBreak = $currentJamPelajaran->is_istirahat || !$currentJamPelajaran->bisa_diisi_mapel || $currentJamPelajaran->jam_ke == 0;

            if ($isBreak) {
                $waktuStr = \Carbon\Carbon::parse($currentJamPelajaran->jam_mulai)->format('H.i') . ' - ' . \Carbon\Carbon::parse($currentJamPelajaran->jam_selesai)->format('H.i');
                $todayJadwal = collect([[
                    'is_istirahat' => true,
                    'waktu'        => $waktuStr,
                    'hari'         => $nowDayName,
                    'keterangan'   => $currentJamPelajaran->keterangan ?? 'Waktu Istirahat',
                ]]);
                $activeDayTitle = 'Sedang Berlangsung — Waktu Istirahat (' . date('H:i', strtotime($nowTime)) . ')';
            } else {
                $todayJadwalQuery = JadwalPelajaran::with(['kelas.jurusan', 'guru', 'mapel', 'jamPelajaran'])
                    ->where('hari', $nowDayName)
                    ->where('jam_ke', $currentJamPelajaran->jam_ke);

                // Apply sidebar filters if present
                if ($request->filled('id_kelas')) {
                    $todayJadwalQuery->where('id_kelas', $request->input('id_kelas'));
                }
                if ($request->filled('id_guru')) {
                    $todayJadwalQuery->where('id_guru', $request->input('id_guru'));
                }
                if ($request->filled('id_mapel')) {
                    $todayJadwalQuery->where('id_mapel', $request->input('id_mapel'));
                }
                if ($request->filled('search')) {
                    $searchTerm = '%' . $request->input('search') . '%';
                    $todayJadwalQuery->where(function($q) use ($searchTerm) {
                        $q->whereHas('mapel', function($m) use ($searchTerm) {
                            $m->where('nama_mapel', 'like', $searchTerm);
                        })
                        ->orWhereHas('guru', function($g) use ($searchTerm) {
                            $g->where('nama_guru', 'like', $searchTerm);
                        })
                        ->orWhereHas('kelas', function($k) use ($searchTerm) {
                            $k->where('tingkat', 'like', $searchTerm)
                              ->orWhere('rombel', 'like', $searchTerm);
                        })
                        ->orWhere('ruangan', 'like', $searchTerm);
                    });
                }

                $todayJadwal = $todayJadwalQuery->orderBy('jam_ke', 'asc')->get();
            }
        }

        // Fetch all time slots for fallback resolution
        $jamPelajaransAll = JamPelajaran::all();

        // If AJAX, return JSON for both table section and timeline widget
        if ($request->ajax() || $request->wantsJson()) {
            $tableRows = $jadwal->map(function ($j) use ($jamPelajaransAll) {
                $jamObj = $j->jamPelajaran;
                if (!$jamObj && $j->jam_ke) {
                    $kat = ($j->hari === 'Jumat') ? 'Jumat' : 'Senin-Kamis';
                    $jamObj = $jamPelajaransAll->where('hari_kategori', $kat)->where('jam_ke', $j->jam_ke)->first();
                }

                $waktu = '-';
                if ($jamObj) {
                    $waktu = \Carbon\Carbon::parse($jamObj->jam_mulai)->format('H.i') . '-' . \Carbon\Carbon::parse($jamObj->jam_selesai)->format('H.i');
                }

                $isKelasDeleted = !$j->kelas || $j->kelas->trashed();
                $isGuruDeleted  = !$j->guru || $j->guru->trashed();
                $isMapelDeleted = !$j->mapel || $j->mapel->trashed();

                $kelasStr = $isKelasDeleted ? '-' : ($j->kelas->tingkat . ' ' . optional($j->kelas->jurusan)->kode_jurusan . ' ' . $j->kelas->rombel);
                $guruStr  = $isGuruDeleted ? '-' : $j->guru->nama_guru;
                $mapelStr = $isMapelDeleted ? '-' : $j->mapel->nama_mapel;

                return [
                    'id_jadwal'        => $j->id_jadwal,
                    'waktu'            => $waktu,
                    'hari'             => $j->hari,
                    'jam_ke'           => $j->jam_ke,
                    'mapel'            => $mapelStr,
                    'kelas'            => $kelasStr,
                    'guru'             => $guruStr,
                    'nama_mapel'       => $mapelStr,
                    'nama_kelas'       => $kelasStr,
                    'nama_guru'        => $guruStr,
                    'ruangan'          => $j->ruangan ?? '-',
                    'id_kelas'         => $j->id_kelas,
                    'id_guru'          => $j->id_guru,
                    'id_mapel'         => $j->id_mapel,
                    'is_kelas_deleted' => $isKelasDeleted,
                    'is_guru_deleted'  => $isGuruDeleted,
                    'is_mapel_deleted' => $isMapelDeleted,
                ];
            });

            $timelineRows = $todayJadwal->map(function ($t) use ($jamPelajaransAll) {
                if (is_array($t) && !empty($t['is_istirahat'])) {
                    return [
                        'is_istirahat' => true,
                        'waktu'        => $t['waktu'],
                        'hari'         => $t['hari'],
                        'keterangan'   => $t['keterangan'] ?? 'Waktu Istirahat',
                    ];
                }

                $jamObj = $t->jamPelajaran;
                if (!$jamObj && $t->jam_ke) {
                    $kat = ($t->hari === 'Jumat') ? 'Jumat' : 'Senin-Kamis';
                    $jamObj = $jamPelajaransAll->where('hari_kategori', $kat)->where('jam_ke', $t->jam_ke)->first();
                }

                $waktu = '-';
                if ($jamObj) {
                    $waktu = \Carbon\Carbon::parse($jamObj->jam_mulai)->format('H.i') . ' - ' . \Carbon\Carbon::parse($jamObj->jam_selesai)->format('H.i');
                }

                $isKelasDeleted = !$t->kelas || $t->kelas->trashed();
                $isGuruDeleted  = !$t->guru || $t->guru->trashed();
                $isMapelDeleted = !$t->mapel || $t->mapel->trashed();

                return [
                    'id_jadwal'        => $t->id_jadwal,
                    'waktu'            => $waktu,
                    'hari'             => $t->hari,
                    'jam_ke'           => $t->jam_ke,
                    'nama_mapel'       => $isMapelDeleted ? '-' : (optional($t->mapel)->nama_mapel ?? '-'),
                    'nama_kelas'       => $isKelasDeleted ? '-' : ($t->kelas ? ($t->kelas->tingkat . ' ' . optional($t->kelas->jurusan)->kode_jurusan . ' ' . $t->kelas->rombel) : '-'),
                    'nama_guru'        => $isGuruDeleted ? '-' : (optional($t->guru)->nama_guru ?? '-'),
                    'ruangan'          => $t->ruangan ?? '-',
                    'is_kelas_deleted' => $isKelasDeleted,
                    'is_guru_deleted'  => $isGuruDeleted,
                    'is_mapel_deleted' => $isMapelDeleted,
                ];
            });

            return response()->json([
                'data'             => $tableRows,
                'timeline'         => $timelineRows,
                'active_day_title' => $activeDayTitle,
                'pagination'       => [
                    'first'   => $jadwal->firstItem() ?? 0,
                    'last'    => $jadwal->lastItem() ?? 0,
                    'total'   => $jadwal->total(),
                    'current' => $jadwal->currentPage(),
                    'lastPage'=> $jadwal->lastPage(),
                    'prev'    => $jadwal->previousPageUrl(),
                    'next'    => $jadwal->nextPageUrl(),
                ],
            ]);
        }

        // Dropdown Lists for Modals & Filters
        $kelases       = Kelas::with(['jurusan', 'waliKelas'])->orderBy('tingkat')->orderBy('id_jurusan')->orderBy('rombel')->get();
        $gurus         = Guru::orderBy('nama_guru')->get();
        $mapels        = Mapel::orderBy('nama_mapel')->get();
        $ruangans      = Ruangan::orderBy('nama_ruangan')->get();
        $jamPelajarans = JamPelajaran::orderBy('jam_mulai')->get();
        $hariList      = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $allJadwal     = JadwalPelajaran::with(['kelas.jurusan', 'guru', 'mapel', 'jamPelajaran'])->get();

        return view('admin.jadwal.index', compact(
            'jadwal', 'todayJadwal', 'todayDayName', 'activeDayTitle',
            'kelases', 'gurus', 'mapels', 'ruangans', 'jamPelajarans', 'hariList', 'allJadwal'
        ));
    }

    /**
     * Store a newly created schedule entry (supports AJAX).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'hari'     => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_ke'   => 'required|integer|min:1|max:12',
            'id_guru'  => 'required|exists:guru,id_guru',
            'id_mapel' => 'required|exists:mapel,id_mapel',
            'ruangan'  => 'nullable|string|max:50',
        ], [
            'id_kelas.required' => 'Kelas wajib dipilih.',
            'hari.required'     => 'Hari wajib dipilih (Senin - Jumat).',
            'hari.in'           => 'Hari pelajaran hanya berlaku untuk Senin sampai Jumat.',
            'jam_ke.required'   => 'Jam pelajaran wajib dipilih.',
            'id_guru.required'  => 'Guru pengajar wajib dipilih.',
            'id_mapel.required' => 'Mata pelajaran wajib dipilih.',
        ]);

        $hariKategori = ($validated['hari'] === 'Jumat') ? 'Jumat' : 'Senin-Kamis';
        $jamObj = JamPelajaran::where('hari_kategori', $hariKategori)->where('jam_ke', $validated['jam_ke'])->first();
        if ($jamObj) {
            $validated['id_jam'] = $jamObj->id_jam;
        }

        // Conflict checks
        $existingJadwal = JadwalPelajaran::where('id_kelas', $validated['id_kelas'])
            ->where('hari', $validated['hari'])
            ->where('jam_ke', $validated['jam_ke'])
            ->first();

        if ($existingJadwal) {
            if (!$request->boolean('force_replace')) {
                $msg = 'Di jam ini sudah ada jadwal. Apakah kamu ingin benar-benar merubahnya dengan ini?';
                if ($request->ajax()) {
                    return response()->json([
                        'confirm_overwrite' => true,
                        'error'             => $msg,
                        'message'           => $msg,
                    ], 409);
                }
                return back()->withInput()->with('error', $msg);
            } else {
                // User confirmed overwrite -> replace existing schedule for this class slot
                $existingJadwal->delete();
            }
        }

        if (JadwalPelajaran::where('id_guru', $validated['id_guru'])->where('hari', $validated['hari'])->where('jam_ke', $validated['jam_ke'])->exists()) {
            $msg = 'Bentrok Guru: Guru tersebut sudah mengajar di kelas lain pada Hari ' . $validated['hari'] . ' Jam Ke-' . $validated['jam_ke'] . '.';
            return $request->ajax() ? response()->json(['error' => $msg], 422) : back()->withInput()->with('error', $msg);
        }
        if (!empty($validated['ruangan']) && $validated['ruangan'] !== '-') {
            if (JadwalPelajaran::where('ruangan', $validated['ruangan'])->where('hari', $validated['hari'])->where('jam_ke', $validated['jam_ke'])->exists()) {
                $msg = 'Bentrok Ruangan: Ruangan "' . $validated['ruangan'] . '" sudah digunakan oleh kelas lain pada Hari ' . $validated['hari'] . ' Jam Ke-' . $validated['jam_ke'] . '.';
                return $request->ajax() ? response()->json(['error' => $msg], 422) : back()->withInput()->with('error', $msg);
            }
        }

        JadwalPelajaran::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => 'Jadwal pelajaran berhasil ditambahkan.']);
        }
        return redirect()->route('jadwal.index')->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    /**
     * Show details via JSON.
     */
    public function show(JadwalPelajaran $jadwal)
    {
        $jadwal->load(['kelas.jurusan', 'guru', 'mapel', 'jamPelajaran']);

        $isKelasDeleted = !$jadwal->kelas || $jadwal->kelas->trashed();
        $isGuruDeleted  = !$jadwal->guru || $jadwal->guru->trashed();
        $isMapelDeleted = !$jadwal->mapel || $jadwal->mapel->trashed();

        $timeRange = '-';
        if ($jadwal->jamPelajaran) {
            $timeRange = \Carbon\Carbon::parse($jadwal->jamPelajaran->jam_mulai)->format('H.i') . ' - ' . \Carbon\Carbon::parse($jadwal->jamPelajaran->jam_selesai)->format('H.i');
        }

        return response()->json([
            'id_jadwal'        => $jadwal->id_jadwal,
            'hari'             => $jadwal->hari,
            'jam_ke'           => $jadwal->jam_ke,
            'waktu'            => $timeRange,
            'nama_kelas'       => $isKelasDeleted ? '-' : ($jadwal->kelas->tingkat . ' ' . optional($jadwal->kelas->jurusan)->kode_jurusan . ' ' . $jadwal->kelas->rombel),
            'id_kelas'         => $jadwal->id_kelas,
            'nama_mapel'       => $isMapelDeleted ? '-' : $jadwal->mapel->nama_mapel,
            'id_mapel'         => $jadwal->id_mapel,
            'nama_guru'        => $isGuruDeleted ? '-' : $jadwal->guru->nama_guru,
            'id_guru'          => $jadwal->id_guru,
            'ruangan'          => $jadwal->ruangan ?? '-',
            'is_kelas_deleted' => $isKelasDeleted,
            'is_guru_deleted'  => $isGuruDeleted,
            'is_mapel_deleted' => $isMapelDeleted,
        ]);
    }

    /**
     * Update (supports AJAX). Only Mapel, Guru, and Ruangan can be changed.
     * Kelas, Hari, Jam stay locked to the original record.
     */
    public function update(Request $request, JadwalPelajaran $jadwal)
    {
        $validated = $request->validate([
            'id_guru'  => 'required|exists:guru,id_guru',
            'id_mapel' => 'required|exists:mapel,id_mapel',
            'ruangan'  => 'nullable|string|max:50',
        ], [
            'id_guru.required'  => 'Guru pengajar wajib dipilih.',
            'id_mapel.required' => 'Mata pelajaran wajib dipilih.',
        ]);

        // Conflict check: another schedule with same hari+jam_ke has this guru (excluding self)
        if (JadwalPelajaran::where('id_guru', $validated['id_guru'])
            ->where('hari', $jadwal->hari)
            ->where('jam_ke', $jadwal->jam_ke)
            ->where('id_jadwal', '!=', $jadwal->id_jadwal)
            ->exists()
        ) {
            $msg = 'Bentrok Guru: Guru tersebut sudah mengajar di kelas lain pada Hari ' . $jadwal->hari . ' Jam Ke-' . $jadwal->jam_ke . '.';
            return $request->ajax() ? response()->json(['error' => $msg], 422) : back()->withInput()->with('error', $msg);
        }

        // Conflict check: ruangan already used at same hari+jam_ke (excluding self)
        if (!empty($validated['ruangan']) && $validated['ruangan'] !== '-') {
            if (JadwalPelajaran::where('ruangan', $validated['ruangan'])
                ->where('hari', $jadwal->hari)
                ->where('jam_ke', $jadwal->jam_ke)
                ->where('id_jadwal', '!=', $jadwal->id_jadwal)
                ->exists()
            ) {
                $msg = 'Bentrok Ruangan: Ruangan "' . $validated['ruangan'] . '" sudah digunakan oleh kelas lain pada Hari ' . $jadwal->hari . ' Jam Ke-' . $jadwal->jam_ke . '.';
                return $request->ajax() ? response()->json(['error' => $msg], 422) : back()->withInput()->with('error', $msg);
            }
        }

        $jadwal->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => 'Jadwal pelajaran berhasil diperbarui.']);
        }
        return redirect()->route('jadwal.index')->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    /**
     * Destroy (supports AJAX).
     */
    public function destroy(Request $request, JadwalPelajaran $jadwal)
    {
        $jadwal->delete();
        if ($request->ajax()) {
            return response()->json(['success' => 'Jadwal pelajaran berhasil dihapus.']);
        }
        return redirect()->route('jadwal.index')->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }

    /**
     * Move schedule to a new day & time slot via Drag & Drop.
     */
    public function move(Request $request, JadwalPelajaran $jadwal)
    {
        $validated = $request->validate([
            'hari'   => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_ke' => 'required|integer|min:1|max:12',
        ]);

        $hariKategori = ($validated['hari'] === 'Jumat') ? 'Jumat' : 'Senin-Kamis';
        $jamObj = JamPelajaran::where('hari_kategori', $hariKategori)->where('jam_ke', $validated['jam_ke'])->first();

        $targetKelasId = $request->input('id_kelas', $jadwal->id_kelas);

        // Conflict checks
        if (JadwalPelajaran::where('id_kelas', $targetKelasId)
            ->where('hari', $validated['hari'])
            ->where('jam_ke', $validated['jam_ke'])
            ->where('id_jadwal', '!=', $jadwal->id_jadwal)->exists()) {
            return response()->json(['error' => 'Bentrok Kelas: Slot ini sudah terisi untuk kelas tersebut pada ' . $validated['hari'] . ' Jam Ke-' . $validated['jam_ke']], 422);
        }

        if (JadwalPelajaran::where('id_guru', $jadwal->id_guru)
            ->where('hari', $validated['hari'])
            ->where('jam_ke', $validated['jam_ke'])
            ->where('id_jadwal', '!=', $jadwal->id_jadwal)->exists()) {
            return response()->json(['error' => 'Bentrok Guru: Guru tersebut sudah mengajar di kelas lain pada ' . $validated['hari'] . ' Jam Ke-' . $validated['jam_ke']], 422);
        }

        if (!empty($jadwal->ruangan) && JadwalPelajaran::where('ruangan', $jadwal->ruangan)
            ->where('hari', $validated['hari'])
            ->where('jam_ke', $validated['jam_ke'])
            ->where('id_jadwal', '!=', $jadwal->id_jadwal)->exists()) {
            return response()->json(['error' => 'Bentrok Ruangan: Ruangan "' . $jadwal->ruangan . '" sudah terpakai pada ' . $validated['hari'] . ' Jam Ke-' . $validated['jam_ke']], 422);
        }

        $jadwal->update([
            'id_kelas' => $targetKelasId,
            'hari'     => $validated['hari'],
            'jam_ke'   => $validated['jam_ke'],
            'id_jam'   => $jamObj->id_jam ?? $jadwal->id_jam,
        ]);

        return response()->json(['success' => 'Jadwal berhasil dipindahkan ke ' . $validated['hari'] . ' Jam Ke-' . $validated['jam_ke']]);
    }

    /**
     * Export filtered schedule data as CSV (Matrix Layout matching PDF).
     */
    public function exportCsv(Request $request)
    {
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $records = $this->buildFilteredQuery($request)->get();

        $kelasId = $request->input('id_kelas');

        // Determine which classes to include
        if ($request->filled('id_kelas')) {
            $kelases = Kelas::with(['jurusan', 'waliKelas'])->where('id_kelas', $kelasId)->get();
        } else {
            $kelasIdsInRecords = $records->pluck('id_kelas')->unique()->filter();
            if ($kelasIdsInRecords->isNotEmpty()) {
                $kelases = Kelas::with(['jurusan', 'waliKelas'])->whereIn('id_kelas', $kelasIdsInRecords)->orderBy('tingkat')->orderBy('id_jurusan')->orderBy('rombel')->get();
            } else {
                $kelases = Kelas::with(['jurusan', 'waliKelas'])->orderBy('tingkat')->orderBy('id_jurusan')->orderBy('rombel')->get();
            }
        }

        // Build matrix array: $matrix[id_kelas][hari][jam_ke]
        $matrix = [];
        foreach ($records as $r) {
            $matrix[$r->id_kelas][$r->hari][$r->jam_ke] = $r;
        }

        $maxJamSeninKamis = JamPelajaran::where('hari_kategori', 'Senin-Kamis')->max('jam_ke') ?: 10;
        $maxJamJumat      = JamPelajaran::where('hari_kategori', 'Jumat')->max('jam_ke') ?: 6;
        $maxJamOverall    = max($maxJamSeninKamis, $maxJamJumat);

        $jamPelajaransAll = JamPelajaran::all();
        $jamMap = [];
        foreach ($jamPelajaransAll as $jp) {
            $jamMap[$jp->hari_kategori][$jp->jam_ke] = $jp;
        }

        $activeDays = ($request->filled('hari') && in_array($request->input('hari'), $hariList))
            ? [$request->input('hari')]
            : $hariList;

        $rows = [];
        foreach ($kelases as $k) {
            $kelasLabel = $k->tingkat . ' ' . optional($k->jurusan)->kode_jurusan . ' ' . $k->rombel;
            $waliGuru = optional($k->waliKelas)->nama_guru ?? '-';

            foreach ($activeDays as $day) {
                for ($jam = 1; $jam <= $maxJamOverall; $jam++) {
                    $item = $matrix[$k->id_kelas][$day][$jam] ?? null;
                    $kat = ($day === 'Jumat') ? 'Jumat' : 'Senin-Kamis';
                    $jamSlot = $jamMap[$kat][$jam] ?? null;

                    $isNonKbm = false;
                    $nonKbmKet = '';
                    if ($jamSlot) {
                        $appliesToDay = !$jamSlot->berlaku_hari || $jamSlot->berlaku_hari === 'Semua Hari' || $jamSlot->berlaku_hari === $day;
                        if ($jamSlot->bisa_diisi_mapel == 0 && $appliesToDay) {
                            $isNonKbm = true;
                            $nonKbmKet = ($day === 'Senin' && $jam == 1) ? 'UPACARA / APEL' : ($jamSlot->keterangan ?: 'NON-KBM');
                        }
                    }

                    if ($item) {
                        $rows[] = [
                            $kelasLabel,
                            $waliGuru,
                            $day,
                            'Jam Ke-' . $jam,
                            optional($item->mapel)->nama_mapel ?? '-',
                            optional($item->guru)->nama_guru ?? '-',
                            $item->ruangan ?: '-',
                            'KBM',
                        ];
                    } elseif ($isNonKbm) {
                        $rows[] = [
                            $kelasLabel,
                            $waliGuru,
                            $day,
                            'Jam Ke-' . $jam,
                            '-',
                            '-',
                            '-',
                            strtoupper($nonKbmKet),
                        ];
                    } else {
                        $rows[] = [
                            $kelasLabel,
                            $waliGuru,
                            $day,
                            'Jam Ke-' . $jam,
                            '-',
                            '-',
                            '-',
                            'Kosong',
                        ];
                    }
                }
            }
        }

        $filename = 'jadwal-pelajaran-matriks-' . Carbon::now('Asia/Jakarta')->format('Y-m-d') . '.csv';

        return CsvExporter::download($filename, [
            'Kelas',
            'Wali Kelas',
            'Hari',
            'Jam Ke',
            'Mata Pelajaran',
            'Guru',
            'Ruangan',
            'Keterangan',
        ], $rows);
    }

    /**
     * Export filtered schedule data as PDF (Matrix Layout).
     */
    public function exportPdf(Request $request)
    {
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        $records = $this->buildFilteredQuery($request)->get();

        $filterHari  = $request->input('hari') ?: 'Semua Hari';
        $filterKelas = 'Semua Kelas';
        $filterMapel = 'Semua Mapel';

        $kelasId = $request->input('id_kelas');
        $mapelId = $request->input('id_mapel');

        if ($request->filled('id_kelas')) {
            $k = Kelas::with('jurusan')->find($kelasId);
            if ($k) {
                $filterKelas = $k->tingkat . ' ' . optional($k->jurusan)->kode_jurusan . ' ' . $k->rombel;
            }
        }

        if ($request->filled('id_mapel')) {
            $m = Mapel::find($mapelId);
            if ($m) {
                $filterMapel = $m->nama_mapel;
            }
        }

        // Determine which classes to render in PDF
        if ($request->filled('id_kelas')) {
            $kelases = Kelas::with(['jurusan', 'waliKelas'])->where('id_kelas', $kelasId)->get();
        } else {
            $kelasIdsInRecords = $records->pluck('id_kelas')->unique()->filter();
            if ($kelasIdsInRecords->isNotEmpty()) {
                $kelases = Kelas::with(['jurusan', 'waliKelas'])->whereIn('id_kelas', $kelasIdsInRecords)->orderBy('tingkat')->orderBy('id_jurusan')->orderBy('rombel')->get();
            } else {
                $kelases = Kelas::with(['jurusan', 'waliKelas'])->orderBy('tingkat')->orderBy('id_jurusan')->orderBy('rombel')->get();
            }
        }

        // Build matrix array: $matrix[id_kelas][hari][jam_ke]
        $matrix = [];
        foreach ($records as $r) {
            $matrix[$r->id_kelas][$r->hari][$r->jam_ke] = $r;
        }

        $maxJamSeninKamis = JamPelajaran::where('hari_kategori', 'Senin-Kamis')->max('jam_ke') ?: 10;
        $maxJamJumat      = JamPelajaran::where('hari_kategori', 'Jumat')->max('jam_ke') ?: 6;
        $maxJamOverall    = max($maxJamSeninKamis, $maxJamJumat);

        $jamPelajaransAll = JamPelajaran::all();
        $jamMap = [];
        foreach ($jamPelajaransAll as $jp) {
            $jamMap[$jp->hari_kategori][$jp->jam_ke] = $jp;
        }

        $pdf = Pdf::loadView('admin.jadwal.pdf', compact(
            'kelases',
            'hariList',
            'matrix',
            'jamMap',
            'maxJamOverall',
            'filterHari',
            'filterKelas',
            'filterMapel',
            'request'
        ))->setPaper('a4', 'landscape');

        $filename = 'jadwal-pelajaran-matriks-' . Carbon::now('Asia/Jakarta')->format('Y-m-d') . '.pdf';

        return $pdf->stream($filename);
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = JadwalPelajaran::with(['kelas.jurusan', 'guru', 'mapel', 'jamPelajaran']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('mapel', fn($mQ) => $mQ->where('nama_mapel', 'like', "%{$search}%"))
                  ->orWhereHas('guru', fn($gQ) => $gQ->where('nama_guru', 'like', "%{$search}%"))
                  ->orWhereHas('kelas', fn($kQ) => $kQ->where('tingkat', 'like', "%{$search}%")->orWhere('rombel', 'like', "%{$search}%")->orWhere('wali_kelas', 'like', "%{$search}%"))
                  ->orWhere('ruangan', 'like', "%{$search}%");
            });
        }
        if ($request->filled('hari')) {
            $query->where('hari', $request->input('hari'));
        }
        if ($request->filled('id_kelas')) {
            $query->where('id_kelas', $request->input('id_kelas'));
        }
        if ($request->filled('id_mapel')) {
            $query->where('id_mapel', $request->input('id_mapel'));
        }

        return $query;
    }
}