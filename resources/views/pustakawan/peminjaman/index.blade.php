@extends('layouts.app')

@section('title', 'Transaksi Peminjaman Aktif')

@section('content')
<div class="block w-full text-left clear-both">

    {{-- STATS / HEADER CARD --}}
    <div class="w-full bg-white p-6 rounded-2xl border border-gray-100 shadow mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-[#2F3951] tracking-tight">Peminjaman Buku Aktif</h2>
                <p class="text-gray-400 text-xs mt-1">Daftar buku yang saat ini sedang dipinjam oleh anggota perpustakaan.</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#4D9BE2]/10 text-[#4D9BE2]">
                    <span class="w-2 h-2 rounded-full bg-[#4D9BE2] animate-pulse"></span>
                    {{ $peminjamans->total() }} Transaksi Aktif
                </span>
            </div>
        </div>
    </div>

    {{-- SEARCH BAR --}}
    <div class="w-full bg-white p-4 rounded-2xl border border-gray-100 shadow mb-6">
        <form action="{{ route(auth()->user()->role . '.peminjaman.index') }}" method="GET" class="w-full flex items-center gap-3">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
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
                    <a href="{{ route(auth()->user()->role . '.peminjaman.index', request()->only('status')) }}" 
                    class="px-5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl font-semibold text-sm transition flex items-center gap-2 cursor-pointer">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- FILTER PILLS --}}
    <div class="flex items-center gap-3 mb-6 overflow-x-auto pb-2 no-scrollbar">
        <!-- Semua Tab -->
        <a href="{{ route(auth()->user()->role . '.peminjaman.index', array_merge(request()->except('status', 'page'))) }}"
           class="px-5 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ !request('status') ? 'bg-[#4D9BE2] text-white shadow-sm' : 'bg-white text-gray-500 border border-gray-100 hover:bg-[#F3F7FB]' }}">
            Semua
        </a>
        
        <!-- Menunggu Persetujuan Tab -->
        <a href="{{ route(auth()->user()->role . '.peminjaman.index', array_merge(request()->except('page'), ['status' => 'menunggu_konfirmasi'])) }}"
           class="px-5 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ request('status') === 'menunggu_konfirmasi' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'bg-white text-gray-500 border border-gray-100 hover:bg-[#F3F7FB]' }}">
            Menunggu Persetujuan
        </a>
        
        <!-- Setelah Disetujui Tab -->
        <a href="{{ route(auth()->user()->role . '.peminjaman.index', array_merge(request()->except('page'), ['status' => 'dipinjam'])) }}"
           class="px-5 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ request('status') === 'dipinjam' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'bg-white text-gray-500 border border-gray-100 hover:bg-[#F3F7FB]' }}">
            Setelah Disetujui
        </a>
    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    {{-- TABLE PEMINJAMAN AKTIF --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[950px]">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center w-[5%]">No</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest w-[25%]">Anggota / Member</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest w-[25%]">Buku yang Dipinjam</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest w-[12%]">Tanggal Pinjam</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest w-[12%]">Jatuh Tempo</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center w-[11%]">Status / Sisa Waktu</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center w-[10%]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm text-[#2F3951]">
                    @forelse($peminjamans as $peminjaman)
                        @php
                            $dueDate = \Carbon\Carbon::parse($peminjaman->tanggal_kembali);
                            $today = \Carbon\Carbon::today();
                            $isOverdue = $today->gt($dueDate);
                            $daysDiff = $today->diffInDays($dueDate, false);
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-center font-medium text-gray-400">
                                {{ $loop->iteration + ($peminjamans->firstItem() - 1) }}
                            </td>
                            
                            {{-- INFO MEMBER --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#4D9BE2]/10 flex items-center justify-center text-[#4D9BE2] font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($peminjaman->user->name ?? 'M', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-[#2F3951] truncate">{{ $peminjaman->user->name ?? 'Tamu/Deleted User' }}</p>
                                        <p class="text-[11px] text-gray-400 truncate">{{ $peminjaman->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- INFO BUKU --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-12 rounded overflow-hidden shadow-sm border border-gray-100 flex-shrink-0 bg-gray-50 flex items-center justify-center">
                                        <img src="{{ $peminjaman->buku->cover_buku ? asset('storage/' . $peminjaman->buku->cover_buku) : 'https://placehold.co/120x160?text=' . urlencode($peminjaman->buku->judul ?? 'Buku') }}" 
                                             alt="{{ $peminjaman->buku->judul ?? 'Cover' }}" 
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-bold text-[#2F3951] truncate" title="{{ $peminjaman->buku->judul ?? '-' }}">{{ $peminjaman->buku->judul ?? '-' }}</p>
                                        <p class="text-[11px] text-gray-400 truncate">Penulis: {{ $peminjaman->buku->pengarang ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- TANGGAL PINJAM --}}
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                                @if($peminjaman->status === 'menunggu_konfirmasi')
                                    <span class="text-xs text-amber-600 font-bold bg-amber-50 px-2 py-1 rounded border border-amber-200/50">Pengajuan</span>
                                @else
                                    {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}
                                @endif
                            </td>

                            {{-- JATUH TEMPO --}}
                            <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                                @if($peminjaman->status === 'menunggu_konfirmasi')
                                    @php
                                        $duration = \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->diffInDays(\Carbon\Carbon::parse($peminjaman->tanggal_pinjam), true);
                                        if ($duration <= 0) $duration = 7;
                                    @endphp
                                    <span class="text-gray-400 font-medium">{{ $duration }} Hari</span>
                                @else
                                    {{ $dueDate->format('d M Y') }}
                                @endif
                            </td>

                            {{-- STATUS / SISA HARI --}}
                            <td class="px-6 py-4 text-center">
                                @if($peminjaman->status === 'menunggu_konfirmasi')
                                    @if($peminjaman->isExpired())
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold text-rose-600 bg-rose-50 border border-rose-100 whitespace-nowrap">
                                                Kadaluwarsa
                                            </span>
                                            <span class="text-[10px] font-semibold text-rose-500">Waktu Pengambilan Habis</span>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center gap-1" id="status-container-{{ $peminjaman->id }}">
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold text-amber-600 bg-amber-50 border border-amber-100 whitespace-nowrap">
                                                Menunggu Persetujuan
                                            </span>
                                            <span class="text-[10px] font-bold text-amber-600 bg-amber-50/50 px-2 py-0.5 rounded border border-amber-200/40 whitespace-nowrap countdown-timer" data-id="{{ $peminjaman->id }}" data-deadline="{{ $peminjaman->pickup_deadline->toIso8601String() }}">
                                                Sisa Waktu: --:--:--
                                            </span>
                                        </div>
                                    @endif
                                @else
                                    @if($isOverdue)
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold text-rose-600 bg-rose-50 border border-rose-100 whitespace-nowrap">
                                                Terlambat {{ abs($daysDiff) }} Hari
                                            </span>
                                            <span class="text-[10px] font-bold text-rose-500 bg-rose-50/50 px-2 py-0.5 rounded border border-rose-200/40">
                                                Denda: Rp {{ number_format(abs($daysDiff) * 1000, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 whitespace-nowrap">
                                            {{ $daysDiff == 0 ? 'Hari Ini' : ($daysDiff . ' Hari Lagi') }}
                                        </span>
                                    @endif
                                @endif
                            </td>

                            {{-- AKSI KEMBALI --}}
                            <td class="px-6 py-4 text-center">
                                @if($peminjaman->status === 'menunggu_konfirmasi')
                                    <div class="flex items-center justify-center gap-2" id="action-container-{{ $peminjaman->id }}">
                                        @if($peminjaman->isExpired())
                                            <form action="{{ route(auth()->user()->role . '.peminjaman.tolak', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan kadaluwarsa ini?')">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white rounded-xl text-xs font-bold transition shadow-sm whitespace-nowrap cursor-pointer">
                                                    Batalkan (Kadaluwarsa)
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route(auth()->user()->role . '.peminjaman.setujui', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui peminjaman ini?')">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition shadow-sm whitespace-nowrap cursor-pointer">
                                                    Setujui
                                                </button>
                                            </form>
                                            <form action="{{ route(auth()->user()->role . '.peminjaman.tolak', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak peminjaman ini?')">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white rounded-xl text-xs font-bold transition shadow-sm whitespace-nowrap cursor-pointer">
                                                    Tolak
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @else
                                    <form action="{{ route('member.peminjaman.kembali', $peminjaman->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan peminjaman ini atas nama member?')">
                                        @csrf
                                        <button type="submit" class="px-3.5 py-1.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl text-xs font-bold transition shadow-sm whitespace-nowrap cursor-pointer">
                                            Kembalikan
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-10 w-10 text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-gray-400 text-xs font-medium">Tidak ada transaksi peminjaman aktif saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        @if($peminjamans->hasPages())
            <div class="p-4 border-t border-gray-50 bg-[#F8FAFC]/40 w-full clear-both">
                {{ $peminjamans->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timers = document.querySelectorAll('.countdown-timer');
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
        
        function updateTimers() {
            timers.forEach(timer => {
                const deadlineStr = timer.getAttribute('data-deadline');
                const id = timer.getAttribute('data-id');
                if (!deadlineStr || !id) return;
                
                const deadline = new Date(deadlineStr).getTime();
                const now = new Date().getTime();
                const diff = deadline - now;
                
                if (diff <= 0) {
                    // Waktu habis, ubah status ke Kadaluwarsa
                    const statusContainer = document.getElementById(`status-container-${id}`);
                    if (statusContainer) {
                        statusContainer.innerHTML = `
                            <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold text-rose-600 bg-rose-50 border border-rose-100 whitespace-nowrap">
                                Kadaluwarsa
                            </span>
                            <span class="text-[10px] font-semibold text-rose-500 mt-1">Waktu Pengambilan Habis</span>
                        `;
                    }
                    
                    const actionContainer = document.getElementById(`action-container-${id}`);
                    if (actionContainer) {
                        const tolakForm = actionContainer.querySelector('form[action$="/tolak"]');
                        let actionUrl = '';
                        if (tolakForm) {
                            actionUrl = tolakForm.getAttribute('action');
                        } else {
                            const role = "{{ auth()->user()->role }}";
                            actionUrl = `/${role}/peminjaman/${id}/tolak`;
                        }
                        
                        actionContainer.innerHTML = `
                            <form action="${actionUrl}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan kadaluwarsa ini?')">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <button type="submit" class="px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white rounded-xl text-xs font-bold transition shadow-sm whitespace-nowrap cursor-pointer">
                                    Batalkan (Kadaluwarsa)
                                </button>
                            </form>
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
                
                timer.textContent = `Sisa Waktu: ${hDisplay}:${mDisplay}:${sDisplay}`;
            });
        }
        
        updateTimers();
        setInterval(updateTimers, 1000);
    });
</script>
@endpush

