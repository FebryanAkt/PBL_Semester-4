<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $seller;
    private Category $electronics;
    private Category $fashion;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seller = User::factory()->create([
            'role' => 'seller',
        ]);

        $this->electronics = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        $this->fashion = Category::create([
            'name' => 'Fashion',
            'slug' => 'fashion',
        ]);

        $this->createItem('Laptop Filter', $this->electronics, 3000000, 'Lowokwaru', 'Sangat Baik');
        $this->createItem('Jaket Filter', $this->fashion, 150000, 'Klojen', 'Minus Pemakaian');
        $this->createItem('Keyboard Filter', $this->electronics, 250000, 'Blimbing', 'Bekas');
    }

    public function test_category_filter_uses_category_relationship(): void
    {
        $response = $this->get(route('home', [
            'kategori' => 'Elektronik',
        ]));

        $response
            ->assertOk()
            ->assertSee('Laptop Filter')
            ->assertSee('Keyboard Filter')
            ->assertDontSee('Jaket Filter');
    }

    public function test_district_and_condition_filters_can_be_combined(): void
    {
        $response = $this->get(route('home', [
            'kecamatan' => 'Klojen',
            'kondisi' => 'Minus Pemakaian',
        ]));

        $response
            ->assertOk()
            ->assertSee('Jaket Filter')
            ->assertDontSee('Laptop Filter')
            ->assertDontSee('Keyboard Filter');
    }

    public function test_price_filter_orders_items_from_lowest_to_highest(): void
    {
        $response = $this->get(route('home', [
            'harga' => 'Termurah',
        ]));

        $response
            ->assertOk()
            ->assertSeeInOrder([
                'Jaket Filter',
                'Keyboard Filter',
                'Laptop Filter',
            ]);
    }

    public function test_price_filter_orders_items_from_highest_to_lowest(): void
    {
        $response = $this->get(route('home', [
            'harga' => 'Termahal',
        ]));

        $response
            ->assertOk()
            ->assertSeeInOrder([
                'Laptop Filter',
                'Keyboard Filter',
                'Jaket Filter',
            ]);
    }

    public function test_selected_filter_values_remain_visible_after_submit(): void
    {
        $response = $this->get(route('home', [
            'kategori' => 'Fashion',
            'kecamatan' => 'Klojen',
            'kondisi' => 'Minus Pemakaian',
            'harga' => 'Termurah',
        ]));

        $response
            ->assertOk()
            ->assertSee('value="Fashion"', false)
            ->assertSee('value="Klojen"', false)
            ->assertSee('value="Minus Pemakaian"', false)
            ->assertSee('value="Termurah"', false);
    }

    public function test_marketplace_card_has_direct_checkout_button(): void
    {
        $item = Item::where('name', 'Laptop Filter')->firstOrFail();

        $response = $this->get(route('home'));

        $response
            ->assertOk()
            ->assertSee('Beli Sekarang')
            ->assertSee(route('checkout', ['item_id' => $item->id, 'quantity' => 1]));
    }

    public function test_editing_an_item_updates_the_category_relationship(): void
    {
        $item = Item::where('name', 'Laptop Filter')->firstOrFail();

        $response = $this->actingAs($this->seller)->put(route('barang.update', $item), [
            'name' => $item->name,
            'price' => $item->price,
            'category_id' => $this->fashion->id,
            'description' => $item->description,
            'status' => $item->status,
        ]);

        $response->assertRedirect(route('barang.saya'));
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'category_id' => $this->fashion->id,
            'category' => null,
        ]);
    }

    private function createItem(
        string $name,
        Category $category,
        int $price,
        string $location,
        string $condition
    ): Item {
        return Item::create([
            'user_id' => $this->seller->id,
            'category_id' => $category->id,
            'name' => $name,
            'price' => $price,
            'location' => $location,
            'phone' => '081234567890',
            'condition' => $condition,
            'description' => $name . ' description',
            'status' => 'tersedia',
        ]);
    }
}
