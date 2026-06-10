<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MultiItemTransactionFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_multi_seller_transaction_is_visible_and_manageable_by_each_seller(): void
    {
        $buyer = User::factory()->create(['role' => 'buyer']);
        $firstSeller = User::factory()->create(['role' => 'seller']);
        $secondSeller = User::factory()->create(['role' => 'seller']);
        $category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        $firstItem = $this->createItem($firstSeller, $category, 'Laptop Seller Pertama', 200000);
        $secondItem = $this->createItem($secondSeller, $category, 'Mouse Seller Kedua', 100000);

        $transaction = Transaction::create([
            'user_id' => $buyer->id,
            'item_id' => $firstItem->id,
            'quantity' => 2,
            'price' => 304000,
            'status' => 'pending',
            'order_id' => 'TRX-MULTI-SELLER',
            'snap_token' => 'multi-seller-token',
        ]);

        $firstLine = TransactionItem::create([
            'transaction_id' => $transaction->id,
            'item_id' => $firstItem->id,
            'quantity' => 1,
            'price' => $firstItem->price,
        ]);

        $secondLine = TransactionItem::create([
            'transaction_id' => $transaction->id,
            'item_id' => $secondItem->id,
            'quantity' => 1,
            'price' => $secondItem->price,
        ]);

        Notification::fake();

        $grossAmount = (string) $transaction->price;
        $signature = hash(
            'sha512',
            $transaction->order_id . '200' . $grossAmount . env('MIDTRANS_SERVER_KEY')
        );

        $this->postJson(route('midtrans.callback'), [
            'order_id' => $transaction->order_id,
            'status_code' => '200',
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
        ])->assertOk();

        Notification::assertSentTo(
            $firstSeller,
            NewOrderNotification::class,
            function (NewOrderNotification $notification) use ($firstSeller, $firstItem, $secondItem) {
                $data = $notification->toArray($firstSeller);

                return str_contains($data['item_name'], $firstItem->name)
                    && !str_contains($data['item_name'], $secondItem->name);
            }
        );

        Notification::assertSentTo(
            $secondSeller,
            NewOrderNotification::class,
            function (NewOrderNotification $notification) use ($secondSeller, $firstItem, $secondItem) {
                $data = $notification->toArray($secondSeller);

                return str_contains($data['item_name'], $secondItem->name)
                    && !str_contains($data['item_name'], $firstItem->name);
            }
        );

        $this->actingAs($secondSeller)
            ->get(route('penjual.orders.index'))
            ->assertOk()
            ->assertSee($secondItem->name)
            ->assertDontSee($firstItem->name);

        $this->actingAs($secondSeller)
            ->get(route('penjual.orders.show', $transaction->id))
            ->assertOk()
            ->assertSee($secondItem->name)
            ->assertDontSee($firstItem->name);

        $this->actingAs($secondSeller)
            ->post(route('penjual.orders.delivery', $transaction->id), [
                'delivery_status' => 'dikirim',
                'shipping_code' => 'RESI-SELLER-2',
            ])
            ->assertRedirect(route('penjual.orders.show', $transaction->id));

        $this->assertDatabaseHas('transaction_items', [
            'id' => $secondLine->id,
            'delivery_status' => 'dikirim',
            'shipping_code' => 'RESI-SELLER-2',
        ]);

        $this->assertDatabaseHas('transaction_items', [
            'id' => $firstLine->id,
            'delivery_status' => 'belum_dikirim',
            'shipping_code' => null,
        ]);

        $this->actingAs($buyer)
            ->get(route('transaksi.riwayat'))
            ->assertOk()
            ->assertSee($firstItem->name)
            ->assertSee($secondItem->name);

        $this->actingAs($buyer)
            ->getJson(route('transaction.detail', $transaction->id))
            ->assertOk()
            ->assertJsonPath('items.0.name', $firstItem->name)
            ->assertJsonPath('items.1.name', $secondItem->name);
    }

    private function createItem(User $seller, Category $category, string $name, int $price): Item
    {
        return Item::create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'name' => $name,
            'price' => $price,
            'location' => 'Lowokwaru',
            'phone' => '081234567890',
            'condition' => 'Baik',
            'description' => $name,
            'status' => 'tersedia',
            'stock' => 5,
        ]);
    }
}
