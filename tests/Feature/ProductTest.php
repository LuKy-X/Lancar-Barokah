<?php

namespace Tests\Feature;

use App\Livewire\Products\ProductIndex;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Test',
            'username' => 'admintest',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'kode_kategori' => 'KAT-TEST',
            'nama_kategori' => 'Kaos & Pakaian',
            'tipe' => 'produk',
            'is_active' => true,
        ]);
    }

    public function test_can_create_product(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(ProductIndex::class)
            ->call('openCreateModal')
            ->set('kode_produk', 'PRD-TEST-01')
            ->set('nama_produk', 'Kaos Polos Raglan 30s')
            ->set('category_id', $this->category->id)
            ->set('satuan', 'pcs')
            ->set('harga_default', 48000)
            ->set('is_active', true)
            ->call('saveProduct')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('products', [
            'kode_produk' => 'PRD-TEST-01',
            'nama_produk' => 'Kaos Polos Raglan 30s',
            'category_id' => $this->category->id,
            'harga_default' => 48000,
        ]);
    }

    public function test_can_toggle_product_status(): void
    {
        $this->actingAs($this->admin);

        $product = Product::create([
            'category_id' => $this->category->id,
            'kode_produk' => 'PRD-001',
            'nama_produk' => 'Kaos Combed',
            'satuan' => 'pcs',
            'harga_default' => 45000,
            'is_active' => true,
        ]);

        Livewire::test(ProductIndex::class)
            ->call('toggleActive', $product->id);

        $this->assertFalse($product->fresh()->is_active);
    }

    public function test_cannot_delete_product_with_transaction_details(): void
    {
        $this->actingAs($this->admin);

        $customer = Customer::create([
            'no_customer' => 'CUST-0001',
            'nama' => 'Test Customer',
        ]);

        $product = Product::create([
            'category_id' => $this->category->id,
            'kode_produk' => 'PRD-USED',
            'nama_produk' => 'Kaos Yang Digunakan',
            'satuan' => 'pcs',
            'harga_default' => 50000,
            'is_active' => true,
        ]);

        $transaction = Transaction::create([
            'invoice_no' => 'INV-20260915-001',
            'customer_id' => $customer->id,
            'user_id' => $this->admin->id,
            'tanggal_transaksi' => now()->toDateString(),
            'deadline' => now()->addDays(7)->toDateString(),
            'subtotal' => 50000,
            'total_tagihan' => 50000,
            'status_produksi' => 'cutting',
            'status_pembayaran' => 'belum_bayar',
            'status_pengambilan' => 'belum_diambil',
        ]);

        TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'product_id' => $product->id,
            'nama_produk_snapshot' => $product->nama_produk,
            'jumlah' => 1,
            'harga_snapshot' => 50000,
            'subtotal' => 50000,
        ]);

        Livewire::test(ProductIndex::class)
            ->call('confirmDelete', $product->id)
            ->call('deleteProduct')
            ->assertDispatched('toast', message: 'Produk tidak dapat dihapus karena tercatat dalam transaksi.', type: 'error');

        $this->assertDatabaseHas('products', ['id' => $product->id, 'deleted_at' => null]);
    }
}
