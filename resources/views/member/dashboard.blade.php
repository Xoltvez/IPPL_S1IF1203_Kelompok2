@extends('layouts.app')

@section('title', 'Beranda MacaBae')

@section('content')
<div class="w-full flex flex-col md:px-2 max-w-7xl mx-auto Box-border">

    <div class="mb-8 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
    <div>
        <h1 class="text-2xl font-bold text-[#2F3951] tracking-tight">Halo, {{ explode(' ', Auth::user()->name ?? 'Member')[0] }}! 👋</h1>
        <p class="text-gray-500 text-sm mt-1.5 font-medium">Mau baca apa hari ini?</p>
    </div>

    @if(!request('search'))
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-[#4D9BE2]/10 text-[#4D9BE2] rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Total Pinjam</p>
                <h3 class="text-xl font-bold text-[#2F3951]">{{ $stats['total_pinjam'] }} Buku</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Belum Kembali</p>
                <h3 class="text-xl font-bold text-[#2F3951]">{{ $stats['sedang_dipinjam'] }} Buku</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500">Kategori</p>
                <h3 class="text-xl font-bold text-[#2F3951]">{{ $stats['kategori_tersedia'] }} Jenis</h3>
            </div>
        </div>
    </div>
    @endif
        
        <div class="w-full bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <form action="{{ route('dashboard') }}" method="GET" class="w-full flex items-center gap-3">
                <div class="relative flex-1">                        
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, pengarang, atau ISBN..." class="w-full pl-12 pr-4 py-2.5 bg-[#F8FAFC]/60 border border-gray-100 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-[#2F3951] hover:bg-[#2F3951]/90 text-white rounded-xl font-semibold text-sm transition whitespace-nowrap">Cari Buku</button>
                @if(request('search'))
                    <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-rose-50 text-rose-500 hover:bg-rose-100 rounded-xl font-medium text-sm transition">Reset</a>
                @endif
            </form>
        </div>
    </div>

    @if(!request('search'))
    <div class="w-full bg-white rounded-2xl border border-gray-200 shadow p-5 md:p-5 mb-8">
        <h2 class="text-base font-bold text-[#2F3951] mb-4">Sedang kamu pinjam</h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @forelse($bukuDipinjam as $pinjam)
                <div class="relative group flex flex-col cursor-pointer">
                    <div class="aspect-[3/4] rounded-2xl overflow-hidden shadow-sm border border-gray-100 relative mb-3 bg-[#FCFCFC]">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-300 z-10 flex items-center justify-center">
                            <span class="opacity-0 group-hover:opacity-100 bg-white text-[#4D9BE2] text-[10px] font-extrabold px-4 py-2 rounded-xl shadow-lg transition-all transform translate-y-2 group-hover:translate-y-0 uppercase tracking-wider">Detail</span>
                        </div>

                        @if(isset($pinjam->tgl_kembali))
                        <div class="absolute top-2.5 left-2.5 z-20 bg-white/95 backdrop-blur text-rose-500 border border-rose-100 text-[9px] font-bold px-2.5 py-1.5 rounded-lg flex items-center shadow-sm">
                            <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($pinjam->tgl_kembali)->format('d M') }}
                        </div>
                        @endif

                        <img src="{{ $pinjam->cover_buku ? asset('storage/' . $pinjam->cover_buku) : 'https://placehold.co/300x400?text=' . urlencode($pinjam->judul) }}" 
                            alt="{{ $pinjam->judul }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <h3 class="text-xs font-bold text-[#2F3951] truncate" title="{{ $pinjam->judul }}">{{ $pinjam->judul }}</h3>
                    <p class="text-[10px] text-gray-400 mt-0.5 truncate">{{ $pinjam->pengarang }}</p>
                </div>
            @empty
                {{-- Tampilan Placeholder Kosong dengan gaya Dashed Border --}}
                <a href="#" class="aspect-[3/4] rounded-2xl border-2 border-dashed border-gray-200 bg-[#FCFCFC]/50 flex flex-col items-center justify-center text-gray-400 hover:border-[#4D9BE2] hover:text-[#4D9BE2] hover:bg-[#4D9BE2]/5 transition-all group cursor-pointer">
                    <div class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform border border-gray-50">
                        <svg class="w-5 h-5 text-[#4D9BE2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-widest">Pinjam Buku</span>
                </a>
            @endforelse
        </div>
    </div>
    @endif

    @if(!request('search'))
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest mr-2 text-[10px]">Kategori Populer:</span>
        @foreach($kategoriList as $kat)
            <a href="{{ route('dashboard', ['search' => $kat->nama_kategori]) }}" 
            class="px-5 py-2 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl text-xs font-bold transition-all shadow-sm transform hover:scale-105 active:scale-95">
                {{ $kat->nama_kategori }}
            </a>
        @endforeach
    </div>
    @endif

    <div class="w-full bg-white rounded-2xl border border-gray-200 shadow p-5">
        <h2 class="text-base font-bold text-[#2F3951] mb-4">
            {{ request('search') ? 'Hasil pencarian untuk "' . request('search') . '"' : 'Rekomendasi buku untukmu' }}
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @forelse($bukuPopuler as $buku)
            <div class="relative group flex flex-col cursor-pointer">
                <div class="aspect-[3/4] rounded-2xl overflow-hidden shadow-sm border border-gray-100 mb-3 relative bg-[#FCFCFC]">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-300 z-10"></div>
                    <img src="{{ $buku->cover_buku ? asset('storage/' . $buku->cover_buku) : 'https://placehold.co/300x400?text=' . urlencode($buku->judul) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <h3 class="text-xs font-bold text-[#2F3951] truncate">{{ $buku->judul }}</h3>
                <p class="text-[10px] text-gray-400 mt-0.5 truncate">{{ $buku->pengarang }}</p>
            </div>
            @empty
            <div class="col-span-full py-10 text-center text-gray-400">Buku tidak ditemukan.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection