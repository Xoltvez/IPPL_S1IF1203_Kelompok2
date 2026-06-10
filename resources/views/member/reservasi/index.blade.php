@extends('layouts.app')

@section('title', 'Daftar Reservasi')

@section('content')
<div class="w-full flex flex-col Box-border">

    <!-- Header Section -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#2F3951] dark:text-slate-100 tracking-tight">Reservasi Buku</h1>
            <p class="text-gray-500 dark:text-slate-400 text-sm mt-1 font-medium">Daftar reservasi buku Anda ketika stok sedang kosong.</p>
        </div>
    </div>

    <!-- Main Content Table/Grid Card -->
    <div class="w-full bg-white dark:bg-slate-900 rounded-3xl border border-gray-200 dark:border-slate-800 shadow p-6 transition-colors duration-200">
        @if($reservasis->count() > 0)
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-gray-500 dark:text-slate-400 border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-slate-800 text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">
                            <th scope="col" class="pb-4 font-bold">Buku</th>
                            <th scope="col" class="pb-4 font-bold">Tanggal Reservasi</th>
                            <th scope="col" class="pb-4 font-bold">Status</th>
                            <th scope="col" class="pb-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-800/50">
                        @foreach($reservasis as $res)
                            <tr>
                                <!-- Book info -->
                                <td class="py-4 pr-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 aspect-[3/4] rounded-lg overflow-hidden shadow-sm border border-gray-100 dark:border-slate-800 bg-[#FCFCFC] dark:bg-slate-950 flex-shrink-0">
                                            <img src="{{ $res->buku->cover_buku ? asset('storage/' . $res->buku->cover_buku) : 'https://placehold.co/300x400?text=' . urlencode($res->buku->judul) }}" 
                                                 alt="{{ $res->buku->judul }}" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div class="text-left">
                                            <a href="{{ route('buku.show', $res->buku->id) }}" class="font-bold text-[#2F3951] dark:text-slate-200 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2] transition-colors line-clamp-1">{{ $res->buku->judul }}</a>
                                            <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-0.5 truncate">{{ $res->buku->pengarang }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Date Reserved -->
                                <td class="py-4 text-xs font-bold text-[#2F3951] dark:text-slate-300">
                                    {{ $res->created_at->format('d M Y H:i') }}
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 text-xs">
                                    @if($res->status === 'menunggu')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 font-bold border border-amber-100 dark:border-amber-900/50 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Menunggu Antrean
                                        </span>
                                    @elseif($res->status === 'proses')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 font-bold border border-emerald-100 dark:border-emerald-900/50 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Diproses ke Peminjaman
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 font-bold border border-slate-200 dark:border-slate-700 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 dark:bg-slate-500"></span>
                                            Dibatalkan
                                        </span>
                                    @endif
                                </td>

                                <!-- Action button -->
                                <td class="py-4 text-center">
                                    @if($res->status === 'menunggu')
                                        <form action="{{ route('member.reservasi.cancel', $res->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi buku ini?')" class="m-0">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/40 rounded-xl text-xs font-bold border border-rose-200/40 dark:border-rose-900/30 transition transform active:scale-95 cursor-pointer">
                                                Batalkan Reservasi
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-slate-600 font-semibold">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($reservasis->hasPages())
                <div class="mt-6">
                    {{ $reservasis->links('partials.pagination') }}
                </div>
            @endif
        @else
            <!-- Empty state -->
            <div class="flex flex-col items-center justify-center py-16 text-center text-gray-400 dark:text-slate-500">
                <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center justify-center mb-4 text-gray-300 dark:text-slate-600">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-[#2F3951] dark:text-slate-200 mb-1">Tidak ada reservasi aktif</h3>
                <p class="text-xs text-gray-400 dark:text-slate-500 max-w-xs leading-relaxed">Reservasi buku hanya bisa dilakukan untuk buku yang stoknya sedang habis.</p>
                <a href="{{ route('member.katalog') }}" class="mt-5 px-5 py-2.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl font-bold text-xs shadow-sm transition">
                    Buka Katalog
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
