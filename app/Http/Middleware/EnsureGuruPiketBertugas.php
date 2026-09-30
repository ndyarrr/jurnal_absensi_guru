<?php

namespace App\Http\Middleware;

use App\Models\JadwalPiket;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Tampilan Guru Piket hanya bisa dibuka oleh guru yang terjadwal piket
 * pada HARI dan JAM saat ini (sesuai peran: KBM Pagi 07.00-11.00, KBM Siang 11.00-15.00).
 * Admin tetap boleh masuk untuk memantau.
 */
class EnsureGuruPiketBertugas
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($user->isAdmin() || $user->sedangBertugasPiket()) {
            return $next($request);
        }

        // Jadwal sudah lewat / belum mulai -> keluarkan dari tampilan piket.
        if (session('active_role') === 'guru_piket') {
            session()->forget('active_role');
        }

        $pesan = JadwalPiket::pesanTidakBertugas($user->resolveIdGuru());

        if ($request->expectsJson()) {
            abort(403, $pesan);
        }

        $tujuan = match (true) {
            $user->isSatpam() => 'satpam.dashboard',
            in_array($user->role, ['waka', 'waka_kurikulum', 'waka_sdm', 'kepala_sekolah'], true) => 'approver.dashboard',
            default => 'guru-mengajar.dashboard',
        };

        return redirect()->route($tujuan)->with('error', $pesan);
    }
}