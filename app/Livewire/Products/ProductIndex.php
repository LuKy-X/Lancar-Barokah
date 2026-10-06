<?php

namespace App\Livewire\Products;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Master Produk & Jasa')]
class ProductIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $categoryFilter = '';

    public ?int $deleteId = null;

    public bool $confirmingDelete = false;

    // Modal state
    public bool $showModal = false;

    public ?int $productId = null;

    public ?int $category_id = null;

    public ?int $unit_id = null;

    public string $kode_produk = '';

    public string $nama_produk = '';

    public string $satuan = 'pcs';

    public float $harga_default = 0;

    public string $deskripsi = '';

    public bool $is_active = true;

    protected function rules(): array
    {
        return [
            'kode_produk' => ['required', 'string', 'max:50', 'unique:products,kode_produk,'.$this->productId],
            'nama_produk' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'unit_id' => ['nullable', 'exists:units,id'],
            'satuan' => ['required', 'string', 'max:30'],
            'harga_default' => ['required', 'numeric', 'min:0'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['boolean'],
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Akses ditolak. Petugas hanya memiliki hak akses melihat katalog.');

        $this->reset(['productId', 'nama_produk', 'harga_default', 'deskripsi']);
        $this->category_id = Category::active()->first()?->id;
        $this->unit_id = Unit::active()->first()?->id;
        $this->satuan = Unit::find($this->unit_id)?->kode_satuan ?? 'pcs';
        $this->is_active = true;

        // Auto generate next product code
        $count = Product::withTrashed()->count() + 1;
        $this->kode_produk = 'PRD-'.str_pad((string) $count, 3, '0', STR_PAD_LEFT);

        $this->resetValidation();
        $this->showModal = true;
    }

    public function openEditModal(Product $product): void
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Akses ditolak. Petugas hanya memiliki hak akses melihat katalog.');

        $this->productId = $product->id;
        $this->category_id = $product->category_id;
        $this->unit_id = $product->unit_id;
        $this->kode_produk = $product->kode_produk;
        $this->nama_produk = $product->nama_produk;
        $this->satuan = $product->satuan;
        $this->harga_default = (float) $product->harga_default;
        $this->deskripsi = (string) $product->deskripsi;
        $this->is_active = $product->is_active;

        $this->resetValidation();
        $this->showModal = true;
    }

    public function updatedUnitId($value): void
    {
        if ($value) {
            $unit = Unit::find($value);
            if ($unit) {
                $this->satuan = strtolower($unit->kode_satuan);
            }
        }
    }

    public function save(): void
    {
        $this->saveProduct();
    }

    public function saveProduct(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Akses ditolak. Petugas hanya memiliki hak akses melihat katalog.');

        $this->validate();

        $data = [
            'kode_produk' => $this->kode_produk,
            'nama_produk' => $this->nama_produk,
            'category_id' => $this->category_id,
            'unit_id' => $this->unit_id,
            'satuan' => $this->satuan,
            'harga_default' => $this->harga_default,
            'deskripsi' => $this->deskripsi ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->productId) {
            $product = Product::findOrFail($this->productId);
            $product->update($data);
            $msg = 'Produk/jasa berhasil diperbarui.';
        } else {
            Product::create($data);
            $msg = 'Produk/jasa baru berhasil ditambahkan.';
        }

        $this->showModal = false;
        $this->dispatch('toast', message: $msg, type: 'success');
    }

    public function toggleActive(Product $product): void
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Akses ditolak. Petugas hanya memiliki hak akses melihat katalog.');

        $product->update(['is_active' => ! $product->is_active]);
        $statusStr = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        $this->dispatch('toast', message: "Status produk {$product->nama_produk} berhasil {$statusStr}.", type: 'success');
    }

    public function confirmDelete(int $id): void
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Akses ditolak. Petugas hanya memiliki hak akses melihat katalog.');

        $this->deleteId = $id;
        $this->confirmingDelete = true;
    }

    public function deleteProduct(): void
    {
        abort_unless(auth()->user()->isAdmin(), 403, 'Akses ditolak. Petugas hanya memiliki hak akses melihat katalog.');

        if (! $this->deleteId) {
            return;
        }

        $product = Product::withCount('transactionDetails')->findOrFail($this->deleteId);

        if ($product->transaction_details_count > 0) {
            $this->confirmingDelete = false;
            $this->dispatch('toast', message: 'Produk tidak dapat dihapus karena tercatat dalam transaksi.', type: 'error');

            return;
        }

        $product->delete();
        $this->confirmingDelete = false;
        $this->deleteId = null;
        $this->dispatch('toast', message: 'Produk berhasil dihapus.', type: 'success');
    }

    public function render()
    {
        $products = Product::query()
            ->with(['category', 'unit'])
            ->withCount('transactionDetails')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('nama_produk', 'like', '%'.$this->search.'%')
                        ->orWhere('kode_produk', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->categoryFilter, fn ($q) => $q->where('category_id', $this->categoryFilter))
            ->orderBy('category_id')
            ->orderBy('nama_produk')
            ->paginate(12);

        return view('livewire.products.product-index', [
            'products' => $products,
            'categories' => Category::active()->orderBy('tipe')->orderBy('nama_kategori')->get(),
            'units' => Unit::active()->orderBy('nama_satuan')->get(),
        ]);
    }
}
