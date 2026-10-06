<div class="space-y-4">
    <!-- Header -->
    <div class="sm:flex sm:items-center sm:justify-between pb-3 border-b border-slate-200">
        <div>
            <h2 class="text-base sm:text-lg font-black text-slate-900 tracking-tight">Laporan & Rekapitulasi Bisnis</h2>
            <p class="text-xs text-slate-500 mt-0.5">Laporan omzet penjualan, penerimaan kas harian/bulanan, piutang, dan kinerja deadline.</p>
        </div>

        <div class="mt-3 sm:mt-0 flex items-center gap-2">
            <button type="button" wire:click="exportCsv" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#0e3f32] hover:bg-[#124d3e] text-white font-bold text-xs rounded-xl shadow-xs transition-all active:scale-98">
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Ekspor CSV</span>
            </button>
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 shadow-xs transition-colors">
                <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    <!-- Date Presets & Filter Card -->
    <div class="bg-white p-3 rounded-2xl border border-slate-100 shadow-2xs space-y-3">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <!-- Preset Buttons -->
            <div class="flex flex-wrap items-center gap-1">
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mr-1">Periode:</span>
                <button type="button" wire:click="applyPreset('today')" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-colors {{ $datePreset === 'today' ? 'bg-[#0e3f32] text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Hari Ini</button>
                <button type="button" wire:click="applyPreset('week')" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-colors {{ $datePreset === 'week' ? 'bg-[#0e3f32] text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Minggu Ini</button>
                <button type="button" wire:click="applyPreset('month')" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-colors {{ $datePreset === 'month' ? 'bg-[#0e3f32] text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Bulan Ini</button>
                <button type="button" wire:click="applyPreset('last_month')" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-colors {{ $datePreset === 'last_month' ? 'bg-[#0e3f32] text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Bulan Lalu</button>
                <button type="button" wire:click="applyPreset('year')" class="px-2.5 py-1 rounded-lg text-xs font-bold transition-colors {{ $datePreset === 'year' ? 'bg-[#0e3f32] text-white shadow-2xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Tahun Ini</button>
            </div>

            <!-- Custom Date Inputs -->
            <div class="flex items-center gap-1.5 text-xs">
                <span class="text-slate-400 font-medium text-[11px]">Kustom:</span>
                <input wire:model.live="startDate" type="date" class="px-2.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                <span class="text-slate-400 font-medium text-[11px]">s/d</span>
                <input wire:model.live="endDate" type="date" class="px-2.5 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-1 border-b border-slate-200 text-xs overflow-x-auto pb-px">
        <button type="button" 
                wire:click="$set('activeTab', 'transaksi')"
                class="inline-flex items-center gap-1.5 px-3 py-2 font-bold border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'transaksi' ? 'border-[#0e3f32] text-[#0e3f32]' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            <span>Rekap Transaksi</span>
        </button>
        <button type="button" 
                wire:click="$set('activeTab', 'omzet')"
                class="inline-flex items-center gap-1.5 px-3 py-2 font-bold border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'omzet' ? 'border-[#0e3f32] text-[#0e3f32]' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            <span>Laporan Omzet</span>
        </button>
        <button type="button" 
                wire:click="$set('activeTab', 'kas')"
                class="inline-flex items-center gap-1.5 px-3 py-2 font-bold border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'kas' ? 'border-[#0e3f32] text-[#0e3f32]' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>Kas Masuk (Penerimaan)</span>
        </button>
        <button type="button" 
                wire:click="$set('activeTab', 'piutang')"
                class="inline-flex items-center gap-1.5 px-3 py-2 font-bold border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'piutang' ? 'border-[#0e3f32] text-[#0e3f32]' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span>Piutang (Belum Lunas) ({{ $totalPiutangCount }})</span>
        </button>
        <button type="button" 
                wire:click="$set('activeTab', 'overdue')"
                class="inline-flex items-center gap-1.5 px-3 py-2 font-bold border-b-2 transition-all whitespace-nowrap {{ $activeTab === 'overdue' ? 'border-[#0e3f32] text-[#0e3f32]' : 'border-transparent text-slate-500 hover:text-slate-800' }}">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Keterlambatan (Overdue) ({{ $totalOverdueCount }})</span>
        </button>
    </div>

    <!-- Summary KPI Cards for Periode -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <div class="bg-white p-3.5 rounded-2xl border border-slate-100 shadow-2xs">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Omzet Periode Ini</p>
            <p class="text-lg font-black text-slate-900 mt-1 font-mono">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-0.5 font-medium">{{ $totalOrders }} transaksi pesanan</p>
        </div>
        <div class="bg-white p-3.5 rounded-2xl border border-slate-100 shadow-2xs">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kas Masuk Terkumpul</p>
            <p class="text-lg font-black text-emerald-600 mt-1 font-mono">Rp {{ number_format($totalKas, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Uang muka, cicilan, dan pelunasan</p>
        </div>
        <div class="bg-white p-3.5 rounded-2xl border border-slate-100 shadow-2xs">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rata-rata Nilai Order</p>
            <p class="text-lg font-black text-slate-900 mt-1 font-mono">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</p>
            <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Rata-rata per transaksi</p>
        </div>
    </div>

    <!-- Active Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            @if($activeTab === 'kas')
                <!-- Table Kas Masuk -->
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#18222c] text-white text-[10px] font-bold uppercase tracking-wider">
                            <th class="py-2.5 px-3.5">Tanggal</th>
                            <th class="py-2.5 px-3.5">No. Invoice</th>
                            <th class="py-2.5 px-3.5">Pelanggan</th>
                            <th class="py-2.5 px-3.5">Jenis Pembayaran</th>
                            <th class="py-2.5 px-3.5">Metode</th>
                            <th class="py-2.5 px-3.5 text-right">Nominal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $row)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-2.5 px-3.5 text-slate-700 font-medium">{{ $row->tanggal_bayar->format('d/m/Y') }}</td>
                                <td class="py-2.5 px-3.5 font-mono font-bold text-[#0e3f32]">{{ $row->transaction->invoice_no ?? '-' }}</td>
                                <td class="py-2.5 px-3.5 font-bold text-slate-800">{{ $row->transaction->customer->nama ?? '-' }}</td>
                                <td class="py-2.5 px-3.5 uppercase font-bold text-slate-700 text-[11px]">{{ $row->jenis_bayar }}</td>
                                <td class="py-2.5 px-3.5 uppercase text-slate-600 font-medium text-[11px]">{{ $row->metode_bayar }}</td>
                                <td class="py-2.5 px-3.5 text-right font-black text-emerald-600 text-xs font-mono">
                                    + Rp {{ number_format($row->jumlah_bayar, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-8 text-center text-slate-400">Tidak ada data kas masuk pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <!-- Table Transaksi / Omzet / Piutang / Overdue -->
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#18222c] text-white text-[10px] font-bold uppercase tracking-wider">
                            <th class="py-2.5 px-3.5">No. Invoice</th>
                            <th class="py-2.5 px-3.5">Tanggal</th>
                            <th class="py-2.5 px-3.5">Target Deadline</th>
                            <th class="py-2.5 px-3.5">Pelanggan</th>
                            <th class="py-2.5 px-3.5 text-center">Produksi</th>
                            <th class="py-2.5 px-3.5 text-center">Status Bayar</th>
                            <th class="py-2.5 px-3.5 text-right">Total Tagihan</th>
                            <th class="py-2.5 px-3.5 text-right">Sisa Piutang</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data as $row)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-2.5 px-3.5 font-mono font-bold text-[#0e3f32]">
                                    <a href="{{ route('transactions.detail', $row) }}" class="hover:underline">
                                        {{ $row->invoice_no }}
                                    </a>
                                </td>
                                <td class="py-2.5 px-3.5 text-slate-600 font-medium">{{ $row->tanggal_transaksi->format('d/m/Y') }}</td>
                                <td class="py-2.5 px-3.5">
                                    <span class="{{ $row->is_overdue ? 'text-rose-600 font-bold' : 'text-slate-600 font-medium' }}">
                                        {{ $row->deadline->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3.5 font-bold text-slate-800">{{ $row->customer->nama ?? '-' }}</td>
                                <td class="py-2.5 px-3.5 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase
                                        {{ $row->status_produksi === 'selesai' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                                        {{ $row->status_produksi }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3.5 text-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase
                                        {{ $row->status_pembayaran === 'lunas' ? 'bg-emerald-50 text-emerald-700' : ($row->status_pembayaran === 'belum_bayar' ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700') }}">
                                        {{ str_replace('_', ' ', $row->status_pembayaran) }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3.5 text-right font-black text-slate-900 font-mono">
                                    Rp {{ number_format($row->total_tagihan, 0, ',', '.') }}
                                </td>
                                <td class="py-2.5 px-3.5 text-right font-bold font-mono {{ $row->sisa_tagihan > 0 ? 'text-rose-600' : 'text-slate-400' }}">
                                    Rp {{ number_format($row->sisa_tagihan, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="py-8 text-center text-slate-400">Tidak ada data transaksi pada periode ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
