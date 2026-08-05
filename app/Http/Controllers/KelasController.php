<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Jurusan;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('jurusan')->get();
        return view('kelas.index', compact('kelas'));
    }

    public function create()
    {
        $jurusan = Jurusan::all();
        return view('kelas.create', compact('jurusan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tingkat' => 'required|in:X,XI,XII',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'rombel' => 'required|integer|min:1',
            'wali_kelas' => 'nullable|string|max:100',
            'jumlah_siswa' => 'nullable|integer|min:0',
        ]);

        Kelas::create($validated);
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    public function show(Kelas $kelas)
    {
        $kelas->load('jurusan');
        return view('kelas.show', compact('kelas'));
    }

    public function edit(Kelas $kelas)
    {
        $jurusan = Jurusan::all();
        return view('kelas.edit', compact('kelas', 'jurusan'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'tingkat' => 'required|in:X,XI,XII',
            'id_jurusan' => 'required|exists:jurusan,id_jurusan',
            'rombel' => 'required|integer|min:1',
            'wali_kelas' => 'nullable|string|max:100',
            'jumlah_siswa' => 'nullable|integer|min:0',
        ]);

        $kelas->update($validated);
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus');
    }
}