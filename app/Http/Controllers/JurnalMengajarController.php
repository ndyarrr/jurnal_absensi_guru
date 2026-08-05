<?php

namespace App\Http\Controllers;

use App\Models\JurnalMengajar;
use App\Models\JadwalPelajaran;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;

class JurnalMengajarController extends Controller
{
    public function index()
    {
        $jurnal = JurnalMengajar::with(['jadwal.kelas.jurusan', 'jadwal.mapel', 'jadwal.guru'])
            ->orderByDesc('tanggal')
            ->get();
        return view('jurnal.index', compact('jurnal'));
    }

    public function create()
    {
        $jadwal = JadwalPelajaran::with(['kelas.jurusan', 'mapel', 'guru'])->get();
        $guru = Guru::orderBy('nama_guru')->get();
        $siswa = Siswa::orderBy('nama_siswa')->get();
        $jadwalKelasMap = $jadwal->pluck('id_kelas', 'id_jadwal');

        return view('jurnal.create', compact('jadwal', 'guru', 'siswa', 'jadwalKelasMap'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_jadwal' => 'required|exists:jadwal_pelajaran,id_jadwal',
            'tanggal' => 'required|date',
            'status_kehadiran' => 'required|in:Hadir,Izin,Sakit,Tanpa Keterangan',
            'id_guru_pengganti' => 'nullable|exists:guru,id_guru',
            'materi' => 'nullable|string|max:255',
            'jumlah_hadir' => 'nullable|integer|min:0',
            'jumlah_tidak_hadir' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string|max:255',
            'detail' => 'nullable|array',
            'detail.*.id_siswa' => 'required_with:detail|exists:siswa,id_siswa',
            'detail.*.status' => 'required_with:detail|in:S,I,A',
        ]);

        $jurnal = JurnalMengajar::create([
            'id_jadwal' => $validated['id_jadwal'],
            'tanggal' => $validated['tanggal'],
            'status_kehadiran' => $validated['status_kehadiran'],
            'id_guru_pengganti' => $validated['id_guru_pengganti'] ?? null,
            'materi' => $validated['materi'] ?? null,
            'jumlah_hadir' => $validated['jumlah_hadir'] ?? 0,
            'jumlah_tidak_hadir' => $validated['jumlah_tidak_hadir'] ?? 0,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        if (!empty($validated['detail'])) {
            foreach ($validated['detail'] as $d) {
                $jurnal->detailKetidakhadiran()->create([
                    'id_siswa' => $d['id_siswa'],
                    'status' => $d['status'],
                ]);
            }
        }

        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil disimpan');
    }

    public function show(JurnalMengajar $jurnal)
    {
        $jurnal->load([
            'jadwal.kelas.jurusan',
            'jadwal.mapel',
            'jadwal.guru',
            'guruPengganti',
            'detailKetidakhadiran.siswa' => function ($query) {
                $query->withTrashed();
            }
        ]);
        return view('jurnal.show', compact('jurnal'));
    }

    public function edit(JurnalMengajar $jurnal)
    {
        $jadwal = JadwalPelajaran::with(['kelas.jurusan', 'mapel', 'guru'])->get();
        $guru = Guru::orderBy('nama_guru')->get();
        $siswa = Siswa::orderBy('nama_siswa')->get();
        $jadwalKelasMap = $jadwal->pluck('id_kelas', 'id_jadwal');
        $jurnal->load('detailKetidakhadiran');

        return view('jurnal.edit', compact('jurnal', 'jadwal', 'guru', 'siswa', 'jadwalKelasMap'));
    }

    public function update(Request $request, JurnalMengajar $jurnal)
    {
        $validated = $request->validate([
            'id_jadwal' => 'required|exists:jadwal_pelajaran,id_jadwal',
            'tanggal' => 'required|date',
            'status_kehadiran' => 'required|in:Hadir,Izin,Sakit,Tanpa Keterangan',
            'id_guru_pengganti' => 'nullable|exists:guru,id_guru',
            'materi' => 'nullable|string|max:255',
            'jumlah_hadir' => 'nullable|integer|min:0',
            'jumlah_tidak_hadir' => 'nullable|integer|min:0',
            'catatan' => 'nullable|string|max:255',
            'detail' => 'nullable|array',
            'detail.*.id_siswa' => 'required_with:detail|exists:siswa,id_siswa',
            'detail.*.status' => 'required_with:detail|in:S,I,A',
        ]);

        $jurnal->update([
            'id_jadwal' => $validated['id_jadwal'],
            'tanggal' => $validated['tanggal'],
            'status_kehadiran' => $validated['status_kehadiran'],
            'id_guru_pengganti' => $validated['id_guru_pengganti'] ?? null,
            'materi' => $validated['materi'] ?? null,
            'jumlah_hadir' => $validated['jumlah_hadir'] ?? 0,
            'jumlah_tidak_hadir' => $validated['jumlah_tidak_hadir'] ?? 0,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        // hapus detail lama, ganti dengan yang baru (paling simpel & aman)
        $jurnal->detailKetidakhadiran()->delete();

        if (!empty($validated['detail'])) {
            foreach ($validated['detail'] as $d) {
                $jurnal->detailKetidakhadiran()->create([
                    'id_siswa' => $d['id_siswa'],
                    'status' => $d['status'],
                ]);
            }
        }

        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil diperbarui');
    }

    public function destroy(JurnalMengajar $jurnal)
    {
        $jurnal->delete();
        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil dihapus');
    }
}