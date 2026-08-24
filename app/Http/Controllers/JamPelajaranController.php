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
                'hari_kategori'      => 'Senin-Kamis',
                'durasi_per_jam'     => 40,
                'jam_masuk'          => '07:00:00',
                'jam_pulang'         => '14:30:00',
                'durasi_istirahat_1' => 20,
                'setelah_jam_ke_1'   => 4,
                'durasi_istirahat_2' => 30,
                'setelah_jam_ke_2'   => 7,
                'keterangan'         => 'Hari Reguler (1 Jam = 40 Menit)',
            ]);
        }
        if (!isset($settings['Jumat'])) {
            $settings['Jumat'] = PengaturanJamSekolah::create([
                'hari_kategori'      => 'Jumat',
                'durasi_per_jam'     => 30,
                'jam_masuk'          => '07:00:00',
                'jam_pulang'         => '11:30:00',
                'durasi_istirahat_1' => 15,
                'setelah_jam_ke_1'   => 3,
                'durasi_istirahat_2' => 0,
                'setelah_jam_ke_2'   => null,
                'keterangan'         => 'Hari Singkat / Sholat Jumat (1 Jam = 30 Menit)',
            ]);
        }

        $activeTab = $request->input('tab', 'Senin-Kamis');

        // Always sanitize and recalculate sequential times on load
        $this->recalculateTimesForCategory($activeTab);

        $jamList = JamPelajaran::where('hari_kategori', $activeTab)
            ->orderBy('jam_mulai', 'asc')
            ->get();

        $curSetting = $settings[$activeTab] ?? null;

        $detectedSetting = [
            'jam_masuk'               => $curSetting->jam_masuk ? substr($curSetting->jam_masuk, 0, 5) : '07:00',
            'jam_pulang'              => $curSetting->jam_pulang ? substr($curSetting->jam_pulang, 0, 5) : '14:30',
            'mode_durasi_kbm'         => $curSetting->mode_durasi_kbm ?? 'seragam',
            'durasi_per_jam'          => $curSetting->durasi_per_jam ?? 40,
            'durasi_jam_utama'        => $curSetting->durasi_jam_utama ?? 40,
            'sampai_jam_ke'           => $curSetting->sampai_jam_ke ?? 4,
            'durasi_jam_setelahnya'   => $curSetting->durasi_jam_setelahnya ?? 35,
            'mode_istirahat_1'        => $curSetting->mode_istirahat_1 ?? 'durasi',
            'durasi_istirahat_1'      => $curSetting->durasi_istirahat_1 ?? null,
            'setelah_jam_ke_1'        => $curSetting->setelah_jam_ke_1 ?? null,
            'jam_mulai_istirahat_1'   => $curSetting->jam_mulai_istirahat_1 ? substr($curSetting->jam_mulai_istirahat_1, 0, 5) : null,
            'jam_selesai_istirahat_1' => $curSetting->jam_selesai_istirahat_1 ? substr($curSetting->jam_selesai_istirahat_1, 0, 5) : null,
            'mode_istirahat_2'        => $curSetting->mode_istirahat_2 ?? 'durasi',
            'durasi_istirahat_2'      => $curSetting->durasi_istirahat_2 ?? null,
            'setelah_jam_ke_2'        => $curSetting->setelah_jam_ke_2 ?? null,
            'jam_mulai_istirahat_2'   => $curSetting->jam_mulai_istirahat_2 ? substr($curSetting->jam_mulai_istirahat_2, 0, 5) : null,
            'jam_selesai_istirahat_2' => $curSetting->jam_selesai_istirahat_2 ? substr($curSetting->jam_selesai_istirahat_2, 0, 5) : null,
        ];

        if ($jamList->count() > 0) {
            $firstSlot = $jamList->first();
            $lastSlot  = $jamList->last();
            $detectedSetting['jam_masuk']  = substr($firstSlot->jam_mulai, 0, 5);
            $detectedSetting['jam_pulang'] = substr($lastSlot->jam_selesai, 0, 5);

            $kbmSlots = $jamList->where('is_istirahat', false)->values();
            if ($kbmSlots->count() > 0) {
                $firstKbmDur = $kbmSlots->first()->durasi_menit;
                $detectedSetting['durasi_per_jam']   = $firstKbmDur;
                $detectedSetting['durasi_jam_utama'] = $firstKbmDur;

                $lastKbmDur = $kbmSlots->last()->durasi_menit;
                if ($lastKbmDur != $firstKbmDur) {
                    $detectedSetting['mode_durasi_kbm']       = 'variatif';
                    $detectedSetting['durasi_jam_setelahnya'] = $lastKbmDur;
                    $diffSlot = $kbmSlots->first(fn($s) => $s->durasi_menit != $firstKbmDur);
                    if ($diffSlot) {
                        $diffIdx = $kbmSlots->search(fn($s) => $s->id_jam == $diffSlot->id_jam);
                        $detectedSetting['sampai_jam_ke'] = $diffIdx > 0 ? $diffIdx : 4;
                    }
                }
            }

            $breakSlots = $jamList->where('is_istirahat', true)->values();
            if ($breakSlots->count() > 0) {
                $b1 = $breakSlots->get(0);
                $detectedSetting['durasi_istirahat_1']      = $b1->durasi_menit;
                $detectedSetting['jam_mulai_istirahat_1']   = substr($b1->jam_mulai, 0, 5);
                $detectedSetting['jam_selesai_istirahat_1'] = substr($b1->jam_selesai, 0, 5);
                
                $prevKbm = $jamList->filter(fn($s) => !$s->is_istirahat && $s->jam_selesai <= $b1->jam_mulai)->last();
                if ($prevKbm) {
                    $detectedSetting['setelah_jam_ke_1'] = $prevKbm->jam_ke;
                }
            }

            if ($breakSlots->count() > 1) {
                $b2 = $breakSlots->get(1);
                $detectedSetting['durasi_istirahat_2']      = $b2->durasi_menit;
                $detectedSetting['jam_mulai_istirahat_2']   = substr($b2->jam_mulai, 0, 5);
                $detectedSetting['jam_selesai_istirahat_2'] = substr($b2->jam_selesai, 0, 5);
                
                $prevKbm2 = $jamList->filter(fn($s) => !$s->is_istirahat && $s->jam_selesai <= $b2->jam_mulai)->last();
                if ($prevKbm2) {
                    $detectedSetting['setelah_jam_ke_2'] = $prevKbm2->jam_ke;
                }
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'settings'         => $settings,
                'detected_setting' => $detectedSetting,
                'jam_list'         => $jamList,
            ]);
        }

        return view('admin.jam.index', compact('settings', 'activeTab', 'jamList', 'detectedSetting'));
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
        // Smart Auto-Correction for Browser AM/PM time mixups
        $inputMasuk  = $request->input('jam_masuk');
        $inputPulang = $request->input('jam_pulang');
        $inputStart1 = $request->input('jam_mulai_istirahat_1');
        $inputEnd1   = $request->input('jam_selesai_istirahat_1');
        $inputStart2 = $request->input('jam_mulai_istirahat_2');
        $inputEnd2   = $request->input('jam_selesai_istirahat_2');

        // Fix jam_pulang: 01:00-06:00 -> 13:00-18:00
        if (!empty($inputPulang) && strlen($inputPulang) == 5) {
            $hP = (int) substr($inputPulang, 0, 2);
            if ($hP >= 1 && $hP <= 6) {
                $inputPulang = sprintf('%02d:%s', $hP + 12, substr($inputPulang, 3, 2));
            }
        }

        // Fix Istirahat 1 & 2 start/end times if browser sent PM (>=18) or 12-hour afternoon hours (1-5)
        $fixBreakPair = function(&$start, &$end) {
            if (empty($start) || empty($end)) return;
            $hS = (int) substr($start, 0, 2);
            $mS = (int) substr($start, 3, 2);
            $hE = (int) substr($end, 0, 2);
            $mE = (int) substr($end, 3, 2);

            // If start is 18..23 (e.g. 23:45 for 11:45 PM), change to 11:45
            if ($hS >= 18) {
                $hS -= 12;
            }
            // If end is 01..05 (e.g. 01:15 for 01:15 PM), change to 13:15
            if ($hE >= 1 && $hE <= 6) {
                $hE += 12;
            }
            // If both start and end are 01..06, e.g. 01:15 - 01:45 (1:15 PM - 1:45 PM)
            if ($hS >= 1 && $hS <= 6 && $hE >= $hS && $hE <= 6) {
                $hS += 12;
                $hE += 12;
            }

            $start = sprintf('%02d:%02d', $hS, $mS);
            $end   = sprintf('%02d:%02d', $hE, $mE);
        };

        $fixBreakPair($inputStart1, $inputEnd1);
        $fixBreakPair($inputStart2, $inputEnd2);

        $request->merge([
            'jam_pulang'              => $inputPulang,
            'jam_mulai_istirahat_1'   => $inputStart1,
            'jam_selesai_istirahat_1' => $inputEnd1,
            'jam_mulai_istirahat_2'   => $inputStart2,
            'jam_selesai_istirahat_2' => $inputEnd2,
        ]);

        $rules = [
            'hari_kategori'           => 'required|string',
            'jam_masuk'               => 'required|date_format:H:i',
            'jam_pulang'              => 'required|date_format:H:i',
            'mode_durasi_kbm'         => 'nullable|string|in:seragam,variatif',
            'durasi_per_jam'          => 'required_if:mode_durasi_kbm,seragam|nullable|integer|min:15|max:120',
            'durasi_jam_utama'        => 'required_if:mode_durasi_kbm,variatif|nullable|integer|min:15|max:120',
            'sampai_jam_ke'           => 'required_if:mode_durasi_kbm,variatif|nullable|integer|min:1|max:10',
            'durasi_jam_setelahnya'   => 'required_if:mode_durasi_kbm,variatif|nullable|integer|min:15|max:120',
            'mode_istirahat_1'        => 'nullable|string|in:durasi,pukul',
            'durasi_istirahat_1'      => 'required_if:mode_istirahat_1,durasi|nullable|integer|min:0|max:60',
            'setelah_jam_ke_1'        => 'required_if:mode_istirahat_1,durasi|nullable|integer|min:1|max:10',
            'jam_mulai_istirahat_1'   => 'required_if:mode_istirahat_1,pukul|nullable|date_format:H:i',
            'jam_selesai_istirahat_1' => 'required_if:mode_istirahat_1,pukul|nullable|date_format:H:i|after:jam_mulai_istirahat_1',
            'mode_istirahat_2'        => 'nullable|string|in:durasi,pukul',
            'durasi_istirahat_2'      => 'nullable|integer|min:0|max:60',
            'setelah_jam_ke_2'        => 'nullable|integer|min:1|max:10',
            'jam_mulai_istirahat_2'   => 'nullable|date_format:H:i',
            'jam_selesai_istirahat_2' => 'nullable|date_format:H:i|after:jam_mulai_istirahat_2',
            'keterangan'              => 'nullable|string|max:255',
        ];

        $messages = [
            'durasi_per_jam.required_if'          => 'Durasi jam pelajaran KBM seragam wajib diisi!',
            'durasi_jam_utama.required_if'        => 'Durasi jam KBM utama (Jam 1 s/d X) wajib diisi!',
            'sampai_jam_ke.required_if'           => 'Batas jam KBM utama (sampai jam ke-) wajib diisi!',
            'durasi_jam_setelahnya.required_if'   => 'Durasi jam KBM setelahnya wajib diisi!',
            'jam_masuk.required'                  => 'Jam masuk sekolah wajib diisi!',
            'jam_pulang.required'                 => 'Jam pulang sekolah wajib diisi!',
            'durasi_istirahat_1.required_if'      => 'Durasi Jam Istirahat 1 wajib diisi!',
            'setelah_jam_ke_1.required_if'        => 'Posisi Istirahat 1 (Setelah Jam Ke-) wajib diisi!',
            'jam_mulai_istirahat_1.required_if'   => 'Jam mulai Istirahat 1 wajib diisi!',
            'jam_selesai_istirahat_1.required_if' => 'Jam selesai Istirahat 1 wajib diisi!',
            'jam_selesai_istirahat_1.after'       => 'Jam selesai Istirahat 1 harus lebih akhir dari Jam Mulai!',
            'jam_selesai_istirahat_2.after'       => 'Jam selesai Istirahat 2 harus lebih akhir dari Jam Mulai!',
        ];

        $validated = $request->validate($rules, $messages);

        $hariKategori = $validated['hari_kategori'];
        $modeKbm      = $validated['mode_durasi_kbm'] ?? 'seragam';
        $durasiPerJam = isset($validated['durasi_per_jam']) && $validated['durasi_per_jam'] !== '' ? (int) $validated['durasi_per_jam'] : 40;
        $durasiUtama  = isset($validated['durasi_jam_utama']) && $validated['durasi_jam_utama'] !== '' ? (int) $validated['durasi_jam_utama'] : $durasiPerJam;
        $sampaiJamKe  = isset($validated['sampai_jam_ke']) && $validated['sampai_jam_ke'] !== '' ? (int) $validated['sampai_jam_ke'] : 4;
        $durasiLanjut = isset($validated['durasi_jam_setelahnya']) && $validated['durasi_jam_setelahnya'] !== '' ? (int) $validated['durasi_jam_setelahnya'] : 35;

        $mode1   = $validated['mode_istirahat_1'] ?? 'durasi';
        $durasi1 = 0;
        $setelah1 = null;
        $bStart1Obj = null;
        $bEnd1Obj   = null;

        if ($mode1 === 'pukul' && !empty($validated['jam_mulai_istirahat_1']) && !empty($validated['jam_selesai_istirahat_1'])) {
            $bStart1Obj = Carbon::createFromFormat('H:i', $validated['jam_mulai_istirahat_1']);
            $bEnd1Obj   = Carbon::createFromFormat('H:i', $validated['jam_selesai_istirahat_1']);
            if ($bEnd1Obj->gt($bStart1Obj)) {
                $durasi1 = max(0, $bStart1Obj->diffInMinutes($bEnd1Obj));
            } else {
                $durasi1 = 0;
                $bStart1Obj = null;
                $bEnd1Obj = null;
            }
        } else {
            $durasi1  = isset($validated['durasi_istirahat_1']) && $validated['durasi_istirahat_1'] !== null ? (int) $validated['durasi_istirahat_1'] : 0;
            $setelah1 = isset($validated['setelah_jam_ke_1']) && $validated['setelah_jam_ke_1'] !== null ? (int) $validated['setelah_jam_ke_1'] : null;
        }

        $mode2   = $validated['mode_istirahat_2'] ?? 'durasi';
        $durasi2 = 0;
        $setelah2 = null;
        $bStart2Obj = null;
        $bEnd2Obj   = null;

        if ($mode2 === 'pukul' && !empty($validated['jam_mulai_istirahat_2']) && !empty($validated['jam_selesai_istirahat_2'])) {
            $bStart2Obj = Carbon::createFromFormat('H:i', $validated['jam_mulai_istirahat_2']);
            $bEnd2Obj   = Carbon::createFromFormat('H:i', $validated['jam_selesai_istirahat_2']);
            if ($bEnd2Obj->gt($bStart2Obj)) {
                $durasi2 = max(0, $bStart2Obj->diffInMinutes($bEnd2Obj));
            } else {
                $durasi2 = 0;
                $bStart2Obj = null;
                $bEnd2Obj = null;
            }
        } else {
            $durasi2  = isset($validated['durasi_istirahat_2']) && $validated['durasi_istirahat_2'] !== null ? (int) $validated['durasi_istirahat_2'] : 0;
            $setelah2 = isset($validated['setelah_jam_ke_2']) && $validated['setelah_jam_ke_2'] !== null ? (int) $validated['setelah_jam_ke_2'] : null;
        }

        $start = Carbon::createFromFormat('H:i', $validated['jam_masuk']);
        $end   = Carbon::createFromFormat('H:i', $validated['jam_pulang']);

        // Clear existing slots for this category
        JamPelajaran::where('hari_kategori', $hariKategori)->delete();

        $jamKe = 1;
        $curr = $start->copy();
        $break1Done = false;
        $break2Done = false;

        while ($curr->lt($end)) {
            // Check Break 1 (Durasi or Pukul)
            $isBreak1Time = false;
            if (!$break1Done && $durasi1 > 0) {
                if ($mode1 === 'pukul' && $bStart1Obj && $curr->format('H:i') >= $bStart1Obj->format('H:i')) {
                    $isBreak1Time = true;
                } elseif ($mode1 === 'durasi' && $setelah1 !== null && $jamKe == ($setelah1 + 1)) {
                    $isBreak1Time = true;
                }
            }

            if ($isBreak1Time) {
                $bStart = $mode1 === 'pukul' && $bStart1Obj ? $bStart1Obj->copy() : $curr->copy();
                $bEnd   = $mode1 === 'pukul' && $bEnd1Obj ? $bEnd1Obj->copy() : $curr->copy()->addMinutes($durasi1);
                if ($bEnd->gt($end) && $bStart->lt($end)) {
                    $bEnd = $end->copy();
                }
                if ($bEnd->lte($end) && $bStart->lt($bEnd)) {
                    $durasiBreakActual = max(1, (int) $bStart->diffInMinutes($bEnd));
                    JamPelajaran::create([
                        'hari_kategori'   => $hariKategori,
                        'jam_ke'          => 0,
                        'jam_mulai'       => $bStart->format('H:i:s'),
                        'jam_selesai'     => $bEnd->format('H:i:s'),
                        'is_istirahat'    => true,
                        'bisa_diisi_mapel'=> false,
                        'durasi_menit'    => $durasiBreakActual,
                        'keterangan'      => 'Istirahat 1',
                    ]);
                    $curr = $bEnd;
                    $break1Done = true;
                    continue;
                }
            }

            // Check Break 2 (Durasi or Pukul)
            $isBreak2Time = false;
            if (!$break2Done && $durasi2 > 0) {
                if ($mode2 === 'pukul' && $bStart2Obj && $curr->format('H:i') >= $bStart2Obj->format('H:i')) {
                    $isBreak2Time = true;
                } elseif ($mode2 === 'durasi' && $setelah2 !== null && $jamKe == ($setelah2 + 1)) {
                    $isBreak2Time = true;
                }
            }

            if ($isBreak2Time) {
                $bStart = $mode2 === 'pukul' && $bStart2Obj ? $bStart2Obj->copy() : $curr->copy();
                $bEnd   = $mode2 === 'pukul' && $bEnd2Obj ? $bEnd2Obj->copy() : $curr->copy()->addMinutes($durasi2);
                if ($bEnd->gt($end) && $bStart->lt($end)) {
                    $bEnd = $end->copy();
                }
                if ($bEnd->lte($end) && $bStart->lt($bEnd)) {
                    $durasiBreakActual = max(1, (int) $bStart->diffInMinutes($bEnd));
                    JamPelajaran::create([
                        'hari_kategori'   => $hariKategori,
                        'jam_ke'          => 0,
                        'jam_mulai'       => $bStart->format('H:i:s'),
                        'jam_selesai'     => $bEnd->format('H:i:s'),
                        'is_istirahat'    => true,
                        'bisa_diisi_mapel'=> false,
                        'durasi_menit'    => $durasiBreakActual,
                        'keterangan'      => 'Istirahat 2 (Sholat/Makan)',
                    ]);
                    $curr = $bEnd;
                    $break2Done = true;
                    continue;
                }
            }

            // Calculate KBM slot duration depending on mode
            $slotDurasi = ($modeKbm === 'variatif')
                ? ($jamKe <= $sampaiJamKe ? $durasiUtama : $durasiLanjut)
                : $durasiPerJam;

            // If Break 1 or Break 2 in Pukul mode starts before this slot would end, truncate slot duration up to break start
            if (!$break1Done && $mode1 === 'pukul' && $bStart1Obj && $curr->lt($bStart1Obj) && $curr->copy()->addMinutes($slotDurasi)->gt($bStart1Obj)) {
                $slotDurasi = max(10, (int) $curr->diffInMinutes($bStart1Obj));
            }
            if (!$break2Done && $mode2 === 'pukul' && $bStart2Obj && $curr->lt($bStart2Obj) && $curr->copy()->addMinutes($slotDurasi)->gt($bStart2Obj)) {
                $slotDurasi = max(10, (int) $curr->diffInMinutes($bStart2Obj));
            }

            $slotStart = $curr->copy();
            $slotEnd   = $curr->copy()->addMinutes($slotDurasi);

            if ($slotEnd->gt($end)) {
                // If remaining time before jam_pulang is at least 10 minutes, create adjusted final slot up to jam_pulang
                $sisaMenit = (int) $curr->diffInMinutes($end);
                if ($sisaMenit >= 10) {
                    JamPelajaran::create([
                        'hari_kategori'   => $hariKategori,
                        'jam_ke'          => $jamKe,
                        'jam_mulai'       => $slotStart->format('H:i:s'),
                        'jam_selesai'     => $end->format('H:i:s'),
                        'is_istirahat'    => false,
                        'bisa_diisi_mapel'=> true,
                        'durasi_menit'    => $sisaMenit,
                        'keterangan'      => 'Jam ke-' . $jamKe,
                    ]);
                }
                break;
            }

            JamPelajaran::create([
                'hari_kategori'   => $hariKategori,
                'jam_ke'          => $jamKe,
                'jam_mulai'       => $slotStart->format('H:i:s'),
                'jam_selesai'     => $slotEnd->format('H:i:s'),
                'is_istirahat'    => false,
                'bisa_diisi_mapel'=> true,
                'durasi_menit'    => $slotDurasi,
                'keterangan'      => 'Jam ke-' . $jamKe,
            ]);

            $curr = $slotEnd;
            $jamKe++;
        }

        // Update settings record
        PengaturanJamSekolah::updateOrCreate(
            ['hari_kategori' => $hariKategori],
            [
                'mode_durasi_kbm'         => $modeKbm,
                'durasi_per_jam'          => $durasiPerJam,
                'durasi_jam_utama'        => $validated['durasi_jam_utama'] ?? null,
                'sampai_jam_ke'           => $validated['sampai_jam_ke'] ?? null,
                'durasi_jam_setelahnya'   => $validated['durasi_jam_setelahnya'] ?? null,
                'jam_masuk'               => strlen($validated['jam_masuk']) == 5 ? $validated['jam_masuk'] . ':00' : $validated['jam_masuk'],
                'jam_pulang'              => strlen($validated['jam_pulang']) == 5 ? $validated['jam_pulang'] . ':00' : $validated['jam_pulang'],
                'mode_istirahat_1'        => $mode1,
                'durasi_istirahat_1'      => isset($validated['durasi_istirahat_1']) && $validated['durasi_istirahat_1'] !== '' ? (int) $validated['durasi_istirahat_1'] : null,
                'setelah_jam_ke_1'        => isset($validated['setelah_jam_ke_1']) && $validated['setelah_jam_ke_1'] !== '' ? (int) $validated['setelah_jam_ke_1'] : null,
                'jam_mulai_istirahat_1'   => $validated['jam_mulai_istirahat_1'] ?? null,
                'jam_selesai_istirahat_1' => $validated['jam_selesai_istirahat_1'] ?? null,
                'mode_istirahat_2'        => $mode2,
                'durasi_istirahat_2'      => isset($validated['durasi_istirahat_2']) && $validated['durasi_istirahat_2'] !== '' ? (int) $validated['durasi_istirahat_2'] : null,
                'setelah_jam_ke_2'        => isset($validated['setelah_jam_ke_2']) && $validated['setelah_jam_ke_2'] !== '' ? (int) $validated['setelah_jam_ke_2'] : null,
                'jam_mulai_istirahat_2'   => $validated['jam_mulai_istirahat_2'] ?? null,
                'jam_selesai_istirahat_2' => $validated['jam_selesai_istirahat_2'] ?? null,
                'keterangan'              => $validated['keterangan'] ?? null,
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
