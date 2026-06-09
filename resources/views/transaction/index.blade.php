@extends('layouts.app')

@section('title', 'Bekaswit - Riwayat Transaksi')

@section('content')
    <div class="bg-gray-50/50 min-h-screen py-12 relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
            style="background-image: radial-gradient(#1f2937 1px, transparent 1px); background-size: 32px 32px;"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 relative z-10">
            <div class="mb-8 md:mb-10 animate-on-scroll">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-bekas-green/10 text-bekas-green text-sm font-bold tracking-wide border border-bekas-green/20 mb-4">
                    🧾 Catatan Belanjamu
                </div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-bekas-dark tracking-tight">Riwayat Transaksi</h1>
                <p class="text-gray-500 mt-2 font-medium">Pantau status pesanan dan lanjutkan pembayaran yang tertunda di
                    sini.</p>
            </div>

            <div class="space-y-6">
                @forelse ($transactions as $trx)
                    <div
                        class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] flex flex-col md:flex-row animate-on-scroll">

                        <div
                            class="p-5 md:p-6 flex-grow flex gap-4 md:gap-6 items-start border-b md:border-b-0 md:border-r border-gray-100">
                            <div
                                class="w-24 h-24 sm:w-32 sm:h-32 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 shrink-0 relative">
                                @if($trx->item && $trx->item->image)
                                    <img src="{{ asset('images/' . $trx->item->image) }}" alt="Barang"
                                        class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="flex flex-col h-full justify-between py-1">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 mb-1 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        {{ $trx->created_at->format('d M Y, H:i') }}
                                    </p>
                                    <h3 class="text-lg font-bold text-gray-800 leading-tight mb-1">
                                        {{ $trx->item ? $trx->item->name : 'Barang telah dihapus' }}
                                    </h3>
                                    <p class="text-sm font-medium text-gray-500">Order ID: <span
                                            class="font-mono bg-gray-100 px-1.5 py-0.5 rounded text-gray-700">{{ $trx->order_id }}</span>
                                    </p>
                                </div>

                                <div class="mt-4">
                                    <span
                                        class="text-[10px] font-bold text-bekas-green uppercase tracking-wider bg-bekas-green/10 px-2.5 py-1 rounded-md">
                                        {{ optional($trx->item)->category ?: 'UMUM' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="p-5 md:p-6 w-full md:w-64 flex flex-col justify-center bg-gray-50/50 shrink-0">
                            <p class="text-sm text-gray-500 mb-1 font-medium">Total Belanja</p>
                            <p class="text-xl font-black text-gray-900 mb-4">Rp {{ number_format($trx->price, 0, ',', '.') }}
                            </p>

                            <div class="mb-5">
                                @if($trx->status == 'pending')
                                    <span
                                        class="inline-flex items-center gap-1.5 text-yellow-600 bg-yellow-50 px-3 py-1.5 rounded-lg text-sm font-bold border border-yellow-200 w-fit">
                                        <span class="w-2 h-2 rounded-full bg-yellow-500 animate-pulse"></span>
                                        Menunggu Pembayaran
                                    </span>
                                @elseif($trx->status == 'success')
                                    <span
                                        class="inline-flex items-center gap-1.5 text-bekas-green bg-bekas-green/10 px-3 py-1.5 rounded-lg text-sm font-bold border border-bekas-green/20 w-fit">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Sudah Dibayar
                                    </span>
                                @elseif($trx->status == 'failed' || $trx->status == 'expired')
                                    <span
                                        class="inline-flex items-center gap-1.5 text-red-600 bg-red-50 px-3 py-1.5 rounded-lg text-sm font-bold border border-red-200 w-fit">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Batal / Kadaluarsa
                                    </span>
                                @endif
                            </div>

                            @if($trx->status == 'pending' && $trx->snap_token)
                                <button onclick="lanjutkanPembayaran('{{ $trx->snap_token }}')"
                                    class="group flex items-center justify-center gap-2 bg-bekas-dark text-white px-4 py-2.5 rounded-xl font-bold transition-all duration-300 shadow-md hover:bg-gray-800 hover:shadow-lg hover:shadow-bekas-dark/30 w-full text-sm">
                                    Bayar Sekarang
                                    <svg class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>
                            @elseif($trx->status == 'success')
                                <div class="flex flex-col gap-2.5 w-full mt-1">

                                    {{-- TOMBOL LIHAT DETAIL INTERAKTIF (Hover Full Color) --}}
                                    <button onclick="openDetailModal({{ $trx->id }})"
                                        class="group flex items-center justify-center gap-2 bg-white border-2 border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl font-bold shadow-sm transition-all duration-300 w-full text-sm hover:-translate-y-1 hover:bg-bekas-dark hover:border-bekas-dark hover:text-white hover:shadow-lg active:scale-95 focus:outline-none focus:ring-2 focus:ring-gray-400/50">

                                        {{-- Ikon Mata (group-hover: warna putih & scale) --}}
                                        <svg class="w-4 h-4 text-gray-400 transition-colors duration-300 group-hover:text-white group-hover:scale-110"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        Lihat Detail
                                    </button>

                                    {{-- Munculkan tombol ini JIKA status pengirimannya sedang "dikirim" --}}
                                    @if($trx->delivery_status == 'dikirim')
                                        {{-- PERBAIKAN: id form sudah ditambahkan --}}
                                        <form id="form-confirm-{{ $trx->id }}" action="{{ route('transaction.confirm-delivery', $trx->id) }}" method="POST"
                                            class="w-full m-0">
                                            @csrf
                                            <button type="button" onclick="openConfirmModal('form-confirm-{{ $trx->id }}')"
                                                class="flex items-center justify-center gap-2.5 bg-bekas-green text-white px-4 py-2.5 rounded-xl font-bold hover:bg-green-700 transition-all duration-300 w-full text-sm shadow-md active:scale-95">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Pesanan Diterima
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        class="py-24 flex flex-col items-center justify-center bg-white rounded-2xl border-2 border-dashed border-gray-200 shadow-sm animate-on-scroll">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Transaksi</h3>
                        <p class="text-gray-500 mb-6 text-center max-w-sm">Kamu belum melakukan pembelian apapun. Yuk mulai
                            cari barang incaranmu sekarang!</p>
                        <a href="{{ route('home') }}"
                            class="bg-bekas-green text-white px-6 py-3 rounded-xl font-bold shadow-md hover:bg-green-700 transition-colors">
                            Mulai Belanja
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- MODAL DETAIL TRANSAKSI --}}
        <div id="detailModal"
            class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-300">
            <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl transform transition-all scale-95 opacity-0"
                id="modalContent">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-xl font-extrabold text-bekas-dark flex items-center gap-2">
                        <svg class="w-6 h-6 text-bekas-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Detail Transaksi
                    </h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>
                <div class="p-6 space-y-5" id="modalBody">
                    {{-- Data akan diisi via JavaScript --}}
                    <div class="animate-pulse flex space-x-3">
                        <div class="bg-gray-200 rounded-xl w-20 h-20"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                        </div>
                    </div>
                </div>
                <div class="p-6 border-t border-gray-100 flex justify-end">
                    <button onclick="closeDetailModal()"
                        class="px-5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        {{-- MODAL KONFIRMASI PENERIMAAN BARANG --}}
        <div id="confirmModal"
            class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm transition-all duration-300">
            <div class="bg-white rounded-3xl max-w-sm w-full shadow-2xl transform transition-all scale-95 opacity-0 p-6 text-center"
                id="confirmModalContent">
                {{-- Ikon Centang Besar --}}
                <div
                    class="w-20 h-20 bg-green-50 text-bekas-green rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-green-100">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h3 class="text-xl font-extrabold text-gray-900 mb-2">Konfirmasi Penerimaan</h3>
                <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                    Apakah Anda yakin barang sudah diterima dengan baik? Uang akan diteruskan ke penjual dan transaksi tidak
                    dapat dibatalkan.
                </p>

                <div class="flex gap-3 justify-center">
                    <button onclick="closeConfirmModal()"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button onclick="submitConfirmForm()"
                        class="flex-1 bg-bekas-green hover:bg-green-700 text-white font-bold py-3 rounded-xl transition-colors shadow-md">
                        Ya, Diterima
                    </button>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

        <script>
            // Fungsi memanggil Midtrans
            function lanjutkanPembayaran(token) {
                window.snap.pay(token, {
                    onSuccess: function (result) {
                        alert("Pembayaran berhasil!");
                        location.reload();
                    },
                    onPending: function (result) {
                        alert("Menunggu pembayaran Anda!");
                    },
                    onError: function (result) {
                        alert("Pembayaran gagal!");
                        location.reload();
                    },
                    onClose: function () {
                        console.log('Popup ditutup sebelum pembayaran selesai');
                    }
                });
            }

            // ===================== MODAL DETAIL =====================
            // Data transaksi akan diambil via AJAX dari endpoint baru
            async function openDetailModal(transactionId) {
                const modal = document.getElementById('detailModal');
                const modalBody = document.getElementById('modalBody');
                modalBody.innerHTML = `
                        <div class="flex justify-center items-center py-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-bekas-green"></div>
                        </div>
                    `;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                // Animasi masuk
                setTimeout(() => {
                    document.getElementById('modalContent').classList.remove('scale-95', 'opacity-0');
                    document.getElementById('modalContent').classList.add('scale-100', 'opacity-100');
                }, 10);

                try {
                    const response = await fetch(`/transaction/detail/${transactionId}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    });
                    if (!response.ok) throw new Error('Gagal mengambil data');
                    const data = await response.json();

                    // Format delivery status badge
                    let deliveryBadge = '';
                    if (data.delivery_status === 'belum_dikirim') {
                        deliveryBadge = '<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-orange-100 text-orange-700 border border-orange-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Belum Dikirim</span>';
                    } else if (data.delivery_status === 'dikirim') {
                        deliveryBadge = '<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-blue-100 text-blue-700 border border-blue-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Sedang Dikirim</span>';
                    } else if (data.delivery_status === 'diterima') {
                        deliveryBadge = '<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold bg-green-100 text-green-700 border border-green-200"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Sudah Diterima</span>';
                    }

                    modalBody.innerHTML = `
                            <div class="flex gap-4 items-start border-b border-gray-100 pb-4">
                                <img src="${data.item_image || 'https://via.placeholder.com/80'}" class="w-20 h-20 rounded-xl object-cover border border-gray-100" onerror="this.src='https://via.placeholder.com/80'">
                                <div>
                                    <h4 class="font-bold text-gray-800 text-lg">${data.item_name}</h4>
                                    <p class="text-sm text-gray-500 mt-0.5">Kategori: ${data.category || 'Umum'}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div class="text-gray-500 font-medium">Tanggal Pembelian</div>
                                <div class="text-gray-800 font-semibold">${data.created_at}</div>

                                <div class="text-gray-500 font-medium">Total Dibayar</div>
                                <div class="text-gray-800 font-bold text-bekas-green">Rp ${data.price_formatted}</div>

                                <div class="text-gray-500 font-medium">Metode Pembayaran</div>
                                <div class="text-gray-800">${data.payment_method || 'Tidak tersedia'}</div>

                                <div class="text-gray-500 font-medium">Status Transaksi</div>
                                <div>
                                    ${data.status === 'success' ? '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700">✔ Lunas</span>' :
                            data.status === 'pending' ? '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">⏳ Menunggu</span>' :
                                '<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">✖ Gagal</span>'}
                                </div>

                                <div class="text-gray-500 font-medium">Status Pengiriman</div>
                                <div>${deliveryBadge}</div>
                            </div>
                            ${data.notes ? `<div class="bg-gray-50 p-3 rounded-xl text-sm text-gray-600 border border-gray-100"><span class="font-bold">Catatan:</span> ${data.notes}</div>` : ''}
                        `;
                } catch (error) {
                    modalBody.innerHTML = `<div class="text-red-500 text-center py-6">Gagal memuat detail. Silakan coba lagi.</div>`;
                }
            }

            function closeDetailModal() {
                const modal = document.getElementById('detailModal');
                const modalContent = document.getElementById('modalContent');
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 200);
            }

            // ===================== MODAL KONFIRMASI =====================
            let currentConfirmFormId = null;

            function openConfirmModal(formId) {
                currentConfirmFormId = formId; // Simpan ID form yang ditekan
                const modal = document.getElementById('confirmModal');
                const modalContent = document.getElementById('confirmModalContent');

                modal.classList.remove('hidden');
                modal.classList.add('flex');

                // Animasi masuk
                setTimeout(() => {
                    modalContent.classList.remove('scale-95', 'opacity-0');
                    modalContent.classList.add('scale-100', 'opacity-100');
                }, 10);
            }

            function closeConfirmModal() {
                const modal = document.getElementById('confirmModal');
                const modalContent = document.getElementById('confirmModalContent');

                // Animasi keluar
                modalContent.classList.remove('scale-100', 'opacity-100');
                modalContent.classList.add('scale-95', 'opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                    currentConfirmFormId = null; // Reset form ID
                }, 200);
            }

            function submitConfirmForm() {
                if (currentConfirmFormId) {
                    // Jika tombol "Ya, Diterima" ditekan, submit formnya secara otomatis
                    document.getElementById(currentConfirmFormId).submit();
                }
            }

            // Animasi Scroll (Sama dengan Home)
            document.addEventListener('DOMContentLoaded', function () {
                const observerOptions = { root: null, rootMargin: '0px', threshold: 0.1 };
                const observer = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('is-visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, observerOptions);

                document.querySelectorAll('.animate-on-scroll').forEach((el) => {
                    observer.observe(el);
                });
            });
        </script>

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
    </div>
@endsection