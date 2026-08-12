<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display Master Data - Pengguna page matching the exact design mockup.
     */
    public function index(Request $request)
    {
        // 1. Summary Cards Counts
        $adminCount      = User::where('role', 'admin')->count();
        $guruPiketCount  = User::where('role', 'guru_piket')->count();
        $guruMapelCount  = User::where('role', 'guru_mengajar')->count();
        $waliKelasCount  = User::where('role', 'wali_kelas')->count();

        // 2. Query Builder with Search & Role Filters
        $query = User::with('guru');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->orderBy('id', 'asc')->paginate(8)->withQueryString();

        // 3. Guru list for create/edit select dropdowns
        $guruList = Guru::orderBy('nama_guru')->get();

        return view('users.index', compact(
            'users',
            'adminCount',
            'guruPiketCount',
            'guruMapelCount',
            'waliKelasCount',
            'guruList'
        ));
    }

    /**
     * Store a newly created user in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:admin,guru_mengajar,wali_kelas,guru_piket,kepala_sekolah,waka,waka_sdm,satpam',
            'id_guru'  => [
                'nullable',
                'integer',
                'exists:guru,id_guru',
                Rule::unique('users', 'id_guru')->whereNotNull('id_guru'),
            ],
        ], [
            'name.required'     => 'Nama pengguna wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.min'      => 'Password minimal 6 karakter.',
            'id_guru.unique'    => 'Guru ini sudah memiliki akun pengguna.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Admin & Satpam do not require guru profile mapping
        if (in_array($validated['role'], ['admin', 'satpam'], true)) {
            $validated['id_guru'] = null;
        }

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan');
    }

    /**
     * Display details of a specific user.
     */
    public function show(User $user)
    {
        $user->load('guru.mapel');
        return response()->json([
            'id'          => $user->id,
            'name'        => $user->name,
            'email'       => $user->email,
            'role'        => $user->role,
            'role_label'  => $user->role_label,
            'id_guru'     => $user->id_guru,
            'nama_guru'   => optional($user->guru)->nama_guru ?? '-',
            'created_at'  => $user->created_at ? $user->created_at->format('d-m-Y H:i') : '-',
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $guru = Guru::orderBy('nama_guru')->get();
        return view('users.edit', compact('user', 'guru'));
    }

    /**
     * Update the specified user in database.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6',
            'role'     => 'required|in:admin,guru_mengajar,wali_kelas,guru_piket,kepala_sekolah,waka,waka_sdm,satpam',
            'id_guru'  => [
                'nullable',
                'integer',
                'exists:guru,id_guru',
                Rule::unique('users', 'id_guru')->ignore($user->id)->whereNotNull('id_guru'),
            ],
        ], [
            'id_guru.unique' => 'Guru ini sudah memiliki akun pengguna lain.',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if (in_array($validated['role'], ['admin', 'satpam'], true)) {
            $validated['id_guru'] = null;
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui');
    }

    /**
     * Remove the specified user from database.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus');
    }
}
