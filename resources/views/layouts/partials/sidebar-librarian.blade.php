<aside id="mainSidebar" class="w-72 bg-white h-full border-r border-gray-100 flex flex-col">
    <div class="p-8 pb-4">
        <div class="flex items-center gap-3">
            <img src="{{ asset('assets/img/brand/logo-macabae.png') }}" alt="Logo MacaBae" class="mx-auto">
        </div>
    </div>

    <div class="flex-1 overflow-y-auto px-4 py-4 space-y-8 custom-scrollbar">
        {{-- MENU UTAMA: DASHBOARD SUMMARY --}}
        <div>
            <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Menu Utama</p>
            <div class="space-y-1">
                <a href="{{ route('dashboard') }}" 
                class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('dashboard') ? 'bg-[#F3F7FB] text-[#4D9BE2] font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm">Dashboard</span>
                </a>
            </div>
        </div>

        {{-- MANAJEMEN BUKU --}}
        <div>
            <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Manajemen Buku</p>
            <div class="space-y-1">
                {{-- Sesuaikan nama rute di 'request()->routeIs' dengan route:list web.php kamu --}}
                <a href="{{ route('pustakawan.buku.index') }}" 
                class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('pustakawan.buku*') ? 'bg-[#F3F7FB] text-[#4D9BE2] font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332 0.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 0.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332 0.477-4.5 1.253" />
                    </svg>
                    <span class="text-sm">Data Buku</span>
                </a>
                <a href="{{ route('pustakawan.kategori.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('pustakawan.kategori*') ? 'bg-[#F3F7FB] text-[#4D9BE2] font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="text-sm">Kategori Buku</span>
                </a>
            </div>
        </div>

        {{-- SIRKULASI --}}
        <div>
            <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Transaksi</p>
            <div class="space-y-1">
                <a href="{{ route('pustakawan.peminjaman.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('pustakawan.peminjaman*') ? 'bg-[#F3F7FB] text-[#4D9BE2] font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span class="text-sm">Peminjaman</span>
                </a>
                <a href="{{ route('pustakawan.pengembalian.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('pustakawan.pengembalian*') ? 'bg-[#F3F7FB] text-[#4D9BE2] font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">Pengembalian</span>
                </a>
                <a href="{{ route('pustakawan.denda.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('pustakawan.denda*') ? 'bg-[#F3F7FB] text-[#4D9BE2] font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm">Denda</span>
                </a>
                <a href="{{ route('pustakawan.kunjungan.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('pustakawan.kunjungan*') ? 'bg-[#F3F7FB] text-[#4D9BE2] font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-sm">Presensi Kunjungan</span>
                </a>
            </div>
        </div>

        {{-- ANGGOTA --}}
        <div>
            <p class="px-4 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">Manajemen User</p>
            <div class="space-y-1">
                <a href="{{ route('pustakawan.member.index') }}" 
                class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all {{ request()->routeIs('pustakawan.member*') ? 'bg-[#F3F7FB] text-[#4D9BE2] font-semibold' : 'text-gray-500 hover:bg-gray-50' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-sm">Data Member</span>
                </a>
            </div>
        </div>
    </div>
</aside>