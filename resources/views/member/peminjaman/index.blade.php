@extends('layouts.app')

@section('title', 'Peminjaman Aktif')

@section('content')
<div class="w-full flex flex-col Box-border">

    <!-- Header Section -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#2F3951] tracking-tight">Peminjaman Buku</h1>
            <p class="text-gray-500 text-sm mt-1 font-medium">Daftar buku yang sedang Anda pinjam saat ini.</p>
        </div>
    </div>

    <!-- Main Content Table/Grid Card -->
    <div class="w-full bg-white rounded-3xl border border-gray-200 shadow p-6">
        @if($peminjamans->count() > 0)
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-gray-500 border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            <th scope="col" class="pb-4 font-bold">Buku</th>
                            <th scope="col" class="pb-4 font-bold">Tanggal Pinjam</th>
                            <th scope="col" class="pb-4 font-bold">Jatuh Tempo</th>
                            <th scope="col" class="pb-4 font-bold">Status / Sisa Waktu</th>
                            <th scope="col" class="pb-4 font-bold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($peminjamans as $pinjam)
                            <tr>
                                <!-- Book info -->
                                <td class="py-4 pr-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 aspect-[3/4] rounded-lg overflow-hidden shadow-sm border border-gray-100 bg-[#FCFCFC] flex-shrink-0">
                                            <img src="{{ $pinjam->buku->cover_buku ? asset('storage/' . $pinjam->buku->cover_buku) : 'https://placehold.co/300x400?text=' . urlencode($pinjam->buku->judul) }}" 
                                                 alt="{{ $pinjam->buku->judul }}" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div class="text-left">
                                            <a href="{{ route('buku.show', $pinjam->buku->id) }}" class="font-bold text-[#2F3951] hover:text-[#4D9BE2] transition-colors line-clamp-1">{{ $pinjam->buku->judul }}</a>
                                            <p class="text-[10px] text-gray-400 mt-0.5 truncate">{{ $pinjam->buku->pengarang }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Date Borrowed -->
                                <td class="py-4 text-xs font-bold text-[#2F3951]">
                                    {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M Y') }}
                                </td>

                                <!-- Due Date -->
                                <td class="py-4 text-xs font-bold text-[#2F3951]">
                                    {{ \Carbon\Carbon::parse($pinjam->tanggal_kembali)->format('d M Y') }}
                                </td>

                                <!-- Status & Days Remaining -->
                                <td class="py-4 text-xs">
                                    @if($pinjam->status === 'menunggu_konfirmasi')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-600 font-bold border border-amber-100 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                            Menunggu Persetujuan
                                        </span>
                                    @else
                                        @php
                                            $today = \Carbon\Carbon::now()->startOfDay();
                                            $dueDate = \Carbon\Carbon::parse($pinjam->tanggal_kembali)->startOfDay();
                                            $isOverdue = $today->gt($dueDate);
                                        @endphp

                                        @if($isOverdue)
                                            @php
                                                $daysLate = $today->diffInDays($dueDate);
                                            @endphp
                                            <div class="flex flex-col items-start gap-1">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-600 font-bold border border-rose-100">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    Terlambat {{ $daysLate }} hari
                                                </span>
                                                <span class="text-[10px] font-bold text-rose-500 bg-rose-50/50 px-2 py-0.5 rounded border border-rose-200/40">
                                                    Denda berjalan: Rp {{ number_format($daysLate * 1000, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        @else
                                            @php
                                                $daysRemaining = $today->diffInDays($dueDate);
                                            @endphp
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 font-bold border border-emerald-100">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                {{ $daysRemaining == 0 ? 'Hari ini' : $daysRemaining . ' hari lagi' }}
                                            </span>
                                        @endif
                                    @endif
                                </td>

                                <!-- Action button -->
                                <td class="py-4 text-center">
                                    @if($pinjam->status === 'menunggu_konfirmasi')
                                        <form action="{{ route('member.peminjaman.cancel', $pinjam->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan peminjaman ini?')" class="m-0">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-100 rounded-xl text-xs font-bold border border-rose-200/40 transition transform active:scale-95 cursor-pointer">
                                                Batalkan
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('member.peminjaman.kembali', $pinjam->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan buku ini?')" class="m-0">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl text-xs font-extrabold shadow-sm transition-all transform active:scale-95 cursor-pointer">
                                                Kembalikan Buku
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($peminjamans->hasPages())
                <div class="mt-6">
                    {{ $peminjamans->links('partials.pagination') }}
                </div>
            @endif
        @else
            <!-- Empty state -->
            <div class="flex flex-col items-center justify-center py-16 text-center text-gray-400">
                <div class="w-16 h-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-4 text-gray-300">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332 0.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 0.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332 0.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-[#2F3951] mb-1">Tidak ada peminjaman aktif</h3>
                <p class="text-xs text-gray-400 max-w-xs leading-relaxed">Cari buku menarik di katalog dan pinjam buku untuk mulai membaca.</p>
                <a href="{{ route('member.katalog') }}" class="mt-5 px-5 py-2.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl font-bold text-xs shadow-sm transition">
                    Buka Katalog
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
