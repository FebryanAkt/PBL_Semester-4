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

// EDIT BARANG
Route::get('/barang/{id}/edit', [ItemController::class, 'edit'])->name('barang.edit');
Route::put('/barang/{id}', [ItemController::class, 'update'])->name('barang.update');
// // TAMBAH BARANG
// Route::get('/barang/tambah', [ItemController::class, 'create'])
//     //->middleware('auth')
//     ->name('barang.create');

// Route::post('/barang/tambah', [ItemController::class, 'store'])
//     //->middleware('auth')
//     ->name('barang.store');

//JUAL BARANG
Route::get('/barang/jual', [ItemController::class, 'jual'])->name('barang.jual');