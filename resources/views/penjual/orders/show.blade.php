@extends('layouts.penjual')

@section('title', 'Bekaswit - Detail Pesanan')

@section('content')
@php
    $deliveryStatus = $transaction->deliveryStatusSummary($sellerId);
    $shippingCode = $sellerItems->pluck('shipping_code')->filter()->first();
    $sellerSubtotal = $sellerItems->sum(fn ($line) => $line->price * $line->quantity);
@endphp
<div class="bg-gray-50/50 min-h-screen py-8 relative overflow-hidden">
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
        style="background-image: radial-gradient(#1f2937 1px, transparent 1px); background-size: 32px 32px;"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 relative z-10">
        {{-- Tombol kembali --}}
        <div class="mb-5">
            <a href="{{ route('penjual.orders.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-bekas-green hover:text-green-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke daftar pesanan
            </a>
        </div>

        {{-- Kartu utama --}}
        <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden">
            {{-- Header --}}
            <div class="p-6 border-b border-gray-100 flex justify-between items-center flex-wrap gap-3">
                <div>
                    <h1 class="text-2xl font-extrabold text-bekas-dark">Detail Pesanan</h1>
                    <p class="text-sm text-gray-500 mt-1">Order ID: <span class="font-mono bg-gray-100 px-2 py-0.5 rounded">{{ $transaction->order_id }}</span></p>
                </div>
                <div class="px-3 py-1.5 rounded-full text-xs font-bold {{ $transaction->status == 'success' ? 'bg-green-100 text-green-700' : ($transaction->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                    {{ $transaction->status == 'success' ? 'LUNAS' : ($transaction->status == 'pending' ? 'MENUNGGU' : 'GAGAL') }}
                </div>
            </div>

            {{-- Informasi Barang & Pembeli (2 kolom) --}}
            <div class="p-6 grid md:grid-cols-2 gap-8">
                {{-- Kolom kiri: Barang --}}
                <div>
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-bekas-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        Informasi Barang
                    </h2>
                    <div class="space-y-3">
                        @foreach($sellerItems as $line)
                            <div class="flex gap-4 rounded-xl border border-gray-100 p-3">
                                <div class="w-20 h-20 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 shrink-0">
                                    @if($line->item?->image)
                                        <img src="{{ asset('images/' . $line->item->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800">{{ $line->item?->name ?? 'Barang dihapus' }}</p>
                                    <p class="text-sm text-gray-500">{{ $line->quantity }} x Rp {{ number_format($line->price, 0, ',', '.') }}</p>
                                    <p class="text-sm font-bold text-bekas-green">Subtotal: Rp {{ number_format($line->price * $line->quantity, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                        <p class="text-right font-bold text-gray-800">Total barangmu: Rp {{ number_format($sellerSubtotal, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Kolom kanan: Pembeli --}}
                <div>
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-bekas-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Informasi Pembeli
                    </h2>
                    <div class="space-y-2 text-sm">
                        <p><span class="font-medium text-gray-600">Nama:</span> {{ $transaction->user->name }}</p>
                        <p><span class="font-medium text-gray-600">Email:</span> {{ $transaction->user->email }}</p>
                        <p><span class="font-medium text-gray-600">Telepon:</span> {{ $transaction->user->phone_number ?? '-' }}</p>
                        <p><span class="font-medium text-gray-600">Alamat:</span> {{ $transaction->user->address ?? '-' }}</p>
                        <p><span class="font-medium text-gray-600">Universitas:</span> {{ $transaction->user->university ?? '-' }}</p>
                    </div>
                </div>
            </div>

            {{-- Form Update Pengiriman (hanya jika transaksi sukses) --}}
            @if($transaction->status == 'success')
            <div class="border-t border-gray-100 p-6 bg-gray-50/30">
                <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2 mb-5">
                    <svg class="w-5 h-5 text-bekas-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Status Pengiriman
                </h2>
                <form action="{{ route('penjual.orders.delivery', $transaction->id) }}" method="POST" class="space-y-5 max-w-md">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Status Kirim</label>
                        <select name="delivery_status" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-bekas-green focus:ring focus:ring-bekas-green/20 p-3">
                            <option value="belum_dikirim" {{ $deliveryStatus == 'belum_dikirim' ? 'selected' : '' }}>Belum Dikirim</option>
                            <option value="dikirim" {{ $deliveryStatus == 'dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
                            <option value="diterima" {{ $deliveryStatus == 'diterima' ? 'selected' : '' }}>Sudah Diterima</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Resi (Opsional)</label>
                        <input type="text" name="shipping_code" value="{{ $shippingCode }}" placeholder="Contoh: JNE-1234567890" class="w-full border-gray-200 rounded-xl shadow-sm focus:border-bekas-green focus:ring focus:ring-bekas-green/20 p-3">
                    </div>
                    <button type="submit" class="bg-bekas-green hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl transition-all shadow-md flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Simpan Perubahan
                    </button>
                </form>
                <div class="mt-4 text-xs text-gray-400">
                    * Status "Sedang Dikirim" akan memberi notifikasi ke pembeli (jika diimplementasikan).
                </div>
            </div>
            @else
            <div class="border-t border-gray-100 p-6 bg-gray-50/30">
                <p class="text-sm text-gray-500">ⓘ Status pengiriman hanya dapat diubah setelah pembayaran dinyatakan sukses.</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
