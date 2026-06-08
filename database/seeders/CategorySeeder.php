<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('categories')) {
            return;
        }

        $categories = [
            ['name' => 'Elektronik', 'slug' => 'elektronik'],
            ['name' => 'Furniture', 'slug' => 'furniture'],
            ['name' => 'Fashion', 'slug' => 'fashion'],
            ['name' => 'Hobi', 'slug' => 'hobi'],
        ];

        foreach ($categories as $category) {
            if (!Schema::hasColumn('categories', 'slug')) {
                Category::updateOrCreate(
                    ['name' => $category['name']],
                    ['name' => $category['name']]
                );

                continue;
            }

            Category::updateOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }
}
