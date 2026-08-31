<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Update the authenticated user profile (name, no_hp, password, avatar).
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'no_hp'                 => 'nullable|string|max:30',
            'avatar'               => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'current_password'      => 'nullable|string',
            'new_password'          => 'nullable|string|min:6|confirmed',
        ], [
            'name.required'          => 'Nama profil wajib diisi.',
            'avatar.image'           => 'File foto harus berupa gambar.',
            'avatar.mimes'           => 'Foto profil harus berformat JPG, PNG, atau WEBP.',
            'avatar.max'             => 'Ukuran foto maksimal 2 MB.',
            'new_password.min'       => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = $request->user();

        // Password update logic if user filled new_password
        if ($request->filled('new_password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama yang Anda masukkan tidak sesuai.'])->withInput();
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->name = $validated['name'];

        if ($request->boolean('remove_avatar') && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->avatar = null;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        if ($user->guru && !$user->isAdmin()) {
            $user->guru->update([
                'no_hp' => $request->input('no_hp'),
            ]);
        }

        return back()->with('profile_success', 'Profil & password berhasil diperbarui.');
    }
}
