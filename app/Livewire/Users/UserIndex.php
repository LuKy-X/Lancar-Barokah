<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Manajemen Pengguna & Petugas')]
class UserIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $roleFilter = '';

    public ?int $deleteId = null;

    public bool $confirmingDelete = false;

    // Modal state
    public bool $showModal = false;

    public ?int $userId = null;

    public string $name = '';

    public string $username = '';

    public string $email = '';

    public string $password = '';

    public string $role = 'petugas';

    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users', 'username')->ignore($this->userId)],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->userId)],
            'password' => [$this->userId ? 'nullable' : 'required', 'string', 'min:6'],
            'role' => ['required', 'in:admin,petugas'],
            'is_active' => ['boolean'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->reset(['userId', 'name', 'username', 'email', 'password']);
        $this->role = 'petugas';
        $this->is_active = true;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEditModal(User $user): void
    {
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email ?? '';
        $this->password = '';
        $this->role = $user->role;
        $this->is_active = $user->is_active;

        $this->resetValidation();
        $this->showModal = true;
    }

    public function saveUser(): void
    {
        $this->validate();

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $data = [
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email ?: null,
                'role' => $this->role,
                'is_active' => $this->is_active,
            ];
            if (! empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }
            $user->update($data);
            $msg = "Data pengguna {$user->name} berhasil diperbarui.";
        } else {
            User::create([
                'name' => $this->name,
                'username' => $this->username,
                'email' => $this->email ?: null,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'is_active' => $this->is_active,
            ]);
            $msg = 'Pengguna baru berhasil ditambahkan.';
        }

        $this->showModal = false;
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function toggleActive(User $user): void
    {
        if ($user->id === auth()->id()) {
            $this->dispatch('toast', message: 'Anda tidak dapat menonaktifkan akun Anda sendiri.', type: 'error');

            return;
        }

        $user->update(['is_active' => ! $user->is_active]);
        $statusStr = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        $this->dispatch('toast', message: "Akun {$user->name} berhasil {$statusStr}.", type: 'success');
    }

    public function confirmDelete(int $id): void
    {
        if ($id === auth()->id()) {
            $this->dispatch('toast', message: 'Anda tidak dapat menghapus akun Anda sendiri.', type: 'error');

            return;
        }
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteUser(): void
    {
        if (! $this->deleteId || $this->deleteId === auth()->id()) {
            return;
        }

        $user = User::findOrFail($this->deleteId);
        $user->delete();

        $this->confirmingDelete = false;
        $this->deleteId = null;
        $this->dispatch('toast', message: 'Pengguna berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('username', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->roleFilter, fn ($q) => $q->where('role', $this->roleFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.users.user-index', [
            'users' => $users,
        ]);
    }
}
