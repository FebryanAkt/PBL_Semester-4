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