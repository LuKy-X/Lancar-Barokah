<div class="space-y-6 pb-12">
    
    @if($isAdmin)
        <!-- ============================================================== -->
        <!-- =================== DASHBOARD ADMINISTRATOR ================== -->
        <!-- ============================================================== -->

        <!-- Admin Header -->
        <div class="sm:flex sm:items-center sm:justify-between pb-1">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded-lg bg-[#0e3f32] text-white text-[10px] font-black tracking-wider uppercase">Admin Mode</span>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        Dashboard Eksekutif & Bisnis
                    </h1>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Ringkasan performa finansial, volume penjualan, utilisasi produksi, dan arus kas usaha Lancar Barokah.
                </p>
            </div>
            <div class="mt-3 sm:mt-0 flex items-center gap-2">
                <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#0e3f32] hover:bg-[#124b3c] text-white font-bold text-xs rounded-xl shadow-md shadow-[#0e3f32]/20 transition-all active:scale-98">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Buat Order Baru</span>
                </a>
                <a href="{{ route('reports.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 shadow-2xs transition-colors">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Laporan Bisnis</span>
                </a>
            </div>
        </div>

        <!-- Overdue Alert Banner if Any -->
        @if($overdueCount > 0)
            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-xs text-rose-900">Perhatian: Ada {{ $overdueCount }} Pesanan Melewati Batas Waktu (Overdue)</h4>
                        <p class="text-[11px] text-rose-700 mt-0.5">Segera buka papan produksi untuk memprioritaskan antrean pengerjaan sablon.</p>
                    </div>
                </div>
                <a href="{{ route('production.board') }}" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-lg shadow-xs shrink-0 transition-colors">
                    Buka Papan Produksi &rarr;
                </a>
            </div>
        @endif

        <!-- Admin Financial Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Omzet Bulan Ini -->
            <div class="bg-white p-4 sm:p-4.5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between group hover:border-[#0e3f32]/40 transition-colors">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Omzet Bulan Ini</span>
                        <span class="px-2 py-0.5 rounded-full {{ $growthPercent >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }} text-[10px] font-bold">
                            {{ $growthPercent >= 0 ? '+' : '' }}{{ $growthPercent }}%
                        </span>
                    </div>
                    <p class="text-xl font-black text-slate-900 mt-1.5 font-mono">
                        Rp {{ number_format($omzetBulanIni, 0, ',', '.') }}
                    </p>
                </div>
                <p class="text-xs text-slate-500 mt-2.5 pt-2.5 border-t border-slate-100 flex items-center justify-between">
                    <span>Omzet Hari Ini:</span>
                    <strong class="text-slate-800 font-mono">Rp {{ number_format($omzetHariIni, 0, ',', '.') }}</strong>
                </p>
            </div>

            <!-- Card 2: Total Kas Masuk -->
            <div class="bg-white p-4 sm:p-4.5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between group hover:border-emerald-300 transition-colors">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Kas Masuk</span>
                        <span class="w-5 h-5 rounded-md bg-emerald-50 flex items-center justify-center">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                    </div>
                    <p class="text-xl font-black text-emerald-700 mt-1.5 font-mono">
                        Rp {{ number_format($kasBulanIni, 0, ',', '.') }}
                    </p>
                </div>
                <p class="text-xs text-slate-500 mt-2.5 pt-2.5 border-t border-slate-100 flex items-center justify-between">
                    <span>Kas Masuk Hari Ini:</span>
                    <strong class="text-emerald-800 font-mono">Rp {{ number_format($kasHariIni, 0, ',', '.') }}</strong>
                </p>
            </div>

            <!-- Card 3: Sisa Piutang Usaha -->
            <div class="bg-white p-4 sm:p-4.5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between group hover:border-amber-300 transition-colors">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Sisa Piutang Usaha</span>
                        <span class="w-5 h-5 rounded-md bg-rose-50 flex items-center justify-center">
                            <svg class="w-3 h-3 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </span>
                    </div>
                    <p class="text-xl font-black text-rose-600 mt-1.5 font-mono">
                        Rp {{ number_format($piutangBelumLunas, 0, ',', '.') }}
                    </p>
                </div>
                <p class="text-xs text-slate-500 mt-2.5 pt-2.5 border-t border-slate-100 flex items-center justify-between">
                    <span>Barang Siap Diambil:</span>
                    <strong class="text-[#0e3f32]">{{ $readyToPickupCount }} Order</strong>
                </p>
            </div>

            <!-- Card 4: Antrean Produksi Aktif (High Impact Dark Card) -->
            <div class="bg-[#0e3f32] p-4 sm:p-4.5 rounded-2xl text-white shadow-xl shadow-[#0e3f32]/25 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/5 rounded-full pointer-events-none"></div>
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-emerald-200/80 uppercase tracking-wider">Antrean Produksi Aktif</span>
                        <span class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center">
                            <svg class="w-3 h-3 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                    </div>
                    <p class="text-2xl font-black mt-1.5 font-mono">
                        {{ $inProductionCount }} <span class="text-xs font-normal text-emerald-200">Pesanan</span>
                    </p>
                </div>
                <div class="mt-2.5 pt-2.5 border-t border-white/10 flex items-center justify-between text-xs text-emerald-100">
                    <span>Pengerjaan Berjalan:</span>
                    <span class="px-2 py-0.5 rounded-full bg-white/15 font-bold text-[10px] text-white">
                        {{ $totalActiveProduction }} On-Progress
                    </span>
                </div>
            </div>
        </div>

        <!-- Middle Analytics Section: Production Stage Distribution & 7-Day Bar Chart -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            <!-- Stage Distribution (5 cols) -->
            <div class="lg:col-span-5 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-4 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 tracking-tight">Distribusi Alur Produksi</h3>
                        <p class="text-[11px] text-slate-400">Porsi pesanan pada tiap tahapan sablon</p>
                    </div>
                    <a href="{{ route('production.board') }}" class="text-xs font-bold text-[#0e3f32] hover:underline">
                        Lihat Board &rarr;
                    </a>
                </div>

                <div class="space-y-2.5 my-auto py-1">
                    @php
                        $stages = [
                            ['key' => 'cutting', 'label' => 'Cutting (Potong Pola)', 'color' => 'bg-slate-800', 'textColor' => 'text-slate-800', 'count' => $stageCounts['cutting']],
                            ['key' => 'jahit', 'label' => 'Jahit (Penjahitan)', 'color' => 'bg-amber-500', 'textColor' => 'text-amber-700', 'count' => $stageCounts['jahit']],
                            ['key' => 'printing', 'label' => 'Printing (Sablon)', 'color' => 'bg-[#0e3f32]', 'textColor' => 'text-[#0e3f32]', 'count' => $stageCounts['printing']],
                            ['key' => 'packing', 'label' => 'Packing (Finishing)', 'color' => 'bg-teal-500', 'textColor' => 'text-teal-700', 'count' => $stageCounts['packing']],
                            ['key' => 'selesai', 'label' => 'Selesai (Siap Diambil)', 'color' => 'bg-emerald-600', 'textColor' => 'text-emerald-700', 'count' => $stageCounts['selesai']],
                        ];
                        $totalAllOrders = max(1, array_sum($stageCounts));
                    @endphp

                    @foreach($stages as $stage)
                        @php
                            $pct = round(($stage['count'] / $totalAllOrders) * 100);
                        @endphp
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-bold text-slate-700">{{ $stage['label'] }}</span>
                                <span class="font-mono font-extrabold {{ $stage['textColor'] }}">
                                    {{ $stage['count'] }} order ({{ $pct }}%)
                                </span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full {{ $stage['color'] }} transition-all duration-500" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                    <span>Total order: {{ $totalAllOrders }} pesanan</span>
                    <span class="font-semibold text-slate-600">Real-time update</span>
                </div>
            </div>

            <!-- 7-Day Activity Chart (7 cols) -->
            <div class="lg:col-span-7 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 tracking-tight">Tren Transaksi 7 Hari Terakhir</h3>
                        <p class="text-[11px] text-slate-400">Aktivitas nilai order transaksi harian sablon</p>
                    </div>
                    <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">
                        Minggu Ini
                    </span>
                </div>

                <div class="h-40 flex items-end justify-between gap-2.5 pt-4 pb-1 px-1">
                    @foreach($activityDays as $day)
                        @php
                            $heightPercent = $maxDayVal > 0 ? max(8, round(($day['amount'] / $maxDayVal) * 100)) : 8;
                        @endphp
                        <div class="flex-1 flex flex-col items-center gap-1.5 group h-full justify-end">
                            <div class="relative w-full flex justify-center">
                                <div class="absolute -top-9 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900 text-white text-[10px] font-mono py-0.5 px-2 rounded-lg pointer-events-none whitespace-nowrap z-20 shadow-md">
                                    Rp {{ number_format($day['amount'], 0, ',', '.') }} ({{ $day['count'] }} order)
                                </div>
                                <div class="w-7 sm:w-8 rounded-t-lg bg-[#0e3f32] group-hover:bg-[#124b3c] transition-all duration-300" style="height: {{ $heightPercent }}%"></div>
                            </div>
                            <span class="text-[10px] font-bold text-slate-600 uppercase">{{ $day['day'] }}</span>
                            <span class="text-[9px] text-slate-400 font-mono -mt-1">{{ $day['date'] }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                    <span>Puncak: <strong class="text-slate-800 font-mono">Rp {{ number_format($maxDayVal, 0, ',', '.') }}</strong></span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded bg-[#0e3f32]"></span>
                        <span>Nilai Order (Rp)</span>
                    </span>
                </div>
            </div>
        </div>

        <!-- Admin Row 3: Payment Breakdown & Top Selling Products -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            <!-- Payment Method Breakdown (5 cols) -->
            <div class="lg:col-span-5 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 tracking-tight">Kasir: Metode Pembayaran</h3>
                        <p class="text-[11px] text-slate-400">Penerimaan kas bulan ini per kanal bayar</p>
                    </div>
                    <a href="{{ route('payments.index') }}" class="text-xs font-bold text-[#0e3f32] hover:underline">
                        Buka Kasir &rarr;
                    </a>
                </div>

                @php
                    $totalKasMetode = max(1, array_sum($paymentMethodBreakdown));
                @endphp

                <div class="space-y-3 pt-2">
                    <!-- Transfer Bank -->
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70">
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-extrabold text-slate-800 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                Transfer Bank
                            </span>
                            <span class="font-mono font-black text-slate-900">
                                Rp {{ number_format($paymentMethodBreakdown['transfer'], 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-slate-200 overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full" style="width: {{ round(($paymentMethodBreakdown['transfer'] / $totalKasMetode) * 100) }}%"></div>
                        </div>
                    </div>

                    <!-- QRIS -->
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70">
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-extrabold text-slate-800 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                                QRIS Digital
                            </span>
                            <span class="font-mono font-black text-slate-900">
                                Rp {{ number_format($paymentMethodBreakdown['qris'], 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-slate-200 overflow-hidden">
                            <div class="h-full bg-teal-500 rounded-full" style="width: {{ round(($paymentMethodBreakdown['qris'] / $totalKasMetode) * 100) }}%"></div>
                        </div>
                    </div>

                    <!-- Tunai (Cash) -->
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/70">
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="font-extrabold text-slate-800 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Tunai / Kasir
                            </span>
                            <span class="font-mono font-black text-slate-900">
                                Rp {{ number_format($paymentMethodBreakdown['cash'], 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="w-full h-1.5 rounded-full bg-slate-200 overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ round(($paymentMethodBreakdown['cash'] / $totalKasMetode) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Selling Products / Services (7 cols) -->
            <div class="lg:col-span-7 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 tracking-tight">Top Produk & Jasa Paling Laris</h3>
                        <p class="text-[11px] text-slate-400">Item dengan pesanan kuantitas terbanyak bulan ini</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-[#0e3f32] text-[10px] font-bold">
                        Bulan Ini
                    </span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-100">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 text-[10px] font-extrabold uppercase tracking-wider border-b border-slate-200/80">
                                <th class="py-2 px-3">#</th>
                                <th class="py-2 px-3">Produk / Layanan</th>
                                <th class="py-2 px-3 text-center">Volume (Pcs/Qty)</th>
                                <th class="py-2 px-3 text-right">Total Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($topProducts as $idx => $prod)
                                <tr class="hover:bg-slate-50/70 transition-colors">
                                    <td class="py-2.5 px-3 font-bold text-slate-400 text-xs font-mono">
                                        {{ $idx + 1 }}
                                    </td>
                                    <td class="py-2.5 px-3 font-bold text-slate-800">
                                        {{ $prod->nama_produk_snapshot }}
                                    </td>
                                    <td class="py-2.5 px-3 text-center font-black font-mono text-[#0e3f32]">
                                        {{ number_format($prod->total_qty) }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-black font-mono text-slate-900">
                                        Rp {{ number_format($prod->total_nominal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400 text-xs">
                                        Belum ada data penjualan produk bulan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @else
        <!-- ============================================================== -->
        <!-- =================== DASHBOARD PETUGAS ======================== -->
        <!-- ============================================================== -->

        <!-- Petugas Header -->
        <div class="sm:flex sm:items-center sm:justify-between pb-1">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded-lg bg-teal-800 text-white text-[10px] font-black tracking-wider uppercase">Petugas Operasional</span>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
                        Pusat Kerja Operasional & Kasir
                    </h1>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Pantau antrean pesanan sablon, target deadline pengerjaan, dan kesiapan pengambilan barang pelanggan hari ini.
                </p>
            </div>
            <div class="mt-3 sm:mt-0 flex flex-wrap items-center gap-2">
                <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#0e3f32] hover:bg-[#124b3c] text-white font-bold text-xs rounded-xl shadow-md shadow-[#0e3f32]/20 transition-all active:scale-98">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Buat Order Baru</span>
                </a>
                <a href="{{ route('production.board') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 shadow-2xs transition-colors">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    <span>Papan Kanban Produksi</span>
                </a>
                <a href="{{ route('payments.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 shadow-2xs transition-colors">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Data Pembayaran</span>
                </a>
            </div>
        </div>

        <!-- Overdue Alert Banner if Any -->
        @if($overdueCount > 0)
            <div class="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-xs">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-rose-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-xs text-rose-900">Perhatian: Ada {{ $overdueCount }} Pesanan Melewati Batas Waktu!</h4>
                        <p class="text-[11px] text-rose-700 mt-0.5">Segera prioritaskan pengerjaan pesanan ini di papan produksi.</p>
                    </div>
                </div>
                <a href="{{ route('production.board') }}" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-lg shadow-xs shrink-0 transition-colors">
                    Prioritaskan di Kanban &rarr;
                </a>
            </div>
        @endif

        <!-- Petugas Operational Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Order Masuk Hari Ini -->
            <div class="bg-white p-4 sm:p-4.5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between group hover:border-[#0e3f32]/40 transition-colors">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Order Masuk Hari Ini</span>
                        <span class="w-5 h-5 rounded-md bg-emerald-50 flex items-center justify-center">
                            <svg class="w-3 h-3 text-[#0e3f32]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                    </div>
                    <p class="text-2xl font-black text-slate-900 mt-1.5 font-mono">
                        {{ $transaksiHariIniCount }} <span class="text-xs font-semibold text-slate-500">Pesanan</span>
                    </p>
                </div>
                <p class="text-xs text-slate-500 mt-2.5 pt-2.5 border-t border-slate-100 flex items-center justify-between">
                    <span>Input Kasir:</span>
                    <strong class="text-slate-800">Hari ini</strong>
                </p>
            </div>

            <!-- Card 2: Antrean Produksi Aktif (High Impact Dark Card) -->
            <div class="bg-[#0e3f32] p-4 sm:p-4.5 rounded-2xl text-white shadow-xl shadow-[#0e3f32]/25 flex flex-col justify-between relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-white/5 rounded-full pointer-events-none"></div>
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-emerald-200/80 uppercase tracking-wider">Sedang Dikerjakan</span>
                        <span class="w-5 h-5 rounded-full bg-white/10 flex items-center justify-center">
                            <svg class="w-3 h-3 text-emerald-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </span>
                    </div>
                    <p class="text-2xl font-black mt-1.5 font-mono">
                        {{ $inProductionCount }} <span class="text-xs font-normal text-emerald-200">Pesanan</span>
                    </p>
                </div>
                <div class="mt-2.5 pt-2.5 border-t border-white/10 flex items-center justify-between text-xs text-emerald-100">
                    <span>On-Progress:</span>
                    <span class="px-2 py-0.5 rounded-full bg-white/15 font-bold text-[10px] text-white">
                        {{ $totalActiveProduction }} Antrean
                    </span>
                </div>
            </div>

            <!-- Card 3: Deadline Mendesak -->
            <div class="bg-white p-4 sm:p-4.5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between group hover:border-amber-300 transition-colors">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Deadline Mendesak (&le; 2 Hari)</span>
                        <span class="w-5 h-5 rounded-md bg-amber-50 flex items-center justify-center">
                            <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                    </div>
                    <p class="text-2xl font-black text-amber-700 mt-1.5 font-mono">
                        {{ $urgentOrdersCount }} <span class="text-xs font-semibold text-slate-500">Order</span>
                    </p>
                </div>
                <p class="text-xs text-slate-500 mt-2.5 pt-2.5 border-t border-slate-100 flex items-center justify-between">
                    <span>Status:</span>
                    <strong class="{{ $urgentOrdersCount > 0 ? 'text-amber-700' : 'text-emerald-700' }}">
                        {{ $urgentOrdersCount > 0 ? 'Perlu Prioritas' : 'Terkendali' }}
                    </strong>
                </p>
            </div>

            <!-- Card 4: Siap Diambil Pelanggan -->
            <div class="bg-white p-4 sm:p-4.5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between group hover:border-emerald-300 transition-colors">
                <div>
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Siap Diambil Pelanggan</span>
                        <span class="w-5 h-5 rounded-md bg-emerald-50 flex items-center justify-center">
                            <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </span>
                    </div>
                    <p class="text-2xl font-black text-emerald-700 mt-1.5 font-mono">
                        {{ $readyToPickupCount }} <span class="text-xs font-semibold text-slate-500">Barang Jadi</span>
                    </p>
                </div>
                <p class="text-xs text-slate-500 mt-2.5 pt-2.5 border-t border-slate-100 flex items-center justify-between">
                    <span>Follow-up:</span>
                    <strong class="text-emerald-800">Hubungi Pemesan</strong>
                </p>
            </div>
        </div>

        <!-- Production Stage Quick Flow Cards (Clickable Pipeline) -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-extrabold text-slate-900 tracking-tight">Antrean Alur Produksi Sablon</h3>
                    <p class="text-[11px] text-slate-400">Distribusi pesanan aktif di workshop saat ini</p>
                </div>
                <a href="{{ route('production.board') }}" class="text-xs font-bold text-[#0e3f32] hover:underline">
                    Buka Papan Kanban &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 pt-1">
                <!-- Cutting -->
                <a href="{{ route('production.board') }}" class="p-3 rounded-xl bg-slate-50 hover:bg-slate-100/80 border border-slate-200/80 transition-all group">
                    <span class="text-[10px] font-extrabold uppercase text-slate-500 block">1. Cutting</span>
                    <span class="text-xl font-black font-mono text-slate-900 block mt-1">{{ $stageCounts['cutting'] }}</span>
                    <span class="text-[10px] text-slate-400 group-hover:text-[#0e3f32] font-semibold mt-0.5 block">Potong Pola &rarr;</span>
                </a>

                <!-- Jahit -->
                <a href="{{ route('production.board') }}" class="p-3 rounded-xl bg-amber-50/60 hover:bg-amber-50 border border-amber-200/80 transition-all group">
                    <span class="text-[10px] font-extrabold uppercase text-amber-700 block">2. Jahit</span>
                    <span class="text-xl font-black font-mono text-amber-900 block mt-1">{{ $stageCounts['jahit'] }}</span>
                    <span class="text-[10px] text-amber-700 group-hover:text-amber-900 font-semibold mt-0.5 block">Penjahitan &rarr;</span>
                </a>

                <!-- Printing -->
                <a href="{{ route('production.board') }}" class="p-3 rounded-xl bg-emerald-50/60 hover:bg-emerald-50 border border-emerald-200/80 transition-all group">
                    <span class="text-[10px] font-extrabold uppercase text-[#0e3f32] block">3. Printing</span>
                    <span class="text-xl font-black font-mono text-[#0e3f32] block mt-1">{{ $stageCounts['printing'] }}</span>
                    <span class="text-[10px] text-[#0d7a60] group-hover:text-[#0e3f32] font-semibold mt-0.5 block">Sablon & Cetak &rarr;</span>
                </a>

                <!-- Packing -->
                <a href="{{ route('production.board') }}" class="p-3 rounded-xl bg-teal-50/60 hover:bg-teal-50 border border-teal-200/80 transition-all group">
                    <span class="text-[10px] font-extrabold uppercase text-teal-700 block">4. Packing</span>
                    <span class="text-xl font-black font-mono text-teal-900 block mt-1">{{ $stageCounts['packing'] }}</span>
                    <span class="text-[10px] text-teal-700 group-hover:text-teal-900 font-semibold mt-0.5 block">Finishing & Kemas &rarr;</span>
                </a>

                <!-- Selesai -->
                <a href="{{ route('production.board') }}" class="p-3 rounded-xl bg-emerald-100/70 hover:bg-emerald-100 border border-emerald-300 transition-all group">
                    <span class="text-[10px] font-extrabold uppercase text-emerald-800 block">5. Selesai</span>
                    <span class="text-xl font-black font-mono text-emerald-950 block mt-1">{{ $stageCounts['selesai'] }}</span>
                    <span class="text-[10px] text-emerald-800 group-hover:text-emerald-950 font-semibold mt-0.5 block">Siap Diambil &rarr;</span>
                </a>
            </div>
        </div>

        <!-- Petugas Two Focus Columns: Urgent Deadlines vs Ready for Pickup -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            <!-- Left: Target Deadline Mendesak (7 cols) -->
            <div class="lg:col-span-7 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 tracking-tight">Prioritas Target Deadline Produksi</h3>
                        <p class="text-[11px] text-slate-400">Pesanan dengan jadwal penyelesaian terdekat</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold">
                        Top Prioritas
                    </span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-100">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 text-[10px] font-extrabold uppercase tracking-wider border-b border-slate-200/80">
                                <th class="py-2 px-3">Invoice & Pemesan</th>
                                <th class="py-2 px-3">Deadline</th>
                                <th class="py-2 px-3 text-center">Tahap</th>
                                <th class="py-2 px-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($upcomingDeadlines as $ord)
                                @php
                                    $isOver = $ord->deadline && $ord->deadline->isPast() && !$ord->deadline->isToday();
                                    $isToday = $ord->deadline && $ord->deadline->isToday();
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors {{ $isOver ? 'bg-rose-50/30' : '' }}">
                                    <td class="py-2.5 px-3">
                                        <a href="{{ route('transactions.detail', $ord) }}" class="font-mono font-bold text-[#0e3f32] hover:underline block text-xs">
                                            {{ $ord->invoice_no }}
                                        </a>
                                        <span class="text-[10px] text-slate-500 block truncate max-w-[150px]">
                                            {{ $ord->customer->nama ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 whitespace-nowrap">
                                        <span class="font-bold {{ $isOver ? 'text-rose-600' : ($isToday ? 'text-amber-600 font-black' : 'text-slate-700') }}">
                                            {{ $ord->deadline ? $ord->deadline->format('d/m/Y') : '-' }}
                                        </span>
                                        @if($isOver)
                                            <span class="block text-[9px] font-extrabold text-rose-600 uppercase">Lewat Batas</span>
                                        @elseif($isToday)
                                            <span class="block text-[9px] font-extrabold text-amber-600 uppercase">Hari Ini!</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase
                                            @if($ord->status_produksi === 'cutting') bg-slate-100 text-slate-800
                                            @elseif($ord->status_produksi === 'jahit') bg-amber-50 text-amber-800 border border-amber-200
                                            @elseif($ord->status_produksi === 'printing') bg-emerald-50 text-[#0e3f32] border border-emerald-200
                                            @elseif($ord->status_produksi === 'packing') bg-teal-50 text-teal-800 border border-teal-200
                                            @else bg-emerald-100 text-emerald-900
                                            @endif">
                                            {{ $ord->status_produksi }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-right">
                                        <a href="{{ route('transactions.detail', $ord) }}" class="px-2.5 py-1 bg-[#0e3f32] hover:bg-[#124b3c] text-white text-[10px] font-bold rounded-lg shadow-2xs transition-colors">
                                            Proses &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-6 text-center text-slate-400 text-xs">
                                        Tidak ada pesanan aktif yang mendekati deadline.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right: Barang Siap Diambil Pelanggan (5 cols) -->
            <div class="lg:col-span-5 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 tracking-tight">Pesanan Siap Diambil</h3>
                        <p class="text-[11px] text-slate-400">Barang sudah jadi, serahkan ke pelanggan</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-800 text-[10px] font-bold">
                        {{ $readyToPickupOrders->count() }} Menunggu
                    </span>
                </div>

                <div class="space-y-2.5">
                    @forelse($readyToPickupOrders as $rItem)
                        <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-200/80 text-xs space-y-1.5">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('transactions.detail', $rItem) }}" class="font-mono font-black text-[#0e3f32] hover:underline">
                                    {{ $rItem->invoice_no }}
                                </a>
                                @if($rItem->status_pembayaran === 'lunas')
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[9px] font-extrabold uppercase">
                                        LUNAS
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[9px] font-extrabold uppercase">
                                        Sisa: Rp {{ number_format($rItem->sisa_tagihan, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between text-[11px]">
                                <span class="font-bold text-slate-800">{{ $rItem->customer->nama ?? '-' }}</span>
                                <span class="text-slate-400 font-mono">{{ $rItem->customer->no_hp ?? '-' }}</span>
                            </div>
                            <div class="pt-1 border-t border-slate-200/60 flex items-center justify-between">
                                <span class="text-[10px] text-slate-400">{{ $rItem->details->sum('jumlah') }} pcs barang jadi</span>
                                @if($rItem->status_pembayaran === 'lunas')
                                    <button type="button" 
                                            wire:click="markAsPickedUp({{ $rItem->id }})" 
                                            class="px-2.5 py-1 bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-[10px] rounded-lg transition-colors cursor-pointer">
                                        ✓ Serahkan Barang
                                    </button>
                                @else
                                    <a href="{{ route('transactions.detail', $rItem) }}" class="px-2.5 py-1 bg-amber-600 hover:bg-amber-700 text-white font-bold text-[10px] rounded-lg transition-colors">
                                        Tagih Pelunasan &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-slate-400 text-xs">
                            Belum ada pesanan selesai yang menunggu pengambilan.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    <!-- ============================================================== -->
    <!-- ========== TABEL TRANSAKSI TERKINI (BERSAMA/COMMON) ========== -->
    <!-- ============================================================== -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden space-y-3 p-4 sm:p-5">
        
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h3 class="text-sm font-black text-slate-900 tracking-tight">Transaksi Terkini</h3>
                <p class="text-[11px] text-slate-400">Daftar transaksi order terbaru yang masuk ke sistem.</p>
            </div>
            <div class="mt-2 sm:mt-0 flex items-center gap-2">
                <a href="{{ route('transactions.index') }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-lg transition-colors">
                    Lihat Semua Transaksi &rarr;
                </a>
            </div>
        </div>

        <div class="overflow-x-auto rounded-xl border border-slate-200/80">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#18222c] text-white text-[10px] font-extrabold uppercase tracking-wider">
                        <th class="py-2.5 px-3 rounded-tl-xl">Nomor Invoice & Tanggal</th>
                        <th class="py-2.5 px-3">Pelanggan</th>
                        <th class="py-2.5 px-3">Rincian Order</th>
                        <th class="py-2.5 px-3 text-right">Total Tagihan</th>
                        <th class="py-2.5 px-3 text-center">Status Pembayaran</th>
                        <th class="py-2.5 px-3 text-center">Status Produksi</th>
                        <th class="py-2.5 px-3 text-right rounded-tr-xl">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($recentTransactions as $t)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-2 px-3 font-mono">
                                <a href="{{ route('transactions.detail', $t) }}" class="font-bold text-[#0e3f32] hover:underline block text-xs">
                                    {{ $t->invoice_no }}
                                </a>
                                <span class="text-[10px] text-slate-400">
                                    {{ $t->tanggal_transaksi->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="py-2 px-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-100 text-[#0e3f32] font-black text-[9px] flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($t->customer->nama ?? 'C', 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-slate-900 block truncate text-xs">{{ $t->customer->nama ?? '-' }}</span>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ $t->customer->no_hp ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2 px-3">
                                <span class="font-semibold text-slate-800 text-xs">
                                    {{ $t->details->count() }} item produk
                                </span>
                                <span class="text-[10px] text-slate-400 block truncate max-w-xs">
                                    {{ $t->details->pluck('nama_produk_snapshot')->take(2)->implode(', ') }}
                                </span>
                            </td>
                            <td class="py-2 px-3 text-right font-bold text-slate-900 font-mono text-xs">
                                Rp {{ number_format($t->total_tagihan, 0, ',', '.') }}
                            </td>
                            <td class="py-2 px-3 text-center">
                                @if($t->status_pembayaran === 'lunas')
                                    <span class="px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[9px] font-extrabold uppercase">Lunas</span>
                                @elseif($t->status_pembayaran === 'dp')
                                    <span class="px-2 py-0.5 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-[9px] font-extrabold uppercase">DP</span>
                                @elseif($t->status_pembayaran === 'cicilan')
                                    <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200 text-[9px] font-extrabold uppercase">Cicilan</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[9px] font-extrabold uppercase">Belum Bayar</span>
                                @endif
                            </td>
                            <td class="py-2 px-3 text-center">
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-bold text-[9px] uppercase">
                                    {{ $t->status_produksi }}
                                </span>
                            </td>
                            <td class="py-2 px-3 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <a href="{{ route('invoices.preview', $t) }}" title="Lihat Invoice" class="p-1 text-slate-400 hover:text-[#0e3f32] hover:bg-slate-100 rounded transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    <a href="{{ route('invoices.download', $t) }}" title="Unduh PDF" class="p-1 text-slate-400 hover:text-[#0e3f32] hover:bg-slate-100 rounded transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    </a>
                                    <a href="{{ route('transactions.detail', $t) }}" class="px-2 py-0.5 bg-[#0e3f32] hover:bg-[#124b3c] text-white text-[10px] font-bold rounded shadow-2xs">
                                        Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-slate-400">
                                Belum ada transaksi tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
