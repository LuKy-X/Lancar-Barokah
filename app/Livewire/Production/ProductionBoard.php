<?php

namespace App\Livewire\Production;

use App\Models\Transaction;
use App\Services\ProductionService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Monitoring Produksi Sablon')]
class ProductionBoard extends Component
{
    public string $search = '';

    public bool $onlyOverdue = false;

    // Status Update Modal State
    public bool $showStatusModal = false;

    public ?int $selectedTransactionId = null;

    public ?Transaction $selectedTransaction = null;

    public string $newStatus = '';

    public string $statusNotes = '';

    public function openStatusModal(int $transactionId): void
    {
        $this->selectedTransactionId = $transactionId;
        $this->selectedTransaction = Transaction::findOrFail($transactionId);
        $this->newStatus = $this->selectedTransaction->status_produksi;
        $this->statusNotes = '';
        $this->showStatusModal = true;
    }

    public function advanceStage(int $transactionId, ProductionService $service): void
    {
        $transaction = Transaction::findOrFail($transactionId);
        $nextStage = $transaction->getNextStage();

        if ($nextStage) {
            $stageLabel = Transaction::STAGE_LABELS[$nextStage] ?? strtoupper($nextStage);
            $service->updateStatus($transaction, $nextStage, 'Maju ke tahap '.$stageLabel, auth()->user());
            $this->dispatch('toast', message: "Transaksi {$transaction->invoice_no} bergeser ke tahap {$stageLabel}.", type: 'success');
        } else {
            $this->dispatch('toast', message: "Transaksi {$transaction->invoice_no} sudah berada pada tahap akhir.", type: 'warning');
        }
    }

    public function submitStatusModal(ProductionService $service): void
    {
        if (! $this->selectedTransactionId) {
            return;
        }

        $transaction = Transaction::findOrFail($this->selectedTransactionId);
        $service->updateStatus($transaction, $this->newStatus, $this->statusNotes, auth()->user());

        $this->showStatusModal = false;
        $this->dispatch('toast', message: "Status {$transaction->invoice_no} berhasil diperbarui.", type: 'success');
    }

    public function render()
    {
        $query = Transaction::query()
            ->with(['customer', 'details'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('invoice_no', 'like', '%'.$this->search.'%')
                        ->orWhereHas('customer', fn ($c) => $c->where('nama', 'like', '%'.$this->search.'%'));
                });
            })
            ->when($this->onlyOverdue, function ($q) {
                $q->where('status_produksi', '!=', 'selesai')
                    ->whereDate('deadline', '<', now()->toDateString());
            })
            ->orderBy('deadline', 'asc');

        $allOrders = $query->get();

        $columns = [
            'cutting' => [
                'title' => 'Cutting',
                'desc' => 'Pemotongan Bahan',
                'color' => 'blue',
                'items' => $allOrders->where('status_produksi', 'cutting'),
            ],
            'jahit' => [
                'title' => 'Jahit',
                'desc' => 'Penjahitan Pola',
                'color' => 'purple',
                'items' => $allOrders->where('status_produksi', 'jahit'),
            ],
            'printing' => [
                'title' => 'Printing',
                'desc' => 'Sablon & Bordir',
                'color' => 'amber',
                'items' => $allOrders->where('status_produksi', 'printing'),
            ],
            'packing' => [
                'title' => 'Packing',
                'desc' => 'Finishing & Kemas',
                'color' => 'teal',
                'items' => $allOrders->where('status_produksi', 'packing'),
            ],
            'selesai' => [
                'title' => 'Selesai',
                'desc' => 'Siap Diambil',
                'color' => 'emerald',
                'items' => $allOrders->where('status_produksi', 'selesai'),
            ],
        ];

        $overdueCount = Transaction::where('status_produksi', '!=', 'selesai')
            ->whereDate('deadline', '<', now()->toDateString())
            ->count();

        return view('livewire.production.production-board', [
            'columns' => $columns,
            'overdueCount' => $overdueCount,
        ]);
    }
}
