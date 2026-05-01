@extends('layouts.app')

@section('title', $item->name . ' - Bekaswit')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10 md:py-16">
    {{-- Breadcrumb & Tombol Kembali --}}
    <div class="mb-8">
        <a href="{{ url('/') }}" class="inline-flex items-center text-gray-500 hover:text-bekas-green transition-colors font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Beranda
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
        
        {{-- Sisi Kiri: Gambar Produk --}}
        <div class="w-full md:w-1/2 bg-gray-50 p-6 md:p-10 flex items-center justify-center min-h-[300px] md:min-h-[500px]">
            @if($item->image)
                <img src="{{ asset('images/' . $item->image) }}" 
                alt="{{ $item->name }}" 
                class="object-cover w-full h-full group-hover:scale-110 transition-transform duration-500">
            @else
                <div class="text-center text-gray-400">
                    <svg class="w-24 h-24 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <p>Tidak ada gambar</p>
                </div>
            @endif
        </div>

        {{-- Sisi Kanan: Detail & Info --}}
        <div class="w-full md:w-1/2 p-6 md:p-10 flex flex-col">
            <div class="mb-4 flex items-start justify-between gap-4">
                <span class="{{ $item->status == 'terjual' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} text-xs font-bold px-3 py-1.5 rounded-md uppercase tracking-wide">
                    {{ $item->status == 'terjual' ? 'Terjual' : 'Tersedia' }}
                </span>
                <span class="text-sm text-gray-500 font-medium bg-gray-100 px-3 py-1 rounded-md">{{ $item->category ?? 'Umum' }}</span>
            </div>

            <h1 class="text-2xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-2">{{ $item->name }}</h1>
            <p class="text-3xl font-black text-bekas-green mb-6 border-b border-gray-100 pb-6">Rp {{ number_format($item->price, 0, ',', '.') }}</p>

            <div class="space-y-4 mb-8 flex-grow">
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="font-medium">{{ $item->location }}</span>
                </div>
                <div class="flex items-center text-gray-600">
                    <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    <span class="font-medium">Dijual oleh: <span class="text-gray-900 font-bold">Mahasiswa UB</span></span>
                </div>
                
                <div class="pt-4 mt-4">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Deskripsi Produk</h3>
                    <p class="text-gray-600 leading-relaxed text-sm md:text-base">
                        {{ $item->description ?? 'Tidak ada deskripsi detail untuk produk ini. Silakan hubungi penjual untuk informasi lebih lanjut mengenai kondisi barang.' }}
                    </p>
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="mt-auto pt-6">
                <a href="https://wa.me/6281234567890" target="_blank" class="w-full bg-bekas-dark hover:bg-gray-800 text-white text-lg font-bold py-4 rounded-xl flex items-center justify-center gap-3 transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">
                    <svg viewBox="0 0 448 512" class="w-6 h-6 fill-current"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zM223.9 413.3c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 334.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 54.3 0 105.4 21.2 143.8 59.6 38.4 38.4 59.6 89.5 59.6 143.8 0 101.7-82.8 184.5-184.6 184.5zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
                    Chat Penjual Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection