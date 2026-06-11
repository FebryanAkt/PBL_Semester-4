<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TestConnectionSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('test_connections')) {
            return;
        }

        DB::table('test_connections')->updateOrInsert(
            ['id' => 1],
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
