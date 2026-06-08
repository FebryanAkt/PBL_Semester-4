<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('carts')) {
            return;
        }

        $buyer = User::where('role', 'buyer')
            ->whereNotIn('email', ['buyer@example.com'])
            ->orderByDesc('id')
            ->first()
            ?? User::where('email', 'buyer@example.com')->first();

        if (!$buyer) {
            return;
        }

        $items = Item::query()
            ->where('status', 'tersedia')
            ->take(2)
            ->get();

        foreach ($items as $index => $item) {
            Cart::updateOrCreate(
                [
                    'user_id' => $buyer->id,
                    'item_id' => $item->id,
                ],
                [
                    'quantity' => $index + 1,
                ]
            );
        }
    }
}
