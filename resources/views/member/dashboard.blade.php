@extends('layouts.app')

@section('title', 'Beranda MacaBae')

@section('content')
<div class="w-full flex flex-col Box-border">

    <!-- Search & Filter Bar (Mockup Style) -->
    <div class="mb-6 flex items-center gap-4">
        <!-- Search Input with Left Icon & Spacious Padding -->
        <form action="{{ route('member.katalog') }}" method="GET" class="flex-1 flex items-center gap-4 m-0">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Cari buku yang kamu mau" 
                       class="w-full pl-12 pr-4 py-3 bg-[#F8FAFC] dark:bg-slate-800 border border-gray-200 dark:border-slate-700 focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2] rounded-2xl text-sm transition-all outline-none dark:text-slate-100">
            </div>

            <!-- Search Button -->
            <button type="submit" class="px-6 py-3 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-2xl shadow-sm text-sm font-bold transition-all transform active:scale-95 flex items-center justify-center whitespace-nowrap">
                Cari
            </button>
        </form>
    </div>

    <!-- Blue Promo Banner (Text-Only Style) -->
    <div class="relative overflow-hidden bg-gradient-to-r from-[#4D9BE2] via-[#5fa3e7] to-[#7cbdf2] rounded-3xl p-8 md:p-10 mb-8 flex flex-col justify-center text-white shadow-sm border border-[#4d9be2]/20 min-h-[160px]">
        <!-- Decorative background waves -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                <path d="M0,50 Q25,70 50,50 T100,50 L100,100 L0,100 Z" fill="white"></path>
            </svg>
        </div>
        
        <!-- Text content -->
        <div class="z-10 flex flex-col gap-3 text-left max-w-2xl md:max-w-3xl">
            <!-- White MacaBae Brand Logo -->
            <div class="flex items-center gap-2">
                <img src="{{ asset('assets/img/brand/logo-macabae-putih.png') }}" alt="MacaBae Logo" class="h-6 object-contain">
            </div>
            <h2 class="text-xl md:text-2xl lg:text-3xl font-black leading-tight tracking-tight mt-1">
                Nikmati Peminjaman Buku Digital MacaBae, Mudah dan Cepat!
            </h2>
            <p class="text-xs md:text-sm text-white/90 font-medium">
                Mudah, Cepat, dan Tanpa Ribet
            </p>
        </div>
    </div>

    <!-- Member Statistics Grid (Below Banner) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card 1: Sedang Dipinjam -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-blue-50 dark:bg-blue-950/40 text-blue-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-[#4D9BE2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332 0.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 0.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332 0.477-4.5 1.253" />
                </svg>
            </div>
            <div class="text-left">
                <p class="text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-wider">Sedang Dipinjam</p>
                <h3 class="text-lg font-black text-[#2F3951] dark:text-slate-100 mt-0.5">{{ $stats['sedang_dipinjam'] }} Buku</h3>
            </div>
        </div>

        <!-- Card 2: Buku Tersimpan -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-rose-50 dark:bg-rose-950/40 text-rose-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </div>
            <div class="text-left">
                <p class="text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-wider">Buku Tersimpan</p>
                <h3 class="text-lg font-black text-[#2F3951] dark:text-slate-100 mt-0.5">{{ $stats['total_tersimpan'] }} Buku</h3>
            </div>
        </div>

        <!-- Card 3: Total Peminjaman -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="text-left">
                <p class="text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-wider">Riwayat Pinjam</p>
                <h3 class="text-lg font-black text-[#2F3951] dark:text-slate-100 mt-0.5">{{ $stats['total_pinjam'] }} Buku</h3>
            </div>
        </div>

        <!-- Card 4: Total Denda -->
        <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-sm flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 bg-amber-50 dark:bg-amber-950/40 text-amber-500 rounded-2xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="text-left">
                <p class="text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-wider">Total Denda</p>
                <h3 class="text-lg font-black text-[#2F3951] dark:text-slate-100 mt-0.5">Rp {{ number_format($stats['total_denda'], 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Section: Buku yang sedang dipinjam -->
    <div class="w-full bg-white dark:bg-slate-900 rounded-3xl border border-gray-200 dark:border-slate-800 shadow p-6 mb-8">
        <h2 class="text-base font-bold text-[#2F3951] dark:text-slate-100 mb-5 text-left">Buku yang sedang dipinjam</h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            {{-- Display actual borrowed books --}}
            @foreach($bukuDipinjam as $pinjam)
                <div class="relative group flex flex-col">
                    <a href="{{ route('buku.show', $pinjam->id) }}" class="relative block w-full aspect-[3/4] rounded-2xl overflow-hidden shadow-sm border border-gray-100 dark:border-slate-800 bg-[#FCFCFC] dark:bg-slate-800">
                        
                        <!-- Calendar date badge matching mockup -->
                        @if(isset($pinjam->tanggal_kembali))
                            <div class="absolute top-3 left-3 z-20 bg-[#4D9BE2] text-white text-[9px] font-bold px-2.5 py-1 rounded-lg flex items-center gap-1.5 shadow-sm">
                                <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ \Carbon\Carbon::parse($pinjam->tanggal_kembali)->format('d M Y') }}
                            </div>
                        @endif

                        <img src="{{ $pinjam->cover_buku ? asset('storage/' . $pinjam->cover_buku) : 'https://placehold.co/300x400?text=' . urlencode($pinjam->judul) }}" 
                             alt="{{ $pinjam->judul }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </a>
                </div>
            @endforeach

            {{-- Fill empty slots up to 5 with mockup dashed placeholder --}}
            @for($i = count($bukuDipinjam); $i < 5; $i++)
                <a href="{{ route('member.katalog') }}" class="aspect-[3/4] rounded-2xl border-2 border-dashed border-gray-200 dark:border-slate-800 bg-[#FCFCFC]/50 dark:bg-slate-800/50 flex flex-col items-center justify-center text-gray-400 hover:border-[#4D9BE2] hover:text-[#4D9BE2] hover:bg-[#4D9BE2]/5 transition-all group cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-slate-900 shadow-sm flex items-center justify-center mb-2 group-hover:scale-110 transition-transform border border-gray-100 dark:border-slate-800 text-[#4D9BE2]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <span class="text-[9px] font-bold text-gray-500 dark:text-slate-400">Pinjam buku</span>
                </a>
            @endfor
        </div>
    </div>

    <!-- Section: Buku populer -->
    <div class="w-full bg-white dark:bg-slate-900 rounded-3xl border border-gray-200 dark:border-slate-800 shadow p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-base font-bold text-[#2F3951] dark:text-slate-100">Buku populer</h2>
            <!-- Lihat semua button matching mockup style -->
            <a href="{{ route('member.katalog') }}" class="px-4 py-2 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl text-xs font-bold flex items-center gap-1.5 transition shadow-sm">
                Lihat semua
                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @forelse($bukuPopuler as $buku)
                <div class="relative flex flex-col">
                    <a href="{{ route('buku.show', $buku->id) }}" class="relative block w-full aspect-[3/4] rounded-2xl overflow-hidden shadow-sm border border-gray-100 dark:border-slate-800 bg-[#FCFCFC] dark:bg-slate-800 group">
                        
                        <!-- Hover Overlay with Title, Author & Star Ratings (Premium Polish) -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[#2F3951]/95 via-[#2F3951]/55 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 flex flex-col justify-end p-4 text-left">
                            <p class="text-white font-bold text-xs line-clamp-2">{{ $buku->judul }}</p>
                            <p class="text-gray-300 text-[10px] mt-1 truncate">{{ $buku->pengarang }}</p>
                            
                            <!-- Star rating display -->
                            <div class="flex items-center gap-0.5 mt-2 text-amber-400">
                                @for($j = 0; $j < 5; $j++)
                                    <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        
                        <!-- Availability Status Badge -->
                        @if($buku->stok > 0 && $buku->status == 'aktif')
                            <div class="absolute top-3 right-3 z-20 bg-emerald-500 text-white text-[8px] font-extrabold px-2 py-0.5 rounded uppercase tracking-wider shadow-sm">
                                Tersedia
                            </div>
                        @else
                            <div class="absolute top-3 right-3 z-20 bg-rose-500 text-white text-[8px] font-extrabold px-2 py-0.5 rounded uppercase tracking-wider shadow-sm">
                                Terpinjam
                            </div>
                        @endif

                        <img src="{{ $buku->cover_buku ? asset('storage/' . $buku->cover_buku) : 'https://placehold.co/300x400?text=' . urlencode($buku->judul) }}" 
                             alt="{{ $buku->judul }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </a>
                </div>
            @empty
                <div class="col-span-full py-10 text-center text-gray-400 dark:text-slate-400">Buku tidak ditemukan.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection