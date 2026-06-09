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
                
                {{-- KOTAK PILIH SEMUA --}}
                @if($carts->count() > 0)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 flex items-center gap-4">
                    <input type="checkbox" id="check-all" class="w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500 cursor-pointer">
                    <label for="check-all" class="font-bold text-slate-700 cursor-pointer select-none">Pilih Semua Barang</label>
                </div>
                @endif

                @forelse($carts as $cart)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 sm:p-5 flex flex-wrap sm:flex-nowrap items-start sm:items-center gap-4">
                    
                    {{-- CHECKBOX ITEM --}}
                    <input type="checkbox" class="item-checkbox w-5 h-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500 cursor-pointer mt-2 sm:mt-0" 
                           value="{{ $cart->id }}" 
                           data-price="{{ $cart->item->price }}" 
                           data-qty="{{ $cart->quantity }}">

                    {{-- GAMBAR & JUDUL --}}
                    <a href="{{ route('produk.detail', ['id' => $cart->item->id]) }}" class="flex flex-1 items-start gap-4">
                        <img src="{{ asset('images/' . $cart->item->image) }}" class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl object-cover border border-gray-200 shrink-0">
                        <div class="flex-1">
                            <h3 class="font-bold text-slate-800 text-base sm:text-lg line-clamp-2 leading-tight mb-1">{{ $cart->item->name }}</h3>
                            <p class="text-sm text-emerald-600 font-bold mb-0">Rp{{ number_format($cart->item->price, 0, ',', '.') }}</p>
                        </div>
                    </a>
                    
                    {{-- KONTROL KUANTITAS & HAPUS --}}
                    <div class="flex items-center gap-4 ml-auto w-full sm:w-auto justify-end sm:justify-start pt-2 sm:pt-0">
                        <div class="flex items-center border border-gray-300 rounded-lg bg-white w-max h-8">
                            {{-- Tombol Minus --}}
                            <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="m-0 h-full">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="action" value="minus">
                                <button type="submit" class="px-3 h-full text-gray-600 hover:bg-gray-100 rounded-l-lg font-bold transition-colors flex items-center justify-center {{ $cart->quantity <= 1 ? 'opacity-30 cursor-not-allowed' : '' }}" {{ $cart->quantity <= 1 ? 'disabled' : '' }}>-</button>
                            </form>
                            
                            {{-- Angka --}}
                            <span class="w-10 text-center text-gray-800 font-bold text-sm border-x border-gray-300 h-full flex items-center justify-center bg-gray-50">{{ $cart->quantity }}</span>
                            
                            {{-- Tombol Plus --}}
                            <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="m-0 h-full">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="action" value="plus">
                                <button type="submit" class="px-3 h-full text-gray-600 hover:bg-gray-100 rounded-r-lg font-bold transition-colors flex items-center justify-center">+</button>
                            </form>
                        </div>
                        
                        {{-- Tombol Hapus --}}
                        <form action="{{ route('cart.remove', $cart->id) }}" method="POST" class="h-full">
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

            {{-- Ringkasan Pesanan --}}
            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-24">
                    <h2 class="text-xl font-bold text-slate-800 mb-6">Ringkasan Pesanan</h2>
                    
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-slate-500">
                            <span id="text-subtotal">Subtotal (0 Barang)</span>
                            <span id="text-harga" class="font-medium text-slate-700">Rp0</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4 mb-8 flex justify-between items-center">
                        <span class="font-bold text-slate-800">Total Harga</span>
                        <span id="text-total" class="text-2xl font-black text-emerald-600">Rp0</span>
                    </div>

                    {{-- Tombol Beli --}}
                    <button id="btn-checkout" disabled onclick="submitCheckout()" class="block w-full bg-gray-200 text-gray-400 text-center font-bold py-4 rounded-xl cursor-not-allowed transition shadow-sm">
                        Beli (<span id="btn-count">0</span>)
                    </button>

                    {{-- Form Tersembunyi pengirim ID --}}
                    <form id="checkout-form" action="{{ route('checkout') }}" method="GET" class="hidden"></form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT PENGHITUNG DINAMIS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkAll = document.getElementById('check-all');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const btnCheckout = document.getElementById('btn-checkout');

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        function calculateTotal() {
            let totalHarga = 0;
            let totalBarang = 0;
            let checkedCount = 0;

            itemCheckboxes.forEach(cb => {
                if (cb.checked) {
                    totalHarga += parseInt(cb.dataset.price) * parseInt(cb.dataset.qty);
                    totalBarang += parseInt(cb.dataset.qty);
                    checkedCount++;
                }
            });

            // Update Teks Ringkasan
            document.getElementById('text-subtotal').innerText = `Subtotal (${totalBarang} Barang)`;
            document.getElementById('text-harga').innerText = `Rp${formatRupiah(totalHarga)}`;
            document.getElementById('text-total').innerText = `Rp${formatRupiah(totalHarga)}`;
            document.getElementById('btn-count').innerText = totalBarang;

            // Sinkron Pilih Semua
            if (checkAll) {
                checkAll.checked = (checkedCount === itemCheckboxes.length) && (itemCheckboxes.length > 0);
            }

            // Validasi Tombol Beli
            if (checkedCount > 0) {
                btnCheckout.disabled = false;
                btnCheckout.className = "block w-full bg-slate-900 text-white text-center font-bold py-4 rounded-xl hover:bg-slate-800 transition shadow-lg cursor-pointer";
            } else {
                btnCheckout.disabled = true;
                btnCheckout.className = "block w-full bg-gray-200 text-gray-400 text-center font-bold py-4 rounded-xl cursor-not-allowed transition shadow-sm";
            }
        }

        if (checkAll) {
            checkAll.addEventListener('change', function() {
                itemCheckboxes.forEach(cb => cb.checked = this.checked);
                calculateTotal();
            });
        }

        itemCheckboxes.forEach(cb => cb.addEventListener('change', calculateTotal));
        calculateTotal();

        // Inject & Submit ke Backend
        window.submitCheckout = function() {
            const form = document.getElementById('checkout-form');
            form.innerHTML = ''; 
            
            let hasItems = false;
            itemCheckboxes.forEach(cb => {
                if (cb.checked) {
                    hasItems = true;
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'cart_ids[]'; // Kirim array ID
                    input.value = cb.value;
                    form.appendChild(input);
                }
            });

            if (hasItems) form.submit();
        }
    });
</script>
@endsection