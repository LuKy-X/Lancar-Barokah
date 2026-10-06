<div class="space-y-8 pb-16">
    <!-- Top Header -->
    <div class="sm:flex sm:items-center sm:justify-between pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-400 mb-1">
                <a href="{{ route('transactions.index') }}" class="hover:text-slate-700 transition-colors">Daftar Transaksi</a>
                <span>/</span>
                <span class="text-[#0e3f32] font-bold">Input Transaksi Baru</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Order Builder & Pembuatan Transaksi</h1>
            <p class="text-xs text-slate-500 mt-0.5">Pilih pelanggan, tentukan produk & ukuran pesanan, atur pembayaran awal, dan terbitkan nota.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center gap-2 text-xs font-semibold text-slate-500">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            Tahap Produksi Awal: <strong class="text-[#0e3f32] uppercase">CUTTING</strong>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- LEFT COLUMN: Customer, Catalog, & Order Metadata (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">

            <!-- SECTION 1: Customer Selection -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                        <span class="w-6 h-6 rounded-xl bg-[#0e3f32] text-white flex items-center justify-center text-xs font-black">1</span>
                        <span>Data Pelanggan</span>
                    </h3>
                    <button type="button" 
                            wire:click="$set('showNewCustomerModal', true)"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-[#0e3f32] hover:text-[#124b3c] bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-xl transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        <span>+ Pelanggan Baru</span>
                    </button>
                </div>

                @if($selectedCustomer)
                    <!-- Selected Customer Card -->
                    <div class="p-4 rounded-2xl bg-[#f0fdf4] border border-emerald-200 flex items-center justify-between">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-2xl bg-[#0e3f32] text-white font-black flex items-center justify-center text-sm shadow-sm">
                                {{ strtoupper(substr($selectedCustomer->nama, 0, 2)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-900 text-sm">{{ $selectedCustomer->nama }}</h4>
                                <p class="text-xs text-slate-500 flex items-center gap-2 mt-0.5">
                                    <span class="font-mono text-emerald-800 font-semibold">{{ $selectedCustomer->no_customer }}</span>
                                    <span>&bull;</span>
                                    <span>{{ $selectedCustomer->no_hp ?? 'Tidak ada kontak' }}</span>
                                </p>
                            </div>
                        </div>
                        <button type="button" wire:click="$set('selectedCustomerId', null)" class="text-xs font-bold text-slate-500 hover:text-rose-600 px-3 py-1.5 rounded-xl bg-white border border-slate-200 hover:border-rose-300 transition-colors shadow-2xs">
                            Ganti
                        </button>
                    </div>
                @else
                    <!-- Search & Pick Customer Autocomplete -->
                    <div class="space-y-3">
                        <div class="relative">
                            <input wire:model.live.debounce.250ms="customerSearch" 
                                   type="text" 
                                   placeholder="Ketik nama pelanggan, no. WhatsApp/HP, atau kode..." 
                                   class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none transition-all">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-48 overflow-y-auto">
                            @forelse($customers as $c)
                                <button type="button" 
                                        wire:click="selectCustomer({{ $c->id }})"
                                        class="text-left p-3 rounded-2xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/40 transition-all flex items-center justify-between group">
                                    <div class="min-w-0 pr-2">
                                        <p class="text-xs font-bold text-slate-800 truncate group-hover:text-[#0e3f32]">{{ $c->nama }}</p>
                                        <p class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $c->no_customer }} &bull; {{ $c->no_hp ?? '-' }}</p>
                                    </div>
                                    <span class="text-[#0e3f32] opacity-0 group-hover:opacity-100 transition-opacity text-xs font-bold shrink-0">Pilih &rarr;</span>
                                </button>
                            @empty
                                <div class="col-span-2 text-center py-5 text-xs text-slate-400">
                                    Pelanggan tidak ditemukan. Klik tombol "+ Pelanggan Baru" di atas untuk menambahkan.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>

            <!-- SECTION 2: Catalog Products & Services Picker -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-3.5">
                <!-- Section Header -->
                <div class="flex items-center justify-between gap-3 pb-2.5 border-b border-slate-100">
                    <div class="flex items-center gap-2.5">
                        <span class="w-6 h-6 rounded-xl bg-[#0e3f32] text-white flex items-center justify-center text-xs font-black shrink-0 shadow-xs">2</span>
                        <div>
                            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Pilih Produk & Jasa Sablon</h3>
                            <p class="text-[10px] text-slate-400 font-medium hidden sm:block">Klik item untuk mengatur kuantitas, breakdown ukuran baju, atau harga kustom</p>
                        </div>
                    </div>
                    <div class="shrink-0">
                        <span class="text-[11px] font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200/80">
                            <span class="text-slate-900 font-black">{{ $products->count() }}</span> Item Tersedia
                        </span>
                    </div>
                </div>

                <!-- Search & Category Quick Select Bar -->
                <div class="flex flex-col sm:flex-row items-center gap-2">
                    <!-- Search Input -->
                    <div class="relative flex-1 w-full">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input wire:model.live.debounce.200ms="productSearch" 
                               type="text" 
                               placeholder="Cari produk atau jasa (contoh: Kaos Combed, DTF, Plastisol)..." 
                               class="w-full pl-9 pr-8 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none transition-all">
                        @if($productSearch)
                            <button type="button" wire:click="$set('productSearch', '')" class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @endif
                    </div>

                    <!-- Category Dropdown -->
                    <div class="w-full sm:w-56 shrink-0">
                        <select wire:model.live="productCategoryFilter" 
                                class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none transition-all cursor-pointer">
                            <option value="">Semua Kategori ({{ $categories->count() }})</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Smooth Horizontal Category Chips Strip -->
                <div class="flex items-center gap-1.5 overflow-x-auto pb-1.5 pt-0.5 no-scrollbar">
                    <button type="button" 
                            wire:click="$set('productCategoryFilter', '')" 
                            class="shrink-0 px-3 py-1.5 rounded-xl text-xs font-extrabold transition-all cursor-pointer flex items-center gap-1.5 {{ empty($productCategoryFilter) ? 'bg-[#0e3f32] text-white shadow-sm shadow-[#0e3f32]/25' : 'bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 border border-slate-200/60' }}">
                        <span>Semua</span>
                    </button>
                    @foreach($categories as $cat)
                        <button type="button" 
                                wire:click="$set('productCategoryFilter', '{{ $cat->id }}')" 
                                class="shrink-0 px-3 py-1.5 rounded-xl text-xs font-bold transition-all cursor-pointer flex items-center gap-1.5 {{ $productCategoryFilter == $cat->id ? 'bg-[#0e3f32] text-white shadow-sm shadow-[#0e3f32]/25' : 'bg-slate-100 hover:bg-slate-200 text-slate-600 hover:text-slate-900 border border-slate-200/60' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $cat->tipe === 'produk' ? 'bg-emerald-400' : ($cat->tipe === 'jasa' ? 'bg-teal-400' : 'bg-amber-400') }}"></span>
                            <span>{{ $cat->nama_kategori }}</span>
                        </button>
                    @endforeach
                </div>

                <!-- Product Grid (Bankio Clean Cards) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-80 overflow-y-auto pr-1">
                    @forelse($products as $p)
                        <button type="button" 
                                wire:click="openProductModal({{ $p->id }})"
                                class="text-left p-3.5 rounded-2xl border border-slate-200/90 hover:border-[#0e3f32] hover:bg-slate-50/70 transition-all flex flex-col justify-between group shadow-2xs">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <span class="text-[10px] font-mono font-bold text-[#0d7a60] uppercase tracking-wider">{{ $p->kode_produk }}</span>
                                    <h4 class="font-bold text-slate-900 text-xs mt-0.5 group-hover:text-[#0e3f32]">{{ $p->nama_produk }}</h4>
                                </div>
                                <span class="px-2 py-0.5 text-[9px] font-extrabold uppercase rounded-lg {{ $p->category?->type_badge_classes ?? 'bg-slate-100 text-slate-600' }}">
                                    {{ $p->category?->nama_kategori ?? '-' }}
                                </span>
                            </div>
                            <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between">
                                <span class="text-xs font-black text-slate-900">
                                    Rp {{ number_format($p->harga_default, 0, ',', '.') }}
                                    <span class="text-[10px] font-normal text-slate-400">/ {{ $p->satuan }}</span>
                                </span>
                                <span class="w-6 h-6 rounded-lg bg-slate-100 group-hover:bg-[#0e3f32] group-hover:text-white text-slate-500 flex items-center justify-center text-xs font-bold transition-colors">
                                    +
                                </span>
                            </div>
                        </button>
                    @empty
                        <div class="col-span-2 text-center py-6 text-xs text-slate-400">
                            Produk atau jasa tidak ditemukan.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- SECTION 3: Order Dates & Delivery Information -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5 sm:p-6 space-y-4">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-6 h-6 rounded-xl bg-[#0e3f32] text-white flex items-center justify-center text-xs font-black">3</span>
                    <span>Jadwal & Pengiriman</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tanggal Transaksi</label>
                        <input wire:model="tanggalTransaksi" type="date" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Batas Deadline Produksi *</label>
                        <input wire:model="deadline" type="date" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                        <!-- Quick Deadline Presets -->
                        <div class="flex gap-1.5 mt-2">
                            <span class="text-[10px] text-slate-400 font-bold self-center">Preset:</span>
                            <button type="button" wire:click="setDeadlinePreset(3)" class="px-2 py-0.5 bg-slate-100 hover:bg-emerald-100 hover:text-emerald-800 rounded-lg text-[10px] font-bold text-slate-600 transition-colors">+3 Hari</button>
                            <button type="button" wire:click="setDeadlinePreset(7)" class="px-2 py-0.5 bg-slate-100 hover:bg-emerald-100 hover:text-emerald-800 rounded-lg text-[10px] font-bold text-slate-600 transition-colors">+7 Hari (Standar)</button>
                            <button type="button" wire:click="setDeadlinePreset(14)" class="px-2 py-0.5 bg-slate-100 hover:bg-emerald-100 hover:text-emerald-800 rounded-lg text-[10px] font-bold text-slate-600 transition-colors">+14 Hari</button>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Pengiriman (Opsional jika diambil di tempat)</label>
                    <textarea wire:model="alamatPengiriman" rows="2" placeholder="Alamat lengkap tujuan kirim ekspedisi / kurir..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan Produksi Khusus (Opsional)</label>
                    <input wire:model="catatan" type="text" placeholder="Contoh: Packing per lusin, sablon tinta warna emas..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                </div>
            </div>

            <!-- Step 4: Tahapan Alur Kerja Produksi (Workflow) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Tahapan Alur Produksi</h3>
                        <p class="text-[11px] text-slate-400">Tentukan tahapan pengerjaan yang diperlukan untuk pesanan ini</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-extrabold uppercase">
                        {{ count($tahapanProduksi) }} Tahapan
                    </span>
                </div>

                <!-- Quick Presets -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilihan Cepat (Preset)</label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <button type="button" 
                                wire:click="applyPresetTahapan('lengkap')"
                                class="p-2.5 rounded-xl border text-left transition-all {{ $presetTahapan === 'lengkap' ? 'bg-[#0e3f32] text-white border-[#0e3f32] shadow-sm' : 'bg-slate-50 hover:bg-slate-100 border-slate-200 text-slate-700' }}">
                            <span class="text-xs font-black block">Produksi Lengkap</span>
                            <span class="text-[10px] {{ $presetTahapan === 'lengkap' ? 'text-emerald-200' : 'text-slate-400' }} block">Dari bahan kain mentah</span>
                        </button>

                        <button type="button" 
                                wire:click="applyPresetTahapan('sablon_saja')"
                                class="p-2.5 rounded-xl border text-left transition-all {{ $presetTahapan === 'sablon_saja' ? 'bg-[#0e3f32] text-white border-[#0e3f32] shadow-sm' : 'bg-slate-50 hover:bg-slate-100 border-slate-200 text-slate-700' }}">
                            <span class="text-xs font-black block">Sablon Saja</span>
                            <span class="text-[10px] {{ $presetTahapan === 'sablon_saja' ? 'text-emerald-200' : 'text-slate-400' }} block">Bawa baju/kaos sendiri</span>
                        </button>

                        <button type="button" 
                                wire:click="applyPresetTahapan('jahit_sablon')"
                                class="p-2.5 rounded-xl border text-left transition-all {{ $presetTahapan === 'jahit_sablon' ? 'bg-[#0e3f32] text-white border-[#0e3f32] shadow-sm' : 'bg-slate-50 hover:bg-slate-100 border-slate-200 text-slate-700' }}">
                            <span class="text-xs font-black block">Jahit & Sablon</span>
                            <span class="text-[10px] {{ $presetTahapan === 'jahit_sablon' ? 'text-emerald-200' : 'text-slate-400' }} block">Tanpa potong bahan</span>
                        </button>
                    </div>
                </div>

                <!-- Stage Checkboxes / Toggles -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Sesuaikan Tahapan yang Dikerjakan</label>
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                        @php
                            $allStagesList = [
                                'cutting' => ['label' => 'Cutting', 'desc' => 'Potong Bahan'],
                                'jahit' => ['label' => 'Jahit', 'desc' => 'Penjahitan'],
                                'printing' => ['label' => 'Printing', 'desc' => 'Sablon/Cetak'],
                                'packing' => ['label' => 'Packing', 'desc' => 'Finishing'],
                                'selesai' => ['label' => 'Selesai', 'desc' => 'Siap Diambil'],
                            ];
                        @endphp
                        @foreach($allStagesList as $stageKey => $stageInfo)
                            @php
                                $isActive = in_array($stageKey, $tahapanProduksi, true);
                            @endphp
                            <button type="button" 
                                    wire:click="toggleTahap('{{ $stageKey }}')"
                                    @if($stageKey === 'selesai') disabled @endif
                                    class="p-2 rounded-xl border text-left transition-all flex flex-col justify-between relative {{ $isActive ? 'bg-emerald-50/70 border-emerald-300 text-emerald-950 ring-1 ring-emerald-300' : 'bg-slate-50 border-slate-200 text-slate-400 opacity-60' }} {{ $stageKey === 'selesai' ? 'cursor-default' : 'cursor-pointer hover:border-emerald-400' }}">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="w-4 h-4 rounded-md flex items-center justify-center text-[10px] font-bold {{ $isActive ? 'bg-[#0e3f32] text-white' : 'bg-slate-200 text-slate-400' }}">
                                        @if($isActive) &#10003; @endif
                                    </span>
                                    @if($stageKey === 'selesai')
                                        <span class="text-[9px] text-slate-400 font-semibold">(Wajib)</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-xs font-extrabold block">{{ $stageInfo['label'] }}</span>
                                    <span class="text-[9px] block text-slate-500">{{ $stageInfo['desc'] }}</span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2">
                        * Status awal antrean produksi otomatis mengikuti tahap pertama yang dipilih (misal: jika pilih <strong>Sablon Saja</strong>, status awal langsung masuk ke kolom <strong>Printing</strong>).
                    </p>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Order Summary & Temporary Cart (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">
            
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sticky top-6 space-y-6">
                
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Rincian Keranjang Pesanan</h3>
                        <p class="text-[11px] text-slate-400">Cart sementara (belum tersimpan ke database)</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-[#0e3f32] text-xs font-black">
                        {{ count($cart) }} Item
                    </span>
                </div>

                <!-- Cart Items List -->
                <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
                    @forelse($cart as $index => $item)
                        <div class="p-3.5 rounded-2xl bg-slate-50/90 border border-slate-200/90 space-y-2">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h4 class="font-extrabold text-xs text-slate-900">{{ $item['nama_produk'] }}</h4>
                                    @if(!empty($item['summary_sizes']))
                                        <div class="flex flex-wrap gap-1 mt-1">
                                            <span class="px-2 py-0.5 rounded-md bg-white border border-slate-200 text-[10px] font-bold text-emerald-800 font-mono">
                                                {{ $item['summary_sizes'] }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    <button type="button" wire:click="editCartItem({{ $index }})" class="text-[10px] font-bold text-[#0e3f32] hover:underline px-1.5 py-0.5 bg-emerald-50 rounded">
                                        Edit
                                    </button>
                                    <button type="button" wire:click="removeFromCart({{ $index }})" class="text-[10px] font-bold text-rose-500 hover:text-rose-700 px-1.5 py-0.5 bg-rose-50 rounded">
                                        Hapus
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center justify-between text-xs pt-1 border-t border-slate-200/60">
                                <span class="text-slate-500 font-medium">
                                    {{ $item['jumlah'] }} {{ $item['satuan'] ?? 'pcs' }} &times; Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                </span>
                                <span class="font-black text-slate-900">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center rounded-2xl border-2 border-dashed border-slate-200 text-slate-400 space-y-2">
                            <svg class="w-8 h-8 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <p class="text-xs font-bold text-slate-600">Belum ada produk dalam transaksi</p>
                            <p class="text-[11px] text-slate-400">Pilih pakaian atau jasa dari katalog di sebelah kiri.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Financial Calculation (Bankio Clean Styling) -->
                <div class="space-y-2.5 pt-3 border-t border-slate-100 text-xs">
                    <div class="flex items-center justify-between text-slate-600">
                        <span>Subtotal Item:</span>
                        <span class="font-bold text-slate-800 font-mono">Rp {{ number_format($this->subtotal, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <span class="text-slate-600">Biaya Pengiriman:</span>
                        <div class="relative w-36">
                            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-[10px] text-slate-400">Rp</span>
                            <input wire:model.live="biayaPengiriman" type="number" min="0" class="w-full pl-7 pr-2 py-1 text-right bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold focus:ring-1 focus:ring-[#0e3f32]">
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-3">
                        <span class="text-slate-600">Potongan / Diskon:</span>
                        <div class="relative w-36">
                            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-[10px] text-slate-400">Rp</span>
                            <input wire:model.live="diskon" type="number" min="0" class="w-full pl-7 pr-2 py-1 text-right bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-rose-600 focus:ring-1 focus:ring-[#0e3f32]">
                        </div>
                    </div>

                    <!-- Total Tagihan -->
                    <div class="p-3.5 rounded-2xl bg-[#0e3f32] text-white flex items-center justify-between shadow-md shadow-[#0e3f32]/20">
                        <div>
                            <span class="text-[10px] font-bold text-emerald-200 uppercase tracking-wider block">Total Tagihan Final</span>
                            <span class="text-lg font-black font-mono">Rp {{ number_format($this->totalTagihan, 0, ',', '.') }}</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full bg-white/10 text-emerald-300 text-[10px] font-bold">
                            Server Recalc
                        </span>
                    </div>
                </div>

                <!-- Step 5: Initial Payment Section -->
                <div class="space-y-3 pt-3 border-t border-slate-100">
                    <label class="block text-xs font-black text-slate-800 uppercase tracking-wider">
                        Pembayaran Awal (DP / Lunas)
                    </label>

                    <!-- Quick Percentage Presets -->
                    <div class="grid grid-cols-4 gap-1.5">
                        <button type="button" wire:click="setPaymentPercentage(0)" class="py-1.5 text-[10px] font-bold rounded-xl border {{ $initialPayment == 0 ? 'bg-[#0e3f32] text-white border-[#0e3f32]' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                            0% (Nanti)
                        </button>
                        <button type="button" wire:click="setPaymentPercentage(30)" class="py-1.5 text-[10px] font-bold rounded-xl border {{ round(($this->totalTagihan * 30)/100) == $initialPayment && $initialPayment > 0 ? 'bg-[#0e3f32] text-white border-[#0e3f32]' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                            30% DP
                        </button>
                        <button type="button" wire:click="setPaymentPercentage(50)" class="py-1.5 text-[10px] font-bold rounded-xl border {{ round(($this->totalTagihan * 50)/100) == $initialPayment && $initialPayment > 0 ? 'bg-[#0e3f32] text-white border-[#0e3f32]' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                            50% DP
                        </button>
                        <button type="button" wire:click="setPaymentPercentage(100)" class="py-1.5 text-[10px] font-bold rounded-xl border {{ $this->totalTagihan == $initialPayment && $initialPayment > 0 ? 'bg-[#0e3f32] text-white border-[#0e3f32]' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100' }}">
                            100% Lunas
                        </button>
                    </div>

                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-xs text-slate-400 font-bold">Rp</span>
                        <input wire:model.live="initialPayment" 
                               type="number" 
                               min="0" 
                               max="{{ $this->totalTagihan }}"
                               class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-[#0e3f32]">
                    </div>

                    @if($initialPayment > 0)
                        <!-- Payment Method Choice -->
                        <div class="space-y-1.5">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Metode Pembayaran:</span>
                            <div class="grid grid-cols-3 gap-2">
                                <label class="flex items-center justify-center p-2 rounded-xl border {{ $metodeBayar === 'cash' ? 'bg-emerald-50 border-[#0e3f32] text-[#0e3f32] font-bold' : 'border-slate-200 text-slate-600' }} cursor-pointer text-xs">
                                    <input wire:model="metodeBayar" type="radio" value="cash" class="sr-only">
                                    <span>Tunai</span>
                                </label>
                                <label class="flex items-center justify-center p-2 rounded-xl border {{ $metodeBayar === 'transfer' ? 'bg-emerald-50 border-[#0e3f32] text-[#0e3f32] font-bold' : 'border-slate-200 text-slate-600' }} cursor-pointer text-xs">
                                    <input wire:model="metodeBayar" type="radio" value="transfer" class="sr-only">
                                    <span>Transfer</span>
                                </label>
                                <label class="flex items-center justify-center p-2 rounded-xl border {{ $metodeBayar === 'qris' ? 'bg-emerald-50 border-[#0e3f32] text-[#0e3f32] font-bold' : 'border-slate-200 text-slate-600' }} cursor-pointer text-xs">
                                    <input wire:model="metodeBayar" type="radio" value="qris" class="sr-only">
                                    <span>QRIS</span>
                                </label>
                            </div>
                        </div>

                        <!-- Upload Bukti Bayar Awal -->
                        <div class="space-y-1">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                Bukti Pembayaran / Struk (Opsional):
                            </label>
                            <input wire:model="initialPaymentProof" 
                                   type="file" 
                                   accept="image/*,.pdf" 
                                   class="w-full text-xs text-slate-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-slate-200 file:text-slate-700 hover:file:bg-slate-300 cursor-pointer bg-slate-50 border border-slate-200 rounded-xl p-1">
                            @error('initialPaymentProof') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="initialPaymentProof" class="text-[10px] text-[#0d7a60] font-bold">
                                Mengunggah bukti pembayaran...
                            </div>
                            @if($initialPaymentProof)
                                <div class="flex items-center gap-1.5 p-1.5 bg-emerald-50 border border-emerald-200 rounded-lg text-xs text-emerald-800">
                                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span class="truncate flex-1 text-[11px] font-medium">{{ $initialPaymentProof->getClientOriginalName() }}</span>
                                    <button type="button" wire:click="$set('initialPaymentProof', null)" class="text-rose-600 hover:text-rose-800 font-bold px-1 text-xs">&times;</button>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Big Submit Action -->
                <div class="pt-2">
                    <button type="button" 
                            wire:click="submitOrder"
                            wire:loading.attr="disabled"
                            class="w-full py-4 px-4 bg-[#0e3f32] hover:bg-[#124b3c] text-white font-extrabold text-sm rounded-2xl shadow-xl shadow-[#0e3f32]/25 active:scale-98 transition-all flex items-center justify-center gap-2 disabled:opacity-50 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Simpan & Proses Transaksi</span>
                    </button>
                </div>
            </div>

        </div>

    </div>

    <!-- Product Detail Modal (Master Prompt Section 10 & 13) -->
    @if($showProductModal && $selectedProduct)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <!-- Overlay Backdrop (clean dark overlay without screen blur) -->
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity" wire:click="$set('showProductModal', false)" aria-hidden="true"></div>

            <!-- Centered Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div class="relative z-10 w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all border border-slate-200" wire:click.stop>
                    <div class="bg-white px-6 pt-6 pb-4 space-y-4">
                        
                        <!-- Modal Title & Master Product info -->
                        <div class="flex items-start justify-between gap-3 pb-3 border-b border-slate-100">
                            <div>
                                <span class="text-[10px] font-mono font-bold text-[#0d7a60] uppercase tracking-wider">
                                    Master: {{ $selectedProduct->kode_produk }} &bull; {{ $selectedProduct->nama_produk }}
                                </span>
                                <h3 class="text-base font-black text-slate-900 mt-0.5">Detail Item Transaksi</h3>
                            </div>
                            <button wire:click="$set('showProductModal', false)" class="text-slate-400 hover:text-slate-600 p-1 text-xl font-bold">
                                &times;
                            </button>
                        </div>

                        <!-- Editable Snapshot Name (Master Prompt Section 9 & 13) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Nama pada Transaksi (Snapshot) *
                            </label>
                            <input wire:model="modalNamaSnapshot" 
                                   type="text" 
                                   required 
                                   placeholder="Contoh: Kaos Polos Combed 30s Reaktif" 
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                            <span class="text-[10px] text-slate-400 mt-0.5 block">
                                Nama ini tercetak pada nota &amp; tidak akan mengubah nama master produk di database.
                            </span>
                        </div>

                        <!-- Multi-Size Breakdown or Single Quantity (Master Prompt Section 10 & 13) -->
                        @if($isSized)
                            <div class="bg-slate-50/80 p-4 rounded-2xl border border-slate-200/80 space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        Ukuran &amp; Jumlah (Pcs)
                                    </label>
                                    <span class="text-xs font-extrabold text-[#0e3f32]">
                                        Total: {{ $this->modalTotalQty }} pcs
                                    </span>
                                </div>

                                <!-- Sizes Row XS to L -->
                                <div class="grid grid-cols-4 gap-2">
                                    @foreach(['XS', 'S', 'M', 'L'] as $sz)
                                        <div class="bg-white p-2.5 rounded-xl border border-slate-200 text-center shadow-2xs">
                                            <span class="block text-[11px] font-black text-slate-700 mb-1.5">{{ $sz }}</span>
                                            <div class="flex items-center justify-center gap-1">
                                                <button type="button" 
                                                        wire:click="decrementSize('{{ $sz }}')"
                                                        class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center transition-colors">
                                                    -
                                                </button>
                                                <input type="number" 
                                                       wire:model.live.debounce.200ms="modalSizes.{{ $sz }}" 
                                                       min="0"
                                                       class="w-10 text-center text-xs font-bold text-slate-900 border-0 p-0 focus:ring-0">
                                                <button type="button" 
                                                        wire:click="incrementSize('{{ $sz }}')"
                                                        class="w-6 h-6 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center transition-colors">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Sizes Row XL to 3XL -->
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach(['XL', 'XXL', '3XL'] as $sz)
                                        <div class="bg-white p-2.5 rounded-xl border border-slate-200 text-center shadow-2xs">
                                            <span class="block text-[11px] font-black text-slate-700 mb-1.5">{{ $sz }}</span>
                                            <div class="flex items-center justify-center gap-1">
                                                <button type="button" 
                                                        wire:click="decrementSize('{{ $sz }}')"
                                                        class="w-6 h-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center transition-colors">
                                                    -
                                                </button>
                                                <input type="number" 
                                                       wire:model.live.debounce.200ms="modalSizes.{{ $sz }}" 
                                                       min="0"
                                                       class="w-10 text-center text-xs font-bold text-slate-900 border-0 p-0 focus:ring-0">
                                                <button type="button" 
                                                        wire:click="incrementSize('{{ $sz }}')"
                                                        class="w-6 h-6 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs flex items-center justify-center transition-colors">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <!-- Single Qty for non-apparel / service -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                        Jumlah / Volume ({{ $selectedProduct->satuan }}) *
                                    </label>
                                    <input wire:model.live.debounce.200ms="modalSingleJumlah" 
                                           type="number" 
                                           min="1" 
                                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                        Satuan
                                    </label>
                                    <input type="text" 
                                           disabled 
                                           value="{{ $selectedProduct->satuan }}" 
                                           class="w-full px-3.5 py-2 bg-slate-100 border border-slate-200 rounded-xl text-sm font-bold text-slate-500 uppercase">
                                </div>
                            </div>
                        @endif

                        <!-- Snapshot Price & Subtotal Preview -->
                        <div class="grid grid-cols-2 gap-3 items-end">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Harga Satuan Transaksi (Rp) *
                                </label>
                                <input wire:model.live.debounce.200ms="modalHarga" 
                                       type="number" 
                                       min="0" 
                                       step="500" 
                                       class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-black text-slate-800 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                            </div>
                            <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-right">
                                <span class="text-[10px] font-bold uppercase text-slate-400 block">Subtotal Item Ini</span>
                                <span class="text-base font-black text-[#0e3f32]">
                                    Rp {{ number_format($this->modalSubtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Special Item Notes -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Catatan Khusus Item Ini (Opsional)
                            </label>
                            <input wire:model="modalCatatan" 
                                   type="text" 
                                   placeholder="Contoh: Warna Kaos Hitam Reaktif 30s, sablon dada &amp; punggung..." 
                                   class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                        </div>
                    </div>

                    <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                        <button type="button" wire:click="$set('showProductModal', false)" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-200">
                            Batal
                        </button>
                        <button type="button" wire:click="addOrUpdateCart" class="px-6 py-2.5 bg-[#0e3f32] hover:bg-[#124b3c] text-white text-xs font-bold rounded-xl shadow-md shadow-[#0e3f32]/20">
                            {{ $editingCartIndex !== null ? 'Perbarui Item di Keranjang' : 'Simpan Detail ke Keranjang' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Quick New Customer Modal -->
    @if($showNewCustomerModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <!-- Overlay Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity" wire:click="$set('showNewCustomerModal', false)" aria-hidden="true"></div>

            <!-- Centered Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div class="relative z-10 w-full max-w-md transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all border border-slate-200" wire:click.stop>
                    <form wire:submit="createCustomerQuick">
                        <div class="bg-white px-6 pt-6 pb-4 space-y-4">
                            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                <h3 class="text-base font-black text-slate-900">Tambah Pelanggan Baru</h3>
                                <button type="button" wire:click="$set('showNewCustomerModal', false)" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama / Instansi / Komunitas *</label>
                                <input wire:model="newCustNama" type="text" required placeholder="Contoh: Distro Sablon Solo" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                                @error('newCustNama') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nomor WhatsApp / HP</label>
                                <input wire:model="newCustNoHp" type="text" placeholder="081234567890" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                                @error('newCustNoHp') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Pengiriman</label>
                                <textarea wire:model="newCustAlamat" rows="2" placeholder="Alamat jalan, RT/RW, kota tujuan..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none"></textarea>
                                @error('newCustAlamat') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                            <button type="button" wire:click="$set('showNewCustomerModal', false)" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-200">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-[#0e3f32] hover:bg-[#124b3c] text-white text-xs font-bold rounded-xl shadow-md shadow-[#0e3f32]/20">
                                Simpan &amp; Pilih Pelanggan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
