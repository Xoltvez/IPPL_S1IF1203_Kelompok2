@extends('layouts.app')

@section('title', 'Tambah Pustakawan')

@section('content')
<div class="max-w-2xl mx-auto block text-left">
    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-[#2F3951] dark:text-slate-100">Tambah Akun Pustakawan</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Daftarkan petugas perpustakaan baru untuk sistem MacaBae.</p>
        </div>
    </div>

    {{-- Card Form --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow p-8 transition-colors duration-200">
        <form action="{{ route('admin.pustakawan.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                {{-- Field Nama --}}
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full px-4 py-3 bg-[#F8FAFC]/60 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 shadow-sm focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all @error('name') border-rose-500 @enderror"
                           placeholder="Masukkan nama lengkap..." required>
                    @error('name')
                        <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field Email --}}
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-2">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="w-full px-4 py-3 bg-[#F8FAFC]/60 dark:bg-slate-800 border shadow-sm border-gray-200 dark:border-slate-700 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all @error('email') border-rose-500 @enderror"
                           placeholder="pustakawan@macabae.com" required>
                    @error('email')
                        <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Field Password --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-2">Kata Sandi</label>
                        <div class="relative" x-data="{ showPassword: false }">
                            <input :type="showPassword ? 'text' : 'password'" name="password" 
                                   class="w-full pl-4 pr-10 py-3 bg-[#F8FAFC]/60 dark:bg-slate-800 border shadow-sm border-gray-200 dark:border-slate-700 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all @error('password') border-rose-500 @enderror"
                                   placeholder="Min. 8 karakter..." required>
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-4 flex items-center text-gray-400 hover:text-[#4D9BE2] transition-colors focus:outline-none">
                                <template x-if="showPassword">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </template>
                                <template x-if="!showPassword">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </template>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-rose-500 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mb-2">Konfirmasi Sandi</label>
                        <div class="relative" x-data="{ showPassword: false }">
                            <input :type="showPassword ? 'text' : 'password'" name="password_confirmation" 
                                   class="w-full pl-4 pr-10 py-3 bg-[#F8FAFC]/60 dark:bg-slate-800 border shadow-sm border-gray-200 dark:border-slate-700 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] dark:text-slate-100 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all"
                                   placeholder="Ulangi sandi..." required>
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-[#4D9BE2] transition-colors focus:outline-none">
                                <template x-if="showPassword">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                    </svg>
                                </template>
                                <template x-if="!showPassword">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </template>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="pt-4 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.pustakawan.index') }}" class="px-5 py-2.5 text-gray-500 dark:text-slate-400 font-bold rounded-xl text-xs transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl font-semibold text-sm transition shadow-md cursor-pointer">
                        Simpan Akun
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
