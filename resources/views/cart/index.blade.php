@extends('layouts.app')

@section('title', 'Bekaswit - Keranjang Belanja')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900">Keranjang Belanja</h1>
            <p class="text-slate-500 mt-2">Kamu punya {{ $carts->count() }} barang di keranjang.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <div class="w-full lg:w-2/3 space-y-4">
                @forelse($carts as $cart)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 flex items-center gap-4">
                    <img src="{{ $cart->item->image_url }}" class="w-24 h-24 rounded-xl object-cover border">
                    
                    <div class="flex-1">
                        <h3 class="font-bold text-slate-800 text-lg">{{ $cart->item->name }}</h3>
                        <p class="text-sm text-emerald-600 font-bold">Rp {{ number_format($cart->item->price, 0, ',', '.') }}</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <form action="{{ route('cart.remove', $cart->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-full transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="text-center py-20 bg-white rounded-2xl border-2 border-dashed">
                    <p class="text-gray-400">Keranjangmu masih kosong. <a href="/home" class="text-emerald-600 font-bold">Yuk belanja!</a></p>
                </div>
                @endforelse
            </div>

            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-slate-800 mb-6">Ringkasan Pesanan</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($carts->sum(fn($c) => $c->item->price * $c->quantity), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="border-t pt-4 mb-8 flex justify-between items-center">
                        <span class="font-bold text-slate-800">Total Harga</span>
                        <span class="text-2xl font-black text-emerald-600">Rp {{ number_format($carts->sum(fn($c) => $c->item->price * $c->quantity), 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('checkout') }}" class="block w-full bg-slate-900 text-white text-center font-bold py-4 rounded-xl hover:bg-slate-800 transition shadow-lg">
                        Lanjut ke Pembayaran
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection