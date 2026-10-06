<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Services\PaymentService;
use App\Services\ProductionService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Detail Transaksi')]
class TransactionDetail extends Component
{
    use WithFileUploads;

    public Transaction $transaction;

    // Payment Modal State
    public bool $showPaymentModal = false;

    public float $paymentAmount = 0;

    public string $paymentMethod = 'cash';

    public string $paymentType = '';

    public string $paymentDate = '';

    public string $paymentNotes = '';

    public $paymentProof = null;

    // Proof Viewer Modal State
    public ?string $viewingProofUrl = null;

    public ?string $viewingProofType = null;

    // Production Status Modal State
    public bool $showStatusModal = false;

    public string $newProductionStatus = '';

    public string $statusNotes = '';

    public function mount(Transaction $transaction): void
    {
        $this->transaction = $transaction;
        $this->paymentDate = Carbon::today()->toDateString();
        $this->newProductionStatus = $transaction->status_produksi;
    }

    public function openPaymentModal(): void
    {
        $sisa = $this->transaction->sisa_tagihan;
        $this->paymentAmount = $sisa;
        $this->paymentDate = Carbon::today()->toDateString();
        $this->paymentNotes = '';
        $this->paymentProof = null;

        if ($sisa <= 0) {
            $this->dispatch('toast', message: 'Transaksi ini sudah lunas.', type: 'warning');

            return;
        }

        // Auto-select type
        if ($this->transaction->payments()->count() === 0) {
            $this->paymentType = 'dp';
        } elseif ($this->paymentAmount >= $sisa) {
            $this->paymentType = 'pelunasan';
        } else {
            $this->paymentType = 'cicilan';
        }

        $this->showPaymentModal = true;
    }

    public function submitPayment(PaymentService $service): void
    {
        $this->validate([
            'paymentAmount' => ['required', 'numeric', 'min:1', 'max:'.$this->transaction->sisa_tagihan],
            'paymentMethod' => ['required', 'in:cash,transfer,qris'],
            'paymentDate' => ['required', 'date'],
            'paymentProof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:3072'],
        ]);

        try {
            $service->addPayment($this->transaction, [
                'jumlah_bayar' => (float) $this->paymentAmount,
                'jenis_bayar' => $this->paymentType ?: null,
                'metode_bayar' => $this->paymentMethod,
                'tanggal_bayar' => $this->paymentDate,
                'bukti_bayar' => $this->paymentProof,
                'keterangan' => $this->paymentNotes,
            ], auth()->user());

            $this->transaction->refresh();
            $this->showPaymentModal = false;
            $this->paymentProof = null;
            $this->dispatch('toast', message: 'Pembayaran berhasil dicatat.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    public function viewProof(int $paymentId): void
    {
        $payment = $this->transaction->payments()->find($paymentId);
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

    public function openStatusModal(): void
    {
        $this->newProductionStatus = $this->transaction->status_produksi;
        $this->statusNotes = '';
        $this->showStatusModal = true;
    }

    public function submitStatusUpdate(ProductionService $service): void
    {
        try {
            $service->updateStatus(
                $this->transaction,
                $this->newProductionStatus,
                $this->statusNotes,
                auth()->user()
            );

            $this->transaction->refresh();
            $this->showStatusModal = false;
            $this->dispatch('toast', message: 'Status produksi berhasil diperbarui.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    public function advanceStage(ProductionService $service): void
    {
        $nextStage = $this->transaction->getNextStage();

        if ($nextStage) {
            try {
                $stageLabel = Transaction::STAGE_LABELS[$nextStage] ?? strtoupper($nextStage);
                $service->updateStatus($this->transaction, $nextStage, 'Maju ke tahap '.$stageLabel, auth()->user());
                $this->transaction->refresh();
                $this->dispatch('toast', message: "Status pesanan berhasil maju ke tahap {$stageLabel}.", type: 'success');
            } catch (\Exception $e) {
                $this->dispatch('toast', message: $e->getMessage(), type: 'error');
            }
        }
    }

    public function markAsPickedUp(ProductionService $service): void
    {
        try {
            $service->markAsPickedUp($this->transaction);
            $this->transaction->refresh();
            $this->dispatch('toast', message: 'Status pengambilan berhasil diubah menjadi SUDAH DIAMBIL.', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.transactions.transaction-detail', [
            'details' => $this->transaction->details()->with('product')->get(),
            'payments' => $this->transaction->payments()->with('user')->latest('tanggal_bayar')->get(),
            'statusHistories' => $this->transaction->statusHistories()->with('user')->orderBy('waktu', 'desc')->get(),
        ]);
    }
}
