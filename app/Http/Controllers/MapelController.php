<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\Request;

class MapelController extends Controller
{
    public function index()
    {
        $mapel = Mapel::orderBy('nama_mapel')->get();
        return view('mapel.index', compact('mapel'));
    }

    public function create()
    {
        return view('mapel.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:100',
        ]);

        Mapel::create($validated);
        return redirect()->route('mapel.index')->with('success', 'Mapel berhasil ditambahkan');
    }

    public function show(Mapel $mapel)
    {
        return view('mapel.show', compact('mapel'));
    }

    public function edit(Mapel $mapel)
    {
        return view('mapel.edit', compact('mapel'));
    }

    public function update(Request $request, Mapel $mapel)
    {
        $validated = $request->validate([
            'nama_mapel' => 'required|string|max:100',
        ]);

        $mapel->update($validated);
        return redirect()->route('mapel.index')->with('success', 'Mapel berhasil diperbarui');
    }

    public function destroy(Mapel $mapel)
    {
        $mapel->delete();
        return redirect()->route('mapel.index')->with('success', 'Mapel berhasil dihapus');
    }
}