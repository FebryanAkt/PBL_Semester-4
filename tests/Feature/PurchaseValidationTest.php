<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_direct_checkout_rejects_invalid_quantities(): void
    {
        [$buyer, $item] = $this->createBuyerAndItem();

        foreach ([0, -1, 'bukan-angka'] as $quantity) {
            $this->actingAs($buyer)
                ->postJson(route('checkout.getToken'), [
                    'payment_method' => 'gopay',
                    'is_direct' => 'yes',
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                ])
                ->assertUnprocessable()
                ->assertJsonValidationErrors('quantity');

            $this->actingAs($buyer)
                ->postJson(route('cart.add'), [
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                ])
                ->assertUnprocessable()
                ->assertJsonValidationErrors('quantity');
        }

        $this->assertDatabaseCount('carts', 0);

        $this->actingAs($buyer)
            ->from(route('produk.detail', $item->id))
            ->get(route('checkout', [
                'item_id' => $item->id,
                'quantity' => 0,
            ]))
            ->assertRedirect(route('produk.detail', $item->id))
            ->assertSessionHasErrors('quantity');
    }

    public function test_user_cannot_buy_their_own_item(): void
    {
        $owner = User::factory()->create(['role' => 'seller']);
        $item = $this->createItem($owner);

        $this->actingAs($owner)
            ->postJson(route('checkout.getToken'), $this->directCheckoutPayload($item))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('quantity');

        $this->actingAs($owner)
            ->postJson(route('cart.add'), [
                'item_id' => $item->id,
                'quantity' => 1,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('quantity');

        $this->assertDatabaseCount('carts', 0);
    }

    public function test_unavailable_or_out_of_stock_item_cannot_be_purchased(): void
    {
        [$buyer, $item] = $this->createBuyerAndItem();

        $item->update(['status' => 'terjual']);

        $this->actingAs($buyer)
            ->postJson(route('checkout.getToken'), $this->directCheckoutPayload($item))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('quantity');

        $item->update([
            'status' => 'tersedia',
            'stock' => 0,
        ]);

        $this->actingAs($buyer)
            ->postJson(route('checkout.getToken'), $this->directCheckoutPayload($item))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('quantity');
    }

    public function test_quantity_cannot_exceed_stock_when_adding_to_cart_or_checkout(): void
    {
        [$buyer, $item] = $this->createBuyerAndItem(stock: 2);

        $this->actingAs($buyer)
            ->postJson(route('cart.add'), [
                'item_id' => $item->id,
                'quantity' => 3,
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('quantity');

        $this->actingAs($buyer)
            ->postJson(route('checkout.getToken'), $this->directCheckoutPayload($item, 3))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('quantity');

        $this->assertDatabaseCount('carts', 0);
    }

    public function test_cart_checkout_revalidates_current_item_status_and_stock(): void
    {
        [$buyer, $item] = $this->createBuyerAndItem(stock: 2);

        $cart = Cart::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'quantity' => 2,
        ]);

        $this->actingAs($buyer)
            ->get(route('checkout', ['cart_ids' => [$cart->id]]))
            ->assertOk();

        $item->update(['status' => 'booking']);

        $this->actingAs($buyer)
            ->postJson(route('checkout.getToken'), [
                'payment_method' => 'gopay',
                'is_direct' => 'no',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('quantity');

        $item->update([
            'status' => 'tersedia',
            'stock' => 1,
        ]);

        $this->actingAs($buyer)
            ->postJson(route('checkout.getToken'), [
                'payment_method' => 'gopay',
                'is_direct' => 'no',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('quantity');

        $this->assertDatabaseCount('transactions', 0);
    }

    private function createBuyerAndItem(int $stock = 5): array
    {
        $buyer = User::factory()->create(['role' => 'buyer']);
        $seller = User::factory()->create(['role' => 'seller']);

        return [$buyer, $this->createItem($seller, $stock)];
    }

    private function createItem(User $seller, int $stock = 5): Item
    {
        $category = Category::firstOrCreate(
            ['slug' => 'elektronik'],
            ['name' => 'Elektronik']
        );

        return Item::create([
            'user_id' => $seller->id,
            'category_id' => $category->id,
            'name' => 'Barang Uji',
            'price' => 100000,
            'location' => 'Lowokwaru',
            'phone' => '081234567890',
            'condition' => 'Baik',
            'description' => 'Barang untuk pengujian',
            'status' => 'tersedia',
            'stock' => $stock,
        ]);
    }

    private function directCheckoutPayload(Item $item, int $quantity = 1): array
    {
        return [
            'payment_method' => 'gopay',
            'is_direct' => 'yes',
            'item_id' => $item->id,
            'quantity' => $quantity,
        ];
    }
}
