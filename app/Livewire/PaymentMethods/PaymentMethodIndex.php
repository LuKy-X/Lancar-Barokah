<?php

namespace App\Livewire\PaymentMethods;

use App\Models\PaymentMethod;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Master Rekening & Metode Pembayaran')]
class PaymentMethodIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $tipeFilter = '';

    public ?int $deleteId = null;

    public bool $confirmingDelete = false;

    public bool $showModal = false;

    public ?int $methodId = null;

    public string $kode_metode = '';

    public string $nama_metode = '';

    public string $tipe = 'transfer';

    public string $nomor_rekening = '';

    public string $atas_nama = '';

    public string $keterangan = '';

    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'kode_metode' => ['required', 'string', 'max:30', 'unique:payment_methods,kode_metode,'.$this->methodId],
            'nama_metode' => ['required', 'string', 'max:255'],
            'tipe' => ['required', 'in:transfer,qris,cash'],
            'nomor_rekening' => ['nullable', 'string', 'max:100'],
            'atas_nama' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTipeFilter(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->reset(['methodId', 'nama_metode', 'nomor_rekening', 'atas_nama', 'keterangan']);
        $this->tipe = 'transfer';
        $this->is_active = true;

        $count = PaymentMethod::withTrashed()->count() + 1;
        $this->kode_metode = 'PAY-'.str_pad((string) $count, 3, '0', STR_PAD_LEFT);

        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEditModal(PaymentMethod $method): void
    {
        $this->methodId = $method->id;
        $this->kode_metode = $method->kode_metode;
        $this->nama_metode = $method->nama_metode;
        $this->tipe = $method->tipe;
        $this->nomor_rekening = (string) $method->nomor_rekening;
        $this->atas_nama = (string) $method->atas_nama;
        $this->keterangan = (string) $method->keterangan;
        $this->is_active = $method->is_active;

        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->saveMethod();
    }

    public function saveMethod(): void
    {
        $this->validate();

        $data = [
            'kode_metode' => strtoupper(trim($this->kode_metode)),
            'nama_metode' => $this->nama_metode,
            'tipe' => $this->tipe,
            'nomor_rekening' => $this->nomor_rekening ?: null,
            'atas_nama' => $this->atas_nama ?: null,
            'keterangan' => $this->keterangan ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->methodId) {
            $method = PaymentMethod::findOrFail($this->methodId);
            $method->update($data);
            $msg = 'Rekening/Metode pembayaran berhasil diperbarui.';
        } else {
            PaymentMethod::create($data);
            $msg = 'Rekening/Metode pembayaran baru berhasil ditambahkan.';
        }

        $this->showModal = false;
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function toggleActive(int $id): void
    {
        $method = PaymentMethod::findOrFail($id);
        $method->update(['is_active' => ! $method->is_active]);

        $this->dispatch('toast',
            message: 'Status '.$method->nama_metode.' diubah menjadi '.($method->is_active ? 'Aktif' : 'Non-Aktif').'.',
            type: 'success'
        );
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteMethod(): void
    {
        if (! $this->deleteId) {
            return;
        }

        $method = PaymentMethod::findOrFail($this->deleteId);

        if ($method->payments()->exists()) {
            $this->confirmingDelete = false;
            $this->deleteId = null;
            $this->dispatch('toast', message: 'Metode pembayaran tidak dapat dihapus karena tercatat dalam transaksi pembayaran.', type: 'error');

            return;
        }

        $method->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
        $this->dispatch('toast', message: 'Metode pembayaran berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $methods = PaymentMethod::query()
            ->withCount('payments')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nama_metode', 'like', '%'.$this->search.'%')
                        ->orWhere('kode_metode', 'like', '%'.$this->search.'%')
                        ->orWhere('nomor_rekening', 'like', '%'.$this->search.'%')
                        ->orWhere('atas_nama', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->tipeFilter, fn ($q) => $q->where('tipe', $this->tipeFilter))
            ->orderBy('tipe')
            ->orderBy('nama_metode')
            ->paginate(10);

        return view('livewire.payment-methods.payment-method-index', [
            'methods' => $methods,
            'totalMethods' => PaymentMethod::count(),
            'totalActive' => PaymentMethod::where('is_active', true)->count(),
        ]);
    }
}
