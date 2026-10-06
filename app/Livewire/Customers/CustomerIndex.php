<?php

namespace App\Livewire\Customers;

use App\Models\Customer;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Master Pelanggan')]
class CustomerIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $deleteId = null;

    public bool $confirmingDelete = false;

    // Inline modal creation/editing support
    public bool $showModal = false;

    public ?int $customerId = null;

    public string $nama = '';

    public string $no_hp = '';

    public string $email = '';

    public string $alamat = '';

    protected function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'alamat' => ['nullable', 'string'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->reset(['customerId', 'nama', 'no_hp', 'email', 'alamat']);
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEditModal(Customer $customer): void
    {
        $this->customerId = $customer->id;
        $this->nama = $customer->nama;
        $this->no_hp = $customer->no_hp ?? '';
        $this->email = $customer->email ?? '';
        $this->alamat = $customer->alamat ?? '';
        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->saveCustomer();
    }

    public function saveCustomer(): void
    {
        $this->validate();

        if ($this->customerId) {
            $customer = Customer::findOrFail($this->customerId);
            $customer->update([
                'nama' => $this->nama,
                'no_hp' => $this->no_hp ?: null,
                'email' => $this->email ?: null,
                'alamat' => $this->alamat ?: null,
            ]);
            $msg = 'Data pelanggan berhasil diperbarui.';
        } else {
            Customer::create([
                'no_customer' => Customer::generateCustomerNumber(),
                'nama' => $this->nama,
                'no_hp' => $this->no_hp ?: null,
                'email' => $this->email ?: null,
                'alamat' => $this->alamat ?: null,
            ]);
            $msg = 'Pelanggan baru berhasil ditambahkan.';
        }

        $this->showModal = false;
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function confirmDelete(int $id): void
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Hanya Administrator yang dapat menghapus data pelanggan.');

        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteCustomer(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Hanya Administrator yang dapat menghapus data pelanggan.');

        if (! $this->deleteId) {
            return;
        }

        $customer = Customer::withCount('transactions')->findOrFail($this->deleteId);

        if ($customer->transactions_count > 0) {
            $this->confirmingDelete = false;
            $this->dispatch('toast', message: 'Pelanggan tidak dapat dihapus karena memiliki riwayat transaksi.', type: 'error');

            return;
        }

        $customer->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
        $this->dispatch('toast', message: 'Pelanggan berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $customers = Customer::query()
            ->withCount('transactions')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nama', 'like', '%'.$this->search.'%')
                        ->orWhere('no_customer', 'like', '%'.$this->search.'%')
                        ->orWhere('no_hp', 'like', '%'.$this->search.'%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.customers.customer-index', [
            'customers' => $customers,
        ]);
    }
}
