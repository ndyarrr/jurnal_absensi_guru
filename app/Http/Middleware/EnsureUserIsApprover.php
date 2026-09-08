<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApprover
{
    /**
     * Handle an incoming request.
     * Ensure only Waka, Waka SDM, and Kepala Sekolah can access approver routes.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (! $user || ! in_array($user->role, ['waka', 'waka_sdm', 'kepala_sekolah'], true)) {
            return redirect()->route('role.dashboard');
        }

        return $next($request);
    }
}
