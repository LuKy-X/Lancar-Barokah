<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="pb-4 border-b border-slate-200">
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Profil Usaha & Pengaturan Invoice</h2>
        <p class="text-sm text-slate-500 mt-1">Informasi ini akan tercetak pada kop surat dan catatan footer invoice pelanggan.</p>
    </div>

    <form wire:submit="save" class="space-y-6">
        <!-- Identity Section -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8">
            <h3 class="text-base font-bold text-slate-900 mb-1 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-[#0e3f32]/10 text-[#0e3f32] flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <span>Identitas & Kontak Usaha</span>
            </h3>
            <p class="text-xs text-slate-500 mb-6">Nama usaha, alamat workshop, serta saluran komunikasi resmi.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Usaha / Konveksi *</label>
                    <input wire:model="nama_usaha" type="text" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                    @error('nama_usaha') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Workshop / Workshop Address</label>
                    <textarea wire:model="alamat" rows="2" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none"></textarea>
                    @error('alamat') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Telepon Kantor</label>
                    <input wire:model="telepon" type="text" placeholder="(0271) 123456" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                    @error('telepon') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor WhatsApp CS</label>
                    <input wire:model="whatsapp" type="text" placeholder="081234567890" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                    @error('whatsapp') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Resmi</label>
                    <input wire:model="email" type="email" placeholder="halo@lancarbarokah.com" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                    @error('email') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">URL Logo (Opsional)</label>
                    <input wire:model="logo" type="text" placeholder="https://example.com/logo.png" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                    @error('logo') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Bank Account Section -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8">
            <h3 class="text-base font-bold text-slate-900 mb-1 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <span>Rekening Pembayaran Resmi</span>
            </h3>
            <p class="text-xs text-slate-500 mb-6">Data rekening tujuan transfer yang akan dicantumkan di lembar invoice/tagihan.</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Bank</label>
                    <input wire:model="nama_bank" type="text" placeholder="BCA / Mandiri / BRI" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                    @error('nama_bank') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nomor Rekening</label>
                    <input wire:model="no_rekening" type="text" placeholder="0158921102" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-mono focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                    @error('no_rekening') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Atas Nama (A/N)</label>
                    <input wire:model="atas_nama" type="text" placeholder="Lancar Barokah Sablon" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-semibold focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                    @error('atas_nama') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Footer Invoice Section -->
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 sm:p-8">
            <h3 class="text-base font-bold text-slate-900 mb-1 flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-[#0e3f32]/10 text-[#0e3f32] flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <span>Catatan Kaki Invoice (Syarat & Ketentuan)</span>
            </h3>
            <p class="text-xs text-slate-500 mb-6">Teks syarat & ketentuan atau ucapan terima kasih di bagian bawah lembar invoice.</p>

            <div>
                <textarea wire:model="footer_invoice" rows="4" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none"></textarea>
                @error('footer_invoice') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end">
            <button type="submit" class="px-7 py-3 bg-[#0e3f32] hover:bg-[#124d3e] text-white font-bold text-sm rounded-2xl shadow-sm transition-all active:scale-98">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
