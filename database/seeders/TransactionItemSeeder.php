<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransactionItemSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('transaction_items') || !Schema::hasTable('transactions')) {
            return;
        }

        $transaction = Schema::hasColumn('transactions', 'order_id')
            ? Transaction::where('order_id', 'SEED-ORDER-001')->first()
            : Transaction::where('status', 'success')->first();

        if (!$transaction || !$transaction->item) {
            return;
        }

        $quantity = Schema::hasColumn('transactions', 'quantity')
            ? (int) $transaction->quantity
            : 1;

        $transactionItem = [
            'transaction_id' => $transaction->id,
            'item_id' => $transaction->item_id,
            'quantity' => max($quantity, 1),
            'price' => $transaction->item->price,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('transaction_items', 'cart_id')) {
            $cart = Cart::where('user_id', $transaction->user_id)
                ->where('item_id', $transaction->item_id)
                ->first();

            $transactionItem['cart_id'] = $cart?->id;
        }

        DB::table('transaction_items')->updateOrInsert(
            [
                'transaction_id' => $transaction->id,
                'item_id' => $transaction->item_id,
            ],
            $transactionItem
        );
    }
}
