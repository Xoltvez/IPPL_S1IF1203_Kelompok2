@extends('layouts.app')

@section('title', 'Manajemen Denda Keterlambatan')

@section('content')
<div class="block w-full text-left clear-both" x-data="{ 
    showPaymentModal: false, 
    fineId: null, 
    memberName: '', 
    fineAmount: 0,
    paymentMethod: 'tunai',
    openPayment(id, name, amount) {
        this.fineId = id;
        this.memberName = name;
        this.fineAmount = amount;
        this.paymentMethod = 'tunai';
        this.showPaymentModal = true;
    }
}">

    {{-- STATS / HEADER CARD --}}
    <div class="w-full bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-100 dark:border-slate-800 shadow mb-6 transition-colors duration-200">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-[#2F3951] dark:text-slate-100 tracking-tight">Manajemen Denda</h2>
                <p class="text-gray-400 dark:text-slate-400 text-xs mt-1">Kelola denda keterlambatan pengembalian buku dan catat metode pembayaran.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-900/40">
                    <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                    {{ $countUnpaid }} Belum Lunas
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-900/40">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    {{ $countOverdue }} Terlambat Berjalan
                </span>
            </div>
        </div>
    </div>

    {{-- SEARCH BAR --}}
    <div class="w-full bg-white dark:bg-slate-900 p-4 rounded-2xl border border-gray-100 dark:border-slate-800 shadow mb-6 transition-colors duration-200">
        <form action="{{ route(auth()->user()->role . '.denda.index') }}" method="GET" class="w-full flex items-center gap-3">
            <input type="hidden" name="tab" value="{{ $tab }}">
            
            <div class="relative flex-1 flex items-center">
                <span class="absolute left-4 text-gray-400 pointer-events-none flex items-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" 
                    placeholder="Cari nama member, email, atau judul buku..." 
                    class="w-full pl-12 pr-4 py-2.5 bg-[#F8FAFC]/60 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 focus:border-[#4D9BE2]/50 focus:bg-white dark:focus:bg-slate-900 rounded-xl text-sm text-[#2F3951] dark:text-slate-100 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 dark:focus:ring-[#4D9BE2]/10 transition-all">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2.5 bg-[#2F3951] hover:bg-[#2F3951]/90 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-100 text-white rounded-xl font-semibold text-sm transition shadow-sm cursor-pointer">
                    Cari
                </button>

                @if($search)
                    <a href="{{ route(auth()->user()->role . '.denda.index', ['tab' => $tab]) }}" 
                       class="px-5 py-2.5 bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 rounded-xl font-semibold text-sm transition flex items-center gap-2 cursor-pointer">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- FILTER PILLS / TABS --}}
    <div class="flex items-center gap-3 mb-6 overflow-x-auto pb-2 no-scrollbar">
        <!-- Belum Lunas Tab -->
        <a href="{{ route(auth()->user()->role . '.denda.index', ['tab' => 'belum_lunas', 'search' => $search]) }}"
           class="px-5 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ $tab === 'belum_lunas' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'bg-white dark:bg-slate-900 text-gray-500 dark:text-slate-400 border border-gray-100 dark:border-slate-800 hover:bg-[#F3F7FB] dark:hover:bg-slate-800/50' }}">
            Belum Lunas ({{ $countUnpaid }})
        </a>
        
        <!-- Terlambat Berjalan Tab -->
        <a href="{{ route(auth()->user()->role . '.denda.index', ['tab' => 'terlambat', 'search' => $search]) }}"
           class="px-5 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ $tab === 'terlambat' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'bg-white dark:bg-slate-900 text-gray-500 dark:text-slate-400 border border-gray-100 dark:border-slate-800 hover:bg-[#F3F7FB] dark:hover:bg-slate-800/50' }}">
            Terlambat Berjalan ({{ $countOverdue }})
        </a>
        
        <!-- Riwayat Pembayaran Tab -->
        <a href="{{ route(auth()->user()->role . '.denda.index', ['tab' => 'riwayat', 'search' => $search]) }}"
           class="px-5 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ $tab === 'riwayat' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'bg-white dark:bg-slate-900 text-gray-500 dark:text-slate-400 border border-gray-100 dark:border-slate-800 hover:bg-[#F3F7FB] dark:hover:bg-slate-800/50' }}">
            Riwayat Pembayaran ({{ $countPaid }})
        </a>
    </div>

    {{-- CONTENT --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto custom-scrollbar">
            
            {{-- TAB 1: BELUM LUNAS --}}
            @if($tab === 'belum_lunas')
                <table class="w-full text-left border-collapse min-w-[1050px]">
                    <thead>
                        <tr class="bg-[#F8FAFC] dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[5%]">No</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest w-[25%]">Anggota / Member</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest w-[25%]">Buku</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[12%]">Tanggal Kembali</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[10%]">Jumlah Denda</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[12%]">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[11%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-800 text-sm text-[#2F3951] dark:text-slate-300">
                        @forelse($unpaidFines as $denda)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 text-center font-medium text-gray-400 dark:text-slate-500">
                                    {{ $loop->iteration + ($unpaidFines->firstItem() - 1) }}
                                </td>
                                
                                {{-- MEMBER --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-[#4D9BE2]/10 flex items-center justify-center text-[#4D9BE2] font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($denda->peminjaman->user->name ?? 'M', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-[#2F3951] dark:text-slate-200 truncate">{{ $denda->peminjaman->user->name ?? 'Tamu/Deleted' }}</p>
                                            <p class="text-[11px] text-gray-400 dark:text-slate-400 truncate">{{ $denda->peminjaman->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- BUKU --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-12 rounded overflow-hidden shadow-sm border border-gray-100 dark:border-slate-800 flex-shrink-0 bg-gray-50 dark:bg-slate-800 flex items-center justify-center">
                                            <img src="{{ $denda->peminjaman->buku->cover_buku ? asset('storage/' . $denda->peminjaman->buku->cover_buku) : 'https://placehold.co/120x160?text=' . urlencode($denda->peminjaman->buku->judul ?? 'Buku') }}" 
                                                 alt="{{ $denda->peminjaman->buku->judul ?? 'Cover' }}" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-bold text-[#2F3951] dark:text-slate-200 truncate" title="{{ $denda->peminjaman->buku->judul ?? '-' }}">{{ $denda->peminjaman->buku->judul ?? '-' }}</p>
                                            <p class="text-[11px] text-gray-400 dark:text-slate-400 truncate">Penulis: {{ $denda->peminjaman->buku->pengarang ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- TANGGAL KEMBALI --}}
                                <td class="px-6 py-4 text-center text-sm text-gray-500 dark:text-slate-400 font-medium">
                                    {{ \Carbon\Carbon::parse($denda->peminjaman->updated_at)->format('d M Y') }}
                                </td>

                                {{-- JUMLAH DENDA --}}
                                <td class="px-6 py-4 text-center font-bold text-rose-600 dark:text-rose-400">
                                    Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}
                                </td>

                                {{-- STATUS --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold text-rose-600 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/40 whitespace-nowrap animate-pulse">
                                        Belum Lunas
                                    </span>
                                </td>

                                {{-- AKSI --}}
                                <td class="px-6 py-4 text-center">
                                    <button type="button" 
                                            @click="openPayment({{ $denda->id }}, '{{ addslashes($denda->peminjaman->user->name ?? 'Member') }}', {{ $denda->jumlah_denda }})"
                                            class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition shadow-sm hover:shadow cursor-pointer">
                                        Bayar Denda
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-gray-400 dark:text-slate-400 text-xs font-medium">Tidak ada denda yang belum dilunasi.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- PAGINATION --}}
                @if($unpaidFines && $unpaidFines->hasPages())
                    <div class="p-4 border-t border-gray-50 dark:border-slate-800 bg-[#F8FAFC]/40 dark:bg-slate-900/20 w-full clear-both">
                        {{ $unpaidFines->links() }}
                    </div>
                @endif
            @endif

            {{-- TAB 2: TERLAMBAT BERJALAN --}}
            @if($tab === 'terlambat')
                <table class="w-full text-left border-collapse min-w-[1050px]">
                    <thead>
                        <tr class="bg-[#F8FAFC] dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[5%]">No</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest w-[25%]">Anggota / Member</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest w-[25%]">Buku</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[12%]">Tanggal Pinjam</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[12%]">Jatuh Tempo</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[12%]">Akumulasi Denda</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[11%]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-800 text-sm text-[#2F3951] dark:text-slate-300">
                        @forelse($overdueLoans as $loan)
                            @php
                                $dueDate = \Carbon\Carbon::parse($loan->tanggal_kembali)->startOfDay();
                                $today = \Carbon\Carbon::today();
                                $daysLate = $today->diffInDays($dueDate, false); // negative means past due
                                $absDays = abs($daysLate);
                                $currentFine = $absDays * 1000;
                            @endphp
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 text-center font-medium text-gray-400 dark:text-slate-500">
                                    {{ $loop->iteration + ($overdueLoans->firstItem() - 1) }}
                                </td>
                                
                                {{-- MEMBER --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-[#4D9BE2]/10 flex items-center justify-center text-[#4D9BE2] font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($loan->user->name ?? 'M', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-[#2F3951] dark:text-slate-200 truncate">{{ $loan->user->name ?? 'Tamu/Deleted' }}</p>
                                            <p class="text-[11px] text-gray-400 dark:text-slate-400 truncate">{{ $loan->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- BUKU --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-12 rounded overflow-hidden shadow-sm border border-gray-100 dark:border-slate-800 flex-shrink-0 bg-gray-50 dark:bg-slate-800 flex items-center justify-center">
                                            <img src="{{ $loan->buku->cover_buku ? asset('storage/' . $loan->buku->cover_buku) : 'https://placehold.co/120x160?text=' . urlencode($loan->buku->judul ?? 'Buku') }}" 
                                                 alt="{{ $loan->buku->judul ?? 'Cover' }}" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-bold text-[#2F3951] dark:text-slate-200 truncate" title="{{ $loan->buku->judul ?? '-' }}">{{ $loan->buku->judul ?? '-' }}</p>
                                            <p class="text-[11px] text-gray-400 dark:text-slate-400 truncate">Penulis: {{ $loan->buku->pengarang ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- TANGGAL PINJAM --}}
                                <td class="px-6 py-4 text-center text-sm text-gray-500 dark:text-slate-400 font-medium">
                                    {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y') }}
                                </td>

                                {{-- JATUH TEMPO --}}
                                <td class="px-6 py-4 text-center text-sm font-semibold text-rose-600 dark:text-rose-400">
                                    {{ $dueDate->format('d M Y') }}
                                    <span class="block text-[10px] text-rose-500 font-normal">Terlambat {{ $absDays }} Hari</span>
                                </td>

                                {{-- AKUMULASI DENDA --}}
                                <td class="px-6 py-4 text-center font-bold text-rose-600 dark:text-rose-400">
                                    Rp {{ number_format($currentFine, 0, ',', '.') }}
                                </td>

                                {{-- AKSI --}}
                                <td class="px-6 py-4 text-center">
                                    <form action="{{ route('member.peminjaman.kembali', $loan->id) }}" method="POST" onsubmit="return confirm('Apakah buku ini akan dikembalikan sekarang? Denda otomatis akan dibuat.')">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl text-xs font-bold transition shadow-sm cursor-pointer whitespace-nowrap">
                                            Kembalikan Buku
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        <p class="text-gray-400 dark:text-slate-400 text-xs font-medium">Tidak ada member dengan keterlambatan berjalan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- PAGINATION --}}
                @if($overdueLoans && $overdueLoans->hasPages())
                    <div class="p-4 border-t border-gray-50 dark:border-slate-800 bg-[#F8FAFC]/40 dark:bg-slate-900/20 w-full clear-both">
                        {{ $overdueLoans->links() }}
                    </div>
                @endif
            @endif

            {{-- TAB 3: RIWAYAT PEMBAYARAN --}}
            @if($tab === 'riwayat')
                <table class="w-full text-left border-collapse min-w-[1050px]">
                    <thead>
                        <tr class="bg-[#F8FAFC] dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[5%]">No</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest w-[25%]">Anggota / Member</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest w-[25%]">Buku</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[12%]">Tanggal Bayar</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[12%]">Jumlah Denda</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[12%]">Metode Pembayaran</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[10%]">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-800 text-sm text-[#2F3951] dark:text-slate-300">
                        @forelse($paidFines as $denda)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                                <td class="px-6 py-4 text-center font-medium text-gray-400 dark:text-slate-500">
                                    {{ $loop->iteration + ($paidFines->firstItem() - 1) }}
                                </td>
                                
                                {{-- MEMBER --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-[#4D9BE2]/10 flex items-center justify-center text-[#4D9BE2] font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($denda->peminjaman->user->name ?? 'M', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-[#2F3951] dark:text-slate-200 truncate">{{ $denda->peminjaman->user->name ?? 'Tamu/Deleted' }}</p>
                                            <p class="text-[11px] text-gray-400 dark:text-slate-400 truncate">{{ $denda->peminjaman->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- BUKU --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-12 rounded overflow-hidden shadow-sm border border-gray-100 dark:border-slate-800 flex-shrink-0 bg-gray-50 dark:bg-slate-800 flex items-center justify-center">
                                            <img src="{{ $denda->peminjaman->buku->cover_buku ? asset('storage/' . $denda->peminjaman->buku->cover_buku) : 'https://placehold.co/120x160?text=' . urlencode($denda->peminjaman->buku->judul ?? 'Buku') }}" 
                                                 alt="{{ $denda->peminjaman->buku->judul ?? 'Cover' }}" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-bold text-[#2F3951] dark:text-slate-200 truncate" title="{{ $denda->peminjaman->buku->judul ?? '-' }}">{{ $denda->peminjaman->buku->judul ?? '-' }}</p>
                                            <p class="text-[11px] text-gray-400 dark:text-slate-400 truncate">Penulis: {{ $denda->peminjaman->buku->pengarang ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                {{-- TANGGAL BAYAR --}}
                                <td class="px-6 py-4 text-center text-sm text-gray-500 dark:text-slate-400 font-medium">
                                    {{ \Carbon\Carbon::parse($denda->updated_at)->format('d M Y H:i') }}
                                </td>

                                {{-- JUMLAH DENDA --}}
                                <td class="px-6 py-4 text-center font-bold text-gray-700 dark:text-slate-300">
                                    Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}
                                </td>

                                {{-- METODE PEMBAYARAN --}}
                                <td class="px-6 py-4 text-center">
                                    @if($denda->metode_pembayaran === 'tunai')
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/40 uppercase whitespace-nowrap">
                                            Tunai
                                        </span>
                                    @elseif($denda->metode_pembayaran === 'qris')
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold text-blue-600 bg-blue-50 dark:bg-blue-950/20 border border-blue-100 dark:border-blue-900/40 uppercase whitespace-nowrap">
                                            QRIS
                                        </span>
                                    @elseif($denda->metode_pembayaran === 'transfer_bank')
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold text-purple-600 bg-purple-50 dark:bg-purple-950/20 border border-purple-100 dark:border-purple-900/40 uppercase whitespace-nowrap">
                                            Transfer Bank
                                        </span>
                                    @else
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold text-gray-500 bg-gray-50 dark:bg-gray-800 border border-gray-100 dark:border-gray-700 uppercase whitespace-nowrap">
                                            Lainnya
                                        </span>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/40 uppercase whitespace-nowrap">
                                        Lunas
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-gray-400 dark:text-slate-400 text-xs font-medium">Belum ada riwayat pembayaran denda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- PAGINATION --}}
                @if($paidFines && $paidFines->hasPages())
                    <div class="p-4 border-t border-gray-50 dark:border-slate-800 bg-[#F8FAFC]/40 dark:bg-slate-900/20 w-full clear-both">
                        {{ $paidFines->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>

    {{-- MODAL PEMBAYARAN DENDA --}}
    <div class="fixed inset-0 z-50 overflow-y-auto" 
         x-show="showPaymentModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background Overlay --}}
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="showPaymentModal = false"></div>

            {{-- Center Modal Alignment Trick --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            {{-- Modal Box --}}
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-slate-900 rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 dark:border-slate-800"
                 x-show="showPaymentModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                {{-- Form action is generated dynamically via javascript form submission --}}
                <form :action="'{{ route(auth()->user()->role . '.denda.bayar', ['id' => '__ID__']) }}'.replace('__ID__', fineId)" method="POST">
                    @csrf
                    
                    {{-- Header --}}
                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center">
                        <h3 class="text-md font-bold text-[#2F3951] dark:text-slate-100">Proses Pelunasan Denda</h3>
                        <button type="button" @click="showPaymentModal = false" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="p-6 space-y-6">
                        
                        {{-- Fine Summary --}}
                        <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/40">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs text-rose-700 dark:text-rose-400 font-semibold uppercase tracking-wider">Nama Anggota</span>
                                <span class="text-sm text-[#2F3951] dark:text-slate-200 font-bold" x-text="memberName"></span>
                            </div>
                            <div class="flex justify-between items-center border-t border-rose-100 dark:border-rose-900/30 pt-2">
                                <span class="text-xs text-rose-700 dark:text-rose-400 font-semibold uppercase tracking-wider">Total Tagihan</span>
                                <span class="text-lg text-rose-600 dark:text-rose-400 font-extrabold" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(fineAmount)"></span>
                            </div>
                        </div>

                        {{-- Payment Method Selection --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-3">Pilih Metode Pembayaran</label>
                            
                            <div class="grid grid-cols-1 gap-3">
                                
                                {{-- Tunai --}}
                                <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all"
                                       :class="paymentMethod === 'tunai' ? 'border-[#4D9BE2] bg-[#4D9BE2]/5 dark:bg-[#4D9BE2]/10' : 'border-gray-200 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800/50'">
                                    <input type="radio" name="metode_pembayaran" value="tunai" x-model="paymentMethod" class="sr-only">
                                    <span class="w-5 h-5 rounded-full border flex items-center justify-center mr-3"
                                          :class="paymentMethod === 'tunai' ? 'border-[#4D9BE2]' : 'border-gray-300 dark:border-slate-700'">
                                        <span class="w-2.5 h-2.5 rounded-full bg-[#4D9BE2]" x-show="paymentMethod === 'tunai'"></span>
                                    </span>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-[#2F3951] dark:text-slate-200">Tunai / Cash</p>
                                            <p class="text-[11px] text-gray-400 dark:text-slate-400">Pembayaran tunai langsung ke kasir/perpustakaan.</p>
                                        </div>
                                    </div>
                                </label>

                                {{-- QRIS --}}
                                <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all"
                                       :class="paymentMethod === 'qris' ? 'border-[#4D9BE2] bg-[#4D9BE2]/5 dark:bg-[#4D9BE2]/10' : 'border-gray-200 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800/50'">
                                    <input type="radio" name="metode_pembayaran" value="qris" x-model="paymentMethod" class="sr-only">
                                    <span class="w-5 h-5 rounded-full border flex items-center justify-center mr-3"
                                          :class="paymentMethod === 'qris' ? 'border-[#4D9BE2]' : 'border-gray-300 dark:border-slate-700'">
                                        <span class="w-2.5 h-2.5 rounded-full bg-[#4D9BE2]" x-show="paymentMethod === 'qris'"></span>
                                    </span>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-[#2F3951] dark:text-slate-200">QRIS / E-Wallet</p>
                                            <p class="text-[11px] text-gray-400 dark:text-slate-400">Scan kode QR dinamis melalui GoPay, OVO, Dana, dll.</p>
                                        </div>
                                    </div>
                                </label>

                                {{-- Transfer Bank --}}
                                <label class="relative flex items-center p-4 rounded-xl border cursor-pointer transition-all"
                                       :class="paymentMethod === 'transfer_bank' ? 'border-[#4D9BE2] bg-[#4D9BE2]/5 dark:bg-[#4D9BE2]/10' : 'border-gray-200 dark:border-slate-800 hover:bg-gray-50 dark:hover:bg-slate-800/50'">
                                    <input type="radio" name="metode_pembayaran" value="transfer_bank" x-model="paymentMethod" class="sr-only">
                                    <span class="w-5 h-5 rounded-full border flex items-center justify-center mr-3"
                                          :class="paymentMethod === 'transfer_bank' ? 'border-[#4D9BE2]' : 'border-gray-300 dark:border-slate-700'">
                                        <span class="w-2.5 h-2.5 rounded-full bg-[#4D9BE2]" x-show="paymentMethod === 'transfer_bank'"></span>
                                    </span>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400 flex items-center justify-center">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-[#2F3951] dark:text-slate-200">Transfer Bank</p>
                                            <p class="text-[11px] text-gray-400 dark:text-slate-400">Transfer bank ke rekening BCA/Mandiri perpustakaan.</p>
                                        </div>
                                    </div>
                                </label>

                            </div>
                        </div>

                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 bg-gray-50 dark:bg-slate-800 border-t border-gray-100 dark:border-slate-700 flex justify-end gap-3">
                        <button type="button" @click="showPaymentModal = false" class="px-5 py-2.5 text-xs font-bold text-gray-500 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-xl transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition shadow-sm cursor-pointer">
                            Konfirmasi Pembayaran
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</div>
@endsection
