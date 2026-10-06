<?php

namespace App\Livewire\Payments;

use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Data Pembayaran')]
class PaymentIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $jenisFilter = '';

    public string $metodeFilter = '';

    public string $startDate = '';

    public string $endDate = '';

    // Proof Viewer Modal State
    public ?string $viewingProofUrl = null;

    public ?string $viewingProofType = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingJenisFilter(): void
    {
        $this->resetPage();
    }

    public function updatingMetodeFilter(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'jenisFilter', 'metodeFilter', 'startDate', 'endDate']);
        $this->resetPage();
    }

    public function viewProof(int $paymentId): void
    {
        $payment = Payment::find($paymentId);
        if ($payment && $payment->bukti_bayar) {
            $this->viewingProofUrl = $payment->bukti_bayar_url;
            $this->viewingProofType = $payment->isBuktiBayarPdf() ? 'pdf' : 'image';
        }
    }

    public function closeProofModal(): void
    {
        $this->viewingProofUrl = null;
        $this->viewingProofType = null;
    }

    public function render()
    {
        $payments = Payment::query()
            ->with(['transaction.customer', 'user', 'paymentMethod'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->whereHas('transaction', function ($t) {
                        $t->where('invoice_no', 'like', '%'.$this->search.'%')
                            ->orWhereHas('customer', fn ($c) => $c->where('nama', 'like', '%'.$this->search.'%'));
                    })->orWhere('keterangan', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->jenisFilter, fn ($q) => $q->where('jenis_bayar', $this->jenisFilter))
            ->when($this->metodeFilter, fn ($q) => $q->where('metode_bayar', $this->metodeFilter))
            ->when($this->startDate, fn ($q) => $q->whereDate('tanggal_bayar', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('tanggal_bayar', '<=', $this->endDate))
            ->latest('tanggal_bayar')
            ->paginate(15);

        // Compute summary metrics for filtered range
        $totalCash = Payment::where('metode_bayar', 'cash')
            ->when($this->startDate, fn ($q) => $q->whereDate('tanggal_bayar', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('tanggal_bayar', '<=', $this->endDate))
            ->sum('jumlah_bayar');

        $totalTransfer = Payment::where('metode_bayar', 'transfer')
            ->when($this->startDate, fn ($q) => $q->whereDate('tanggal_bayar', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('tanggal_bayar', '<=', $this->endDate))
            ->sum('jumlah_bayar');

        $totalQris = Payment::where('metode_bayar', 'qris')
            ->when($this->startDate, fn ($q) => $q->whereDate('tanggal_bayar', '>=', $this->startDate))
            ->when($this->endDate, fn ($q) => $q->whereDate('tanggal_bayar', '<=', $this->endDate))
            ->sum('jumlah_bayar');

        $totalAll = $totalCash + $totalTransfer + $totalQris;

        return view('livewire.payments.payment-index', [
            'payments' => $payments,
            'totalCash' => $totalCash,
            'totalTransfer' => $totalTransfer,
            'totalQris' => $totalQris,
            'totalAll' => $totalAll,
        ]);
    }
}
