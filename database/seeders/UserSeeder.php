<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Schema::hasTable('users')) {
            return;
        }

        $users = [
            [
                'name' => 'Admin Bekaswit',
                'email' => 'admin@gmail.com',
                'password' => 'password123',
                'role' => 'admin',
                'university' => 'Universitas Brawijaya',
                'address' => 'Lowokwaru, Malang',
                'phone_number' => '081234567000',
            ],
            [
                'name' => 'Gitar Seller',
                'email' => 'seller@example.com',
                'password' => 'password123',
                'role' => 'seller',
                'university' => 'Universitas Negeri Malang',
                'address' => 'Klojen, Malang',
                'phone_number' => '081234567001',
            ],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password',
                'role' => 'seller',
                'university' => 'Universitas Brawijaya',
                'address' => 'Sukun, Malang',
                'phone_number' => '081234567003',
            ],
            [
                'name' => 'Pembeli Bekaswit',
                'email' => 'buyer@example.com',
                'password' => 'password123',
                'role' => 'buyer',
                'university' => 'Politeknik Negeri Malang',
                'address' => 'Blimbing, Malang',
                'phone_number' => '081234567004',
            ],
        ];

        foreach ($users as $user) {
            $attributes = [
                'name' => $user['name'],
                'password' => Hash::make($user['password']),
            ];

            foreach (['role', 'university', 'address', 'phone_number'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $attributes[$column] = $user[$column];
                }
            }

            User::updateOrCreate(
                ['email' => $user['email']],
                $attributes
            );
        }
    }
}
