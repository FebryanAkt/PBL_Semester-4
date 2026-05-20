<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item; 

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'user_id' => 1, 
                'category_id' => 1, // 1 = ID untuk kategori Elektronik
                'name' => 'Laptop Axioo Hype 5 X5 (Minus Baterai Dikit)',
                'price' => 3200000,
                'location' => 'Lowokwaru',
                'phone' => '081234567001', // <--- Tambahan nomor telepon
                'status' => 'tersedia',
                'image' => 'laptop_axio.png',
                'condition' => 'Bekas',
                'description' => 'Dijual cepat laptop Axioo Hype 5 X5...'
            ],
            [
                'user_id' => 1, 
                'category_id' => 2, // 2 = ID untuk kategori Fashion
                'name' => 'Jaket Bomber Techwear WOLV American Canvas',
                'price' => 185000,
                'location' => 'Klojen',
                'phone' => '081234567002', // <--- Tambahan nomor telepon
                'status' => 'tersedia',
                'image' => 'jacket_bomber.png',
                'condition' => 'Bekas',
                'description' => 'Jaket gaya techwear bahan American Canvas tebal...'
            ],
            [
                'user_id' => 1, 
                'category_id' => 1, // Elektronik
                'name' => 'Oximus Desk Mount Bracket Monitor',
                'price' => 125000,
                'location' => 'Malang',
                'phone' => '081234567003', // <--- Tambahan nomor telepon
                'status' => 'terjual', 
                'image' => 'bracket_monitor.png',
                'condition' => 'Bekas',
                'description' => 'Bracket monitor kokoh merk Oximus...'
            ],
            [
                'user_id' => 1,
                'category_id' => 2, // Fashion
                'name' => 'Jacket Bomber',
                'price' => 100000,
                'location' => 'Blimbing',
                'phone' => '081234567004', // <--- Tambahan nomor telepon
                'condition' => 'Sangat Baik',
                'description' => 'Jacket berkualitas',
                'image' => '1778473732_jacket_bomber.jpg',
                'status' => 'tersedia',
            ],
        ];

        // Masukkan semua data ke dalam database
        foreach ($items as $item) {
            Item::create($item);
        }
    }
}