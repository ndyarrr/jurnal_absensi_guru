<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Mapel;
use Illuminate\Http\Request;

class JadwalPelajaranController extends Controller
{
    /**
     * Display Master Data & Real-time Timeline Schedule.
     */
    public function index(Request $request)
    {
        // Get current system day name in Indonesian (Asia/Jakarta timezone)
        $todayDayName = \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l');

        // Real-time Today's Schedule for the Timeline Widget (includes soft-deleted)
        $todayJadwal = JadwalPelajaran::with(['kelas.jurusan', 'guru', 'mapel', 'jamPelajaran'])
            ->where('hari', $todayDayName)
            ->orderBy('jam_ke', 'asc')
            ->get();

        // Query Builder for Full Schedule Table with Filters
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
        if ($request->filled('hari'))     { $query->where('hari', $request->input('hari')); }
        if ($request->filled('id_kelas')) { $query->where('id_kelas', $request->input('id_kelas')); }
        if ($request->filled('id_mapel')) { $query->where('id_mapel', $request->input('id_mapel')); }

        $jadwal = $query->orderBy('hari', 'asc')->orderBy('jam_ke', 'asc')->paginate(10)->withQueryString();

        // If AJAX, return JSON for the table section
        if ($request->ajax() || $request->wantsJson()) {
            $tableRows = $jadwal->map(function ($j) {
                $waktu = '-';
                if ($j->jamPelajaran) {
                    $waktu = \Carbon\Carbon::parse($j->jamPelajaran->jam_mulai)->format('H.i') . '-' . \Carbon\Carbon::parse($j->jamPelajaran->jam_selesai)->format('H.i');
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
                    'ruangan'          => $j->ruangan ?? 'R. 57',
                    'id_kelas'         => $j->id_kelas,
                    'id_guru'          => $j->id_guru,
                    'id_mapel'         => $j->id_mapel,
                    'is_kelas_deleted' => $isKelasDeleted,
                    'is_guru_deleted'  => $isGuruDeleted,
                    'is_mapel_deleted' => $isMapelDeleted,
                ];
            });

            return response()->json([
                'data'       => $tableRows,
                'pagination' => [
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
        $kelases       = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('id_jurusan')->orderBy('rombel')->get();
        $gurus         = Guru::orderBy('nama_guru')->get();
        $mapels        = Mapel::orderBy('nama_mapel')->get();
        $jamPelajarans = JamPelajaran::orderBy('jam_ke')->get();
        $hariList      = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        return view('jadwal.index', compact(
            'jadwal', 'todayJadwal', 'todayDayName',
            'kelases', 'gurus', 'mapels', 'jamPelajarans', 'hariList'
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

        if (empty($validated['ruangan'])) { $validated['ruangan'] = 'R. 57'; }

        $jamObj = JamPelajaran::where('jam_ke', $validated['jam_ke'])->first();
        if ($jamObj) { $validated['id_jam'] = $jamObj->id_jam; }

        // Conflict checks
        if (JadwalPelajaran::where('id_kelas', $validated['id_kelas'])->where('hari', $validated['hari'])->where('jam_ke', $validated['jam_ke'])->exists()) {
            $msg = 'Bentrok Kelas: Kelas ini sudah memiliki jadwal pelajaran lain di Hari ' . $validated['hari'] . ' Jam Ke-' . $validated['jam_ke'] . '.';
            return $request->ajax() ? response()->json(['error' => $msg], 422) : back()->withInput()->with('error', $msg);
        }
        if (JadwalPelajaran::where('id_guru', $validated['id_guru'])->where('hari', $validated['hari'])->where('jam_ke', $validated['jam_ke'])->exists()) {
            $msg = 'Bentrok Guru: Guru tersebut sudah mengajar di kelas lain pada Hari ' . $validated['hari'] . ' Jam Ke-' . $validated['jam_ke'] . '.';
            return $request->ajax() ? response()->json(['error' => $msg], 422) : back()->withInput()->with('error', $msg);
        }
        if (JadwalPelajaran::where('ruangan', $validated['ruangan'])->where('hari', $validated['hari'])->where('jam_ke', $validated['jam_ke'])->exists()) {
            $msg = 'Bentrok Ruangan: Ruangan "' . $validated['ruangan'] . '" sudah digunakan oleh kelas lain pada Hari ' . $validated['hari'] . ' Jam Ke-' . $validated['jam_ke'] . '.';
            return $request->ajax() ? response()->json(['error' => $msg], 422) : back()->withInput()->with('error', $msg);
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
            'ruangan'          => $jadwal->ruangan ?? 'R. 57',
            'is_kelas_deleted' => $isKelasDeleted,
            'is_guru_deleted'  => $isGuruDeleted,
            'is_mapel_deleted' => $isMapelDeleted,
        ]);
    }

    /**
     * Update (supports AJAX).
     */
    public function update(Request $request, JadwalPelajaran $jadwal)
    {
        $validated = $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'hari'     => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_ke'   => 'required|integer|min:1|max:12',
            'id_guru'  => 'required|exists:guru,id_guru',
            'id_mapel' => 'required|exists:mapel,id_mapel',
            'ruangan'  => 'nullable|string|max:50',
        ], ['hari.in' => 'Hari pelajaran hanya berlaku untuk Senin sampai Jumat.']);

        if (empty($validated['ruangan'])) { $validated['ruangan'] = 'R. 57'; }
        $jamObj = JamPelajaran::where('jam_ke', $validated['jam_ke'])->first();
        if ($jamObj) { $validated['id_jam'] = $jamObj->id_jam; }

        if (JadwalPelajaran::where('id_kelas', $validated['id_kelas'])->where('hari', $validated['hari'])->where('jam_ke', $validated['jam_ke'])->where('id_jadwal', '!=', $jadwal->id_jadwal)->exists()) {
            $msg = 'Bentrok Kelas: Kelas ini sudah memiliki jadwal pelajaran lain di Hari ' . $validated['hari'] . ' Jam Ke-' . $validated['jam_ke'] . '.';
            return $request->ajax() ? response()->json(['error' => $msg], 422) : back()->withInput()->with('error', $msg);
        }
        if (JadwalPelajaran::where('id_guru', $validated['id_guru'])->where('hari', $validated['hari'])->where('jam_ke', $validated['jam_ke'])->where('id_jadwal', '!=', $jadwal->id_jadwal)->exists()) {
            $msg = 'Bentrok Guru: Guru tersebut sudah mengajar di kelas lain pada Hari ' . $validated['hari'] . ' Jam Ke-' . $validated['jam_ke'] . '.';
            return $request->ajax() ? response()->json(['error' => $msg], 422) : back()->withInput()->with('error', $msg);
        }
        if (JadwalPelajaran::where('ruangan', $validated['ruangan'])->where('hari', $validated['hari'])->where('jam_ke', $validated['jam_ke'])->where('id_jadwal', '!=', $jadwal->id_jadwal)->exists()) {
            $msg = 'Bentrok Ruangan: Ruangan "' . $validated['ruangan'] . '" sudah digunakan oleh kelas lain pada Hari ' . $validated['hari'] . ' Jam Ke-' . $validated['jam_ke'] . '.';
            return $request->ajax() ? response()->json(['error' => $msg], 422) : back()->withInput()->with('error', $msg);
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
}