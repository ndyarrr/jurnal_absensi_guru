<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Support\CsvExporter;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SiswaController extends Controller
{
    /**
     * Display Master Data - Siswa page matching the exact mockup design.
     */
    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $siswa = $query->orderBy('nama_siswa', 'asc')->paginate(8)->withQueryString();
        $totalSiswaCount = Siswa::count();

        // Master lists for filter dropdowns and create/edit selects
        $kelasList   = Kelas::with('jurusan')->orderBy('tingkat')->get();
        $jurusanList = Jurusan::orderBy('nama_jurusan')->get();
        $tingkatList = ['X', 'XI', 'XII'];

        // Dynamic Rombel list fetched from database
        $dbRombels  = Kelas::select('rombel')->distinct()->whereNotNull('rombel')->pluck('rombel')->map(fn($r) => (int)$r)->unique()->sort()->values()->toArray();
        $rombelList = !empty($dbRombels) ? $dbRombels : [1, 2, 3, 4, 5];

        return view('admin.siswa.index', compact(
            'siswa',
            'totalSiswaCount',
            'kelasList',
            'jurusanList',
            'tingkatList',
            'rombelList'
        ));
    }

    /**
     * Store a newly created Siswa.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn'          => 'required|digits:10|unique:siswa,nisn',
            'nama_siswa'    => 'required|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_telepon'    => 'nullable|string|max:20',
            'id_kelas'      => 'required|exists:kelas,id_kelas',
        ], [
            'nisn.required'       => 'NISN wajib diisi.',
            'nisn.digits'         => 'NISN harus berisi tepat 10 digit angka.',
            'nisn.unique'         => 'NISN sudah terdaftar.',
            'nama_siswa.required' => 'Nama siswa wajib diisi.',
            'id_kelas.required'   => 'Kelas wajib dipilih.',
        ]);

        Siswa::create($validated);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan');
    }

    /**
     * Show details of a specific Siswa via JSON AJAX.
     */
    public function show(Siswa $siswa)
    {
        $siswa->load('kelas.jurusan');
        return response()->json([
            'id_siswa'      => $siswa->id_siswa,
            'nisn'          => $siswa->nisn,
            'nama_siswa'    => $siswa->nama_siswa,
            'jenis_kelamin' => $siswa->jenis_kelamin ?? '-',
            'jk_label'      => $siswa->jenis_kelamin === 'L' ? 'Laki-laki (L)' : ($siswa->jenis_kelamin === 'P' ? 'Perempuan (P)' : '-'),
            'no_telepon'    => $siswa->no_telepon,
            'kelas_str'     => optional($siswa->kelas)->tingkat
                            . ' ' . optional(optional($siswa->kelas)->jurusan)->kode_jurusan
                            . ' ' . optional($siswa->kelas)->rombel,
            'id_kelas'      => $siswa->id_kelas,
        ]);
    }

    /**
     * Update the specified Siswa in database.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $validated = $request->validate([
            'nisn'          => 'required|digits:10|unique:siswa,nisn,' . $siswa->id_siswa . ',id_siswa',
            'nama_siswa'    => 'required|string|max:100',
            'jenis_kelamin' => 'nullable|in:L,P',
            'no_telepon'    => 'nullable|string|max:20',
            'id_kelas'      => 'required|exists:kelas,id_kelas',
        ], [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.digits'   => 'NISN harus berisi tepat 10 digit angka.',
            'nisn.unique'   => 'NISN ini sudah digunakan oleh siswa lain.',
        ]);

        $siswa->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => 'Data siswa berhasil diperbarui']);
        }

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * Remove the specified Siswa.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil dihapus');
    }

    /**
     * Export filtered siswa data as CSV.
     */
    public function exportCsv(Request $request)
    {
        $records = $this->buildFilteredQuery($request)
            ->orderBy('nama_siswa')
            ->get();

        $rows = $records->map(function ($s) {
            $kelasStr = '-';
            if ($s->kelas && !$s->kelas->trashed()) {
                $kelasStr = trim(
                    $s->kelas->tingkat . ' '
                    . optional($s->kelas->jurusan)->kode_jurusan . ' '
                    . $s->kelas->rombel
                );
            }

            $jkText = '-';
            if ($s->jenis_kelamin === 'L') {
                $jkText = 'Laki-laki (L)';
            } elseif ($s->jenis_kelamin === 'P') {
                $jkText = 'Perempuan (P)';
            }

            return [
                $s->nisn,
                $s->nama_siswa,
                $jkText,
                $s->no_telepon ?? '-',
                $kelasStr,
                'Aktif',
            ];
        });

        $filename = 'data-siswa-' . Carbon::now('Asia/Jakarta')->format('Y-m-d') . '.csv';

        return CsvExporter::download($filename, [
            'NISN',
            'Nama Siswa',
            'Jenis Kelamin',
            'No. Telepon',
            'Kelas',
            'Status',
        ], $rows);
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = Siswa::with(['kelas.jurusan']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nisn', 'like', "%{$search}%")
                  ->orWhere('nama_siswa', 'like', "%{$search}%");
            });
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->input('jenis_kelamin'));
        }

        if ($request->filled('tingkat')) {
            $tingkat = $request->input('tingkat');
            $query->whereHas('kelas', function ($q) use ($tingkat) {
                $q->where('tingkat', $tingkat);
            });
        }

        if ($request->filled('id_jurusan')) {
            $idJurusan = $request->input('id_jurusan');
            $query->whereHas('kelas', function ($q) use ($idJurusan) {
                $q->where('id_jurusan', $idJurusan);
            });
        }

        if ($request->filled('rombel')) {
            $rombel = $request->input('rombel');
            $query->whereHas('kelas', function ($q) use ($rombel) {
                $q->where('rombel', $rombel);
            });
        }

        return $query;
    }
}