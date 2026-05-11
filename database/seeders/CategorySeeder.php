<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik', 'slug' => 'elektronik'],
            ['name' => 'Furniture', 'slug' => 'furniture'],
            ['name' => 'Fashion', 'slug' => 'fashion'],
            ['name' => 'Hobi', 'slug' => 'hobi'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

