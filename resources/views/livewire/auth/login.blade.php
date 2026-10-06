<div class="w-full max-w-5xl bg-white rounded-[2rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.07)] border border-slate-200/80 overflow-hidden flex flex-col lg:flex-row my-auto">

    <!-- LEFT COLUMN: JETVIN Style Illustrated Hero & Feature Showcase -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-[#ecfdf5] via-[#f0fdfa] to-white p-10 xl:p-12 flex-col justify-between border-r border-slate-100 relative overflow-hidden">
        
        <!-- Subtle Ambient Shapes -->
        <div class="absolute -top-16 -left-16 w-64 h-64 bg-emerald-300/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -right-16 w-64 h-64 bg-teal-300/20 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Top Branding -->
        <div class="relative z-10 flex items-center gap-3">
            <img src="{{ asset('images/logo-icon.png') }}" alt="Logo Lancar Barokah" class="w-12 h-12 object-contain">
            <div>
                <h1 class="text-lg font-extrabold text-slate-900 tracking-tight leading-tight">Lancar Barokah</h1>
                <p class="text-[11px] text-[#0d7a60] font-bold tracking-wider uppercase">Sablon & Konveksi</p>
            </div>
        </div>

        <!-- Center: Modern Graphic Illustration of Sablon & Order Workflow -->
        <div class="relative z-10 my-auto py-8 space-y-6">
            <!-- Custom Clean Vector Graphic Presentation (JETVIN style) -->
            <div class="relative w-full max-w-sm mx-auto flex items-center justify-center py-4">
                <div class="w-48 h-48 rounded-3xl bg-white border border-emerald-100 shadow-xl shadow-emerald-500/10 flex flex-col items-center justify-center p-6 relative">
                    <!-- Screen Print Frame / T-Shirt Graphic Icon -->
                    <div class="w-20 h-20 rounded-2xl bg-emerald-50 text-[#0e3f32] flex items-center justify-center mb-3 border border-emerald-200/60 shadow-inner">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <span class="text-xs font-extrabold text-slate-800">Manajemen Produksi</span>
                    <span class="text-[10px] text-emerald-700 font-semibold mt-0.5">5 Tahap Alur Sablon</span>

                    <!-- Floating Badge 1: Real-time status -->
                    <div class="absolute -top-3 -right-4 px-3 py-1 bg-white rounded-full border border-slate-200/90 shadow-md flex items-center gap-1.5 text-[11px] font-bold text-slate-700">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Auto Invoice</span>
                    </div>

                    <!-- Floating Badge 2: Cash / DP -->
                    <div class="absolute -bottom-3 -left-4 px-3 py-1 bg-[#0e3f32] rounded-full shadow-md text-[10px] font-bold text-white flex items-center gap-1.5">
                        <svg class="w-3 h-3 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>DP & Pelunasan</span>
                    </div>
                </div>
            </div>

            <!-- Value Propositions -->
            <div class="text-center space-y-2">
                <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">
                    Digitalisasi Transaksi & Alur Produksi
                </h3>
                <p class="text-xs text-slate-500 leading-relaxed max-w-xs mx-auto">
                    Tinggalkan pencatatan manual spreadsheet. Kelola nota pesanan, antrean produksi, dan status pembayaran pelanggan dengan rapi dan terukur.
                </p>
            </div>
        </div>

        <!-- Bottom Footer info -->
        <div class="relative z-10 flex items-center justify-between text-[11px] text-slate-400 border-t border-slate-200/60 pt-4">
            <span>Sistem Operasional Internal</span>
            <span>Versi 2.5 &bull; Stabil</span>
        </div>
    </div>

    <!-- RIGHT COLUMN: JETVIN Style Login Card & Form -->
    <div class="flex-1 p-8 sm:p-12 lg:p-14 flex flex-col justify-center bg-white">
        
        <!-- Mobile Logo View -->
        <div class="lg:hidden flex items-center gap-3 mb-6">
            <img src="{{ asset('images/logo-icon.png') }}" alt="Logo Lancar Barokah" class="w-10 h-10 object-contain">
            <div>
                <h2 class="text-base font-extrabold text-slate-900 leading-tight">Lancar Barokah</h2>
                <p class="text-[10px] text-[#0d7a60] font-bold uppercase">Sablon & Konveksi</p>
            </div>
        </div>

        <div class="max-w-md w-full mx-auto space-y-6">
            
            <!-- Heading -->
            <div>
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Masuk ke Akun</h2>
                <p class="text-xs text-slate-500 mt-1">
                    Selamat datang kembali! Silakan masuk ke akun operasional Anda.
                </p>
            </div>

            <!-- JETVIN Fast Demo Credential Selector -->
            <div class="space-y-2 bg-slate-50/80 p-3.5 rounded-2xl border border-slate-100">
                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Klik untuk Masuk Cepat (Akun Demo):</span>
                </span>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" 
                            wire:click="setDemoCredentials('admin')"
                            class="p-2.5 text-left rounded-xl border transition-all duration-150 group {{ $username === 'admin' ? 'bg-white border-[#0e3f32] shadow-xs ring-1 ring-[#0e3f32]' : 'bg-white/70 border-slate-200 hover:border-slate-300 hover:bg-white' }}">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span class="text-xs font-bold text-slate-800 group-hover:text-[#0e3f32]">Admin</span>
                        </div>
                        <p class="text-[10px] text-slate-400">Akses Penuh</p>
                    </button>

                    <button type="button" 
                            wire:click="setDemoCredentials('petugas')"
                            class="p-2.5 text-left rounded-xl border transition-all duration-150 group {{ $username === 'petugas' ? 'bg-white border-[#0e3f32] shadow-xs ring-1 ring-[#0e3f32]' : 'bg-white/70 border-slate-200 hover:border-slate-300 hover:bg-white' }}">
                        <div class="flex items-center gap-1.5 mb-0.5">
                            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="text-xs font-bold text-slate-800 group-hover:text-[#0e3f32]">Petugas</span>
                        </div>
                        <p class="text-[10px] text-slate-400">Staf Produksi / Kasir</p>
                    </button>
                </div>
            </div>

            <!-- Login Form -->
            <form wire:submit="login" class="space-y-4" x-data="{ showPassword: false }">
                
                <!-- Username Input -->
                <div>
                    <label for="username" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Username / Login ID
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <input wire:model="username" 
                               type="text" 
                               id="username" 
                               required 
                               placeholder="admin atau petugas"
                               class="w-full pl-10 pr-4 py-3 bg-white border {{ $errors->has('username') ? 'border-rose-500 bg-rose-50/20' : 'border-slate-200' }} rounded-xl text-slate-800 placeholder-slate-400 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#0e3f32] focus:border-transparent transition-all shadow-xs">
                    </div>
                    @error('username')
                        <p class="text-xs text-rose-500 mt-1 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Kata Sandi (Password)
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input wire:model="password" 
                               :type="showPassword ? 'text' : 'password'"
                               id="password" 
                               required 
                               placeholder="••••••••"
                               class="w-full pl-10 pr-11 py-3 bg-white border {{ $errors->has('password') ? 'border-rose-500 bg-rose-50/20' : 'border-slate-200' }} rounded-xl text-slate-800 placeholder-slate-400 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-[#0e3f32] focus:border-transparent transition-all shadow-xs">
                        <button type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                            <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-rose-500 mt-1 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-xs text-slate-600 font-medium select-none">
                        <input wire:model="remember" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-[#0e3f32] focus:ring-[#0e3f32]">
                        <span>Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- JETVIN Style Bold Action Button -->
                <div class="pt-2">
                    <button type="submit" 
                            wire:loading.attr="disabled"
                            class="w-full py-3.5 px-4 bg-[#0e3f32] hover:bg-[#124b3c] active:scale-[0.98] text-white font-extrabold text-sm rounded-xl shadow-lg shadow-[#0e3f32]/25 transition-all duration-150 flex items-center justify-center gap-2 disabled:opacity-50 cursor-pointer">
                        <span wire:loading.remove wire:target="login">Masuk ke Sistem</span>
                        <span wire:loading wire:target="login" class="inline-flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                            <span>Memproses...</span>
                        </span>
                    </button>
                </div>
            </form>

            <div class="text-center pt-2">
                <p class="text-[11px] text-slate-400 font-medium">
                    &copy; {{ date('Y') }} Lancar Barokah Sablon & Konveksi. All rights reserved.
                </p>
            </div>

        </div>

    </div>

</div>
