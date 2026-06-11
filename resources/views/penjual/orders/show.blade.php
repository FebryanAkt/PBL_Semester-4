@extends('layouts.app')

@section('title', 'Detail Pesanan')

@section('content')
@php
    $deliveryStatus = $transaction->deliveryStatusSummary($sellerId);
    $shippingCode = $sellerItems->pluck('shipping_code')->filter()->first();
    $sellerSubtotal = $sellerItems->sum(fn ($line) => $line->price * $line->quantity);
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between mb-8">
        <div>
            <a href="{{ route('penjual.orders.index') }}"
               class="inline-flex items-center gap-2 text-sm font-bold text-bekas-green hover:text-green-700 mb-3">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Kelola Pesanan
            </a>
            <h1 class="text-3xl font-extrabold text-bekas-dark">Detail Pesanan</h1>
            <p class="text-gray-500 mt-1">Periksa barang, pembeli, dan status pengiriman.</p>
        </div>
        <div class="inline-flex self-start items-center gap-2 rounded-xl border border-green-200 bg-green-50 px-4 py-2.5 text-sm font-bold text-green-700">
            <span class="w-2 h-2 rounded-full bg-green-500"></span>
            Pembayaran Lunas
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-5 py-4 sm:px-6">
                    <h2 class="text-lg font-bold text-gray-800">Barang Dipesan</h2>
                    <span class="text-xs font-mono rounded-md bg-gray-100 px-2 py-1 text-gray-500">
                        {{ $transaction->order_id ?: 'TRX-' . $transaction->id }}
                    </span>
                </div>
                <div class="p-5 sm:p-6 space-y-4">
                    @foreach($sellerItems as $line)
                        <div class="flex gap-4 rounded-xl border border-gray-100 p-3">
                            <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden shrink-0">
                                @if($line->item?->image)
                                    <img src="{{ asset('images/' . $line->item->image) }}"
                                         alt="{{ $line->item->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-gray-800">{{ $line->item?->name ?? 'Barang tidak tersedia' }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $line->quantity }} x Rp {{ number_format($line->price, 0, ',', '.') }}</p>
                                <p class="text-sm font-bold text-bekas-green mt-1">
                                    Rp {{ number_format($line->price * $line->quantity, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach

                    <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                        <span class="text-sm font-semibold text-gray-500">Total barangmu</span>
                        <span class="text-xl font-black text-bekas-green">Rp {{ number_format($sellerSubtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </section>

            <section class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="border-b border-gray-100 px-5 py-4 sm:px-6">
                    <h2 class="text-lg font-bold text-gray-800">Informasi Pembeli</h2>
                </div>
                <dl class="grid sm:grid-cols-2 gap-4 p-5 sm:p-6 text-sm">
                    <div>
                        <dt class="text-gray-400">Nama</dt>
                        <dd class="font-semibold text-gray-700 mt-1">{{ $transaction->user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Email</dt>
                        <dd class="font-semibold text-gray-700 mt-1 break-all">{{ $transaction->user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Telepon</dt>
                        <dd class="font-semibold text-gray-700 mt-1">{{ $transaction->user->phone_number ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-400">Universitas</dt>
                        <dd class="font-semibold text-gray-700 mt-1">{{ $transaction->user->university ?? '-' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-gray-400">Alamat</dt>
                        <dd class="font-semibold text-gray-700 mt-1">{{ $transaction->user->address ?? '-' }}</dd>
                    </div>
                </dl>
            </section>
        </div>

        <aside class="lg:col-span-1">
            <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6 lg:sticky lg:top-24">
                <h2 class="text-lg font-bold text-gray-800">Status Pengiriman</h2>
                <p class="text-sm text-gray-500 mt-1 mb-5">Perbarui status sesuai proses pengiriman.</p>

                <form action="{{ route('penjual.orders.delivery', $transaction->id) }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="delivery_status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                        <select id="delivery_status"
                                name="delivery_status"
                                class="w-full rounded-xl border border-gray-200 bg-white p-3 text-sm focus:border-bekas-green focus:outline-none focus:ring-2 focus:ring-bekas-green/20">
                            <option value="belum_dikirim" @selected($deliveryStatus === 'belum_dikirim')>Belum Dikirim</option>
                            <option value="dikirim" @selected($deliveryStatus === 'dikirim')>Sedang Dikirim</option>
                            <option value="diterima">Sudah Diterima</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-2">Status diterima akan menyelesaikan dan menghapus pesanan dari daftar aktif.</p>
                    </div>

                    <div>
                        <label for="shipping_code" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Resi</label>
                        <input id="shipping_code"
                               type="text"
                               name="shipping_code"
                               value="{{ old('shipping_code', $shippingCode) }}"
                               placeholder="Contoh: JNE-1234567890"
                               class="w-full rounded-xl border border-gray-200 p-3 text-sm focus:border-bekas-green focus:outline-none focus:ring-2 focus:ring-bekas-green/20">
                    </div>

                    <button type="submit"
                            class="w-full rounded-xl bg-bekas-green px-4 py-3 text-sm font-bold text-white transition hover:bg-green-700">
                        Simpan Status
                    </button>
                </form>
            </section>
        </aside>
    </div>
</div>
@endsection
