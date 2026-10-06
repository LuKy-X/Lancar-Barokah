<div class="space-y-4">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-0.5 text-[10px] font-extrabold uppercase rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200/60">Master Data</span>
                <span class="text-xs text-slate-400 font-medium">Rekening & Kanal Bayar</span>
            </div>
            <h1 class="text-base sm:text-lg font-black text-slate-900 tracking-tight mt-1">
                Rekening & Metode Pembayaran
            </h1>
            <p class="text-xs text-slate-500 font-medium mt-0.5">
                Kelola akun rekening bank, QRIS digital, dan kasir workshop untuk penerimaan tagihan pesanan.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button wire:click="openCreateModal" class="inline-flex items-center gap-2 px-3.5 py-2 bg-[#0e3f32] hover:bg-[#124b3c] text-white text-xs font-bold rounded-xl shadow-sm shadow-[#0e3f32]/20 transition-all duration-150 transform active:scale-95 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Rekening / Kanal</span>
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
                       placeholder="Cari nama bank, rekening, atas nama..."
                       class="w-full pl-9 pr-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#0e3f32] focus:border-transparent transition-all">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <!-- Tipe Filter -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <span class="text-xs text-slate-400 font-bold hidden md:inline">Jenis:</span>
                <select wire:model.live="tipeFilter" class="w-full sm:w-auto px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-[#0e3f32] focus:border-transparent transition-all">
                    <option value="">Semua Kanal</option>
                    <option value="transfer">Transfer Bank</option>
                    <option value="qris">QRIS Digital</option>
                    <option value="cash">Tunai / Kasir</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/75 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">
                        <th class="py-3 px-4">Kanal & Nama Metode</th>
                        <th class="py-3 px-4">Tipe Pembayaran</th>
                        <th class="py-3 px-4">Nomor Rekening / ID</th>
                        <th class="py-3 px-4">Atas Nama</th>
                        <th class="py-3 px-4 text-center">Digunakan</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($methods as $method)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs shrink-0 {{ $method->tipe === 'transfer' ? 'bg-blue-50 text-blue-700 border border-blue-200' : ($method->tipe === 'qris' ? 'bg-purple-50 text-purple-700 border border-purple-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') }}">
                                        @if($method->tipe === 'transfer')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        @elseif($method->tipe === 'qris')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-extrabold text-slate-900">{{ $method->nama_metode }}</p>
                                        <p class="text-[10px] font-mono text-slate-400">{{ $method->kode_metode }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-[10px] font-extrabold rounded-lg inline-flex items-center gap-1.5 {{ $method->tipe === 'transfer' ? 'bg-blue-50 text-blue-800 border border-blue-200' : ($method->tipe === 'qris' ? 'bg-purple-50 text-purple-800 border border-purple-200' : 'bg-emerald-50 text-emerald-800 border border-emerald-200') }}">
                                    {{ $method->type_label }}
                                </span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($method->nomor_rekening)
                                    <span class="font-mono font-black text-slate-900 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 text-xs">
                                        {{ $method->nomor_rekening }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">- Langsung Kasir -</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-800 whitespace-nowrap">
                                {{ $method->atas_nama ?: '-' }}
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $method->payments_count }}x Transaksi
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <button type="button" 
                                        wire:click="toggleActive({{ $method->id }})"
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-extrabold transition-all cursor-pointer {{ $method->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}"
                                        title="Ubah status">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $method->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    {{ $method->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </button>
                            </td>
                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1">
                                    <button wire:click="openEditModal({{ $method->id }})" 
                                            class="p-1.5 text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors cursor-pointer"
                                            title="Edit Rekening">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $method->id }})" 
                                            class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors cursor-pointer"
                                            title="Hapus Rekening">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400">
                                <p class="font-bold text-slate-700">Belum ada metode/rekening pembayaran</p>
                                <p class="text-xs text-slate-400">Gunakan tombol "Tambah Rekening / Kanal" untuk membuat baru.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($methods->hasPages())
            <div class="p-3.5 border-t border-slate-100 bg-slate-50/50">
                {{ $methods->links() }}
            </div>
        @endif
    </div>

    <!-- Create / Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 overflow-y-auto">
            <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl border border-slate-200 overflow-hidden my-8">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
                    <h3 class="font-black text-slate-900 text-sm">
                        {{ $methodId ? 'Edit Rekening / Kanal Pembayaran' : 'Tambah Rekening / Kanal Pembayaran' }}
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="p-5 space-y-3.5 text-xs">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-1">
                                    Kode Metode <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" 
                                       wire:model="kode_metode" 
                                       placeholder="PAY-BCA"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-mono font-bold uppercase focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all">
                                @error('kode_metode') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-1">
                                    Jenis Pembayaran <span class="text-rose-500">*</span>
                                </label>
                                <select wire:model="tipe" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all">
                                    <option value="transfer">Transfer Bank (BCA, Mandiri, dll)</option>
                                    <option value="qris">QRIS Digital (E-Wallet & m-Banking)</option>
                                    <option value="cash">Tunai / Kasir Workshop</option>
                                </select>
                                @error('tipe') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-1">
                                Nama Kanal / Bank <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" 
                                   wire:model="nama_metode" 
                                   placeholder="Contoh: Bank Central Asia (BCA), QRIS Toko..."
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all">
                            @error('nama_metode') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-1">
                                    Nomor Rekening / ID Kanal
                                </label>
                                <input type="text" 
                                       wire:model="nomor_rekening" 
                                       placeholder="Contoh: 8830192833 (Kosongkan bila tunai)"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-mono font-bold focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all">
                                @error('nomor_rekening') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-1">
                                    Atas Nama Pemilik Rekening
                                </label>
                                <input type="text" 
                                       wire:model="atas_nama" 
                                       placeholder="Contoh: CV LANCAR BAROKAH"
                                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-bold focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all">
                                @error('atas_nama') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-1">
                                Petunjuk / Instruksi Pembayaran (Tampil di Invoice)
                            </label>
                            <textarea wire:model="keterangan" 
                                      rows="2" 
                                      placeholder="Contoh: Kirimkan bukti transfer ke WhatsApp admin..."
                                      class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl font-medium focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all"></textarea>
                            @error('keterangan') <span class="text-rose-500 text-[10px] font-bold block mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-1">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="is_active" class="w-4 h-4 rounded text-[#0e3f32] focus:ring-[#0e3f32] border-slate-300">
                                <span class="font-bold text-slate-700 text-xs">Aktifkan rekening ini untuk kasir & ditampilkan di invoice</span>
                            </label>
                        </div>
                    </div>

                    <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
                        <button type="button" wire:click="$set('showModal', false)" class="px-3.5 py-2 border border-slate-200 hover:bg-slate-100 text-slate-700 font-bold rounded-xl cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-[#0e3f32] hover:bg-[#124b3c] text-white font-bold rounded-xl shadow-sm cursor-pointer">
                            {{ $methodId ? 'Simpan Perubahan' : 'Tambah Rekening' }}
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
                    <h4 class="font-extrabold text-slate-900 text-sm">Hapus Metode Pembayaran?</h4>
                    <p class="text-xs text-slate-500">
                        Metode pembayaran tidak dapat dihapus jika masih ada catatan transaksi pembayaran yang merujuknya.
                    </p>
                </div>
                <div class="flex items-center justify-center gap-2 pt-2">
                    <button wire:click="$set('confirmingDelete', false)" class="w-full py-2 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl cursor-pointer">
                        Batal
                    </button>
                    <button wire:click="deleteMethod" class="w-full py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-sm cursor-pointer">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
