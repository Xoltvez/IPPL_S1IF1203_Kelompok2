@extends('layouts.app')

@section('title', 'Katalog')

@section('content')
<div class="w-full flex flex-col Box-border">

    <!-- Header Section -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#2F3951] tracking-tight">Katalog</h1>
            <p class="text-gray-500 text-sm mt-1 font-medium">Temukan berbagai koleksi buku terbaik untuk Anda baca.</p>
        </div>
    </div>

    <!-- Search & Filter Form -->
    <div x-data="{ openDetailSearch: false }" class="w-full mb-6">
        <form action="{{ route('member.katalog') }}" method="GET" class="w-full flex items-center gap-4 m-0">
            <!-- Search Input with Left Icon & Spacious Padding -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ $search }}"
                       placeholder="Cari buku yang kamu mau" 
                       class="w-full pl-12 pr-12 py-3 bg-[#F8FAFC] border border-gray-200 focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2] rounded-2xl text-sm transition-all outline-none">
                
                <!-- Filter Button Inside Input -->
                <button type="button" @click="openDetailSearch = true" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#4D9BE2] p-1.5 rounded-lg transition-colors cursor-pointer" title="Pencarian Detail">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                </button>
            </div>
            
            <!-- Retain category filter when searching -->
            @if($kategoriId)
                <input type="hidden" name="kategori" value="{{ $kategoriId }}">
            @endif

            <!-- Search Button -->
            <button type="submit" class="px-6 py-3 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-2xl shadow-sm text-sm font-bold transition-all transform active:scale-95 flex items-center justify-center whitespace-nowrap">
                Cari
            </button>
        </form>

        <!-- Detailed Search Modal -->
        <div x-show="openDetailSearch" 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             style="display: none;">
             
             <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-2xl w-full max-w-md overflow-hidden text-left"
                  @click.away="openDetailSearch = false">
                  
                  <!-- Modal Header -->
                  <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
                      <h3 class="text-base font-bold text-[#2F3951] dark:text-slate-100">Pencarian Detil Buku</h3>
                      <button type="button" @click="openDetailSearch = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-300 font-bold text-xl cursor-pointer">&times;</button>
                  </div>
                  
                  <!-- Modal Body / Form -->
                  <form action="{{ route('member.katalog') }}" method="GET" class="p-6 space-y-4">
                      <!-- Jenis Dropdown -->
                      <div>
                          <label for="jenis" class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Jenis</label>
                          <select name="jenis" id="jenis" class="w-full px-4 py-2.5 bg-[#F8FAFC] dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2] outline-none dark:text-slate-100">
                              <option value="">Pilih jenis buku</option>
                              <option value="Buku Fisik" {{ request('jenis') === 'Buku Fisik' ? 'selected' : '' }}>Buku Fisik</option>
                              <option value="E-Book Digital" {{ request('jenis') === 'E-Book Digital' ? 'selected' : '' }}>E-Book Digital</option>
                          </select>
                      </div>
                      
                      <!-- Judul -->
                      <div>
                          <label for="judul" class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Judul</label>
                          <input type="text" name="judul" id="judul" value="{{ request('judul') }}" placeholder="Judul buku" class="w-full px-4 py-2.5 bg-[#F8FAFC] dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2] outline-none dark:text-slate-100">
                      </div>
                      
                      <!-- Pengarang -->
                      <div>
                          <label for="pengarang" class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Pengarang</label>
                          <input type="text" name="pengarang" id="pengarang" value="{{ request('pengarang') }}" placeholder="Nama pengarang buku" class="w-full px-4 py-2.5 bg-[#F8FAFC] dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2] outline-none dark:text-slate-100">
                      </div>
                      
                      <!-- Penerbit -->
                      <div>
                          <label for="penerbit" class="block text-xs font-bold text-gray-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Penerbit</label>
                          <input type="text" name="penerbit" id="penerbit" value="{{ request('penerbit') }}" placeholder="Penerbit buku" class="w-full px-4 py-2.5 bg-[#F8FAFC] dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2] outline-none dark:text-slate-100">
                      </div>
                      
                      <!-- Action Buttons -->
                      <div class="flex items-center justify-end gap-3 pt-2">
                          <button type="button" @click="openDetailSearch = false" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 dark:bg-slate-800 dark:hover:bg-slate-750 text-gray-600 dark:text-slate-300 rounded-xl text-xs font-bold transition cursor-pointer">
                              Batal
                          </button>
                          <button type="submit" class="px-5 py-2.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl text-xs font-bold transition shadow-sm cursor-pointer">
                              Cari Buku
                          </button>
                      </div>
                  </form>
             </div>
        </div>
    </div>

    <!-- Category Pills (Horizontal Scrollable) -->
    <div class="flex items-center gap-3 overflow-x-auto pb-4 mb-6 no-scrollbar">
        <!-- Semua Pill -->
        <a href="{{ route('member.katalog', request()->only('search')) }}" 
           class="px-5 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ !$kategoriId ? 'bg-[#4D9BE2] text-white shadow-sm' : 'bg-[#F1F5F9] text-gray-500 hover:bg-gray-200/70' }}">
            Semua
        </a>
        
        <!-- Database Categories -->
        @foreach($categories as $category)
            <a href="{{ route('member.katalog', array_merge(request()->only('search'), ['kategori' => $category->id])) }}" 
               class="px-5 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ $kategoriId == $category->id ? 'bg-[#4D9BE2] text-white shadow-sm' : 'bg-[#F1F5F9] text-gray-500 hover:bg-gray-200/70' }}">
                {{ $category->nama_kategori }}
            </a>
        @endforeach
    </div>

    <!-- Books Grid -->
    @if($bukus->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($bukus as $buku)
                <div class="relative flex flex-col">
                    <a href="{{ route('buku.show', $buku->id) }}" class="relative block w-full aspect-[3/4] rounded-2xl overflow-hidden shadow-sm border border-gray-100 bg-[#FCFCFC] group">
                        
                        <!-- Hover Overlay with Title, Author & Star Ratings (Premium Polish) -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[#2F3951]/95 via-[#2F3951]/55 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 flex flex-col justify-end p-4 text-left">
                            <p class="text-white font-bold text-xs line-clamp-2">{{ $buku->judul }}</p>
                            <p class="text-gray-300 text-[10px] mt-1 truncate">{{ $buku->pengarang }}</p>
                            
                            <!-- Star rating display -->
                            <div class="flex items-center gap-0.5 mt-2 text-amber-400">
                                @for($j = 0; $j < 5; $j++)
                                    <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        
                        <!-- Availability Status Badge -->
                        @if($buku->stok > 0 && $buku->status == 'aktif')
                            <div class="absolute top-3 right-3 z-20 bg-emerald-500 text-white text-[8px] font-extrabold px-2 py-0.5 rounded uppercase tracking-wider shadow-sm">
                                Tersedia
                            </div>
                        @else
                            <div class="absolute top-3 right-3 z-20 bg-rose-500 text-white text-[8px] font-extrabold px-2 py-0.5 rounded uppercase tracking-wider shadow-sm">
                                Terpinjam
                            </div>
                        @endif

                        <!-- Book Cover Image -->
                        <img src="{{ $buku->cover_buku ? asset('storage/' . $buku->cover_buku) : 'https://placehold.co/300x400?text=' . urlencode($buku->judul) }}" 
                             alt="{{ $buku->judul }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Pagination Section -->
        @if($bukus->hasPages())
            <div class="mt-8">
                {{ $bukus->links('partials.pagination') }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-20 text-center text-gray-400">
            <div class="w-16 h-16 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-4 text-gray-300">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332 0.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 0.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332 0.477-4.5 1.253" />
                </svg>
            </div>
            <h3 class="text-sm font-bold text-[#2F3951] mb-1">Buku tidak ditemukan</h3>
            <p class="text-xs text-gray-400 max-w-xs leading-relaxed">Coba cari dengan kata kunci lain atau pilih kategori yang berbeda.</p>
            <a href="{{ route('member.katalog') }}" class="mt-5 px-5 py-2.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl font-bold text-xs shadow-sm transition">
                Reset Pencarian
            </a>
        </div>
    @endif

</div>

<style>
    /* Hide horizontal scrollbar for category pills container */
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>
@endsection
