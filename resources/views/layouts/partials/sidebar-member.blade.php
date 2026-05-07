<div id="sidebarBackdrop" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

<aside id="mainSidebar" class="w-72 bg-white h-full border-r border-gray-100 flex flex-col">
    <div class="p-8 pb-4">
        <div class="flex items-center gap-3">
            <img src="{{ asset('assets/img/brand/logo-macabae.png') }}" alt="Logo MacaBae" class="mx-auto">
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-4 py-4 space-y-8 custom-scrollbar">
        <div>
            <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Menu utama</p>
            <div class="space-y-1">
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-[#F3F7FB] text-[#4D9BE2] font-semibold">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm">Beranda</span>
                </a>
                </div>
        </div>

        <div>
            <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Peminjaman</p>
            <div class="space-y-1">
                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-500 hover:bg-gray-50 transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332 0.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 0.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332 0.477-4.5 1.253" />
                    </svg>
                    <span class="text-sm">Peminjaman Buku</span>
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