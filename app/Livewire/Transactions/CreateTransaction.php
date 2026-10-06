<?php

namespace App\Livewire\Transactions;

use App\Models\Category;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Services\TransactionService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Buat Order Transaksi Baru')]
class CreateTransaction extends Component
{
    use WithFileUploads;

    // Step 1: Customer Selection
    public ?int $selectedCustomerId = null;

    public string $customerSearch = '';

    public bool $showNewCustomerModal = false;

    public string $newCustNama = '';

    public string $newCustNoHp = '';

    public string $newCustAlamat = '';

    // Step 2: Product Catalog & Modal State
    public string $productSearch = '';

    public string $productCategoryFilter = '';

    public bool $showProductModal = false;

    public ?Product $selectedProduct = null;

    public ?int $editingCartIndex = null;

    public string $modalNamaSnapshot = '';

    public float $modalHarga = 0;

    public string $modalCatatan = '';

    public bool $isSized = true;

    /**
     * Size breakdown for apparel products: XS, S, M, L, XL, XXL, 3XL
     *
     * @var array<string, int>
     */
    public array $modalSizes = [
        'XS' => 0,
        'S' => 0,
        'M' => 0,
        'L' => 0,
        'XL' => 0,
        'XXL' => 0,
        '3XL' => 0,
    ];

    /**
     * Single quantity for non-sized products (services, materials)
     */
    public int $modalSingleJumlah = 1;

    // Step 3: Temporary Cart
    public array $cart = [];

    // Step 4: Transaction Information
    public string $tanggalTransaksi = '';

    public string $deadline = '';

    public string $alamatPengiriman = '';

    public float $biayaPengiriman = 0;

    public float $diskon = 0;

    public string $catatan = '';

    // Step 4.1: Production Stages Pipeline
    public string $presetTahapan = 'lengkap';

    /**
     * Selected production stages for this transaction.
     *
     * @var array<string>
     */
    public array $tahapanProduksi = ['cutting', 'jahit', 'printing', 'packing', 'selesai'];

    // Step 5: Initial Payment
    public float $initialPayment = 0;

    public string $metodeBayar = 'cash';

    public $initialPaymentProof = null;

    public function mount(): void
    {
        $this->tanggalTransaksi = Carbon::today()->toDateString();
        $this->deadline = Carbon::today()->addDays(7)->toDateString();
        $this->tahapanProduksi = ['cutting', 'jahit', 'printing', 'packing', 'selesai'];

        if (request()->has('customer_id')) {
            $customer = Customer::find(request()->get('customer_id'));
            if ($customer) {
                $this->selectCustomer($customer->id);
            }
        }
    }

    public function selectCustomer(int $id): void
    {
        $this->selectedCustomerId = $id;
        $customer = Customer::find($id);
        if ($customer && empty($this->alamatPengiriman)) {
            $this->alamatPengiriman = $customer->alamat ?? '';
        }
    }

    public function createCustomerQuick(): void
    {
        $this->validate([
            'newCustNama' => ['required', 'string', 'max:255'],
            'newCustNoHp' => ['nullable', 'string', 'max:20'],
            'newCustAlamat' => ['nullable', 'string'],
        ]);

        $customer = Customer::create([
            'no_customer' => Customer::generateCustomerNumber(),
            'nama' => $this->newCustNama,
            'no_hp' => $this->newCustNoHp ?: null,
            'alamat' => $this->newCustAlamat ?: null,
        ]);

        $this->selectCustomer($customer->id);
        $this->showNewCustomerModal = false;
        $this->reset(['newCustNama', 'newCustNoHp', 'newCustAlamat']);
        $this->dispatch('toast', message: 'Pelanggan baru berhasil didaftarkan.', type: 'success');
    }

    public function openProductModal(int $productId): void
    {
        $this->editingCartIndex = null;
        $this->selectedProduct = Product::findOrFail($productId);
        $this->modalNamaSnapshot = $this->selectedProduct->nama_produk;
        $this->modalHarga = (float) $this->selectedProduct->harga_default;
        $this->modalCatatan = '';

        // Determine if sized (apparel) or non-sized (services, materials)
        $this->isSized = $this->selectedProduct->isSized();

        if ($this->isSized) {
            $this->modalSizes = [
                'XS' => 0,
                'S' => 0,
                'M' => 12,
                'L' => 12,
                'XL' => 0,
                'XXL' => 0,
                '3XL' => 0,
            ];
            $this->modalSingleJumlah = 0;
        } else {
            $this->modalSizes = [
                'XS' => 0,
                'S' => 0,
                'M' => 0,
                'L' => 0,
                'XL' => 0,
                'XXL' => 0,
                '3XL' => 0,
            ];
            $this->modalSingleJumlah = 24;
        }

        $this->showProductModal = true;
    }

    public function incrementSize(string $size): void
    {
        if (isset($this->modalSizes[$size])) {
            $this->modalSizes[$size]++;
        }
    }

    public function decrementSize(string $size): void
    {
        if (isset($this->modalSizes[$size]) && $this->modalSizes[$size] > 0) {
            $this->modalSizes[$size]--;
        }
    }

    public function editCartItem(int $index): void
    {
        if (! isset($this->cart[$index])) {
            return;
        }

        $item = $this->cart[$index];
        $this->editingCartIndex = $index;
        $this->selectedProduct = Product::find($item['product_id']);
        $this->modalNamaSnapshot = $item['nama_produk'];
        $this->modalHarga = (float) $item['harga'];
        $this->modalCatatan = $item['catatan'] ?? '';
        $this->isSized = (bool) ($item['is_sized'] ?? false);

        if ($this->isSized) {
            $this->modalSizes = array_merge([
                'XS' => 0, 'S' => 0, 'M' => 0, 'L' => 0, 'XL' => 0, 'XXL' => 0, '3XL' => 0,
            ], $item['sizes'] ?? []);
            $this->modalSingleJumlah = 0;
        } else {
            $this->modalSingleJumlah = (int) ($item['jumlah'] ?? 1);
            $this->modalSizes = [
                'XS' => 0, 'S' => 0, 'M' => 0, 'L' => 0, 'XL' => 0, 'XXL' => 0, '3XL' => 0,
            ];
        }

        $this->showProductModal = true;
    }

    public function getModalTotalQtyProperty(): int
    {
        if ($this->isSized) {
            return (int) array_sum($this->modalSizes);
        }

        return max(1, (int) $this->modalSingleJumlah);
    }

    public function getModalSubtotalProperty(): float
    {
        return $this->getModalTotalQtyProperty() * max(0, $this->modalHarga);
    }

    public function addOrUpdateCart(): void
    {
        $this->validate([
            'modalNamaSnapshot' => ['required', 'string', 'max:255'],
            'modalHarga' => ['required', 'numeric', 'min:0'],
        ]);

        $totalQty = $this->getModalTotalQtyProperty();

        if ($totalQty <= 0) {
            $this->dispatch('toast', message: 'Kuantitas total pesanan harus lebih dari 0.', type: 'error');

            return;
        }

        // Build human readable size summary string (e.g. "S 5 | M 10 | L 15")
        $summarySizes = '';
        if ($this->isSized) {
            $parts = [];
            foreach ($this->modalSizes as $s => $q) {
                if ($q > 0) {
                    $parts[] = "{$s}: {$q}";
                }
            }
            $summarySizes = implode(' • ', $parts);
        }

        $item = [
            'product_id' => $this->selectedProduct->id,
            'kode_produk' => $this->selectedProduct->kode_produk,
            'nama_produk' => $this->modalNamaSnapshot,
            'kategori' => $this->selectedProduct->kategori,
            'satuan' => $this->selectedProduct->satuan,
            'is_sized' => $this->isSized,
            'sizes' => $this->isSized ? $this->modalSizes : [],
            'jumlah' => $totalQty,
            'harga' => $this->modalHarga,
            'subtotal' => $totalQty * $this->modalHarga,
            'summary_sizes' => $summarySizes,
            'catatan' => $this->modalCatatan ?: null,
        ];

        if ($this->editingCartIndex !== null && isset($this->cart[$this->editingCartIndex])) {
            $this->cart[$this->editingCartIndex] = $item;
        } else {
            $this->cart[] = $item;
        }

        $this->showProductModal = false;
        $this->dispatch('toast', message: 'Item berhasil disimpan ke keranjang.', type: 'success');
    }

    public function removeFromCart(int $index): void
    {
        if (isset($this->cart[$index])) {
            unset($this->cart[$index]);
            $this->cart = array_values($this->cart);
            $this->dispatch('toast', message: 'Item dihapus dari keranjang.', type: 'success');
        }
    }

    public function setDeadlinePreset(int $days): void
    {
        $this->deadline = Carbon::parse($this->tanggalTransaksi)->addDays($days)->toDateString();
    }

    public function setPaymentPercentage(int $percent): void
    {
        $total = $this->getTotalTagihanProperty();
        $this->initialPayment = round(($total * $percent) / 100);
    }

    public function getSubtotalProperty(): float
    {
        return array_reduce($this->cart, fn ($sum, $item) => $sum + ((float) ($item['subtotal'] ?? 0)), 0.0);
    }

    public function getTotalTagihanProperty(): float
    {
        return max(0, $this->getSubtotalProperty() + (float) $this->biayaPengiriman - (float) $this->diskon);
    }

    public function applyPresetTahapan(string $preset): void
    {
        $this->presetTahapan = $preset;

        if ($preset === 'lengkap') {
            $this->tahapanProduksi = ['cutting', 'jahit', 'printing', 'packing', 'selesai'];
        } elseif ($preset === 'sablon_saja') {
            $this->tahapanProduksi = ['printing', 'packing', 'selesai'];
        } elseif ($preset === 'jahit_sablon') {
            $this->tahapanProduksi = ['jahit', 'printing', 'packing', 'selesai'];
        }
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
            $this->tahapanProduksi = array_values(array_diff($this->tahapanProduksi, [$stage]));
        } else {
            $this->tahapanProduksi[] = $stage;
            $this->tahapanProduksi = array_values(array_intersect($allOrdered, $this->tahapanProduksi));
        }

        $this->presetTahapan = 'kustom';
    }

    public function submitOrder(TransactionService $service): void
    {
        if (! $this->selectedCustomerId) {
            $this->dispatch('toast', message: 'Silakan pilih atau tambahkan pelanggan terlebih dahulu.', type: 'error');

            return;
        }

        if (empty($this->cart)) {
            $this->dispatch('toast', message: 'Keranjang pesanan masih kosong. Tambahkan minimal 1 item produk atau jasa.', type: 'error');

            return;
        }

        if (empty($this->tahapanProduksi)) {
            $this->tahapanProduksi = ['cutting', 'jahit', 'printing', 'packing', 'selesai'];
        }

        $this->validate([
            'deadline' => ['required', 'date', 'after_or_equal:tanggalTransaksi'],
            'biayaPengiriman' => ['numeric', 'min:0'],
            'diskon' => ['numeric', 'min:0'],
            'initialPayment' => ['numeric', 'min:0', 'max:'.$this->getTotalTagihanProperty()],
            'initialPaymentProof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:3072'],
        ]);

        $data = [
            'customer_id' => $this->selectedCustomerId,
            'tanggal_transaksi' => $this->tanggalTransaksi,
            'deadline' => $this->deadline,
            'alamat_pengiriman' => $this->alamatPengiriman,
            'biaya_pengiriman' => (float) $this->biayaPengiriman,
            'diskon' => (float) $this->diskon,
            'catatan' => $this->catatan,
            'items' => $this->cart,
            'tahapan_produksi' => $this->tahapanProduksi,
            'initial_payment' => (float) $this->initialPayment,
            'metode_bayar' => $this->metodeBayar,
            'bukti_bayar' => $this->initialPaymentProof,
        ];

        try {
            $transaction = $service->createTransaction($data, auth()->user());
            session()->flash('success', "Order transaksi {$transaction->invoice_no} berhasil dibuat!");
            $this->redirect(route('transactions.detail', $transaction), navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Gagal membuat transaksi: '.$e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $selectedCustomer = $this->selectedCustomerId ? Customer::find($this->selectedCustomerId) : null;

        $customers = Customer::query()
            ->when($this->customerSearch, function ($q) {
                $q->where('nama', 'like', '%'.$this->customerSearch.'%')
                    ->orWhere('no_customer', 'like', '%'.$this->customerSearch.'%')
                    ->orWhere('no_hp', 'like', '%'.$this->customerSearch.'%');
            })
            ->latest()
            ->take(8)
            ->get();

        $products = Product::active()
            ->with(['category', 'unit'])
            ->when($this->productSearch, function ($q) {
                $q->where('nama_produk', 'like', '%'.$this->productSearch.'%')
                    ->orWhere('kode_produk', 'like', '%'.$this->productSearch.'%');
            })
            ->when($this->productCategoryFilter, fn ($q) => $q->where('category_id', $this->productCategoryFilter))
            ->orderBy('category_id')
            ->orderBy('nama_produk')
            ->get();

        return view('livewire.transactions.create-transaction', [
            'selectedCustomer' => $selectedCustomer,
            'customers' => $customers,
            'products' => $products,
            'categories' => Category::active()->orderBy('tipe')->orderBy('nama_kategori')->get(),
            'paymentMethods' => PaymentMethod::active()->get(),
        ]);
    }
}
