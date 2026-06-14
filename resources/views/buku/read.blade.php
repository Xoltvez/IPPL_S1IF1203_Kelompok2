@extends('layouts.app')

@section('title', __('Membaca') . ': ' . $buku->judul)

@section('content')
<div class="w-full flex flex-col md:px-2 max-w-7xl mx-auto box-border">

    {{-- BREADCRUMBS --}}
    <div class="mb-6 flex items-center gap-2 text-xs font-semibold text-gray-400 dark:text-slate-500">
        <a href="{{ route('dashboard') }}" class="hover:text-[#4D9BE2]">{{ __('Beranda') }}</a>
        <span>/</span>
        <a href="{{ route('buku.show', $buku->id) }}" class="hover:text-[#4D9BE2] truncate max-w-[200px]">{{ $buku->judul }}</a>
        <span>/</span>
        <span class="text-[#2F3951] dark:text-slate-300">E-Book</span>
    </div>

    {{-- TWO COLUMN READER GRID --}}
    <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-6 items-start text-left">
        
        {{-- LEFT COLUMN: READER CONTENT (lg:col-span-8) --}}
        <div class="lg:col-span-8 flex flex-col gap-6">
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-sm p-6 md:p-8 flex flex-col gap-8 transition-colors duration-200">
                
                {{-- HEADER BAR --}}
                <div class="flex items-center justify-between border-b border-gray-100 dark:border-slate-800 pb-4">
                    <a href="{{ route('buku.show', $buku->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-slate-800 text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-xl text-xs font-bold transition-all">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>{{ __('Kembali') }}</span>
                    </a>
                    
                    <h2 class="text-sm md:text-base font-extrabold text-[#2F3951] dark:text-slate-100 truncate px-2 max-w-[280px] md:max-w-[380px]">{{ $buku->judul }}</h2>
                    
                    <span class="text-xs font-bold text-gray-400 dark:text-slate-500 bg-gray-50 dark:bg-slate-800/50 px-3 py-1.5 rounded-lg whitespace-nowrap">
                        {{ __('Halaman') }} {{ $page }} / {{ $totalPages }}
                    </span>
                </div>

                {{-- READER TEXT BODY --}}
                <div class="flex-1 py-4 px-2 md:px-6 min-h-[380px] select-text">
                    <p class="text-base md:text-lg text-gray-700 dark:text-slate-200 leading-loose whitespace-pre-line tracking-wide">
                        {{ $content }}
                    </p>
                </div>

                {{-- FOOTER NAVIGATION BAR --}}
                <div class="flex items-center justify-between border-t border-gray-100 dark:border-slate-800 pt-5">
                    {{-- Previous Button --}}
                    @if($page > 1)
                        <a href="{{ route('buku.read', ['id' => $buku->id, 'page' => $page - 1]) }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#4D9BE2] hover:text-[#3D8BCF] transition-all">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>{{ __('Kembali') }}</span>
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 text-xs font-bold text-gray-300 dark:text-slate-700 cursor-not-allowed">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                            </svg>
                            <span>{{ __('Kembali') }}</span>
                        </span>
                    @endif
 
                    {{-- Bookmark Button --}}
                    <form action="{{ route('buku.bookmark', $buku->id) }}" method="POST" class="m-0">
                        @csrf
                        <input type="hidden" name="page_number" value="{{ $page }}">
                        @if($isBookmarked)
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-[#4D9BE2]/10 text-[#4D9BE2] rounded-xl text-xs font-extrabold transition-all hover:bg-[#4D9BE2]/15 cursor-pointer">
                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                                </svg>
                                <span>{{ __('Halaman Ditandai') }}</span>
                            </button>
                        @else
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-gray-50 dark:bg-slate-800 text-gray-500 dark:text-slate-300 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2] rounded-xl text-xs font-extrabold transition-all hover:bg-gray-100 dark:hover:bg-slate-700 cursor-pointer">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 5c0-1.1.9-2 2-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                                <span>{{ __('Tandai Halaman') }}</span>
                            </button>
                        @endif
                    </form>
 
                    {{-- Next Button --}}
                    @if($page < $totalPages)
                        <a href="{{ route('buku.read', ['id' => $buku->id, 'page' => $page + 1]) }}" class="inline-flex items-center gap-2 text-xs font-bold text-[#4D9BE2] hover:text-[#3D8BCF] transition-all">
                            <span>{{ __('Selanjutnya') }}</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <span class="inline-flex items-center gap-2 text-xs font-bold text-gray-300 dark:text-slate-700 cursor-not-allowed">
                            <span>{{ __('Selanjutnya') }}</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </span>
                    @endif
                </div>

            </div>
        </div>

        {{-- RIGHT COLUMN: CONTROL PANEL & TOC (lg:col-span-4) --}}
        <div class="lg:col-span-4 flex flex-col gap-6">
            
            {{-- DAFTAR ISI (TOC) --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-sm p-6 transition-colors duration-200">
                <h3 class="text-xs font-bold text-[#2F3951] dark:text-slate-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-[#4D9BE2] rounded-full"></span>
                    {{ __('Daftar Isi') }}
                </h3>
                
                <div class="space-y-1.5 max-h-[300px] overflow-y-auto custom-scrollbar pr-1">
                    @foreach($chapters as $ch)
                        @php
                            $isCurrentChapter = ($activeChapter && $activeChapter['index'] === $ch['index']);
                        @endphp
                        <a href="{{ route('buku.read', ['id' => $buku->id, 'page' => $ch['start_page']]) }}" 
                           class="flex items-center justify-between px-3 py-2 rounded-xl transition-all text-xs
                           {{ $isCurrentChapter 
                              ? 'bg-[#F3F7FB] dark:bg-blue-950/30 text-[#4D9BE2] font-bold border-l-3 border-[#4D9BE2]' 
                              : 'text-gray-500 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800' }}">
                            <span class="truncate max-w-[210px]">{{ $ch['title'] }}</span>
                            <span class="text-[10px] text-gray-400 dark:text-slate-500">{{ __('Hal') }} {{ $ch['start_page'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- HALAMAN DITANDAI (BOOKMARKS) --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-sm p-6 transition-colors duration-200">
                <h3 class="text-xs font-bold text-[#2F3951] dark:text-slate-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-[#4D9BE2] rounded-full"></span>
                    {{ __('Halaman Ditandai') }}
                </h3>
                
                @if(count($bookmarks) > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($bookmarks as $bPage)
                            <a href="{{ route('buku.read', ['id' => $buku->id, 'page' => $bPage]) }}" 
                               class="h-8 min-w-8 px-2 flex items-center justify-center rounded-lg border text-xs font-bold shadow-sm transition-all
                               {{ $page === $bPage
                                  ? 'bg-[#4D9BE2] text-white border-[#4D9BE2]'
                                  : 'bg-white dark:bg-slate-800 border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-300 hover:border-[#4D9BE2] hover:text-[#4D9BE2]' }}">
                                {{ $bPage }}
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-400 dark:text-slate-500 text-xs py-4 text-center">{{ __('Belum ada halaman yang ditandai.') }}</p>
                @endif
            </div>

            {{-- LONCAT HALAMAN --}}
            <div class="bg-white dark:bg-slate-900 rounded-3xl border border-gray-200 dark:border-slate-800 shadow-sm p-6 transition-colors duration-200"
                 x-data="{ pageNum: {{ $page }} }">
                <h3 class="text-xs font-bold text-[#2F3951] dark:text-slate-200 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-[#4D9BE2] rounded-full"></span>
                    {{ __('Loncat Halaman') }}
                </h3>
                
                <div class="flex flex-col gap-4">
                    {{-- Switcher Box --}}
                    <div class="flex items-center justify-center gap-4 bg-[#F8FAFC] dark:bg-slate-800/50 p-2.5 rounded-xl border border-gray-100 dark:border-slate-800">
                        <button type="button" 
                                @click="if (pageNum > 1) { pageNum-- }"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-white dark:bg-slate-800 text-gray-500 hover:text-[#4D9BE2] border border-gray-200 dark:border-slate-750 transition shadow-sm cursor-pointer">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        
                        <div class="flex items-center gap-1.5">
                            <input type="number" 
                                   x-model="pageNum"
                                   min="1" 
                                   max="{{ $totalPages }}"
                                   class="w-14 text-center font-extrabold text-sm text-[#2F3951] dark:text-slate-100 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 py-1.5 rounded-lg focus:outline-none focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2]">
                        </div>
                        
                        <button type="button" 
                                @click="if (pageNum < {{ $totalPages }}) { pageNum++ }"
                                class="w-8 h-8 flex items-center justify-center rounded-lg bg-white dark:bg-slate-800 text-gray-500 hover:text-[#4D9BE2] border border-gray-200 dark:border-slate-750 transition shadow-sm cursor-pointer">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
 
                    {{-- Jump Action Button --}}
                    <a :href="'{{ route('buku.read', $buku->id) }}?page=' + pageNum" 
                       class="w-full py-2.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white text-center font-bold rounded-xl text-xs shadow-sm transition-all flex items-center justify-center">
                        {{ __('Loncat') }}
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
