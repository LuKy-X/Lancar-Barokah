<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use App\Services\PaymentService;
use App\Services\ProductionService;
use App\Services\TransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Customer $customer;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Petugas Kasir',
            'username' => 'kasir',
            'password' => Hash::make('password'),
            'role' => 'petugas',
            'is_active' => true,
        ]);

        $this->customer = Customer::create([
            'no_customer' => 'CUST-0001',
            'nama' => 'Komunitas Gowes',
            'no_hp' => '08123456789',
        ]);

        $category = Category::create([
            'kode_kategori' => 'KAT-001',
            'nama_kategori' => 'Kaos Combed',
            'tipe' => 'produk',
            'is_active' => true,
        ]);

        $this->product = Product::create([
            'category_id' => $category->id,
            'kode_produk' => 'PRD-001',
            'nama_produk' => 'Kaos Combed 30s',
            'satuan' => 'pcs',
            'harga_default' => 50000,
            'is_active' => true,
        ]);
    }

    public function test_can_create_transaction_with_server_side_calculation(): void
    {
        $service = app(TransactionService::class);

        $data = [
            'customer_id' => $this->customer->id,
            'tanggal_transaksi' => now()->toDateString(),
            'deadline' => now()->addDays(7)->toDateString(),
            'biaya_pengiriman' => 20000,
            'diskon' => 10000,
            'catatan' => 'Sablon plastisol',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'nama_produk' => $this->product->nama_produk,
                    'ukuran' => 'L',
                    'jumlah' => 20,
                    'harga' => 50000, // 20 * 50000 = 1,000,000
                ],
            ],
            'initial_payment' => 500000, // DP 500,000
            'metode_bayar' => 'transfer',
        ];

        $transaction = $service->createTransaction($data, $this->user);

        $this->assertNotNull($transaction->id);
        $this->assertStringStartsWith('INV-', $transaction->invoice_no);
        $this->assertEquals(1000000, $transaction->subtotal);
        // Total = 1,000,000 + 20,000 - 10,000 = 1,010,000
        $this->assertEquals(1010000, $transaction->total_tagihan);
        $this->assertEquals('cutting', $transaction->status_produksi);
        $this->assertEquals('dp', $transaction->status_pembayaran);
        $this->assertEquals('belum_diambil', $transaction->status_pengambilan);

        // Check details snapshot
        $this->assertDatabaseHas('transaction_details', [
            'transaction_id' => $transaction->id,
            'nama_produk_snapshot' => 'Kaos Combed 30s',
            'jumlah' => 20,
            'subtotal' => 1000000,
        ]);

        // Check payment record
        $this->assertDatabaseHas('payments', [
            'transaction_id' => $transaction->id,
            'jumlah_bayar' => 500000,
            'jenis_bayar' => 'dp',
            'metode_bayar' => 'transfer',
        ]);

        // Check status history
        $this->assertDatabaseHas('transaction_status_histories', [
            'transaction_id' => $transaction->id,
            'status' => 'cutting',
        ]);
    }

    public function test_can_add_payment_and_automatically_mark_as_lunas(): void
    {
        $txService = app(TransactionService::class);
        $payService = app(PaymentService::class);

        $transaction = $txService->createTransaction([
            'customer_id' => $this->customer->id,
            'tanggal_transaksi' => now()->toDateString(),
            'deadline' => now()->addDays(7)->toDateString(),
            'biaya_pengiriman' => 0,
            'diskon' => 0,
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'nama_produk' => $this->product->nama_produk,
                    'jumlah' => 10,
                    'harga' => 50000, // 500,000
                ],
            ],
            'initial_payment' => 200000, // DP 200,000
            'metode_bayar' => 'cash',
        ], $this->user);

        $this->assertEquals('dp', $transaction->status_pembayaran);
        $this->assertEquals(300000, $transaction->sisa_tagihan);

        // Add remaining payment 300,000
        $payService->addPayment($transaction, [
            'jumlah_bayar' => 300000,
            'tanggal_bayar' => now()->toDateString(),
            'metode_bayar' => 'cash',
            'keterangan' => 'Pelunasan tunai di kasir',
        ], $this->user);

        $transaction->refresh();
        $this->assertEquals(0, $transaction->sisa_tagihan);
        $this->assertEquals('lunas', $transaction->status_pembayaran);
    }

    public function test_cannot_pick_up_order_unless_production_finished_and_payment_lunas(): void
    {
        $txService = app(TransactionService::class);
        $prodService = app(ProductionService::class);
        $payService = app(PaymentService::class);

        $transaction = $txService->createTransaction([
            'customer_id' => $this->customer->id,
            'tanggal_transaksi' => now()->toDateString(),
            'deadline' => now()->addDays(7)->toDateString(),
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'nama_produk' => $this->product->nama_produk,
                    'jumlah' => 2,
                    'harga' => 50000,
                ],
            ],
            'initial_payment' => 0,
        ], $this->user);

        // 1. Trying to pick up while in cutting and belum_bayar should fail
        $this->expectException(InvalidArgumentException::class);
        $prodService->markAsPickedUp($transaction);
    }

    public function test_can_pick_up_when_finished_and_lunas(): void
    {
        $txService = app(TransactionService::class);
        $prodService = app(ProductionService::class);
        $payService = app(PaymentService::class);

        $transaction = $txService->createTransaction([
            'customer_id' => $this->customer->id,
            'tanggal_transaksi' => now()->toDateString(),
            'deadline' => now()->addDays(7)->toDateString(),
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'nama_produk' => $this->product->nama_produk,
                    'jumlah' => 2,
                    'harga' => 50000,
                ],
            ],
            'initial_payment' => 100000, // Full payment
            'metode_bayar' => 'cash',
        ], $this->user);

        $this->assertEquals('lunas', $transaction->status_pembayaran);

        // Advance to selesai
        $prodService->updateStatus($transaction, 'selesai', 'Produksi siap', $this->user);

        // Now pick up should succeed
        $prodService->markAsPickedUp($transaction);
        $this->assertEquals('diambil', $transaction->fresh()->status_pengambilan);
    }

    public function test_multi_size_breakdown_order_creation(): void
    {
        $service = app(TransactionService::class);

        $data = [
            'customer_id' => $this->customer->id,
            'tanggal_transaksi' => now()->toDateString(),
            'deadline' => now()->addDays(5)->toDateString(),
            'biaya_pengiriman' => 15000,
            'diskon' => 5000,
            'catatan' => 'Pesanan kaos seragam dengan multi-size',
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'nama_produk' => 'Kaos Polo Eksklusif (Custom)',
                    'sizes' => [
                        'S' => 10,
                        'M' => 20,
                        'L' => 15,
                        'XL' => 5,
                    ],
                    'harga' => 65000,
                ],
            ],
            'initial_payment' => 1000000,
            'metode_bayar' => 'transfer',
        ];

        $transaction = $service->createTransaction($data, $this->user);

        // Total qty = 10 + 20 + 15 + 5 = 50 pcs
        // Subtotal = 50 * 65,000 = 3,250,000
        // Total tagihan = 3,250,000 + 15,000 - 5,000 = 3,260,000
        $this->assertEquals(3250000, $transaction->subtotal);
        $this->assertEquals(3260000, $transaction->total_tagihan);
        $this->assertEquals(4, $transaction->details()->count());

        // Verify each size row exists with snapshot name
        $this->assertDatabaseHas('transaction_details', [
            'transaction_id' => $transaction->id,
            'nama_produk_snapshot' => 'Kaos Polo Eksklusif (Custom)',
            'ukuran' => 'M',
            'jumlah' => 20,
            'subtotal' => 1300000,
        ]);
        $this->assertDatabaseHas('transaction_details', [
            'transaction_id' => $transaction->id,
            'nama_produk_snapshot' => 'Kaos Polo Eksklusif (Custom)',
            'ukuran' => 'S',
            'jumlah' => 10,
            'subtotal' => 650000,
        ]);
    }

    public function test_cannot_record_payment_exceeding_remaining_balance(): void
    {
        $txService = app(TransactionService::class);
        $payService = app(PaymentService::class);

        $transaction = $txService->createTransaction([
            'customer_id' => $this->customer->id,
            'tanggal_transaksi' => now()->toDateString(),
            'deadline' => now()->addDays(7)->toDateString(),
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'nama_produk' => $this->product->nama_produk,
                    'jumlah' => 2,
                    'harga' => 50000, // total 100,000
                ],
            ],
            'initial_payment' => 50000,
        ], $this->user);

        // Sisa tagihan is 50,000. Paying 60,000 should throw InvalidArgumentException
        $this->expectException(InvalidArgumentException::class);
        $payService->addPayment($transaction, [
            'jumlah_bayar' => 60000,
            'tanggal_bayar' => now()->toDateString(),
            'metode_bayar' => 'cash',
        ], $this->user);
    }

    public function test_cannot_delete_transaction_with_payments(): void
    {
        $txService = app(TransactionService::class);

        $transaction = $txService->createTransaction([
            'customer_id' => $this->customer->id,
            'tanggal_transaksi' => now()->toDateString(),
            'deadline' => now()->addDays(7)->toDateString(),
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'nama_produk' => $this->product->nama_produk,
                    'jumlah' => 1,
                    'harga' => 50000,
                ],
            ],
            'initial_payment' => 50000,
        ], $this->user);

        // Admin cannot delete order with payment
        $admin = User::create([
            'name' => 'Admin Utama',
            'username' => 'admin_test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->assertFalse($admin->can('delete', $transaction));
    }

    public function test_can_create_transaction_with_custom_production_stages_such_as_sablon_saja(): void
    {
        $txService = app(TransactionService::class);
        $prodService = app(ProductionService::class);

        // Scenario: Customer brings their own shirts, so only printing -> packing -> selesai
        $transaction = $txService->createTransaction([
            'customer_id' => $this->customer->id,
            'tanggal_transaksi' => now()->toDateString(),
            'deadline' => now()->addDays(5)->toDateString(),
            'items' => [
                [
                    'product_id' => $this->product->id,
                    'nama_produk' => 'Jasa Sablon Kaos Sendiri',
                    'jumlah' => 24,
                    'harga' => 15000,
                ],
            ],
            'tahapan_produksi' => ['printing', 'packing', 'selesai'],
        ], $this->user);

        // Initial stage must immediately be 'printing', not 'cutting'
        $this->assertEquals('printing', $transaction->status_produksi);
        $this->assertEquals(['printing', 'packing', 'selesai'], $transaction->tahapan_list);
        $this->assertEquals('Sablon Saja (Bawa Baju)', $transaction->pipeline_label);
        $this->assertTrue($transaction->hasStage('printing'));
        $this->assertFalse($transaction->hasStage('cutting'));
        $this->assertFalse($transaction->hasStage('jahit'));

        // Next stage must be 'packing' (skipping cutting and jahit)
        $this->assertEquals('packing', $transaction->getNextStage());

        // Advance to packing
        $prodService->updateStatus($transaction, $transaction->getNextStage(), 'Sablon selesai, lanjut packing', $this->user);
        $transaction->refresh();
        $this->assertEquals('packing', $transaction->status_produksi);
        $this->assertEquals('selesai', $transaction->getNextStage());

        // Advance to selesai
        $prodService->updateStatus($transaction, $transaction->getNextStage(), 'Kemas selesai, siap diambil', $this->user);
        $transaction->refresh();
        $this->assertEquals('selesai', $transaction->status_produksi);
        $this->assertNull($transaction->getNextStage());
    }
}
