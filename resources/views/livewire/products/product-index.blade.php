<div class="space-y-6 pb-16">
    <!-- Header with Action -->
    <div class="sm:flex sm:items-center sm:justify-between pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Katalog Produk & Jasa Sablon</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data master kaos, polo, jasa cetak sablon, bordir, dan bahan pelengkap konveksi.</p>
        </div>
        @if(auth()->user()->isAdmin())
            <div class="mt-4 sm:mt-0 flex gap-2">
                <button wire:click="openCreateModal" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0e3f32] hover:bg-[#124b3c] text-white font-bold text-xs rounded-2xl shadow-md shadow-[#0e3f32]/20 transition-all active:scale-98 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah Item Baru</span>
                </button>
            </div>
        @else
            <div class="mt-4 sm:mt-0 flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 rounded-xl text-slate-600 font-bold text-xs border border-slate-200/80">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>Mode Katalog (Lihat Saja)</span>
            </div>
        @endif
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="Cari kode atau nama item produk/jasa..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:outline-none focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all">
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto">
            <span class="text-xs text-slate-400 font-bold hidden md:inline">Kategori:</span>
            <select wire:model.live="categoryFilter" class="w-full sm:w-auto px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                <option value="">Semua Kategori</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->nama_kategori }} ({{ $c->type_label }})</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Products Table (Bankio Dark Header Bar Style) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#18222c] text-white text-[10px] font-extrabold uppercase tracking-wider">
                        <th class="py-2.5 px-3 sm:px-3.5">Kode Item</th>
                        <th class="py-2.5 px-3 sm:px-3.5">Nama Produk / Jasa</th>
                        <th class="py-2.5 px-3 sm:px-3.5">Kategori</th>
                        <th class="py-2.5 px-3 sm:px-3.5 text-right">Harga Default (Rp)</th>
                        <th class="py-2.5 px-3 sm:px-3.5 text-center">Status</th>
                        @if(auth()->user()->isAdmin())
                            <th class="py-2.5 px-3 sm:px-3.5 text-right">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/70 transition-colors {{ ! $product->is_active ? 'opacity-60 bg-slate-50/40' : '' }}">
                            <td class="py-2.5 px-3 sm:px-3.5 font-mono text-xs font-extrabold text-[#0d7a60]">
                                {{ $product->kode_produk }}
                            </td>
                            <td class="py-2.5 px-3 sm:px-3.5 font-bold text-slate-900 text-xs">
                                {{ $product->nama_produk }}
                                <span class="text-[10px] text-slate-400 font-normal ml-1">/ {{ $product->satuan }}</span>
                                @if($product->deskripsi)
                                    <p class="text-[10px] text-slate-400 font-normal line-clamp-1 mt-0.5">{{ $product->deskripsi }}</p>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 sm:px-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-extrabold uppercase tracking-wider {{ $product->category?->type_badge_classes ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $product->category?->nama_kategori ?? '-' }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 sm:px-3.5 text-right font-black text-slate-900 font-mono text-xs">
                                Rp {{ number_format($product->harga_default, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-3 sm:px-3.5 text-center">
                                @if(auth()->user()->isAdmin())
                                    <button type="button" 
                                            wire:click="toggleActive({{ $product->id }})"
                                            title="Klik untuk ubah status aktif/nonaktif"
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-extrabold transition-colors cursor-pointer
                                                {{ $product->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $product->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </button>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-extrabold
                                                {{ $product->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-500 border border-slate-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $product->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        <span>{{ $product->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </span>
                                @endif
                            </td>
                            @if(auth()->user()->isAdmin())
                                <td class="py-2.5 px-3 sm:px-3.5 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <button wire:click="openEditModal({{ $product->id }})" title="Edit Item" class="p-1 rounded text-slate-500 hover:text-[#0e3f32] hover:bg-slate-100 transition-colors cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button wire:click="confirmDelete({{ $product->id }})" 
                                                title="Hapus Master" 
                                                {{ $product->transaction_details_count > 0 ? 'disabled' : '' }}
                                                class="p-1 rounded {{ $product->transaction_details_count > 0 ? 'text-slate-300 cursor-not-allowed' : 'text-slate-400 hover:text-rose-600 hover:bg-rose-50 cursor-pointer' }} transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? 6 : 5 }}" class="py-12 text-center text-slate-400">
                                Belum ada item produk atau jasa yang cocok.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Create / Edit Product Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <!-- Overlay Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity" wire:click="$set('showModal', false)" aria-hidden="true"></div>

            <!-- Centered Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div class="relative z-10 w-full max-w-md transform overflow-hidden rounded-3xl bg-white p-6 text-left shadow-2xl transition-all border border-slate-200" wire:click.stop>
                    <form wire:submit="saveProduct">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100 mb-4">
                            <h3 class="text-base font-black text-slate-900">
                                {{ $productId ? 'Edit Produk / Jasa' : 'Tambah Produk / Jasa Baru' }}
                            </h3>
                            <button type="button" wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 text-xl font-bold cursor-pointer">&times;</button>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kode Item *</label>
                                <input wire:model="kode_produk" type="text" required placeholder="Contoh: PRD-005 atau SRV-008" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                                @error('kode_produk') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Produk / Jasa *</label>
                                <input wire:model="nama_produk" type="text" required placeholder="Contoh: Polo Shirt Lacoste CVC" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                                @error('nama_produk') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Kategori *</label>
                                    <select wire:model="category_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-[#0e3f32]">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $c)
                                            <option value="{{ $c->id }}">{{ $c->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Satuan Ukuran</label>
                                    <select wire:model.live="unit_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-[#0e3f32]">
                                        <option value="">-- Pilih Satuan --</option>
                                        @foreach($units as $u)
                                            <option value="{{ $u->id }}">{{ $u->kode_satuan }} - {{ $u->nama_satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Harga Default (Rp) *</label>
                                <input wire:model="harga_default" type="number" min="0" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#0e3f32]">
                                @error('harga_default') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi / Catatan Produk</label>
                                <textarea wire:model="deskripsi" rows="2" placeholder="Keterangan bahan, kualitas atau spesifikasi..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#0e3f32]"></textarea>
                                @error('deskripsi') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex items-center gap-2 pt-1">
                                <input wire:model="is_active" type="checkbox" id="is_active" class="w-4 h-4 rounded text-[#0e3f32] focus:ring-[#0e3f32]">
                                <label for="is_active" class="text-xs font-bold text-slate-700">Status Aktif (Ditampilkan di katalog transaksi)</label>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
                            <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100 cursor-pointer">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-[#0e3f32] hover:bg-[#124b3c] text-white text-xs font-bold rounded-xl shadow-md shadow-[#0e3f32]/20 cursor-pointer">
                                {{ $productId ? 'Simpan Perubahan' : 'Tambahkan ke Katalog' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($confirmingDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50">
            <div class="bg-white w-full max-w-sm rounded-2xl shadow-xl border border-slate-200 overflow-hidden p-5 space-y-4">
                <div class="w-10 h-10 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div class="text-center space-y-1">
                    <h4 class="font-extrabold text-slate-900 text-sm">Hapus Item Produk/Jasa?</h4>
                    <p class="text-xs text-slate-500">
                        Item tidak dapat dihapus jika sudah pernah tercatat dalam transaksi.
                    </p>
                </div>
                <div class="flex items-center justify-center gap-2 pt-2">
                    <button wire:click="$set('confirmingDelete', false)" class="w-full py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl cursor-pointer">
                        Batal
                    </button>
                    <button wire:click="deleteProduct" class="w-full py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-sm cursor-pointer">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
