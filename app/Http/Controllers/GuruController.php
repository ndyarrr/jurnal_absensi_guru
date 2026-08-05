<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::orderBy('nama_guru')->get();
        return view('guru.index', compact('guru'));
    }

    public function create()
    {
        return view('guru.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nuptk' => 'required|string|max:50',
            'nama_guru' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ]);

        Guru::create($validated);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan');
    }

    public function show(Guru $guru)
    {
        return view('guru.show', compact('guru'));
    }

    public function edit(Guru $guru)
    {
        return view('guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nuptk' => 'required|string|max:50',
            'nama_guru' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $guru->update($validated);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();
        return redirect()->route('guru.index')->with('success', 'Data guru berhasil dihapus');
    }
}
