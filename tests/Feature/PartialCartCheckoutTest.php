<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PartialCartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_selected_cart_items_are_saved_to_the_transaction(): void
    {
        $buyer = User::factory()->create([
            'role' => 'buyer',
        ]);

        $seller = User::factory()->create([
            'role' => 'seller',
        ]);

        $category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        $selectedItem = $this->createItem($seller, $category, 'Barang Dipilih', 100000);
        $unselectedItem = $this->createItem($seller, $category, 'Barang Tidak Dipilih', 200000);

        $selectedCart = Cart::create([
            'user_id' => $buyer->id,
            'item_id' => $selectedItem->id,
            'quantity' => 1,
        ]);

        $unselectedCart = Cart::create([
            'user_id' => $buyer->id,
            'item_id' => $unselectedItem->id,
            'quantity' => 1,
        ]);

        $this->actingAs($buyer)
            ->get(route('checkout', [
                'cart_ids' => [$selectedCart->id],
            ]))
            ->assertOk()
            ->assertSee('Barang Dipilih')
            ->assertDontSee('Barang Tidak Dipilih');

        Mockery::mock('alias:Midtrans\Snap')
            ->shouldReceive('getSnapToken')
            ->once()
            ->andReturn('test-snap-token');

        $response = $this->actingAs($buyer)->postJson(route('checkout.getToken'), [
            'payment_method' => 'gopay',
            'is_direct' => 'no',
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'token' => 'test-snap-token',
            ]);

        $this->assertDatabaseHas('transaction_items', [
            'cart_id' => $selectedCart->id,
            'item_id' => $selectedItem->id,
            'quantity' => 1,
        ]);

        $this->assertDatabaseMissing('transaction_items', [
            'cart_id' => $unselectedCart->id,
            'item_id' => $unselectedItem->id,
        ]);

        $transaction = Transaction::where('snap_token', 'test-snap-token')->firstOrFail();
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

        $this->assertDatabaseHas('items', [
            'id' => $selectedItem->id,
            'stock' => 4,
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $unselectedItem->id,
            'stock' => 5,
        ]);

        $this->assertDatabaseMissing('carts', [
            'id' => $selectedCart->id,
        ]);

        $this->assertDatabaseHas('carts', [
            'id' => $unselectedCart->id,
        ]);
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
