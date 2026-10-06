<div class="space-y-6 pb-16">
    <!-- Header with Action -->
    <div class="sm:flex sm:items-center sm:justify-between pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Daftar Transaksi Pesanan</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola seluruh order transaksi sablon, lacak progres produksi, dan riwayat pembayaran.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center gap-2">
            <a href="{{ route('transactions.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0e3f32] hover:bg-[#124b3c] text-white font-bold text-xs rounded-2xl shadow-md shadow-[#0e3f32]/20 transition-all active:scale-98">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>+ Buat Order Baru</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar Card (Bankio Clean Card) -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Search Input -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" 
                       type="text" 
                       placeholder="Cari invoice, pelanggan, no. HP, atau produk..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none transition-all">
            </div>

            <!-- Status Produksi Filter -->
            <div>
                <select wire:model.live="statusProduksi" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                    <option value="">Status Produksi: Semua</option>
                    <option value="cutting">Cutting</option>
                    <option value="jahit">Jahit</option>
                    <option value="printing">Printing</option>
                    <option value="packing">Packing</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>

            <!-- Status Pembayaran Filter -->
            <div>
                <select wire:model.live="statusPembayaran" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                    <option value="">Status Bayar: Semua</option>
                    <option value="belum_bayar">Belum Bayar</option>
                    <option value="dp">Uang Muka (DP)</option>
                    <option value="cicilan">Cicilan</option>
                    <option value="lunas">Lunas</option>
                </select>
            </div>

            <!-- Status Pengambilan Filter -->
            <div>
                <select wire:model.live="statusPengambilan" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                    <option value="">Pengambilan: Semua</option>
                    <option value="belum_diambil">Belum Diambil</option>
                    <option value="diambil">Sudah Diambil</option>
                </select>
            </div>
        </div>

        <!-- Date Range Filter Row -->
        <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100 text-xs">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-slate-400 font-bold">Rentang Tanggal:</span>
                <input wire:model.live="startDate" type="date" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-[#0e3f32]">
                <span class="text-slate-300">&mdash;</span>
                <input wire:model.live="endDate" type="date" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-[#0e3f32]">
            </div>

            @if($search || $statusProduksi || $statusPembayaran || $statusPengambilan || $startDate || $endDate)
                <button type="button" wire:click="resetFilters" class="text-xs font-bold text-[#0e3f32] hover:underline flex items-center gap-1">
                    <span>&times; Reset Filter</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Transactions Table (Bankio Dark Header Bar Style) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#18222c] text-white text-[10px] font-extrabold uppercase tracking-wider">
                        <th class="py-2.5 px-3 sm:px-3.5">Invoice</th>
                        <th class="py-2.5 px-3 sm:px-3.5">Pelanggan</th>
                        <th class="py-2.5 px-3 sm:px-3.5">Tanggal & Deadline</th>
                        <th class="py-2.5 px-3 sm:px-3.5 text-center">Produksi</th>
                        <th class="py-2.5 px-3 sm:px-3.5 text-center">Pembayaran</th>
                        <th class="py-2.5 px-3 sm:px-3.5 text-right">Tagihan</th>
                        <th class="py-2.5 px-3 sm:px-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($transactions as $trans)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-2.5 px-3 sm:px-3.5 font-mono">
                                <a href="{{ route('transactions.detail', $trans) }}" class="font-extrabold text-[#0e3f32] hover:underline block text-xs">
                                    {{ $trans->invoice_no }}
                                </a>
                                <div class="text-[10px] text-slate-400 font-sans font-normal">
                                    {{ $trans->details->count() }} baris item
                                </div>
                            </td>
                            <td class="py-2.5 px-3 sm:px-3.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-100 text-[#0e3f32] font-black text-[9px] flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($trans->customer->nama ?? 'C', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-xs">{{ $trans->customer->nama ?? '-' }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono">{{ $trans->customer->no_hp ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2.5 px-3 sm:px-3.5 text-xs">
                                <div class="font-bold text-slate-800">{{ $trans->tanggal_transaksi->format('d/m/Y') }}</div>
                                <div class="flex items-center gap-1 mt-0.5">
                                    <span class="text-slate-400 text-[10px]">DL: {{ $trans->deadline->format('d/m/Y') }}</span>
                                    @if($trans->is_overdue)
                                        <span class="px-1 py-0.2 rounded bg-rose-50 text-rose-700 border border-rose-200 font-bold text-[8px] animate-pulse">
                                            TERLAMBAT
                                        </span>
                                    @elseif($trans->is_deadline_near)
                                        <span class="px-1 py-0.2 rounded bg-amber-50 text-amber-700 border border-amber-200 font-bold text-[8px]">
                                            MEPET
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-2.5 px-3 sm:px-3.5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider
                                    @if($trans->status_produksi === 'cutting') bg-slate-100 text-slate-800
                                    @elseif($trans->status_produksi === 'jahit') bg-amber-50 text-amber-800
                                    @elseif($trans->status_produksi === 'printing') bg-[#0e3f32] text-white
                                    @elseif($trans->status_produksi === 'packing') bg-teal-50 text-teal-800
                                    @elseif($trans->status_produksi === 'selesai') bg-emerald-50 text-emerald-800
                                    @endif">
                                    {{ $trans->status_produksi }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3 sm:px-3.5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider
                                    @if($trans->status_pembayaran === 'lunas') bg-emerald-50 text-emerald-800 border border-emerald-200
                                    @elseif($trans->status_pembayaran === 'dp') bg-amber-50 text-amber-800 border border-amber-200
                                    @elseif($trans->status_pembayaran === 'cicilan') bg-blue-50 text-blue-800 border border-blue-200
                                    @else bg-rose-50 text-rose-800 border border-rose-200
                                    @endif">
                                    {{ str_replace('_', ' ', $trans->status_pembayaran) }}
                                </span>
                                @if($trans->status_pembayaran !== 'lunas')
                                    <div class="text-[9px] text-slate-400 font-mono mt-0.5">
                                        Sisa: Rp {{ number_format($trans->sisa_tagihan, 0, ',', '.') }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-2.5 px-3 sm:px-3.5 text-right font-black text-slate-900 font-mono text-xs">
                                Rp {{ number_format($trans->total_tagihan, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-3 sm:px-3.5 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('transactions.detail', $trans) }}" title="Lihat Detail" class="p-1 rounded text-slate-500 hover:text-[#0e3f32] hover:bg-slate-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('invoices.print', $trans) }}" target="_blank" title="Cetak Nota" class="p-1 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    </a>
                                    <a href="{{ route('invoices.download', $trans) }}" title="Unduh PDF" class="p-1 rounded text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>
                                    @if(auth()->user()->isAdmin())
                                        <button wire:click="confirmDelete({{ $trans->id }})" title="Hapus Transaksi" class="p-1 rounded text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="text-base font-bold text-slate-700">Tidak ada transaksi yang cocok</p>
                                <p class="text-xs text-slate-400 mt-1">Coba sesuaikan filter status atau kata kunci pencarian Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($confirmingDelete)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <!-- Overlay Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity" wire:click="$set('confirmingDelete', false)" aria-hidden="true"></div>

            <!-- Centered Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div class="relative z-10 w-full max-w-sm transform overflow-hidden rounded-3xl bg-white p-6 text-left shadow-2xl transition-all border border-slate-200" wire:click.stop>
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-base font-black text-slate-900 mb-1">Hapus Transaksi</h3>
                    <p class="text-xs text-slate-500 mb-6">Apakah Anda yakin ingin menghapus transaksi ini? Tindakan ini hanya diizinkan untuk transaksi yang belum memiliki riwayat pembayaran.</p>
                    <div class="flex items-center justify-end gap-2">
                        <button type="button" wire:click="$set('confirmingDelete', false)" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100">Batal</button>
                        <button type="button" wire:click="deleteTransaction" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-md shadow-rose-600/20">Hapus Transaksi</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
