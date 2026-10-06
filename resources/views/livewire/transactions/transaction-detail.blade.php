<div class="space-y-8 pb-16">
    <!-- Top Action Bar -->
    <div class="sm:flex sm:items-center sm:justify-between pb-4 border-b border-slate-200">
        <div class="flex items-center gap-3">
            <a href="{{ route('transactions.index') }}" class="p-2.5 rounded-2xl bg-white border border-slate-200 text-slate-500 hover:text-slate-800 transition-colors shadow-2xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="font-mono text-xl sm:text-2xl font-black text-slate-900">{{ $transaction->invoice_no }}</span>
                    
                    <!-- Production Status Chip -->
                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider
                        @if($transaction->status_produksi === 'cutting') bg-slate-100 text-slate-800 border border-slate-300
                        @elseif($transaction->status_produksi === 'jahit') bg-amber-50 text-amber-800 border border-amber-200
                        @elseif($transaction->status_produksi === 'printing') bg-[#0e3f32] text-white
                        @elseif($transaction->status_produksi === 'packing') bg-teal-50 text-teal-800 border border-teal-200
                        @elseif($transaction->status_produksi === 'selesai') bg-emerald-50 text-emerald-800 border border-emerald-200
                        @endif">
                        Tahap: {{ $transaction->status_produksi }}
                    </span>

                    <!-- Pipeline Type Chip -->
                    <span class="px-2.5 py-1 rounded-full text-xs font-extrabold
                        @if($transaction->tahapan_list === ['printing', 'packing', 'selesai']) bg-amber-50 text-amber-800 border border-amber-200
                        @elseif($transaction->tahapan_list === ['jahit', 'printing', 'packing', 'selesai']) bg-purple-50 text-purple-800 border border-purple-200
                        @elseif($transaction->tahapan_list === \App\Models\Transaction::ALL_STAGES) bg-slate-100 text-slate-700
                        @else bg-blue-50 text-blue-800 border border-blue-200
                        @endif">
                        {{ $transaction->pipeline_label }}
                    </span>

                    <!-- Payment Status Chip -->
                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider
                        @if($transaction->status_pembayaran === 'lunas') bg-emerald-50 text-emerald-800 border border-emerald-200
                        @elseif($transaction->status_pembayaran === 'dp') bg-amber-50 text-amber-800 border border-amber-200
                        @elseif($transaction->status_pembayaran === 'cicilan') bg-blue-50 text-blue-800 border border-blue-200
                        @else bg-rose-50 text-rose-800 border border-rose-200
                        @endif">
                        {{ str_replace('_', ' ', $transaction->status_pembayaran) }}
                    </span>

                    <!-- Pickup Status Chip -->
                    <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider {{ $transaction->status_pengambilan === 'diambil' ? 'bg-emerald-100 text-emerald-900' : 'bg-slate-100 text-slate-600' }}">
                        {{ str_replace('_', ' ', $transaction->status_pengambilan) }}
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1">Dibuat pada {{ $transaction->tanggal_transaksi->translatedFormat('d F Y') }} oleh {{ $transaction->user->name ?? 'Staf' }}</p>
            </div>
        </div>

        <div class="mt-4 sm:mt-0 flex flex-wrap items-center gap-2">
            <!-- Invoice Print / PDF Buttons -->
            <a href="{{ route('invoices.preview', $transaction) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 shadow-2xs transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>Lihat Nota</span>
            </a>
            <a href="{{ route('invoices.print', $transaction) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 shadow-2xs transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Nota</span>
            </a>
            <a href="{{ route('invoices.download', $transaction) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 shadow-2xs transition-colors">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Unduh PDF</span>
            </a>

            <!-- Update Production Status CTA -->
            <button wire:click="openStatusModal" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0e3f32] hover:bg-[#124b3c] text-white text-xs font-bold rounded-xl shadow-md shadow-[#0e3f32]/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>Update Status</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

        <!-- LEFT COLUMN: Items Table, Customer Card, & Production Timeline (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">

            <!-- Customer & Delivery Information Card -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6">
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pemesan (Customer)</span>
                        <h3 class="text-base font-black text-slate-900 mt-0.5">{{ $transaction->customer->nama ?? '-' }}</h3>
                        <p class="text-xs text-[#0d7a60] font-mono font-bold mt-0.5">{{ $transaction->customer->no_customer ?? '-' }}</p>
                    </div>
                    @if(!empty($transaction->customer->no_hp))
                        <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $transaction->customer->no_hp)) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold rounded-xl border border-emerald-200 transition-colors">
                            <span>Hubungi WA</span>
                            <span class="text-[11px] font-normal">({{ $transaction->customer->no_hp }})</span>
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-0.5">Target Selesai (Deadline):</span>
                        <span class="font-bold text-slate-800 {{ $transaction->is_overdue ? 'text-rose-600 font-extrabold' : '' }}">
                            {{ $transaction->deadline->translatedFormat('l, d F Y') }}
                            @if($transaction->is_overdue)
                                <span class="text-[10px] text-rose-500 block">(Terlewat / Overdue)</span>
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Status Pengambilan:</span>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold uppercase {{ $transaction->status_pengambilan === 'diambil' ? 'bg-emerald-50 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                {{ str_replace('_', ' ', $transaction->status_pengambilan) }}
                            </span>
                            @if($transaction->status_pengambilan === 'belum_diambil' && $transaction->status_produksi === 'selesai')
                                <button type="button" wire:click="markAsPickedUp" class="text-[10px] font-bold text-[#0e3f32] hover:underline bg-emerald-50 px-2 py-0.5 rounded">
                                    Tandai Diambil
                                </button>
                            @endif
                        </div>
                    </div>
                    @if($transaction->alamat_pengiriman)
                        <div class="col-span-2">
                            <span class="text-slate-400 block mb-0.5">Alamat Pengiriman:</span>
                            <span class="text-slate-700">{{ $transaction->alamat_pengiriman }}</span>
                        </div>
                    @endif
                    @if($transaction->catatan)
                        <div class="col-span-2 p-3 rounded-2xl bg-amber-50/70 border border-amber-200/80 text-amber-900 text-xs">
                            <span class="font-bold">Catatan Pesanan:</span> {{ $transaction->catatan }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Visual Workflow Stepper for this specific Order -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5 space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-900">Alur Produksi: {{ $transaction->pipeline_label }}</h3>
                        <p class="text-[11px] text-slate-400">Tahapan kerja terkonfigurasi khusus untuk pesanan ini</p>
                    </div>
                    @if($transaction->status_produksi !== 'selesai')
                        <button type="button" 
                                wire:click="advanceStage" 
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#0e3f32] hover:bg-[#124b3c] text-white text-xs font-bold rounded-xl shadow-xs transition-colors cursor-pointer">
                            <span>Maju ke Tahap {{ ucfirst($transaction->getNextStage() ?? 'Selesai') }} &rarr;</span>
                        </button>
                    @endif
                </div>

                @php
                    $tahapan = $transaction->tahapan_list;
                    $currentIndex = array_search($transaction->status_produksi, $tahapan, true);
                @endphp
                <div class="flex items-center justify-between gap-1 pt-2 overflow-x-auto">
                    @foreach($tahapan as $idx => $stageKey)
                        @php
                            $isPassed = $currentIndex !== false && $idx < $currentIndex;
                            $isCurrent = $transaction->status_produksi === $stageKey;
                            $isFuture = $currentIndex !== false && $idx > $currentIndex;
                        @endphp
                        <div class="flex-1 flex flex-col items-center text-center relative min-w-[70px]">
                            @if($idx > 0)
                                <div class="absolute -left-1/2 right-1/2 top-3.5 h-0.5 {{ $isPassed || $isCurrent ? 'bg-[#0e3f32]' : 'bg-slate-200' }} -z-0"></div>
                            @endif
                            <div class="relative z-10 w-7 h-7 rounded-full flex items-center justify-center text-xs font-extrabold transition-all
                                @if($isCurrent) bg-[#0e3f32] text-white ring-4 ring-[#0e3f32]/20 shadow-xs
                                @elseif($isPassed) bg-emerald-600 text-white
                                @else bg-slate-100 text-slate-400 border border-slate-200
                                @endif">
                                @if($isPassed) &#10003; @else {{ $idx + 1 }} @endif
                            </div>
                            <span class="text-[11px] font-bold capitalize mt-1.5 {{ $isCurrent ? 'text-[#0e3f32] font-black' : ($isPassed ? 'text-emerald-700' : 'text-slate-400') }}">
                                {{ $stageKey }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Ordered Line Items (Bankio Dark Table Header Style) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Rincian Item Pesanan</h3>
                        <p class="text-[11px] text-slate-400">Snapshot data produk & harga pada transaksi ini</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                        {{ $details->count() }} Baris Item
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-[#18222c] text-white text-[11px] font-extrabold uppercase tracking-wider">
                                <th class="py-3 px-6">Nama Produk / Jasa (Snapshot)</th>
                                <th class="py-3 px-4 text-center">Ukuran</th>
                                <th class="py-3 px-4 text-center">Jumlah</th>
                                <th class="py-3 px-4 text-right">Harga Snapshot</th>
                                <th class="py-3 px-6 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($details as $item)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-3.5 px-6">
                                        <div class="font-bold text-slate-900">{{ $item->nama_produk_snapshot }}</div>
                                        @if($item->catatan)
                                            <div class="text-[10px] text-slate-400 italic mt-0.5">{{ $item->catatan }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-bold font-mono text-slate-800">
                                        {{ $item->ukuran ?? '-' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-900">
                                        {{ $item->jumlah }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-mono font-medium text-slate-700">
                                        Rp {{ number_format($item->harga_snapshot, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3.5 px-6 text-right font-black text-slate-900 font-mono">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Production Timeline History -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-black text-slate-900 tracking-tight">Timeline & Riwayat Tahapan Produksi</h3>
                        <p class="text-[11px] text-slate-400">Seluruh mutasi status alur sablon tercatat otomatis</p>
                    </div>
                    <button type="button" wire:click="openStatusModal" class="text-xs font-bold text-[#0e3f32] hover:underline">
                        + Update Status
                    </button>
                </div>

                <div class="relative pl-6 space-y-5 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                    @forelse($statusHistories as $history)
                        <div class="relative">
                            <div class="absolute -left-[27px] top-1 w-3.5 h-3.5 rounded-full bg-[#0e3f32] ring-4 ring-white shadow-xs"></div>
                            <div class="text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="font-extrabold uppercase tracking-wider text-slate-900">{{ $history->status }}</span>
                                    <span class="text-slate-400 font-mono">{{ $history->waktu->format('d M Y, H:i') }}</span>
                                </div>
                                <p class="text-slate-600 mt-1">{{ $history->catatan ?? '-' }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Petugas: {{ $history->user->name ?? 'Sistem' }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">Belum ada riwayat alur status.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: Financial Summary & Payments History (5 Cols) -->
        <div class="lg:col-span-5 space-y-6">

            <!-- Financial Summary Card (Bankio Clean Style) -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 space-y-4">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Ringkasan Keuangan</h3>

                <div class="space-y-2.5 text-xs divide-y divide-slate-100">
                    <div class="flex justify-between text-slate-600 pt-1">
                        <span>Subtotal Produk & Jasa</span>
                        <span class="font-bold text-slate-800 font-mono">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($transaction->biaya_pengiriman > 0)
                        <div class="flex justify-between text-slate-600 pt-2">
                            <span>Ongkos Kirim</span>
                            <span class="font-bold text-slate-800 font-mono">Rp {{ number_format($transaction->biaya_pengiriman, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    @if($transaction->diskon > 0)
                        <div class="flex justify-between text-slate-600 pt-2">
                            <span>Potongan / Diskon</span>
                            <span class="font-bold text-rose-600 font-mono">- Rp {{ number_format($transaction->diskon, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-slate-900 font-black text-sm pt-3">
                        <span>Total Tagihan Order</span>
                        <span class="text-[#0e3f32] text-base font-mono">Rp {{ number_format($transaction->total_tagihan, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-emerald-800 font-bold pt-2">
                        <span>Total Telah Dibayar</span>
                        <span class="font-mono">Rp {{ number_format($transaction->total_bayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-black text-sm pt-2 {{ $transaction->sisa_tagihan > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                        <span>Sisa Tagihan (Piutang)</span>
                        <span class="font-mono">Rp {{ number_format($transaction->sisa_tagihan, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Payment Action Button -->
                @if($transaction->sisa_tagihan > 0)
                    <div class="pt-3">
                        <button type="button" 
                                wire:click="openPaymentModal"
                                class="w-full py-3.5 px-4 bg-[#0e3f32] hover:bg-[#124b3c] text-white font-extrabold text-xs rounded-2xl shadow-lg shadow-[#0e3f32]/20 active:scale-98 transition-all flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Catat Pembayaran (DP / Cicilan / Lunas)</span>
                        </button>
                    </div>
                @else
                    <div class="p-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-center text-xs font-extrabold text-emerald-800 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span>Transaksi Ini Telah LUNAS Sepenuhnya</span>
                    </div>
                @endif
            </div>

            <!-- Payment Logs History Table -->
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 space-y-3">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <h3 class="text-xs font-black text-slate-900 uppercase tracking-wider">Histori Pembayaran</h3>
                    <span class="text-xs font-bold text-slate-400 font-mono">{{ $payments->count() }} Kali Bayar</span>
                </div>

                <div class="space-y-2.5">
                    @forelse($payments as $p)
                        <div class="p-3 rounded-2xl bg-slate-50/80 border border-slate-200 text-xs space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-black text-slate-900 uppercase tracking-wider text-[10px]">
                                    {{ $p->jenis_bayar }} &bull; {{ strtoupper($p->metode_bayar) }}
                                </span>
                                <span class="font-mono font-black text-emerald-700">
                                    Rp {{ number_format($p->jumlah_bayar, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-slate-400">
                                <span>{{ $p->tanggal_bayar->format('d/m/Y') }} oleh {{ $p->user->name ?? 'Kasir' }}</span>
                                <div class="flex items-center gap-2">
                                    @if($p->bukti_bayar)
                                        <button type="button" 
                                                wire:click="viewProof({{ $p->id }})" 
                                                class="px-2 py-0.5 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100 font-bold flex items-center gap-1 transition-colors cursor-pointer">
                                            <svg class="w-3 h-3 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            <span>Lihat Bukti</span>
                                        </button>
                                    @endif
                                    @if($p->keterangan)
                                        <span class="italic truncate max-w-[120px]">{{ $p->keterangan }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic text-center py-4">Belum ada pembayaran yang dicatat.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- Payment Recording Modal -->
    @if($showPaymentModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <!-- Overlay Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity" wire:click="$set('showPaymentModal', false)" aria-hidden="true"></div>

            <!-- Centered Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div class="relative z-10 w-full max-w-md transform overflow-hidden rounded-3xl bg-white p-6 text-left shadow-2xl transition-all border border-slate-200" wire:click.stop>
                    <form wire:submit="submitPayment" class="space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <div>
                                <h3 class="text-base font-black text-slate-900">Catat Pembayaran Kasir</h3>
                                <p class="text-xs text-slate-500">Sisa tagihan: <strong class="font-mono text-rose-600">Rp {{ number_format($transaction->sisa_tagihan, 0, ',', '.') }}</strong></p>
                            </div>
                            <button type="button" wire:click="$set('showPaymentModal', false)" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jumlah Bayar (Rp) *</label>
                            <input wire:model="paymentAmount" type="number" min="1" max="{{ $transaction->sisa_tagihan }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-800 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                            @error('paymentAmount') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jenis Pembayaran</label>
                                <select wire:model="paymentType" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-[#0e3f32]">
                                    <option value="dp">DP (Uang Muka)</option>
                                    <option value="cicilan">Cicilan</option>
                                    <option value="pelunasan">Pelunasan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Metode Bayar</label>
                                <select wire:model="paymentMethod" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-[#0e3f32]">
                                    <option value="cash">Tunai (Cash)</option>
                                    <option value="transfer">Transfer Bank</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tanggal Bayar</label>
                            <input wire:model="paymentDate" type="date" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#0e3f32]">
                        </div>

                        <!-- Upload Bukti Bayar -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Bukti Pembayaran / Struk Transfer (Opsional)
                            </label>
                            <input wire:model="paymentProof" 
                                   type="file" 
                                   accept="image/*,.pdf" 
                                   class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer bg-slate-50 border border-slate-200 rounded-xl p-1">
                            @error('paymentProof') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                            <div wire:loading wire:target="paymentProof" class="text-[11px] text-[#0d7a60] font-bold mt-1">
                                Mengunggah bukti pembayaran...
                            </div>
                            @if($paymentProof)
                                <div class="mt-2 flex items-center gap-2 p-2 bg-emerald-50/80 border border-emerald-200 rounded-xl text-xs text-emerald-800">
                                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span class="truncate flex-1 font-semibold">{{ $paymentProof->getClientOriginalName() }}</span>
                                    <button type="button" wire:click="$set('paymentProof', null)" class="text-rose-600 hover:text-rose-800 font-bold px-1.5 py-0.5 rounded">&times; Hapus</button>
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Kasir (Opsional)</label>
                            <input wire:model="paymentNotes" type="text" placeholder="Contoh: Transfer BCA an Ferry..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#0e3f32]">
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                            <button type="button" wire:click="$set('showPaymentModal', false)" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-[#0e3f32] hover:bg-[#124b3c] text-white text-xs font-bold rounded-xl shadow-md shadow-[#0e3f32]/20">Simpan Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Production Status Update Modal -->
    @if($showStatusModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <!-- Overlay Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity" wire:click="$set('showStatusModal', false)" aria-hidden="true"></div>

            <!-- Centered Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div class="relative z-10 w-full max-w-sm transform overflow-hidden rounded-3xl bg-white p-6 text-left shadow-2xl transition-all border border-slate-200" wire:click.stop>
                    <form wire:submit="submitStatusUpdate" class="space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <h3 class="text-base font-black text-slate-900">Update Status Produksi</h3>
                            <button type="button" wire:click="$set('showStatusModal', false)" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status Tahapan Baru</label>
                            <select wire:model="newProductionStatus" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-[#0e3f32]">
                                @foreach(\App\Models\Transaction::STAGE_LABELS as $sKey => $sLabel)
                                    @php
                                        $isInPipeline = $transaction->hasStage($sKey);
                                    @endphp
                                    <option value="{{ $sKey }}">
                                        {{ $sLabel }} {{ $isInPipeline ? '✓ (Alur Pesanan)' : '(Di Luar Alur)' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Pengerjaan (Opsional)</label>
                            <textarea wire:model="statusNotes" rows="2" placeholder="Keterangan alur kerja staf..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#0e3f32]"></textarea>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2">
                            <button type="button" wire:click="$set('showStatusModal', false)" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100">Batal</button>
                            <button type="submit" class="px-5 py-2.5 bg-[#0e3f32] hover:bg-[#124b3c] text-white text-xs font-bold rounded-xl shadow-md shadow-[#0e3f32]/20">Simpan Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

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
