<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

// Pastikan method di controller mengarah ke view 'home' (sesuai kodemu sebelumnya)
Route::get('/', [ItemController::class, 'index'])->name('home');

// Route Detail Produk
Route::get('/produk/{id}', [ItemController::class, 'show'])->name('produk.detail');

// BARANG SAYA
Route::get('/barang-saya', [ItemController::class, 'myItems'])
    //->middleware('auth')
    ->name('barang.saya');

// // TAMBAH BARANG
// Route::get('/barang/tambah', [ItemController::class, 'create'])
//     //->middleware('auth')
//     ->name('barang.create');

// Route::post('/barang/tambah', [ItemController::class, 'store'])
//     //->middleware('auth')
//     ->name('barang.store');