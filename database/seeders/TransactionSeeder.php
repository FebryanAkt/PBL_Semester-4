<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('transactions')) {
            return;
        }

        $buyer = User::where('role', 'buyer')
            ->whereNotIn('email', ['buyer@example.com'])
            ->orderByDesc('id')
            ->first()
            ?? User::where('email', 'buyer@example.com')->first();
        $item = Item::query()->first();

        if (!$buyer || !$item) {
            return;
        }

        $quantity = 1;
        $transaction = [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'price' => $item->price * $quantity,
            'status' => 'success',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('transactions', 'quantity')) {
            $transaction['quantity'] = $quantity;
        }

        if (Schema::hasColumn('transactions', 'delivery_status')) {
            $transaction['delivery_status'] = 'belum_dikirim';
        }

        if (Schema::hasColumn('transactions', 'order_id')) {
            $transaction['order_id'] = 'SEED-ORDER-001';
        }

        if (Schema::hasColumn('transactions', 'snap_token')) {
            $transaction['snap_token'] = 'seed-snap-token-001';
        }

        if (Schema::hasColumn('transactions', 'shipping_code')) {
            $transaction['shipping_code'] = 'SEEDSHIP001';
        }

        $uniqueBy = Schema::hasColumn('transactions', 'order_id')
            ? ['order_id' => 'SEED-ORDER-001']
            : [
                'user_id' => $buyer->id,
                'item_id' => $item->id,
                'status' => 'success',
            ];

        DB::table('transactions')->updateOrInsert($uniqueBy, $transaction);
    }
}
