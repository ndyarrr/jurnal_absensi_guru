<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * Login menggunakan username (name) — tanpa perlu email.
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'NIP / Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];

        if (! Auth::attempt($credentials, $request->has('remember'))) {
            return back()->withErrors([
                'username' => 'NIP/Username atau password salah.',
            ])->onlyInput('username');
        }

        $request->session()->regenerate();

        // Role aktif di-reset; tujuan dashboard ditentukan dari role akun.
        session()->forget('active_role');

        // Clear intended URL if it points to an API endpoint (e.g. /pengaturan-wa/api/status)
        $intended = session('url.intended');
        if ($intended && (str_contains($intended, '/api/') || str_contains($intended, '/api-status'))) {
            session()->forget('url.intended');
        }

        $authUser = Auth::user();

        if ($authUser->isAdmin()) {
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang, ' . $authUser->name . '!');
        }

        if (in_array($authUser->role, ['waka', 'waka_kurikulum', 'waka_sdm', 'kepala_sekolah'], true)) {
            return redirect()->intended(route($authUser->role === 'kepala_sekolah' ? 'kepsek.dashboard' : 'approver.dashboard'));
        }

        // Guru, Wali Kelas, Guru Piket, Satpam, dll → diarahkan oleh DashboardController::roleDashboard
        return redirect()->intended(route('role.dashboard'));
    }

    /**
     * Switch active role / view for teachers without re-authenticating.
     */
    public function switchRole(Request $request)
    {
        $targetRole = $request->input('role') ?: $request->query('role');
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $allowedRoles = ['admin', 'guru_mengajar', 'wali_kelas', 'guru_piket'];
        if (!in_array($targetRole, $allowedRoles, true)) {
            return back()->with('error', 'Peran/Tampilan yang dipilih tidak valid.');
        }

        if ($targetRole === 'admin') {
            if (!$user->isAdmin()) {
                return back()->with('error', 'Hanya Admin yang dapat kembali ke Dashboard Utama Admin.');
            }
            session()->forget('active_role');
            return redirect()->route('dashboard')->with('success', 'Kembali ke Dashboard Utama Admin.');
        }

        $isTeacherUser = in_array($user->role, ['guru', 'guru_mengajar', 'wali_kelas', 'guru_piket'], true) || $user->isAdmin() || $user->id_guru || $user->guru;
        if (!$isTeacherUser) {
            return back()->with('error', 'Fitur beralih tampilan ini hanya untuk pengguna akun Guru/Admin.');
        }

        if ($targetRole === 'guru_piket' && !$user->isAdmin() && !$user->sedangBertugasPiket()) {
            if (session('active_role') === 'guru_piket') {
                session()->forget('active_role');
            }
            return back()->with('error', \App\Models\JadwalPiket::pesanTidakBertugas($user->resolveIdGuru()));
        }

        session(['active_role' => $targetRole]);

        $roleNames = [
            'guru_mengajar' => 'Guru Mengajar (Mapel)',
            'wali_kelas'    => 'Wali Kelas',
            'guru_piket'    => 'Guru Piket',
        ];

        $targetRoute = match ($targetRole) {
            'wali_kelas' => 'wali-kelas.dashboard',
            'guru_piket' => 'guru-piket.dashboard',
            default => 'guru-mengajar.dashboard',
        };

        return redirect()->route($targetRoute)->with('success', 'Berhasil beralih ke tampilan ' . ($roleNames[$targetRole] ?? $targetRole) . '.');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}