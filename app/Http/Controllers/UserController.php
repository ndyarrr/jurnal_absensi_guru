<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display Master Data - Pengguna page matching the exact design mockup.
     */
    public function index(Request $request)
    {
        // 1. Summary Cards Counts
        $adminCount      = User::whereIn('role', ['admin', 'super_admin'])->count();
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

        return view('admin.users.index', compact(
            'users',
            'adminCount',
            'guruPiketCount',
            'guruMapelCount',
            'waliKelasCount',
            'guruList'
        ));
    }

    /**
     * Check authorization for managing a specific target user.
     */
    private function checkUserAuthorization(User $targetUser, string $action = 'mengedit')
    {
        $currentUser = auth()->user();

        // 1. Cannot edit/delete self in Users Management
        if ($currentUser->id === $targetUser->id) {
            return 'Anda tidak dapat ' . $action . ' akun Anda sendiri dari Kelola Pengguna. Silakan gunakan menu Pengaturan Profil di pojok kanan atas.';
        }

        // 2. Regular Admin cannot edit/delete Super Admin
        if (!$currentUser->isSuperAdmin() && $targetUser->isSuperAdmin()) {
            return 'Admin biasa tidak memiliki hak akses untuk ' . $action . ' akun Super Admin.';
        }

        return null;
    }

    /**
     * Store a newly created user in database.
     */
    public function store(Request $request)
    {
        $isSuper = auth()->user()->isSuperAdmin();
        $rolesAllowed = $isSuper
            ? 'admin,super_admin,guru_mengajar,wali_kelas,guru_piket,kepala_sekolah,waka,waka_sdm,satpam'
            : 'admin,guru_mengajar,wali_kelas,guru_piket,kepala_sekolah,waka,waka_sdm,satpam';

        $nonGuruRoles = ['admin', 'super_admin', 'satpam', 'kepala_sekolah', 'waka', 'waka_sdm'];
        $isNonGuruRole = in_array($request->input('role'), $nonGuruRoles, true);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:' . $rolesAllowed,
            'id_guru'  => [
                $isNonGuruRole ? 'nullable' : 'required',
                'integer',
                'exists:guru,id_guru',
                Rule::unique('users', 'id_guru')->whereNotNull('id_guru'),
            ],
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required'     => 'Nama pengguna wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'role.in'           => 'Role tidak valid atau Anda tidak memiliki akses membuat Super Admin.',
            'id_guru.required'  => 'Relasi Profil Guru wajib dipilih untuk role ini.',
            'id_guru.unique'    => 'Guru ini sudah memiliki akun pengguna.',
            'avatar.image'      => 'File harus berupa gambar.',
            'avatar.mimes'      => 'Format gambar harus JPG, PNG, atau WebP.',
            'avatar.max'        => 'Ukuran foto maksimal 2MB.',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Admin & Satpam do not require guru profile mapping
        if ($isNonGuruRole) {
            $validated['id_guru'] = null;
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        } else {
            unset($validated['avatar']);
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
            'avatar_url'  => $user->avatar_url,
            'avatar_initial' => $user->avatar_initial,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        if ($error = $this->checkUserAuthorization($user, 'mengedit')) {
            return redirect()->route('users.index')->with('error', $error);
        }

        $guru = Guru::orderBy('nama_guru')->get();
        return view('admin.users.edit', compact('user', 'guru'));
    }

    /**
     * Update the specified user in database.
     */
    public function update(Request $request, User $user)
    {
        if ($error = $this->checkUserAuthorization($user, 'mengedit')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => $error], 403);
            }
            return redirect()->route('users.index')->with('error', $error);
        }

        $isSuper = auth()->user()->isSuperAdmin();
        $rolesAllowed = $isSuper
            ? 'admin,super_admin,guru_mengajar,wali_kelas,guru_piket,kepala_sekolah,waka,waka_sdm,satpam'
            : 'admin,guru_mengajar,wali_kelas,guru_piket,kepala_sekolah,waka,waka_sdm,satpam';

        $nonGuruRoles = ['admin', 'super_admin', 'satpam', 'kepala_sekolah', 'waka', 'waka_sdm'];
        $isNonGuruRole = in_array($request->input('role'), $nonGuruRoles, true);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
            'role'     => 'required|in:' . $rolesAllowed,
            'id_guru'  => [
                $isNonGuruRole ? 'nullable' : 'required',
                'integer',
                'exists:guru,id_guru',
                Rule::unique('users', 'id_guru')->ignore($user->id)->whereNotNull('id_guru'),
            ],
            'avatar'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'role.in'          => 'Role tidak valid atau Anda tidak memiliki akses memilih Super Admin.',
            'id_guru.required' => 'Relasi Profil Guru wajib dipilih untuk role ini.',
            'id_guru.unique'   => 'Guru ini sudah memiliki akun pengguna lain.',
            'avatar.image'     => 'File harus berupa gambar.',
            'avatar.mimes'     => 'Format gambar harus JPG, PNG, atau WebP.',
            'avatar.max'       => 'Ukuran foto maksimal 2MB.',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($isNonGuruRole) {
            $validated['id_guru'] = null;
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        } else {
            unset($validated['avatar']);
        }

        // Handle avatar removal
        if ($request->boolean('remove_avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = null;
        }

        $user->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => 'Data pengguna berhasil diperbarui']);
        }

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui');
    }

    /**
     * Remove the specified user from database.
     */
    public function destroy(Request $request, User $user)
    {
        if ($error = $this->checkUserAuthorization($user, 'menghapus')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => $error], 403);
            }
            return redirect()->route('users.index')->with('error', $error);
        }

        // Delete avatar file when user is deleted
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => 'Pengguna berhasil dihapus']);
        }

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus');
    }
}
