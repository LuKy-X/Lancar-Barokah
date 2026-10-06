<?php

namespace Tests\Feature;

use App\Livewire\Products\ProductIndex;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $petugas;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->petugas = User::create([
            'name' => 'Petugas Biasa',
            'username' => 'petugas',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'is_active' => true,
        ]);
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('transactions.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('production.board'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('payments.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('products.index'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('customers.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_petugas_can_access_orders_production_payments_customers_and_view_products(): void
    {
        $this->actingAs($this->petugas);

        $this->get(route('dashboard'))->assertStatus(200);
        $this->get(route('transactions.index'))->assertStatus(200);
        $this->get(route('transactions.create'))->assertStatus(200);
        $this->get(route('production.board'))->assertStatus(200);
        $this->get(route('payments.index'))->assertStatus(200);
        $this->get(route('customers.index'))->assertStatus(200);
        $this->get(route('products.index'))->assertStatus(200);
    }

    public function test_petugas_cannot_modify_products(): void
    {
        $category = Category::create([
            'kode_kategori' => 'KAT-001',
            'nama_kategori' => 'Kaos',
            'tipe' => 'produk',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'kode_produk' => 'PRD-LOCK',
            'nama_produk' => 'Kaos Polos Readonly',
            'satuan' => 'pcs',
            'harga_default' => 50000,
            'is_active' => true,
        ]);

        $this->actingAs($this->petugas);

        // Attempting to open create modal
        Livewire::test(ProductIndex::class)
            ->call('openCreateModal')
            ->assertStatus(403);

        // Attempting to open edit modal
        Livewire::test(ProductIndex::class)
            ->call('openEditModal', $product)
            ->assertStatus(403);

        // Attempting to save
        Livewire::test(ProductIndex::class)
            ->call('saveProduct')
            ->assertStatus(403);

        // Attempting to toggle active
        Livewire::test(ProductIndex::class)
            ->call('toggleActive', $product)
            ->assertStatus(403);

        // Attempting to delete
        Livewire::test(ProductIndex::class)
            ->call('confirmDelete', $product->id)
            ->assertStatus(403);

        Livewire::test(ProductIndex::class)
            ->call('deleteProduct')
            ->assertStatus(403);
    }

    public function test_petugas_cannot_access_categories_units_payment_methods_or_reports(): void
    {
        $this->actingAs($this->petugas);

        $this->get(route('categories.index'))->assertStatus(403);
        $this->get(route('units.index'))->assertStatus(403);
        $this->get(route('payment-methods.index'))->assertStatus(403);
        $this->get(route('reports.index'))->assertStatus(403);
        $this->get(route('users.index'))->assertStatus(403);
        $this->get(route('settings.company'))->assertStatus(403);
    }

    public function test_admin_has_unrestricted_access_to_all_features(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('dashboard'))->assertStatus(200);
        $this->get(route('transactions.index'))->assertStatus(200);
        $this->get(route('production.board'))->assertStatus(200);
        $this->get(route('payments.index'))->assertStatus(200);
        $this->get(route('customers.index'))->assertStatus(200);
        $this->get(route('products.index'))->assertStatus(200);
        $this->get(route('categories.index'))->assertStatus(200);
        $this->get(route('units.index'))->assertStatus(200);
        $this->get(route('payment-methods.index'))->assertStatus(200);
        $this->get(route('reports.index'))->assertStatus(200);
        $this->get(route('users.index'))->assertStatus(200);
        $this->get(route('settings.company'))->assertStatus(200);
    }
}
