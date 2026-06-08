@extends('layouts.app')

@section('title', 'Bekaswit - Pesanan Masuk')

@section('content')
<div class="bg-gray-50/50 min-h-screen py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-bekas-dark">Pesanan Masuk</h1>
            <p class="text-gray-500 mt-2">Barang yang telah dibeli pelanggan, segera proses pengiriman.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-bekas-green text-green-700 p-4 mb-6 rounded shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-6">
            @forelse($orders as $order)
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden hover:shadow-lg transition">
                    <div class="p-5 flex flex-wrap md:flex-nowrap gap-4 items-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                            @if($order->item && $order->item->image)
                                <img src="{{ asset('images/'.$order->item->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">📦</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg">{{ $order->item->name ?? 'Barang dihapus' }}</h3>
                            <p class="text-sm text-gray-500">Pembeli: {{ $order->user->name }}</p>
                            <p class="text-sm text-gray-500">Tanggal: {{ $order->created_at->format('d M Y, H:i') }}</p>
                            <p class="text-bekas-green font-bold mt-1">Rp {{ number_format($order->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="flex flex-col gap-2 min-w-[140px]">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold text-center
                                @if($order->status == 'success') bg-green-100 text-green-700
                                @elseif($order->status == 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700 @endif">
                                {{ $order->status == 'success' ? 'Lunas' : ($order->status == 'pending' ? 'Menunggu' : 'Gagal') }}
                            </span>
                            <a href="{{ route('seller.orders.show', $order->id) }}" 
                               class="bg-bekas-dark text-white text-center px-4 py-2 rounded-xl text-sm font-bold hover:bg-gray-800 transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-12 text-center border-2 border-dashed border-gray-200">
                    <p class="text-gray-500">Belum ada pesanan masuk.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection