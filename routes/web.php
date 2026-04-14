<?php

use Illuminate\Support\Facades\Route;

// Kumpulan Data Dummy ditaruh di luar agar bisa diakses oleh 2 route
$dummyItems = [
    (object)[
        'id' => 1,
        'name' => 'Laptop Axioo Hype 5 X5 (Minus Baterai Dikit)',
        'price' => 3200000,
        'location' => 'Lowokwaru',
        'status' => 'tersedia',
        'image' => null,
        'category' => 'Elektronik',
        'description' => 'Dijual cepat laptop Axioo Hype 5 X5, RAM 8GB SSD 512GB. Performa masih kencang buat ngoding dan tugas kuliah. Minus baterai sedikit menggelembung (bisa diganti murah di service center), selebihnya aman.'
    ],
    (object)[
        'id' => 2,
        'name' => 'Jaket Bomber Techwear WOLV American Canvas',
        'price' => 185000,
        'location' => 'Klojen',
        'status' => 'tersedia',
        'image' => null,
        'category' => 'Fashion',
        'description' => 'Jaket gaya techwear bahan American Canvas tebal dan awet. Ukuran L fit to XL. Kondisi 90% masih sangat bagus, jarang dipakai karena salah beli ukuran.'
    ],
    (object)[
        'id' => 3,
        'name' => 'Oximus Desk Mount Bracket Monitor',
        'price' => 125000,
        'location' => 'Blimbing',
        'status' => 'terjual',
        'image' => null,
        'category' => 'Elektronik',
        'description' => 'Bracket monitor kokoh merk Oximus, cocok buat setup meja belajar/ngoding biar rapi. Sudah lengkap dengan baut-bautnya.'
    ],
];

// Route Beranda
Route::get('/', function () use ($dummyItems) {
    return view('home', ['items' => $dummyItems]); // Sesuaikan 'beranda' dengan nama file kamu
});

// Route Detail Produk
Route::get('/produk/{id}', function ($id) use ($dummyItems) {
    // Mencari produk berdasarkan ID dari array dummy
    $item = collect($dummyItems)->firstWhere('id', (int)$id);

    // Jika produk tidak ditemukan, kembalikan error 404
    if (!$item) {
        abort(404);
    }

    return view('detail', compact('item'));
})->name('produk.detail');
