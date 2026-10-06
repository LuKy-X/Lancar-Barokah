<?php

namespace Tests\Feature;

use App\Livewire\Dashboard\DashboardIndex;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class DashboardRoleTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $petugas;

    private Customer $customer;

    private Transaction $transaction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Owner Admin',
            'username' => 'owner',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->petugas = User::create([
            'name' => 'Staf Kasir',
            'username' => 'kasir',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'is_active' => true,
        ]);

        $this->customer = Customer::create([
            'no_customer' => 'CUST-0001',
            'nama' => 'Budi Santoso',
            'no_hp' => '08123456789',
        ]);

        $category = Category::create([
            'kode_kategori' => 'KAT-001',
            'nama_kategori' => 'Kaos Combed',
            'tipe' => 'produk',
            'is_active' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'kode_produk' => 'PRD-001',
            'nama_produk' => 'Kaos Combed 30s',
            'satuan' => 'pcs',
            'harga_default' => 50000,
            'is_active' => true,
        ]);

        $this->transaction = Transaction::create([
            'invoice_no' => 'INV-2026-0001',
            'customer_id' => $this->customer->id,
            'user_id' => $this->petugas->id,
            'tanggal_transaksi' => Carbon::today(),
            'deadline' => Carbon::today()->addDays(2),
            'status_produksi' => 'selesai',
            'status_pembayaran' => 'lunas',
            'status_pengambilan' => 'belum_diambil',
            'subtotal' => 500000,
            'biaya_pengiriman' => 0,
            'diskon' => 0,
            'total_tagihan' => 500000,
            'total_bayar' => 500000,
            'sisa_tagihan' => 0,
            'tahapan_produksi' => ['cutting', 'jahit', 'printing', 'packing', 'selesai'],
        ]);

        $this->transaction->details()->create([
            'product_id' => $product->id,
            'nama_produk_snapshot' => $product->nama_produk,
            'harga_snapshot' => 50000,
            'ukuran' => 'L',
            'jumlah' => 10,
            'subtotal' => 500000,
        ]);
    }

    public function test_admin_sees_executive_financial_metrics_and_analytics(): void
    {
        Livewire::actingAs($this->admin)
            ->test(DashboardIndex::class)
            ->assertSee('Dashboard Eksekutif')
            ->assertSee('Omzet Bulan Ini')
            ->assertSee('Total Kas Masuk')
            ->assertSee('Sisa Piutang Usaha')
            ->assertSee('Metode Pembayaran')
            ->assertSee('Top Produk')
            ->assertSee('Laporan Bisnis')
            ->assertDontSee('Pusat Kerja Operasional');
    }

    public function test_petugas_sees_operational_workspace_and_no_financial_metrics(): void
    {
        Livewire::actingAs($this->petugas)
            ->test(DashboardIndex::class)
            ->assertSee('Pusat Kerja Operasional')
            ->assertSee('Order Masuk Hari Ini')
            ->assertSee('Sedang Dikerjakan')
            ->assertSee('Deadline Mendesak')
            ->assertSee('Siap Diambil Pelanggan')
            ->assertSee('Prioritas Target Deadline Produksi')
            ->assertSee('Pesanan Siap Diambil')
            ->assertDontSee('Dashboard Eksekutif')
            ->assertDontSee('Omzet Bulan Ini')
            ->assertDontSee('Sisa Piutang Usaha')
            ->assertDontSee('Laporan Bisnis')
            ->assertDontSee('Metode Pembayaran')
            ->assertDontSee('Top Produk');
    }

    public function test_petugas_can_mark_completed_order_as_picked_up_from_dashboard(): void
    {
        Livewire::actingAs($this->petugas)
            ->test(DashboardIndex::class)
            ->assertSee('✓ Serahkan Barang')
            ->call('markAsPickedUp', $this->transaction->id)
            ->assertDispatched('toast');

        $this->transaction->refresh();
        $this->assertEquals('diambil', $this->transaction->status_pengambilan);
    }
}
