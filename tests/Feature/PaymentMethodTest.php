<?php

namespace Tests\Feature;

use App\Livewire\PaymentMethods\PaymentMethodIndex;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin Keuangan',
            'username' => 'adminkeuangan',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_can_create_payment_method(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(PaymentMethodIndex::class)
            ->call('openCreateModal')
            ->set('kode_metode', 'PAY-BRI')
            ->set('nama_metode', 'Bank BRI')
            ->set('tipe', 'transfer')
            ->set('nomor_rekening', '012301000123501')
            ->set('atas_nama', 'CV LANCAR BAROKAH')
            ->set('keterangan', 'KCP Sleman')
            ->set('is_active', true)
            ->call('saveMethod')
            ->assertHasNoErrors()
            ->assertDispatched('toast', message: 'Rekening/Metode pembayaran baru berhasil ditambahkan.', type: 'success');

        $this->assertDatabaseHas('payment_methods', [
            'kode_metode' => 'PAY-BRI',
            'nama_metode' => 'Bank BRI',
            'nomor_rekening' => '012301000123501',
        ]);
    }

    public function test_can_toggle_payment_method_status(): void
    {
        $this->actingAs($this->admin);

        $method = PaymentMethod::create([
            'kode_metode' => 'PAY-BSI',
            'nama_metode' => 'Bank Syariah Indonesia',
            'tipe' => 'transfer',
            'nomor_rekening' => '7123456789',
            'atas_nama' => 'CV LANCAR BAROKAH',
            'is_active' => true,
        ]);

        Livewire::test(PaymentMethodIndex::class)
            ->call('toggleActive', $method->id);

        $this->assertFalse($method->fresh()->is_active);
    }

    public function test_cannot_delete_payment_method_with_payments(): void
    {
        $this->actingAs($this->admin);

        $customer = Customer::create([
            'no_customer' => 'CUST-TEST',
            'nama' => 'Pelanggan Uji Coba',
        ]);

        $transaction = Transaction::create([
            'invoice_no' => 'INV-TEST-001',
            'customer_id' => $customer->id,
            'user_id' => $this->admin->id,
            'tanggal_transaksi' => now()->toDateString(),
            'deadline' => now()->addDays(7)->toDateString(),
            'subtotal' => 100000,
            'total_tagihan' => 100000,
            'status_produksi' => 'cutting',
            'status_pembayaran' => 'dp',
            'status_pengambilan' => 'belum_diambil',
        ]);

        $method = PaymentMethod::create([
            'kode_metode' => 'PAY-USED',
            'nama_metode' => 'Bank Mandiri Giro',
            'tipe' => 'transfer',
            'nomor_rekening' => '1370001234567',
            'atas_nama' => 'CV LANCAR BAROKAH',
            'is_active' => true,
        ]);

        Payment::create([
            'transaction_id' => $transaction->id,
            'user_id' => $this->admin->id,
            'payment_method_id' => $method->id,
            'tanggal_bayar' => now()->toDateString(),
            'jenis_bayar' => 'dp',
            'metode_bayar' => 'transfer',
            'jumlah_bayar' => 50000,
        ]);

        Livewire::test(PaymentMethodIndex::class)
            ->call('confirmDelete', $method->id)
            ->call('deleteMethod')
            ->assertDispatched('toast', message: 'Metode pembayaran tidak dapat dihapus karena tercatat dalam transaksi pembayaran.', type: 'error');

        $this->assertDatabaseHas('payment_methods', ['id' => $method->id, 'deleted_at' => null]);
    }

    public function test_can_delete_unused_payment_method(): void
    {
        $this->actingAs($this->admin);

        $method = PaymentMethod::create([
            'kode_metode' => 'PAY-UNUSED',
            'nama_metode' => 'Metode Lama',
            'tipe' => 'cash',
            'is_active' => true,
        ]);

        Livewire::test(PaymentMethodIndex::class)
            ->call('confirmDelete', $method->id)
            ->call('deleteMethod')
            ->assertDispatched('toast', message: 'Metode pembayaran berhasil dihapus.', type: 'success');

        $this->assertSoftDeleted('payment_methods', ['id' => $method->id]);
    }
}
