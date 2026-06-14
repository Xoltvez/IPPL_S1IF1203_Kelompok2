<div id="sidebarBackdrop" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

<aside id="mainSidebar" class="w-72 bg-white dark:bg-slate-900 h-full border-r border-gray-100 dark:border-slate-800 flex flex-col transition-colors duration-200">
    <div class="p-8 pb-4">
        <div class="flex items-center gap-3">
            <img src="{{ asset('assets/img/brand/logo-macabae.png') }}" alt="Logo MacaBae" class="mx-auto dark:hidden">
            <img src="{{ asset('assets/img/brand/logo-macabae-putih.png') }}" alt="Logo MacaBae" class="mx-auto hidden dark:block">
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-4 py-4 space-y-8 custom-scrollbar">
        
        {{-- MENU UTAMA --}}
        <div>
            <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">{{ __('Menu utama') }}</p>
            <div class="space-y-1">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-[#F3F7FB] dark:bg-slate-800 text-[#4D9BE2] font-semibold' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm">{{ __('Dashboard') }}</span>
                </a>
                
                <a href="{{ route('member.katalog') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('member.katalog*') ? 'bg-[#F3F7FB] dark:bg-slate-800 text-[#4D9BE2] font-semibold' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332 0.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 0.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332 0.477-4.5 1.253" />
                    </svg>
                    <span class="text-sm">{{ __('Katalog') }}</span>
                </a>
 
                <a href="{{ route('member.tersimpan') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('member.tersimpan*') ? 'bg-[#F3F7FB] dark:bg-slate-800 text-[#4D9BE2] font-semibold' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                    <span class="text-sm">{{ __('Tersimpan') }}</span>
                </a>
            </div>
        </div>
 
        {{-- PEMINJAMAN --}}
        <div>
            <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">{{ __('Peminjaman') }}</p>
            <div class="space-y-1">
                <a href="{{ route('member.peminjaman.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('member.peminjaman*') ? 'bg-[#F3F7FB] dark:bg-slate-800 text-[#4D9BE2] font-semibold' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332 0.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 0.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332 0.477-4.5 1.253" />
                    </svg>
                    <span class="text-sm">{{ __('Peminjaman buku') }}</span>
                </a>
                
                <a href="{{ route('member.reservasi.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('member.reservasi*') ? 'bg-[#F3F7FB] dark:bg-slate-800 text-[#4D9BE2] font-semibold' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span class="text-sm">{{ __('Reservasi') }}</span>
                </a>
 
                <a href="{{ route('member.riwayat.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('member.riwayat*') ? 'bg-[#F3F7FB] dark:bg-slate-800 text-[#4D9BE2] font-semibold' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">{{ __('Riwayat') }}</span>
                </a>
            </div>
        </div>
 
        {{-- LANJUTAN --}}
        <div>
            <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">{{ __('Lanjutan') }}</p>
            <div class="space-y-1">
                <a href="{{ route('member.pengaturan.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('member.pengaturan*') ? 'bg-[#F3F7FB] dark:bg-slate-800 text-[#4D9BE2] font-semibold' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm">{{ __('Pengaturan') }}</span>
                </a>
                
                <a href="{{ route('member.panduan.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('member.panduan*') ? 'bg-[#F3F7FB] dark:bg-slate-800 text-[#4D9BE2] font-semibold' : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span class="text-sm">{{ __('Panduan') }}</span>
                </a>
            </div>
        </div>
    </div>
</aside>

<style>
    /* Styling tipis untuk scrollbar agar rapi */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #D1D5DB; }
</style>