@extends('layouts.app')

@section('title', 'Bekaswit - Keranjang Belanja')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900">Keranjang Belanja</h1>
            <p class="text-slate-500 mt-2">Kamu punya {{ $carts->count() }} macam barang di keranjang.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <div class="w-full lg:w-2/3 space-y-4">
                @forelse($carts as $cart)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-5 flex items-start sm:items-center gap-4">
                    
                    {{-- PERBAIKAN: Gambar yang sebelumnya rusak --}}
                    <a href="{{ route('produk.detail', ['id' => $cart->item->id]) }}" class="flex items-start gap-4">
                        <img src="{{ asset('images/' . $cart->item->image) }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-200 shrink-0">
                        
                        <div class="flex-1">
                            <h3 class="font-bold text-slate-800 text-base sm:text-lg line-clamp-2 leading-tight mb-1">{{ $cart->item->name }}</h3>
                            <p class="text-sm text-emerald-600 font-bold mb-3">Rp {{ number_format($cart->item->price, 0, ',', '.') }}</p>
                        
                            {{-- KONTROL KUANTITAS (PLUS MINUS) --}}
                            <div class="flex items-center gap-4">
                                <div class="flex items-center border border-gray-300 rounded-lg bg-white w-max h-8">
                                    {{-- Tombol Minus --}}
                                    <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="m-0 h-full">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="action" value="minus">
                                        <button type="submit" class="px-3 h-full text-gray-600 hover:bg-gray-100 rounded-l-lg font-bold transition-colors flex items-center justify-center {{ $cart->quantity <= 1 ? 'opacity-30 cursor-not-allowed' : '' }}" {{ $cart->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                    </form>
                                    
                                    {{-- Angka Kuantitas --}}
                                    <span class="w-10 text-center text-gray-800 font-bold text-sm border-x border-gray-300 h-full flex items-center justify-center bg-gray-50">{{ $cart->quantity }}</span>
                                    
                                    {{-- Tombol Plus --}}
                                    <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="m-0 h-full">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="action" value="plus">
                                        <button type="submit" class="px-3 h-full text-gray-600 hover:bg-gray-100 rounded-r-lg font-bold transition-colors flex items-center justify-center">+</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </a>

                    {{-- Tombol Hapus (Tong Sampah) --}}
                    <div class="flex flex-col items-end gap-4 h-full justify-start shrink-0">
                        <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 hover:text-red-600 rounded-full transition-colors cursor-pointer" title="Hapus Barang">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>

                </div>
                @empty
                <div class="text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-300">
                    <p class="text-gray-400 mb-2">Keranjangmu masih kosong.</p>
                    <a href="{{ route('home') }}" class="text-emerald-600 font-bold hover:underline">Yuk mulai belanja!</a>
                </div>
                @endforelse
            </div>

            {{-- Ringkasan Pesanan (Tetap Sama) --}}
            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-slate-800 mb-6">Ringkasan Pesanan</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal ({{ $carts->sum('quantity') }} Barang)</span>
                            <span class="font-medium text-slate-700">Rp {{ number_format($carts->sum(fn($c) => $c->item->price * $c->quantity), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 mb-8 flex justify-between items-center">
                        <span class="font-bold text-slate-800">Total Harga</span>
                        <span class="text-2xl font-black text-emerald-600">Rp {{ number_format($carts->sum(fn($c) => $c->item->price * $c->quantity), 0, ',', '.') }}</span>
                    </div>

                    @if($carts->count() > 0)
                    <a href="{{ route('checkout') }}" class="block w-full bg-slate-900 text-white text-center font-bold py-4 rounded-xl hover:bg-slate-800 transition shadow-lg">
                        Lanjut ke Pembayaran
                    </a>
                    @else
                    <button disabled class="block w-full bg-gray-200 text-gray-400 text-center font-bold py-4 rounded-xl cursor-not-allowed">
                        Lanjut ke Pembayaran
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection