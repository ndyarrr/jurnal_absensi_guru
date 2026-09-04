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
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // Cari user berdasarkan kolom 'name' (username)
        $user = User::where('name', $request->input('username'))->first();

        if (! $user) {
            return back()->withErrors([
                'username' => 'Username, role atau password salah.',
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
                if (!$idGuru && !empty($user->name)) {
                    $matched = \App\Models\Guru::where('nama_guru', $user->name)->first();
                    if ($matched) {
                        $idGuru = $matched->id_guru;
                    }
                }

                if ($selectedRole === 'guru_piket') {
                    // Only allowed IF teacher is currently assigned in JadwalPiket table
                    if ($idGuru && \Illuminate\Support\Facades\Schema::hasTable('jadwal_piket')) {
                        $isValidRole = \App\Models\JadwalPiket::where('id_guru', $idGuru)->exists();
                    }
                } elseif ($selectedRole === 'wali_kelas') {
                    // Only allowed IF teacher is currently assigned as Wali Kelas in Kelas table
                    if ($idGuru) {
                        $isValidRole = \App\Models\Kelas::where('id_guru_wali', $idGuru)->exists();
                    }
                } elseif ($selectedRole === 'guru_mengajar') {
                    // Any teacher can log in as Guru Mengajar
                    $isValidRole = ($idGuru || $user->isGuruMengajar() || $user->isWaliKelas() || $user->isGuruPiket());
                }
            }

            if (! $isValidRole) {
                return back()->withErrors([
                    'username' => 'Username, role atau password salah.',
                ])->onlyInput('username', 'role');
            }
        }

        // Auth::attempt langsung menggunakan username (kolom name)
        $credentials = [
            'name'     => $user->name,
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
