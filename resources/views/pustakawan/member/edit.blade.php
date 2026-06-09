@extends('layouts.app')

@section('title', 'Edit Member')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#2F3951]">Edit Profil Anggota</h1>
            <p class="mt-1 text-sm text-gray-500">Perbarui informasi akun untuk member ini.</p>
        </div>
    </div>

    {{-- Card Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow p-8">
        <form action="{{ route('pustakawan.member.update', $member->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Field Nama --}}
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $member->name) }}" 
                           class="w-full px-4 py-3 bg-[#F8FAFC]/60 border border-gray-200 shadow-sm focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all @error('name') border-rose-500 @enderror"
                           placeholder="Masukkan nama lengkap...">
                    @error('name')
                        <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field Email --}}
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $member->email) }}" 
                           class="w-full px-4 py-3 bg-[#F8FAFC]/60 border shadow-sm border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all @error('email') border-rose-500 @enderror"
                           placeholder="contoh@student.telkomuniversity.ac.id">
                    @error('email')
                        <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Info (Read Only) --}}
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <div class="flex gap-3">
                        <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-blue-700 leading-relaxed">
                            ID Member: <span class="font-bold">#MBR-{{ str_pad($member->id, 4, '0', STR_PAD_LEFT) }}</span><br>
                            Terdaftar sejak: {{ $member->created_at->format('d F Y') }}
                        </p>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="pt-4 flex items-center justify-end gap-3">
                    <a href="{{ route('pustakawan.member.index') }}" class="px-5 py-2.5 text-gray-500 font-bold rounded-xl text-xs transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 bg-[#4D9BE2] hover:bg-[#2F3951]/90 text-white rounded-xl font-semibold text-sm transition shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection