<?php

namespace Tests\Feature;

use App\Livewire\Categories\CategoryIndex;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Kategori',
            'username' => 'adminkategori',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_can_create_category(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CategoryIndex::class)
            ->call('openCreateModal')
            ->set('kode_kategori', 'KAT-CUSTOM-01')
            ->set('nama_kategori', 'Jersey Sepeda & Futsal')
            ->set('tipe', 'produk')
            ->set('deskripsi', 'Bahan dryfit milano dan bintik')
            ->set('is_active', true)
            ->call('saveCategory')
            ->assertHasNoErrors()
            ->assertDispatched('toast', message: 'Kategori baru berhasil ditambahkan.', type: 'success');

        $this->assertDatabaseHas('categories', [
            'kode_kategori' => 'KAT-CUSTOM-01',
            'nama_kategori' => 'Jersey Sepeda & Futsal',
            'tipe' => 'produk',
        ]);
    }

    public function test_can_toggle_category_status(): void
    {
        $this->actingAs($this->admin);

        $category = Category::create([
            'kode_kategori' => 'KAT-TEST',
            'nama_kategori' => 'Topi Custom',
            'tipe' => 'produk',
            'is_active' => true,
        ]);

        Livewire::test(CategoryIndex::class)
            ->call('toggleActive', $category->id);

        $this->assertFalse($category->fresh()->is_active);
    }

    public function test_cannot_delete_category_with_products(): void
    {
        $this->actingAs($this->admin);

        $category = Category::create([
            'kode_kategori' => 'KAT-WITH-PRD',
            'nama_kategori' => 'Kaos Polo Eksklusif',
            'tipe' => 'produk',
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $category->id,
            'kode_produk' => 'PRD-TEST-1',
            'nama_produk' => 'Polo Shirt Lacoste Pique',
            'satuan' => 'pcs',
            'harga_default' => 65000,
            'is_active' => true,
        ]);

        Livewire::test(CategoryIndex::class)
            ->call('confirmDelete', $category->id)
            ->call('deleteCategory')
            ->assertDispatched('toast', message: 'Kategori tidak dapat dihapus karena masih digunakan oleh produk atau jasa terdaftar.', type: 'error');

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'deleted_at' => null]);
    }

    public function test_can_delete_unused_category(): void
    {
        $this->actingAs($this->admin);

        $category = Category::create([
            'kode_kategori' => 'KAT-UNUSED',
            'nama_kategori' => 'Kategori Tidak Dipakai',
            'tipe' => 'bahan',
            'is_active' => true,
        ]);

        Livewire::test(CategoryIndex::class)
            ->call('confirmDelete', $category->id)
            ->call('deleteCategory')
            ->assertDispatched('toast', message: 'Kategori berhasil dihapus.', type: 'success');

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
    }
}
