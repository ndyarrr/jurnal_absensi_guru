<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::orderBy('nama_jurusan')->get();
        return view('admin.jurusan.index', compact('jurusan'));
    }

    public function create()
    {
        return view('admin.jurusan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_jurusan' => 'required|string|max:10|unique:jurusan,kode_jurusan',
            'nama_jurusan' => 'required|string|max:100',
        ]);

        Jurusan::create($validated);
        return redirect()->back()->with('success', 'Jurusan baru (' . $validated['kode_jurusan'] . ') berhasil ditambahkan');
    }

    public function show(Jurusan $jurusan)
    {
        if (request()->wantsJson() || request()->ajax()) {
            $jurusan->loadCount('kelas');

            return response()->json([
                'id_jurusan'   => $jurusan->id_jurusan,
                'kode_jurusan' => $jurusan->kode_jurusan,
                'nama_jurusan' => $jurusan->nama_jurusan,
                'jumlah_kelas' => $jurusan->kelas_count,
            ]);
        }

        return view('admin.jurusan.show', compact('jurusan'));
    }

    public function edit(Jurusan $jurusan)
    {
        return view('admin.jurusan.edit', compact('jurusan'));
    }

    public function update(Request $request, Jurusan $jurusan)
    {
        $validated = $request->validate([
            'kode_jurusan' => 'required|string|max:10|unique:jurusan,kode_jurusan,' . $jurusan->id_jurusan . ',id_jurusan',
            'nama_jurusan' => 'required|string|max:100',
        ]);

        $jurusan->update($validated);

        return redirect()->back()->with('success', 'Jurusan berhasil diperbarui');
    }

    public function destroy(Jurusan $jurusan)
    {
        $kelasCount = $jurusan->kelas()->count();

        if ($kelasCount > 0) {
            return redirect()->back()->with(
                'error',
                'Jurusan "' . $jurusan->kode_jurusan . '" tidak dapat dihapus karena masih memiliki ' . $kelasCount . ' kelas terkait.'
            );
        }

        $kode = $jurusan->kode_jurusan;
        $jurusan->delete();

        return redirect()->back()->with('success', 'Jurusan "' . $kode . '" berhasil dihapus');
    }
}