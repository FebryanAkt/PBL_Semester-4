@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            
            <div class="bg-bekas-dark px-8 py-6 border-b border-gray-700">
                <h2 class="text-2xl font-extrabold text-white">Lengkapi Data Diri</h2>
                <p class="text-gray-300 text-sm mt-1">Pastikan data yang kamu masukkan sesuai untuk mempermudah komunikasi saat bertransaksi.</p>
            </div>

            <div class="p-8">
                
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center gap-3" role="alert">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="block sm:inline font-medium text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-bekas-green focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white text-gray-800 placeholder-gray-400">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Asal Universitas</label>
                        <input type="text" name="university" value="{{ old('university', $user->university) }}" placeholder="Contoh: Universitas Brawijaya" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-bekas-green focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white text-gray-800 placeholder-gray-400">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor HP / WhatsApp</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-medium">+62</span>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" placeholder="81234567890" 
                                class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-bekas-green focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white text-gray-800 placeholder-gray-400">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap (Kos/Rumah)</label>
                        <textarea name="address" rows="4" placeholder="Masukkan alamat lengkapmu di sini untuk keperluan COD..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-bekas-green focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white text-gray-800 placeholder-gray-400 resize-y">{{ old('address', $user->address) }}</textarea>
                    </div>

                    <div class="pt-4 mt-2 border-t border-gray-100">
                        <button type="submit" 
                            class="w-full bg-bekas-green hover:bg-green-700 text-white font-bold py-3.5 px-4 rounded-xl transition-all duration-300 transform hover:-translate-y-1 shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-green-700/30 flex justify-center items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Profil Saya
                        </button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
@endsection