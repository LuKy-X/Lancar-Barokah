<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Detail Pelanggan')]
class CustomerDetail extends Component
{
    use WithPagination;

    public Customer $customer;

    public function mount(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function render()
    {
        $transactions = $this->customer->transactions()
            ->with(['details', 'payments'])
            ->latest('tanggal_transaksi')
            ->paginate(10);

        $totalSpent = $this->customer->transactions()->sum('total_tagihan');
        $totalPaid = $this->customer->transactions()
            ->join('payments', 'transactions.id', '=', 'payments.transaction_id')
            ->sum('payments.jumlah_bayar');
        $activeOrders = $this->customer->transactions()->where('status_produksi', '!=', 'selesai')->count();

        return view('livewire.customers.customer-detail', [
            'transactions' => $transactions,
            'totalSpent' => $totalSpent,
            'totalPaid' => $totalPaid,
            'activeOrders' => $activeOrders,
        ]);
    }
}
