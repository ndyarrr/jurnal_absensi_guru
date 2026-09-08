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

        if (in_array($asRole, ['waka', 'waka_sdm'], true)) {
            $statusApproval = ($izin->status_kepsek === 'disetujui') ? 'disetujui' : 'disetujui_waka';
            $izin->update([
                'status_waka' => 'disetujui',
                'disetujui_waka_oleh' => $user ? $user->id : null,
                'tgl_disetujui_waka' => now(),
                'status_approval' => $statusApproval,
                'disetujui_oleh' => $user ? $user->id : null,
            ]);
            return back()->with('success', 'Permohonan izin guru berhasil DISETUJUI oleh Waka.');
        } elseif ($asRole === 'kepala_sekolah') {
            $statusApproval = ($izin->status_waka === 'disetujui') ? 'disetujui' : 'disetujui_kepsek';
            $izin->update([
                'status_kepsek' => 'disetujui',
                'disetujui_kepsek_oleh' => $user ? $user->id : null,
                'tgl_disetujui_kepsek' => now(),
                'status_approval' => $statusApproval,
                'disetujui_oleh' => $user ? $user->id : null,
            ]);
            return back()->with('success', 'Permohonan izin guru berhasil DISETUJUI oleh Kepala Sekolah.');
        }

        $izin->update([
            'status_approval' => 'disetujui',
            'disetujui_oleh' => $user ? $user->id : null,
        ]);

        return back()->with('success', 'Permohonan izin berhasil DISETUJUI.');
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

        if (in_array($asRole, ['waka', 'waka_sdm'], true)) {
            $statusApproval = ($dispen->status_kepsek === 'disetujui') ? 'disetujui' : 'disetujui_waka';
            $dispen->update([
                'status_waka' => 'disetujui',
                'disetujui_waka_oleh' => $user ? $user->id : null,
                'tgl_disetujui_waka' => now(),
                'status_approval' => $statusApproval,
                'disetujui_oleh' => $user ? $user->id : null,
            ]);
            return back()->with('success', 'Surat dispensasi siswa berhasil DISETUJUI oleh Waka.');
        } elseif ($asRole === 'kepala_sekolah') {
            $statusApproval = ($dispen->status_waka === 'disetujui') ? 'disetujui' : 'disetujui_kepsek';
            $dispen->update([
                'status_kepsek' => 'disetujui',
                'disetujui_kepsek_oleh' => $user ? $user->id : null,
                'tgl_disetujui_kepsek' => now(),
                'status_approval' => $statusApproval,
                'disetujui_oleh' => $user ? $user->id : null,
            ]);
            return back()->with('success', 'Surat dispensasi siswa berhasil DISETUJUI oleh Kepala Sekolah.');
        }

        $dispen->update([
            'status_approval' => 'disetujui',
            'disetujui_oleh' => $user ? $user->id : null,
        ]);

        return back()->with('success', 'Surat dispensasi siswa berhasil DISETUJUI.');
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

        $dispen->update([
            'status_approval' => 'ditolak',
            'disetujui_oleh' => $user ? $user->id : null,
        ]);

        return back()->with('success', 'Surat dispensasi siswa telah DITOLAK.');
    }
}
