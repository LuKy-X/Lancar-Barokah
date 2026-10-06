<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-200">
        <a href="{{ route('transactions.detail', $transaction) }}" class="p-2 rounded-xl bg-white border border-slate-200 text-slate-500 hover:text-slate-800 transition-colors shadow-xs">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight">Edit Transaksi {{ $transaction->invoice_no }}</h2>
            <p class="text-xs text-slate-500 mt-0.5">Perbarui deadline, ongkos kirim, diskon, atau instruksi alamat pengiriman.</p>
        </div>
    </div>

    <form wire:submit="save" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
        <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Target Deadline Produksi *</label>
            <input wire:model="deadline" type="date" required class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:bg-white">
            @error('deadline') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Biaya Pengiriman (Rp)</label>
                <input wire:model="biayaPengiriman" type="number" min="0" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:bg-white">
                @error('biayaPengiriman') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Diskon / Potongan (Rp)</label>
                <input wire:model="diskon" type="number" min="0" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-rose-600 focus:ring-2 focus:ring-indigo-500 focus:bg-white">
                @error('diskon') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Alamat Pengiriman</label>
            <textarea wire:model="alamatPengiriman" rows="2" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:bg-white"></textarea>
            @error('alamatPengiriman') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1">Catatan Tambahan</label>
            <input wire:model="catatan" type="text" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm focus:ring-2 focus:ring-[#0e3f32] focus:bg-white">
            @error('catatan') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- Tahapan Alur Produksi Setting -->
        <div class="pt-2 border-t border-slate-100">
            <div class="flex items-center justify-between mb-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Tahapan Alur Produksi Pesanan</label>
                <span class="text-[11px] text-slate-400 font-mono">Status Aktif: <strong class="text-[#0e3f32] uppercase">{{ $transaction->status_produksi }}</strong></span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2">
                @php
                    $allStagesList = [
                        'cutting' => ['label' => 'Cutting', 'desc' => 'Potong Bahan'],
                        'jahit' => ['label' => 'Jahit', 'desc' => 'Penjahitan'],
                        'printing' => ['label' => 'Printing', 'desc' => 'Sablon/Cetak'],
                        'packing' => ['label' => 'Packing', 'desc' => 'Finishing'],
                        'selesai' => ['label' => 'Selesai', 'desc' => 'Siap Diambil'],
                    ];
                @endphp
                @foreach($allStagesList as $stageKey => $stageInfo)
                    @php
                        $isActive = in_array($stageKey, $tahapanProduksi, true);
                        $isCurrent = $transaction->status_produksi === $stageKey;
                    @endphp
                    <button type="button" 
                            wire:click="toggleTahap('{{ $stageKey }}')"
                            @if($stageKey === 'selesai' || $isCurrent) disabled @endif
                            class="p-2.5 rounded-xl border text-left transition-all flex flex-col justify-between relative {{ $isActive ? 'bg-emerald-50/70 border-emerald-300 text-emerald-950 ring-1 ring-emerald-300' : 'bg-slate-50 border-slate-200 text-slate-400 opacity-60' }} {{ ($stageKey === 'selesai' || $isCurrent) ? 'cursor-default' : 'cursor-pointer hover:border-emerald-400' }}">
                        <div class="flex items-center justify-between mb-1">
                            <span class="w-4 h-4 rounded-md flex items-center justify-center text-[10px] font-bold {{ $isActive ? 'bg-[#0e3f32] text-white' : 'bg-slate-200 text-slate-400' }}">
                                @if($isActive) &#10003; @endif
                            </span>
                            @if($isCurrent)
                                <span class="text-[8px] font-extrabold uppercase px-1 py-0.5 rounded bg-[#0e3f32] text-white">Aktif</span>
                            @elseif($stageKey === 'selesai')
                                <span class="text-[8px] text-slate-400 font-semibold">(Wajib)</span>
                            @endif
                        </div>
                        <div>
                            <span class="text-xs font-extrabold block">{{ $stageInfo['label'] }}</span>
                            <span class="text-[9px] block text-slate-500">{{ $stageInfo['desc'] }}</span>
                        </div>
                    </button>
                @endforeach
            </div>
            <p class="text-[10px] text-slate-400 mt-2">
                * Tahap yang saat ini sedang aktif berjalan pada pesanan tidak dapat dinonaktifkan sebelum statusnya dipindahkan terlebih dahulu.
            </p>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
            <a href="{{ route('transactions.detail', $transaction) }}" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 rounded-lg hover:bg-slate-100">Batal</a>
            <button type="submit" class="px-5 py-2 bg-[#0e3f32] hover:bg-[#124b3c] text-white text-xs font-bold rounded-xl shadow-md shadow-[#0e3f32]/20">Simpan Perubahan</button>
        </div>
    </form>
</div>
