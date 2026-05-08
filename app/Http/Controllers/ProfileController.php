<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; 

class ProfileController extends Controller
{
    // Menampilkan halaman profil
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Menyimpan perubahan data
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        //foto profil
        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], 
            ]);

            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);
            return back()->with('success', 'Foto profil berhasil diperbarui dan foto lama telah dihapus!');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'university' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'phone_number' => ['nullable', 'string', 'max:20'],
        ]);

        // Update data ke database
        $user->update([
            'name' => $request->name,
            'university' => $request->university,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
        ]);

        return back()->with('success', 'Data diri berhasil diperbarui!');
    }
}