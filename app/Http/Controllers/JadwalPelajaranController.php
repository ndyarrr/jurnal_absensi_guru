<?php

namespace App\Http\Controllers;

use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\Mapel;
use Illuminate\Http\Request;

class JadwalPelajaranController extends Controller
{
    public function index()
    {
        $jadwal = JadwalPelajaran::with(['kelas', 'guru', 'mapel'])
            ->orderBy('hari')
            ->orderBy('jam_ke')
            ->get();
        return view('jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('tingkat')->get();
        $guru = Guru::orderBy('nama_guru')->get();
        $mapel = Mapel::orderBy('nama_mapel')->get();
        return view('jadwal.create', compact('kelas', 'guru', 'mapel'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_ke' => 'required|integer|min:1|max:12',
            'id_guru' => 'required|exists:guru,id_guru',
            'id_mapel' => 'required|exists:mapel,id_mapel',
        ], [
            'jam_ke.max' => 'Jam ke maksimal 12.',
        ]);

        // cegah bentrok jadwal di kelas & jam yang sama
        $bentrok = JadwalPelajaran::where('id_kelas', $validated['id_kelas'])
            ->where('hari', $validated['hari'])
            ->where('jam_ke', $validated['jam_ke'])
            ->exists();

        if ($bentrok) {
            return back()->withInput()->withErrors(['jam_ke' => 'Jadwal bentrok: kelas ini sudah ada pelajaran di hari & jam tersebut.']);
        }

        JadwalPelajaran::create($validated);
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function show(JadwalPelajaran $jadwal)
    {
        $jadwal->load(['kelas', 'guru', 'mapel']);
        return view('jadwal.show', compact('jadwal'));
    }

    public function edit(JadwalPelajaran $jadwal)
    {
        $kelas = Kelas::orderBy('tingkat')->get();
        $guru = Guru::orderBy('nama_guru')->get();
        $mapel = Mapel::orderBy('nama_mapel')->get();
        return view('jadwal.edit', compact('jadwal', 'kelas', 'guru', 'mapel'));
    }

    public function update(Request $request, JadwalPelajaran $jadwal)
    {
        $validated = $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_ke' => 'required|integer|min:1|max:12',
            'id_guru' => 'required|exists:guru,id_guru',
            'id_mapel' => 'required|exists:mapel,id_mapel',
        ]);

        $bentrok = JadwalPelajaran::where('id_kelas', $validated['id_kelas'])
            ->where('hari', $validated['hari'])
            ->where('jam_ke', $validated['jam_ke'])
            ->where('id_jadwal', '!=', $jadwal->id_jadwal)
            ->exists();

        if ($bentrok) {
            return back()->withInput()->withErrors(['jam_ke' => 'Jadwal bentrok: kelas ini sudah ada pelajaran di hari & jam tersebut.']);
        }

        $jadwal->update($validated);
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui');
    }

    public function destroy(JadwalPelajaran $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus');
    }
}