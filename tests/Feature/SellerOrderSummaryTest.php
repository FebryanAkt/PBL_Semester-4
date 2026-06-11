<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionItem;
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

        Transaction::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'price' => $item->price,
            'status' => 'success',
            'delivery_status' => 'diterima',
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

        $receivedOrder = Transaction::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'price' => $item->price,
            'status' => 'success',
            'delivery_status' => 'diterima',
            'order_id' => 'RECEIVED-ORDER',
        ]);

        $this->actingAs($seller)
            ->get(route('penjual.orders.index'))
            ->assertOk()
            ->assertViewHas('transactions', function ($transactions) use ($paidOrder, $pendingOrder, $receivedOrder) {
                return $transactions->contains('id', $paidOrder->id)
                    && !$transactions->contains('id', $pendingOrder->id)
                    && !$transactions->contains('id', $receivedOrder->id);
            })
            ->assertViewHas('pesanan', 1)
            ->assertViewHas('belumDikirim', 1)
            ->assertViewHas('sedangDikirim', 0);

        $this->actingAs($seller)
            ->get(route('penjual.orders.show', $paidOrder))
            ->assertOk();

        $this->actingAs($seller)
            ->get(route('penjual.orders.show', $pendingOrder))
            ->assertNotFound();

        $this->actingAs($seller)
            ->get(route('penjual.orders.show', $receivedOrder))
            ->assertNotFound();
    }

    public function test_received_order_is_removed_from_active_orders_after_status_update(): void
    {
        [$seller, $buyer, $item] = $this->createOrderContext();

        $order = Transaction::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'price' => $item->price,
            'status' => 'success',
            'order_id' => 'ORDER-TO-COMPLETE',
        ]);

        $this->actingAs($seller)
            ->post(route('penjual.orders.delivery', $order), [
                'delivery_status' => 'diterima',
                'shipping_code' => 'RESI-SELESAI',
            ])
            ->assertRedirect(route('penjual.orders.index'));

        $this->assertDatabaseHas('transactions', [
            'id' => $order->id,
            'delivery_status' => 'diterima',
            'shipping_code' => 'RESI-SELESAI',
        ]);

        $this->actingAs($seller)
            ->get(route('penjual.orders.index'))
            ->assertOk()
            ->assertViewHas('pesanan', 0)
            ->assertViewHas('transactions', fn ($transactions) => $transactions->isEmpty());
    }

    public function test_completed_multi_seller_line_only_disappears_for_its_seller(): void
    {
        [$firstSeller, $buyer, $firstItem] = $this->createOrderContext();
        $secondSeller = User::factory()->create(['role' => 'seller']);
        $category = Category::query()->firstOrFail();
        $secondItem = $this->createItem($secondSeller, $category, 'Barang Penjual Kedua');

        $order = Transaction::create([
            'user_id' => $buyer->id,
            'item_id' => $firstItem->id,
            'price' => $firstItem->price + $secondItem->price,
            'status' => 'success',
            'order_id' => 'MULTI-SELLER-ACTIVE',
        ]);

        TransactionItem::create([
            'transaction_id' => $order->id,
            'item_id' => $firstItem->id,
            'quantity' => 1,
            'price' => $firstItem->price,
            'delivery_status' => 'diterima',
        ]);

        TransactionItem::create([
            'transaction_id' => $order->id,
            'item_id' => $secondItem->id,
            'quantity' => 1,
            'price' => $secondItem->price,
            'delivery_status' => 'dikirim',
        ]);

        $this->actingAs($firstSeller)
            ->get(route('penjual.orders.index'))
            ->assertOk()
            ->assertViewHas('pesanan', 0);

        $this->actingAs($secondSeller)
            ->get(route('penjual.orders.index'))
            ->assertOk()
            ->assertViewHas('pesanan', 1)
            ->assertViewHas('sedangDikirim', 1);
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

    private function createItem(User $seller, Category $category, string $name): Item
    {
        return Item::create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'name' => $name,
            'price' => 175000,
            'location' => 'Klojen',
            'phone' => '081234567891',
            'condition' => 'Baik',
            'description' => $name,
            'status' => 'tersedia',
            'stock' => 3,
        ]);
    }
}
