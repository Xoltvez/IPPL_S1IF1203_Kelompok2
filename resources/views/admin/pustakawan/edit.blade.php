@extends('layouts.app')

@section('title', 'Edit Pustakawan')

@section('content')
<div class="max-w-2xl mx-auto block text-left">
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#2F3951] dark:text-slate-100">Edit Profil Pustakawan</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Perbarui informasi akun untuk petugas perpustakaan ini.</p>
        </div>
    </div>

    {{-- Card Form --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow p-8 transition-colors duration-200">
        <form action="{{ route('admin.pustakawan.update', $pustakawan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Field Nama --}}
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $pustakawan->name) }}" 
                           class="w-full px-4 py-3 bg-[#F8FAFC]/60 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow-sm focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all @error('name') border-rose-500 @enderror"
                           placeholder="Masukkan nama lengkap..." required>
                    @error('name')
                        <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field Email --}}
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-2">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email', $pustakawan->email) }}" 
                           class="w-full px-4 py-3 bg-[#F8FAFC]/60 dark:bg-slate-800 border shadow-sm border-gray-200 dark:border-slate-700 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all @error('email') border-rose-500 @enderror"
                           placeholder="pustakawan@macabae.com" required>
                    @error('email')
                        <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field Password (Opsional) --}}
                <div class="p-4 bg-slate-50 dark:bg-slate-800/40 rounded-xl border border-slate-100 dark:border-slate-800">
                    <p class="text-xs text-gray-500 dark:text-slate-400 font-semibold mb-3">Ganti Kata Sandi (Kosongkan jika tidak ingin diubah)</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Kata Sandi Baru</label>
                            <input type="password" name="password" 
                                   class="w-full px-4 py-3 bg-white dark:bg-slate-900 border shadow-sm border-gray-200 dark:border-slate-700 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all @error('password') border-rose-500 @enderror"
                                   placeholder="Min. 8 karakter...">
                            @error('password')
                                <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-1.5">Konfirmasi Sandi Baru</label>
                            <input type="password" name="password_confirmation" 
                                   class="w-full px-4 py-3 bg-white dark:bg-slate-900 border shadow-sm border-gray-200 dark:border-slate-700 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all"
                                   placeholder="Ulangi sandi baru...">
                        </div>
                    </div>
                </div>

                {{-- Status Info (Read Only) --}}
                <div class="p-4 bg-blue-50 dark:bg-blue-950/20 rounded-xl border border-blue-100 dark:border-blue-900/30">
                    <div class="flex gap-3">
                        <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-blue-700 dark:text-blue-350 leading-relaxed">
                            ID Pustakawan: <span class="font-bold">#PST-{{ str_pad($pustakawan->id, 4, '0', STR_PAD_LEFT) }}</span><br>
                            Terdaftar sejak: {{ $pustakawan->created_at->format('d F Y') }}
                        </p>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="pt-4 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.pustakawan.index') }}" class="px-5 py-2.5 text-gray-500 dark:text-slate-400 font-bold rounded-xl text-xs transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl font-semibold text-sm transition shadow-md cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
