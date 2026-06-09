@extends('layouts.app')

@section('title', 'Barang Saya')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-bekas-dark">Barang Saya</h1>
        <p class="text-gray-500 mt-1">Kelola barang yang kamu jual dan pantau statusnya.</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

{{-- Statistik Card & Filter --}}
    @php
        // Mengambil status filter saat ini dari URL untuk menentukan tombol mana yang aktif
        $currentFilter = request('filter', '');
    @endphp

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            
            {{-- 1. Filter: Total Barang (Aktif jika filter kosong) --}}
            <a href="{{ route('barang.saya') }}" 
               class="p-5 rounded-xl text-center transition-all duration-300 shadow-sm border-2 flex flex-col justify-center items-center
               {{ $currentFilter == '' && !request()->routeIs('penjual.orders.index') 
                    ? 'bg-slate-700 border-slate-700 text-white shadow-md scale-[1.02]' 
                    : 'bg-white border-slate-200 text-slate-600 hover:border-slate-700 hover:text-slate-700 hover:bg-slate-50' }}">
                <p class="text-xs uppercase tracking-widest font-bold mb-1 {{ $currentFilter == '' && !request()->routeIs('penjual.orders.index') ? 'text-slate-200' : 'opacity-70' }}">Total Barang</p>
                <h2 class="text-3xl font-black">{{ $total }}</h2>
            </a>

            {{-- 2. Filter: Tersedia --}}
            <a href="{{ route('barang.saya', ['filter' => 'tersedia']) }}" 
               class="p-5 rounded-xl text-center transition-all duration-300 shadow-sm border-2 flex flex-col justify-center items-center
               {{ $currentFilter == 'tersedia' 
                    ? 'bg-emerald-600 border-emerald-600 text-white shadow-md scale-[1.02]' 
                    : 'bg-white border-emerald-200 text-emerald-600 hover:border-emerald-600 hover:text-emerald-700 hover:bg-emerald-50' }}">
                <p class="text-xs uppercase tracking-widest font-bold mb-1 {{ $currentFilter == 'tersedia' ? 'text-emerald-100' : 'opacity-70' }}">Tersedia</p>
                <h2 class="text-3xl font-black">{{ $tersedia }}</h2>
            </a>

            {{-- 3. Filter: Pesanan (Mengarahkan ke halaman orders) --}}
            <a href="{{ route('penjual.orders.index') }}" 
               class="p-5 rounded-xl text-center transition-all duration-300 shadow-sm border-2 flex flex-col justify-center items-center
               {{ request()->routeIs('penjual.orders.index') 
                    ? 'bg-amber-500 border-amber-500 text-white shadow-md scale-[1.02]' 
                    : 'bg-white border-amber-200 text-amber-600 hover:border-amber-500 hover:text-amber-700 hover:bg-amber-50' }}">
                <p class="text-xs uppercase tracking-widest font-bold mb-1 {{ request()->routeIs('penjual.orders.index') ? 'text-amber-100' : 'opacity-70' }}">Pesanan</p>
                <h2 class="text-3xl font-black">{{ $booking ?? 0 }}</h2>
            </a>

            {{-- 4. Filter: Terjual --}}
            <a href="{{ route('barang.saya', ['filter' => 'terjual']) }}" 
               class="p-5 rounded-xl text-center transition-all duration-300 shadow-sm border-2 flex flex-col justify-center items-center
               {{ $currentFilter == 'terjual' 
                    ? 'bg-rose-500 border-rose-500 text-white shadow-md scale-[1.02]' 
                    : 'bg-white border-rose-200 text-rose-500 hover:border-rose-500 hover:text-rose-600 hover:bg-rose-50' }}">
                <p class="text-xs uppercase tracking-widest font-bold mb-1 {{ $currentFilter == 'terjual' ? 'text-rose-100' : 'opacity-70' }}">Terjual</p>
                <h2 class="text-3xl font-black">{{ $terjual }}</h2>
            </a>

        </div>
    </div>

    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">
            @switch($filter ?? '')
                @case('tersedia') Barang Tersedia @break
                @case('booking') Pesanan Masuk @break
                @case('terjual') Barang Terjual @break
                @default Semua Barang
            @endswitch
        </h2>
    </div>

    {{-- Grid Produk --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($items as $item)
            
        {{-- KARTU PRODUK (Flexbox Diperbaiki) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col h-full hover:shadow-lg transition-all group">
            
            {{-- Bagian Gambar (Aspect Ratio Tetap) --}}
            <div class="relative aspect-[4/3] w-full bg-gray-100 shrink-0 overflow-hidden">
                @if($item->image)
                    <img src="{{ asset('images/' . $item->image) }}" alt="{{ $item->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                @else
                    <div class="absolute inset-0 flex items-center justify-center text-gray-300">📦</div>
                @endif

                {{-- Badge Status --}}
                <span class="absolute top-3 right-3 text-[10px] px-2.5 py-1 rounded-md font-bold uppercase tracking-wider text-white shadow-sm
                    @if($item->status == 'terjual') bg-red-500
                    @elseif($item->status == 'booking') bg-yellow-500
                    @else bg-green-500 @endif">
                    {{ $item->status }}
                </span>

                {{-- Badge Kategori --}}
                <span class="absolute bottom-3 left-3 text-[10px] px-2.5 py-1 rounded-md font-bold bg-white/90 backdrop-blur-sm text-bekas-dark shadow-sm">
                    {{ $item->category ?? 'Lainnya' }}
                </span>
            </div>

            {{-- Bagian Konten --}}
            <div class="p-4 flex flex-col flex-grow">
                {{-- Judul Barang (Memanjang dinamis) --}}
                <h3 class="text-base font-bold text-gray-800 line-clamp-2 leading-tight mb-2 flex-grow" title="{{ $item->name }}">
                    {{ $item->name }}
                </h3>
                
                {{-- Harga --}}
                <p class="text-lg font-black text-bekas-green mb-3">
                    Rp {{ number_format($item->price, 0, ',', '.') }}
                </p>

                {{-- Lokasi --}}
                <div class="flex items-center text-xs text-gray-500 mb-4">
                    <svg class="w-3.5 h-3.5 mr-1.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="truncate">{{ $item->location }}</span>
                </div>

{{-- Tombol Aksi (Otomatis ditaruh di paling bawah) --}}
                <div class="mt-auto pt-4 flex gap-2">
                    
                    {{-- Tombol Lihat (Hover berubah jadi Hijau) --}}
                    <a href="{{ route('produk.detail', $item->id) }}" class="flex-1 bg-white border-2 border-gray-200 text-gray-700 text-sm font-bold py-2 rounded-xl flex items-center justify-center gap-1.5 hover:bg-bekas-green hover:border-bekas-green hover:text-white transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Lihat
                    </a>

                    {{-- Tombol Edit (Hover berubah jadi Warna Gelap/Dark agar beda dengan Lihat) --}}
                    <a href="{{ route('barang.edit', $item->id) }}" class="flex-1 bg-white border-2 border-gray-200 text-gray-700 text-sm font-bold py-2 rounded-xl flex items-center justify-center gap-1.5 hover:bg-bekas-dark hover:border-bekas-dark hover:text-white transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit
                    </a>
                    
                </div>
            </div>

        </div>
        
        @empty
            <div class="col-span-full py-16 flex flex-col items-center justify-center text-gray-400 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                <span class="text-6xl mb-4 grayscale opacity-40">📦</span>
                <p class="text-xl font-bold text-gray-500">Belum ada barang</p>
                <p class="text-sm mt-1 mb-4">Mulai jual barang bekasmu sekarang!</p>
                <a href="{{ route('barang.jual') }}" class="bg-bekas-green text-white px-6 py-2.5 rounded-xl font-bold hover:bg-green-700 transition">Jual Barang</a>
            </div>
        @endforelse

    </div>

</div>

{{-- Float Button --}}
<a href="{{ route('barang.jual') }}"
   class="fixed bottom-6 right-6 bg-bekas-green hover:bg-green-700 text-white w-14 h-14 flex items-center justify-center rounded-full shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 group z-50">
    <svg class="w-6 h-6 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
</a>
@endsection