<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapelController extends Controller
{
    /**
     * Display a listing of Mapel with statistics.
     */
    public function index(Request $request)
    {
        $query = Mapel::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama_mapel', 'like', "%{$search}%");
        }

        $mapel = $query->orderBy('nama_mapel', 'asc')->paginate(10)->withQueryString();

        // Calculate count of teachers (pengampu) per mapel
        foreach ($mapel as $m) {
            $teacherIdsFromPivot = DB::table('guru_mapel')->where('id_mapel', $m->id_mapel)->pluck('id_guru');
            $teacherIdsFromJadwal = DB::table('jadwal_pelajaran')->where('id_mapel', $m->id_mapel)->pluck('id_guru');
            $allTeacherIds = $teacherIdsFromPivot->merge($teacherIdsFromJadwal)->unique();
            $m->jumlah_pengampu = $allTeacherIds->count();
        }

        // Summary Statistics
        $totalMapel = Mapel::count();
        $totalPengampu = DB::table('guru_mapel')->pluck('id_guru')
            ->merge(DB::table('jadwal_pelajaran')->pluck('id_guru'))
            ->unique()->count();

        // Default detail mapel data for initial right panel load (first mapel)
        $defaultMapel = $mapel->first();
        $defaultTeachers = collect([]);
        if ($defaultMapel) {
            $tIdsFromPivot = DB::table('guru_mapel')->where('id_mapel', $defaultMapel->id_mapel)->pluck('id_guru');
            $tIdsFromJadwal = DB::table('jadwal_pelajaran')->where('id_mapel', $defaultMapel->id_mapel)->pluck('id_guru');
            $allTIds = $tIdsFromPivot->merge($tIdsFromJadwal)->unique();
            $defaultTeachers = $allTIds->isNotEmpty() ? Guru::whereIn('id_guru', $allTIds)->get() : collect([]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'data' => $mapel->map(function($m) {
                    return [
                        'id_mapel'        => $m->id_mapel,
                        'nama_mapel'      => $m->nama_mapel,
                        'jumlah_pengampu' => $m->jumlah_pengampu,
                    ];
                }),
                'pagination' => [
                    'first'    => $mapel->firstItem() ?? 0,
                    'last'     => $mapel->lastItem() ?? 0,
                    'total'    => $mapel->total(),
                    'current'  => $mapel->currentPage(),
                    'lastPage' => $mapel->lastPage(),
                ]
            ]);
        }

        return view('mapel.index', compact(
            'mapel',
            'totalMapel',
            'totalPengampu',
            'defaultMapel',
            'defaultTeachers'
        ));
    }

    /**
     * Store a newly created mapel.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:100|unique:mapel,nama_mapel',
        ], [
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
            'nama_mapel.unique'   => 'Nama mata pelajaran tersebut sudah ada.',
        ]);

        $newMapel = Mapel::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => 'Mata pelajaran berhasil ditambahkan.',
                'mapel'   => $newMapel
            ]);
        }

        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    /**
     * Show mapel detail for JSON AJAX view.
     */
    public function show(Mapel $mapel)
    {
        $tIdsFromPivot = DB::table('guru_mapel')->where('id_mapel', $mapel->id_mapel)->pluck('id_guru');
        $tIdsFromJadwal = DB::table('jadwal_pelajaran')->where('id_mapel', $mapel->id_mapel)->pluck('id_guru');
        $allTIds = $tIdsFromPivot->merge($tIdsFromJadwal)->unique();
        
        $teachers = $allTIds->isNotEmpty() ? Guru::whereIn('id_guru', $allTIds)->get() : collect([]);

        return response()->json([
            'id_mapel'        => $mapel->id_mapel,
            'nama_mapel'      => $mapel->nama_mapel,
            'jumlah_pengampu' => $teachers->count(),
            'gurus'           => $teachers->map(fn($g) => [
                'id_guru'   => $g->id_guru,
                'nama_guru' => $g->nama_guru,
            ])
        ]);
    }

    /**
     * Update the specified mapel.
     */
    public function update(Request $request, Mapel $mapel)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:100|unique:mapel,nama_mapel,' . $mapel->id_mapel . ',id_mapel',
        ], [
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
            'nama_mapel.unique'   => 'Nama mata pelajaran tersebut sudah ada.',
        ]);

        $mapel->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => 'Mata pelajaran berhasil diperbarui.'
            ]);
        }

        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    /**
     * Remove the specified mapel.
     */
    public function destroy(Request $request, Mapel $mapel)
    {
        $mapel->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => 'Mata pelajaran berhasil dihapus.'
            ]);
        }

        return redirect()->route('mapel.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}