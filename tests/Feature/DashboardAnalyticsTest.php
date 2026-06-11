<?php

namespace Tests\Feature;

use App\Filament\AdminDashboard;
use App\Filament\Resources\Users\UserResource;
use App\Models\Category;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_navigation_uses_indonesian_labels(): void
    {
        $this->assertSame('Dasbor', AdminDashboard::getNavigationLabel());
        $this->assertSame('Pengguna', UserResource::getNavigationLabel());
    }

    public function test_admin_can_render_the_analytics_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $buyer = User::factory()->create([
            'role' => 'buyer',
        ]);

        $category = Category::create([
            'name' => 'Elektronik',
            'slug' => 'elektronik',
        ]);

        $item = Item::create([
            'user_id' => $admin->id,
            'category_id' => $category->id,
            'name' => 'Laptop Analitik',
            'price' => 2500000,
            'location' => 'Lowokwaru',
            'phone' => '081234567890',
            'condition' => 'Baik',
            'status' => 'tersedia',
        ]);

        Transaction::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'price' => $item->price,
            'status' => 'success',
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard-analytics');

        $response
            ->assertOk()
            ->assertSee('Dasbor Analitik')
            ->assertSee('Ringkasan Utama')
            ->assertSee('Komposisi Barang')
            ->assertSee('Tren Transaksi')
            ->assertSee('Status Inventaris')
            ->assertSee('Transaksi Terbaru')
            ->assertSee('Laptop Analitik');
    }

    public function test_non_admin_cannot_open_the_analytics_dashboard(): void
    {
        $seller = User::factory()->create([
            'role' => 'seller',
        ]);

        $this->actingAs($seller)
            ->get('/admin/dashboard-analytics')
            ->assertForbidden();
    }
}
