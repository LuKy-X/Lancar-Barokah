<?php

namespace Tests\Feature;

use App\Livewire\Customers\CustomerIndex;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

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
    }

    public function test_can_create_customer(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(CustomerIndex::class)
            ->call('openCreateModal')
            ->set('nama', 'Komunitas Motor Solo')
            ->set('no_hp', '08123456789')
            ->set('email', 'motor@solo.test')
            ->set('alamat', 'Jl. Slamet Riyadi No. 1')
            ->call('saveCustomer')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('customers', [
            'nama' => 'Komunitas Motor Solo',
            'no_customer' => 'CUST-0001',
        ]);
    }

    public function test_cannot_delete_customer_with_transactions(): void
    {
        $this->actingAs($this->admin);

        $customer = Customer::create([
            'no_customer' => 'CUST-0001',
            'nama' => 'Customer Langganan',
            'no_hp' => '0811111111',
        ]);

        Transaction::create([
            'invoice_no' => 'INV-20260915-001',
            'customer_id' => $customer->id,
            'user_id' => $this->admin->id,
            'tanggal_transaksi' => now()->toDateString(),
            'deadline' => now()->addDays(7)->toDateString(),
            'subtotal' => 100000,
            'total_tagihan' => 100000,
            'status_produksi' => 'cutting',
            'status_pembayaran' => 'belum_bayar',
            'status_pengambilan' => 'belum_diambil',
        ]);

        Livewire::test(CustomerIndex::class)
            ->call('confirmDelete', $customer->id)
            ->call('deleteCustomer')
            ->assertDispatched('toast', message: 'Pelanggan tidak dapat dihapus karena memiliki riwayat transaksi.', type: 'error');

        $this->assertDatabaseHas('customers', ['id' => $customer->id, 'deleted_at' => null]);
    }
}
