@extends('layouts.app')

@section('title', 'Kelola Pesanan')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-bekas-dark">Kelola Pesanan</h1>
            <p class="text-gray-500 mt-1">Proses pesanan yang sudah dibayar dan pantau pengirimannya.</p>
        </div>
        <a href="{{ route('barang.saya') }}"
           class="inline-flex items-center justify-center gap-2 rounded-xl border-2 border-gray-200 bg-white px-4 py-2.5 text-sm font-bold text-gray-700 transition hover:border-bekas-dark hover:bg-bekas-dark hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Barang Saya
        </a>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-5 rounded-xl text-center border-2 border-amber-500 bg-amber-500 text-white shadow-md">
                <p class="text-xs uppercase tracking-widest font-bold mb-1 text-amber-100">Pesanan Aktif</p>
                <p class="text-3xl font-black">{{ $pesanan }}</p>
            </div>
            <div class="p-5 rounded-xl text-center border-2 border-orange-200 bg-white text-orange-600">
                <p class="text-xs uppercase tracking-widest font-bold mb-1 opacity-70">Belum Dikirim</p>
                <p class="text-3xl font-black">{{ $belumDikirim }}</p>
            </div>
            <div class="p-5 rounded-xl text-center border-2 border-blue-200 bg-white text-blue-600">
                <p class="text-xs uppercase tracking-widest font-bold mb-1 opacity-70">Sedang Dikirim</p>
                <p class="text-3xl font-black">{{ $sedangDikirim }}</p>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-800">Pesanan Masuk</h2>
        <p class="text-sm text-gray-500">Pesanan yang diterima otomatis keluar dari daftar.</p>
    </div>

    <div class="space-y-5">
        @forelse($transactions as $trx)
            @php
                $sellerItems = $trx->orderLinesForSeller($sellerId);
                $primaryItem = $sellerItems->first()?->item;
                $sellerSubtotal = $sellerItems->sum(fn ($line) => $line->price * $line->quantity);
                $deliveryStatus = $trx->deliveryStatusSummary($sellerId);
            @endphp

            <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition hover:shadow-lg">
                <div class="p-5 md:p-6 flex flex-col lg:flex-row lg:items-center gap-5">
                    <div class="flex flex-1 gap-4 min-w-0">
                        <div class="w-24 h-24 bg-gray-100 rounded-xl overflow-hidden border border-gray-100 shrink-0">
                            @if($primaryItem?->image)
                                <img src="{{ asset('images/' . $primaryItem->image) }}"
                                     alt="{{ $primaryItem->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="text-xs font-bold text-gray-400">{{ $trx->created_at->format('d M Y, H:i') }}</span>
                                <span class="text-xs font-mono rounded-md bg-gray-100 px-2 py-1 text-gray-500">
                                    {{ $trx->order_id ?: 'TRX-' . $trx->id }}
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800 leading-tight">
                                {{ $sellerItems->pluck('item.name')->filter()->join(', ') ?: 'Barang tidak tersedia' }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-2">
                                Pembeli: <span class="font-semibold text-gray-700">{{ $trx->user->name }}</span>
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $sellerItems->sum('quantity') }} barang
                                <span class="mx-1">|</span>
                                <span class="font-bold text-bekas-green">Rp {{ number_format($sellerSubtotal, 0, ',', '.') }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row lg:flex-col gap-3 lg:w-56 shrink-0">
                        @if($deliveryStatus === 'belum_dikirim')
                            <span class="inline-flex items-center justify-center gap-2 rounded-xl border border-orange-200 bg-orange-50 px-4 py-2.5 text-sm font-bold text-orange-600">
                                <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                                Belum Dikirim
                            </span>
                        @else
                            <span class="inline-flex items-center justify-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-bold text-blue-600">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                Sedang Dikirim
                            </span>
                        @endif

                        <a href="{{ route('penjual.orders.show', $trx->id) }}"
                           class="inline-flex items-center justify-center gap-2 rounded-xl bg-bekas-dark px-4 py-2.5 text-sm font-bold text-white transition hover:bg-slate-700">
                            Kelola Pesanan
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="py-16 flex flex-col items-center justify-center text-center bg-white rounded-2xl border-2 border-dashed border-gray-200">
                <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-300 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <p class="text-xl font-bold text-gray-600">Tidak ada pesanan aktif</p>
                <p class="text-sm text-gray-400 mt-1">Pesanan baru akan muncul setelah pembayaran berhasil.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
