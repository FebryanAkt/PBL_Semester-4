<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CartController;

// Auth (Login / Register / Logout)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'processRegister'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Home Guest
Route::get('/', [ItemController::class, 'landing'])->name('landing');

// Home
Route::get('/home', [ItemController::class, 'index'])
    //->middleware('auth')
    ->name('home');

// Detail Produk
Route::get('/produk/{id}', [ItemController::class, 'show'])->name('produk.detail');

// Barang Saya
Route::get('/barang-saya', [ItemController::class, 'myItems'])
    //->middleware('auth')
    ->name('barang.saya');

// Edit Barang
Route::get('/barang/{id}/edit', [ItemController::class, 'edit'])->name('barang.edit');
Route::put('/barang/{id}', [ItemController::class, 'update'])->name('barang.update');

// // TAMBAH BARANG
// Route::get('/barang/tambah', [ItemController::class, 'create'])
//     //->middleware('auth')
//     ->name('barang.create');

// Route::post('/barang/tambah', [ItemController::class, 'store'])
//     //->middleware('auth')
//     ->name('barang.store');

Route::middleware('auth')->group(function () {
    // Route Keranjang
    Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
    Route::post('/keranjang/tambah/{item_id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/keranjang/hapus/{id}', [CartController::class, 'remove'])->name('cart.remove');
    
});

// Checkout & Pembayaran
Route::middleware('auth')->group(function () {
    // Opsional: Anda bisa menambahkan {id} barang jika ingin mengirim data spesifik
    // Route::get('/checkout/{id}', [PaymentController::class, 'checkout'])->name('checkout');
    // Tambahkan ini di dalam grup middleware('auth') tempat Anda menaruh route checkout
    Route::post('/checkout/get-token', [PaymentController::class, 'getToken'])->name('checkout.getToken');
    Route::get('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
    
});

// Chat System
Route::middleware('auth')->group(function () {
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{id}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{id}', [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');
});

//Bual Barang
Route::middleware('auth')->group(function () {

    Route::get('/barang/jual', [ItemController::class, 'jual'])
        ->name('barang.jual');

    Route::post('/barang/jual_simpan', [ItemController::class, 'jual_simpan'])
        ->name('barang.jual_simpan');

});
//Profil
Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
});

// Midtrans Webhook / Callback
Route::post('/midtrans-callback', [PaymentController::class, 'callback'])->name('midtrans.callback');