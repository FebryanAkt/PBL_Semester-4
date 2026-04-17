<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{
    // Menampilkan halaman profil
    public function edit()
    {
        // Mengambil data user yang sedang login
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Menyimpan perubahan data
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi data
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