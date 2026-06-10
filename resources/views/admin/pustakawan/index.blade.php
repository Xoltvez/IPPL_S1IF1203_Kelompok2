@extends('layouts.app')

@section('title', 'Manajemen Pustakawan')

@section('content')
<div class="block w-full text-left clear-both">

    <!-- Header Section -->
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#2F3951] dark:text-slate-100 tracking-tight">Data Pustakawan</h1>
            <p class="text-gray-500 dark:text-slate-400 text-sm mt-1 font-medium">Manajemen akun petugas perpustakaan / pustakawan MacaBae.</p>
        </div>
        <div>
            <a href="{{ route('admin.pustakawan.create') }}" class="px-5 py-2.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl font-semibold text-sm transition shadow-sm inline-flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap">
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Pustakawan</span>
            </a>
        </div>
    </div>

    {{-- HEADER & SEARCH --}}
    <div class="w-full bg-white dark:bg-slate-900 p-4 rounded-2xl border border-gray-100 dark:border-slate-800 shadow mb-6 transition-colors duration-200">
        <form action="{{ route('admin.pustakawan.index') }}" method="GET" class="w-full flex items-center gap-3">
            <div class="relative flex-1 flex items-center">                
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari nama pustakawan, email..." 
                    class="w-full pl-12 pr-4 py-2.5 bg-[#F8FAFC]/60 dark:bg-slate-800 border border-gray-100 dark:border-slate-700 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] dark:text-slate-100 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2.5 bg-[#2F3951] hover:bg-[#2F3951]/90 dark:bg-slate-800 dark:hover:bg-slate-700 text-white rounded-xl font-semibold text-sm transition shadow-sm cursor-pointer">
                    Cari Pustakawan
                </button>

                @if(request()->filled('search'))
                    <a href="{{ route('admin.pustakawan.index') }}" 
                    class="px-5 py-2.5 bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 rounded-xl font-semibold text-sm transition flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABLE DATA PUSTAKAWAN --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow overflow-hidden transition-colors duration-200">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] dark:bg-slate-800/40 border-b border-gray-100 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest">Info Pustakawan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest">Email</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest">Status Akun</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest">Bergabung Pada</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-slate-800/50">
                    @forelse($pustakawans as $pustakawan)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#4D9BE2]/10 dark:bg-[#4D9BE2]/20 flex items-center justify-center text-[#4D9BE2] dark:text-[#5fa8eb] font-bold">
                                    {{ strtoupper(substr($pustakawan->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-[#2F3951] dark:text-slate-200">{{ $pustakawan->name }}</p>
                                    <p class="text-[11px] text-gray-400 dark:text-slate-500">ID: #PST-{{ str_pad($pustakawan->id, 4, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-slate-400">
                            {{ $pustakawan->email }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-950/20 border border-green-100 dark:border-green-900/30">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-slate-400">
                            {{ $pustakawan->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.pustakawan.edit', $pustakawan->id) }}" class="p-2 text-gray-400 dark:text-slate-500 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2] hover:bg-[#4D9BE2]/5 rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.pustakawan.destroy', $pustakawan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pustakawan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 dark:text-slate-500 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/20 rounded-lg transition cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <p class="text-gray-400 dark:text-slate-500 text-sm">Tidak ada data pustakawan ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($pustakawans->hasPages())
            <div class="p-6 border-t border-gray-100 dark:border-slate-800">
                {{ $pustakawans->links('partials.pagination') }}
            </div>
        @endif
    </div>
</div>
@endsection
