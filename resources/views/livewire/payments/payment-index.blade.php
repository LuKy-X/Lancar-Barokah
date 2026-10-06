<div class="space-y-6 pb-16">
    <!-- Header -->
    <div class="pb-4 border-b border-slate-200">
        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Data Penerimaan Kas & Pembayaran</h1>
        <p class="text-xs text-slate-500 mt-0.5">Daftar rekapan penerimaan pembayaran DP, cicilan, dan pelunasan transaksi sablon.</p>
    </div>

    <!-- 4 Revenue Metric Cards (Bankio Style) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Seluruh Kas Masuk</p>
            <p class="text-xl font-black text-[#0e3f32] mt-1 font-mono">Rp {{ number_format($totalAll, 0, ',', '.') }}</p>
            <p class="text-[10px] text-slate-400 mt-1">Akumulasi seluruh transaksi</p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Penerimaan Tunai (Cash)</p>
            <p class="text-xl font-black text-emerald-700 mt-1 font-mono">Rp {{ number_format($totalCash, 0, ',', '.') }}</p>
            <p class="text-[10px] text-slate-400 mt-1">Kas fisik kasir</p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Transfer Bank</p>
            <p class="text-xl font-black text-teal-700 mt-1 font-mono">Rp {{ number_format($totalTransfer, 0, ',', '.') }}</p>
            <p class="text-[10px] text-slate-400 mt-1">Rekening usaha</p>
        </div>
        <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Penerimaan QRIS</p>
            <p class="text-xl font-black text-slate-900 mt-1 font-mono">Rp {{ number_format($totalQris, 0, ',', '.') }}</p>
            <p class="text-[10px] text-slate-400 mt-1">Dompet digital & scan QRIS</p>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" 
                       type="text" 
                       placeholder="Cari invoice atau nama pelanggan..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none transition-all">
            </div>

            <div>
                <select wire:model.live="jenisFilter" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                    <option value="">Semua Jenis Pembayaran</option>
                    <option value="dp">Uang Muka (DP)</option>
                    <option value="cicilan">Cicilan</option>
                    <option value="pelunasan">Pelunasan</option>
                </select>
            </div>

            <div>
                <select wire:model.live="metodeFilter" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                    <option value="">Semua Metode Bayar</option>
                    <option value="cash">Tunai (Cash)</option>
                    <option value="transfer">Transfer Bank</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100 text-xs">
            <div class="flex items-center gap-2 flex-wrap">
                <span class="text-slate-400 font-bold">Rentang Tanggal Bayar:</span>
                <input wire:model.live="startDate" type="date" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-[#0e3f32]">
                <span class="text-slate-300">&mdash;</span>
                <input wire:model.live="endDate" type="date" class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-1 focus:ring-[#0e3f32]">
            </div>

            @if($search || $jenisFilter || $metodeFilter || $startDate || $endDate)
                <button type="button" wire:click="resetFilters" class="text-xs font-bold text-[#0e3f32] hover:underline">
                    &times; Reset Filter
                </button>
            @endif
        </div>
    </div>

    <!-- Payments Table (Bankio Dark Header Bar Style) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#18222c] text-white text-[10px] font-extrabold uppercase tracking-wider">
                        <th class="py-2.5 px-3.5">Tanggal</th>
                        <th class="py-2.5 px-3.5">Invoice & Pelanggan</th>
                        <th class="py-2.5 px-3.5">Jenis Pembayaran</th>
                        <th class="py-2.5 px-3.5">Metode</th>
                        <th class="py-2.5 px-3.5 text-center">Bukti Bayar</th>
                        <th class="py-2.5 px-3.5">Dicatat Oleh</th>
                        <th class="py-2.5 px-3.5 text-right">Nominal (Rp)</th>
                        <th class="py-2.5 px-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-2.5 px-3.5 text-xs font-bold text-slate-800">
                                {{ $payment->tanggal_bayar->format('d/m/Y') }}
                            </td>
                            <td class="py-2.5 px-3.5 font-mono">
                                <a href="{{ route('transactions.detail', $payment->transaction) }}" class="font-extrabold text-[#0e3f32] hover:underline block text-xs">
                                    {{ $payment->transaction->invoice_no }}
                                </a>
                                <span class="text-[10px] text-slate-400 font-sans">
                                    {{ $payment->transaction->customer->nama ?? '-' }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3.5">
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-extrabold uppercase tracking-wider
                                    @if($payment->jenis_bayar === 'pelunasan') bg-emerald-50 text-emerald-800 border border-emerald-200
                                    @elseif($payment->jenis_bayar === 'dp') bg-amber-50 text-amber-800 border border-amber-200
                                    @else bg-blue-50 text-blue-800 border border-blue-200
                                    @endif">
                                    {{ $payment->jenis_bayar }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3.5 text-slate-800 text-[11px]">
                                <span class="font-extrabold uppercase text-slate-900 block">{{ $payment->paymentMethod?->nama_metode ?? $payment->metode_bayar }}</span>
                                @if($payment->paymentMethod?->nomor_rekening)
                                    <span class="text-[10px] font-mono text-slate-400 block">{{ $payment->paymentMethod->nomor_rekening }}</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3.5 text-center">
                                @if($payment->bukti_bayar)
                                    <button type="button" 
                                            wire:click="viewProof({{ $payment->id }})" 
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 font-bold text-[10px] transition-colors cursor-pointer" 
                                            title="Klik untuk melihat bukti">
                                        <svg class="w-3 h-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Lihat Bukti</span>
                                    </button>
                                @else
                                    <span class="text-[10px] text-slate-400 font-medium italic">
                                        Tanpa Bukti
                                    </span>
                                @endif
                            </td>
                            <td class="py-2.5 px-3.5 text-slate-600 text-[11px]">
                                {{ $payment->user->name ?? 'Kasir' }}
                            </td>
                            <td class="py-2.5 px-3.5 text-right font-black text-slate-900 font-mono text-xs">
                                Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-3.5 text-right">
                                <a href="{{ route('transactions.detail', $payment->transaction) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-[#0e3f32] hover:text-white text-slate-700 text-[10px] font-bold rounded-lg transition-colors">
                                    Buka Order
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                Belum ada riwayat pembayaran yang cocok dengan filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- Proof Viewer Modal -->
    @if($viewingProofUrl)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/70 transition-opacity" wire:click="closeProofModal" aria-hidden="true"></div>
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div class="relative z-10 w-full max-w-2xl transform overflow-hidden rounded-3xl bg-white p-6 text-left shadow-2xl transition-all border border-slate-200" wire:click.stop>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <span class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-black">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-black text-slate-900">Bukti Pembayaran</h3>
                                <p class="text-[10px] text-slate-400">Verifikasi dokumen transfer kasir & pelanggan</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ $viewingProofUrl }}" target="_blank" download class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                                Unduh / Tab Baru
                            </a>
                            <button type="button" wire:click="closeProofModal" class="text-slate-400 hover:text-slate-600 text-2xl font-bold leading-none cursor-pointer">&times;</button>
                        </div>
                    </div>

                    <div class="py-4 flex items-center justify-center max-h-[70vh] overflow-y-auto">
                        @if($viewingProofType === 'pdf')
                            <iframe src="{{ $viewingProofUrl }}" class="w-full h-96 rounded-xl border border-slate-200"></iframe>
                        @else
                            <img src="{{ $viewingProofUrl }}" alt="Bukti Pembayaran" class="max-w-full max-h-[65vh] object-contain rounded-xl border border-slate-100 shadow-xs">
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
