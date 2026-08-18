<?php

namespace App\Http\Controllers;

use App\Models\JamPelajaran;
use App\Models\PengaturanJamSekolah;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JamPelajaranController extends Controller
{
    /**
     * Display Master Data Jam Pelajaran & Settings.
     */
    public function index(Request $request)
    {
        $settings = PengaturanJamSekolah::all()->keyBy('hari_kategori');

        // Defaults if missing
        if (!isset($settings['Senin-Kamis'])) {
            $settings['Senin-Kamis'] = PengaturanJamSekolah::create([
                'hari_kategori'  => 'Senin-Kamis',
                'durasi_per_jam' => 40,
                'jam_masuk'      => '07:00:00',
                'jam_pulang'     => '14:30:00',
                'keterangan'     => 'Hari Reguler (1 Jam = 40 Menit)',
            ]);
        }
        if (!isset($settings['Jumat'])) {
            $settings['Jumat'] = PengaturanJamSekolah::create([
                'hari_kategori'  => 'Jumat',
                'durasi_per_jam' => 30,
                'jam_masuk'      => '07:00:00',
                'jam_pulang'     => '11:30:00',
                'keterangan'     => 'Hari Singkat / Sholat Jumat (1 Jam = 30 Menit)',
            ]);
        }

        $activeTab = $request->input('tab', 'Senin-Kamis');

        // Always sanitize and recalculate sequential times on load
        $this->recalculateTimesForCategory($activeTab);

        $jamList = JamPelajaran::where('hari_kategori', $activeTab)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'settings' => $settings,
                'jam_list' => $jamList,
            ]);
        }

        return view('admin.jam.index', compact('settings', 'activeTab', 'jamList'));
    }

    /**
     * Update School Time Settings & Auto-Generate Slots.
     */
    public function updateSettings(Request $request)
    {
        return $this->generateSlots($request);
    }

    /**
     * Auto Generate Time Slots for specified day category (Supports 2 Break Slots).
     */
    public function generateSlots(Request $request)
    {
        $validated = $request->validate([
            'hari_kategori'      => 'required|string',
            'durasi_per_jam'     => 'required|integer|min:15|max:120',
            'jam_masuk'          => 'required|date_format:H:i',
            'jam_pulang'         => 'required|date_format:H:i',
            'durasi_istirahat_1' => 'nullable|integer|min:0|max:60',
            'setelah_jam_ke_1'   => 'nullable|integer|min:1|max:10',
            'durasi_istirahat_2' => 'nullable|integer|min:0|max:60',
            'setelah_jam_ke_2'   => 'nullable|integer|min:1|max:10',
            'keterangan'         => 'nullable|string|max:255',
        ]);

        $hariKategori = $validated['hari_kategori'];
        $durasi = (int) $validated['durasi_per_jam'];

        $durasi1 = (int) ($validated['durasi_istirahat_1'] ?? ($hariKategori === 'Jumat' ? 15 : 20));
        $setelah1 = (int) ($validated['setelah_jam_ke_1'] ?? ($hariKategori === 'Jumat' ? 3 : 4));

        $durasi2 = (int) ($validated['durasi_istirahat_2'] ?? ($hariKategori === 'Jumat' ? 0 : 30));
        $setelah2 = (int) ($validated['setelah_jam_ke_2'] ?? 7);

        $start = Carbon::createFromFormat('H:i', $validated['jam_masuk']);
        $end   = Carbon::createFromFormat('H:i', $validated['jam_pulang']);

        // Clear existing slots for this category
        JamPelajaran::where('hari_kategori', $hariKategori)->delete();

        $jamKe = 1;
        $curr = $start->copy();
        $break1Done = false;
        $break2Done = false;

        while ($curr->lt($end)) {
            // Check Break 1
            if (!$break1Done && $jamKe == ($setelah1 + 1) && $durasi1 > 0) {
                $bStart = $curr->copy();
                $bEnd   = $curr->copy()->addMinutes($durasi1);
                if ($bEnd->lte($end)) {
                    JamPelajaran::create([
                        'hari_kategori'   => $hariKategori,
                        'jam_ke'          => 0,
                        'jam_mulai'       => $bStart->format('H:i:s'),
                        'jam_selesai'     => $bEnd->format('H:i:s'),
                        'is_istirahat'    => true,
                        'bisa_diisi_mapel'=> false,
                        'durasi_menit'    => $durasi1,
                        'keterangan'      => 'Istirahat 1',
                    ]);
                    $curr = $bEnd;
                    $break1Done = true;
                    continue;
                }
            }

            // Check Break 2
            if (!$break2Done && $jamKe == ($setelah2 + 1) && $durasi2 > 0) {
                $bStart = $curr->copy();
                $bEnd   = $curr->copy()->addMinutes($durasi2);
                if ($bEnd->lte($end)) {
                    JamPelajaran::create([
                        'hari_kategori'   => $hariKategori,
                        'jam_ke'          => 0,
                        'jam_mulai'       => $bStart->format('H:i:s'),
                        'jam_selesai'     => $bEnd->format('H:i:s'),
                        'is_istirahat'    => true,
                        'bisa_diisi_mapel'=> false,
                        'durasi_menit'    => $durasi2,
                        'keterangan'      => 'Istirahat 2 (Sholat/Makan)',
                    ]);
                    $curr = $bEnd;
                    $break2Done = true;
                    continue;
                }
            }

            $slotStart = $curr->copy();
            $slotEnd   = $curr->copy()->addMinutes($durasi);

            if ($slotEnd->gt($end)) break;

            JamPelajaran::create([
                'hari_kategori'   => $hariKategori,
                'jam_ke'          => $jamKe,
                'jam_mulai'       => $slotStart->format('H:i:s'),
                'jam_selesai'     => $slotEnd->format('H:i:s'),
                'is_istirahat'    => false,
                'bisa_diisi_mapel'=> true,
                'durasi_menit'    => $durasi,
                'keterangan'      => 'Jam ke-' . $jamKe,
            ]);

            $curr = $slotEnd;
            $jamKe++;
        }

        // Update settings record
        PengaturanJamSekolah::updateOrCreate(
            ['hari_kategori' => $hariKategori],
            [
                'durasi_per_jam' => $durasi,
                'jam_masuk'      => strlen($validated['jam_masuk']) == 5 ? $validated['jam_masuk'] . ':00' : $validated['jam_masuk'],
                'jam_pulang'     => strlen($validated['jam_pulang']) == 5 ? $validated['jam_pulang'] . ':00' : $validated['jam_pulang'],
                'keterangan'     => $validated['keterangan'] ?? null,
            ]
        );

        if ($request->ajax()) {
            return response()->json(['success' => 'Slot jam pelajaran & 2 jam istirahat berhasil digenerate otomatis.']);
        }

        return redirect()->route('jam.index', ['tab' => $hariKategori])
            ->with('success', 'Slot jam pelajaran & 2 jam istirahat berhasil digenerate otomatis.');
    }

    /**
     * Reorder list of slots via Drag & Drop & Recalculate times sequentially.
     */
    public function reorderSlots(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:jam_pelajaran,id_jam',
        ]);

        if (empty($validated['order'])) {
            return response()->json(['error' => 'Data urutan kosong'], 400);
        }

        $firstSlot = JamPelajaran::find($validated['order'][0]);
        $hariKategori = $firstSlot ? $firstSlot->hari_kategori : 'Senin-Kamis';

        $this->recalculateTimesForCategory($hariKategori, $validated['order']);

        return response()->json(['success' => 'Urutan slot jam berhasil diperbarui & waktu otomatis disesuaikan beruntun.']);
    }

    /**
     * Recalculate start and end times sequentially for a day category starting from jam_masuk.
     */
    public function recalculateTimesForCategory($hariKategori, ?array $orderedIds = null)
    {
        $setting = PengaturanJamSekolah::where('hari_kategori', $hariKategori)->first();
        $jamMasuk = $setting->jam_masuk ?? '07:00:00';
        $startFormatted = strlen($jamMasuk) == 5 ? $jamMasuk . ':00' : $jamMasuk;
        $currTime = Carbon::createFromFormat('H:i:s', $startFormatted);

        if ($orderedIds && count($orderedIds) > 0) {
            $slots = collect($orderedIds)->map(fn($id) => JamPelajaran::find($id))->filter();
        } else {
            $slots = JamPelajaran::where('hari_kategori', $hariKategori)
                ->orderBy('jam_mulai')
                ->get();
        }

        $jamKeCounter = 1;
        foreach ($slots as $slot) {
            $startStr = $currTime->format('H:i:s');
            $durasi = max(1, (int) ($slot->durasi_menit ?? 40));
            $end = $currTime->copy()->addMinutes($durasi);
            $endStr = $end->format('H:i:s');

            $dataToUpdate = [
                'jam_mulai'   => $startStr,
                'jam_selesai' => $endStr,
            ];

            if ($slot->is_istirahat) {
                $dataToUpdate['jam_ke'] = 0;
            } else {
                $dataToUpdate['jam_ke'] = $jamKeCounter;
                $jamKeCounter++;
            }

            $slot->update($dataToUpdate);
            $currTime = $end;
        }
    }

    /**
     * Store a custom slot.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari_kategori'   => 'required|string',
            'jam_ke'          => 'required|integer|min:0|max:20',
            'jam_mulai'       => 'required|date_format:H:i',
            'jam_selesai'     => 'required|date_format:H:i',
            'is_istirahat'    => 'nullable|boolean',
            'bisa_diisi_mapel'=> 'nullable|boolean',
            'berlaku_hari'    => 'nullable|string|max:100',
            'keterangan'      => 'nullable|string|max:255',
        ]);

        $start = Carbon::createFromFormat('H:i', $validated['jam_mulai']);
        $end   = Carbon::createFromFormat('H:i', $validated['jam_selesai']);
        $validated['durasi_menit'] = max(1, $start->diffInMinutes($end));

        $bisaDiisi = $request->boolean('bisa_diisi_mapel');
        $validated['bisa_diisi_mapel'] = $bisaDiisi;
        $validated['berlaku_hari'] = $this->normalizeBerlakuHari(
            $request->input('berlaku_hari'),
            $validated['hari_kategori']
        );
        // is_istirahat is ONLY true if jam_ke is 0 or explicitly set as istirahat.
        // Non-KBM slots (like Upacara/Apel/Pembiasaan) have is_istirahat = false and maintain their jam_ke number (e.g. Jam 1).
        $jamKeVal = isset($validated['jam_ke']) ? (int) $validated['jam_ke'] : 1;
        $validated['is_istirahat'] = $request->has('is_istirahat') ? $request->boolean('is_istirahat') : ($jamKeVal === 0);

        JamPelajaran::create($validated);
        $this->recalculateTimesForCategory($validated['hari_kategori']);

        if ($request->ajax()) {
            return response()->json(['success' => 'Jam pelajaran berhasil ditambahkan.']);
        }

        return redirect()->route('jam.index', ['tab' => $validated['hari_kategori']])
            ->with('success', 'Jam pelajaran berhasil ditambahkan.');
    }

    /**
     * Destroy a slot.
     */
    public function destroy(Request $request, $id)
    {
        $jam = JamPelajaran::find($id);
        if (!$jam) {
            $msg = 'Slot jam pelajaran tidak ditemukan.';
            return $request->ajax() ? response()->json(['error' => $msg], 404) : back()->with('error', $msg);
        }

        $tab = $jam->hari_kategori;

        // Disassociate any linked jadwal_pelajaran
        \App\Models\JadwalPelajaran::where('id_jam', $jam->id_jam)->update(['id_jam' => null]);

        $jam->delete();
        $this->recalculateTimesForCategory($tab);

        if ($request->ajax()) {
            return response()->json(['success' => 'Jam pelajaran berhasil dihapus.']);
        }

        return redirect()->route('jam.index', ['tab' => $tab])
            ->with('success', 'Jam pelajaran berhasil dihapus.');
    }

    /**
     * Update a slot (supports duration & break edits with cascading times).
     */
    public function update(Request $request, $id)
    {
        $jam = JamPelajaran::find($id);
        if (!$jam) {
            $msg = 'Slot jam pelajaran tidak ditemukan.';
            return $request->ajax() ? response()->json(['error' => $msg], 404) : back()->with('error', $msg);
        }

        $validated = $request->validate([
            'jam_ke'          => 'nullable|integer|min:0|max:20',
            'jam_mulai'       => 'required|date_format:H:i',
            'jam_selesai'     => 'required|date_format:H:i',
            'is_istirahat'    => 'nullable|boolean',
            'bisa_diisi_mapel'=> 'nullable|boolean',
            'berlaku_hari'    => 'nullable|string|max:100',
            'keterangan'      => 'nullable|string|max:255',
        ]);

        $start = Carbon::createFromFormat('H:i', substr($validated['jam_mulai'], 0, 5));
        $end   = Carbon::createFromFormat('H:i', substr($validated['jam_selesai'], 0, 5));
        $validated['durasi_menit'] = max(1, $start->diffInMinutes($end));
        $jamKeVal = isset($validated['jam_ke']) ? (int) $validated['jam_ke'] : $jam->jam_ke;

        $bisaDiisi = $request->boolean('bisa_diisi_mapel');
        $validated['bisa_diisi_mapel'] = $bisaDiisi;
        $validated['berlaku_hari'] = $this->normalizeBerlakuHari(
            $request->input('berlaku_hari'),
            $jam->hari_kategori
        );
        // is_istirahat is ONLY true if jam_ke is 0 or explicitly set as istirahat.
        // Non-KBM slots (like Upacara/Apel/Pembiasaan) are NOT istirahat, so they keep their jam_ke number.
        $validated['is_istirahat'] = $request->has('is_istirahat') ? $request->boolean('is_istirahat') : ($jamKeVal === 0);

        $jam->update($validated);
        $this->recalculateTimesForCategory($jam->hari_kategori);

        if ($request->ajax()) {
            return response()->json(['success' => 'Slot jam pelajaran berhasil diperbarui & waktu beruntun disesuaikan.']);
        }

        return redirect()->route('jam.index', ['tab' => $jam->hari_kategori])
            ->with('success', 'Slot jam pelajaran berhasil diperbarui & waktu beruntun disesuaikan.');
    }

    /**
     * Normalize berlaku_hari based on hari_kategori tab rules.
     */
    private function normalizeBerlakuHari(?string $berlakuHari, string $hariKategori): ?string
    {
        if (!$berlakuHari || $berlakuHari === 'Semua Hari') {
            return null;
        }

        if ($hariKategori === 'Jumat') {
            return null;
        }

        $allowed = ['Senin', 'Selasa', 'Rabu', 'Kamis'];

        return in_array($berlakuHari, $allowed, true) ? $berlakuHari : null;
    }
}
