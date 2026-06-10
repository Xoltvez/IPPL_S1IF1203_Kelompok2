@extends('layouts.app')

@section('title', 'Laporan Sirkulasi & Keuangan')

@section('content')
<div class="block w-full text-left clear-both">
    
    {{-- TITLE & ACTIONS --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#2F3951] dark:text-slate-100">Laporan Sirkulasi & Denda</h1>
            <p class="text-gray-500 dark:text-slate-400 text-sm mt-1">Analisis performa sirkulasi perpustakaan dan rekapitulasi denda anggota.</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('admin.laporan.export', request()->all()) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-semibold text-xs shadow-sm transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Ekspor CSV</span>
            </a>
            <a href="{{ route('admin.laporan.print', request()->all()) }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl font-semibold text-xs shadow-sm transition-all duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                <span>Cetak Laporan</span>
            </a>
        </div>
    </div>

    {{-- FILTER FORM --}}
    <div class="w-full bg-white dark:bg-slate-900 p-5 rounded-2xl border border-gray dark:border-slate-800 shadow mb-8 transition-colors duration-200">
        <form action="{{ route('admin.laporan.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label for="tanggal_mulai" class="block text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest mb-2">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ $start }}" class="w-full px-4 py-2.5 bg-[#F8FAFC]/60 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 focus:border-[#4D9BE2]/50 dark:focus:border-[#4D9BE2]/50 focus:bg-white dark:focus:bg-slate-900 rounded-xl text-sm text-[#2F3951] dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
            </div>
            <div>
                <label for="tanggal_selesai" class="block text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest mb-2">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ $end }}" class="w-full px-4 py-2.5 bg-[#F8FAFC]/60 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 focus:border-[#4D9BE2]/50 dark:focus:border-[#4D9BE2]/50 focus:bg-white dark:focus:bg-slate-900 rounded-xl text-sm text-[#2F3951] dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
            </div>
            <div>
                <label for="status" class="block text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest mb-2">Status Transaksi</label>
                <select name="status" id="status" class="w-full px-4 py-2.5 bg-[#F8FAFC]/60 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 focus:border-[#4D9BE2]/50 dark:focus:border-[#4D9BE2]/50 focus:bg-white dark:focus:bg-slate-900 rounded-xl text-sm text-[#2F3951] dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
                    <option value="semua" {{ $status === 'semua' ? 'selected' : '' }}>Semua Transaksi</option>
                    <option value="menunggu_konfirmasi" {{ $status === 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    <option value="dipinjam" {{ $status === 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="terlambat" {{ $status === 'terlambat' ? 'selected' : '' }}>Terlambat (Belum Kembali)</option>
                    <option value="dikembalikan" {{ $status === 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                    <option value="ditolak" {{ $status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-5 py-2.5 bg-[#2F3951] dark:bg-slate-800 hover:bg-[#2F3951]/90 dark:hover:bg-slate-700 text-white rounded-xl font-semibold text-sm transition-all shadow-sm">
                    Terapkan
                </button>
                <a href="{{ route('admin.laporan.index') }}" class="px-4 py-2.5 bg-gray-100 dark:bg-slate-850 hover:bg-gray-200 dark:hover:bg-slate-750 text-gray-600 dark:text-slate-300 rounded-xl font-semibold text-sm transition-all text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Peminjaman --}}
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-blue-100 dark:border-slate-800 flex items-center justify-between shadow-sm hover:border-blue-300 transition-all duration-300 group">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-gray-400 dark:text-slate-400 uppercase tracking-wider">Total Peminjaman</p>
                <h3 class="text-2xl font-bold text-[#2F3951] dark:text-slate-100">{{ $totalPeminjaman }}</h3>
                <p class="text-xs text-[#4D9BE2] font-medium">Buku diajukan/dipinjam</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 dark:bg-blue-950/30 flex items-center justify-center text-[#4D9BE2] transition-transform group-hover:scale-110 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332 0.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 0.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332 0.477-4.5 1.253" />
                </svg>
            </div>
        </div>

        {{-- Total Pengembalian --}}
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-emerald-100 dark:border-slate-800 flex items-center justify-between shadow-sm hover:border-emerald-300 transition-all duration-300 group">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-gray-400 dark:text-slate-400 uppercase tracking-wider">Sudah Kembali</p>
                <h3 class="text-2xl font-bold text-[#2F3951] dark:text-slate-100">{{ $totalPengembalian }}</h3>
                <p class="text-xs text-emerald-500 font-medium">Pengembalian sukses</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 flex items-center justify-center text-emerald-500 transition-transform group-hover:scale-110 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        {{-- Denda Lunas --}}
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-indigo-100 dark:border-slate-800 flex items-center justify-between shadow-sm hover:border-indigo-300 transition-all duration-300 group">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-gray-400 dark:text-slate-400 uppercase tracking-wider">Denda Terbayar</p>
                <h3 class="text-2xl font-bold text-[#2F3951] dark:text-slate-100">Rp {{ number_format($totalDendaLunas, 0, ',', '.') }}</h3>
                <p class="text-xs text-indigo-500 font-medium">Kas denda terkumpul</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 flex items-center justify-center text-indigo-500 transition-transform group-hover:scale-110 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>

        {{-- Denda Belum Lunas --}}
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-rose-100 dark:border-slate-800 flex items-center justify-between shadow-sm hover:border-rose-300 transition-all duration-300 group">
            <div class="space-y-1">
                <p class="text-xs font-semibold text-gray-400 dark:text-slate-400 uppercase tracking-wider">Denda Tertunggak</p>
                <h3 class="text-2xl font-bold text-rose-600">Rp {{ number_format($totalDendaBelumLunas, 0, ',', '.') }}</h3>
                <p class="text-xs text-rose-500 font-medium">Belum diselesaikan</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-950/30 flex items-center justify-center text-rose-500 transition-transform group-hover:scale-110 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- TOP LISTS SECTION (2 COLUMN) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        {{-- Buku Terpopuler --}}
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm transition-colors duration-200">
            <h3 class="font-bold text-base text-[#2F3951] dark:text-slate-100 mb-6 flex items-center gap-2">
                🔥 <span>5 Buku Paling Populer</span>
            </h3>
            <div class="space-y-4">
                @forelse($topBuku as $b)
                    @php
                        $maxTotal = $topBuku->first()->total ?? 1;
                        $percentage = ($b->total / $maxTotal) * 100;
                    @endphp
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-semibold text-[#2F3951] dark:text-slate-200 truncate w-3/4">{{ $b->buku->judul ?? 'Buku Terhapus' }}</span>
                            <span class="font-bold text-gray-500 dark:text-slate-400">{{ $b->total }} Peminjaman</span>
                        </div>
                        <div class="w-full bg-gray-100 dark:bg-slate-800 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-[#4D9BE2] h-full rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 dark:text-slate-500 text-xs text-center py-8">Tidak ada data peminjaman di periode ini.</p>
                @endforelse
            </div>
        </div>

        {{-- Member Teraktif --}}
        <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm transition-colors duration-200">
            <h3 class="font-bold text-base text-[#2F3951] dark:text-slate-100 mb-6 flex items-center gap-2">
                👑 <span>5 Member Paling Aktif</span>
            </h3>
            <div class="space-y-4">
                @forelse($topMembers as $m)
                    <div class="flex items-center justify-between p-3 bg-[#F8FAFC]/60 dark:bg-slate-800/40 border border-gray-50 dark:border-slate-800/80 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#4D9BE2]/10 text-[#4D9BE2] flex items-center justify-center font-bold text-xs">
                                {{ strtoupper(substr($m->user->name ?? 'M', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-[#2F3951] dark:text-slate-200">{{ $m->user->name ?? 'Member Terhapus' }}</p>
                                <p class="text-[10px] text-gray-400 dark:text-slate-400">{{ $m->user->email ?? '-' }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-[#4D9BE2] bg-[#4D9BE2]/10 px-2.5 py-1 rounded-lg">
                            {{ $m->total }} Pinjaman
                        </span>
                    </div>
                @empty
                    <p class="text-gray-400 dark:text-slate-500 text-xs text-center py-8">Tidak ada data peminjaman di periode ini.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- TRANSACTIONS TABLE --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden transition-colors duration-200">
        <div class="px-6 py-5 border-b border-gray-50 dark:border-slate-800 flex items-center justify-between bg-white dark:bg-slate-900">
            <h2 class="font-bold text-base text-[#2F3951] dark:text-slate-100">Daftar Transaksi Laporan</h2>
            <span class="text-xs text-gray-400 dark:text-slate-400">Total data terfilter: {{ $transactions->total() }}</span>
        </div>
        
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-[#F8FAFC] dark:bg-slate-800/50 border-b border-gray-100 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest text-center w-[5%]">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest w-[20%]">Nama Member</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest w-[25%]">Buku</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest w-[12%]">Tgl Pinjam</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest w-[12%]">Tgl Kembali</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest w-[12%]">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-400 uppercase tracking-widest text-center w-[14%]">Denda</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800 text-sm text-[#2F3951] dark:text-slate-200">
                    @forelse($transactions as $t)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 text-center font-medium text-gray-400 dark:text-slate-500">
                                {{ $loop->iteration + ($transactions->firstItem() - 1) }}
                            </td>
                            <td class="px-6 py-4 font-semibold">
                                {{ $t->user->name ?? '-' }}
                                <span class="block text-[10px] text-gray-400 dark:text-slate-500 font-normal">ID: #MBR-{{ str_pad($t->user_id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold block truncate max-w-[200px]" title="{{ $t->buku->judul ?? '-' }}">{{ $t->buku->judul ?? '-' }}</span>
                                <span class="block text-[10px] text-gray-400 dark:text-slate-500 font-normal">ISBN: {{ $t->buku->isbn ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-slate-400">
                                {{ \Carbon\Carbon::parse($t->tanggal_pinjam)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-slate-400">
                                {{ \Carbon\Carbon::parse($t->tanggal_kembali)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($t->status === 'menunggu_konfirmasi')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 dark:bg-amber-950/20 text-amber-600 border border-amber-100 dark:border-amber-900/30 uppercase">Menunggu</span>
                                @elseif($t->status === 'dipinjam')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-blue-50 dark:bg-blue-950/20 text-blue-600 border border-blue-100 dark:border-blue-900/30 uppercase">Dipinjam</span>
                                @elseif($t->status === 'dikembalikan')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 border border-emerald-100 dark:border-emerald-900/30 uppercase">Selesai</span>
                                @elseif($t->status === 'ditolak')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 dark:bg-rose-950/20 text-rose-600 border border-rose-100 dark:border-rose-900/30 uppercase">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($t->denda)
                                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-lg {{ $t->denda->status_pembayaran === 'lunas' ? 'text-emerald-600 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30' : 'text-rose-600 bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30' }}">
                                        Rp {{ number_format($t->denda->jumlah_denda, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-xs font-bold text-gray-400 dark:text-slate-500">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400 dark:text-slate-500">
                                Tidak ada data sirkulasi peminjaman ditemukan dalam rentang filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($transactions->hasPages())
            <div class="p-4 border-t border-gray-50 dark:border-slate-800 bg-[#F8FAFC]/40 dark:bg-slate-900 w-full clear-both">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
