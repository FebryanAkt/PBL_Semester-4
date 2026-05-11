@extends('layouts.app')

@section('title', 'Bekaswit - Pembayaran')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        <!-- Breadcrumb & Header -->
        <div class="mb-8">
            <a href="{{ route('home') }}" class="text-sm font-medium text-gray-500 hover:text-bekas-green flex items-center gap-2 mb-2 w-max">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <h1 class="text-2xl md:text-3xl font-extrabold text-bekas-dark">Selesaikan Pembayaran</h1>
            <p class="text-gray-500 text-sm mt-1">Pilih metode pembayaran yang paling nyaman untukmu.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Left Column: Payment Methods -->
            <div class="w-full lg:w-2/3 space-y-6">
                
                <!-- Payment Options -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 md:p-8">
                    <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-bekas-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Pilih Metode Pembayaran
                    </h2>

                    <!-- E-Wallet Section -->
                    <div class="mb-6">
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">E-Wallet</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- GoPay -->
                            <label class="relative flex items-center gap-4 p-4 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-bekas-green/50 hover:bg-gray-50 transition-colors">
                                <input type="radio" name="payment_method" value="gopay" class="peer w-5 h-5 text-bekas-green border-gray-300 focus:ring-bekas-green" checked>
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800">GoPay</span>
                                </div>
                                <div class="absolute right-4">
                                    <div class="w-12 h-6 bg-blue-500 rounded text-white text-[10px] flex items-center justify-center font-bold">GOPAY</div>
                                </div>
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-bekas-green rounded-xl pointer-events-none transition-colors"></div>
                            </label>

                            <!-- OVO -->
                            <label class="relative flex items-center gap-4 p-4 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-bekas-green/50 hover:bg-gray-50 transition-colors">
                                <input type="radio" name="payment_method" value="ovo" class="peer w-5 h-5 text-bekas-green border-gray-300 focus:ring-bekas-green">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800">OVO</span>
                                </div>
                                <div class="absolute right-4">
                                    <div class="w-12 h-6 bg-purple-600 rounded text-white text-[10px] flex items-center justify-center font-bold">OVO</div>
                                </div>
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-bekas-green rounded-xl pointer-events-none transition-colors"></div>
                            </label>

                            <!-- Dana -->
                            <label class="relative flex items-center gap-4 p-4 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-bekas-green/50 hover:bg-gray-50 transition-colors">
                                <input type="radio" name="payment_method" value="dana" class="peer w-5 h-5 text-bekas-green border-gray-300 focus:ring-bekas-green">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800">DANA</span>
                                </div>
                                <div class="absolute right-4">
                                    <div class="w-12 h-6 bg-blue-400 rounded text-white text-[10px] flex items-center justify-center font-bold">DANA</div>
                                </div>
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-bekas-green rounded-xl pointer-events-none transition-colors"></div>
                            </label>
                            
                            <!-- ShopeePay -->
                            <label class="relative flex items-center gap-4 p-4 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-bekas-green/50 hover:bg-gray-50 transition-colors">
                                <input type="radio" name="payment_method" value="shopeepay" class="peer w-5 h-5 text-bekas-green border-gray-300 focus:ring-bekas-green">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800">ShopeePay</span>
                                </div>
                                <div class="absolute right-4">
                                    <div class="w-12 h-6 bg-orange-500 rounded text-white text-[10px] flex items-center justify-center font-bold">SHOPEE</div>
                                </div>
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-bekas-green rounded-xl pointer-events-none transition-colors"></div>
                            </label>
                        </div>
                    </div>

                    <!-- Virtual Account -->
                    <div>
                        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Transfer Virtual Account</h3>
                        <div class="space-y-4">
                            <!-- BCA -->
                            <label class="relative flex items-center gap-4 p-4 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-bekas-green/50 hover:bg-gray-50 transition-colors">
                                <input type="radio" name="payment_method" value="bca" class="peer w-5 h-5 text-bekas-green border-gray-300 focus:ring-bekas-green">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800">BCA Virtual Account</span>
                                    <span class="text-xs text-gray-500">Dicek otomatis</span>
                                </div>
                                <div class="absolute right-4">
                                    <div class="px-2 py-1 bg-blue-900 rounded text-white text-[10px] font-bold">BCA</div>
                                </div>
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-bekas-green rounded-xl pointer-events-none transition-colors"></div>
                            </label>
                            
                            <!-- Mandiri -->
                            <label class="relative flex items-center gap-4 p-4 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-bekas-green/50 hover:bg-gray-50 transition-colors">
                                <input type="radio" name="payment_method" value="mandiri" class="peer w-5 h-5 text-bekas-green border-gray-300 focus:ring-bekas-green">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800">Mandiri Virtual Account</span>
                                    <span class="text-xs text-gray-500">Dicek otomatis</span>
                                </div>
                                <div class="absolute right-4">
                                    <div class="px-2 py-1 bg-yellow-400 rounded text-blue-900 text-[10px] font-bold">Mandiri</div>
                                </div>
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-bekas-green rounded-xl pointer-events-none transition-colors"></div>
                            </label>

                            <!-- BNI -->
                            <label class="relative flex items-center gap-4 p-4 border-2 border-gray-100 rounded-xl cursor-pointer hover:border-bekas-green/50 hover:bg-gray-50 transition-colors">
                                <input type="radio" name="payment_method" value="bni" class="peer w-5 h-5 text-bekas-green border-gray-300 focus:ring-bekas-green">
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-800">BNI Virtual Account</span>
                                    <span class="text-xs text-gray-500">Dicek otomatis</span>
                                </div>
                                <div class="absolute right-4">
                                    <div class="px-2 py-1 bg-teal-600 rounded text-white text-[10px] font-bold">BNI</div>
                                </div>
                                <div class="absolute inset-0 border-2 border-transparent peer-checked:border-bekas-green rounded-xl pointer-events-none transition-colors"></div>
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="w-full lg:w-1/3">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-24">
                    <div class="p-5 md:p-6 bg-gray-50/50 border-b border-gray-100">
                        <h2 class="text-lg font-bold text-gray-800">Ringkasan Pesanan</h2>
                    </div>
                    
                    <div class="p-5 md:p-6">
                        <!-- Product Info -->
                        <div class="flex gap-4 mb-6 pb-6 border-b border-gray-100">
                            <div class="w-20 h-20 rounded-xl bg-gray-100 overflow-hidden shrink-0 border border-gray-200">
                                <img src="https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=200&h=200&q=80" alt="Produk" class="w-full h-full object-cover">
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-bekas-green uppercase tracking-wider mb-1">ELEKTRONIK</span>
                                <h3 class="font-bold text-gray-800 line-clamp-2 leading-tight">Laptop Bekas Berkualitas Mantap Kuliah Mulus</h3>
                                <p class="text-xs text-gray-500 mt-auto">Penjual: Ahmad Teknik</p>
                            </div>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Harga Barang</span>
                                <span class="font-semibold text-gray-800">Rp 2.500.000</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Biaya Layanan Platform</span>
                                <span class="font-semibold text-gray-800">Rp 2.500</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500">Biaya Penanganan (Payment)</span>
                                <span class="font-semibold text-gray-800">Rp 1.500</span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="flex justify-between items-center pt-4 border-t border-gray-100 mb-8">
                            <span class="font-bold text-gray-800">Total Pembayaran</span>
                            <span class="text-xl font-black text-bekas-green">Rp 2.504.000</span>
                        </div>

                        <!-- Pay Button -->
                        <button type="button" id="pay-button" class="w-full bg-bekas-dark text-white font-bold text-lg py-4 rounded-xl flex justify-center items-center gap-2 hover:bg-gray-800 transition-colors duration-300 shadow-md">
                            Bayar Sekarang
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                        
                        <p class="text-center text-[10px] text-gray-400 mt-4 flex items-center justify-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Pembayaran dijamin 100% aman
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
</div>
</div>
{{-- Midtrans Script --}}
<script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ $clientKey }}"></script>

<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        // Panggil fungsi Snap Pay dengan token dari controller
        window.snap.pay('{{ $snapToken }}', {
            onSuccess: function (result) {
                /* Pembayaran sukses */
                alert("Pembayaran berhasil!"); 
                console.log(result);
                // Nanti kita bisa arahkan ke halaman sukses, misal:
                // window.location.href = '/pembayaran-sukses';
            },
            onPending: function (result) {
                /* Menunggu pembayaran (misal VA bank / e-wallet blm dibayar) */
                alert("Menunggu pembayaran Anda!"); 
                console.log(result);
            },
            onError: function (result) {
                /* Pembayaran gagal */
                alert("Pembayaran gagal!"); 
                console.log(result);
            },
            onClose: function () {
                /* Pengguna menutup popup tanpa membayar */
                alert('Anda menutup jendela pembayaran sebelum menyelesaikannya');
            }
        });
    };
</script>
@endsection
