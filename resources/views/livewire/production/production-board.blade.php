<div class="space-y-6 pb-16">
    <!-- Top Header & Overdue Alert -->
    <div class="sm:flex sm:items-center sm:justify-between pb-4 border-b border-slate-200">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Papan Monitoring Produksi (Kanban)</h1>
            <p class="text-xs text-slate-500 mt-0.5">Pantau dan kelola pergerakan antrean kerja sablon dari pemotongan pola hingga selesai siap diambil.</p>
        </div>

        <div class="mt-4 sm:mt-0 flex items-center gap-3">
            @if($overdueCount > 0)
                <button type="button" 
                        wire:click="$toggle('onlyOverdue')"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-2xl text-xs font-bold transition-all shadow-2xs
                            {{ $onlyOverdue ? 'bg-rose-600 text-white shadow-md shadow-rose-600/30' : 'bg-rose-50 text-rose-700 border border-rose-200 hover:bg-rose-100' }}">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                    <span>{{ $overdueCount }} Pesanan Terlambat (Overdue)</span>
                </button>
            @endif
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-3.5 rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between gap-4">
        <div class="relative flex-1 max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="Cari invoice atau nama pemesan di papan produksi..." 
                   class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-2xl text-xs focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none transition-all">
        </div>

        <div class="text-xs text-slate-500 font-semibold hidden sm:flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Alur Produksi 5 Tahapan Sablon</span>
        </div>
    </div>

    <!-- 5 Columns Kanban Horizontal Scrolling Container -->
    <div class="w-full overflow-x-auto pb-6 pt-1">
        <div class="flex items-start gap-3.5 min-w-max">
            @foreach($columns as $key => $column)
                <div class="w-64 sm:w-72 shrink-0 bg-white rounded-2xl p-3 sm:p-3.5 border border-slate-200/80 shadow-2xs flex flex-col">
                    
                    <!-- Column Header -->
                    <div class="flex items-center justify-between pb-2.5 mb-2.5 border-b border-slate-100">
                        <div>
                            <h3 class="font-black text-xs uppercase tracking-wider text-slate-900">{{ $column['title'] }}</h3>
                            <p class="text-[10px] text-slate-400">{{ $column['desc'] }}</p>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-black
                            @if($key === 'cutting') bg-slate-100 text-slate-800
                            @elseif($key === 'jahit') bg-amber-50 text-amber-800
                            @elseif($key === 'printing') bg-[#0e3f32] text-white
                            @elseif($key === 'packing') bg-teal-50 text-teal-800
                            @elseif($key === 'selesai') bg-emerald-50 text-emerald-800
                            @endif">
                            {{ $column['items']->count() }}
                        </span>
                    </div>

                    <!-- Cards List -->
                    <div class="space-y-2.5 flex-1 min-h-[140px]">
                        @forelse($column['items'] as $item)
                            <div class="bg-slate-50/70 p-3 rounded-xl border border-slate-200/90 shadow-2xs hover:shadow-sm hover:border-[#0e3f32] transition-all space-y-2">
                                
                                <!-- Card Header: Invoice & Deadline Indicator -->
                                <div class="flex items-start justify-between gap-1.5">
                                    <a href="{{ route('transactions.detail', $item) }}" class="font-mono text-[11px] font-extrabold text-[#0e3f32] hover:underline">
                                        {{ $item->invoice_no }}
                                    </a>
                                    @if($item->is_overdue)
                                        <span class="px-1.5 py-0.5 rounded bg-rose-50 text-rose-700 border border-rose-200 text-[9px] font-black animate-pulse shrink-0">
                                            TERLAMBAT
                                        </span>
                                    @elseif($item->is_deadline_near)
                                        <span class="px-1.5 py-0.5 rounded bg-amber-50 text-amber-700 border border-amber-200 text-[9px] font-black shrink-0">
                                            H-{{ \Carbon\Carbon::today()->diffInDays(\Carbon\Carbon::parse($item->deadline)) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Customer Name & Pipeline Type -->
                                <div>
                                    <h4 class="text-[11px] font-bold text-slate-900 line-clamp-1">
                                        {{ $item->customer->nama ?? '-' }}
                                    </h4>
                                    <div class="flex items-center gap-1.5 mt-0.5 flex-wrap">
                                        <span class="text-[10px] text-slate-400 font-medium">
                                            Deadline: {{ $item->deadline->format('d/m/Y') }}
                                        </span>
                                        <span class="px-1.5 py-0.2 rounded text-[9px] font-extrabold
                                            @if($item->tahapan_list === ['printing', 'packing', 'selesai']) bg-amber-50 text-amber-800 border border-amber-200
                                            @elseif($item->tahapan_list === ['jahit', 'printing', 'packing', 'selesai']) bg-purple-50 text-purple-800 border border-purple-200
                                            @elseif($item->tahapan_list === \App\Models\Transaction::ALL_STAGES) bg-slate-100 text-slate-600
                                            @else bg-blue-50 text-blue-800 border border-blue-200
                                            @endif">
                                            {{ $item->pipeline_label }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Order Items Summary -->
                                <div class="p-2 rounded-lg bg-white border border-slate-200/80 text-[10px] text-slate-600 space-y-0.5">
                                    @foreach($item->details->take(2) as $det)
                                        <div class="truncate">
                                            &bull; {{ $det->jumlah }}x {{ $det->nama_produk_snapshot }}
                                            @if($det->ukuran) ({{ $det->ukuran }}) @endif
                                        </div>
                                    @endforeach
                                    @if($item->details->count() > 2)
                                        <div class="text-[9px] text-slate-400 italic">
                                            + {{ $item->details->count() - 2 }} item lainnya
                                        </div>
                                    @endif
                                </div>

                                <!-- Card Footer: Quick Advance or Modal Action -->
                                <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between text-xs">
                                    <button type="button" 
                                            wire:click="openStatusModal({{ $item->id }})" 
                                            class="text-[10px] font-bold text-slate-400 hover:text-slate-800">
                                        Opsi
                                    </button>

                                    @if($key !== 'selesai')
                                        <button type="button" 
                                                wire:click="advanceStage({{ $item->id }})"
                                                class="inline-flex items-center gap-1 text-[10px] font-bold text-[#0e3f32] hover:text-white bg-emerald-50 hover:bg-[#0e3f32] px-2 py-0.5 rounded-lg transition-colors">
                                            <span>Lanjut ({{ ucfirst($item->getNextStage() ?? 'Selesai') }}) &rarr;</span>
                                        </button>
                                    @else
                                        <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg">Siap Ambil</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="h-24 flex items-center justify-center border-2 border-dashed border-slate-100 rounded-xl text-center p-3 text-[10px] text-slate-400">
                                Tidak ada antrean
                            </div>
                        @endforelse
                    </div>

                </div>
            @endforeach
        </div>
    </div>

    <!-- Status Change Modal -->
    @if($showStatusModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <!-- Overlay Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity" wire:click="$set('showStatusModal', false)" aria-hidden="true"></div>

            <!-- Centered Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div class="relative z-10 w-full max-w-sm transform overflow-hidden rounded-3xl bg-white p-6 text-left shadow-2xl transition-all border border-slate-200" wire:click.stop>
                    <form wire:submit="submitStatusModal">
                        <h3 class="text-base font-black text-slate-900 mb-1">Pindahkan Tahap Produksi</h3>
                        @if($selectedTransaction)
                            <div class="mb-3 p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                                <span class="font-mono font-bold text-[#0e3f32] block">{{ $selectedTransaction->invoice_no }}</span>
                                <span class="text-[10px] text-slate-500">Alur: {{ $selectedTransaction->pipeline_label }}</span>
                            </div>
                        @endif

                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pilih Tahap Tujuan</label>
                                <select wire:model="newStatus" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-[#0e3f32] focus:outline-none">
                                    @if($selectedTransaction)
                                        @foreach(\App\Models\Transaction::STAGE_LABELS as $sKey => $sLabel)
                                            @php
                                                $isInPipeline = $selectedTransaction->hasStage($sKey);
                                            @endphp
                                            <option value="{{ $sKey }}">
                                                {{ $sLabel }} {{ $isInPipeline ? '✓ (Alur Pesanan)' : '(Di Luar Alur)' }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="cutting">Cutting (Pemotongan Bahan)</option>
                                        <option value="jahit">Jahit (Penjahitan Pola)</option>
                                        <option value="printing">Printing (Sablon / Cetak)</option>
                                        <option value="packing">Packing (Finishing & Kemas)</option>
                                        <option value="selesai">Selesai (Siap Diambil)</option>
                                    @endif
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Catatan Tambahan (Opsional)</label>
                                <textarea wire:model="statusNotes" rows="2" placeholder="Keterangan pengerjaan oleh petugas..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-[#0e3f32] focus:outline-none"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
                            <button type="button" wire:click="$set('showStatusModal', false)" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100">Batal</button>
                            <button type="submit" class="px-5 py-2 bg-[#0e3f32] hover:bg-[#124b3c] text-white text-xs font-bold rounded-xl shadow-md shadow-[#0e3f32]/20">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
