<div class="space-y-6 pb-16">
    <!-- Header with Action -->
    <div class="sm:flex sm:items-center sm:justify-between pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Data Pelanggan (Customer)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola data pemesan sablon, kontak WhatsApp, alamat, dan pantau histori order.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-2">
            <button wire:click="openCreateModal" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0e3f32] hover:bg-[#124b3c] text-white font-bold text-xs rounded-2xl shadow-md shadow-[#0e3f32]/20 transition-all active:scale-98 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>+ Tambah Pelanggan Baru</span>
            </button>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-white p-4 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="Cari nama, nomor HP / WA, atau nomor customer..." 
                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:outline-none focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all">
        </div>
        <div class="text-xs text-slate-500 font-bold hidden sm:block font-mono">
            Total {{ $customers->total() }} Pelanggan
        </div>
    </div>

    <!-- Customers Table (Bankio Dark Header Bar Style) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#18222c] text-white text-[10px] font-extrabold uppercase tracking-wider">
                        <th class="py-2.5 px-3 sm:px-3.5">Nama & No. Pelanggan</th>
                        <th class="py-2.5 px-3 sm:px-3.5">Kontak / WhatsApp</th>
                        <th class="py-2.5 px-3 sm:px-3.5">Alamat</th>
                        <th class="py-2.5 px-3 sm:px-3.5 text-center">Akumulasi Order</th>
                        <th class="py-2.5 px-3 sm:px-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($customers as $customer)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-2.5 px-3 sm:px-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-xl bg-[#0e3f32] text-white font-black flex items-center justify-center text-[10px] shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($customer->nama, 0, 2)) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('customers.detail', $customer) }}" class="font-extrabold text-slate-900 hover:text-[#0e3f32] transition-colors block text-xs">
                                            {{ $customer->nama }}
                                        </a>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $customer->no_customer }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2.5 px-3 sm:px-3.5">
                                @if($customer->no_hp)
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $customer->no_hp)) }}" 
                                       target="_blank" 
                                       class="inline-flex items-center gap-1.5 text-slate-800 hover:text-emerald-700 font-bold font-mono">
                                        <span>{{ $customer->no_hp }}</span>
                                        <span class="text-[9px] bg-emerald-50 text-emerald-800 border border-emerald-200 px-1 py-0.2 rounded font-extrabold">WA</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 italic text-xs">-</span>
                                @endif
                                @if($customer->email)
                                    <div class="text-[10px] text-slate-400">{{ $customer->email }}</div>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 sm:px-3.5 text-slate-600 max-w-xs truncate text-[11px]">
                                {{ $customer->alamat ?? '-' }}
                            </td>
                            <td class="py-2.5 px-3 sm:px-3.5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold {{ $customer->transactions_count > 0 ? 'bg-emerald-50 text-[#0e3f32] border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $customer->transactions_count }} Order
                                </span>
                            </td>
                            <td class="py-2.5 px-3 sm:px-3.5 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('customers.detail', $customer) }}" title="Lihat Profil & Riwayat" class="p-1 rounded text-slate-500 hover:text-[#0e3f32] hover:bg-slate-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <button wire:click="openEditModal({{ $customer->id }})" title="Edit Data" class="p-1 rounded text-slate-500 hover:text-amber-600 hover:bg-slate-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    @if(auth()->user()->isAdmin())
                                        <button wire:click="confirmDelete({{ $customer->id }})" 
                                                title="Hapus Pelanggan" 
                                                {{ $customer->transactions_count > 0 ? 'disabled' : '' }}
                                                class="p-1 rounded {{ $customer->transactions_count > 0 ? 'text-slate-300 cursor-not-allowed' : 'text-slate-400 hover:text-rose-600 hover:bg-rose-50' }} transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                Tidak ada data pelanggan yang cocok dengan pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $customers->links() }}
        </div>
    </div>

    <!-- Create / Edit Customer Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <!-- Overlay Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity" wire:click="$set('showModal', false)" aria-hidden="true"></div>

            <!-- Centered Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div class="relative z-10 w-full max-w-md transform overflow-hidden rounded-3xl bg-white p-6 text-left shadow-2xl transition-all border border-slate-200" wire:click.stop>
                    <form wire:submit="saveCustomer">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100 mb-4">
                            <h3 class="text-base font-black text-slate-900">
                                {{ $customerId ? 'Edit Data Pelanggan' : 'Pendaftaran Pelanggan Baru' }}
                            </h3>
                            <button type="button" wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Pelanggan / Instansi *</label>
                                <input wire:model="nama" type="text" required placeholder="Contoh: Ferry Sablon" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                                @error('nama') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nomor WhatsApp / HP</label>
                                <input wire:model="no_hp" type="text" placeholder="081234567890" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                                @error('no_hp') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email</label>
                                <input wire:model="email" type="email" placeholder="customer@email.com" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                                @error('email') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Lengkap</label>
                                <textarea wire:model="alamat" rows="2" placeholder="Alamat lengkap instansi / domisili..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none"></textarea>
                                @error('alamat') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
                            <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-[#0e3f32] hover:bg-[#124b3c] text-white text-xs font-bold rounded-xl shadow-md shadow-[#0e3f32]/20">
                                {{ $customerId ? 'Simpan Perubahan' : 'Daftarkan Pelanggan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($confirmingDelete)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <!-- Overlay Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity" wire:click="$set('confirmingDelete', false)" aria-hidden="true"></div>

            <!-- Centered Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div class="relative z-10 w-full max-w-sm transform overflow-hidden rounded-3xl bg-white p-6 text-left shadow-2xl transition-all border border-slate-200" wire:click.stop>
                    <h3 class="text-base font-black text-slate-900 mb-1">Hapus Data Pelanggan</h3>
                    <p class="text-xs text-slate-500 mb-6">Apakah Anda yakin ingin menghapus pelanggan ini? Pelanggan yang telah memiliki riwayat order tidak dapat dihapus.</p>
                    <div class="flex items-center justify-end gap-2">
                        <button type="button" wire:click="$set('confirmingDelete', false)" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100">Batal</button>
                        <button type="button" wire:click="deleteCustomer" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/20">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
