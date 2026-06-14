@extends('layouts.app')

@section('title', __('Buku Tersimpan'))

@section('content')
<div class="w-full flex flex-col Box-border">

    <!-- Header Section -->
    <div class="mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#2F3951] dark:text-slate-100 tracking-tight">{{ __('Buku Tersimpan') }}</h1>
            <p class="text-gray-500 dark:text-slate-400 text-sm mt-1 font-medium">{{ __('Koleksi buku favorit yang telah Anda tandai.') }}</p>
        </div>
    </div>

    <!-- Search, Filter, Count & Hapus Semua Row (Mockup Header) -->
    <div class="w-full flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4 mb-6">
        <!-- Search & Filter Form (Left & Center) -->
        <form action="{{ route('member.tersimpan') }}" method="GET" class="flex-1 flex items-center gap-4 m-0">
            <!-- Search Input with Left Icon & Spacious Padding -->
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" 
                       id="search-input"
                       name="search" 
                       value="{{ $search }}"
                       placeholder="{{ __('Cari buku yang kamu mau') }}" 
                       class="w-full pl-12 pr-4 py-3 bg-[#F8FAFC] dark:bg-slate-800 border border-gray-200 dark:border-slate-700/50 focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2] rounded-2xl text-sm transition-all outline-none dark:text-slate-100">
            </div>
            
            @if($kategoriId)
                <input type="hidden" name="kategori" value="{{ $kategoriId }}">
            @endif

            <!-- Search Button -->
            <button type="submit" class="px-6 py-3 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-2xl shadow-sm text-sm font-bold transition-all transform active:scale-95 flex items-center justify-center whitespace-nowrap">
                {{ __('Cari') }}
            </button>
        </form>

        <!-- Right Side Controls Container -->
        <div class="flex items-center gap-4 flex-wrap md:flex-nowrap">
            <!-- Count Pill Card -->
            <div class="bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700/50 rounded-2xl px-4 py-2 flex items-center gap-3 shadow-sm flex-shrink-0 min-w-[120px]" id="count-pill-container">
                <div class="text-[#4D9BE2]">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                    </svg>
                </div>
                <div class="leading-tight text-left">
                    <p class="text-[9px] font-bold text-gray-400 dark:text-slate-400 uppercase tracking-wider">{{ __('Buku tersimpan') }}</p>
                    <p class="text-sm font-black text-[#2F3951] dark:text-slate-100">{{ $bukuFavorit->total() }}</p>
                </div>
            </div>

            <!-- Hapus Semua Action Button -->
            @if($bukuFavorit->total() > 0)
                <form action="{{ route('member.tersimpan.clear') }}" method="POST" onsubmit="return confirm('{{ __('Apakah Anda yakin ingin menghapus semua buku dari daftar tersimpan?') }}')" class="m-0 flex-shrink-0">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="flex items-center gap-2 px-5 py-3 bg-[#EF4444] hover:bg-[#DC2626] text-white rounded-2xl shadow-sm text-xs font-extrabold transition-all transform active:scale-95">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        {{ __('Hapus semua') }}
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Category Pills (Horizontal Scrollable) -->
    <div class="flex items-center gap-3 overflow-x-auto pb-4 mb-6 no-scrollbar" id="category-pills-container">
        <!-- Semua Pill -->
        <a href="{{ route('member.tersimpan', request()->only('search')) }}" 
           class="px-5 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ !$kategoriId ? 'bg-[#4D9BE2] text-white shadow-sm' : 'bg-[#F1F5F9] dark:bg-slate-800 text-gray-500 dark:text-slate-400 hover:bg-gray-200/70 dark:hover:bg-slate-700' }}">
            {{ __('Semua') }}
        </a>
        
        <!-- Database Categories -->
        @foreach($categories as $category)
            <a href="{{ route('member.tersimpan', array_merge(request()->only('search'), ['kategori' => $category->id])) }}" 
               class="px-5 py-2.5 rounded-xl text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ $kategoriId == $category->id ? 'bg-[#4D9BE2] text-white shadow-sm' : 'bg-[#F1F5F9] dark:bg-slate-800 text-gray-500 dark:text-slate-400 hover:bg-gray-200/70 dark:hover:bg-slate-700' }}">
                {{ $category->nama_kategori }}
            </a>
        @endforeach
    </div>

    <!-- Saved Books Grid (Cover-Only Cards) -->
    <div id="books-grid-container" class="w-full">
    @if($bukuFavorit->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
            @foreach($bukuFavorit as $buku)
                <div class="relative flex flex-col">
                    <div class="relative block w-full aspect-[3/4] rounded-2xl overflow-hidden shadow-sm border border-gray-100 dark:border-slate-700 bg-[#FCFCFC] dark:bg-slate-900 group">
                        
                        <!-- Hover Overlay with Title, Author & Quick Remove (Premium Polish) -->
                        <div class="absolute inset-0 bg-gradient-to-t from-[#2F3951]/95 via-[#2F3951]/55 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 flex flex-col justify-end p-4 text-left">
                            <a href="{{ route('buku.show', $buku->id) }}" class="text-white font-bold text-xs line-clamp-2 hover:text-[#4D9BE2] transition-colors">
                                {{ $buku->judul }}
                            </a>
                            <p class="text-gray-300 text-[10px] mt-1 truncate">{{ $buku->pengarang }}</p>
                            
                            <!-- Star rating display -->
                            <div class="flex items-center gap-0.5 mt-2 text-amber-400">
                                @for($j = 0; $j < 5; $j++)
                                    <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            
                            <!-- Quick Delete Action -->
                            <form action="{{ route('buku.favorit.toggle', $buku->id) }}" method="POST" class="mt-3 m-0">
                                @csrf
                                <button type="submit" class="w-full py-1.5 bg-[#EF4444] hover:bg-red-600 text-white rounded-xl font-bold text-[9px] flex items-center justify-center gap-1 transition-all transform active:scale-95">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    {{ __('Hapus') }}
                                </button>
                            </form>
                        </div>

                        <!-- Book Cover Image Link -->
                        <a href="{{ route('buku.show', $buku->id) }}" class="block w-full h-full">
                            <img src="{{ $buku->cover_buku ? asset('storage/' . $buku->cover_buku) : 'https://placehold.co/300x400?text=' . urlencode($buku->judul) }}" 
                                 alt="{{ $buku->judul }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Custom Pagination Slider -->
        @if($bukuFavorit->hasPages())
            <div class="mt-8">
                {{ $bukuFavorit->links('partials.pagination') }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center py-20 text-center text-gray-400 dark:text-slate-500">
            <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center justify-center mb-4 text-gray-300 dark:text-slate-650">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                </svg>
            </div>
            <h3 class="text-sm font-bold text-[#2F3951] dark:text-slate-200 mb-1">{{ __('Belum ada buku tersimpan') }}</h3>
            <p class="text-xs text-gray-400 dark:text-slate-500 max-w-xs leading-relaxed">{{ __('Pilih kategori atau gunakan pencarian untuk mencari buku favorit Anda.') }}</p>
            <a href="{{ route('member.katalog') }}" class="mt-5 px-5 py-2.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl font-bold text-xs shadow-sm transition">
                {{ __('Buka Katalog Buku') }}
            </a>
        </div>
    @endif
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchForm = searchInput ? searchInput.closest('form') : null;
    const gridContainer = document.getElementById('books-grid-container');
    const categoryContainer = document.getElementById('category-pills-container');
    const countContainer = document.getElementById('count-pill-container');
    
    if (!searchInput || !searchForm) return;

    let debounceTimeout;

    function fetchResults() {
        const formData = new FormData(searchForm);
        const params = new URLSearchParams(formData);
        const url = `${searchForm.action}?${params.toString()}`;

        if (gridContainer) {
            gridContainer.style.opacity = '0.6';
            gridContainer.style.transition = 'opacity 0.15s ease';
        }

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            const newGrid = doc.getElementById('books-grid-container');
            const newCategories = doc.getElementById('category-pills-container');
            const newCount = doc.getElementById('count-pill-container');
            
            if (newGrid && gridContainer) {
                gridContainer.innerHTML = newGrid.innerHTML;
                gridContainer.style.opacity = '1';
            }
            if (newCategories && categoryContainer) {
                categoryContainer.innerHTML = newCategories.innerHTML;
            }
            if (newCount && countContainer) {
                countContainer.innerHTML = newCount.innerHTML;
            }

            window.history.replaceState(null, '', url);
        })
        .catch(error => {
            console.error('Search error:', error);
            if (gridContainer) gridContainer.style.opacity = '1';
        });
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(fetchResults, 300);
    });

    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        clearTimeout(debounceTimeout);
        fetchResults();
    });
});
</script>

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
