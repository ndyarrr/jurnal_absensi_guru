<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsWaka
{
    /**
     * Hanya Waka dan Waka Kurikulum yang boleh membuka dashboard & halaman persetujuan izin khusus Waka.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, ['waka', 'waka_kurikulum', 'waka_sdm'], true)) {
            return redirect()->route('role.dashboard');
        }

        return $next($request);
    }
}