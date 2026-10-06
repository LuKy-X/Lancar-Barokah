<div class="space-y-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-md bg-teal-50 text-teal-800 border border-teal-200/60">Master Data</span>
                <span class="text-xs text-slate-400 font-medium">Satuan Operasional</span>
            </div>
            <h1 class="text-base sm:text-lg font-black text-slate-900 tracking-tight mt-1">
                Satuan Ukuran Produk
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                Kelola satuan ukuran transaksi pakaian (pcs, lusin), jasa sablon (titik), dan perlengkapan (roll, pack).
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-3.5 py-2 bg-[#0e3f32] hover:bg-[#124b3c] text-white text-xs font-bold rounded-xl shadow-sm shadow-[#0e3f32]/20 transition-all duration-150 transform active:scale-95 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Satuan</span>
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-3.5 sm:p-4 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/50">
            <!-- Search Input -->
            <div class="relative w-full sm:w-80">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Cari kode satuan, nama..."
                       class="w-full pl-9 pr-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#0e3f32] focus:border-transparent transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <div class="text-xs text-slate-500 font-bold">
                Total: <span class="text-slate-900 font-black">{{ $totalUnits }}</span> Satuan (<span class="text-emerald-700 font-black">{{ $activeUnits }}</span> Aktif)
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">
                        <th class="py-3 px-4">Kode Satuan</th>
                        <th class="py-3 px-4">Nama Satuan</th>
                        <th class="py-3 px-4">Keterangan</th>
                        <th class="py-3 px-4 text-center">Jumlah Produk</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($units as $unit)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="font-mono font-black text-slate-900 px-2.5 py-1 bg-slate-100 rounded-lg border border-slate-200 text-xs">
                                    {{ $unit->kode_satuan }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-900">
                                {{ $unit->nama_satuan }}
                            </td>
                            <td class="py-3 px-4 text-slate-500">
                                {{ $unit->keterangan ?: '-' }}
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $unit->products_count }} Produk
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <button type="button" 
                                        wire:click="toggleActive({{ $unit->id }})"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold transition-all cursor-pointer {{ $unit->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}"
                                        title="Ubah status">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $unit->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    {{ $unit->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </button>
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <button wire:click="openEditModal({{ $unit->id }})" 
                                            class="p-1.5 text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors cursor-pointer"
                                            title="Edit Satuan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $unit->id }})" 
                                            class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                            title="Hapus Satuan">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-slate-400">
                                <p class="font-bold text-slate-700">Belum ada satuan ukuran</p>
                                <p class="text-xs text-slate-400">Gunakan tombol "Tambah Satuan" untuk membuat baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($units->hasPages())
            <div class="p-3.5 border-t border-slate-100 bg-slate-50/50">
                {{ $units->links() }}
            </div>
        @endif
    </div>

    <!-- Create / Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50">
            <div class="bg-white w-full max-w-md rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                    <h3 class="font-black text-slate-900 text-sm">
                        {{ $unitId ? 'Edit Satuan Ukuran' : 'Tambah Satuan Baru' }}
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="p-5 space-y-3.5 text-xs">
                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-1">
                                Kode Singkatan Satuan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="kode_satuan" 
                                   placeholder="Contoh: PCS, LSN, TTK, MTR"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-mono font-bold uppercase focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all">
                            @error('kode_satuan') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-1">
                                Nama Lengkap Satuan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="nama_satuan" 
                                   placeholder="Contoh: Pcs (Pieces / Potong), Lusin (12 pcs)"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all">
                            @error('nama_satuan') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-1">
                                Keterangan / Deskripsi
                            </label>
                            <textarea wire:model="keterangan" 
                                      rows="2" 
                                      placeholder="Penjelasan penggunaan satuan ini..."
                                      class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all"></textarea>
                            @error('keterangan') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-1">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="w-4 h-4 rounded text-[#0e3f32] focus:ring-[#0e3f32] border-slate-300">
                                <span class="font-bold text-slate-700 text-xs">Aktifkan satuan ini untuk produk</span>
                            </label>
                        </div>
                    </div>

                    <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" wire:click="$set('showModal', false)" class="px-3.5 py-2 border border-slate-200 hover:bg-slate-100 text-slate-700 font-bold rounded-xl cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-[#0e3f32] hover:bg-[#124b3c] text-white font-bold rounded-xl shadow-sm cursor-pointer">
                            {{ $unitId ? 'Simpan Perubahan' : 'Tambah Satuan' }}
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
                    <h4 class="font-extrabold text-slate-900 text-sm">Hapus Satuan Ukuran?</h4>
                    <p class="text-xs text-slate-500">
                        Satuan tidak dapat dihapus jika masih ada produk atau jasa yang menggunakannya.
                    </p>
                </div>
                <div class="flex items-center justify-center gap-2 pt-2">
                    <button wire:click="$set('confirmingDelete', false)" class="w-full py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl cursor-pointer">
                        Batal
                    </button>
                    <button wire:click="deleteUnit" class="w-full py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-sm cursor-pointer">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
