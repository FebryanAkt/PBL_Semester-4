@extends('layouts.app')

@section('title', $item->name . ' - Bekaswit')

@section('content')
<div class="max-w-6xl mx-auto px-6 py-10 md:py-16">
    <div class="mb-8">
        <a href="{{ url('/') }}" class="inline-flex items-center text-gray-500 hover:text-bekas-green transition-colors font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Beranda
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">
        
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

            <div class="mt-auto pt-6 flex flex-col sm:flex-row gap-3">
                @if(Auth::check() && Auth::id() != ($item->user_id ?? 1))
                    <a href="{{ route('chat.show', ['id' => $item->user_id ?? 1, 'item_id' => $item->id]) }}" class="w-full sm:w-1/2 bg-white border-2 border-bekas-dark text-bekas-dark hover:bg-gray-50 text-base font-bold py-3.5 rounded-xl flex items-center justify-center gap-2.5 transition-all">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"></path></svg>
                        Chat Penjual
                    </a>
                @elseif(Auth::guest())
                    <a href="{{ route('login') }}" class="w-full sm:w-1/2 bg-white border-2 border-bekas-dark text-bekas-dark hover:bg-gray-50 text-base font-bold py-3.5 rounded-xl flex items-center justify-center gap-2.5 transition-all">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"></path></svg>
                        Login untuk Chat
                    </a>
                @else
                    <button disabled class="w-full sm:w-1/2 bg-gray-100 border-2 border-gray-200 text-gray-400 text-base font-bold py-3.5 rounded-xl flex items-center justify-center gap-2.5 cursor-not-allowed">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"></path></svg>
                        Barang Anda Sendiri
                    </button>
                @endif
                <a href="{{ route('checkout') }}" class="w-full sm:w-1/2 bg-bekas-green hover:bg-green-700 text-white text-base font-bold py-3.5 rounded-xl flex items-center justify-center gap-2.5 transition-all shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Beli Sekarang
                </a>
            </div>
        </div>
    </div>
</div>
@endsection