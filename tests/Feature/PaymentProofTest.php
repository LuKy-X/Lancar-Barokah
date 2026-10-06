<?php

namespace Tests\Feature;

use App\Livewire\Payments\PaymentIndex;
use App\Livewire\Transactions\CreateTransaction;
use App\Livewire\Transactions\TransactionDetail;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentProofTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Customer $customer;

    private Product $product;

    private Transaction $transaction;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

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

        $this->transaction = Transaction::create([
            'invoice_no' => 'INV-2026-0001',
            'customer_id' => $this->customer->id,
            'user_id' => $this->user->id,
            'tanggal_transaksi' => Carbon::today(),
            'deadline' => Carbon::today()->addDays(7),
            'status_produksi' => 'cutting',
            'status_pembayaran' => 'belum_bayar',
            'status_pengambilan' => 'belum_diambil',
            'subtotal' => 500000,
            'biaya_pengiriman' => 0,
            'diskon' => 0,
            'total_tagihan' => 500000,
            'total_bayar' => 0,
            'sisa_tagihan' => 500000,
            'tahapan_produksi' => ['cutting', 'jahit', 'printing', 'packing', 'selesai'],
        ]);

        $this->transaction->details()->create([
            'product_id' => $this->product->id,
            'nama_produk_snapshot' => $this->product->nama_produk,
            'harga_snapshot' => 50000,
            'ukuran' => 'L',
            'jumlah' => 10,
            'subtotal' => 500000,
        ]);
    }

    public function test_payment_service_stores_uploaded_proof_of_payment(): void
    {
        $file = UploadedFile::fake()->image('struk-transfer.jpg');

        $payment = app(PaymentService::class)->addPayment(
            $this->transaction,
            [
                'jumlah_bayar' => 250000,
                'jenis_bayar' => 'dp',
                'metode_bayar' => 'transfer',
                'tanggal_bayar' => Carbon::today()->toDateString(),
                'bukti_bayar' => $file,
                'keterangan' => 'Transfer DP BCA',
            ],
            $this->user
        );

        $this->assertNotNull($payment->bukti_bayar);
        Storage::disk('public')->assertExists($payment->bukti_bayar);
        $this->assertFalse($payment->isBuktiBayarPdf());
        $this->assertNotNull($payment->bukti_bayar_url);
    }

    public function test_transaction_detail_component_can_record_payment_with_proof(): void
    {
        $file = UploadedFile::fake()->create('bukti-bayar.pdf', 500, 'application/pdf');

        Livewire::actingAs($this->user)
            ->test(TransactionDetail::class, ['transaction' => $this->transaction])
            ->set('paymentAmount', 500000)
            ->set('paymentType', 'pelunasan')
            ->set('paymentMethod', 'transfer')
            ->set('paymentDate', Carbon::today()->toDateString())
            ->set('paymentProof', $file)
            ->call('submitPayment')
            ->assertHasNoErrors()
            ->assertDispatched('toast');

        $this->transaction->refresh();
        $this->assertEquals(500000, $this->transaction->total_bayar);
        $this->assertEquals('lunas', $this->transaction->status_pembayaran);

        $payment = $this->transaction->payments()->first();
        $this->assertNotNull($payment->bukti_bayar);
        $this->assertTrue($payment->isBuktiBayarPdf());
        Storage::disk('public')->assertExists($payment->bukti_bayar);
    }

    public function test_payment_index_component_displays_proof_and_opens_viewer(): void
    {
        $file = UploadedFile::fake()->image('receipt.png');
        $storedPath = $file->store('payments', 'public');

        $payment = Payment::create([
            'transaction_id' => $this->transaction->id,
            'user_id' => $this->user->id,
            'tanggal_bayar' => Carbon::today(),
            'jenis_bayar' => 'dp',
            'metode_bayar' => 'transfer',
            'jumlah_bayar' => 200000,
            'bukti_bayar' => $storedPath,
        ]);

        $test = Livewire::actingAs($this->user)
            ->test(PaymentIndex::class)
            ->assertSee('Lihat Bukti')
            ->call('viewProof', $payment->id)
            ->assertSet('viewingProofType', 'image');

        $this->assertNotNull($test->get('viewingProofUrl'));

        $test->call('closeProofModal')
            ->assertSet('viewingProofUrl', null);
    }

    public function test_create_transaction_with_initial_payment_proof(): void
    {
        $file = UploadedFile::fake()->image('dp-transfer.jpg');

        Livewire::actingAs($this->user)
            ->test(CreateTransaction::class)
            ->set('selectedCustomerId', $this->customer->id)
            ->set('cart', [
                [
                    'product_id' => $this->product->id,
                    'kode_produk' => $this->product->kode_produk,
                    'nama_produk' => 'Kaos Combed 30s',
                    'kategori' => 'Kaos Combed',
                    'satuan' => 'pcs',
                    'is_sized' => false,
                    'sizes' => [],
                    'ukuran' => 'L',
                    'jumlah' => 10,
                    'harga' => 50000,
                    'subtotal' => 500000,
                    'catatan' => '',
                    'summary_sizes' => 'L: 10',
                ],
            ])
            ->set('initialPayment', 250000)
            ->set('metodeBayar', 'transfer')
            ->set('initialPaymentProof', $file)
            ->call('submitOrder')
            ->assertHasNoErrors();

        $transaction = Transaction::where('customer_id', $this->customer->id)->latest('id')->first();
        $this->assertNotNull($transaction);
        $this->assertEquals(250000, $transaction->total_bayar);

        $payment = $transaction->payments()->first();
        $this->assertNotNull($payment);
        $this->assertNotNull($payment->bukti_bayar);
        Storage::disk('public')->assertExists($payment->bukti_bayar);
    }
}
