@extends('layouts.app')

@section('title', 'Riwayat Pengembalian Buku')

@section('content')
<div class="block w-full text-left clear-both">

    {{-- STATS / HEADER CARD --}}
    <div class="w-full bg-white p-6 rounded-2xl border border-gray-100 shadow mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-[#2F3951] tracking-tight">Riwayat Pengembalian Buku</h2>
                <p class="text-gray-400 text-xs mt-1">Daftar buku yang telah dikembalikan oleh anggota perpustakaan.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-600 border border-emerald-100">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    {{ $pengembalians->total() }} Buku Dikembalikan
                </span>
            </div>
        </div>
    </div>

    {{-- SEARCH BAR --}}
    <div class="w-full bg-white p-4 rounded-2xl border border-gray-100 shadow mb-6">
        <form action="{{ route(auth()->user()->role . '.pengembalian.index') }}" method="GET" class="w-full flex items-center gap-3">
            <div class="relative flex-1 flex items-center">
                <span class="absolute left-4 text-gray-400 pointer-events-none flex items-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari nama member, email, judul buku, atau scan QR..." 
                    class="w-full pl-12 pr-4 py-2.5 bg-[#F8FAFC]/60 border border-gray-100 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2.5 bg-[#2F3951] hover:bg-[#2F3951]/90 text-white rounded-xl font-semibold text-sm transition shadow-sm cursor-pointer">
                    Cari
                </button>

                @if(request()->filled('search'))
                    <a href="{{ route(auth()->user()->role . '.pengembalian.index') }}" 
                    class="px-5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl font-semibold text-sm transition flex items-center gap-2 cursor-pointer">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABLE PENGEMBALIAN --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[1050px]">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center w-[5%]">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest w-[20%]">Anggota / Member</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest w-[25%]">Buku</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest w-[12%]">Tanggal Pinjam</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest w-[12%]">Jatuh Tempo</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest w-[12%]">Tanggal Kembali</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center w-[14%]">Denda / Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm text-[#2F3951]">
                    @forelse($pengembalians as $pengembalian)
                        @php
                            $dueDate = \Carbon\Carbon::parse($pengembalian->tanggal_kembali)->startOfDay();
                            $returnDate = \Carbon\Carbon::parse($pengembalian->updated_at)->startOfDay();
                            $isLate = $returnDate->gt($dueDate);
                            $daysLate = $isLate ? $returnDate->diffInDays($dueDate, true) : 0;
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-center font-medium text-gray-400">
                                {{ $loop->iteration + ($pengembalians->firstItem() - 1) }}
                            </td>
                            
                            {{-- INFO MEMBER --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#4D9BE2]/10 flex items-center justify-center text-[#4D9BE2] font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($pengembalian->user->name ?? 'M', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-[#2F3951] truncate">{{ $pengembalian->user->name ?? 'Tamu/Deleted User' }}</p>
                                        <p class="text-[11px] text-gray-400 truncate">{{ $pengembalian->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- INFO BUKU --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-12 rounded overflow-hidden shadow-sm border border-gray-100 flex-shrink-0 bg-gray-50 flex items-center justify-center">
                                        <img src="{{ $pengembalian->buku->cover_buku ? asset('storage/' . $pengembalian->buku->cover_buku) : 'https://placehold.co/120x160?text=' . urlencode($pengembalian->buku->judul ?? 'Buku') }}" 
                                             alt="{{ $pengembalian->buku->judul ?? 'Cover' }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-bold text-[#2F3951] truncate" title="{{ $pengembalian->buku->judul ?? '-' }}">{{ $pengembalian->buku->judul ?? '-' }}</p>
                                        <p class="text-[11px] text-gray-400 truncate">Penulis: {{ $pengembalian->buku->pengarang ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- TANGGAL PINJAM --}}
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                                {{ \Carbon\Carbon::parse($pengembalian->tanggal_pinjam)->format('d M Y') }}
                            </td>

                            {{-- JATUH TEMPO --}}
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                                {{ $dueDate->format('d M Y') }}
                            </td>

                            {{-- TANGGAL KEMBALI --}}
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                                {{ $returnDate->format('d M Y') }}
                            </td>

                            {{-- DENDA --}}
                            <td class="px-6 py-4 text-center">
                                @if($pengembalian->denda)
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-xs font-bold text-rose-600 bg-rose-50 border border-rose-100 px-2 py-0.5 rounded-lg whitespace-nowrap">
                                            Rp {{ number_format($pengembalian->denda->jumlah_denda, 0, ',', '.') }}
                                        </span>
                                        @if($pengembalian->denda->status_pembayaran === 'lunas')
                                            <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200/60 uppercase">
                                                Lunas
                                            </span>
                                        @else
                                            <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded border border-rose-200/60 uppercase animate-pulse mb-1">
                                                Belum Lunas
                                            </span>
                                            <form action="{{ route(auth()->user()->role . '.denda.bayar', $pengembalian->denda->id) }}" method="POST" onsubmit="return confirm('Apakah denda ini sudah dilunasi?')">
                                                @csrf
                                                <button type="submit" class="px-2 py-0.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-md text-[9px] font-bold transition shadow-sm whitespace-nowrap mt-1 cursor-pointer">
                                                    Bayar Denda
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @elseif($isLate && $daysLate > 0)
                                    <div class="flex flex-col items-center gap-1">
                                        <span class="text-xs font-bold text-rose-600 bg-rose-50 border border-rose-100 px-2 py-0.5 rounded-lg whitespace-nowrap">
                                            Rp {{ number_format($daysLate * 1000, 0, ',', '.') }}
                                        </span>
                                        <span class="text-[9px] font-bold text-rose-500 bg-rose-50/50 px-1.5 py-0.5 rounded border border-rose-200/30 uppercase">
                                            Terlambat {{ $daysLate }} Hari
                                        </span>
                                    </div>
                                @else
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 whitespace-nowrap">
                                        Tepat Waktu (No Fine)
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="text-gray-400 text-xs font-medium">Belum ada riwayat pengembalian buku.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($pengembalians->hasPages())
            <div class="p-4 border-t border-gray-50 bg-[#F8FAFC]/40 w-full clear-both">
                {{ $pengembalians->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
