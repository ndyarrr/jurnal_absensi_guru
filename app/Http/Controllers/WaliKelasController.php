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

class WaliKelasController extends Controller
{
    /**
     * Display dedicated Dashboard for Wali Kelas matching exact UX design mockup.
     */
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $guru = $user->guru;

        // Find assigned class for this Wali Kelas
        $kelas = null;
        if ($guru) {
            $kelas = Kelas::with(['jurusan', 'siswa'])->where('id_guru_wali', $guru->id_guru)->first();
        }

        // Fallback to first class if not directly mapped yet
        if (!$kelas) {
            $kelas = Kelas::with(['jurusan', 'siswa'])->first();
        }

        $namaKelas = $kelas ? ($kelas->tingkat . ' ' . optional($kelas->jurusan)->kode_jurusan . ' ' . $kelas->rombel) : 'XI RPL 1';
        $namaWali  = $guru ? $guru->nama_guru : ($user->name ?? 'Aily Cantika, S.Pd');

        // Date formatting
        Carbon::setLocale('id');
        $todayFormatted = Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y');

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

        // Calculate REAL attendance stats from DetailKetidakhadiran
        $sakitCount = 0;
        $izinCount  = 0;
        $alpaCount  = 0;

        if (!empty($siswaIds)) {
            $ketidakhadiran = DetailKetidakhadiran::whereIn('id_siswa', $siswaIds)->get();
            foreach ($ketidakhadiran as $dk) {
                $st = strtolower($dk->status);
                if (in_array($st, ['sakit'])) {
                    $sakitCount++;
                } elseif (in_array($st, ['izin', 'ijin'])) {
                    $izinCount++;
                } elseif (in_array($st, ['alpa', 'tanpa keterangan'])) {
                    $alpaCount++;
                }
            }
        }

        $totalAbsent = $sakitCount + $izinCount + $alpaCount;
        $hadirCount = max(0, $totalSiswa - $totalAbsent);
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

        // Find assigned class for this Wali Kelas
        $kelas = null;
        if ($guru) {
            $kelas = Kelas::with(['jurusan', 'siswa'])->where('id_guru_wali', $guru->id_guru)->first();
        }

        if (!$kelas) {
            $kelas = Kelas::with(['jurusan', 'siswa'])->first();
        }

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
            $sakit = 0; $izin = 0; $alpa = 0;
            foreach ($absentRecords as $rec) {
                $st = strtolower($rec->status);
                if ($st === 'sakit') $sakit++;
                elseif (in_array($st, ['izin', 'ijin'])) $izin++;
                elseif (in_array($st, ['alpa', 'tanpa keterangan'])) $alpa++;
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

        return view('wali_kelas.perwalian', compact(
            'user',
            'guru',
            'kelas',
            'namaKelas',
            'namaWali',
            'siswaList',
            'totalSiswa'
        ));
    }

    /**
     * Display Rekap Kehadiran page for Wali Kelas matching exact UX design mockup.
     */
    public function rekapKehadiran(Request $request)
    {
        $user = auth()->user();
        $guru = $user->guru;

        $kelas = null;
        if ($guru) {
            $kelas = Kelas::with(['jurusan', 'siswa'])->where('id_guru_wali', $guru->id_guru)->first();
        }

        if (!$kelas) {
            $kelas = Kelas::with(['jurusan', 'siswa'])->first();
        }

        $namaKelas = $kelas ? ($kelas->tingkat . ' ' . optional($kelas->jurusan)->kode_jurusan . ' ' . $kelas->rombel) : 'XI RPL 1';
        $namaWali  = $guru ? $guru->nama_guru : ($user->name ?? 'Aily Cantika, S.Pd');

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

        // Absent records in month
        $absentRecordsMonth = collect([]);
        if (!empty($siswaIds) && !empty($jurnalIdsMonth)) {
            $absentRecordsMonth = DetailKetidakhadiran::whereIn('id_siswa', $siswaIds)
                ->whereIn('id_jurnal', $jurnalIdsMonth)
                ->get();
        }

        // Top 4 Stat Cards
        $totalSakit = 0; $totalIzin = 0; $totalAlpa = 0;
        foreach ($absentRecordsMonth as $rec) {
            $st = strtolower($rec->status);
            if (in_array($st, ['sakit'])) $totalSakit++;
            elseif (in_array($st, ['izin', 'ijin'])) $totalIzin++;
            elseif (in_array($st, ['alpa', 'tanpa keterangan'])) $totalAlpa++;
        }

        $distinctAlpaSiswa = $absentRecordsMonth->whereIn('status', ['Alpa', 'alpa', 'Tanpa Keterangan'])->pluck('id_siswa')->unique()->count();
        $totalPossibleSlots = ($totalSiswa > 0 ? $totalSiswa : 1) * max($totalJurnalMonth, 1);
        $totalAbsentMonth = $totalSakit + $totalIzin + $totalAlpa;
        $avgHadirPct = round((max(0, $totalPossibleSlots - $totalAbsentMonth) / $totalPossibleSlots) * 100);

        // Calendar Heatmap Grid (Days 1 to $daysInMonth)
        $calendarGrid = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDateStr = Carbon::create($year, $month, $day)->toDateString();
            $isWeekend = Carbon::create($year, $month, $day)->isWeekend();

            $jurnalsOnDay = $jurnalEntries->where('tanggal', $currentDateStr);
            $hasClasses = $jurnalsOnDay->count() > 0;

            if ($isWeekend || !$hasClasses) {
                $statusColorClass = 'libur';
                $pctDay = 0;
            } else {
                $jurnalIdsDay = $jurnalsOnDay->pluck('id_jurnal')->toArray();
                $absentCountDay = $absentRecordsMonth->whereIn('id_jurnal', $jurnalIdsDay)->count();
                $possibleDay = $totalSiswa * $jurnalsOnDay->count();
                $pctDay = $possibleDay > 0 ? round((max(0, $possibleDay - $absentCountDay) / $possibleDay) * 100) : 100;

                if ($pctDay >= 95) $statusColorClass = 'color-95';
                elseif ($pctDay >= 85) $statusColorClass = 'color-85';
                elseif ($pctDay >= 70) $statusColorClass = 'color-70';
                else $statusColorClass = 'color-low';
            }

            $calendarGrid[] = [
                'day'         => $day,
                'date_str'    => $currentDateStr,
                'is_weekend'  => $isWeekend,
                'has_classes' => $hasClasses,
                'pct'         => $pctDay,
                'color_class' => $statusColorClass
            ];
        }

        // Calculate student monthly attendance breakdown table
        foreach ($siswaList as $siswa) {
            $studentAbsents = $absentRecordsMonth->where('id_siswa', $siswa->id_siswa);
            $sakit = 0; $izin = 0; $alpa = 0;
            foreach ($studentAbsents as $rec) {
                $st = strtolower($rec->status);
                if ($st === 'sakit') $sakit++;
                elseif (in_array($st, ['izin', 'ijin'])) $izin++;
                elseif (in_array($st, ['alpa', 'tanpa keterangan'])) $alpa++;
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
            'calendarGrid',
            'siswaList'
        ));
    }

    /**
     * Export Rekap Kehadiran per siswa as CSV.
     */
    public function exportRekapCsv(Request $request)
    {
        $user = auth()->user();
        $guru = $user->guru;

        $kelas = null;
        if ($guru) {
            $kelas = Kelas::with(['jurusan'])->where('id_guru_wali', $guru->id_guru)->first();
        }
        if (!$kelas) {
            $kelas = Kelas::with(['jurusan'])->first();
        }

        $month = (int) $request->input('month', Carbon::now('Asia/Jakarta')->month);
        $year  = (int) $request->input('year', Carbon::now('Asia/Jakarta')->year);
        $dateObj = Carbon::create($year, $month, 1, 0, 0, 0, 'Asia/Jakarta');

        $siswaList = Siswa::where('id_kelas', optional($kelas)->id_kelas)->orderBy('nama_siswa')->get();

        $startDate = $dateObj->copy()->startOfMonth();
        $endDate   = $dateObj->copy()->endOfMonth();

        $jurnalEntries = JurnalMengajar::whereBetween('tanggal', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereHas('jadwal', function($q) use ($kelas) {
                if ($kelas) $q->where('id_kelas', $kelas->id_kelas);
            })->get();
        $totalJurnal = max($jurnalEntries->count(), 1);

        $filename = 'Rekap_Kehadiran_' . str_replace(' ', '_', optional($kelas)->tingkat . '_' . optional($kelas)->rombel) . '_' . $dateObj->format('Y_m') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($siswaList, $jurnalEntries, $totalJurnal) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, ['No', 'NISN', 'Nama Siswa', 'No. Telepon', 'Hadir', 'Sakit', 'Izin', 'Alpa', 'Kehadiran (%)', 'Status']);

            foreach ($siswaList as $idx => $s) {
                $studentAbsents = DetailKetidakhadiran::where('id_siswa', $s->id_siswa)
                    ->whereIn('id_jurnal', $jurnalEntries->pluck('id_jurnal'))
                    ->get();

                $sakit = 0; $izin = 0; $alpa = 0;
                foreach ($studentAbsents as $rec) {
                    $st = strtolower($rec->status);
                    if ($st === 'sakit') $sakit++;
                    elseif (in_array($st, ['izin', 'ijin'])) $izin++;
                    elseif (in_array($st, ['alpa', 'tanpa keterangan'])) $alpa++;
                }

                $totalAbsent = $sakit + $izin + $alpa;
                $hadir = max(0, $totalJurnal - $totalAbsent);
                $pct = round(($hadir / $totalJurnal) * 100);

                $statusLabel = ($alpa >= 2 || $pct < 70) ? 'Perlu tindak lanjut' : (($alpa == 1 || $pct < 85) ? 'Perlu pantau' : 'Baik');

                fputcsv($file, [
                    $idx + 1,
                    $s->nisn ?? '-',
                    $s->nama_siswa,
                    $s->no_telepon ?? '-',
                    $hadir,
                    $sakit,
                    $izin,
                    $alpa,
                    $pct . '%',
                    $statusLabel
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
