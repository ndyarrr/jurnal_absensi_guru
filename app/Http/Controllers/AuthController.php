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
            'role'     => ['nullable', 'string'],
        ], [
            'username.required' => 'NIP / Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cari user berdasarkan kolom 'username' (NIP untuk guru, name untuk admin/lainnya)
        $user = User::where('username', $request->input('username'))->first();

        if (! $user) {
            return back()->withErrors([
                'username' => 'NIP/Username, role atau password salah.',
            ])->onlyInput('username', 'role');
        }

        // Cek jika akun ber-role satpam (Satpam tidak memiliki akses login)
        if ($user->role === 'satpam' || $user->isSatpam() || $request->input('role') === 'satpam') {
            return back()->withErrors([
                'username' => 'Akun Satpam tidak memiliki hak akses untuk login ke sistem ini.',
            ])->onlyInput('username', 'role');
        }

        // Validasi role jika dipilih pada form login
        if ($request->filled('role')) {
            $selectedRole = $request->input('role');
            $isValidRole = false;

            // 1. Direct primary role match or Admin bypass
            if ($user->role === $selectedRole || $user->isAdmin()) {
                $isValidRole = true;
            } else {
                // Resolve id_guru for checking active assignments
                $idGuru = $user->id_guru ?: optional($user->guru)->id_guru;

                if ($selectedRole === 'guru_piket') {
                    if ($idGuru && \Illuminate\Support\Facades\Schema::hasTable('jadwal_piket')) {
                        $isValidRole = \App\Models\JadwalPiket::where('id_guru', $idGuru)->exists();
                    }
                } elseif ($selectedRole === 'wali_kelas') {
                    if ($idGuru) {
                        $isValidRole = \App\Models\Kelas::where('id_guru_wali', $idGuru)->exists();
                    }
                } elseif ($selectedRole === 'guru_mengajar') {
                    $isValidRole = ($idGuru || $user->isGuruMengajar() || $user->isWaliKelas() || $user->isGuruPiket());
                }
            }

            if (! $isValidRole) {
                return back()->withErrors([
                    'username' => 'NIP/Username, role atau password salah.',
                ])->onlyInput('username', 'role');
            }
        }

        // Auth::attempt menggunakan kolom username
        $credentials = [
            'username' => $user->username,
            'password' => $request->input('password'),
        ];

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $authUser = Auth::user();
            if ($authUser->isAdmin()) {
                return redirect()->intended(route('dashboard'))
                    ->with('success', 'Selamat datang, ' . $authUser->name . '!');
            }

            $selectedRole = $request->input('role');
            if (in_array($selectedRole, ['waka', 'waka_kurikulum', 'waka_sdm', 'kepala_sekolah'], true) || in_array($authUser->role, ['waka', 'waka_kurikulum', 'waka_sdm', 'kepala_sekolah'], true)) {
                if (!in_array($selectedRole, ['guru_mengajar', 'wali_kelas', 'guru_piket'], true)) {
                    session()->forget('active_role');
                    return redirect()->intended(route('approver.dashboard'));
                }
            }

            if (in_array($selectedRole, ['guru_mengajar', 'wali_kelas', 'guru_piket'], true)) {
                session(['active_role' => $selectedRole]);
            } else {
                session()->forget('active_role');
            }

            if ($selectedRole === 'guru_piket') {
                return redirect()->intended(route('guru-piket.dashboard'));
            } elseif ($selectedRole === 'wali_kelas') {
                return redirect()->intended(route('wali-kelas.dashboard'));
            }

            return redirect()->intended(route('role.dashboard'));
        }

        return back()->withErrors([
            'username' => 'Username, role atau password salah.',
        ])->onlyInput('username', 'role');
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
