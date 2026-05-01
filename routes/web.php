<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

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

//Bual Barang
Route::get('/barang/jual', [ItemController::class, 'jual'])->name('barang.jual');

//Profil
Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
});