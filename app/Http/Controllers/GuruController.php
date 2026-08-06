<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Mapel;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::with('mapel')->orderBy('nama_guru')->get();
        return view('guru.index', compact('guru'));
    }

    public function create()
    {
        $mapel = Mapel::orderBy('nama_mapel')->get();
        return view('guru.create', compact('mapel'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nuptk' => 'required|string|max:50',
            'nama_guru' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'mapel' => 'nullable|array',
            'mapel.*' => 'exists:mapel,id_mapel',
        ]);

        $guru = Guru::create($validated);
        $guru->mapel()->sync($request->input('mapel', []));

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan');
    }

    public function show(Guru $guru)
    {
        $guru->load('mapel');
        return view('guru.show', compact('guru'));
    }

    public function edit(Guru $guru)
    {
        $guru->load('mapel');
        $mapel = Mapel::orderBy('nama_mapel')->get();
        return view('guru.edit', compact('guru', 'mapel'));
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nuptk' => 'required|string|max:50',
            'nama_guru' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
            'mapel' => 'nullable|array',
            'mapel.*' => 'exists:mapel,id_mapel',
        ]);

        $guru->update($validated);
        $guru->mapel()->sync($request->input('mapel', []));

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();
        return redirect()->route('guru.index')->with('success', 'Data guru berhasil dihapus');
    }
}

