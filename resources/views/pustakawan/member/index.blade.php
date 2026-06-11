@extends('layouts.app')

@section('title', 'Manajemen Anggota')

@section('content')
<div class="block w-full text-left clear-both">

    {{-- HEADER & SEARCH --}}
    <div class="w-full bg-white p-4 rounded-2xl border border-gray-100 shadow mb-6">
        <form action="{{ route(auth()->user()->role . '.member.index') }}" method="GET" class="w-full flex items-center gap-3">
            <div class="relative flex-1 flex items-center">
                <span class="absolute left-4 text-gray-400 pointer-events-none flex items-center">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari member atau email..." 
                    class="w-full pl-12 pr-4 py-2.5 bg-[#F8FAFC]/60 border border-gray-100 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2.5 bg-[#2F3951] hover:bg-[#2F3951]/90 text-white rounded-xl font-semibold text-sm transition shadow-sm">
                    Cari Member
                </button>

                {{-- TOMBOL RESET: Hanya muncul jika ada input search --}}
                @if(request()->filled('search'))
                    <a href="{{ route(auth()->user()->role . '.member.index') }}" 
                    class="px-5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-xl font-semibold text-sm transition flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- TABLE DATA MEMBER --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F8FAFC] border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Info Member</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Email</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Status Akun</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Bergabung Pada</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($members as $member)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#4D9BE2]/10 flex items-center justify-center text-[#4D9BE2] font-bold">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-[#2F3951]">{{ $member->name }}</p>
                                    <p class="text-[11px] text-gray-400">ID: #MBR-{{ str_pad($member->id, 4, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $member->email }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium text-green-600">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $member->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route(auth()->user()->role . '.member.edit', $member->id) }}" class="p-2 text-gray-400 hover:text-[#4D9BE2] hover:bg-[#4D9BE2]/5 rounded-lg transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                                <form action="{{ route(auth()->user()->role . '.member.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus member ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition">
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
                                <p class="text-gray-400 text-sm">Tidak ada data member ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection