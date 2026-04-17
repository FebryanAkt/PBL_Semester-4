@extends('layouts.guest')

@section('title', 'Daftar - Bekaswit')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center p-6 bg-bekas-bg">
    <div class="w-full max-w-lg bg-white rounded-3xl shadow-xl overflow-hidden p-8 border border-gray-100">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-bekas-dark">Buat Akun Bekaswit</h2>
            <p class="text-gray-500 mt-2 text-sm">Bergabunglah untuk jual & beli barang mahasiswa dengan mudah dan aman.</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-500 p-4 rounded-xl mb-6 text-sm border border-red-100">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" class="space-y-5">
            @csrf
            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <input type="text" id="name" name="name" required placeholder="Budi Santoso" 
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-bekas-green focus:border-transparent transition-all text-sm">
                </div>
            </div>

            <!-- Email Mahasiswa -->
            <div>
                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <input type="email" id="email" name="email" required placeholder="email@gmail.com" 
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-bekas-green focus:border-transparent transition-all text-sm">
                </div>
                <p class="text-xs text-gray-400 mt-1">Disarankan menggunakan email kampus</p>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input type="password" id="password" name="password" required placeholder="Minimal 8 karakter" 
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-bekas-green focus:border-transparent transition-all text-sm">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-bekas-green text-white font-bold py-3.5 rounded-xl shadow-md hover:bg-green-700 hover:shadow-lg transition-all duration-300 mt-2">
                Daftar Sekarang
            </button> <!-- Margin top ditambahkan agar terlihat rapi -->
        </form>

        <div class="mt-8 text-center pt-6 border-t border-gray-100">
            <p class="text-sm text-gray-600">Sudah punya akun? 
                <a href="{{ route('login') }}" class="font-bold text-bekas-green hover:underline">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection