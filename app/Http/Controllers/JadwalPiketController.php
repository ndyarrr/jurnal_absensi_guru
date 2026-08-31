<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPiket;
use App\Models\Guru;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class JadwalPiketController extends Controller
{
    /**
     * Ensure table & initial seed data exists safely.
     */
    private function ensureTableExists()
    {
        if (!Schema::hasTable('jadwal_piket')) {
            Schema::create('jadwal_piket', function (Blueprint $table) {
                $table->integer('id_piket')->autoIncrement();
                $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']);
                $table->integer('id_guru');
                $table->string('keterangan')->nullable();
                $table->timestamps();

                $table->foreign('id_guru')->references('id_guru')->on('guru')->onDelete('cascade');
            });
        }

        // Seed initial sample data if empty
        if (JadwalPiket::count() === 0) {
            $guruList = Guru::take(5)->get();
            $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

            if ($guruList->isNotEmpty()) {
                foreach ($hariList as $idx => $hari) {
                    $guru = $guruList[$idx % $guruList->count()];
                    JadwalPiket::create([
                        'hari' => $hari,
                        'id_guru' => $guru->id_guru,
                        'keterangan' => 'Petugas Piket Utama ' . $hari,
                    ]);
                }
            }
        }
    }

    /**
     * Tampilkan halaman kelola Jadwal Guru Piket (Senin - Jumat).
     */
    public function index(Request $request)
    {
        $this->ensureTableExists();

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        
        $jadwalGrouped = [];
        foreach ($days as $day) {
            $jadwalGrouped[$day] = JadwalPiket::with('guru')
                ->where('hari', $day)
                ->get();
        }

        $guruList = Guru::orderBy('nama_guru')->get();
        $totalPetugas = JadwalPiket::count();

        return view('admin.jadwal_piket.index', compact('days', 'jadwalGrouped', 'guruList', 'totalPetugas'));
    }

    /**
     * Simpan penugasan Guru Piket baru.
     */
    public function store(Request $request)
    {
        $this->ensureTableExists();

        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'id_guru' => 'required|exists:guru,id_guru',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'hari.required' => 'Pilih hari tugas piket.',
            'id_guru.required' => 'Pilih guru yang bertugas.',
            'id_guru.exists' => 'Guru yang dipilih tidak valid.',
        ]);

        $guru = Guru::findOrFail($request->id_guru);

        // Check if teacher is already assigned to this day
        $exists = JadwalPiket::where('hari', $request->hari)
            ->where('id_guru', $request->id_guru)
            ->exists();

        if ($exists) {
            return back()->with('error', "Guru {$guru->nama_guru} sudah terdaftar sebagai Petugas Piket pada hari {$request->hari}.");
        }

        JadwalPiket::create([
            'hari' => $request->hari,
            'id_guru' => $request->id_guru,
            'keterangan' => $request->keterangan ?? ('Petugas Piket Hari ' . $request->hari),
        ]);

        return redirect()->route('jadwal-piket.index')
            ->with('success', "Berhasil menambahkan {$guru->nama_guru} sebagai Petugas Piket hari {$request->hari}.");
    }

    /**
     * Hapus penugasan Guru Piket.
     */
    public function destroy($id)
    {
        $this->ensureTableExists();

        $jadwal = JadwalPiket::with('guru')->findOrFail($id);
        $namaGuru = optional($jadwal->guru)->nama_guru ?? 'Guru';
        $hari = $jadwal->hari;

        $jadwal->delete();

        return redirect()->route('jadwal-piket.index')
            ->with('success', "Penugasan piket {$namaGuru} pada hari {$hari} telah dihapus.");
    }
}
