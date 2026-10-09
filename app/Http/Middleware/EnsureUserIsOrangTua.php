<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOrangTua
{
    /**
     * Hanya akun dengan role orang tua yang boleh membuka halaman portal orang tua.
     * Admin tidak dikecualikan karena portal ini bergantung pada anak yang ditautkan ke akun.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->isOrangTua()) {
            return redirect()->route('role.dashboard');
        }

        return $next($request);
    }
}