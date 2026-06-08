<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('notifications') || !Schema::hasTable('transactions')) {
            return;
        }

        $seller = User::where('role', 'seller')
            ->whereNotIn('email', ['seller@example.com', 'test@example.com'])
            ->orderByDesc('id')
            ->first()
            ?? User::where('email', 'seller@example.com')->first();
        $transaction = Schema::hasColumn('transactions', 'order_id')
            ? Transaction::where('order_id', 'SEED-ORDER-001')->with(['item', 'user'])->first()
            : Transaction::with(['item', 'user'])->first();

        if (!$seller || !$transaction) {
            return;
        }

        $data = [
            'transaction_id' => $transaction->id,
            'order_id' => $transaction->order_id ?? null,
            'item_name' => $transaction->item?->name ?? 'Barang',
            'buyer_name' => $transaction->user?->name ?? 'Pembeli',
            'total_price' => $transaction->price,
            'message' => 'Ada pesanan baru untuk barang "' . ($transaction->item?->name ?? 'Barang') . '".',
            'url' => url('/penjual/orders/' . $transaction->id),
        ];

        DB::table('notifications')->updateOrInsert(
            ['id' => '11111111-1111-4111-8111-111111111111'],
            [
                'id' => '11111111-1111-4111-8111-111111111111',
                'type' => NewOrderNotification::class,
                'notifiable_type' => User::class,
                'notifiable_id' => $seller->id,
                'data' => json_encode($data),
                'read_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
