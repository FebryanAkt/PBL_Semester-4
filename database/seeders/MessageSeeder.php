<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('messages')) {
            return;
        }

        $buyer = User::where('role', 'buyer')
            ->whereNotIn('email', ['buyer@example.com'])
            ->orderByDesc('id')
            ->first()
            ?? User::where('email', 'buyer@example.com')->first();
        $seller = User::where('role', 'seller')
            ->whereNotIn('email', ['seller@example.com', 'test@example.com'])
            ->orderByDesc('id')
            ->first()
            ?? User::where('email', 'seller@example.com')->first();
        $item = Item::where('status', 'tersedia')->first();

        if (!$buyer || !$seller || !$item) {
            return;
        }

        $messages = [
            [
                'sender_id' => $buyer->id,
                'receiver_id' => $seller->id,
                'item_id' => $item->id,
                'message' => 'Halo, barang ini masih tersedia?',
                'is_read' => true,
            ],
            [
                'sender_id' => $seller->id,
                'receiver_id' => $buyer->id,
                'item_id' => $item->id,
                'message' => 'Masih tersedia. Bisa COD di area kampus.',
                'is_read' => false,
            ],
        ];

        foreach ($messages as $message) {
            Message::updateOrCreate(
                [
                    'sender_id' => $message['sender_id'],
                    'receiver_id' => $message['receiver_id'],
                    'item_id' => $message['item_id'],
                    'message' => $message['message'],
                ],
                ['is_read' => $message['is_read']]
            );
        }
    }
}
