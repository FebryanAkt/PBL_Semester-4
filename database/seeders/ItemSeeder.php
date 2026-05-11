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
            'name' => 'Laptop Axioo Hype 5 X5 (Minus Baterai Dikit)',
            'price' => 3200000,
            'category' => 'Elektronik',
            'location' => 'Lowokwaru',
            'status' => 'tersedia',
            'image' => 'laptop_axio.png',
            'condition' => 'Bekas',
            'description' => 'Dijual cepat laptop Axioo Hype 5 X5...'
        ],
        [
            'user_id' => 1, 
            'name' => 'Jaket Bomber Techwear WOLV American Canvas',
            'price' => 185000,
            'category' => 'Fashion',
            'location' => 'Klojen',
            'status' => 'tersedia',
            'image' => 'jacket_bomber.png',
            'condition' => 'Bekas',
            'description' => 'Jaket gaya techwear bahan American Canvas tebal...'
        ],
        [
            'user_id' => 1, 
            'name' => 'Oximus Desk Mount Bracket Monitor',
            'price' => 125000,
            'location' => 'Elektronik',
            'status' => 'terjual', 
            'image' => 'bracket_monitor.png',
            'condition' => 'Bekas',
            'description' => 'Bracket monitor kokoh merk Oximus...'
        ],
        [
            'user_id' => 1,
            'name' => 'Jacket Bomber',
            'price' => 100000,
            'category' => 'Fashion',
            'location' => 'Blimbing',
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