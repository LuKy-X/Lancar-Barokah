<div class="space-y-6 pb-16">
    <!-- Top breadcrumb & Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('customers.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Pelanggan</span>
        </a>
        <a href="{{ route('transactions.create', ['customer_id' => $customer->id]) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0e3f32] hover:bg-[#124b3c] text-white font-bold text-xs rounded-2xl shadow-md shadow-[#0e3f32]/20 transition-all">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            <span>+ Buat Order untuk Pelanggan Ini</span>
        </a>
    </div>

    <!-- Customer Profile Card (Bankio Style) -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-7">
        <div class="sm:flex sm:items-center sm:justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-[#0e3f32] text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-[#0e3f32]/25 shrink-0">
                    {{ strtoupper(substr($customer->nama, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ $customer->nama }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold bg-emerald-50 text-[#0d7a60] border border-emerald-200">
                            {{ $customer->no_customer }}
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 mt-2">
                        @if($customer->no_hp)
                            <span class="flex items-center gap-1 font-mono font-semibold text-slate-700">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                {{ $customer->no_hp }}
                            </span>
                        @endif
                        @if($customer->email)
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ $customer->email }}
                            </span>
                        @endif
                    </div>
                    @if($customer->alamat)
                        <p class="text-xs text-slate-600 mt-2 flex items-start gap-1">
                            <svg class="w-3.5 h-3.5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ $customer->alamat }}</span>
                        </p>
                    @endif
                </div>
            </div>

            <!-- Stats Mini-Widgets (Bankio Style) -->
            <div class="mt-6 sm:mt-0 grid grid-cols-3 gap-3 border-t sm:border-t-0 sm:border-l border-slate-100 pt-4 sm:pt-0 sm:pl-6 text-center shrink-0">
                <div class="px-2">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Nilai Order</p>
                    <p class="text-sm font-black text-slate-900 mt-1 font-mono">Rp {{ number_format($totalSpent, 0, ',', '.') }}</p>
                </div>
                <div class="px-2">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Terbayar</p>
                    <p class="text-sm font-black text-emerald-700 mt-1 font-mono">Rp {{ number_format($totalPaid, 0, ',', '.') }}</p>
                </div>
                <div class="px-2">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Order Aktif</p>
                    <p class="text-sm font-black text-[#0e3f32] mt-1">{{ $activeOrders }} Order</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order History List (Bankio Dark Table Header Style) -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-black text-slate-900 tracking-tight">Riwayat Transaksi & Produksi Pelanggan</h3>
                <p class="text-[11px] text-slate-400">Daftar seluruh nota order yang pernah dibuat oleh {{ $customer->nama }}</p>
            </div>
            <span class="text-xs text-slate-500 font-bold font-mono">{{ $transactions->total() }} Transaksi</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#18222c] text-white text-[11px] font-extrabold uppercase tracking-wider">
                        <th class="py-3.5 px-6">Invoice</th>
                        <th class="py-3.5 px-6">Tanggal & Deadline</th>
                        <th class="py-3.5 px-6 text-center">Status Produksi</th>
                        <th class="py-3.5 px-6 text-center">Status Pembayaran</th>
                        <th class="py-3.5 px-6 text-right">Total Tagihan</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($transactions as $trans)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-6 font-mono">
                                <a href="{{ route('transactions.detail', $trans) }}" class="font-extrabold text-[#0e3f32] hover:underline block">
                                    {{ $trans->invoice_no }}
                                </a>
                                <span class="text-[10px] text-slate-400 font-sans">{{ $trans->details->count() }} item</span>
                            </td>
                            <td class="py-4 px-6 text-xs">
                                <div class="font-bold text-slate-800">{{ $trans->tanggal_transaksi->format('d/m/Y') }}</div>
                                <div class="text-slate-400 mt-0.5">DL: {{ $trans->deadline->format('d/m/Y') }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                                    @if($trans->status_produksi === 'cutting') bg-slate-100 text-slate-800
                                    @elseif($trans->status_produksi === 'jahit') bg-amber-50 text-amber-800
                                    @elseif($trans->status_produksi === 'printing') bg-[#0e3f32] text-white
                                    @elseif($trans->status_produksi === 'packing') bg-teal-50 text-teal-800
                                    @elseif($trans->status_produksi === 'selesai') bg-emerald-50 text-emerald-800
                                    @endif">
                                    {{ $trans->status_produksi }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                                    @if($trans->status_pembayaran === 'lunas') bg-emerald-50 text-emerald-800 border border-emerald-200
                                    @elseif($trans->status_pembayaran === 'dp') bg-amber-50 text-amber-800 border border-amber-200
                                    @elseif($trans->status_pembayaran === 'cicilan') bg-blue-50 text-blue-800 border border-blue-200
                                    @else bg-rose-50 text-rose-800 border border-rose-200
                                    @endif">
                                    {{ str_replace('_', ' ', $trans->status_pembayaran) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right font-black text-slate-900 font-mono text-sm">
                                Rp {{ number_format($trans->total_tagihan, 0, ',', '.') }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('transactions.detail', $trans) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-[#0e3f32] hover:text-white text-slate-700 text-[10px] font-bold rounded-xl transition-colors">
                                    Buka Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Pelanggan ini belum memiliki riwayat transaksi order.
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
</div>
