<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Update the authenticated admin profile (name + avatar).
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ], [
            'name.required' => 'Nama profil wajib diisi.',
            'avatar.image'  => 'File foto harus berupa gambar.',
            'avatar.mimes'  => 'Foto profil harus berformat JPG, PNG, atau WEBP.',
            'avatar.max'    => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user = $request->user();
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

        return back()->with('profile_success', 'Profil berhasil diperbarui.');
    }
}
