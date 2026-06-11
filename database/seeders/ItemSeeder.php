<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Item; 
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('items')) {
            return;
        }

        $seller = User::where('role', 'seller')
            ->whereNotIn('email', ['seller@example.com', 'test@example.com'])
            ->orderByDesc('id')
            ->first()
            ?? User::where('email', 'seller@example.com')->first()
            ?? User::where('email', 'admin@gmail.com')->first()
            ?? User::first();

        if (!$seller) {
            return;
        }

        $categories = Schema::hasTable('categories')
            ? Category::query()->pluck('id', 'name')
            : collect();

        $items = [
            [
                'name' => 'Laptop Axioo Hype 5 X5 (Minus Baterai Dikit)',
                'category' => 'Elektronik',
                'price' => 3200000,
                'location' => 'Lowokwaru',
                'phone' => '081234567001',
                'status' => 'tersedia',
                'image' => 'laptop_axio.png',
                'condition' => 'Bekas',
                'description' => 'Dijual cepat laptop Axioo Hype 5 X5, performa masih lancar untuk kuliah dan kerja.',
                'stock' => 2,
            ],
            [
                'name' => 'Jaket Bomber Techwear WOLV American Canvas',
                'category' => 'Pakaian',
                'price' => 185000,
                'location' => 'Klojen',
                'phone' => '081234567002',
                'status' => 'tersedia',
                'image' => 'jacket_bomber.png',
                'condition' => 'Bekas',
                'description' => 'Jaket gaya techwear bahan American Canvas tebal, cocok untuk harian.',
                'stock' => 4,
            ],
            [
                'name' => 'Oximus Desk Mount Bracket Monitor',
                'category' => 'Elektronik',
                'price' => 125000,
                'location' => 'Lowokwaru',
                'phone' => '081234567003',
                'status' => 'terjual', 
                'image' => 'bracket_monitor.png',
                'condition' => 'Bekas',
                'description' => 'Bracket monitor kokoh merk Oximus, clamp meja masih kuat.',
                'stock' => 0,
            ],
            [
                'name' => 'Jacket Bomber',
                'category' => 'Pakaian',
                'price' => 100000,
                'location' => 'Blimbing',
                'phone' => '081234567004',
                'condition' => 'Sangat Baik',
                'description' => 'Jacket berkualitas',
                'image' => '1778473732_jacket_bomber.jpg',
                'status' => 'tersedia',
                'stock' => 1,
            ],
            [
                'name' => 'Meja Belajar Kayu Minimalis',
                'category' => 'Perabotan',
                'price' => 175000,
                'location' => 'Lowokwaru',
                'phone' => '081234567005',
                'condition' => 'Baik',
                'description' => 'Meja belajar bekas kos, masih kokoh dan siap pakai.',
                'image' => 'meja_belajar.png',
                'status' => 'tersedia',
                'stock' => 1,
            ],
            [
                'name' => 'Sepatu Sneakers Putih',
                'category' => 'Pakaian',
                'price' => 95000,
                'location' => 'Klojen',
                'phone' => '081234567006',
                'condition' => 'Bekas',
                'description' => 'Sneakers putih ukuran 42 dengan tanda pemakaian ringan.',
                'image' => 'sneakers_putih.png',
                'status' => 'tersedia',
                'stock' => 1,
            ],
        ];

        foreach ($items as $item) {
            $attributes = [
                'user_id' => $seller->id,
                'name' => $item['name'],
                'price' => $item['price'],
                'category' => $item['category'],
                'location' => $item['location'],
                'condition' => $item['condition'],
                'description' => $item['description'],
                'image' => $item['image'],
                'status' => $item['status'],
            ];

            if (Schema::hasColumn('items', 'category_id')) {
                $attributes['category_id'] = $categories[$item['category']] ?? null;
            }

            if (Schema::hasColumn('items', 'phone')) {
                $attributes['phone'] = $item['phone'];
            }

            if (Schema::hasColumn('items', 'stock')) {
                $attributes['stock'] = $item['stock'];
            }

            if (Schema::hasColumn('items', 'images')) {
                $attributes['images'] = json_encode([]);
            }

            Item::updateOrCreate(
                ['name' => $item['name']],
                $attributes
            );
        }
    }
}
