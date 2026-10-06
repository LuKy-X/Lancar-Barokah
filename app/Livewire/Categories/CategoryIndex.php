<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Master Kategori Produk & Jasa')]
class CategoryIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $typeFilter = '';

    public ?int $deleteId = null;

    public bool $confirmingDelete = false;

    // Modal state
    public bool $showModal = false;

    public ?int $categoryId = null;

    public string $kode_kategori = '';

    public string $nama_kategori = '';

    public string $tipe = 'produk';

    public string $deskripsi = '';

    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'kode_kategori' => ['required', 'string', 'max:50', 'unique:categories,kode_kategori,'.$this->categoryId],
            'nama_kategori' => ['required', 'string', 'max:255'],
            'tipe' => ['required', 'in:produk,jasa,bahan'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->reset(['categoryId', 'nama_kategori', 'deskripsi']);
        $this->tipe = 'produk';
        $this->is_active = true;

        // Auto generate next category code
        $count = Category::withTrashed()->count() + 1;
        $this->kode_kategori = 'KAT-'.str_pad((string) $count, 3, '0', STR_PAD_LEFT);

        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEditModal(Category $category): void
    {
        $this->categoryId = $category->id;
        $this->kode_kategori = $category->kode_kategori;
        $this->nama_kategori = $category->nama_kategori;
        $this->tipe = $category->tipe;
        $this->deskripsi = (string) $category->deskripsi;
        $this->is_active = $category->is_active;

        $this->resetValidation();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->saveCategory();
    }

    public function saveCategory(): void
    {
        $this->validate();

        if ($this->categoryId) {
            $category = Category::findOrFail($this->categoryId);
            $category->update([
                'kode_kategori' => $this->kode_kategori,
                'nama_kategori' => $this->nama_kategori,
                'tipe' => $this->tipe,
                'deskripsi' => $this->deskripsi ?: null,
                'is_active' => $this->is_active,
            ]);
            $msg = 'Kategori berhasil diperbarui.';
        } else {
            Category::create([
                'kode_kategori' => $this->kode_kategori,
                'nama_kategori' => $this->nama_kategori,
                'tipe' => $this->tipe,
                'deskripsi' => $this->deskripsi ?: null,
                'is_active' => $this->is_active,
            ]);
            $msg = 'Kategori baru berhasil ditambahkan.';
        }

        $this->showModal = false;
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function toggleActive(int $id): void
    {
        $category = Category::findOrFail($id);
        $category->update(['is_active' => ! $category->is_active]);

        $this->dispatch('toast',
            message: 'Status kategori '.$category->nama_kategori.' diubah menjadi '.($category->is_active ? 'Aktif' : 'Non-Aktif').'.',
            type: 'success'
        );
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteCategory(): void
    {
        if (! $this->deleteId) {
            return;
        }

        $category = Category::findOrFail($this->deleteId);

        // Prevent deletion if category is currently used by any products
        if ($category->products()->exists()) {
            $this->confirmingDelete = false;
            $this->deleteId = null;
            $this->dispatch('toast', message: 'Kategori tidak dapat dihapus karena masih digunakan oleh produk atau jasa terdaftar.', type: 'error');

            return;
        }

        $category->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
        $this->dispatch('toast', message: 'Kategori berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $categories = Category::query()
            ->withCount('products')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nama_kategori', 'like', '%'.$this->search.'%')
                        ->orWhere('kode_kategori', 'like', '%'.$this->search.'%')
                        ->orWhere('deskripsi', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->typeFilter, fn ($q) => $q->where('tipe', $this->typeFilter))
            ->orderBy('tipe')
            ->orderBy('nama_kategori')
            ->paginate(10);

        $counts = [
            'total' => Category::count(),
            'produk' => Category::where('tipe', 'produk')->count(),
            'jasa' => Category::where('tipe', 'jasa')->count(),
            'bahan' => Category::where('tipe', 'bahan')->count(),
        ];

        return view('livewire.categories.category-index', [
            'categories' => $categories,
            'counts' => $counts,
        ]);
    }
}
