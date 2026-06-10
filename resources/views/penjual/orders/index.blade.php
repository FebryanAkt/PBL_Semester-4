@extends('layouts.penjual')  {{-- atau 'layouts.app' jika pakai header penjual --}}

@section('title', 'Bekaswit - Pesanan Masuk')

@section('content')
<div class="bg-gray-50/50 min-h-screen py-8 relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
        style="background-image: radial-gradient(#1f2937 1px, transparent 1px); background-size: 32px 32px;"></div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10">
        {{-- Header --}}
        <div class="mb-8 animate-on-scroll">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-bekas-green/10 text-bekas-green text-sm font-bold tracking-wide border border-bekas-green/20 mb-4">
                📦 Pesanan Masuk
            </div>
            <h1 class="text-3xl md:text-4xl font-extrabold text-bekas-dark tracking-tight">Kelola Pesanan</h1>
            <p class="text-gray-500 mt-2 font-medium">Barang yang sudah dibeli pelanggan. Segera proses pengiriman.</p>
        </div>

        @if($transactions->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="text-gray-500 text-lg">Belum ada pesanan masuk.</p>
                <p class="text-gray-400 text-sm mt-1">Pelanggan akan muncul di sini setelah melakukan pembayaran.</p>
            </div>
        @else
            <div class="space-y-5">
                @foreach($transactions as $trx)
                @php
                    $sellerItems = $trx->orderLinesForSeller($sellerId);
                    $primaryItem = $sellerItems->first()?->item;
                    $sellerSubtotal = $sellerItems->sum(fn ($line) => $line->price * $line->quantity);
                    $deliveryStatus = $trx->deliveryStatusSummary($sellerId);
                @endphp
                <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] flex flex-col md:flex-row">
                    {{-- Bagian kiri: info barang & pembeli --}}
                    <div class="p-5 md:p-6 flex-grow flex gap-4 md:gap-6 items-start border-b md:border-b-0 md:border-r border-gray-100">
                        <div class="w-24 h-24 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 shrink-0">
                            @if($primaryItem?->image)
                                <img src="{{ asset('images/' . $primaryItem->image) }}" alt="{{ $primaryItem->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-gray-400 mb-1">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                            <h3 class="text-lg font-bold text-gray-800 leading-tight">
                                {{ $sellerItems->pluck('item.name')->filter()->join(', ') ?: 'Barang dihapus' }}
                            </h3>
                            <p class="text-xs text-gray-400 mt-1">{{ $sellerItems->count() }} jenis barang dalam pesanan ini</p>
                            <p class="text-sm text-gray-500 mt-1">Pembeli: <span class="font-medium">{{ $trx->user->name }}</span></p>
                            <p class="text-sm text-gray-500">Subtotal barangmu: <span class="font-bold text-bekas-green">Rp {{ number_format($sellerSubtotal, 0, ',', '.') }}</span></p>
                        </div>
                    </div>
                    {{-- Bagian kanan: status & aksi --}}
                    <div class="p-5 md:p-6 w-full md:w-64 flex flex-col justify-center bg-gray-50/50 shrink-0">
                        <div class="mb-3">
                            @if($deliveryStatus == 'belum_dikirim')
                                <span class="inline-flex items-center gap-1.5 text-orange-600 bg-orange-50 px-3 py-1.5 rounded-lg text-sm font-bold border border-orange-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Belum Dikirim
                                </span>
                            @elseif($deliveryStatus == 'dikirim')
                                <span class="inline-flex items-center gap-1.5 text-blue-600 bg-blue-50 px-3 py-1.5 rounded-lg text-sm font-bold border border-blue-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Sedang Dikirim
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-green-600 bg-green-50 px-3 py-1.5 rounded-lg text-sm font-bold border border-green-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Sudah Diterima
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('penjual.orders.show', $trx->id) }}" 
                            class="group flex items-center justify-center gap-2 bg-bekas-dark text-white px-4 py-2.5 rounded-xl font-bold transition-all duration-300 shadow-md hover:bg-gray-800 hover:shadow-lg w-full text-sm">
                            Detail Pesanan
                            <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }
    .animate-on-scroll.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
    });
</script>
@endsection
