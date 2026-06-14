@extends('layouts.app')

@section('title', __('Peminjaman Aktif'))

@section('content')
<div class="w-full flex flex-col Box-border">

    <!-- Header Section -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#2F3951] dark:text-slate-100 tracking-tight">{{ __('Peminjaman Buku') }}</h1>
            <p class="text-gray-500 text-sm mt-1 font-medium">{{ __('Daftar buku yang sedang Anda pinjam saat ini.') }}</p>
        </div>
    </div>

    <!-- Main Content Table/Grid Card -->
    <div class="w-full bg-white dark:bg-slate-800 rounded-3xl border border-gray-200 dark:border-slate-700/50 shadow p-6 transition-colors duration-200">
        @if($peminjamans->count() > 0)
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left text-gray-500 dark:text-slate-300 border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-slate-700/50 text-[10px] font-bold text-gray-400 dark:text-slate-400 uppercase tracking-wider">
                            <th scope="col" class="pb-4 font-bold">{{ __('Buku') }}</th>
                            <th scope="col" class="pb-4 font-bold">{{ __('Tanggal Pinjam') }}</th>
                            <th scope="col" class="pb-4 font-bold">{{ __('Jatuh Tempo') }}</th>
                            <th scope="col" class="pb-4 font-bold">{{ __('Status / Sisa Waktu') }}</th>
                            <th scope="col" class="pb-4 font-bold text-center">{{ __('Aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                        @foreach($peminjamans as $pinjam)
                            <tr>
                                <!-- Book info -->
                                <td class="py-4 pr-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 aspect-[3/4] rounded-lg overflow-hidden shadow-sm border border-gray-100 dark:border-slate-700 bg-[#FCFCFC] dark:bg-slate-900 flex-shrink-0">
                                            <img src="{{ $pinjam->buku->cover_buku ? asset('storage/' . $pinjam->buku->cover_buku) : 'https://placehold.co/300x400?text=' . urlencode($pinjam->buku->judul) }}" 
                                                 alt="{{ $pinjam->buku->judul }}" 
                                                 class="w-full h-full object-cover">
                                        </div>
                                        <div class="text-left">
                                            <a href="{{ route('buku.show', $pinjam->buku->id) }}" class="font-bold text-[#2F3951] dark:text-slate-200 hover:text-[#4D9BE2] dark:hover:text-[#5fa8eb] transition-colors line-clamp-1">{{ $pinjam->buku->judul }}</a>
                                            <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-0.5 truncate">{{ $pinjam->buku->pengarang }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Date Borrowed -->
                                <td class="py-4 text-xs font-bold text-[#2F3951] dark:text-slate-300">
                                    {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->translatedFormat('d M Y') }}
                                </td>

                                <!-- Due Date -->
                                <td class="py-4 text-xs font-bold text-[#2F3951] dark:text-slate-300">
                                    {{ \Carbon\Carbon::parse($pinjam->tanggal_kembali)->translatedFormat('d M Y') }}
                                </td>

                                <!-- Status & Days Remaining -->
                                <td class="py-4 text-xs">
                                    @if($pinjam->status === 'menunggu_konfirmasi')
                                        @if($pinjam->isExpired())
                                            <div class="flex flex-col items-start gap-1">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 font-bold border border-rose-100 dark:border-rose-900/30 whitespace-nowrap">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    {{ __('Kadaluwarsa') }}
                                                </span>
                                                <span class="text-[10px] font-semibold text-rose-500 dark:text-rose-400">{{ __('Batas pengambilan habis (6 jam)') }}</span>
                                            </div>
                                        @else
                                            <div class="flex flex-col items-start gap-1">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 font-bold border border-amber-100 dark:border-amber-900/30 whitespace-nowrap">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                                    {{ __('Menunggu Persetujuan') }}
                                                </span>
                                                <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 bg-amber-50/50 dark:bg-amber-950/20 px-2 py-0.5 rounded border border-amber-200/40 dark:border-amber-900/30 whitespace-nowrap countdown-timer" data-deadline="{{ $pinjam->pickup_deadline->toIso8601String() }}">
                                                    {{ __('Sisa Waktu:') }} --:--:--
                                                </span>
                                            </div>
                                        @endif
                                    @else
                                        @php
                                            $today = \Carbon\Carbon::now()->startOfDay();
                                            $dueDate = \Carbon\Carbon::parse($pinjam->tanggal_kembali)->startOfDay();
                                            $isOverdue = $today->gt($dueDate);
                                        @endphp

                                        @if($isOverdue)
                                            @php
                                                $daysLate = $today->diffInDays($dueDate, true);
                                            @endphp
                                            <div class="flex flex-col items-start gap-1">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 font-bold border border-rose-100 dark:border-rose-900/30">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                    {{ __('Terlambat') }} {{ $daysLate }} {{ __('hari') }}
                                                </span>
                                                <span class="text-[10px] font-bold text-rose-500 dark:text-rose-400 bg-rose-50/50 dark:bg-rose-950/20 px-2 py-0.5 rounded border border-rose-200/40 dark:border-rose-900/30">
                                                    {{ __('Denda berjalan:') }} Rp {{ number_format($daysLate * 1000, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        @else
                                            @php
                                                $daysRemaining = $today->diffInDays($dueDate, true);
                                            @endphp
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 font-bold border border-emerald-100 dark:border-emerald-900/30">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                                {{ $daysRemaining == 0 ? __('Hari ini') : $daysRemaining . ' ' . __('hari lagi') }}
                                            </span>
                                        @endif
                                    @endif
                                </td>

                                <!-- Action button -->
                                <td class="py-4 text-center">
                                    @if($pinjam->status === 'menunggu_konfirmasi')
                                        <form action="{{ route('member.peminjaman.cancel', $pinjam->id) }}" method="POST" onsubmit="return confirm('{{ __('Apakah Anda yakin ingin membatalkan pengajuan peminjaman ini?') }}')" class="m-0">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/40 rounded-xl text-xs font-bold border border-rose-200/40 dark:border-rose-900/30 transition transform active:scale-95 cursor-pointer">
                                                {{ __('Batalkan') }}
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('member.peminjaman.kembali', $pinjam->id) }}" method="POST" onsubmit="return confirm('{{ __('Apakah Anda yakin ingin mengembalikan buku ini?') }}')" class="m-0">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl text-xs font-extrabold shadow-sm transition-all transform active:scale-95 cursor-pointer">
                                                {{ __('Kembalikan Buku') }}
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
            <div class="flex flex-col items-center justify-center py-16 text-center text-gray-400 dark:text-slate-500">
                <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center justify-center mb-4 text-gray-300 dark:text-slate-500">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332 0.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 0.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332 0.477-4.5 1.253" />
                    </svg>
                </div>
                <h3 class="text-sm font-bold text-[#2F3951] dark:text-slate-200 mb-1">{{ __('Tidak ada peminjaman aktif') }}</h3>
                <p class="text-xs text-gray-400 dark:text-slate-500 max-w-xs leading-relaxed">{{ __('Cari buku menarik di katalog dan pinjam buku untuk mulai membaca.') }}</p>
                <a href="{{ route('member.katalog') }}" class="mt-5 px-5 py-2.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl font-bold text-xs shadow-sm transition">
                    {{ __('Buka Katalog') }}
                </a>
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timers = document.querySelectorAll('.countdown-timer');
        const expiredText = "{{ __('Kadaluwarsa') }}";
        const limitText = "{{ __('Batas pengambilan habis (6 jam)') }}";
        const sisaWaktuText = "{{ __('Sisa Waktu:') }}";
        
        function updateTimers() {
            timers.forEach(timer => {
                const deadlineStr = timer.getAttribute('data-deadline');
                if (!deadlineStr) return;
                
                const deadline = new Date(deadlineStr).getTime();
                const now = new Date().getTime();
                const diff = deadline - now;
                
                if (diff <= 0) {
                    // Waktu habis, ubah status ke Kadaluwarsa
                    const parent = timer.closest('.flex');
                    if (parent) {
                        parent.innerHTML = `
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 font-bold border border-rose-100 dark:border-rose-900/30 whitespace-nowrap">
                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                ${expiredText}
                            </span>
                            <span class="text-[10px] font-semibold text-rose-500 dark:text-rose-400">${limitText}</span>
                        `;
                    }
                    return;
                }
                
                const hours = Math.floor(diff / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                
                const hDisplay = String(hours).padStart(2, '0');
                const mDisplay = String(minutes).padStart(2, '0');
                const sDisplay = String(seconds).padStart(2, '0');
                
                timer.textContent = `${sisaWaktuText} ${hDisplay}:${mDisplay}:${sDisplay}`;
            });
        }
        
        updateTimers();
        setInterval(updateTimers, 1000);
    });
</script>
@endpush

