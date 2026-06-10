@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center gap-2 mt-8 py-4">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-300 cursor-not-allowed">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-[#4D9BE2] hover:text-[#3D8BCF] transition-colors">
                <svg class="w-3.5 h-3.5 text-[#4D9BE2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="flex items-center gap-1">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="w-8 h-8 flex items-center justify-center text-xs font-semibold text-gray-400">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="w-8 h-8 rounded-full flex items-center justify-center bg-[#4D9BE2] text-white font-bold text-xs shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-100 font-semibold text-xs transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-[#4D9BE2] hover:text-[#3D8BCF] transition-colors">
                Selanjutnya
                <svg class="w-3.5 h-3.5 text-[#4D9BE2]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        @else
            <span class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-300 cursor-not-allowed">
                Selanjutnya
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </span>
        @endif
    </nav>
@endif
