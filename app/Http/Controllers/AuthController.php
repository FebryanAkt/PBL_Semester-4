<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek ke dalam database (table users)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Jika berhasil, arahkan ke home dengan notifikasi
            return redirect()->route('home')->with('success', 'Berhasil masuk! Selamat datang kembali.');
        }

        // Jika salah, kembali ke form login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function processRegister(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Menyimpan data User baru ke dalam tabel 'users' di Database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Kata sandi di-enkripsi
        ]);

        // Langsung login otomatis setelah berhasil daftar
        Auth::login($user);

        // Arahkan ke tampilan "Home" dengan notifikasi sukses
        return redirect()->route('home')->with('success', 'Akun berhasil dibuat! Selamat datang di Bekaswit.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Setelah logout, arahkan kembali ke Landing Page (Guest)
        return redirect('/');
    }
}
