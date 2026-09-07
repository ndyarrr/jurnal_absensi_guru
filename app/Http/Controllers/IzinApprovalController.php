<?php

namespace App\Http\Controllers;

use App\Models\IzinGuru;
use Illuminate\Http\Request;

class IzinApprovalController extends Controller
{
    /**
     * Tampilkan Halaman Persetujuan Izin Guru (Public/Token-based)
     */
    public function show($id, $token)
    {
        $izin = IzinGuru::with('guru')
            ->where('id_izin_guru', $id)
            ->where('approval_token', $token)
            ->firstOrFail();

        return view('izin.approval', compact('izin'));
    }

    /**
     * Setujui Izin Guru
     */
    public function approve($id, $token)
    {
        $izin = IzinGuru::where('id_izin_guru', $id)
            ->where('approval_token', $token)
            ->firstOrFail();

        if ($izin->status_approval !== 'pending') {
            return back()->with('info', 'Permohonan izin ini sudah diproses sebelumnya (Status: ' . ucfirst($izin->status_approval) . ').');
        }

        $izin->update([
            'status_approval' => 'disetujui',
            'disetujui_oleh' => auth()->id() ?? null,
        ]);

        return back()->with('success', 'Permohonan izin berhasil DISETUJUI.');
    }

    /**
     * Tolak Izin Guru
     */
    public function reject(Request $request, $id, $token)
    {
        $izin = IzinGuru::where('id_izin_guru', $id)
            ->where('approval_token', $token)
            ->firstOrFail();

        if ($izin->status_approval !== 'pending') {
            return back()->with('info', 'Permohonan izin ini sudah diproses sebelumnya (Status: ' . ucfirst($izin->status_approval) . ').');
        }

        $catatan = $request->input('catatan_approver', 'Ditolak oleh Waka/Kepsek.');

        $izin->update([
            'status_approval' => 'ditolak',
            'catatan_approver' => $catatan,
            'disetujui_oleh' => auth()->id() ?? null,
        ]);

        return back()->with('success', 'Permohonan izin telah DITOLAK.');
    }
}
