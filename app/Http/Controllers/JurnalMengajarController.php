<?php

namespace App\Http\Controllers;

use App\Models\JurnalMengajar;
use App\Models\JadwalPelajaran;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JurnalMengajarController extends Controller
{
    public function index(Request $request)
    {
        $query = JurnalMengajar::with(['jadwal.kelas.jurusan', 'jadwal.mapel', 'jadwal.guru', 'jadwal.jamPelajaran']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('materi', 'like', "%{$search}%")
                  ->orWhereHas('jadwal.mapel', fn($mQ) => $mQ->withTrashed()->where('nama_mapel', 'like', "%{$search}%"))
                  ->orWhereHas('jadwal.guru', fn($gQ) => $gQ->withTrashed()->where('nama_guru', 'like', "%{$search}%"))
                  ->orWhereHas('jadwal.kelas', fn($kQ) => $kQ->withTrashed()->where('tingkat', 'like', "%{$search}%")->orWhere('rombel', 'like', "%{$search}%"));
            });
        }

        // Filter by Guru (matching teacher name to cover duplicates & trashed)
        if ($request->filled('id_guru')) {
            $guruObj = Guru::withTrashed()->find($request->input('id_guru'));
            if ($guruObj) {
                $guruName = $guruObj->nama_guru;
                $query->whereHas('jadwal.guru', fn($gQ) => $gQ->withTrashed()->where('nama_guru', $guruName));
            } else {
                $query->whereHas('jadwal', fn($q) => $q->withTrashed()->where('id_guru', $request->input('id_guru')));
            }
        }

        // Filter by Kelas (matching tingkat, rombel, id_jurusan to cover duplicates & trashed)
        if ($request->filled('id_kelas')) {
            $kelasObj = Kelas::withTrashed()->find($request->input('id_kelas'));
            if ($kelasObj) {
                $query->whereHas('jadwal.kelas', function ($kQ) use ($kelasObj) {
                    $kQ->withTrashed()
                       ->where('tingkat', $kelasObj->tingkat)
                       ->where('rombel', $kelasObj->rombel);
                    if ($kelasObj->id_jurusan) {
                        $kQ->where('id_jurusan', $kelasObj->id_jurusan);
                    }
                });
            } else {
                $query->whereHas('jadwal', fn($q) => $q->withTrashed()->where('id_kelas', $request->input('id_kelas')));
            }
        }

        // Filter by Mapel (matching subject name to cover duplicates & trashed)
        if ($request->filled('id_mapel')) {
            $mapelObj = Mapel::withTrashed()->find($request->input('id_mapel'));
            if ($mapelObj) {
                $mapelName = $mapelObj->nama_mapel;
                $query->whereHas('jadwal.mapel', fn($mQ) => $mQ->withTrashed()->where('nama_mapel', $mapelName));
            } else {
                $query->whereHas('jadwal', fn($q) => $q->withTrashed()->where('id_mapel', $request->input('id_mapel')));
            }
        }

        // Filter by Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('tanggal', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('tanggal', '<=', $request->input('date_to'));
        }

        // Dynamic stats calculated from filtered query
        $statsCollection = (clone $query)->get();
        $totalPertemuan  = $statsCollection->count();
        $terlaksana       = $statsCollection->where('status_kehadiran', 'Hadir')->count();
        $belumTerlaksana = $totalPertemuan - $terlaksana;
        $totalGuruAktif  = $statsCollection->map(fn($j) => optional($j->jadwal)->id_guru)->filter()->unique()->count();

        $jurnal = $query->orderByDesc('tanggal')->paginate(10)->withQueryString();

        // AJAX response
        if ($request->ajax() || $request->wantsJson()) {
            $tableRows = $jurnal->map(function ($j) {
                $isMapelDel = !optional($j->jadwal)->mapel || optional(optional($j->jadwal)->mapel)->trashed();
                $isKelasDel = !optional($j->jadwal)->kelas || optional(optional($j->jadwal)->kelas)->trashed();
                $isGuruDel  = !optional($j->jadwal)->guru || optional(optional($j->jadwal)->guru)->trashed();

                $kelasStr = '-';
                if (!$isKelasDel && $j->jadwal && $j->jadwal->kelas) {
                    $kelasStr = $j->jadwal->kelas->tingkat . ' ' . optional($j->jadwal->kelas->jurusan)->kode_jurusan . ' ' . $j->jadwal->kelas->rombel;
                }

                $jumlahSiswa = 0;
                if ($j->jadwal && $j->jadwal->kelas) {
                    $jumlahSiswa = $j->jadwal->kelas->jumlah_siswa ?? 36;
                }

                return [
                    'id_jurnal'        => $j->id_jurnal,
                    'tanggal'          => $j->tanggal,
                    'tanggal_day'      => Carbon::parse($j->tanggal)->format('d'),
                    'tanggal_month'    => strtoupper(Carbon::parse($j->tanggal)->translatedFormat('M')),
                    'tanggal_year'     => Carbon::parse($j->tanggal)->format('Y'),
                    'mapel'            => $isMapelDel ? '-' : $j->jadwal->mapel->nama_mapel,
                    'kelas'            => $kelasStr,
                    'guru'             => $isGuruDel ? '-' : $j->jadwal->guru->nama_guru,
                    'materi'           => $j->materi ?? '-',
                    'pertemuan'        => ($j->jumlah_hadir ?? 0) . ' / ' . $jumlahSiswa,
                    'status_kehadiran' => $j->status_kehadiran,
                    'is_mapel_deleted' => $isMapelDel,
                    'is_kelas_deleted' => $isKelasDel,
                    'is_guru_deleted'  => $isGuruDel,
                ];
            });

            return response()->json([
                'data' => $tableRows,
                'stats' => [
                    'total_pertemuan'   => $totalPertemuan,
                    'terlaksana'        => $terlaksana,
                    'belum_terlaksana'  => $belumTerlaksana,
                    'total_guru_aktif'  => $totalGuruAktif,
                ],
                'pagination' => [
                    'first'    => $jurnal->firstItem() ?? 0,
                    'last'     => $jurnal->lastItem() ?? 0,
                    'total'    => $jurnal->total(),
                    'current'  => $jurnal->currentPage(),
                    'lastPage' => $jurnal->lastPage(),
                    'prev'     => $jurnal->previousPageUrl(),
                    'next'     => $jurnal->nextPageUrl(),
                ],
            ]);
        }

        // Dropdown Lists for Filters (include trashed models & unique items)
        $gurus   = Guru::withTrashed()->orderBy('nama_guru')->get()->unique('nama_guru');
        $kelases = Kelas::withTrashed()->with(['jurusan' => fn($j) => $j->withTrashed()])
            ->orderBy('tingkat')->orderBy('id_jurusan')->orderBy('rombel')->get()
            ->unique(function ($k) {
                return $k->tingkat . '-' . $k->id_jurusan . '-' . $k->rombel;
            });
        $mapels  = Mapel::withTrashed()->orderBy('nama_mapel')->get()->unique('nama_mapel');

        return view('admin.jurnal.index', compact(
            'jurnal', 'gurus', 'kelases', 'mapels',
            'totalPertemuan', 'terlaksana', 'belumTerlaksana', 'totalGuruAktif'
        ));
    }

    public function create()
    {
        $jadwal = JadwalPelajaran::with(['kelas.jurusan', 'mapel', 'guru'])->get();
        $guru = Guru::orderBy('nama_guru')->get();
        $siswa = Siswa::orderBy('nama_siswa')->get();
        $jadwalKelasMap = $jadwal->pluck('id_kelas', 'id_jadwal');

        return view('admin.jurnal.create', compact('jadwal', 'guru', 'siswa', 'jadwalKelasMap'));
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

        if ($request->ajax()) {
            return response()->json(['success' => 'Jurnal berhasil disimpan.']);
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

        if (request()->ajax()) {
            return response()->json([
                'id_jurnal'          => $jurnal->id_jurnal,
                'tanggal'            => Carbon::parse($jurnal->tanggal)->translatedFormat('d F Y'),
                'hari'               => optional($jurnal->jadwal)->hari ?? '-',
                'jam_ke'             => optional($jurnal->jadwal)->jam_ke ?? '-',
                'mapel'              => optional(optional($jurnal->jadwal)->mapel)->nama_mapel ?? '-',
                'kelas'              => optional(optional($jurnal->jadwal)->kelas) ? (optional($jurnal->jadwal)->kelas->tingkat . ' ' . optional(optional($jurnal->jadwal)->kelas->jurusan)->kode_jurusan . ' ' . optional($jurnal->jadwal)->kelas->rombel) : '-',
                'guru'               => optional(optional($jurnal->jadwal)->guru)->nama_guru ?? '-',
                'status_kehadiran'   => $jurnal->status_kehadiran,
                'materi'             => $jurnal->materi ?? '-',
                'jumlah_hadir'       => $jurnal->jumlah_hadir ?? 0,
                'jumlah_tidak_hadir' => $jurnal->jumlah_tidak_hadir ?? 0,
                'catatan'            => $jurnal->catatan ?? '-',
                'guru_pengganti'     => optional($jurnal->guruPengganti)->nama_guru ?? '-',
            ]);
        }

        return view('admin.jurnal.show', compact('jurnal'));
    }

    public function edit(JurnalMengajar $jurnal)
    {
        $jadwal = JadwalPelajaran::with(['kelas.jurusan', 'mapel', 'guru'])->get();
        $guru = Guru::orderBy('nama_guru')->get();
        $siswa = Siswa::orderBy('nama_siswa')->get();
        $jadwalKelasMap = $jadwal->pluck('id_kelas', 'id_jadwal');
        $jurnal->load('detailKetidakhadiran');

        return view('admin.jurnal.edit', compact('jurnal', 'jadwal', 'guru', 'siswa', 'jadwalKelasMap'));
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

        if ($request->ajax()) {
            return response()->json(['success' => 'Jurnal berhasil diperbarui.']);
        }
        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil diperbarui');
    }

    public function destroy(Request $request, JurnalMengajar $jurnal)
    {
        $jurnal->delete();
        if ($request->ajax()) {
            return response()->json(['success' => 'Jurnal berhasil dihapus.']);
        }
        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil dihapus');
    }
}