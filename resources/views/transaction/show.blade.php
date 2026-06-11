@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
<div class="bg-gray-50/50 min-h-screen py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        <div class="mb-6">
            <a href="{{ route('seller.orders.index') }}" class="inline-flex items-center gap-2 text-bekas-green hover:underline">
                ← Kembali ke Daftar Pesanan
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-2xl font-bold text-bekas-dark">Detail Pesanan</h2>
                <p class="text-gray-500 text-sm">Order ID: {{ $transaction->order_id }}</p>
            </div>

            <div class="p-6 space-y-6">
                {{-- Informasi Barang --}}
                <div class="flex gap-4">
                    <div class="w-28 h-28 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                        @if($transaction->item && $transaction->item->image)
                            <img src="{{ asset('images/'.$transaction->item->image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300">📦</div>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold text-xl">{{ $transaction->item->name ?? 'Barang tidak tersedia' }}</h3>
                        <p class="text-gray-600 mt-1">Kategori: {{ $transaction->item->category ?? '-' }}</p>
                        <p class="text-bekas-green font-bold text-lg mt-2">Rp {{ number_format($transaction->price, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Informasi Pembeli --}}
                <div class="bg-gray-50 p-4 rounded-xl">
                    <h4 class="font-semibold text-gray-700 mb-2">Informasi Pembeli</h4>
                    <p><span class="font-medium">Nama:</span> {{ $transaction->user->name }}</p>
                    <p><span class="font-medium">Email:</span> {{ $transaction->user->email }}</p>
                    <p><span class="font-medium">Telepon:</span> {{ $transaction->user->phone_number ?? '-' }}</p>
                    <p><span class="font-medium">Alamat:</span> {{ $transaction->user->address ?? '-' }}</p>
                </div>

                {{-- Status Transaksi --}}
                <div class="flex justify-between items-center border-t pt-4">
                    <div>
                        <p class="text-sm text-gray-500">Status Pembayaran</p>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-bold
                            @if($transaction->status == 'success') bg-green-100 text-green-700
                            @elseif($transaction->status == 'pending') bg-yellow-100 text-yellow-700
                            @else bg-red-100 text-red-700 @endif">
                            {{ $transaction->status == 'success' ? 'Sudah Dibayar' : ($transaction->status == 'pending' ? 'Menunggu Pembayaran' : 'Gagal') }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status Pengiriman</p>
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-bold
                            @if($transaction->delivery_status == 'dikirim') bg-blue-100 text-blue-700
                            @elseif($transaction->delivery_status == 'diterima') bg-green-100 text-green-700
                            @else bg-orange-100 text-orange-700 @endif">
                            {{ $transaction->delivery_status == 'dikirim' ? 'Sedang Dikirim' : ($transaction->delivery_status == 'diterima' ? 'Sudah Diterima' : 'Belum Dikirim') }}
                        </span>
                    </div>
                </div>

                {{-- Form Update Pengiriman (hanya jika pembayaran sukses) --}}
                @if($transaction->status == 'success')
                <div class="border-t pt-4">
                    <h4 class="font-semibold text-gray-700 mb-3">Update Status Pengiriman</h4>
                    <form action="{{ route('seller.orders.delivery', $transaction->id) }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <select name="delivery_status" class="border border-gray-300 rounded-xl px-4 py-2 focus:ring-bekas-green focus:border-bekas-green">
                            <option value="belum_dikirim" {{ $transaction->delivery_status == 'belum_dikirim' ? 'selected' : '' }}>Belum Dikirim</option>
                            <option value="dikirim" {{ $transaction->delivery_status == 'dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
                            <option value="diterima" {{ $transaction->delivery_status == 'diterima' ? 'selected' : '' }}>Sudah Diterima</option>
                        </select>
                        <input type="text" name="shipping_code" placeholder="Nomor Resi (opsional)" class="border border-gray-300 rounded-xl px-4 py-2 flex-1">
                        <button type="submit" class="bg-bekas-green text-white px-6 py-2 rounded-xl font-bold hover:bg-green-700 transition">
                            Update
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection