<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('barangs')) {
            return;
        }

        $barangs = [
            [
                'name' => 'Meja Belajar Kayu Minimalis',
                'category' => 'Furniture',
                'description' => 'Meja belajar bekas kos, masih kokoh dan siap pakai.',
                'tags' => 'meja,kayu,kos',
                'image' => 'meja_belajar.png',
                'price' => 175000,
                'status' => 'tersedia',
                'location' => 'Lowokwaru',
            ],
            [
                'name' => 'Sepatu Sneakers Putih',
                'category' => 'Fashion',
                'description' => 'Sneakers putih ukuran 42, ada tanda pemakaian ringan.',
                'tags' => 'sepatu,sneakers,fashion',
                'image' => 'sneakers_putih.png',
                'price' => 95000,
                'status' => 'tersedia',
                'location' => 'Klojen',
            ],
        ];

        foreach ($barangs as $barang) {
            Barang::updateOrCreate(
                ['name' => $barang['name']],
                $barang
            );
        }
    }
}
