<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item; // Pastikan model Item di-import

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'Laptop Axioo Hype 5 X5 (Minus Baterai Dikit)',
                'price' => 3200000,
                'location' => 'Lowokwaru',
                'status' => 'tersedia',
                'image' => null,
                'condition' => 'Bekas',
                'description' => 'Dijual cepat laptop Axioo Hype 5 X5, RAM 8GB SSD 512GB. Performa masih kencang buat ngoding dan tugas kuliah. Minus baterai sedikit menggelembung (bisa diganti murah di service center), selebihnya aman.'
            ],
            [
                'name' => 'Jaket Bomber Techwear WOLV American Canvas',
                'price' => 185000,
                'location' => 'Klojen',
                'status' => 'tersedia',
                'image' => null,
                'condition' => 'Bekas',
                'description' => 'Jaket gaya techwear bahan American Canvas tebal dan awet. Ukuran L fit to XL. Kondisi 90% masih sangat bagus, jarang dipakai karena salah beli ukuran.'
            ],
            [
                'name' => 'Oximus Desk Mount Bracket Monitor',
                'price' => 125000,
                'location' => 'Blimbing',
                'status' => 'terjual',
                'image' => null,
                'condition' => 'Bekas',
                'description' => 'Bracket monitor kokoh merk Oximus, cocok buat setup meja belajar/ngoding biar rapi. Sudah lengkap dengan baut-bautnya.'
            ],
        ];

        // Masukkan semua data ke dalam database
        foreach ($items as $item) {
            Item::create($item);
        }
    }
}