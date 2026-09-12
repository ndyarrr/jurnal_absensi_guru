<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Mapel;
use App\Support\CsvExporter;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GuruController extends Controller
{
    /**
     * Display Master Data - Guru page matching the exact mockup design.
     */
    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request);
        $guru = $query->orderBy('nama_guru', 'asc')->paginate(8)->withQueryString();
        $totalGuruCount = Guru::count();
        $mapelList = Mapel::orderBy('nama_mapel')->get();

        return view('admin.guru.index', compact('guru', 'totalGuruCount', 'mapelList'));
    }

    /**
     * Store a newly created Guru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip'     => ['required', 'regex:/^([0-9]{16}|[0-9]{18})$/', 'unique:guru,nip'],
            'nama_guru' => ['required', 'string', 'max:255'],
            'no_hp'     => ['nullable', 'string', 'max:20'],
            'mapel'     => ['nullable', 'array'],
            'mapel.*'   => ['exists:mapel,id_mapel'],
        ], [
            'nip.required'     => 'NIP wajib diisi.',
            'nip.regex'        => 'NIP harus berisi tepat 16 atau 18 digit angka.',
            'nip.unique'       => 'NIP sudah terdaftar.',
            'nama_guru.required' => 'Nama guru wajib diisi.',
        ]);

        $guru = Guru::create([
            'nip'     => $validated['nip'],
            'nama_guru' => $validated['nama_guru'],
            'no_hp'     => $validated['no_hp'] ?? null,
        ]);

        $guru->mapel()->sync($request->input('mapel', []));

        // Auto-create User login account for this new Guru (Username = NIP, Password = NIP)
        \App\Models\User::create([
            'name'     => $guru->nama_guru,
            'username' => $validated['nip'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['nip']),
            'role'     => 'guru_mengajar',
            'id_guru'  => $guru->id_guru,
        ]);

        return redirect()->route('guru.index')->with('success', "Data guru & akun login pengguna ({$guru->nama_guru}) berhasil ditambahkan! Username: {$validated['nip']} | Password default: {$validated['nip']}");
    }

    /**
     * Show details of a specific Guru via JSON AJAX for modal.
     */
    public function show(Guru $guru)
    {
        $guru->load(['mapel', 'user']);

        $mapelNames = $guru->mapel ? $guru->mapel->pluck('nama_mapel')->filter()->join(', ') : '';
        if (empty($mapelNames)) {
            $mapelNames = '-';
        }

        $mapelIds = $guru->mapel ? $guru->mapel->pluck('id_mapel')->map(function($id) { return (int) $id; })->toArray() : [];

        return response()->json([
            'id_guru'     => $guru->id_guru,
            'nip'       => $guru->nip,
            'nama_guru'   => $guru->nama_guru,
            'no_hp'       => $guru->no_hp ?? '-',
            'mapel_names' => $mapelNames,
            'mapel_ids'   => $mapelIds,
            'username'    => optional($guru->user)->username ?? '-',
            'user_role'   => optional($guru->user)->role_label ?? 'Belum Punya Akun',
        ]);
    }

    /**
     * Update the specified Guru in database.
     */
    public function update(Request $request, Guru $guru)
    {
        $validated = $request->validate([
            'nip'     => ['required', 'regex:/^([0-9]{16}|[0-9]{18})$/', 'unique:guru,nip,' . $guru->id_guru . ',id_guru'],
            'nama_guru' => ['required', 'string', 'max:255'],
            'no_hp'     => ['nullable', 'string', 'max:20'],
            'mapel'     => ['nullable', 'array'],
            'mapel.*'   => ['exists:mapel,id_mapel'],
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.regex'    => 'NIP harus berisi tepat 16 atau 18 digit angka.',
            'nip.unique'   => 'NIP ini sudah digunakan oleh guru lain.',
        ]);

        $guru->update([
            'nip'     => $validated['nip'],
            'nama_guru' => $validated['nama_guru'],
            'no_hp'     => $validated['no_hp'] ?? null,
        ]);

        $guru->mapel()->sync($request->input('mapel', []));

        // Sync associated User name
        if ($guru->user) {
            $guru->user->update([
                'name' => $validated['nama_guru'],
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => 'Data guru berhasil diperbarui']);
        }

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui');
    }

    /**
     * Remove the specified Guru.
     */
    public function destroy(Request $request, Guru $guru)
    {
        if ($guru->user) {
            $guru->user->delete();
        }
        $guru->delete();
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => 'Data guru & akun penggunanya berhasil dihapus']);
        }
        return redirect()->route('guru.index')->with('success', 'Data guru & akun penggunanya berhasil dihapus');
    }

    /**
     * Remove multiple guru records from database at once.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['error' => 'Tidak ada guru yang dipilih untuk dihapus.'], 400);
        }

        $gurus = Guru::with('user')->whereIn('id_guru', $ids)->get();
        $deletedCount = 0;

        foreach ($gurus as $guru) {
            if ($guru->user) {
                $guru->user->delete();
            }
            $guru->delete();
            $deletedCount++;
        }

        return response()->json([
            'success' => "{$deletedCount} data guru & akun penggunanya berhasil dihapus.",
            'deleted_count' => $deletedCount,
            'deleted_ids' => $ids,
        ]);
    }

    /**
     * Remove duplicate Guru entries and re-link relations.
     */
    public function deduplicate(Request $request)
    {
        $result = \App\Console\Commands\DeduplicateGuruCommand::runDeduplication();

        if ($result['deleted_count'] > 0) {
            $msg = "Berhasil membersihkan data! {$result['merged_count']} kelompok guru ganda berhasil digabungkan dan {$result['deleted_count']} data guru ganda telah dihapus.";
        } else {
            $msg = "Tidak ditemukan data guru ganda. Seluruh data guru sudah bersih.";
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }

        return redirect()->route('guru.index')->with('success', $msg);
    }

    /**
     * Export filtered guru data as CSV.
     */
    public function exportCsv(Request $request)
    {
        $records = $this->buildFilteredQuery($request)
            ->orderBy('nama_guru')
            ->get();

        $rows = $records->map(function ($g) {
            $mapelNames = $g->mapel->pluck('nama_mapel')->join(', ');

            return [
                $g->nip ?? '-',
                $g->nama_guru,
                $mapelNames ?: '-',
                $g->no_hp ?? '-',
                optional($g->user)->username ?? '-',
                optional($g->user)->role_label ?? 'Belum Punya Akun',
            ];
        });

        $filename = 'data-guru-' . Carbon::now('Asia/Jakarta')->format('Y-m-d') . '.csv';

        return CsvExporter::download($filename, [
            'NIP',
            'Nama Guru',
            'Mapel Diampu',
            'No Telp',
            'Username',
            'Status Akun',
        ], $rows);
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = Guru::with(['mapel', 'user']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nip', 'like', "%{$search}%")
                  ->orWhere('nama_guru', 'like', "%{$search}%");
            });
        }

        if ($request->filled('id_mapel')) {
            $idMapel = $request->input('id_mapel');
            $query->whereHas('mapel', function ($q) use ($idMapel) {
                $q->where('mapel.id_mapel', $idMapel);
            });
        }

        return $query;
    }
}
