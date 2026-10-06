<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Daftar Transaksi')]
class TransactionIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusProduksi = '';

    public string $statusPembayaran = '';

    public string $statusPengambilan = '';

    public string $startDate = '';

    public string $endDate = '';

    public ?int $deleteId = null;

    public bool $confirmingDelete = false;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusProduksi(): void
    {
        $this->resetPage();
    }

    public function updatingStatusPembayaran(): void
    {
        $this->resetPage();
    }

    public function updatingStatusPengambilan(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'statusProduksi', 'statusPembayaran', 'statusPengambilan', 'startDate', 'endDate']);
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteTransaction(): void
    {
        if (! $this->deleteId || ! auth()->user()->isAdmin()) {
            return;
        }

        $transaction = Transaction::findOrFail($this->deleteId);
        $transaction->details()->delete();
        $transaction->payments()->delete();
        $transaction->statusHistories()->delete();
        $transaction->delete();

        $this->confirmingDelete = false;
        $this->deleteId = null;
        $this->dispatch('toast', message: 'Transaksi berhasil dihapus dari sistem.', type: 'success');
    }

    public function render()
    {
        $transactions = Transaction::query()
            ->with(['customer', 'user', 'details', 'payments'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('invoice_no', 'like', '%'.$this->search.'%')
                        ->orWhereHas('customer', fn ($c) => $c->where('nama', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('details', fn ($d) => $d->where('nama_produk_snapshot', 'like', '%'.$this->search.'%'));
                });
            })
            ->when($this->statusProduksi, fn ($q) => $q->where('status_produksi', $this->statusProduksi))
            ->when($this->statusPembayaran, fn ($q) => $q->where('status_pembayaran', $this->statusPembayaran))
            ->when($this->statusPengambilan, fn ($q) => $q->where('status_pengambilan', $this->statusPengambilan))
            ->when($this->startDate, fn ($q) => $q->whereDate('tanggal_transaksi', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('tanggal_transaksi', '<=', $this->endDate))
            ->latest('tanggal_transaksi')
            ->paginate(15);

        return view('livewire.transactions.transaction-index', [
            'transactions' => $transactions,
        ]);
    }
}
