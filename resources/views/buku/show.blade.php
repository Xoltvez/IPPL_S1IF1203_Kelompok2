@extends('layouts.app')

@section('title', $buku->judul)

@section('content')
<div class="w-full flex flex-col md:px-2 max-w-7xl mx-auto Box-border">

    <!-- Breadcrumbs -->
    <div class="mb-6 flex items-center gap-2 text-xs font-semibold text-gray-400 dark:text-slate-500">
        <a href="{{ route('dashboard') }}" class="hover:text-[#4D9BE2]">Beranda</a>
        <span>/</span>
        <span class="text-[#2F3951] dark:text-slate-300">{{ $buku->judul }}</span>
    </div>

    <!-- Main Container: 3 Column Layout -->
    <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left Column: Cover & Action (lg:col-span-3) -->
        <div class="lg:col-span-3 flex flex-col gap-4">
            <div class="bg-white dark:bg-slate-900 p-4 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-sm flex flex-col items-center gap-4 transition-colors duration-200">
                <div class="w-full aspect-[3/4] rounded-2xl overflow-hidden shadow-sm border border-gray-50 dark:border-slate-800 bg-[#FCFCFC] dark:bg-slate-950">
                    <img src="{{ $buku->cover_buku ? asset('storage/' . $buku->cover_buku) : 'https://placehold.co/300x400?text=' . urlencode($buku->judul) }}" 
                         alt="{{ $buku->judul }}" 
                         class="w-full h-full object-cover">
                </div>
                <form action="{{ route('buku.favorit.toggle', $buku->id) }}" method="POST" class="w-full m-0">
                    @csrf
                    @if($isFavorited)
                        <button type="submit" class="w-full py-2.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-all transform active:scale-95 shadow-sm">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                            </svg>
                            Tersimpan
                        </button>
                    @else
                        <button type="submit" class="w-full py-2.5 bg-[#F1F5F9] dark:bg-slate-800 hover:bg-[#E2E8F0] dark:hover:bg-slate-700 text-[#475569] dark:text-slate-300 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-all transform active:scale-95">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                            Simpan buku
                        </button>
                    @endif
                </form>
            </div>
        </div>

        <!-- Middle Column: Description & Synopsis (lg:col-span-6) -->
        <div class="lg:col-span-6 flex flex-col gap-6">
            <!-- Title Card -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-sm transition-colors duration-200">
                <h1 class="text-xl md:text-2xl font-extrabold text-[#2F3951] dark:text-slate-100 leading-tight">{{ $buku->judul }}</h1>
                <p class="text-xs md:text-sm text-gray-400 dark:text-slate-500 mt-2 font-medium">Oleh <span class="text-[#4D9BE2] font-semibold">{{ $buku->pengarang }}</span></p>
            </div>

            <!-- Description Card -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-sm transition-colors duration-200">
                <h3 class="text-xs font-bold text-[#2F3951] dark:text-slate-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-[#4D9BE2] rounded-full"></span>
                    Deskripsi
                </h3>
                <p class="text-sm text-gray-500 dark:text-slate-400 leading-relaxed whitespace-pre-line">
                    {{ $buku->deskripsi ?? 'Belum ada deskripsi lengkap untuk buku ini.' }}
                </p>
            </div>

            <!-- Synopsis Card -->
            <div class="bg-white dark:bg-slate-900 p-6 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-sm transition-colors duration-200">
                <h3 class="text-xs font-bold text-[#2F3951] dark:text-slate-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-[#4D9BE2] rounded-full"></span>
                    Sinopsis
                </h3>
                <p class="text-sm text-gray-500 dark:text-slate-400 leading-relaxed whitespace-pre-line">
                    {{ $buku->sinopsis ?? 'Belum ada sinopsis atau ringkasan cerita untuk buku ini.' }}
                </p>
            </div>
        </div>

        <!-- Right Column: Status, Specs & Reviews (lg:col-span-3) -->
        <div class="lg:col-span-3 flex flex-col gap-6">
            <!-- Status Card -->
            <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-sm transition-colors duration-200">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Status buku</span>
                    <span class="text-xs font-semibold text-gray-500 dark:text-slate-400">{{ $buku->stok }}/{{ $buku->stok + 2 }} buku</span>
                </div>
                
                @php
                    $availableStockForNewLoans = $buku->stok - $activeRequestsCount;
                @endphp

                @if($buku->kategori && strtolower($buku->kategori->nama_kategori) === 'e-book')
                    <div class="w-full py-2 bg-[#4D9BE2]/10 text-[#4D9BE2] rounded-xl font-bold text-xs text-center flex items-center justify-center gap-1.5 border border-[#4D9BE2]/20 mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#4D9BE2] animate-pulse"></span>
                        E-Book Digital
                    </div>
                    <a href="{{ route('buku.read', $buku->id) }}" class="block w-full py-3 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white text-center font-bold rounded-xl text-sm shadow transition-all transform active:scale-95">
                        Baca E-Book
                    </a>
                @elseif($availableStockForNewLoans > 0 && $buku->status == 'aktif')
                    <div class="w-full py-2 bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 rounded-xl font-bold text-xs text-center flex items-center justify-center gap-1.5 border border-emerald-100 dark:border-emerald-900/50 mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Tersedia
                    </div>
                    
                    <form action="{{ route('buku.pinjam', $buku->id) }}" method="POST" class="m-0 space-y-3.5">
                        @csrf
                        <div class="text-left">
                            <label for="durasi" class="block text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-1.5">Durasi Peminjaman</label>
                            <select name="durasi" id="durasi" class="w-full px-3.5 py-2.5 bg-[#F8FAFC] dark:bg-slate-800 border border-gray-200 dark:border-slate-700 focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2] rounded-xl text-xs font-bold text-[#2F3951] dark:text-slate-100 transition-all outline-none cursor-pointer">
                                <option value="1">1 Hari</option>
                                <option value="2">2 Hari</option>
                                <option value="3">3 Hari</option>
                                <option value="4">4 Hari</option>
                                <option value="5">5 Hari</option>
                                <option value="6">6 Hari</option>
                                <option value="7" selected>7 Hari (Maksimal)</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full py-3 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl font-bold text-sm shadow transition-all transform active:scale-95">
                            Pinjam buku
                        </button>
                    </form>
                @else
                    <div class="w-full py-2 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-xl font-bold text-xs text-center flex items-center justify-center gap-1.5 border border-rose-100 dark:border-rose-900/50 mb-4">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                        Terpinjam
                    </div>

                    @if($isReserved)
                        <button disabled class="w-full py-3 bg-gray-100 dark:bg-slate-800 text-gray-400 dark:text-slate-500 rounded-xl font-bold text-sm cursor-not-allowed border border-gray-200 dark:border-slate-700">
                            Sudah Direservasi
                        </button>
                    @else
                        <form action="{{ route('buku.reservasi', $buku->id) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="w-full py-3 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl font-bold text-sm shadow transition-all transform active:scale-95">
                                Reservasi Buku
                            </button>
                        </form>
                    @endif
                @endif
            </div>

            <!-- Detail Spesifikasi Card -->
            <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-sm transition-colors duration-200">
                <h3 class="text-xs font-bold text-[#2F3951] dark:text-slate-200 uppercase tracking-wider mb-4">Detail buku</h3>
                <div class="grid grid-cols-2 gap-y-4 gap-x-2 text-xs">
                    <div>
                        <p class="text-gray-400 dark:text-slate-500 font-medium">Penerbit</p>
                        <p class="font-bold text-[#2F3951] dark:text-slate-300 mt-0.5">{{ $buku->penerbit ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 dark:text-slate-500 font-medium">Tanggal Terbit</p>
                        <p class="font-bold text-[#2F3951] dark:text-slate-300 mt-0.5">
                            {{ $buku->tanggal_terbit ? \Carbon\Carbon::parse($buku->tanggal_terbit)->format('d M Y') : $buku->tahun_terbit }}
                        </p>
                    </div>
                    
                    <div class="col-span-2 border-t border-gray-50 dark:border-slate-800 my-1"></div>
                    
                    <div>
                        <p class="text-gray-400 dark:text-slate-500 font-medium">ISBN</p>
                        <p class="font-bold text-[#2F3951] dark:text-slate-300 mt-0.5">{{ $buku->isbn }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 dark:text-slate-500 font-medium">Halaman</p>
                        <p class="font-bold text-[#2F3951] dark:text-slate-300 mt-0.5">{{ $buku->halaman ?? '-' }}</p>
                    </div>
                    
                    <div class="col-span-2 border-t border-gray-50 dark:border-slate-800 my-1"></div>
                    
                    <div>
                        <p class="text-gray-400 dark:text-slate-500 font-medium">Bahasa</p>
                        <p class="font-bold text-[#2F3951] dark:text-slate-300 mt-0.5">{{ $buku->bahasa ?? 'Indonesia' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 dark:text-slate-500 font-medium">Panjang</p>
                        <p class="font-bold text-[#2F3951] dark:text-slate-300 mt-0.5">{{ $buku->panjang ?? '-' }}</p>
                    </div>
                    
                    <div class="col-span-2 border-t border-gray-50 dark:border-slate-800 my-1"></div>
                    
                    <div>
                        <p class="text-gray-400 dark:text-slate-500 font-medium">Lebar</p>
                        <p class="font-bold text-[#2F3951] dark:text-slate-300 mt-0.5">{{ $buku->lebar ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 dark:text-slate-500 font-medium">Berat</p>
                        <p class="font-bold text-[#2F3951] dark:text-slate-300 mt-0.5">{{ $buku->berat ?? '-' }}</p>
                    </div>
                    
                    <div class="col-span-2 border-t border-gray-50 dark:border-slate-800 my-1"></div>
                    
                    <div>
                        <p class="text-gray-400 dark:text-slate-500 font-medium">Penulis</p>
                        <p class="font-bold text-[#2F3951] dark:text-slate-300 mt-0.5">{{ $buku->pengarang }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 dark:text-slate-500 font-medium">Jenis</p>
                        <p class="font-bold text-[#2F3951] dark:text-slate-300 mt-0.5">{{ $buku->jenis ?? 'Buku Fisik' }}</p>
                    </div>
                </div>
            </div>

            <!-- Ulasan Buku Card -->
            <div class="bg-white dark:bg-slate-900 p-5 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-sm transition-colors duration-200" x-data="{ showReviewModal: false, showAllReviewsModal: false, selectedRating: 5 }">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-bold text-[#2F3951] dark:text-slate-200 uppercase tracking-wider">Ulasan buku</h3>
                    <span class="text-[9px] font-semibold text-gray-400 dark:text-slate-500">{{ $totalReviews }} ulasan</span>
                </div>
                
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-amber-400">
                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </span>
                    <span class="text-lg font-black text-[#2F3951] dark:text-slate-100">{{ $rating }}</span>
                    <span class="text-xs text-gray-400 dark:text-slate-500 font-medium">/5</span>
                </div>
                
                <div class="space-y-4 max-h-[400px] overflow-y-auto pr-1 no-scrollbar">
                    @foreach(array_slice($reviews, 0, 2) as $rev)
                        <div class="p-3 bg-[#F8FAFC]/60 dark:bg-slate-800/40 rounded-2xl border border-gray-100 dark:border-slate-800/80">
                            <div class="flex items-center justify-between mb-1.5">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-[#4D9BE2]/10 dark:bg-[#4D9BE2]/20 flex items-center justify-center text-[10px] font-extrabold text-[#4D9BE2] dark:text-[#5fa8eb]">
                                        {{ substr($rev['nama'], 0, 2) }}
                                    </div>
                                    <span class="text-xs font-bold text-[#2F3951] dark:text-slate-300">{{ $rev['nama'] }}</span>
                                </div>
                                <span class="text-[9px] text-gray-400 dark:text-slate-500">{{ $rev['tanggal'] }}</span>
                            </div>
                            
                            <!-- Star Rating -->
                            <div class="flex items-center gap-0.5 mb-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3.5 h-3.5 {{ $i <= $rev['rating'] ? 'text-amber-400 fill-current' : 'text-gray-300 dark:text-slate-700' }}" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            
                            <p class="text-xs text-gray-500 dark:text-slate-400 leading-relaxed">{{ $rev['komentar'] }}</p>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4 flex flex-col gap-2">
                    <button @click="showReviewModal = true" class="w-full py-2.5 bg-[#4D9BE2]/10 hover:bg-[#4D9BE2]/20 dark:bg-[#4D9BE2]/20 dark:hover:bg-[#4D9BE2]/30 text-[#4D9BE2] dark:text-[#5fa8eb] rounded-xl font-bold text-xs transition cursor-pointer">
                        Beri ulasan
                    </button>
                    <button @click="showAllReviewsModal = true" class="w-full py-2 text-gray-400 dark:text-slate-500 hover:text-gray-500 rounded-xl font-bold text-xs transition cursor-pointer">
                        Lihat semua ulasan
                    </button>
                </div>

                <!-- Review Modal Overlay -->
                <div x-show="showReviewModal" 
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     style="display: none;">
                     
                    <!-- Modal Card -->
                    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 w-full max-w-md rounded-3xl p-6 shadow-xl relative text-left"
                         @click.away="showReviewModal = false"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4 scale-95"
                         x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 transform translate-y-4 scale-95">
                         
                        <h3 class="text-sm font-bold text-[#2F3951] dark:text-slate-100 uppercase tracking-wider mb-2">Beri Ulasan Buku</h3>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mb-5">Bagikan pengalaman Anda setelah membaca buku <strong>{{ $buku->judul }}</strong>.</p>
                        
                        <form action="{{ route('buku.ulasan.store', $buku->id) }}" method="POST" class="space-y-4 m-0">
                            @csrf
                            <input type="hidden" name="rating" :value="selectedRating">
                            
                            <!-- Rating Stars Selector -->
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Penilaian Anda</label>
                                <div class="flex items-center gap-1">
                                    <template x-for="star in 5">
                                        <button type="button" @click="selectedRating = star" class="text-gray-300 dark:text-slate-700 hover:scale-110 transition-transform duration-150 focus:outline-none cursor-pointer">
                                            <svg class="w-8 h-8" :class="star <= selectedRating ? 'text-amber-400 fill-current' : 'text-gray-300 dark:text-slate-700'" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            
                            <!-- Comment Textarea -->
                            <div class="flex flex-col gap-1.5 text-left">
                                <label for="komentar" class="text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider">Komentar / Ulasan</label>
                                <textarea name="komentar" id="komentar" rows="4" required placeholder="Tuliskan ulasan jujur Anda tentang isi buku ini..." class="w-full px-3.5 py-2.5 bg-[#F8FAFC] dark:bg-slate-800 border border-gray-200 dark:border-slate-700 focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2] rounded-xl text-xs text-[#2F3951] dark:text-slate-100 placeholder-gray-400 focus:outline-none transition-all"></textarea>
                            </div>
                            
                            <!-- Modal Footer Buttons -->
                            <div class="flex items-center justify-end gap-3 pt-2">
                                <button type="button" @click="showReviewModal = false" class="px-4 py-2 text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 text-xs font-bold rounded-xl transition">
                                    Batal
                                </button>
                                <button type="submit" class="px-5 py-2.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white text-xs font-bold rounded-xl shadow-sm transition">
                                    Kirim Ulasan
                                </button>
                            </div>
                        </form>
                        
                    </div>
                </div>

                <!-- All Reviews Modal Overlay -->
                <div x-show="showAllReviewsModal" 
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     style="display: none;">
                     
                    <!-- Modal Card -->
                    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 w-full max-w-lg rounded-3xl p-6 shadow-xl relative text-left"
                         @click.away="showAllReviewsModal = false"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform translate-y-4 scale-95"
                         x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 transform translate-y-4 scale-95">
                         
                        <div class="flex items-center justify-between mb-4 border-b border-gray-100 dark:border-slate-800 pb-3">
                            <h3 class="text-sm font-bold text-[#2F3951] dark:text-slate-100 uppercase tracking-wider">Semua Ulasan</h3>
                            <span class="text-xs font-semibold text-gray-400 dark:text-slate-500">{{ $totalReviews }} ulasan</span>
                        </div>
                        
                        <!-- List of all reviews -->
                        <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 no-scrollbar">
                            @foreach($reviews as $rev)
                                <div class="p-3 bg-[#F8FAFC]/60 dark:bg-slate-800/40 rounded-2xl border border-gray-100 dark:border-slate-800/80">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-[#4D9BE2]/10 dark:bg-[#4D9BE2]/20 flex items-center justify-center text-[10px] font-extrabold text-[#4D9BE2] dark:text-[#5fa8eb]">
                                                {{ substr($rev['nama'], 0, 2) }}
                                            </div>
                                            <span class="text-xs font-bold text-[#2F3951] dark:text-slate-300">{{ $rev['nama'] }}</span>
                                        </div>
                                        <span class="text-[9px] text-gray-400 dark:text-slate-500">{{ $rev['tanggal'] }}</span>
                                    </div>
                                    
                                    <!-- Star Rating -->
                                    <div class="flex items-center gap-0.5 mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $rev['rating'] ? 'text-amber-400 fill-current' : 'text-gray-300 dark:text-slate-700' }}" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                    
                                    <p class="text-xs text-gray-500 dark:text-slate-400 leading-relaxed">{{ $rev['komentar'] }}</p>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Modal Footer Close -->
                        <div class="flex items-center justify-end pt-4 border-t border-gray-100 dark:border-slate-800 mt-4">
                            <button type="button" @click="showAllReviewsModal = false" class="px-5 py-2.5 bg-[#F1F5F9] dark:bg-slate-800 hover:bg-[#E2E8F0] dark:hover:bg-slate-700 text-[#475569] dark:text-slate-300 text-xs font-bold rounded-xl transition cursor-pointer">
                                Tutup
                            </button>
                        </div>
                        
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
