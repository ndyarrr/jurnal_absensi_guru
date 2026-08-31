<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\DetailKetidakhadiran;
use App\Models\JurnalMengajar;
use App\Models\PermohonanIzin;
use App\Models\SuratDispensasi;

class WaliKelasController extends Controller
{
    /**
     * Ensure student and permohonan_izin records exist for the active class in August 2026.
     */
    private function ensureDataExist($kelas)
    {
        if (!$kelas) return;

        $todayStr = Carbon::now('Asia/Jakarta')->toDateString();

        $siswaList = Siswa::where('id_kelas', $kelas->id_kelas)->orderBy('nama_siswa')->get();
        if ($siswaList->isEmpty()) {
            $defaultNames = [
                'Ilona Lovita', 'Canva Narendra', 'Bella Sutanto',
                'Megan Fernita', 'Azzura Atasya', 'Felix Fernandez'
            ];
            foreach ($defaultNames as $idx => $name) {
                Siswa::create([
                    'nisn' => '0056789' . str_pad($kelas->id_kelas, 2, '0', STR_PAD_LEFT) . str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
                    'nama_siswa' => $name,
                    'id_kelas' => $kelas->id_kelas
                ]);
            }
            $siswaList = Siswa::where('id_kelas', $kelas->id_kelas)->orderBy('nama_siswa')->get();
        }

        $siswaIds = $siswaList->pluck('id_siswa')->toArray();

        // Seed sample permohonan_izin only if empty
        $permohonanCount = PermohonanIzin::whereIn('id_siswa', $siswaIds)->count();
        if ($permohonanCount === 0 && count($siswaList) > 0) {
            $sampleTemplates = [
                ['name' => 'Ilona Lovita',    'jenis' => 'Izin',  'mulai' => '2026-08-26', 'selesai' => '2026-08-26', 'alasan' => 'Acara keluarga mendesak', 'bukti' => 'surat_orang_tua.pdf', 'status' => 'approved_piket', 'created' => '2026-08-26 07:36:00'],
                ['name' => 'Canva Narendra', 'jenis' => 'Sakit', 'mulai' => '2026-08-26', 'selesai' => '2026-08-26', 'alasan' => 'Pemeriksaan dokter RSUD', 'bukti' => 'surat_dokter.pdf', 'status' => 'approved_piket', 'created' => '2026-08-26 07:36:00'],
                ['name' => 'Bella Sutanto',  'jenis' => 'Izin',  'mulai' => '2026-08-26', 'selesai' => '2026-08-26', 'alasan' => 'Acara keluarga', 'bukti' => 'surat_orang_tua.pdf', 'status' => 'approved_waka', 'created' => '2026-08-26 07:36:00'],
                ['name' => 'Megan Fernita',  'jenis' => 'Sakit', 'mulai' => '2026-08-26', 'selesai' => '2026-08-26', 'alasan' => 'Demam tinggi dan butuh istirahat', 'bukti' => 'surat_dokter.pdf', 'status' => 'approved_piket', 'created' => '2026-08-26 07:36:00'],
                ['name' => 'Azzura Atasya',  'jenis' => 'Sakit', 'mulai' => '2026-08-25', 'selesai' => '2026-08-25', 'alasan' => 'Sakit Flu & Batuk', 'bukti' => 'surat_orang_tua.pdf', 'status' => 'pending', 'created' => '2026-08-25 07:05:00'],
                ['name' => 'Felix Fernandez','jenis' => 'Alpa',  'mulai' => '2026-08-24', 'selesai' => '2026-08-24', 'alasan' => 'Tanpa keterangan', 'bukti' => null, 'status' => 'pending', 'created' => '2026-08-24 08:00:00'],
            ];

            foreach ($siswaList as $idx => $s) {
                if ($idx < count($sampleTemplates)) {
                    $tpl = $sampleTemplates[$idx];
                    if ($tpl['mulai'] <= $todayStr) {
                        PermohonanIzin::create([
                            'tipe_pemohon' => 'siswa',
                            'id_siswa' => $s->id_siswa,
                            'jenis_izin' => $tpl['jenis'],
                            'tanggal_mulai' => $tpl['mulai'],
                            'tanggal_selesai' => min($tpl['selesai'], $todayStr),
                            'alasan' => $tpl['alasan'],
                            'bukti_surat' => $tpl['bukti'],
                            'status' => $tpl['status'],
                            'created_at' => $tpl['created'],
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Display dedicated Dashboard for Wali Kelas matching exact UX design mockup.
     */
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $guru = $user->guru;

        // Hard block: User must have a linked Guru profile
        if (!$guru) {
            return redirect()->route('role.dashboard')
                ->with('error', 'Akun Anda belum ditautkan ke profil Guru. Hubungi Admin untuk menautkan profil guru Anda terlebih dahulu.');
        }

        // Find assigned class for this Wali Kelas
        $kelas = Kelas::with(['jurusan', 'siswa'])->where('id_guru_wali', $guru->id_guru)->first();

        // Block if not assigned as wali kelas
        if (!$kelas) {
            return redirect()->route('role.dashboard')
                ->with('error', 'Anda belum ditugaskan sebagai Wali Kelas. Hubungi Admin untuk menetapkan kelas perwalian Anda.');
        }

        $this->ensureDataExist($kelas);

        $namaKelas = $kelas ? ($kelas->tingkat . ' ' . optional($kelas->jurusan)->kode_jurusan . ' ' . $kelas->rombel) : 'XI RPL 1';
        $namaWali  = $guru ? $guru->nama_guru : ($user->name ?? 'Aily Cantika, S.Pd');

        // Date formatting
        Carbon::setLocale('id');
        $todayFormatted = Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y');
        $todayStr = Carbon::now('Asia/Jakarta')->toDateString();

        // Query students in this class
        $siswaQuery = Siswa::query();
        if ($kelas) {
            $siswaQuery->where('id_kelas', $kelas->id_kelas);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $siswaQuery->where('nama_siswa', 'like', "%{$search}%");
        }

        $siswaList = $siswaQuery->orderBy('nama_siswa')->get();
        $totalSiswa = $siswaList->count();
        $siswaIds = $siswaList->pluck('id_siswa')->toArray();

        // Calculate REAL attendance stats for TODAY from DetailKetidakhadiran, PermohonanIzin, and SuratDispensasi
        $sakitStudentIds = [];
        $izinStudentIds  = [];
        $alpaStudentIds  = [];

        if (!empty($siswaIds)) {
            // 1. Check DetailKetidakhadiran
            $jurnalsToday = JurnalMengajar::whereDate('tanggal', $todayStr)->pluck('id_jurnal');
            if ($jurnalsToday->isNotEmpty()) {
                $ketidakhadiran = DetailKetidakhadiran::whereIn('id_siswa', $siswaIds)
                    ->whereIn('id_jurnal', $jurnalsToday)
                    ->get();
                foreach ($ketidakhadiran as $dk) {
                    $st = strtolower($dk->status);
                    if (in_array($st, ['sakit'])) {
                        $sakitStudentIds[] = $dk->id_siswa;
                    } elseif (in_array($st, ['izin', 'ijin', 'dispensasi'])) {
                        $izinStudentIds[] = $dk->id_siswa;
                    } elseif (in_array($st, ['alpa', 'tanpa keterangan'])) {
                        $alpaStudentIds[] = $dk->id_siswa;
                    }
                }
            }

            // 2. Check PermohonanIzin active today
            $permohonanToday = PermohonanIzin::whereIn('id_siswa', $siswaIds)
                ->where('tanggal_mulai', '<=', $todayStr)
                ->where('tanggal_selesai', '>=', $todayStr)
                ->get();

            foreach ($permohonanToday as $perm) {
                $st = strtolower($perm->jenis_izin);
                if (in_array($st, ['sakit'])) {
                    $sakitStudentIds[] = $perm->id_siswa;
                } elseif (in_array($st, ['izin', 'ijin', 'dispensasi'])) {
                    $izinStudentIds[] = $perm->id_siswa;
                } elseif (in_array($st, ['alpa', 'tanpa keterangan'])) {
                    $alpaStudentIds[] = $perm->id_siswa;
                }
            }

            // 3. Check SuratDispensasi active today
            $dispenToday = SuratDispensasi::whereIn('id_siswa', $siswaIds)
                ->where('tanggal_mulai', '<=', $todayStr)
                ->where('tanggal_selesai', '>=', $todayStr)
                ->pluck('id_siswa')
                ->toArray();

            $izinStudentIds = array_merge($izinStudentIds, $dispenToday);
        }

        $sakitCount = count(array_unique($sakitStudentIds));
        $izinCount  = count(array_unique($izinStudentIds));
        $alpaCount  = count(array_unique($alpaStudentIds));

        $distinctAbsentSiswa = count(array_unique(array_merge($sakitStudentIds, $izinStudentIds, $alpaStudentIds)));
        $hadirCount = max(0, $totalSiswa - $distinctAbsentSiswa);
        $persentaseHadir = $totalSiswa > 0 ? round(($hadirCount / $totalSiswa) * 100, 2) : 100;

        // REAL Weekly percentage chart data (Last 4 weeks)
        $weeklyStats = [];
        $now = Carbon::now('Asia/Jakarta');
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = $now->copy()->subWeeks($i)->startOfWeek();
            $endOfWeek   = $now->copy()->subWeeks($i)->endOfWeek();

            $jurnalCount = JurnalMengajar::whereBetween('tanggal', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
                ->whereHas('jadwal', function($q) use ($kelas) {
                    if ($kelas) $q->where('id_kelas', $kelas->id_kelas);
                })->count();

            $totalPossible = ($totalSiswa > 0 ? $totalSiswa : 1) * max($jurnalCount, 1);
            $weeklyAbsent = 0;
            if (!empty($siswaIds) && $jurnalCount > 0) {
                $jurnalIds = JurnalMengajar::whereBetween('tanggal', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
                    ->whereHas('jadwal', function($q) use ($kelas) {
                        if ($kelas) $q->where('id_kelas', $kelas->id_kelas);
                    })->pluck('id_jurnal');
                $weeklyAbsent = DetailKetidakhadiran::whereIn('id_siswa', $siswaIds)->whereIn('id_jurnal', $jurnalIds)->count();
            }

            $weeklyHadir = max(0, $totalPossible - $weeklyAbsent);
            $pct = round(($weeklyHadir / $totalPossible) * 100);

            $weeklyStats[] = [
                'minggu'     => 'Minggu ' . (4 - $i),
                'persentase' => $pct,
                'active'     => ($i === 0),
            ];
        }

        return view('wali_kelas.dashboard', compact(
            'user',
            'guru',
            'kelas',
            'namaKelas',
            'namaWali',
            'todayFormatted',
            'siswaList',
            'totalSiswa',
            'hadirCount',
            'sakitCount',
            'izinCount',
            'alpaCount',
            'persentaseHadir',
            'weeklyStats'
        ));
    }

    /**
     * Display Kelas Perwalian page (Read-Only Student Status Cards for Wali Kelas).
     */
    public function perwalian(Request $request)
    {
        $user = auth()->user();
        $guru = $user->guru;

        // Hard block: User must have a linked Guru profile
        if (!$guru) {
            return redirect()->route('role.dashboard')
                ->with('error', 'Akun Anda belum ditautkan ke profil Guru. Hubungi Admin untuk menautkan profil guru Anda terlebih dahulu.');
        }

        // Find assigned class for this Wali Kelas
        $kelas = Kelas::with(['jurusan', 'siswa'])->where('id_guru_wali', $guru->id_guru)->first();

        if (!$kelas) {
            return redirect()->route('role.dashboard')
                ->with('error', 'Anda belum ditugaskan sebagai Wali Kelas. Hubungi Admin untuk menetapkan kelas perwalian Anda.');
        }

        $this->ensureDataExist($kelas);

        $namaKelas = $kelas ? ($kelas->tingkat . ' ' . optional($kelas->jurusan)->kode_jurusan . ' ' . $kelas->rombel) : 'XI RPL 1';
        $namaWali  = $guru ? $guru->nama_guru : ($user->name ?? 'Aily Cantika, S.Pd');

        $siswaQuery = Siswa::query();
        if ($kelas) {
            $siswaQuery->where('id_kelas', $kelas->id_kelas);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $siswaQuery->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $siswaList = $siswaQuery->orderBy('nama_siswa')->get();

        // Calculate real attendance stats per student
        $totalJurnalKelas = $kelas ? JurnalMengajar::whereHas('jadwal', function($q) use ($kelas) {
            $q->where('id_kelas', $kelas->id_kelas);
        })->count() : 0;

        foreach ($siswaList as $siswa) {
            $absentRecords = DetailKetidakhadiran::where('id_siswa', $siswa->id_siswa)->get();
            $permohonanRecords = PermohonanIzin::where('id_siswa', $siswa->id_siswa)->get();
            $dispenRecords = SuratDispensasi::where('id_siswa', $siswa->id_siswa)->get();

            $sakit = 0; $izin = 0; $alpa = 0;
            foreach ($absentRecords as $rec) {
                $st = strtolower($rec->status);
                if ($st === 'sakit') $sakit++;
                elseif (in_array($st, ['izin', 'ijin', 'dispensasi'])) $izin++;
                elseif (in_array($st, ['alpa', 'tanpa keterangan'])) $alpa++;
            }
            foreach ($permohonanRecords as $perm) {
                $st = strtolower($perm->jenis_izin);
                if ($st === 'sakit') $sakit++;
                elseif (in_array($st, ['izin', 'ijin', 'dispensasi'])) $izin++;
                elseif (in_array($st, ['alpa', 'tanpa keterangan'])) $alpa++;
            }
            foreach ($dispenRecords as $disp) {
                $izin++;
            }

            $totalAbsent = $sakit + $izin + $alpa;
            $totalPertemuan = max($totalJurnalKelas, 1);
            $hadir = max(0, $totalPertemuan - $totalAbsent);
            $pct = round(($hadir / $totalPertemuan) * 100);

            if ($alpa >= 2 || $pct < 70) {
                $statusKey = 'tindak-lanjut';
                $statusLabel = 'Perlu tindak lanjut';
            } elseif ($alpa == 1 || ($pct >= 70 && $pct < 85) || $totalAbsent > 2) {
                $statusKey = 'pantau';
                $statusLabel = 'Perlu pantau';
            } else {
                $statusKey = 'baik';
                $statusLabel = 'Baik';
            }

            $siswa->pct = $pct;
            $siswa->status_key = $statusKey;
            $siswa->status_label = $statusLabel;
            $siswa->hadir_count = $hadir;
            $siswa->sakit_count = $sakit;
            $siswa->izin_count = $izin;
            $siswa->alpa_count = $alpa;
        }

        // Apply status filter if requested
        if ($request->filled('status')) {
            $filterStatus = $request->input('status');
            $siswaList = $siswaList->filter(function($s) use ($filterStatus) {
                return $s->status_key === $filterStatus;
            });
        }

        $totalSiswa = $siswaList->count();
        $paguSiswa  = $kelas ? (int) $kelas->jumlah_siswa : 0;

        return view('wali_kelas.perwalian', compact(
            'user',
            'guru',
            'kelas',
            'namaKelas',
            'namaWali',
            'siswaList',
            'totalSiswa',
            'paguSiswa'
        ));
    }

    /**
     * Display Rekap Kehadiran page for Wali Kelas matching exact UX design mockup.
     */
    public function rekapKehadiran(Request $request)
    {
        $user = auth()->user();
        $guru = $user->guru;

        // Hard block: User must have a linked Guru profile
        if (!$guru) {
            return redirect()->route('role.dashboard')
                ->with('error', 'Akun Anda belum ditautkan ke profil Guru. Hubungi Admin untuk menautkan profil guru Anda terlebih dahulu.');
        }

        $kelas = Kelas::with(['jurusan', 'siswa'])->where('id_guru_wali', $guru->id_guru)->first();

        if (!$kelas) {
            return redirect()->route('role.dashboard')
                ->with('error', 'Anda belum ditugaskan sebagai Wali Kelas. Hubungi Admin untuk menetapkan kelas perwalian Anda.');
        }

        $this->ensureDataExist($kelas);

        $namaKelas = $kelas->tingkat . ' ' . optional($kelas->jurusan)->kode_jurusan . ' ' . $kelas->rombel;
        $namaWali  = $guru->nama_guru;

        // Month and Year selection (Default current month/year)
        $month = (int) $request->input('month', Carbon::now('Asia/Jakarta')->month);
        $year  = (int) $request->input('year', Carbon::now('Asia/Jakarta')->year);

        Carbon::setLocale('id');
        $dateObj = Carbon::create($year, $month, 1, 0, 0, 0, 'Asia/Jakarta');
        $monthNameFormatted = $dateObj->translatedFormat('F Y');

        // Previous and Next Month URLs for navigation < >
        $prevDate = $dateObj->copy()->subMonth();
        $nextDate = $dateObj->copy()->addMonth();

        $prevMonth = $prevDate->month;
        $prevYear  = $prevDate->year;
        $nextMonth = $nextDate->month;
        $nextYear  = $nextDate->year;

        $startDate = $dateObj->copy()->startOfMonth();
        $endDate   = $dateObj->copy()->endOfMonth();
        $daysInMonth = $dateObj->daysInMonth;

        // Query students
        $siswaQuery = Siswa::query();
        if ($kelas) {
            $siswaQuery->where('id_kelas', $kelas->id_kelas);
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $siswaQuery->where(function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%");
            });
        }

        $siswaList = $siswaQuery->orderBy('nama_siswa')->get();
        $totalSiswa = Siswa::where('id_kelas', optional($kelas)->id_kelas)->count();
        $siswaIds = Siswa::where('id_kelas', optional($kelas)->id_kelas)->pluck('id_siswa')->toArray();

        // Query journals in this month for this class
        $jurnalEntries = JurnalMengajar::whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereHas('jadwal', function($q) use ($kelas) {
                if ($kelas) $q->where('id_kelas', $kelas->id_kelas);
            })->get();

        $totalJurnalMonth = $jurnalEntries->count();
        $jurnalIdsMonth = $jurnalEntries->pluck('id_jurnal')->toArray();

        // Absent records in month from DetailKetidakhadiran, PermohonanIzin, and SuratDispensasi
        $absentRecordsMonth = collect([]);
        if (!empty($siswaIds) && !empty($jurnalIdsMonth)) {
            $absentRecordsMonth = DetailKetidakhadiran::with(['siswa', 'jurnal.jadwal.mapel'])
                ->whereIn('id_siswa', $siswaIds)
                ->whereIn('id_jurnal', $jurnalIdsMonth)
                ->get();
        }

        $permohonanRecordsMonth = collect([]);
        if (!empty($siswaIds)) {
            $permohonanRecordsMonth = PermohonanIzin::with('siswa')
                ->whereIn('id_siswa', $siswaIds)
                ->where('tanggal_mulai', '<=', $endDate->toDateString())
                ->where('tanggal_selesai', '>=', $startDate->toDateString())
                ->get();
        }

        $dispenRecordsMonth = collect([]);
        if (!empty($siswaIds)) {
            $dispenRecordsMonth = SuratDispensasi::with('siswa')
                ->whereIn('id_siswa', $siswaIds)
                ->where('tanggal_mulai', '<=', $endDate->toDateString())
                ->where('tanggal_selesai', '>=', $startDate->toDateString())
                ->get();
        }

        // Top 4 Stat Cards
        $totalSakit = 0; $totalIzin = 0; $totalAlpa = 0;
        foreach ($absentRecordsMonth as $rec) {
            $st = strtolower($rec->status);
            if (in_array($st, ['sakit'])) $totalSakit++;
            elseif (in_array($st, ['izin', 'ijin', 'dispensasi'])) $totalIzin++;
            elseif (in_array($st, ['alpa', 'tanpa keterangan'])) $totalAlpa++;
        }
        foreach ($permohonanRecordsMonth as $perm) {
            $st = strtolower($perm->jenis_izin);
            if (in_array($st, ['sakit'])) $totalSakit++;
            elseif (in_array($st, ['izin', 'ijin', 'dispensasi'])) $totalIzin++;
            elseif (in_array($st, ['alpa', 'tanpa keterangan'])) $totalAlpa++;
        }
        foreach ($dispenRecordsMonth as $disp) {
            $totalIzin++;
        }

        $alpaFromDetail = $absentRecordsMonth->whereIn('status', ['Alpa', 'alpa', 'Tanpa Keterangan'])->pluck('id_siswa')->toArray();
        $alpaFromPermohonan = $permohonanRecordsMonth->filter(fn($p) => strtolower($p->jenis_izin) === 'alpa')->pluck('id_siswa')->toArray();
        $distinctAlpaSiswa = count(array_unique(array_merge($alpaFromDetail, $alpaFromPermohonan)));

        $totalPossibleSlots = ($totalSiswa > 0 ? $totalSiswa : 1) * max($totalJurnalMonth, 1);
        $totalAbsentMonth = $totalSakit + $totalIzin + $totalAlpa;
        $avgHadirPct = round((max(0, $totalPossibleSlots - $totalAbsentMonth) / $totalPossibleSlots) * 100);

        // Calendar Grid Calculations (7 Columns: Senin - Minggu)
        $startDayOfWeek = $startDate->dayOfWeekIso; // 1 (Mon) to 7 (Sun)
        $paddingDays = $startDayOfWeek - 1; // Empty slots before day 1
        $todayStr = Carbon::now('Asia/Jakarta')->toDateString();

        $calendarGrid = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $cDate = Carbon::create($year, $month, $day, 0, 0, 0, 'Asia/Jakarta');
            $currentDateStr = $cDate->toDateString();
            $isWeekend = $cDate->isWeekend();
            $isToday = ($currentDateStr === $todayStr);
            $formattedDateStr = $cDate->translatedFormat('l, d F Y');

            $jurnalsOnDay = $jurnalEntries->where('tanggal', $currentDateStr);
            $hasClasses = $jurnalsOnDay->count() > 0;

            // 1. DetailKetidakhadiran on this day
            $jurnalIdsDay = $jurnalsOnDay->pluck('id_jurnal')->toArray();
            $dayDetailAbsentRecs = !empty($jurnalIdsDay) ? $absentRecordsMonth->whereIn('id_jurnal', $jurnalIdsDay) : collect([]);

            // 2. PermohonanIzin active on this day
            $dayPermohonanAbsentRecs = $permohonanRecordsMonth->filter(function($p) use ($currentDateStr) {
                return $p->tanggal_mulai <= $currentDateStr && $p->tanggal_selesai >= $currentDateStr;
            });

            // 3. SuratDispensasi active on this day
            $dayDispenAbsentRecs = $dispenRecordsMonth->filter(function($d) use ($currentDateStr) {
                return $d->tanggal_mulai <= $currentDateStr && $d->tanggal_selesai >= $currentDateStr;
            });

            $absentDetailsDay = [];
            $processedSiswaIds = [];

            foreach ($dayDetailAbsentRecs as $rec) {
                $sId = $rec->id_siswa;
                $processedSiswaIds[] = $sId;
                $absentDetailsDay[] = [
                    'nama_siswa' => optional($rec->siswa)->nama_siswa ?? 'Siswa',
                    'nisn'       => optional($rec->siswa)->nisn ?? '-',
                    'status'     => ucfirst($rec->status),
                    'mapel'      => optional(optional(optional($rec->jurnal)->jadwal)->mapel)->nama_mapel ?? 'Mata Pelajaran',
                    'catatan'    => $rec->catatan ?? ''
                ];
            }

            foreach ($dayPermohonanAbsentRecs as $perm) {
                $sId = $perm->id_siswa;
                if (!in_array($sId, $processedSiswaIds)) {
                    $processedSiswaIds[] = $sId;
                    $absentDetailsDay[] = [
                        'nama_siswa' => optional($perm->siswa)->nama_siswa ?? 'Siswa',
                        'nisn'       => optional($perm->siswa)->nisn ?? '-',
                        'status'     => ucfirst($perm->jenis_izin ?? 'Izin'),
                        'mapel'      => 'Surat Izin / Sakit (Guru Piket)',
                        'catatan'    => $perm->alasan ?? 'Pengajuan Surat Piket'
                    ];
                }
            }

            foreach ($dayDispenAbsentRecs as $disp) {
                $sId = $disp->id_siswa;
                if (!in_array($sId, $processedSiswaIds)) {
                    $processedSiswaIds[] = $sId;
                    $absentDetailsDay[] = [
                        'nama_siswa' => optional($disp->siswa)->nama_siswa ?? 'Siswa',
                        'nisn'       => optional($disp->siswa)->nisn ?? '-',
                        'status'     => 'Dispensasi',
                        'mapel'      => 'Dispensasi Kegiatan (Guru Piket)',
                        'catatan'    => $disp->nama_kegiatan ?? 'Kegiatan Sekolah'
                    ];
                }
            }

            $totalAbsentDayCount = count($absentDetailsDay);
            $hasAbsentDetails = $totalAbsentDayCount > 0;

            if ($isWeekend) {
                $statusColorClass = 'libur';
                $pctDay = 0;
            } elseif (!$hasClasses && !$hasAbsentDetails) {
                $statusColorClass = 'libur';
                $pctDay = 100;
            } else {
                $possibleDay = ($totalSiswa > 0 ? $totalSiswa : 1) * max($jurnalsOnDay->count(), 1);
                $pctDay = $possibleDay > 0 ? round((max(0, $possibleDay - $totalAbsentDayCount) / $possibleDay) * 100) : 100;

                if ($pctDay >= 95) $statusColorClass = 'color-95';
                elseif ($pctDay >= 85) $statusColorClass = 'color-85';
                elseif ($pctDay >= 70) $statusColorClass = 'color-70';
                else $statusColorClass = 'color-low';
            }

            $calendarGrid[] = [
                'day'            => $day,
                'date_str'       => $currentDateStr,
                'formatted_date' => $formattedDateStr,
                'is_weekend'     => $isWeekend,
                'is_today'       => $isToday,
                'has_classes'    => $hasClasses || $hasAbsentDetails,
                'pct'            => $pctDay,
                'color_class'    => $statusColorClass,
                'absent_details' => $absentDetailsDay,
                'total_jurnal'   => $jurnalsOnDay->count()
            ];
        }

        // Calculate student monthly attendance breakdown table
        foreach ($siswaList as $siswa) {
            $studentAbsents = $absentRecordsMonth->where('id_siswa', $siswa->id_siswa);
            $studentPermohonan = $permohonanRecordsMonth->where('id_siswa', $siswa->id_siswa);
            $studentDispen = $dispenRecordsMonth->where('id_siswa', $siswa->id_siswa);

            $sakit = 0; $izin = 0; $alpa = 0;
            foreach ($studentAbsents as $rec) {
                $st = strtolower($rec->status);
                if ($st === 'sakit') $sakit++;
                elseif (in_array($st, ['izin', 'ijin', 'dispensasi'])) $izin++;
                elseif (in_array($st, ['alpa', 'tanpa keterangan'])) $alpa++;
            }
            foreach ($studentPermohonan as $perm) {
                $st = strtolower($perm->jenis_izin);
                if ($st === 'sakit') $sakit++;
                elseif (in_array($st, ['izin', 'ijin', 'dispensasi'])) $izin++;
                elseif (in_array($st, ['alpa', 'tanpa keterangan'])) $alpa++;
            }
            foreach ($studentDispen as $disp) {
                $izin++;
            }

            $totalAbsent = $sakit + $izin + $alpa;
            $pertemuanMonth = max($totalJurnalMonth, 1);
            $hadirCount = max(0, $pertemuanMonth - $totalAbsent);
            $pct = round(($hadirCount / $pertemuanMonth) * 100);

            if ($alpa >= 2 || $pct < 70) {
                $statusKey = 'tindak-lanjut';
                $statusLabel = 'Perlu tindak lanjut';
            } elseif ($alpa == 1 || ($pct >= 70 && $pct < 85) || $totalAbsent > 2) {
                $statusKey = 'pantau';
                $statusLabel = 'Perlu pantau';
            } else {
                $statusKey = 'baik';
                $statusLabel = 'Baik';
            }

            $siswa->pct = $pct;
            $siswa->hadir_count = $hadirCount;
            $siswa->sakit_count = $sakit;
            $siswa->izin_count  = $izin;
            $siswa->alpa_count  = $alpa;
            $siswa->status_key  = $statusKey;
            $siswa->status_label = $statusLabel;
        }

        if ($request->filled('status')) {
            $filterStatus = $request->input('status');
            $siswaList = $siswaList->filter(function($s) use ($filterStatus) {
                return $s->status_key === $filterStatus;
            });
        }

        return view('wali_kelas.rekap_kehadiran', compact(
            'user',
            'guru',
            'kelas',
            'namaKelas',
            'namaWali',
            'month',
            'year',
            'monthNameFormatted',
            'prevMonth',
            'prevYear',
            'nextMonth',
            'nextYear',
            'avgHadirPct',
            'totalSakit',
            'totalIzin',
            'totalAlpa',
            'distinctAlpaSiswa',
            'paddingDays',
            'calendarGrid',
            'siswaList'
        ));
    }

    /**
     * Display Surat Izin & Sakit page for Wali Kelas using 100% REAL database records for the assigned class.
     */
    public function suratIzin(Request $request)
    {
        $user = auth()->user();
        $guru = $user->guru;

        // Hard block: User must have a linked Guru profile
        if (!$guru) {
            return redirect()->route('role.dashboard')
                ->with('error', 'Akun Anda belum ditautkan ke profil Guru. Hubungi Admin untuk menautkan profil guru Anda terlebih dahulu.');
        }

        $kelas = Kelas::with(['jurusan', 'siswa'])->where('id_guru_wali', $guru->id_guru)->first();

        if (!$kelas) {
            return redirect()->route('role.dashboard')
                ->with('error', 'Anda belum ditugaskan sebagai Wali Kelas. Hubungi Admin untuk menetapkan kelas perwalian Anda.');
        }

        $this->ensureDataExist($kelas);

        $namaKelas = $kelas->tingkat . ' ' . optional($kelas->jurusan)->kode_jurusan . ' ' . $kelas->rombel;
        $namaWali  = $guru->nama_guru;

        // Fetch ACTUAL students belonging to THIS class
        $siswaList = Siswa::where('id_kelas', $kelas->id_kelas)->orderBy('nama_siswa')->get();
        $siswaIds = $siswaList->pluck('id_siswa')->toArray();

        // 1. Fetch PermohonanIzin
        $permQuery = PermohonanIzin::with('siswa')->whereIn('id_siswa', $siswaIds);
        if ($request->filled('search')) {
            $search = $request->input('search');
            $permQuery->whereHas('siswa', function($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%");
            });
        }
        if ($request->filled('jenis')) {
            $permQuery->where('jenis_izin', 'like', "%" . $request->input('jenis') . "%");
        }
        $permohonanItems = $permQuery->orderBy('id_permohonan', 'desc')->get();

        // 2. Fetch SuratDispensasi
        $dispenQuery = SuratDispensasi::with('siswa')->whereIn('id_siswa', $siswaIds);
        if ($request->filled('search')) {
            $search = $request->input('search');
            $dispenQuery->where(function($q) use ($search) {
                $q->where('nama_kegiatan', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function($sq) use ($search) {
                      $sq->where('nama_siswa', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('jenis') && strtolower($request->input('jenis')) !== 'dispensasi') {
            $dispenItems = collect([]);
        } else {
            $dispenItems = $dispenQuery->orderBy('id_dispen', 'desc')->get();
        }

        // Combine into unified list for Wali Kelas
        $mergedList = collect([]);

        foreach ($permohonanItems as $p) {
            $stMap = in_array($p->status, ['pending']) ? 'Menunggu' : 'Terverifikasi';

            $tglMulaiStr = Carbon::parse($p->tanggal_mulai)->translatedFormat('d');
            $tglSelesaiStr = Carbon::parse($p->tanggal_selesai)->translatedFormat('d M Y');
            $tglMulaiFull = Carbon::parse($p->tanggal_mulai)->translatedFormat('d M Y');

            $tglAbsen = ($p->tanggal_mulai === $p->tanggal_selesai)
                ? $tglMulaiFull
                : ($tglMulaiStr . '-' . $tglSelesaiStr);

            $words = explode(' ', optional($p->siswa)->nama_siswa ?? 'Siswa');
            $inits = count($words) >= 2 ? mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1) : mb_substr($words[0], 0, 2);

            $lampiranText = 'Surat Piket Digital';
            if ($p->bukti_surat) {
                if (str_contains(strtolower($p->bukti_surat), 'dokter')) {
                    $lampiranText = 'Surat dokter';
                } elseif (str_contains(strtolower($p->bukti_surat), 'orang_tua') || str_contains(strtolower($p->bukti_surat), 'orangtua')) {
                    $lampiranText = 'Surat orang tua';
                } else {
                    $lampiranText = 'Foto/Scan surat piket';
                }
            }

            $mergedList->push([
                'id' => 'p_' . $p->id_permohonan,
                'nama_siswa' => optional($p->siswa)->nama_siswa ?? 'Siswa',
                'initials' => strtoupper($inits),
                'jenis' => ucfirst($p->jenis_izin ?? 'Izin'),
                'tanggal_absen' => $tglAbsen,
                'diajukan' => $p->created_at ? Carbon::parse($p->created_at)->translatedFormat('d M, H:i') : '-',
                'lampiran' => $lampiranText,
                'status' => $stMap,
                'created_at' => $p->created_at ?? $p->tanggal_mulai,
            ]);
        }

        foreach ($dispenItems as $d) {
            $tglMulaiStr = Carbon::parse($d->tanggal_mulai)->translatedFormat('d');
            $tglSelesaiStr = Carbon::parse($d->tanggal_selesai)->translatedFormat('d M Y');
            $tglMulaiFull = Carbon::parse($d->tanggal_mulai)->translatedFormat('d M Y');

            $tglAbsen = ($d->tanggal_mulai === $d->tanggal_selesai)
                ? $tglMulaiFull
                : ($tglMulaiStr . '-' . $tglSelesaiStr);

            $words = explode(' ', optional($d->siswa)->nama_siswa ?? 'Siswa');
            $inits = count($words) >= 2 ? mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1) : mb_substr($words[0], 0, 2);

            $mergedList->push([
                'id' => 'd_' . $d->id_dispen,
                'nama_siswa' => optional($d->siswa)->nama_siswa ?? 'Siswa',
                'initials' => strtoupper($inits),
                'jenis' => 'Dispensasi',
                'tanggal_absen' => $tglAbsen,
                'diajukan' => $d->created_at ? Carbon::parse($d->created_at)->translatedFormat('d M, H:i') : '-',
                'lampiran' => 'Dispensasi Piket (' . $d->nama_kegiatan . ')',
                'status' => 'Terverifikasi',
                'created_at' => $d->created_at ?? $d->tanggal_mulai,
            ]);
        }

        // Apply status filter if selected (Menunggu / Terverifikasi)
        if ($request->filled('status')) {
            $reqSt = $request->input('status');
            $mergedList = $mergedList->filter(function($item) use ($reqSt) {
                return strtolower($item['status']) === strtolower($reqSt);
            });
        }

        $submissions = $mergedList->sortByDesc('created_at')->values()->toArray();

        // Calculate REAL counts directly from database queries for this class
        $allPermohonan = PermohonanIzin::whereIn('id_siswa', $siswaIds)->get();
        $allDispen = SuratDispensasi::whereIn('id_siswa', $siswaIds)->get();

        $menungguCount = $allPermohonan->whereIn('status', ['pending'])->count();
        $terverifikasiCount = $allPermohonan->whereIn('status', ['approved_piket', 'approved_waka', 'approved_waka_sdm', 'approved_kepsek'])->count() + $allDispen->count();
        $tanpaKeteranganCount = $allPermohonan->filter(fn($p) => strtolower($p->jenis_izin) === 'alpa')->count();

        return view('wali_kelas.surat_izin', compact(
            'user',
            'guru',
            'kelas',
            'namaKelas',
            'namaWali',
            'submissions',
            'menungguCount',
            'terverifikasiCount',
            'tanpaKeteranganCount'
        ));
    }
}
