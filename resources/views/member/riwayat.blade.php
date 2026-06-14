@extends('layouts.app')

@section('title', __('Riwayat Transaksi'))

@section('content')
<div class="w-full flex flex-col Box-border">

    <!-- Header Section -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#2F3951] dark:text-slate-100 tracking-tight">{{ __('Riwayat Peminjaman') }}</h1>
            <p class="text-gray-500 dark:text-slate-400 text-sm mt-1 font-medium">{{ __('Catatan seluruh transaksi peminjaman buku Anda.') }}</p>
        </div>
    </div>

    <!-- Status Filter Pills -->
    <div class="flex items-center gap-3 overflow-x-auto pb-4 mb-6 no-scrollbar">
        <a href="{{ route('member.riwayat.index') }}" 
           class="px-5 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ !$status ? 'bg-[#4D9BE2] text-white shadow-sm' : 'bg-[#F1F5F9] dark:bg-slate-800 text-gray-500 dark:text-slate-400 hover:bg-gray-200/70 dark:hover:bg-slate-700' }}">
            {{ __('Semua') }}
        </a>
        <a href="{{ route('member.riwayat.index', ['status' => 'dipinjam']) }}" 
           class="px-5 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ $status === 'dipinjam' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'bg-[#F1F5F9] dark:bg-slate-800 text-gray-500 dark:text-slate-400 hover:bg-gray-200/70 dark:hover:bg-slate-700' }}">
            {{ __('Sedang Dipinjam') }}
        </a>
        <a href="{{ route('member.riwayat.index', ['status' => 'dikembalikan']) }}" 
           class="px-5 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ $status === 'dikembalikan' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'bg-[#F1F5F9] dark:bg-slate-800 text-gray-500 dark:text-slate-400 hover:bg-gray-200/70 dark:hover:bg-slate-700' }}">
            {{ __('Dikembalikan') }}
        </a>
        <a href="{{ route('member.riwayat.index', ['status' => 'ditolak']) }}" 
           class="px-5 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ $status === 'ditolak' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'bg-[#F1F5F9] dark:bg-slate-800 text-gray-500 dark:text-slate-400 hover:bg-gray-200/70 dark:hover:bg-slate-700' }}">
            {{ __('Ditolak') }}
        </a>
    </div>

    <!-- Main Content Card -->
    <div class="w-full bg-white dark:bg-slate-800 rounded-3xl border border-gray-200 dark:border-slate-700/50 shadow p-6 transition-colors duration-200">
        @if($riwayats->count() > 0)
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-gray-500 dark:text-slate-300 border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-slate-700/50 text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">
                            <th scope="col" class="pb-4 font-bold">{{ __('Buku') }}</th>
                            <th scope="col" class="pb-4 font-bold">{{ __('Tanggal Pinjam') }}</th>
                            <th scope="col" class="pb-4 font-bold">{{ __('Tanggal Kembali') }}</th>
                            <th scope="col" class="pb-4 font-bold">{{ __('Status') }}</th>
                            <th scope="col" class="pb-4 font-bold">{{ __('Denda Keterlambatan') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                        @foreach($riwayats as $riwayat)
                            <tr>
                                <!-- Book Info -->
                                <td class="py-4 pr-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 aspect-[3/4] rounded-lg overflow-hidden shadow-sm border border-gray-100 dark:border-slate-700 bg-[#FCFCFC] dark:bg-slate-900 flex-shrink-0">
                                            <img src="{{ $riwayat->buku->cover_buku ? asset('storage/' . $riwayat->buku->cover_buku) : 'https://placehold.co/300x400?text=' . urlencode($riwayat->buku->judul) }}" 
                                                 alt="{{ $riwayat->buku->judul }}" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div class="text-left">
                                            <a href="{{ route('buku.show', $riwayat->buku->id) }}" class="font-bold text-[#2F3951] dark:text-slate-200 hover:text-[#4D9BE2] dark:hover:text-[#5fa8eb] transition-colors line-clamp-1 text-xs">{{ $riwayat->buku->judul }}</a>
                                            <p class="text-[9px] text-gray-400 dark:text-slate-500 mt-0.5 truncate">{{ $riwayat->buku->pengarang }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Date Borrowed -->
                                <td class="py-4 text-xs font-bold text-[#2F3951] dark:text-slate-355">
                                    {{ \Carbon\Carbon::parse($riwayat->tanggal_pinjam)->translatedFormat('d M Y') }}
                                </td>

                                <!-- Date Returned / Due Date -->
                                <td class="py-4 text-xs font-bold text-[#2F3951] dark:text-slate-355">
                                    @if($riwayat->status == 'dikembalikan')
                                        {{ \Carbon\Carbon::parse($riwayat->updated_at)->translatedFormat('d M Y') }}
                                    @else
                                        <span class="text-gray-400 dark:text-slate-500 font-medium">{{ __('Batas:') }} {{ \Carbon\Carbon::parse($riwayat->tanggal_kembali)->translatedFormat('d M Y') }}</span>
                                    @endif
                                </td>

                                 <!-- Status Badge -->
                                <td class="py-4 text-xs">
                                    @if($riwayat->status == 'menunggu_konfirmasi')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 font-bold border border-amber-100 dark:border-amber-900/30 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            {{ __('Menunggu Persetujuan') }}
                                        </span>
                                    @elseif($riwayat->status == 'dipinjam')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-950/30 text-[#4D9BE2] dark:text-[#5fa8eb] font-bold border border-blue-100 dark:border-blue-900/30 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#4D9BE2] animate-pulse"></span>
                                            {{ __('Dipinjam') }}
                                        </span>
                                    @elseif($riwayat->status == 'dikembalikan')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 font-bold border border-emerald-100 dark:border-emerald-900/30 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            {{ __('Dikembalikan') }}
                                        </span>
                                    @elseif($riwayat->status == 'ditolak')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 font-bold border border-rose-100 dark:border-rose-900/30 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            {{ __('Ditolak') }}
                                        </span>
                                    @elseif($riwayat->status == 'hilang')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-50 dark:bg-slate-700/50 text-gray-600 dark:text-slate-400 font-bold border border-gray-100 dark:border-slate-700 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span>
                                            {{ __('Hilang') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-50 dark:bg-slate-700/50 text-slate-600 dark:text-slate-400 font-bold border border-slate-100 dark:border-slate-700 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                            {{ __(ucfirst($riwayat->status)) }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Denda Details -->
                                <td class="py-4 text-xs">
                                    @if($riwayat->denda)
                                        <div class="flex flex-col text-left">
                                            <span class="font-extrabold text-rose-500 dark:text-rose-400">Rp {{ number_format($riwayat->denda->jumlah_denda, 0, ',', '.') }}</span>
                                            @if($riwayat->denda->status_pembayaran == 'belum_lunas')
                                                <span class="text-[9px] font-bold text-rose-400 dark:text-rose-350 uppercase mt-0.5">{{ __('Belum Lunas') }}</span>
                                            @else
                                                <span class="text-[9px] font-bold text-emerald-500 dark:text-emerald-450 uppercase mt-0.5">{{ __('Lunas') }}</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400 dark:text-slate-600">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($riwayats->hasPages())
                <div class="mt-6">
                    {{ $riwayats->links('partials.pagination') }}
                </div>
            @endif
        @else
            <!-- Empty state -->
            <div class="flex flex-col items-center justify-center py-16 text-center text-gray-400 dark:text-slate-500">
                <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center justify-center mb-4 text-gray-300 dark:text-slate-650">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-[#2F3951] dark:text-slate-200 mb-1">{{ __('Tidak ada riwayat transaksi') }}</h3>
                <p class="text-xs text-gray-400 dark:text-slate-500 max-w-xs leading-relaxed">
                    @if($status)
                        {{ __('Tidak ditemukan transaksi dengan status') }} <strong>{{ $status == 'dipinjam' ? __('Sedang Dipinjam') : __(ucfirst($status)) }}</strong>.
                    @else
                        {{ __('Seluruh riwayat peminjaman dan pengembalian Anda akan tercatat di sini.') }}
                    @endif
                </p>
            </div>
        @endif
    </div>

</div>
@endsection
