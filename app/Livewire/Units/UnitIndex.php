<?php

namespace App\Livewire\Units;

use App\Models\Unit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Master Satuan Ukuran')]
class UnitIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $deleteId = null;

    public bool $confirmingDelete = false;

    public bool $showModal = false;

    public ?int $unitId = null;

    public string $kode_satuan = '';

    public string $nama_satuan = '';

    public string $keterangan = '';

    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'kode_satuan' => ['required', 'string', 'max:20', 'unique:units,kode_satuan,'.$this->unitId],
            'nama_satuan' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'is_active' => ['boolean'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->reset(['unitId', 'kode_satuan', 'nama_satuan', 'keterangan']);
        $this->is_active = true;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEditModal(Unit $unit): void
    {
        $this->unitId = $unit->id;
        $this->kode_satuan = $unit->kode_satuan;
        $this->nama_satuan = $unit->nama_satuan;
        $this->keterangan = (string) $unit->keterangan;
        $this->is_active = $unit->is_active;

        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->saveUnit();
    }

    public function saveUnit(): void
    {
        $this->validate();

        if ($this->unitId) {
            $unit = Unit::findOrFail($this->unitId);
            $unit->update([
                'kode_satuan' => strtoupper(trim($this->kode_satuan)),
                'nama_satuan' => $this->nama_satuan,
                'keterangan' => $this->keterangan ?: null,
                'is_active' => $this->is_active,
            ]);
            $msg = 'Satuan berhasil diperbarui.';
        } else {
            Unit::create([
                'kode_satuan' => strtoupper(trim($this->kode_satuan)),
                'nama_satuan' => $this->nama_satuan,
                'keterangan' => $this->keterangan ?: null,
                'is_active' => $this->is_active,
            ]);
            $msg = 'Satuan baru berhasil ditambahkan.';
        }

        $this->showModal = false;
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function toggleActive(int $id): void
    {
        $unit = Unit::findOrFail($id);
        $unit->update(['is_active' => ! $unit->is_active]);

        $this->dispatch('toast',
            message: 'Status satuan '.$unit->kode_satuan.' diubah menjadi '.($unit->is_active ? 'Aktif' : 'Non-Aktif').'.',
            type: 'success'
        );
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteUnit(): void
    {
        if (! $this->deleteId) {
            return;
        }

        $unit = Unit::findOrFail($this->deleteId);

        if ($unit->products()->exists()) {
            $this->confirmingDelete = false;
            $this->deleteId = null;
            $this->dispatch('toast', message: 'Satuan tidak dapat dihapus karena masih digunakan oleh produk terdaftar.', type: 'error');

            return;
        }

        $unit->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
        $this->dispatch('toast', message: 'Satuan berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $units = Unit::query()
            ->withCount('products')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nama_satuan', 'like', '%'.$this->search.'%')
                        ->orWhere('kode_satuan', 'like', '%'.$this->search.'%')
                        ->orWhere('keterangan', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('kode_satuan')
            ->paginate(10);

        return view('livewire.units.unit-index', [
            'units' => $units,
            'totalUnits' => Unit::count(),
            'activeUnits' => Unit::where('is_active', true)->count(),
        ]);
    }
}
