<div class="space-y-6">
    <!-- Header with Action -->
    <div class="sm:flex sm:items-center sm:justify-between pb-4 border-b border-slate-200">
        <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Manajemen Pengguna & Petugas</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola akun staf produksi, hak akses peran (Admin / Petugas), dan status keaktifan.</p>
        </div>
        <div class="mt-4 sm:mt-0 flex gap-2">
            <button wire:click="openCreateModal" 
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0e3f32] hover:bg-[#124d3e] text-white font-bold text-sm rounded-2xl shadow-sm transition-all active:scale-98">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Pengguna</span>
            </button>
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="relative w-full sm:max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input wire:model.live.debounce.300ms="search" 
                   type="text" 
                   placeholder="Cari nama, username, atau email..." 
                   class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#0e3f32] focus:bg-white transition-all">
        </div>

        <div class="flex items-center gap-2 w-full sm:w-auto">
            <span class="text-xs text-slate-500 font-semibold hidden md:inline">Peran:</span>
            <select wire:model.live="roleFilter" class="w-full sm:w-auto px-4 py-2 bg-slate-50 border border-slate-200 rounded-2xl text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-[#0e3f32]">
                <option value="">Semua Peran</option>
                <option value="admin">Administrator</option>
                <option value="petugas">Petugas / Operator</option>
            </select>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-[#18222c] text-white text-[10px] font-bold uppercase tracking-wider">
                        <th class="py-2.5 px-3.5">Nama & Username</th>
                        <th class="py-2.5 px-3.5">Email</th>
                        <th class="py-2.5 px-3.5">Peran (Role)</th>
                        <th class="py-2.5 px-3.5 text-center">Status</th>
                        <th class="py-2.5 px-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/70 transition-colors {{ ! $user->is_active ? 'opacity-60 bg-slate-50/40' : '' }}">
                            <td class="py-2.5 px-3.5">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-[#0e3f32]/10 text-[#0e3f32] font-black flex items-center justify-center text-[10px] shrink-0 border border-[#0e3f32]/20">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 flex items-center gap-1.5 text-xs">
                                            <span>{{ $user->name }}</span>
                                            @if($user->id === auth()->id())
                                                <span class="text-[9px] bg-emerald-50 text-emerald-700 border border-emerald-200 px-1 py-0.2 rounded-full font-bold">Saya</span>
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-mono"><span>@</span>{{ $user->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-2.5 px-3.5 text-slate-600 font-medium">
                                {{ $user->email ?? '-' }}
                            </td>
                            <td class="py-2.5 px-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider
                                    {{ $user->isAdmin() ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="py-2.5 px-3.5 text-center">
                                <button type="button" 
                                        wire:click="toggleActive({{ $user->id }})"
                                        {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-bold transition-colors
                                            {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-slate-100 text-slate-500 border border-slate-200 hover:bg-slate-200' }}
                                            {{ $user->id === auth()->id() ? 'cursor-default' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                    <span>{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </button>
                            </td>
                            <td class="py-2.5 px-3.5 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button wire:click="openEditModal({{ $user->id }})" title="Edit Data" class="p-1 rounded text-slate-400 hover:text-amber-600 hover:bg-slate-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <button wire:click="confirmDelete({{ $user->id }})" title="Hapus Akun" class="p-1 rounded text-slate-400 hover:text-rose-600 hover:bg-slate-100 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <p class="text-base font-semibold text-slate-700">Tidak ada data pengguna ditemukan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
    </div>

    <!-- User Modal Form -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <!-- Overlay Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity" wire:click="$set('showModal', false)" aria-hidden="true"></div>

            <!-- Centered Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div class="relative z-10 w-full max-w-lg transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all border border-slate-100" wire:click.stop>
                    <form wire:submit="saveUser">
                        <div class="bg-white px-6 pt-6 pb-4">
                            <h3 class="text-lg font-black text-slate-900 mb-1">
                                {{ $userId ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
                            </h3>
                            <p class="text-xs text-slate-500 mb-5">Atur identitas akun, hak akses peran, serta kata sandi pengguna.</p>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                                    <input wire:model="name" type="text" required placeholder="Contoh: Budi Santoso" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                                    @error('name') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Username *</label>
                                        <input wire:model="username" type="text" required placeholder="budisantoso" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-mono focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                                        @error('username') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Peran (Role) *</label>
                                        <select wire:model="role" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                                            <option value="petugas">Petugas / Operator</option>
                                            <option value="admin">Administrator</option>
                                        </select>
                                        @error('role') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email (Opsional)</label>
                                    <input wire:model="email" type="email" placeholder="budi@example.com" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                                    @error('email') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Kata Sandi {{ $userId ? '(Kosongkan bila tidak ingin mengubah)' : '*' }}
                                    </label>
                                    <input wire:model="password" type="password" placeholder="••••••••" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-2xl text-sm focus:ring-2 focus:ring-[#0e3f32] focus:bg-white focus:outline-none">
                                    @error('password') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div class="pt-2">
                                    <label class="flex items-center gap-2.5 cursor-pointer">
                                        <input wire:model="is_active" type="checkbox" class="w-4 h-4 rounded border-slate-300 text-[#0e3f32] focus:ring-[#0e3f32]">
                                        <span class="text-xs font-bold text-slate-700">Akun berstatus Aktif (dapat masuk ke dalam sistem)</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50/80 px-6 py-4 flex items-center justify-end gap-3 border-t border-slate-100">
                            <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-sm font-bold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-200/60 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-[#0e3f32] hover:bg-[#124d3e] text-white text-sm font-bold rounded-2xl shadow-sm transition-all active:scale-98">
                                Simpan Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Delete Modal -->
    @if($confirmingDelete)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <!-- Overlay Backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 transition-opacity" wire:click="$set('confirmingDelete', false)" aria-hidden="true"></div>

            <!-- Centered Modal Container -->
            <div class="flex min-h-screen items-center justify-center p-4 text-center">
                <div class="relative z-10 w-full max-w-sm transform overflow-hidden rounded-3xl bg-white p-6 text-left shadow-2xl transition-all border border-slate-100" wire:click.stop>
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-base font-black text-slate-900 mb-1">Hapus Akun Pengguna</h3>
                    <p class="text-xs text-slate-500 mb-6">Apakah Anda yakin ingin menghapus akun ini? Pengguna tidak akan dapat lagi masuk ke sistem.</p>
                    <div class="flex items-center justify-end gap-2">
                        <button type="button" wire:click="$set('confirmingDelete', false)" class="px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100 transition-colors">Batal</button>
                        <button type="button" wire:click="deleteUser" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition-colors">Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
