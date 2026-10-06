<?php

namespace App\Livewire\Transactions;

use App\Models\Transaction;
use App\Services\TransactionService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Edit Informasi Transaksi')]
class EditTransaction extends Component
{
    public Transaction $transaction;

    public string $deadline = '';

    public string $alamatPengiriman = '';

    public float $biayaPengiriman = 0;

    public float $diskon = 0;

    public string $catatan = '';

    /**
     * @var array<string>
     */
    public array $tahapanProduksi = [];

    public function mount(Transaction $transaction): void
    {
        $this->transaction = $transaction;
        $this->deadline = $transaction->deadline->toDateString();
        $this->alamatPengiriman = $transaction->alamat_pengiriman ?? '';
        $this->biayaPengiriman = (float) $transaction->biaya_pengiriman;
        $this->diskon = (float) $transaction->diskon;
        $this->catatan = $transaction->catatan ?? '';
        $this->tahapanProduksi = $transaction->tahapan_list;
    }

    public function toggleTahap(string $stage): void
    {
        if ($stage === 'selesai') {
            return;
        }

        $allOrdered = ['cutting', 'jahit', 'printing', 'packing', 'selesai'];

        if (in_array($stage, $this->tahapanProduksi, true)) {
            if (count($this->tahapanProduksi) <= 2) {
                $this->dispatch('toast', message: 'Minimal harus ada 1 tahap pengerjaan sebelum tahap Selesai.', type: 'warning');

                return;
            }

            if ($this->transaction->status_produksi === $stage) {
                $this->dispatch('toast', message: "Tahap '{$stage}' saat ini sedang berjalan pada pesanan ini dan tidak dapat dihapus sebelum status dipindahkan.", type: 'error');

                return;
            }

            $this->tahapanProduksi = array_values(array_diff($this->tahapanProduksi, [$stage]));
        } else {
            $this->tahapanProduksi[] = $stage;
            $this->tahapanProduksi = array_values(array_intersect($allOrdered, $this->tahapanProduksi));
        }
    }

    protected function rules(): array
    {
        return [
            'deadline' => ['required', 'date'],
            'biayaPengiriman' => ['numeric', 'min:0'],
            'diskon' => ['numeric', 'min:0'],
            'alamatPengiriman' => ['nullable', 'string'],
            'catatan' => ['nullable', 'string'],
        ];
    }

    public function save(TransactionService $service): void
    {
        $this->validate();

        $this->transaction->update([
            'deadline' => $this->deadline,
            'alamat_pengiriman' => $this->alamatPengiriman ?: null,
            'biaya_pengiriman' => $this->biayaPengiriman,
            'diskon' => $this->diskon,
            'catatan' => $this->catatan ?: null,
            'tahapan_produksi' => $this->tahapanProduksi,
        ]);

        $service->recalculateTotals($this->transaction);

        session()->flash('success', 'Data transaksi & tahapan alur berhasil diperbarui.');
        $this->redirect(route('transactions.detail', $this->transaction), navigate: true);
    }

    public function render()
    {
        return view('livewire.transactions.edit-transaction');
    }
}
