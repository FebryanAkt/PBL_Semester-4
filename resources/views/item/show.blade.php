@extends('layouts.app')

@section('title', $item->name . ' - Bekaswit')

@section('content')
    <div class="max-w-6xl mx-auto px-6 py-10 md:py-16">
        <div class="mb-8">
            <a href="{{ url('/') }}"
                class="inline-flex items-center text-gray-500 hover:text-bekas-green transition-colors font-medium">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Beranda
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row">

            <div class="w-full md:w-1/2 bg-gray-50 p-6 md:p-10">

                @php
                    $images = [];

                    // gambar utama
                    if ($item->image) {
                        $images[] = $item->image;
                    }

                    // gambar tambahan
                    if ($item->images) {

                        $additionalImages = json_decode($item->images, true);

                        if (is_array($additionalImages)) {
                            $images = array_merge($images, $additionalImages);
                        }
                    }
                @endphp

                @if(count($images) > 0)

                    <div x-data="{ activeSlide: 0 }" class="relative w-full">

                        {{-- Gambar --}}
                        <div class="overflow-hidden rounded-2xl">
                            <template x-for="(image, index) in {{ json_encode($images) }}" :key="index">
                                <div x-show="activeSlide === index" class="w-full">
                                    <img :src="'/images/' + image"
                                        class="w-full h-[300px] md:h-[500px] object-cover transition-all duration-500">
                                </div>
                            </template>
                        </div>

                        {{-- Tombol kiri --}}
                        <button @click="activeSlide = activeSlide === 0 ? {{ count($images) - 1 }} : activeSlide - 1"
                            class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white shadow-md rounded-full p-2">
                            &lsaquo;
                        </button>

                        {{-- Tombol kanan --}}
                        <button @click="activeSlide = activeSlide === {{ count($images) - 1 }} ? 0 : activeSlide + 1"
                            class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white shadow-md rounded-full p-2">
                            &rsaquo;
                        </button>

                        {{-- Indicator --}}
                        <div class="flex justify-center mt-4 gap-2">
                            <template x-for="(image, index) in {{ json_encode($images) }}" :key="index">
                                <button @click="activeSlide = index" class="w-3 h-3 rounded-full"
                                    :class="activeSlide === index ? 'bg-bekas-green' : 'bg-gray-300'"></button>
                            </template>
                        </div>

                    </div>

                @else

                    <div class="text-center text-gray-400">
                        <svg class="w-24 h-24 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <p>Tidak ada gambar</p>
                    </div>

                @endif
            </div>

            <div class="w-full md:w-1/2 p-6 md:p-10 flex flex-col">
                <div class="mb-4 flex items-start justify-between gap-4">
                    <span
                        class="{{ $item->status == 'terjual' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }} text-xs font-bold px-3 py-1.5 rounded-md uppercase tracking-wide">
                        {{ $item->status == 'terjual' ? 'Terjual' : 'Tersedia' }}
                    </span>
                    <span
                        class="text-sm text-gray-500 font-medium bg-gray-100 px-3 py-1 rounded-md">{{ $item->category ?? 'Umum' }}</span>
                </div>

                <h1 class="text-2xl md:text-4xl font-extrabold text-gray-900 leading-tight mb-2">{{ $item->name }}</h1>
                <p class="text-3xl font-black text-bekas-green mb-6 border-b border-gray-100 pb-6">Rp{{ number_format($item->price, 0, ',', '.') }}</p>
                    {{ number_format($item->price, 0, ',', '.') }}</p>
                <p class="text-sm text-gray-500 mb-4">
                    Stok tersedia:
                    <span class="font-bold text-green-600">
                        {{ $item->stock }}
                    </span>
                </p>

                <div class="space-y-4 mb-8 flex-grow">
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-medium">{{ $item->location }}</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <p>Dijual oleh: 
                            <a href="{{ route('penjual.lapak', $item->user_id) }}" class="text-green-600 hover:underline">
                                {{ $item->user->name }}
                            </a>
                        </p>
                    </div>

                    <div class="pt-4 mt-4">
                        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Deskripsi Produk</h3>
                        <p class="text-gray-600 leading-relaxed text-sm md:text-base">
                            {{ $item->description ?? 'Tidak ada deskripsi detail untuk produk ini. Silakan hubungi penjual untuk informasi lebih lanjut mengenai kondisi barang.' }}
                        </p>
                    </div>
                </div>

                {{-- Bagian Tombol Aksi (Dibungkus dengan Alpine.js x-data) --}}
                <div class="mt-auto pt-6 flex flex-col gap-4" x-data="{ qty: 1, stock: {{ $item->stock }} }">

                    {{-- UI Pengatur Jumlah / Kuantitas --}}
                    <div class="flex items-center gap-4 border-t border-gray-100 pt-4">
                        <span class="text-sm font-bold text-gray-900 uppercase tracking-wider">Atur Jumlah</span>
                        <div class="flex items-center border border-gray-300 rounded-lg bg-white">
                            <button type="button" @click="if(qty > 1) qty--"
                                class="px-4 py-1.5 text-gray-600 hover:bg-gray-100 rounded-l-lg transition-colors font-bold text-lg">-</button>
                            <input type="number"x-model="qty"min="1"max="{{ $item->stock }}"readonly>
                            <button type="button" @click="if(qty < stock) qty++"
                                class="px-4 py-1.5 text-gray-600 hover:bg-gray-100 rounded-r-lg transition-colors font-bold text-lg">+</button>
                        </div>
                    </div>

                    {{-- Baris Atas: Chat Penjual & Beli Sekarang --}}
                    <div class="flex flex-col sm:flex-row gap-3">
                        @if(Auth::check() && $item->user_id && Auth::id() != $item->user_id)
                            <a href="{{ route('chat.show', ['id' => $item->user_id, 'item_id' => $item->id]) }}"
                                class="w-full sm:w-1/2 bg-white border-2 border-bekas-dark text-bekas-dark hover:bg-gray-50 text-base font-bold py-3.5 rounded-xl flex items-center justify-center gap-2.5 transition-all">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                    <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"></path>
                                </svg>
                                Chat Penjual
                            </a>
                        @elseif(Auth::guest())
                            <a href="{{ route('login') }}"
                                class="w-full sm:w-1/2 bg-white border-2 border-bekas-dark text-bekas-dark hover:bg-gray-50 text-base font-bold py-3.5 rounded-xl flex items-center justify-center gap-2.5 transition-all">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                    <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"></path>
                                </svg>
                                Masuk untuk Chat
                            </a>
                        @else
                            <button disabled
                                class="w-full sm:w-1/2 bg-gray-100 border-2 border-gray-200 text-gray-400 text-base font-bold py-3.5 rounded-xl flex items-center justify-center gap-2.5 cursor-not-allowed">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                    <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"></path>
                                </svg>
                                Barang Anda Sendiri
                            </button>
                        @endif

                        <a :href="'{{ route('checkout', ['item_id' => $item->id]) }}&quantity=' + qty"
                            class="w-full sm:w-1/2 bg-bekas-green hover:bg-green-700 text-white text-base font-bold py-3.5 rounded-xl flex items-center justify-center gap-2.5 transition-all shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            Beli Sekarang
                        </a>
                    </div>

                    {{-- Baris Bawah: Tombol Masukkan Keranjang --}}
                    @if(Auth::check() && $item->user_id && Auth::id() != $item->user_id)
                        <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                            @csrf

                            <input type="hidden" name="item_id" value="{{ $item->id }}">

                            {{-- Input hidden untuk mengirim data kuantitas ke controller --}}
                            <input type="hidden" name="quantity" :value="qty">

                            <button type="submit"
                                class="w-full bg-emerald-50 border-2 border-bekas-green text-bekas-green hover:bg-emerald-100 text-base font-bold py-3.5 rounded-xl flex items-center justify-center gap-2.5 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Masukkan Keranjang
                            </button>
                        </form>
                    @elseif(Auth::guest())
                        <a href="{{ route('login') }}"
                            class="w-full bg-emerald-50 border-2 border-bekas-green text-bekas-green hover:bg-emerald-100 text-base font-bold py-3.5 rounded-xl flex items-center justify-center gap-2.5 transition-all text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Masuk untuk Tambahkan Keranjang
                        </a>
                    @endif

                    @if($item->stock <= 0)
                        <button class="w-full bg-gray-300 text-gray-500 cursor-not-allowed" disabled>
                            Stok Habis
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
