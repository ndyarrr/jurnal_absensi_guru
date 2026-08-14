<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelasController extends Controller
{
    /**
     * Display Master Data - Kelas page matching the exact mockup design.
     */
    public function index(Request $request)
    {
        $query = Kelas::with(['jurusan', 'waliKelasGuru']);

        // Search Filter (Tingkat, Kode Jurusan, Nama Jurusan, Wali Kelas)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('tingkat', 'like', "%{$search}%")
                  ->orWhere('rombel', 'like', "%{$search}%")
                  ->orWhere('wali_kelas', 'like', "%{$search}%")
                  ->orWhereHas('jurusan', function ($jQ) use ($search) {
                      $jQ->where('kode_jurusan', 'like', "%{$search}%")
                         ->orWhere('nama_jurusan', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Tingkat
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->input('tingkat'));
        }

        // Filter by Jurusan
        if ($request->filled('id_jurusan')) {
            $query->where('id_jurusan', $request->input('id_jurusan'));
        }

        // Filter by Rombel
        if ($request->filled('rombel')) {
            $query->where('rombel', $request->input('rombel'));
        }

        $kelas = $query->orderBy('tingkat', 'asc')->orderBy('id_jurusan', 'asc')->orderBy('rombel', 'asc')->paginate(8)->withQueryString();

        $totalKelasCount   = Kelas::count();
        $totalJurusanCount = Jurusan::count();

        $jurusanList = Jurusan::orderBy('nama_jurusan')->get();
        $guruList    = Guru::orderBy('nama_guru')->get();
        $tingkatList = ['X', 'XI', 'XII'];

        // Dynamic Rombel list fetched from database
        $dbRombels  = Kelas::select('rombel')->distinct()->whereNotNull('rombel')->pluck('rombel')->map(fn($r) => (int)$r)->unique()->sort()->values()->toArray();
        $rombelList = !empty($dbRombels) ? $dbRombels : [1, 2, 3, 4, 5];

        // Map of assigned Wali Kelas (key: id_guru_wali, val: kelas name)
        $assignedWaliMap = [];
        $allKelasWithWali = Kelas::with('jurusan')->whereNotNull('id_guru_wali')->get();
        foreach ($allKelasWithWali as $kItem) {
            $assignedWaliMap[$kItem->id_guru_wali] = [
                'id_kelas'   => $kItem->id_kelas,
                'kelas_name' => 'Tingkat ' . $kItem->tingkat . ' ' . optional($kItem->jurusan)->kode_jurusan . ' ' . $kItem->rombel
            ];
        }

        return view('kelas.index', compact(
            'kelas',
            'totalKelasCount',
            'totalJurusanCount',
            'jurusanList',
            'guruList',
            'tingkatList',
            'rombelList',
            'assignedWaliMap'
        ));
    }

    /**
     * Store a newly created Kelas.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tingkat'      => 'required|in:X,XI,XII',
            'id_jurusan'   => 'required|exists:jurusan,id_jurusan',
            'rombel'       => 'required|integer|min:1',
            'id_guru_wali' => [
                'required',
                'exists:guru,id_guru',
                Rule::unique('kelas', 'id_guru_wali'),
            ],
            'jumlah_siswa' => 'nullable|integer|min:0',
        ], [
            'tingkat.required'      => 'Tingkat kelas wajib dipilih.',
            'id_jurusan.required'   => 'Jurusan wajib dipilih.',
            'rombel.required'       => 'Rombel wajib diisi (angka 1, 2, dll).',
            'id_guru_wali.required' => 'Wali Kelas wajib dipilih.',
            'id_guru_wali.unique'   => 'Guru ini sudah ditugaskan menjadi Wali Kelas di kelas lain.',
        ]);

        // Auto fill wali_kelas string from Guru relationship
        $guru = Guru::find($validated['id_guru_wali']);
        $validated['wali_kelas'] = $guru ? $guru->nama_guru : null;

        Kelas::create($validated);

        return redirect()->route('kelas.index')->with('success', 'Data kelas baru berhasil ditambahkan');
    }

    /**
     * Show details of a specific Kelas via JSON AJAX for view modal.
     */
    public function show(Kelas $kelas)
    {
        $kelas->load(['jurusan', 'waliKelasGuru']);

        return response()->json([
            'id_kelas'     => $kelas->id_kelas,
            'tingkat'      => $kelas->tingkat,
            'rombel'       => $kelas->rombel,
            'nama_kelas'   => $kelas->tingkat . ' ' . optional($kelas->jurusan)->kode_jurusan . ' ' . $kelas->rombel,
            'kode_jurusan' => optional($kelas->jurusan)->kode_jurusan ?? '-',
            'nama_jurusan' => optional($kelas->jurusan)->nama_jurusan ?? '-',
            'id_jurusan'   => $kelas->id_jurusan,
            'id_guru_wali' => $kelas->id_guru_wali,
            'wali_kelas'   => $kelas->wali_kelas ?: (optional($kelas->waliKelasGuru)->nama_guru ?? 'Belum Ditentukan'),
            'jumlah_siswa' => $kelas->jumlah_siswa ?? 0,
        ]);
    }

    /**
     * Update the specified Kelas.
     */
    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'tingkat'      => 'required|in:X,XI,XII',
            'id_jurusan'   => 'required|exists:jurusan,id_jurusan',
            'rombel'       => 'required|integer|min:1',
            'id_guru_wali' => [
                'required',
                'exists:guru,id_guru',
                Rule::unique('kelas', 'id_guru_wali')->ignore($kelas->id_kelas, 'id_kelas'),
            ],
            'jumlah_siswa' => 'nullable|integer|min:0',
        ], [
            'id_guru_wali.required' => 'Wali Kelas wajib dipilih.',
            'id_guru_wali.unique'   => 'Guru ini sudah ditugaskan menjadi Wali Kelas di kelas lain.',
        ]);

        $guru = Guru::find($validated['id_guru_wali']);
        $validated['wali_kelas'] = $guru ? $guru->nama_guru : null;

        $kelas->update($validated);

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui');
    }

    /**
     * Remove the specified Kelas.
     */
    public function destroy(Kelas $kelas)
    {
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil dihapus');
    }
}