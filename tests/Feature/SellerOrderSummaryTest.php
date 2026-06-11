<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerOrderSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_my_items_counts_only_successful_orders_for_the_seller(): void
    {
        [$seller, $buyer, $item] = $this->createOrderContext();

        Transaction::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'price' => $item->price,
            'status' => 'success',
        ]);

        Transaction::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'price' => $item->price,
            'status' => 'pending',
        ]);

        $this->actingAs($seller)
            ->get(route('barang.saya'))
            ->assertOk()
            ->assertViewHas('pesanan', 1)
            ->assertSee(route('penjual.orders.index'));
    }

    public function test_seller_order_pages_ignore_unpaid_transactions(): void
    {
        [$seller, $buyer, $item] = $this->createOrderContext();

        $paidOrder = Transaction::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'price' => $item->price,
            'status' => 'success',
            'order_id' => 'PAID-ORDER',
        ]);

        $pendingOrder = Transaction::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'price' => $item->price,
            'status' => 'pending',
            'order_id' => 'PENDING-ORDER',
        ]);

        $this->actingAs($seller)
            ->get(route('penjual.orders.index'))
            ->assertOk()
            ->assertViewHas('transactions', function ($transactions) use ($paidOrder, $pendingOrder) {
                return $transactions->contains('id', $paidOrder->id)
                    && !$transactions->contains('id', $pendingOrder->id);
            });

        $this->actingAs($seller)
            ->get(route('penjual.orders.show', $paidOrder))
            ->assertOk();

        $this->actingAs($seller)
            ->get(route('penjual.orders.show', $pendingOrder))
            ->assertNotFound();
    }

    private function createOrderContext(): array
    {
        $seller = User::factory()->create(['role' => 'seller']);
        $buyer = User::factory()->create(['role' => 'buyer']);
        $category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Barang Pesanan Penjual',
            'price' => 250000,
            'location' => 'Lowokwaru',
            'phone' => '081234567890',
            'condition' => 'Baik',
            'description' => 'Barang untuk pengujian pesanan penjual.',
            'status' => 'tersedia',
            'stock' => 5,
        ]);

        return [$seller, $buyer, $item];
    }
}
