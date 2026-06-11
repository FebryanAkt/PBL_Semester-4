@extends('layouts.app')

@section('title', 'Masuk - Bekaswit')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center p-6 bg-bekas-bg">
    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl overflow-hidden p-8 border border-gray-100">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-extrabold text-bekas-dark">Masuk ke Akunmu</h2>
            <p class="text-gray-500 mt-2 text-sm">Selamat datang kembali! Yuk lanjut cari barang idamanmu.</p>
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

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Pos-el</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                    </div>
                    <input type="email" id="email" name="email" required placeholder="email@gmail.com" 
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-bekas-green focus:border-transparent transition-all text-sm">
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Kata Sandi</label>
                <div class="relative" x-data="{ show: false }">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    </div>
                    <input x-bind:type="show ? 'text' : 'password'" id="password" name="password" required placeholder="••••••••" 
                        class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-bekas-green focus:border-transparent transition-all text-sm">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="show" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>
                <div class="flex justify-end mt-2">
                    <a href="#" class="text-xs font-semibold text-bekas-green hover:underline">Lupa Kata Sandi?</a>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-bekas-green text-white font-bold py-3.5 rounded-xl shadow-md hover:bg-green-700 hover:shadow-lg transition-all duration-300">
                Masuk Sekarang
            </button>
        </form>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600">Belum punya akun? 
                <a href="{{ route('register') }}" class="font-bold text-bekas-green hover:underline">Daftar di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection