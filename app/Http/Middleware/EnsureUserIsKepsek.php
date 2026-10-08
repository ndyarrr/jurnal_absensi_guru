<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsKepsek
{
    /**
     * Hanya Kepala Sekolah yang boleh membuka dashboard & halaman persetujuan khusus kepsek.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== 'kepala_sekolah') {
            return redirect()->route('role.dashboard');
        }

        return $next($request);
    }
}