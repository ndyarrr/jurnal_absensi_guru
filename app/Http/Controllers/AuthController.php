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
                'username' => 'Username tidak ditemukan.',
            ])->onlyInput('username', 'role');
        }

        // Validasi role jika dipilih
        if ($request->filled('role') && $user->role !== $request->input('role')) {
            return back()->withErrors([
                'username' => 'Role yang dipilih tidak sesuai dengan akun ini.',
            ])->onlyInput('username', 'role');
        }

        // Auth::attempt tetap menggunakan email di balik layar
        $credentials = [
            'email'    => $user->email,
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

            return redirect()->intended(route('role.dashboard'));
        }

        return back()->withErrors([
            'username' => 'Password yang Anda masukkan salah.',
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
