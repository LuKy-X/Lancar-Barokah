<!DOCTYPE html>
<html lang="id" class="h-full bg-[#f8fafc] font-sans antialiased text-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Lancar Barokah' }} — Sistem Manajemen Transaksi & Produksi Sablon</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-icon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full flex overflow-hidden bg-[#f8fafc] selection:bg-[#0e3f32] selection:text-white" x-data="{ mobileMenuOpen: false, userMenuOpen: false }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition-opacity ease-linear duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 z-40 lg:hidden"
         @click="mobileMenuOpen = false"
         style="display: none;"></div>

    <!-- Bankio Style Modern Light Sidebar Navigation -->
    <aside :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 z-50 w-60 xl:w-64 bg-white text-slate-800 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shrink-0 border-r border-slate-200/80 shadow-sm lg:shadow-none">
        
        <!-- App Logo & Branding -->
        <div class="h-16 flex items-center justify-between px-4 border-b border-slate-100 bg-white">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                <img src="{{ asset('images/logo-icon.png') }}" alt="Logo Lancar Barokah" class="w-10 h-10 object-contain group-hover:scale-105 transition-transform duration-200">
                <div class="leading-tight">
                    <span class="font-extrabold text-slate-900 text-sm sm:text-base tracking-tight block">Lancar Barokah</span>
                    <span class="text-[10px] text-[#0d7a60] font-bold tracking-widest uppercase">Sablon & Konveksi</span>
                </div>
            </a>
            <button @click="mobileMenuOpen = false" class="lg:hidden text-slate-400 hover:text-slate-700 p-1.5 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Quick Order CTA Button -->
        <div class="px-3.5 py-3 border-b border-slate-100">
            <a href="{{ route('transactions.create') }}" class="w-full flex items-center justify-center gap-2 px-3.5 py-2.5 bg-[#0e3f32] hover:bg-[#124b3c] text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md shadow-[#0e3f32]/20 transition-all duration-150 transform active:scale-98">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>+ Buat Order Baru</span>
            </a>
        </div>

        <!-- Navigation Links with Bankio Pill Styling -->
        <nav class="flex-1 overflow-y-auto px-3.5 py-3 space-y-1 text-sm">
            <div class="px-2.5 pb-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Main Menu</div>

            <!-- Dashboard Link -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-[#0e3f32] text-white shadow-md shadow-[#0e3f32]/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Dashboard</span>
            </a>

            <!-- Daftar Transaksi Link -->
            <a href="{{ route('transactions.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold transition-all duration-150 {{ request()->routeIs('transactions.*') && !request()->routeIs('transactions.create') ? 'bg-[#0e3f32] text-white shadow-md shadow-[#0e3f32]/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Daftar Transaksi</span>
            </a>

            <!-- Monitoring Produksi (Kanban) Link -->
            <a href="{{ route('production.board') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold transition-all duration-150 {{ request()->routeIs('production.*') ? 'bg-[#0e3f32] text-white shadow-md shadow-[#0e3f32]/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                <span>Monitoring Produksi</span>
            </a>

            <!-- Pembayaran Kasir Link -->
            <a href="{{ route('payments.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold transition-all duration-150 {{ request()->routeIs('payments.*') ? 'bg-[#0e3f32] text-white shadow-md shadow-[#0e3f32]/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Data Pembayaran</span>
            </a>

            <div class="pt-3 px-2.5 pb-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Master Data</div>

            <!-- Pelanggan Link -->
            <a href="{{ route('customers.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold transition-all duration-150 {{ request()->routeIs('customers.*') ? 'bg-[#0e3f32] text-white shadow-md shadow-[#0e3f32]/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Pelanggan</span>
            </a>

            <!-- Katalog Produk & Jasa Link -->
            <a href="{{ route('products.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold transition-all duration-150 {{ request()->routeIs('products.*') ? 'bg-[#0e3f32] text-white shadow-md shadow-[#0e3f32]/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Katalog Produk & Jasa</span>
            </a>

            @if(auth()->check() && auth()->user()->isAdmin())
                <!-- Kategori Produk Link -->
                <a href="{{ route('categories.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold transition-all duration-150 {{ request()->routeIs('categories.*') ? 'bg-[#0e3f32] text-white shadow-md shadow-[#0e3f32]/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    <span>Kategori Produk</span>
                </a>

                <!-- Satuan Ukuran Link -->
                <a href="{{ route('units.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold transition-all duration-150 {{ request()->routeIs('units.*') ? 'bg-[#0e3f32] text-white shadow-md shadow-[#0e3f32]/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    <span>Satuan Ukuran</span>
                </a>

                <!-- Rekening & Metode Bayar Link -->
                <a href="{{ route('payment-methods.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold transition-all duration-150 {{ request()->routeIs('payment-methods.*') ? 'bg-[#0e3f32] text-white shadow-md shadow-[#0e3f32]/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span>Rekening & Pembayaran</span>
                </a>

                <!-- Laporan Bisnis Link -->
                <a href="{{ route('reports.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold transition-all duration-150 {{ request()->routeIs('reports.*') ? 'bg-[#0e3f32] text-white shadow-md shadow-[#0e3f32]/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Laporan Bisnis</span>
                </a>
            @endif

            @if(auth()->check() && auth()->user()->isAdmin())
                <div class="pt-3 px-2.5 pb-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Pengaturan Admin</div>

                <!-- Manajemen Pengguna Link -->
                <a href="{{ route('users.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold transition-all duration-150 {{ request()->routeIs('users.*') ? 'bg-[#0e3f32] text-white shadow-md shadow-[#0e3f32]/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Pengguna & Petugas</span>
                </a>

                <!-- Profil Usaha Link -->
                <a href="{{ route('settings.company') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-bold transition-all duration-150 {{ request()->routeIs('settings.*') ? 'bg-[#0e3f32] text-white shadow-md shadow-[#0e3f32]/25' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <svg class="w-5 h-5 shrink-0 opacity-85" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Profil Usaha</span>
                </a>
            @endif
        </nav>

        <!-- Current User Footer with Active Status Beacon -->
        <div class="p-3.5 border-t border-slate-100 bg-slate-50/70">
            <div class="flex items-center gap-2.5">
                <div class="relative">
                    <div class="w-8.5 h-8.5 rounded-xl bg-[#0e3f32] text-white font-black flex items-center justify-center text-xs shadow-sm shadow-[#0e3f32]/20">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-slate-800 truncate">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                    <p class="text-[10px] text-slate-500 capitalize font-medium">
                        {{ auth()->user()->isAdmin() ? 'Administrator' : 'Petugas Staf' }}
                    </p>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar dari Sistem" class="text-slate-400 hover:text-rose-600 p-1.5 rounded-lg hover:bg-rose-50 transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Bankio Style Clean Top Header -->
        <header class="h-14 bg-white border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-6 z-10 shrink-0">
            <div class="flex items-center gap-3">
                <button @click="mobileMenuOpen = true" class="lg:hidden p-1.5 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h2 class="text-xs sm:text-sm font-extrabold text-slate-900 tracking-tight">
                        Selamat Datang, {{ auth()->user()->name ?? 'Petugas' }}!
                    </h2>
                    <p class="text-[10px] text-slate-400 font-medium hidden sm:block">
                        Kelola pesanan transaksi & pantau alur produksi sablon secara real-time.
                    </p>
                </div>
            </div>

            <!-- Top Header Right: Search Pill & Action Icons (Bankio Style) -->
            <div class="flex items-center gap-2">
                <a href="{{ route('transactions.index') }}" 
                   class="hidden md:flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-500 hover:text-slate-800 text-xs font-medium rounded-full border border-slate-200/80 transition-colors w-52">
                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span class="truncate">Cari transaksi / data...</span>
                    <kbd class="ml-auto text-[9px] text-slate-400 bg-white px-1 py-0.5 rounded border border-slate-200 font-mono">⌘K</kbd>
                </a>

                <!-- Round Action Buttons (Bankio Style) -->
                <a href="{{ route('production.board') }}" title="Monitoring Produksi" class="w-8 h-8 rounded-full bg-slate-50 hover:bg-slate-100 border border-slate-200/80 flex items-center justify-center text-slate-600 hover:text-slate-900 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                </a>

                <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#0e3f32] hover:bg-[#124b3c] text-white font-bold text-xs rounded-full shadow-sm shadow-[#0e3f32]/20 transition-all active:scale-98">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Order Baru</span>
                </a>
                
                <!-- Avatar Pill -->
                <div class="w-8 h-8 rounded-full bg-[#0e3f32] text-white font-black flex items-center justify-center text-xs shadow-sm ring-2 ring-slate-100">
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                </div>
            </div>
        </header>

        <!-- Global Toast Notification Banner -->
        <div x-data="{ show: false, message: '', type: 'success' }"
             x-on:toast.window="message = $event.detail.message || $event.detail[0]?.message || 'Berhasil'; type = $event.detail.type || $event.detail[0]?.type || 'success'; show = true; setTimeout(() => show = false, 4000)"
             class="fixed bottom-6 right-6 z-50 pointer-events-none">
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                 :class="{
                     'bg-[#0e3f32] text-white border-emerald-400/40 shadow-emerald-900/20': type === 'success',
                     'bg-rose-900 text-white border-rose-500/50 shadow-rose-500/10': type === 'error',
                     'bg-amber-900 text-white border-amber-500/50 shadow-amber-500/10': type === 'warning'
                 }"
                 class="flex items-center gap-3 px-4 py-2.5 rounded-xl shadow-xl border text-xs font-bold pointer-events-auto"
                 style="display: none;">
                <span class="w-2 h-2 rounded-full" :class="{
                    'bg-emerald-400': type === 'success',
                    'bg-rose-400': type === 'error',
                    'bg-amber-400': type === 'warning'
                }"></span>
                <span x-text="message"></span>
            </div>
        </div>

        @if(session()->has('success'))
            <div class="bg-emerald-50 border-b border-emerald-200/80 px-4 sm:px-6 py-2 text-xs text-emerald-800 font-bold flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session()->has('error'))
            <div class="bg-rose-50 border-b border-rose-200/80 px-4 sm:px-6 py-2 text-xs text-rose-800 font-bold flex items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Dynamic Main Content with Bankio Clean Canvas -->
        <main class="flex-1 overflow-y-auto p-3 sm:p-4 lg:p-5 bg-[#f8fafc]">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
