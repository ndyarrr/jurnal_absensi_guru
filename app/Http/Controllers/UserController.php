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
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%")
                  ->orWhereHas('guru', function ($g) use ($search) {
                      $g->where('nama_guru', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->orderBy('id', 'asc')->paginate(8)->withQueryString();

        // 3. Guru list for create/edit select dropdowns
        $guruList = Guru::with('user')->orderBy('nama_guru')->get();

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
            ? 'admin,super_admin,guru_mengajar,wali_kelas,guru_piket,kepala_sekolah,waka,waka_kurikulum,satpam'
            : 'admin,guru_mengajar,wali_kelas,guru_piket,kepala_sekolah,waka,waka_kurikulum,satpam';

        $requiredGuruRoles = ['guru_mengajar', 'wali_kelas', 'guru_piket'];
        $isRequiredGuruRole = in_array($request->input('role'), $requiredGuruRoles, true);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:' . $rolesAllowed,
            'id_guru'  => [
                $isRequiredGuruRole ? 'required' : 'nullable',
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

        $rawPassword = $validated['password'];
        $validated['password'] = Hash::make($rawPassword);
        $validated['plain_password'] = $rawPassword;

        if (empty($validated['id_guru'])) {
            $validated['id_guru'] = null;
        }

        // Roles yang hanya boleh 1 user
        $singletonRoles = ['waka', 'waka_kurikulum', 'kepala_sekolah'];
        if (in_array($validated['role'], $singletonRoles)) {
            $roleLabels = ['waka' => 'Waka', 'waka_kurikulum' => 'Waka Kurikulum', 'kepala_sekolah' => 'Kepala Sekolah'];
            $exists = User::where('role', $validated['role'])->exists();
            if ($exists) {
                return back()->withInput()->withErrors([
                    'role' => 'Role ' . ($roleLabels[$validated['role']] ?? $validated['role']) . ' sudah memiliki akun. Hanya boleh 1 akun per role ini.',
                ]);
            }
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
            'id'             => $user->id,
            'name'           => $user->name,
            'username'       => $user->username,
            'role'           => $user->role,
            'role_label'     => $user->role_label,
            'id_guru'        => $user->id_guru,
            'nama_guru'      => optional($user->guru)->nama_guru ?? '-',
            'nip'          => optional($user->guru)->nip ?? '',
            'plain_password' => $user->plain_password ?? optional($user->guru)->nip ?? '',
            'created_at'     => $user->created_at ? $user->created_at->format('d-m-Y H:i') : '-',
            'avatar_url'     => $user->avatar_url,
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
            ? 'admin,super_admin,guru_mengajar,wali_kelas,guru_piket,kepala_sekolah,waka,waka_kurikulum,satpam'
            : 'admin,guru_mengajar,wali_kelas,guru_piket,kepala_sekolah,waka,waka_kurikulum,satpam';

        $requiredGuruRoles = ['guru_mengajar', 'wali_kelas', 'guru_piket'];
        $isRequiredGuruRole = in_array($request->input('role'), $requiredGuruRoles, true);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'password' => 'nullable|string|min:6',
            'role'     => 'required|in:' . $rolesAllowed,
            'id_guru'  => [
                $isRequiredGuruRole ? 'required' : 'nullable',
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
            $rawPassword = $validated['password'];
            $validated['password'] = Hash::make($rawPassword);
            $validated['plain_password'] = $rawPassword;
        } else {
            unset($validated['password']);
        }

        if (empty($validated['id_guru'])) {
            $validated['id_guru'] = null;
        }

        // Roles yang hanya boleh 1 user — cek jika role BERUBAH ke singleton role
        $singletonRoles = ['waka', 'waka_kurikulum', 'kepala_sekolah'];
        if (in_array($validated['role'], $singletonRoles) && $validated['role'] !== $user->role) {
            $roleLabels = ['waka' => 'Waka', 'waka_kurikulum' => 'Waka Kurikulum', 'kepala_sekolah' => 'Kepala Sekolah'];
            $exists = User::where('role', $validated['role'])->where('id', '!=', $user->id)->exists();
            if ($exists) {
                $errMsg = 'Role ' . ($roleLabels[$validated['role']] ?? $validated['role']) . ' sudah memiliki akun. Hanya boleh 1 akun per role ini.';
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['error' => $errMsg], 422);
                }
                return back()->withInput()->withErrors(['role' => $errMsg]);
            }
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

    /**
     * Remove multiple users from database at once.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return response()->json(['error' => 'Tidak ada pengguna yang dipilih untuk dihapus.'], 400);
        }

        $currentUser = auth()->user();
        $isSuperAdmin = $currentUser->isSuperAdmin();

        // Query targeted users, excluding self
        $query = User::whereIn('id', $ids)->where('id', '!=', $currentUser->id);

        if (!$isSuperAdmin) {
            // Regular admins cannot delete Super Admins
            $query->where('role', '!=', 'super_admin');
        }

        $usersToDelete = $query->get();
        $deletedCount = 0;

        foreach ($usersToDelete as $u) {
            if ($u->avatar && Storage::disk('public')->exists($u->avatar)) {
                Storage::disk('public')->delete($u->avatar);
            }
            $u->delete();
            $deletedCount++;
        }

        if ($deletedCount === 0) {
            return response()->json(['error' => 'Gagal menghapus pengguna. Akun Anda sendiri atau Super Admin tidak dapat dihapus.'], 400);
        }

        return response()->json([
            'success' => "{$deletedCount} pengguna berhasil dihapus.",
            'deleted_count' => $deletedCount,
            'deleted_ids' => $usersToDelete->pluck('id')->toArray(),
        ]);
    }
}
