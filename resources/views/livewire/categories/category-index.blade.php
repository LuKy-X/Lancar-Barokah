<div class="space-y-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200/60">Master Data</span>
                <span class="text-xs text-slate-400 font-medium">Pengelompokan Usaha</span>
            </div>
            <h1 class="text-base sm:text-lg font-black text-slate-900 tracking-tight mt-1">
                Kategori Produk & Jasa
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                Kelola kategori untuk pakaian konveksi, jasa sablon/bordir, serta bahan baku & kemasan.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-3.5 py-2 bg-[#0e3f32] hover:bg-[#124b3c] text-white text-xs font-bold rounded-xl shadow-sm shadow-[#0e3f32]/20 transition-all duration-150 transform active:scale-95 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Kategori</span>
            </button>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-sm flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center font-black text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Total Kategori</span>
                <span class="text-sm sm:text-base font-black text-slate-900">{{ $counts['total'] }}</span>
            </div>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-sm flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center font-black text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <div>
                <span class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider block">Produk Pakaian</span>
                <span class="text-sm sm:text-base font-black text-slate-900">{{ $counts['produk'] }} Kategori</span>
            </div>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-sm flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-black text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
            </div>
            <div>
                <span class="text-[10px] font-bold text-teal-600 uppercase tracking-wider block">Jasa Sablon & Cetak</span>
                <span class="text-sm sm:text-base font-black text-slate-900">{{ $counts['jasa'] }} Kategori</span>
            </div>
        </div>
        <div class="bg-white p-3.5 rounded-xl border border-slate-200/80 shadow-sm flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center font-black text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
            </div>
            <div>
                <span class="text-[10px] font-bold text-amber-600 uppercase tracking-wider block">Bahan & Kemasan</span>
                <span class="text-sm sm:text-base font-black text-slate-900">{{ $counts['bahan'] }} Kategori</span>
            </div>
        </div>
    </div>

    <!-- Filters & Table Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-3.5 sm:p-4 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/50">
            <!-- Search Input -->
            <div class="relative w-full sm:w-80">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Cari kode, nama kategori, deskripsi..."
                       class="w-full pl-9 pr-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#0e3f32] focus:border-transparent transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Tipe Filter -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <span class="text-xs text-slate-400 font-bold hidden md:inline">Tipe:</span>
                <select wire:model.live="typeFilter" class="w-full sm:w-auto px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-[#0e3f32] focus:border-transparent transition-all">
                    <option value="">Semua Tipe</option>
                    <option value="produk">Pakaian / Produk Jadi</option>
                    <option value="jasa">Jasa Sablon / Bordir</option>
                    <option value="bahan">Bahan Baku & Kemasan</option>
                </select>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">
                        <th class="py-3 px-4">Kode</th>
                        <th class="py-3 px-4">Nama Kategori & Deskripsi</th>
                        <th class="py-3 px-4">Klasifikasi Tipe</th>
                        <th class="py-3 px-4 text-center">Jumlah Produk</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="font-mono font-bold text-slate-800 px-2 py-0.5 bg-slate-100 rounded-md border border-slate-200 text-[11px]">
                                    {{ $cat->kode_kategori }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-extrabold text-slate-900">{{ $cat->nama_kategori }}</p>
                                @if($cat->deskripsi)
                                    <p class="text-[11px] text-slate-500 font-normal line-clamp-1 mt-0.5">{{ $cat->deskripsi }}</p>
                                @endif
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-lg inline-flex items-center gap-1.5 {{ $cat->type_badge_classes }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $cat->tipe === 'produk' ? 'bg-emerald-500' : ($cat->tipe === 'jasa' ? 'bg-teal-500' : 'bg-amber-500') }}"></span>
                                    {{ $cat->type_label }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $cat->products_count }} Produk
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <button type="button" 
                                        wire:click="toggleActive({{ $cat->id }})"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold transition-all cursor-pointer {{ $cat->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}"
                                        title="Klik untuk mengubah status aktif">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $cat->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    {{ $cat->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </button>
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <button wire:click="openEditModal({{ $cat->id }})" 
                                            class="p-1.5 text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors cursor-pointer"
                                            title="Edit Kategori">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $cat->id }})" 
                                            class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                            title="Hapus Kategori">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400">
                                <div class="max-w-xs mx-auto text-center space-y-2">
                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    </div>
                                    <p class="font-bold text-slate-700">Tidak ada kategori ditemukan</p>
                                    <p class="text-xs text-slate-400">Coba ubah kata kunci pencarian atau buat kategori baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="p-3.5 border-t border-slate-100 bg-slate-50/50">
                {{ $categories->links() }}
            </div>
        @endif
    </div>

    <!-- Create / Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 overflow-y-auto">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl border border-slate-200 overflow-hidden transform transition-all my-8">
                <!-- Modal Header -->
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                    <div>
                        <h3 class="font-black text-slate-900 text-sm sm:text-base">
                            {{ $categoryId ? 'Edit Kategori Produk & Jasa' : 'Tambah Kategori Baru' }}
                        </h3>
                        <p class="text-[11px] text-slate-500 font-medium">
                            Isi detail kategori untuk pengelompokan produk dan pesanan.
                        </p>
                    </div>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal Form -->
                <form wire:submit.prevent="save">
                    <div class="p-5 space-y-3.5 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-1">
                                    Kode Kategori <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" 
                                       wire:model="kode_kategori" 
                                       placeholder="KAT-001"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all">
                                @error('kode_kategori') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-1">
                                    Klasifikasi Tipe <span class="text-rose-500">*</span>
                                </label>
                                <select wire:model="tipe" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all">
                                    <option value="produk">Pakaian / Produk Jadi</option>
                                    <option value="jasa">Jasa Sablon / Bordir / Cetak</option>
                                    <option value="bahan">Bahan Baku & Packaging</option>
                                </select>
                                @error('tipe') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-1">
                                Nama Kategori <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="nama_kategori" 
                                   placeholder="Contoh: Kaos Polos Combed, Jasa Sablon DTF..."
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all">
                            @error('nama_kategori') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-1">
                                Deskripsi / Catatan Kategori
                            </label>
                            <textarea wire:model="deskripsi" 
                                      rows="3" 
                                      placeholder="Penjelasan ringkas mengenai kategori ini..."
                                      class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all"></textarea>
                            @error('deskripsi') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-2">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="w-4 h-4 rounded text-[#0e3f32] focus:ring-[#0e3f32] border-slate-300">
                                <span class="font-bold text-slate-700 text-xs">Aktifkan kategori ini untuk katalog produk dan pesanan</span>
                            </label>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" 
                                wire:click="$set('showModal', false)" 
                                class="px-3.5 py-2 border border-slate-200 hover:bg-slate-100 text-slate-700 font-bold rounded-xl transition-colors cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-[#0e3f32] hover:bg-[#124b3c] text-white font-bold rounded-xl shadow-sm transition-all cursor-pointer">
                            {{ $categoryId ? 'Simpan Perubahan' : 'Tambah Kategori' }}
                        </button>
                    </div>
                </form>
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
                    <h4 class="font-extrabold text-slate-900 text-sm">Hapus Kategori?</h4>
                    <p class="text-xs text-slate-500">
                        Kategori tidak dapat dihapus jika masih ada produk atau jasa yang menggunakannya. Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="flex items-center justify-center gap-2 pt-2">
                    <button wire:click="$set('confirmingDelete', false)" class="w-full py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl cursor-pointer">
                        Batal
                    </button>
                    <button wire:click="deleteCategory" class="w-full py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-sm cursor-pointer">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
