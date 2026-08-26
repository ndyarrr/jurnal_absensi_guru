<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        if (Ruangan::count() === 0) {
            $defaultRuangan = [
                ['nama_ruangan' => 'R. 57'],
                ['nama_ruangan' => 'R. 58'],
                ['nama_ruangan' => 'R. 59'],
                ['nama_ruangan' => 'Lab. RPL 1'],
                ['nama_ruangan' => 'Lab. RPL 2'],
                ['nama_ruangan' => 'Aula Utama'],
            ];
            foreach ($defaultRuangan as $r) {
                Ruangan::firstOrCreate(['nama_ruangan' => $r['nama_ruangan']], $r);
            }
        }

        $query = Ruangan::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama_ruangan', 'like', "%{$search}%");
        }

        $ruangan = $query->orderBy('nama_ruangan')->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'data'       => $ruangan->items(),
                'pagination' => [
                    'first'    => $ruangan->firstItem() ?? 0,
                    'last'     => $ruangan->lastItem() ?? 0,
                    'total'    => $ruangan->total(),
                    'current'  => $ruangan->currentPage(),
                    'lastPage' => $ruangan->lastPage(),
                    'prev'     => $ruangan->previousPageUrl(),
                    'next'     => $ruangan->nextPageUrl(),
                ],
            ]);
        }

        return view('admin.ruangan.index', compact('ruangan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ruangan' => 'required|string|max:50|unique:ruangan,nama_ruangan',
            'keterangan'   => 'nullable|string|max:255',
        ], [
            'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
            'nama_ruangan.unique'   => 'Nama ruangan ini sudah ada di database.',
        ]);

        Ruangan::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => 'Ruangan berhasil ditambahkan!']);
        }

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil ditambahkan!');
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $validated = $request->validate([
            'nama_ruangan' => 'required|string|max:50|unique:ruangan,nama_ruangan,' . $ruangan->id_ruangan . ',id_ruangan',
            'keterangan'   => 'nullable|string|max:255',
        ], [
            'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
            'nama_ruangan.unique'   => 'Nama ruangan ini sudah ada di database.',
        ]);

        $ruangan->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => 'Ruangan berhasil diperbarui!']);
        }

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil diperbarui!');
    }

    public function destroy(Request $request, Ruangan $ruangan)
    {
        $ruangan->delete();

        if ($request->ajax()) {
            return response()->json(['success' => 'Ruangan berhasil dihapus!']);
        }

        return redirect()->route('ruangan.index')->with('success', 'Ruangan berhasil dihapus!');
    }
}
