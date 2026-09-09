<?php

namespace App\Http\Controllers;

use App\Models\IzinGuru;
use App\Models\SuratDispensasi;
use Illuminate\Http\Request;

class IzinApprovalController extends Controller
{
    /**
     * Tampilkan Halaman Persetujuan Izin Guru (Public/Token-based)
     */
    public function show($id, $token)
    {
        $izin = IzinGuru::with(['guru', 'approverWaka', 'approverKepsek'])
            ->where('id_izin_guru', $id)
            ->where('approval_token', $token)
            ->firstOrFail();

        return view('izin.approval', compact('izin'));
    }

    /**
     * Setujui Izin Guru (Public/Token-based)
     */
    public function approve(Request $request, $id, $token)
    {
        $izin = IzinGuru::where('id_izin_guru', $id)
            ->where('approval_token', $token)
            ->firstOrFail();

        $user = auth()->user();
        $asRole = $request->input('as_role') ?: ($user ? $user->role : 'waka');

        if ($asRole === 'waka') {
            $izin->status_waka = 'disetujui';
            $izin->disetujui_waka_oleh = $user ? $user->id : null;
            $izin->tgl_disetujui_waka = now();
            $msg = 'Permohonan izin guru berhasil DISETUJUI oleh Waka Kesiswaan.';
        } elseif ($asRole === 'waka_kurikulum' || $asRole === 'waka_sdm') {
            $izin->status_waka_kurikulum = 'disetujui';
            $izin->disetujui_waka_kurikulum_oleh = $user ? $user->id : null;
            $izin->tgl_disetujui_waka_kurikulum = now();
            $msg = 'Permohonan izin guru berhasil DISETUJUI oleh Waka Kurikulum.';
        } elseif ($asRole === 'kepala_sekolah') {
            $izin->status_kepsek = 'disetujui';
            $izin->disetujui_kepsek_oleh = $user ? $user->id : null;
            $izin->tgl_disetujui_kepsek = now();
            $msg = 'Permohonan izin guru berhasil DISETUJUI oleh Kepala Sekolah.';
        } else {
            $izin->status_waka = 'disetujui';
            $izin->status_waka_kurikulum = 'disetujui';
            $izin->status_kepsek = 'disetujui';
            $msg = 'Permohonan izin guru berhasil DISETUJUI.';
        }

        if ($izin->status_waka === 'disetujui' && $izin->status_waka_kurikulum === 'disetujui' && $izin->status_kepsek === 'disetujui') {
            $izin->status_approval = 'disetujui';
        } else {
            $izin->status_approval = 'pending';
        }

        $izin->disetujui_oleh = $user ? $user->id : null;
        $izin->save();

        return back()->with('success', $msg);
    }

    /**
     * Tolak Izin Guru (Public/Token-based)
     */
    public function reject(Request $request, $id, $token)
    {
        $izin = IzinGuru::where('id_izin_guru', $id)
            ->where('approval_token', $token)
            ->firstOrFail();

        $user = auth()->user();
        $catatan = $request->input('catatan_approver', 'Ditolak oleh Waka/Kepsek.');

        $izin->update([
            'status_approval' => 'ditolak',
            'catatan_approver' => $catatan,
            'disetujui_oleh' => $user ? $user->id : null,
        ]);

        return back()->with('success', 'Permohonan izin telah DITOLAK.');
    }

    /**
     * Tampilkan Halaman Persetujuan Surat Dispensasi Siswa (Public/Token-based)
     */
    public function showDispensasi($id, $token)
    {
        $dispen = SuratDispensasi::with(['siswa.kelas.jurusan', 'guru', 'kelas.jurusan', 'approverWaka', 'approverKepsek'])
            ->where('id_dispen', $id)
            ->where('barcode_token', $token)
            ->firstOrFail();

        return view('dispensasi.approval', compact('dispen'));
    }

    /**
     * Setujui Surat Dispensasi Siswa (Public/Token-based)
     */
    public function approveDispensasi(Request $request, $id, $token)
    {
        $dispen = SuratDispensasi::where('id_dispen', $id)
            ->where('barcode_token', $token)
            ->firstOrFail();

        $user = auth()->user();
        $asRole = $request->input('as_role') ?: ($user ? $user->role : 'waka');

        if ($asRole === 'kepala_sekolah' || ($user && $user->role === 'kepala_sekolah')) {
            return back()->with('error', 'Kepala Sekolah tidak memiliki wewenang persetujuan dispensasi siswa.');
        }

        if ($asRole === 'waka') {
            $dispen->status_waka = 'disetujui';
            $dispen->disetujui_waka_oleh = $user ? $user->id : null;
            $dispen->tgl_disetujui_waka = now();
            $msg = 'Surat dispensasi siswa berhasil DISETUJUI oleh Waka Kesiswaan.';
        } elseif ($asRole === 'waka_kurikulum' || $asRole === 'waka_sdm') {
            $dispen->status_waka_kurikulum = 'disetujui';
            $dispen->disetujui_waka_kurikulum_oleh = $user ? $user->id : null;
            $dispen->tgl_disetujui_waka_kurikulum = now();
            $msg = 'Surat dispensasi siswa berhasil DISETUJUI oleh Waka Kurikulum.';
        } else {
            $dispen->status_waka = 'disetujui';
            $dispen->status_waka_kurikulum = 'disetujui';
            $msg = 'Surat dispensasi siswa berhasil DISETUJUI.';
        }

        if ($dispen->status_waka === 'disetujui' && $dispen->status_waka_kurikulum === 'disetujui') {
            $dispen->status_approval = 'disetujui';
        } else {
            $dispen->status_approval = 'pending';
        }

        $dispen->disetujui_oleh = $user ? $user->id : null;
        $dispen->save();

        return back()->with('success', $msg);
    }

    /**
     * Tolak Surat Dispensasi Siswa (Public/Token-based)
     */
    public function rejectDispensasi(Request $request, $id, $token)
    {
        $dispen = SuratDispensasi::where('id_dispen', $id)
            ->where('barcode_token', $token)
            ->firstOrFail();

        $user = auth()->user();
        if ($user && $user->role === 'kepala_sekolah') {
            return back()->with('error', 'Kepala Sekolah tidak memiliki wewenang persetujuan dispensasi siswa.');
        }

        $dispen->update([
            'status_waka' => 'ditolak',
            'status_approval' => 'ditolak',
            'disetujui_oleh' => $user ? $user->id : null,
        ]);

        return back()->with('success', 'Surat dispensasi siswa telah DITOLAK.');
    }
}
