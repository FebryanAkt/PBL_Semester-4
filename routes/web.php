<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\PenjualController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\SellerOrderController;


// Auth (Login / Register / Logout)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::get('/lapak/{id}', [PenjualController::class, 'lapak'])->name('penjual.lapak');
Route::get('/barang/{id}', [BarangController::class, 'show'])->whereNumber('id')->name('barang.show');
Route::post('/register', [AuthController::class, 'processRegister'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/riwayat-transaksi', [PaymentController::class, 'history'])->name('transaksi.riwayat');

// Home Guest
Route::get('/', [ItemController::class, 'landing'])->name('landing');

// Home
Route::get('/home', [ItemController::class, 'index'])->name('home');

// Detail Produk
Route::get('/produk/{id}', [ItemController::class, 'show'])->name('produk.detail');

// Midtrans Webhook / Callback
Route::post('/midtrans-callback', [PaymentController::class, 'callback'])->name('midtrans.callback');

// ====================================================================
// SEMUA ROUTE DI BAWAH INI WAJIB LOGIN (AUTH MIDDLEWARE GROUP)
// ====================================================================
Route::middleware('auth')->group(function () {
    // Beranda khusus penjual tetap menggunakan katalog utama.
    Route::get('/penjual/home', [ItemController::class, 'sellerHome'])->name('penjual.home');
    Route::redirect('/penjual/dashboard', '/penjual/home')->name('penjual.dashboard');

    // Barang Saya & Jual Barang
    Route::get('/barang-saya', [ItemController::class, 'myItems'])->name('barang.saya');
    Route::get('/item/sell', [ItemController::class, 'jual'])->name('barang.jual');
    Route::post('/item/jual_simpan', [ItemController::class, 'jual_simpan'])->name('barang.jual_simpan');

    // Edit Barang
    Route::get('/barang/{id}/edit', [ItemController::class, 'edit'])->whereNumber('id')->name('barang.edit');
    Route::put('/barang/{id}', [ItemController::class, 'update'])->whereNumber('id')->name('barang.update');

    // Route Keranjang
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    // PERBAIKAN: Menghapus {item_id} karena id dikirim lewat hidden input di form
    Route::post('/keranjang/tambah', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/keranjang/hapus/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/keranjang/update/{id}', [CartController::class, 'update'])->name('cart.update');

    // Checkout & Pembayaran
    Route::post('/checkout/get-token', [PaymentController::class, 'getToken'])->name('checkout.getToken');
    Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');

    // Chat System
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{id}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{id}', [ChatController::class, 'store'])->name('chat.store');

    // Profil
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/transaction/detail/{id}', [PaymentController::class, 'detail'])->name('transaction.detail');

    Route::middleware(['auth'])->prefix('penjual')->name('penjual.')->group(function () {
        Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{id}', [SellerOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/delivery', [SellerOrderController::class, 'updateDelivery'])->name('orders.delivery');
    });

    // Routes untuk notifikasi (AJAX)
    Route::middleware(['auth'])->group(function () {
        Route::get('/notifications/fetch', function () {
            return auth()->user()->unreadNotifications;
        })->name('notifications.fetch');

        Route::post('/notifications/mark-as-read', function (Request $request) {
            auth()->user()->notifications()->where('id', $request->id)->update(['read_at' => now()]);
            return response()->json(['success' => true]);
        })->name('notifications.mark-read');
    });
});
