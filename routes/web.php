<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

// Pastikan method di controller mengarah ke view 'home' (sesuai kodemu sebelumnya)
Route::get('/', [ItemController::class, 'index'])->name('home');

// Route Detail Produk
Route::get('/produk/{id}', [ItemController::class, 'show'])->name('produk.detail');