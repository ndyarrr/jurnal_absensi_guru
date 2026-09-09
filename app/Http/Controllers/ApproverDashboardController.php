<?php

namespace App\Http\Controllers;

use App\Models\IzinGuru;
use App\Models\SuratDispensasi;
use Illuminate\Http\Request;

class ApproverDashboardController extends Controller
{
    /**
     * Display the Multi-Approval Dashboard for Waka Kesiswaan, Waka Kurikulum, and Kepala Sekolah.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isKepsek = ($user->role === 'kepala_sekolah');

        // Kepala sekolah cannot access dispensasi tab
        $activeTab = $isKepsek ? 'izin' : $request->input('tab', 'izin'); // 'izin' or 'dispensasi'
        $statusFilter = $request->input('status', 'all'); // 'all', 'pending', 'disetujui', 'ditolak'
        $search = $request->input('search');

        // Statistics counts
        $izinStats = [
            'total'     => IzinGuru::count(),
            'pending'   => IzinGuru::where(function($q) {
                $q->where('status_waka', 'pending')
                  ->orWhere('status_waka_kurikulum', 'pending')
                  ->orWhere('status_kepsek', 'pending');
            })->where('status_approval', '!=', 'ditolak')->count(),
            'disetujui' => IzinGuru::where('status_waka', 'disetujui')
                                   ->where('status_waka_kurikulum', 'disetujui')
                                   ->where('status_kepsek', 'disetujui')->count(),
            'ditolak'   => IzinGuru::where(function($q) {
                $q->where('status_approval', 'ditolak')
                  ->orWhere('status_waka', 'ditolak')
                  ->orWhere('status_waka_kurikulum', 'ditolak')
                  ->orWhere('status_kepsek', 'ditolak');
            })->count(),
        ];

        $dispensasiStats = [
            'total'     => SuratDispensasi::count(),
            'pending'   => SuratDispensasi::where(function($q) {
                $q->where('status_waka', 'pending')
                  ->orWhere('status_waka_kurikulum', 'pending');
            })->where('status_approval', '!=', 'ditolak')->count(),
            'disetujui' => SuratDispensasi::where('status_waka', 'disetujui')
                                          ->where('status_waka_kurikulum', 'disetujui')->count(),
            'ditolak'   => SuratDispensasi::where(function($q) {
                $q->where('status_approval', 'ditolak')
                  ->orWhere('status_waka', 'ditolak')
                  ->orWhere('status_waka_kurikulum', 'ditolak');
            })->count(),
        ];

        // 1. Izin Guru Query
        $izinQuery = IzinGuru::with(['guru', 'approver', 'approverWaka', 'approverWakaKurikulum', 'approverKepsek']);

        if ($statusFilter === 'pending') {
            $izinQuery->where(function($q) {
                $q->where('status_waka', 'pending')
                  ->orWhere('status_waka_kurikulum', 'pending')
                  ->orWhere('status_kepsek', 'pending');
            })->where('status_approval', '!=', 'ditolak');
        } elseif ($statusFilter === 'disetujui') {
            $izinQuery->where('status_waka', 'disetujui')
                      ->where('status_waka_kurikulum', 'disetujui')
                      ->where('status_kepsek', 'disetujui');
        } elseif ($statusFilter === 'ditolak') {
            $izinQuery->where(function($q) {
                $q->where('status_approval', 'ditolak')
                  ->orWhere('status_waka', 'ditolak')
                  ->orWhere('status_waka_kurikulum', 'ditolak')
                  ->orWhere('status_kepsek', 'ditolak');
            });
        }

        if ($search) {
            $izinQuery->where(function ($q) use ($search) {
                $q->whereHas('guru', function ($gq) use ($search) {
                    $gq->where('nama_guru', 'like', "%{$search}%");
                })->orWhere('alasan_izin', 'like', "%{$search}%")
                  ->orWhere('kategori_izin', 'like', "%{$search}%");
            });
        }

        $izinList = $izinQuery->orderByDesc('created_at')->paginate(10, ['*'], 'page_izin')->withQueryString();

        // 2. Surat Dispensasi Query
        $dispenQuery = SuratDispensasi::with(['siswa.kelas.jurusan', 'guru', 'kelas.jurusan', 'approver', 'approverWaka', 'approverWakaKurikulum', 'approverKepsek']);

        if ($statusFilter === 'pending') {
            $dispenQuery->where(function($q) {
                $q->where('status_waka', 'pending')
                  ->orWhere('status_waka_kurikulum', 'pending');
            })->where('status_approval', '!=', 'ditolak');
        } elseif ($statusFilter === 'disetujui') {
            $dispenQuery->where('status_waka', 'disetujui')
                      ->where('status_waka_kurikulum', 'disetujui');
        } elseif ($statusFilter === 'ditolak') {
            $dispenQuery->where(function($q) {
                $q->where('status_approval', 'ditolak')
                  ->orWhere('status_waka', 'ditolak')
                  ->orWhere('status_waka_kurikulum', 'ditolak');
            });
        }

        if ($search) {
            $dispenQuery->where(function ($q) use ($search) {
                $q->where('nama_kegiatan', 'like', "%{$search}%")
                  ->orWhere('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('alasan_dispensasi', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function ($sq) use ($search) {
                      $sq->where('nama_siswa', 'like', "%{$search}%");
                  });
            });
        }

        $dispensasiList = $dispenQuery->orderByDesc('created_at')->paginate(10, ['*'], 'page_dispen')->withQueryString();

        return view('approver.dashboard', compact(
            'activeTab',
            'statusFilter',
            'search',
            'izinStats',
            'dispensasiStats',
            'izinList',
            'dispensasiList',
            'isKepsek'
        ));
    }

    /**
     * Setujui Izin Guru dari Dashboard (sesuai role Waka / Waka Kurikulum / Kepsek).
     */
    public function approveIzin(Request $request, $id)
    {
        $izin = IzinGuru::findOrFail($id);
        $user = auth()->user();

        if ($user->role === 'waka') {
            $izin->status_waka = 'disetujui';
            $izin->disetujui_waka_oleh = $user->id;
            $izin->tgl_disetujui_waka = now();
            $msg = 'Permohonan izin guru berhasil DISETUJUI oleh Waka Kesiswaan.';
        } elseif ($user->role === 'waka_kurikulum') {
            $izin->status_waka_kurikulum = 'disetujui';
            $izin->disetujui_waka_kurikulum_oleh = $user->id;
            $izin->tgl_disetujui_waka_kurikulum = now();
            $msg = 'Permohonan izin guru berhasil DISETUJUI oleh Waka Kurikulum.';
        } elseif ($user->role === 'kepala_sekolah') {
            $izin->status_kepsek = 'disetujui';
            $izin->disetujui_kepsek_oleh = $user->id;
            $izin->tgl_disetujui_kepsek = now();
            $msg = 'Permohonan izin guru berhasil DISETUJUI oleh Kepala Sekolah.';
        } else {
            $izin->status_waka = 'disetujui';
            $izin->status_waka_kurikulum = 'disetujui';
            $izin->status_kepsek = 'disetujui';
            $msg = 'Permohonan izin guru berhasil DISETUJUI.';
        }

        // Recalculate overall status_approval
        if ($izin->status_waka === 'disetujui' && $izin->status_waka_kurikulum === 'disetujui' && $izin->status_kepsek === 'disetujui') {
            $izin->status_approval = 'disetujui';
        } elseif ($izin->status_waka === 'ditolak' || $izin->status_waka_kurikulum === 'ditolak' || $izin->status_kepsek === 'ditolak') {
            $izin->status_approval = 'ditolak';
        } else {
            $izin->status_approval = 'pending';
        }

        $izin->disetujui_oleh = $user->id;
        $izin->save();

        return back()->with('success', $msg);
    }

    /**
     * Tolak Izin Guru dari Dashboard.
     */
    public function rejectIzin(Request $request, $id)
    {
        $izin = IzinGuru::findOrFail($id);
        $user = auth()->user();
        $catatan = $request->input('catatan_approver', 'Ditolak oleh ' . ($user->role_label ?? 'Approver'));

        if ($user->role === 'waka') {
            $izin->status_waka = 'ditolak';
            $izin->disetujui_waka_oleh = $user->id;
            $izin->tgl_disetujui_waka = now();
        } elseif ($user->role === 'waka_kurikulum') {
            $izin->status_waka_kurikulum = 'ditolak';
            $izin->disetujui_waka_kurikulum_oleh = $user->id;
            $izin->tgl_disetujui_waka_kurikulum = now();
        } elseif ($user->role === 'kepala_sekolah') {
            $izin->status_kepsek = 'ditolak';
            $izin->disetujui_kepsek_oleh = $user->id;
            $izin->tgl_disetujui_kepsek = now();
        } else {
            $izin->status_waka = 'ditolak';
            $izin->status_waka_kurikulum = 'ditolak';
            $izin->status_kepsek = 'ditolak';
        }

        $izin->status_approval = 'ditolak';
        $izin->catatan_approver = $catatan;
        $izin->disetujui_oleh = $user->id;
        $izin->save();

        return back()->with('success', 'Permohonan izin guru telah DITOLAK.');
    }

    /**
     * Reset / Undo Keputusan Izin Guru kembali ke Pending.
     */
    public function resetIzin(Request $request, $id)
    {
        $izin = IzinGuru::findOrFail($id);
        $user = auth()->user();

        if ($user->role === 'waka') {
            $izin->status_waka = 'pending';
            $izin->disetujui_waka_oleh = null;
            $izin->tgl_disetujui_waka = null;
        } elseif ($user->role === 'waka_kurikulum') {
            $izin->status_waka_kurikulum = 'pending';
            $izin->disetujui_waka_kurikulum_oleh = null;
            $izin->tgl_disetujui_waka_kurikulum = null;
        } elseif ($user->role === 'kepala_sekolah') {
            $izin->status_kepsek = 'pending';
            $izin->disetujui_kepsek_oleh = null;
            $izin->tgl_disetujui_kepsek = null;
        } else {
            $izin->status_waka = 'pending';
            $izin->status_waka_kurikulum = 'pending';
            $izin->status_kepsek = 'pending';
            $izin->disetujui_waka_oleh = null;
            $izin->disetujui_waka_kurikulum_oleh = null;
            $izin->disetujui_kepsek_oleh = null;
        }

        // Recalculate status_approval
        if ($izin->status_waka === 'ditolak' || $izin->status_waka_kurikulum === 'ditolak' || $izin->status_kepsek === 'ditolak') {
            $izin->status_approval = 'ditolak';
        } elseif ($izin->status_waka === 'disetujui' && $izin->status_waka_kurikulum === 'disetujui' && $izin->status_kepsek === 'disetujui') {
            $izin->status_approval = 'disetujui';
        } else {
            $izin->status_approval = 'pending';
        }

        $izin->save();

        return back()->with('success', 'Status permohonan izin guru berhasil DIBATALKAN / DIRESET ke Menunggu.');
    }

    /**
     * Setujui Surat Dispensasi Siswa dari Dashboard (Waka Kesiswaan & Waka Kurikulum; Kepsek tidak perlu).
     */
    public function approveDispensasi(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->role === 'kepala_sekolah') {
            return back()->with('error', 'Kepala Sekolah tidak memiliki wewenang persetujuan dispensasi siswa.');
        }

        $dispen = SuratDispensasi::findOrFail($id);

        if ($user->role === 'waka') {
            $dispen->status_waka = 'disetujui';
            $dispen->disetujui_waka_oleh = $user->id;
            $dispen->tgl_disetujui_waka = now();
            $msg = 'Surat dispensasi siswa berhasil DISETUJUI oleh Waka Kesiswaan.';
        } elseif ($user->role === 'waka_kurikulum') {
            $dispen->status_waka_kurikulum = 'disetujui';
            $dispen->disetujui_waka_kurikulum_oleh = $user->id;
            $dispen->tgl_disetujui_waka_kurikulum = now();
            $msg = 'Surat dispensasi siswa berhasil DISETUJUI oleh Waka Kurikulum.';
        } else {
            $dispen->status_waka = 'disetujui';
            $dispen->status_waka_kurikulum = 'disetujui';
            $msg = 'Surat dispensasi siswa berhasil DISETUJUI.';
        }

        // Recalculate status_approval — Final approval for student dispen requires Waka + Waka Kurikulum (Kepsek not required!)
        if ($dispen->status_waka === 'disetujui' && $dispen->status_waka_kurikulum === 'disetujui') {
            $dispen->status_approval = 'disetujui';
        } elseif ($dispen->status_waka === 'ditolak' || $dispen->status_waka_kurikulum === 'ditolak') {
            $dispen->status_approval = 'ditolak';
        } else {
            $dispen->status_approval = 'pending';
        }

        $dispen->disetujui_oleh = $user->id;
        $dispen->save();

        return back()->with('success', $msg);
    }

    /**
     * Tolak Surat Dispensasi Siswa dari Dashboard.
     */
    public function rejectDispensasi(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->role === 'kepala_sekolah') {
            return back()->with('error', 'Kepala Sekolah tidak memiliki wewenang persetujuan dispensasi siswa.');
        }

        $dispen = SuratDispensasi::findOrFail($id);
        $catatan = $request->input('catatan_approver', 'Ditolak oleh ' . ($user->role_label ?? 'Waka'));

        if ($user->role === 'waka') {
            $dispen->status_waka = 'ditolak';
            $dispen->disetujui_waka_oleh = $user->id;
            $dispen->tgl_disetujui_waka = now();
        } elseif ($user->role === 'waka_kurikulum') {
            $dispen->status_waka_kurikulum = 'ditolak';
            $dispen->disetujui_waka_kurikulum_oleh = $user->id;
            $dispen->tgl_disetujui_waka_kurikulum = now();
        } else {
            $dispen->status_waka = 'ditolak';
            $dispen->status_waka_kurikulum = 'ditolak';
        }

        $dispen->status_approval = 'ditolak';
        $dispen->catatan_approver = $catatan;
        $dispen->disetujui_oleh = $user->id;
        $dispen->save();

        return back()->with('success', 'Surat dispensasi siswa telah DITOLAK.');
    }

    /**
     * Reset / Undo Keputusan Surat Dispensasi Siswa kembali ke Pending.
     */
    public function resetDispensasi(Request $request, $id)
    {
        $user = auth()->user();

        if ($user->role === 'kepala_sekolah') {
            return back()->with('error', 'Kepala Sekolah tidak memiliki wewenang persetujuan dispensasi siswa.');
        }

        $dispen = SuratDispensasi::findOrFail($id);

        if ($user->role === 'waka') {
            $dispen->status_waka = 'pending';
            $dispen->disetujui_waka_oleh = null;
            $dispen->tgl_disetujui_waka = null;
        } elseif ($user->role === 'waka_kurikulum') {
            $dispen->status_waka_kurikulum = 'pending';
            $dispen->disetujui_waka_kurikulum_oleh = null;
            $dispen->tgl_disetujui_waka_kurikulum = null;
        } else {
            $dispen->status_waka = 'pending';
            $dispen->status_waka_kurikulum = 'pending';
            $dispen->disetujui_waka_oleh = null;
            $dispen->disetujui_waka_kurikulum_oleh = null;
        }

        if ($dispen->status_waka === 'ditolak' || $dispen->status_waka_kurikulum === 'ditolak') {
            $dispen->status_approval = 'ditolak';
        } elseif ($dispen->status_waka === 'disetujui' && $dispen->status_waka_kurikulum === 'disetujui') {
            $dispen->status_approval = 'disetujui';
        } else {
            $dispen->status_approval = 'pending';
        }

        $dispen->save();

        return back()->with('success', 'Status surat dispensasi siswa berhasil DIBATALKAN / DIRESET ke Menunggu.');
    }
}
