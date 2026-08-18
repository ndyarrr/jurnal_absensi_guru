<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Mapel;
use App\Support\CsvExporter;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GuruController extends Controller
{
    /**
     * Display Master Data - Guru page matching the exact mockup design.
     */
    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $guru = $query->orderBy('nama_guru', 'asc')->paginate(8)->withQueryString();
        $totalGuruCount = Guru::count();
        $mapelList = Mapel::orderBy('nama_mapel')->get();

        return view('admin.guru.index', compact('guru', 'totalGuruCount', 'mapelList'));
    }

    /**
     * Store a newly created Guru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nuptk'     => 'required|regex:/^[0-9]{16}$|^[0-9]{18}$/|unique:guru,nuptk',
            'nama_guru' => 'required|string|max:255',
            'no_hp'     => 'nullable|string|max:20',
            'mapel'     => 'nullable|array',
            'mapel.*'   => 'exists:mapel,id_mapel',
        ], [
            'nuptk.required'     => 'NUPTK / NIP wajib diisi.',
            'nuptk.regex'        => 'NUPTK harus berisi tepat 16 digit angka (atau NIP 18 digit angka).',
            'nuptk.unique'       => 'NUPTK / NIP sudah terdaftar.',
            'nama_guru.required' => 'Nama guru wajib diisi.',
        ]);

        $guru = Guru::create([
            'nuptk'     => $validated['nuptk'],
            'nama_guru' => $validated['nama_guru'],
            'no_hp'     => $validated['no_hp'] ?? null,
        ]);

        if (!empty($request->input('mapel'))) {
            $guru->mapel()->sync($request->input('mapel'));
        }

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan');
    }

    /**
     * Show details of a specific Guru via JSON AJAX for modal.
     */
    public function show(Guru $guru)
    {
        $guru->load(['mapel', 'user']);

        $mapelNames = $guru->mapel->pluck('nama_mapel')->join(', ');
        if (empty($mapelNames)) {
            $mapelNames = '-';
        }

        return response()->json([
            'id_guru'     => $guru->id_guru,
            'nuptk'       => $guru->nuptk,
            'nama_guru'   => $guru->nama_guru,
            'no_hp'       => $guru->no_hp ?? '-',
            'mapel_names' => $mapelNames,
            'mapel_ids'   => $guru->mapel->pluck('id_mapel')->toArray(),
            'user_email'  => optional($guru->user)->email ?? '-',
            'user_role'   => optional($guru->user)->role_label ?? 'Belum Punya Akun',
        ]);
    }

    /**
     * Update the specified Guru in database.
     */
    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nuptk'     => 'required|regex:/^[0-9]{16}$|^[0-9]{18}$/|unique:guru,nuptk,' . $guru->id_guru . ',id_guru',
            'nama_guru' => 'required|string|max:255',
            'no_hp'     => 'nullable|string|max:20',
            'mapel'     => 'nullable|array',
            'mapel.*'   => 'exists:mapel,id_mapel',
        ], [
            'nuptk.required' => 'NUPTK / NIP wajib diisi.',
            'nuptk.regex'    => 'NUPTK harus berisi tepat 16 digit angka (atau NIP 18 digit angka).',
            'nuptk.unique'   => 'NUPTK / NIP ini sudah digunakan oleh guru lain.',
        ]);

        $guru->update([
            'nuptk'     => $validated['nuptk'],
            'nama_guru' => $validated['nama_guru'],
            'no_hp'     => $validated['no_hp'] ?? null,
        ]);

        $guru->mapel()->sync($request->input('mapel', []));

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => 'Data guru berhasil diperbarui']);
        }

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui');
    }

    /**
     * Remove the specified Guru.
     */
    public function destroy(Guru $guru)
    {
        $guru->delete();
        return redirect()->route('guru.index')->with('success', 'Data guru berhasil dihapus');
    }

    /**
     * Export filtered guru data as CSV.
     */
    public function exportCsv(Request $request)
    {
        $records = $this->buildFilteredQuery($request)
            ->orderBy('nama_guru')
            ->get();

        $rows = $records->map(function ($g) {
            $mapelNames = $g->mapel->pluck('nama_mapel')->join(', ');

            return [
                $g->nuptk ?? '-',
                $g->nama_guru,
                $mapelNames ?: '-',
                $g->no_hp ?? '-',
                optional($g->user)->email ?? '-',
                optional($g->user)->role_label ?? 'Belum Punya Akun',
            ];
        });

        $filename = 'data-guru-' . Carbon::now('Asia/Jakarta')->format('Y-m-d') . '.csv';

        return CsvExporter::download($filename, [
            'NUPTK',
            'Nama Guru',
            'Mapel Diampu',
            'No Telp',
            'Email',
            'Status Akun',
        ], $rows);
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = Guru::with(['mapel', 'user']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nuptk', 'like', "%{$search}%")
                  ->orWhere('nama_guru', 'like', "%{$search}%");
            });
        }

        if ($request->filled('id_mapel')) {
            $idMapel = $request->input('id_mapel');
            $query->whereHas('mapel', function ($q) use ($idMapel) {
                $q->where('mapel.id_mapel', $idMapel);
            });
        }

        return $query;
    }
}
