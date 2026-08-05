<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        $siswa = Siswa::with('kelas')->orderBy('nama_siswa')->get();
        return view('siswa.index', compact('siswa'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('tingkat')->get();
        return view('siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|max:10|unique:siswa,nisn',
            'nama_siswa' => 'required|string|max:100',
            'id_kelas' => 'required|exists:kelas,id_kelas',
        ]);

        Siswa::create($validated);
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan');
    }

    public function show(Siswa $siswa)
    {
        $siswa->load('kelas');
        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::orderBy('tingkat')->get();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nisn' => 'required|string|max:10|unique:siswa,nisn,' . $siswa->id_siswa . ',id_siswa',
            'nama_siswa' => 'required|string|max:100',
            'id_kelas' => 'required|exists:kelas,id_kelas',
        ]);

        $siswa->update($validated);
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil diperbarui');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus');
    }
}