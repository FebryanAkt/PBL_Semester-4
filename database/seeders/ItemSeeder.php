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
            'user_id' => 1, 
            'name' => 'Laptop Axioo Hype 5 X5 (Minus Baterai Dikit)',
            'price' => 3200000,
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
            'location' => 'Blimbing',
            'status' => 'terjual', 
            'image' => 'bracket_monitor.png',
            'condition' => 'Bekas',
            'description' => 'Bracket monitor kokoh merk Oximus...'
        ],
    ];

        // Masukkan semua data ke dalam database
        foreach ($items as $item) {
            Item::create($item);
        }
    }
}