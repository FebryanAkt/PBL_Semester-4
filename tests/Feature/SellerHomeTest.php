<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SellerHomeTest extends TestCase
{
    use RefreshDatabase;

    public function test_seller_is_redirected_to_seller_home_after_login(): void
    {
        $seller = User::factory()->create([
            'role' => 'seller',
        ]);

        $response = $this->post(route('login.post'), [
            'email' => $seller->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('penjual.home'));
    }

    public function test_seller_home_contains_search_and_slider(): void
    {
        $seller = User::factory()->create([
            'role' => 'seller',
        ]);

        $response = $this->actingAs($seller)->get(route('penjual.home'));

        $response
            ->assertOk()
            ->assertSee('Cari barang Mahasiswa Malang...')
            ->assertSee('banner-slider', false)
            ->assertSee('hero-kos-meja-belajar.jpg', false)
            ->assertSee('action="' . route('penjual.home') . '"', false);
    }

    public function test_seller_is_kept_on_seller_home_when_opening_regular_home(): void
    {
        $seller = User::factory()->create([
            'role' => 'seller',
        ]);

        $response = $this->actingAs($seller)->get(route('home', [
            'search' => 'meja',
        ]));

        $response->assertRedirect(route('penjual.home', [
            'search' => 'meja',
        ]));
    }

    public function test_old_seller_dashboard_redirects_to_seller_home(): void
    {
        $seller = User::factory()->create([
            'role' => 'seller',
        ]);

        $response = $this->actingAs($seller)->get(route('penjual.dashboard'));

        $response->assertRedirect(route('penjual.home'));
    }

    public function test_buyer_cannot_use_seller_home(): void
    {
        $buyer = User::factory()->create([
            'role' => 'buyer',
        ]);

        $response = $this->actingAs($buyer)->get(route('penjual.home'));

        $response->assertRedirect(route('home'));
    }
}
