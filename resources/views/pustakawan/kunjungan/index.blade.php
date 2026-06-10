@extends('layouts.app')

@section('title', 'Presensi Kunjungan Perpustakaan')

@section('content')
<div class="block w-full text-left clear-both">

    <!-- Header Section -->
    <div class="w-full bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-[#2F3951] dark:text-slate-100 tracking-tight">Presensi Kunjungan Offline</h2>
                <p class="text-gray-400 dark:text-slate-400 text-xs mt-1">Gunakan pemindai QR Code untuk mencatat kedatangan anggota perpustakaan secara offline.</p>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-bold bg-[#4D9BE2]/10 text-[#4D9BE2] whitespace-nowrap">
                    <span class="w-2 h-2 rounded-full bg-[#4D9BE2] animate-pulse"></span>
                    {{ $kunjungans->total() }} Anggota Hadir Hari Ini
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left Side: Scanner Input -->
        <div class="lg:col-span-5 bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm">
            <h3 class="text-sm font-bold text-[#2F3951] dark:text-slate-200 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#4D9BE2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 20h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2m-4-8a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Pemindaian Kartu Anggota
            </h3>

            <!-- Scan Instruction & Input -->
            <div class="space-y-4">
                <p class="text-xs text-gray-400 dark:text-slate-400 leading-relaxed">
                    Pindai QR Code menggunakan kamera web komputer pustakawan atau menggunakan alat pemindai (scanner) fisik.
                </p>

                <!-- Camera Scanner Toggle & Reader -->
                <div class="space-y-3">
                    <button type="button" id="start-camera-btn" class="inline-flex items-center justify-center gap-2 w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl text-xs font-bold transition shadow-sm cursor-pointer transform active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                        Aktifkan Kamera Scan
                    </button>
                    <button type="button" id="stop-camera-btn" style="display: none;" class="inline-flex items-center justify-center gap-2 w-full py-3 bg-rose-500 hover:bg-rose-600 text-white rounded-2xl text-xs font-bold transition shadow-sm cursor-pointer transform active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Matikan Kamera
                    </button>

                    <div id="reader" style="display: none;" class="w-full aspect-[4/3] rounded-2xl overflow-hidden border border-gray-100 dark:border-slate-800 bg-black relative"></div>
                </div>

                <form action="{{ route(auth()->user()->role . '.kunjungan.store') }}" method="POST" class="m-0 space-y-4" id="scanForm">
                    @csrf
                    <div class="relative flex items-center">
                        <span class="absolute left-4 text-gray-400 flex items-center pointer-events-none">
                            <!-- SVG QR/Barcode scanner style icon -->
                            <svg class="w-5 h-5 text-[#4D9BE2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 20h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2" />
                            </svg>
                        </span>
                        <input type="text" name="member_id" id="member_id_input" autofocus required autocomplete="off"
                               placeholder="Scan QR Code / Masukkan ID Member..."
                               class="w-full pl-12 pr-4 py-3.5 bg-[#F8FAFC]/60 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 focus:border-[#4D9BE2]/50 focus:bg-white dark:focus:bg-slate-900 rounded-2xl text-sm font-semibold text-[#2F3951] dark:text-slate-100 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-2xl text-xs font-bold transition shadow-sm cursor-pointer transform active:scale-[0.98]">
                        Check-in Kunjungan
                    </button>
                </form>

                <div class="flex items-center gap-2 px-4 py-3 bg-[#F3F7FB] dark:bg-slate-800/50 rounded-2xl border border-gray-50 dark:border-slate-800">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></div>
                    <span class="text-[10px] text-gray-500 dark:text-slate-400 font-bold">Sistem Siap Menerima Scan</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Visitor History (Today) -->
        <div class="lg:col-span-7 bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#2F3951] dark:text-slate-200">Log Pengunjung Hari Ini</h3>
                
                <!-- Search bar inside table log -->
                <form action="{{ route(auth()->user()->role . '.kunjungan.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama/email/ID..." class="px-3.5 py-1.5 bg-[#F8FAFC]/80 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 focus:outline-none focus:border-[#4D9BE2] rounded-xl text-xs text-[#2F3951] dark:text-slate-100 placeholder-gray-400">
                    <button type="submit" class="px-3 py-1.5 bg-gray-100 dark:bg-slate-850 hover:bg-gray-200 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 font-bold rounded-xl text-[10px] transition cursor-pointer">
                        Cari
                    </button>
                    @if($search)
                        <a href="{{ route(auth()->user()->role . '.kunjungan.index') }}" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl text-[10px] font-bold transition">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F8FAFC] dark:bg-slate-800/50 border-b border-gray-150 dark:border-slate-800">
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[10%]">No</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest w-[40%]">Anggota</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest w-[25%]">ID Anggota</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center w-[25%]">Waktu Hadir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-slate-800 text-sm text-[#2F3951] dark:text-slate-300">
                        @forelse($kunjungans as $kunjungan)
                        <tr class="hover:bg-[#F8FAFC]/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4 text-center font-medium text-gray-400 dark:text-slate-500">
                                {{ $loop->iteration + ($kunjungans->firstItem() - 1) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-[#4D9BE2]/10 flex items-center justify-center text-[#4D9BE2] font-bold text-xs flex-shrink-0">
                                        @if($kunjungan->user && $kunjungan->user->foto)
                                            <img src="{{ asset('storage/' . $kunjungan->user->foto) }}" alt="Avatar" class="w-full h-full object-cover rounded-full">
                                        @else
                                            {{ strtoupper(substr($kunjungan->user->name ?? 'M', 0, 2)) }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-[#2F3951] dark:text-slate-200 truncate">{{ $kunjungan->user->name ?? 'Tamu/Deleted User' }}</p>
                                        <p class="text-[10px] text-gray-400 dark:text-slate-500 truncate">{{ $kunjungan->user->email ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-semibold text-gray-600 dark:text-slate-400">
                                #MBR-{{ str_pad($kunjungan->user_id, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-6 py-4 text-center text-gray-500 dark:text-slate-400 font-medium">
                                {{ $kunjungan->created_at->format('H:i:s') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-10 w-10 text-gray-300 dark:text-slate-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <p class="text-gray-400 dark:text-slate-500 text-xs font-medium">Belum ada anggota yang check-in hari ini.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination block -->
            @if($kunjungans->hasPages())
                <div class="p-4 border-t border-gray-50 dark:border-slate-800 bg-[#F8FAFC]/40 dark:bg-slate-900/30">
                    {{ $kunjungans->links() }}
                </div>
            @endif
        </div>

    </div>

</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('member_id_input');
        
        // Auto-focus input field
        if (input) {
            input.focus();
            
            // Keep focus even if clicked outside, so scanner is always ready
            document.addEventListener('click', function(e) {
                if (e.target.closest('#start-camera-btn') || e.target.closest('#stop-camera-btn') || e.target.closest('#reader')) {
                    return;
                }
                input.focus();
            });
        }

        // Camera QR Code scanner logic
        const startBtn = document.getElementById('start-camera-btn');
        const stopBtn = document.getElementById('stop-camera-btn');
        const reader = document.getElementById('reader');
        
        let html5QrCode = null;
        const config = { fps: 10, qrbox: { width: 220, height: 220 } };

        function playBeep() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);

                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(880, audioCtx.currentTime); // A5 note
                gainNode.gain.setValueAtTime(0.1, audioCtx.currentTime);

                oscillator.start();
                oscillator.stop(audioCtx.currentTime + 0.12);
            } catch (e) {
                console.error("Audio Context failed: ", e);
            }
        }

        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            playBeep();
            
            // Put decoded text in input
            if (input) {
                input.value = decodedText;
            }

            // Stop camera scan and submit form
            stopScanner().then(() => {
                document.getElementById('scanForm').submit();
            }).catch(() => {
                document.getElementById('scanForm').submit();
            });
        };

        function startScanner() {
            reader.style.display = 'block';
            startBtn.style.display = 'none';
            stopBtn.style.display = 'inline-flex';

            html5QrCode = new Html5Qrcode("reader");
            return html5QrCode.start(
                { facingMode: "user" }, 
                config, 
                qrCodeSuccessCallback
            );
        }

        function stopScanner() {
            if (html5QrCode && html5QrCode.isScanning) {
                return html5QrCode.stop().then(() => {
                    reader.style.display = 'none';
                    startBtn.style.display = 'inline-flex';
                    stopBtn.style.display = 'none';
                });
            }
            return Promise.resolve();
        }

        if (startBtn && stopBtn) {
            startBtn.addEventListener('click', function() {
                startScanner().catch((err) => {
                    console.error("Gagal start camera: ", err);
                    alert("Kamera gagal diakses. Pastikan izin kamera sudah diberikan.");
                    reader.style.display = 'none';
                    startBtn.style.display = 'inline-flex';
                    stopBtn.style.display = 'none';
                });
            });

            stopBtn.addEventListener('click', function() {
                stopScanner().catch((err) => {
                    console.error("Gagal stop camera: ", err);
                });
            });
        }
    });
</script>
@endpush
@endsection
