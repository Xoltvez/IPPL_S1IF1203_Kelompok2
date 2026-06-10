@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<div class="w-full flex flex-col Box-border" 
     x-data="{ 
        activeTab: 'profil', 
        showEditNameModal: false, 
        showEditEmailModal: false, 
        showEditPhoneModal: false, 
        showEditPasswordModal: false 
     }">

    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#2F3951] dark:text-slate-100 tracking-tight">Pengaturan</h1>
        <p class="text-gray-500 dark:text-slate-400 text-sm mt-1 font-medium">Ubah informasi akun dan preferensi aplikasi Anda di sini.</p>
    </div>

    <!-- Main Container -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
        
        <!-- Left Sub-menu / Navigation (Tabs) -->
        <div class="lg:col-span-1 bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm space-y-1.5 h-fit">
            <button @click="activeTab = 'profil'" 
                    :class="activeTab === 'profil' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                Profil
            </button>
            <button @click="activeTab = 'notifikasi'" 
                    :class="activeTab === 'notifikasi' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                Notifikasi
            </button>
            <button @click="activeTab = 'tampilan'" 
                    :class="activeTab === 'tampilan' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                Tampilan
            </button>
            <button @click="activeTab = 'bantuan'" 
                    :class="activeTab === 'bantuan' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                Bantuan
            </button>
            <button @click="activeTab = 'tentang'" 
                    :class="activeTab === 'tentang' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                Tentang
            </button>
        </div>

        <!-- Right Content Section -->
        <div class="lg:col-span-3 bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-8 shadow-sm min-h-[500px]">
            
            {{-- TAB: PROFIL --}}
            <div x-show="activeTab === 'profil'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-3"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <h2 class="text-xl font-bold text-[#2F3951] dark:text-slate-100 mb-6">Profil saya</h2>
                
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
                    
                    <!-- Left: Form Edit Profil -->
                    <div class="xl:col-span-7 space-y-6">
                        <!-- Avatar Row -->
                        <div class="flex flex-col sm:flex-row items-center gap-6 mb-8 pb-8 border-b border-gray-100 dark:border-slate-800">
                            <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-50 dark:bg-slate-800 border-2 border-gray-100 dark:border-slate-700 shadow-inner flex items-center justify-center text-3xl font-extrabold text-[#4D9BE2] flex-shrink-0 relative group">
                                @if($user->foto)
                                    <img src="{{ asset('storage/' . $user->foto) }}" alt="Avatar" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                @endif
                            </div>
                            
                            <div class="space-y-3 text-center sm:text-left flex-1">
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3">
                                    <!-- Hidden File Upload Form -->
                                    <form action="{{ route('member.pengaturan.updateFoto') }}" method="POST" enctype="multipart/form-data" id="fotoForm" class="m-0">
                                        @csrf
                                        <input type="file" name="foto" id="fotoInput" class="hidden" accept="image/*" onchange="document.getElementById('fotoForm').submit()">
                                        <button type="button" onclick="document.getElementById('fotoInput').click()" class="inline-flex items-center gap-2 px-4 py-2 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-xl text-xs font-bold transition shadow-sm cursor-pointer transform active:scale-95">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Rubah foto
                                        </button>
                                    </form>

                                    @if($user->foto)
                                        <form action="{{ route('member.pengaturan.deleteFoto') }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="px-4 py-2 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-300 rounded-xl text-xs font-bold transition cursor-pointer transform active:scale-95">
                                                Hapus foto
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 dark:text-slate-500">JPG, PNG, JPEG dengan ukuran maksimum 2 MB</p>
                            </div>
                        </div>

                        <!-- Form Fields -->
                        <div class="space-y-6">
                            <!-- Nama Lengkap -->
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-2">Nama lengkap</label>
                                    <input type="text" value="{{ $user->name }}" class="w-full bg-[#F8FAFC] dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl px-5 py-3.5 text-sm text-gray-500 dark:text-slate-300 font-semibold focus:outline-none" disabled>
                                </div>
                                <button @click="showEditNameModal = true" class="md:mt-6 px-5 py-3.5 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-300 font-bold rounded-2xl text-xs whitespace-nowrap transition cursor-pointer transform active:scale-95">
                                    Ganti nama
                                </button>
                            </div>

                            <!-- Email -->
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-2">Email</label>
                                    <input type="email" value="{{ $user->email }}" class="w-full bg-[#F8FAFC] dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl px-5 py-3.5 text-sm text-gray-500 dark:text-slate-300 font-semibold focus:outline-none" disabled>
                                </div>
                                <button @click="showEditEmailModal = true" class="md:mt-6 px-5 py-3.5 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-300 font-bold rounded-2xl text-xs whitespace-nowrap transition cursor-pointer transform active:scale-95">
                                    Ganti email
                                </button>
                            </div>

                            <!-- Password -->
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-2">Password</label>
                                    <input type="text" value="••••••••••••" class="w-full bg-[#F8FAFC] dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl px-5 py-3.5 text-sm text-gray-500 dark:text-slate-300 font-semibold tracking-widest focus:outline-none" disabled>
                                </div>
                                <button @click="showEditPasswordModal = true" class="md:mt-6 px-5 py-3.5 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-300 font-bold rounded-2xl text-xs whitespace-nowrap transition cursor-pointer transform active:scale-95">
                                    Ganti password
                                </button>
                            </div>

                            <!-- Nomor Telephone -->
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-2">Nomor telephone</label>
                                    <input type="text" value="{{ $user->no_telp ?? 'Belum diatur' }}" class="w-full bg-[#F8FAFC] dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-2xl px-5 py-3.5 text-sm text-gray-500 dark:text-slate-300 font-semibold focus:outline-none" disabled>
                                </div>
                                <button @click="showEditPhoneModal = true" class="md:mt-6 px-5 py-3.5 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 text-gray-600 dark:text-slate-300 font-bold rounded-2xl text-xs whitespace-nowrap transition cursor-pointer transform active:scale-95">
                                    Ganti nomor
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Digital Member Card -->
                    <div class="xl:col-span-5 flex flex-col items-center gap-4 bg-[#F8FAFC] dark:bg-slate-800/30 p-6 rounded-3xl border border-gray-100 dark:border-slate-800 w-full">
                        <h3 class="text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider w-full text-left">Kartu Anggota Digital</h3>
                        
                        <!-- Physical Card Representation -->
                        <div class="relative w-full max-w-[340px] h-[215px] bg-gradient-to-br from-[#4D9BE2] via-[#2F80ED] to-[#1E40AF] rounded-3xl p-5 text-white shadow-xl border border-white/10 overflow-hidden flex flex-col justify-between select-none">
                            
                            <!-- Glowing Aura Ornaments -->
                            <div class="absolute -right-12 -top-12 w-44 h-44 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
                            <div class="absolute -left-12 -bottom-12 w-44 h-44 bg-blue-300/10 rounded-full blur-2xl pointer-events-none"></div>

                            <!-- Card Header -->
                            <div class="flex items-center justify-between z-10">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-white/90" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332 0.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332 0.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332 0.477-4.5 1.253" />
                                    </svg>
                                    <span class="font-black text-sm tracking-tight">MacaBae</span>
                                </div>
                                <span class="text-[9px] font-bold tracking-widest text-white/70 uppercase">Member Card</span>
                            </div>

                            <!-- Card Body: Member Info & QR Code -->
                            <div class="flex items-center justify-between gap-4 z-10">
                                <!-- Member Details -->
                                <div class="min-w-0 flex-1 space-y-1">
                                    <p class="text-sm font-extrabold truncate text-white/95 leading-tight">{{ $user->name }}</p>
                                    <p class="text-[11px] font-black text-blue-200/90 tracking-wide">#MBR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                                    
                                    <div class="pt-2">
                                        <span class="inline-block px-2 py-0.5 bg-white/15 backdrop-blur-md rounded-lg text-[8px] font-extrabold uppercase tracking-wider text-blue-100">
                                            Anggota Aktif
                                        </span>
                                    </div>
                                </div>

                                <!-- QR Code Canvas Container -->
                                <div class="bg-white p-1.5 rounded-2xl flex items-center justify-center shadow-lg border border-white/20 flex-shrink-0">
                                    <canvas id="member-qr" class="w-20 h-20"></canvas>
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="flex items-center justify-between text-white/60 text-[8px] font-bold z-10">
                                <span class="tracking-wide">Perpustakaan Digital MacaBae</span>
                                <!-- Gold Contact Chip Simulator -->
                                <div class="w-7 h-5 bg-amber-400/80 rounded border border-amber-300/40 relative overflow-hidden">
                                    <div class="absolute inset-0 grid grid-cols-3 gap-0.5 p-0.5 opacity-60">
                                        <div class="border border-white/20"></div>
                                        <div class="border border-white/20"></div>
                                        <div class="border border-white/20"></div>
                                        <div class="border border-white/20"></div>
                                        <div class="border border-white/20"></div>
                                        <div class="border border-white/20"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="text-[10px] text-gray-400 dark:text-slate-500 font-semibold text-center mt-2">
                            Tunjukkan QR Code ini kepada pustakawan saat berkunjung offline untuk presensi dan transaksi cepat.
                        </p>
                    </div>

                </div>
            </div>

            {{-- TAB: NOTIFIKASI --}}
            <div x-show="activeTab === 'notifikasi'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-3"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="display: none;">
                
                <h2 class="text-xl font-bold text-[#2F3951] dark:text-slate-100 mb-6">Preferensi Notifikasi</h2>
                <p class="text-gray-400 dark:text-slate-400 text-xs mb-8">Pilih kapan saja Anda ingin menerima pemberitahuan dari kami terkait aktivitas perpustakaan.</p>

                <form action="{{ route('member.pengaturan.updateNotifikasi') }}" method="POST" class="m-0">
                    @csrf
                    <div class="space-y-6">
                        <!-- Notif 1 -->
                        <div class="flex items-start justify-between gap-6 pb-6 border-b border-gray-50 dark:border-slate-800/50">
                            <div class="space-y-1 flex-1">
                                <h4 class="text-sm font-bold text-[#2F3951] dark:text-slate-200">Email Persetujuan Sirkulasi</h4>
                                <p class="text-xs text-gray-400 dark:text-slate-400">Kirim email otomatis saat pengajuan peminjaman Anda disetujui atau ditolak.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                <input type="checkbox" name="notif_persetujuan" value="1" {{ $user->notif_persetujuan ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#4D9BE2]"></div>
                            </label>
                        </div>

                        <!-- Notif 2 -->
                        <div class="flex items-start justify-between gap-6 pb-6 border-b border-gray-50 dark:border-slate-800/50">
                            <div class="space-y-1 flex-1">
                                <h4 class="text-sm font-bold text-[#2F3951] dark:text-slate-200">Email Pengembalian</h4>
                                <p class="text-xs text-gray-400 dark:text-slate-400">Kirim email sebagai tanda terima resmi setelah buku sukses dikembalikan.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                <input type="checkbox" name="notif_pengembalian" value="1" {{ $user->notif_pengembalian ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#4D9BE2]"></div>
                            </label>
                        </div>

                        <!-- Notif 3 -->
                        <div class="flex items-start justify-between gap-6 pb-6 border-b border-gray-50 dark:border-slate-800/50">
                            <div class="space-y-1 flex-1">
                                <h4 class="text-sm font-bold text-[#2F3951] dark:text-slate-200">Pemberitahuan Jatuh Tempo & Denda</h4>
                                <p class="text-xs text-gray-400 dark:text-slate-400">Dapatkan peringatan penting 1 hari sebelum batas waktu pengembalian buku berakhir.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                <input type="checkbox" name="notif_jatuh_tempo" value="1" {{ $user->notif_jatuh_tempo ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#4D9BE2]"></div>
                            </label>
                        </div>

                        <!-- Notif 4 -->
                        <div class="flex items-start justify-between gap-6">
                            <div class="space-y-1 flex-1">
                                <h4 class="text-sm font-bold text-[#2F3951] dark:text-slate-200">Rekomendasi Buku Baru</h4>
                                <p class="text-xs text-gray-400 dark:text-slate-400">Terima berita bulanan seputar buku baru terpopuler di MacaBae.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                <input type="checkbox" name="notif_rekomendasi" value="1" {{ $user->notif_rekomendasi ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#4D9BE2]"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="px-5 py-3 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white font-bold rounded-2xl text-xs transition shadow-sm cursor-pointer transform active:scale-95">
                            Simpan Preferensi
                        </button>
                    </div>
                </form>
            </div>

            {{-- TAB: TAMPILAN --}}
            <div x-show="activeTab === 'tampilan'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-3"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="display: none;">
                
                <h2 class="text-xl font-bold text-[#2F3951] dark:text-slate-100 mb-6">Tampilan Aplikasi</h2>
                <p class="text-gray-400 dark:text-slate-400 text-xs mb-8">Sesuaikan skema warna dan tema antarmuka visual MacaBae Anda.</p>

                <div class="space-y-8">
                    <!-- Tema Pilihan -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold text-[#2F3951] dark:text-slate-200">Pilihan Tema</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @php $currentTheme = session('theme', 'light'); @endphp
                            <!-- Light Mode -->
                            <a href="{{ route('theme.switch', 'light') }}" 
                               class="border-2 {{ $currentTheme === 'light' ? 'border-[#4D9BE2] bg-white dark:bg-slate-900 shadow-sm' : 'border-gray-100 dark:border-slate-800 bg-[#F8FAFC]/50 dark:bg-slate-800/50 hover:bg-gray-50 dark:hover:bg-slate-800' }} rounded-2xl p-4 flex items-center gap-4 cursor-pointer transition">
                                <div class="w-5 h-5 rounded-full border border-gray-300 dark:border-slate-600 flex items-center justify-center flex-shrink-0">
                                    @if($currentTheme === 'light')
                                        <div class="w-2.5 h-2.5 bg-[#4D9BE2] rounded-full"></div>
                                    @endif
                                </div>
                                <div class="text-left">
                                    <span class="block text-sm font-bold text-[#2F3951] dark:text-slate-100">Mode Terang {{ $currentTheme === 'light' ? '(Aktif)' : '' }}</span>
                                    <span class="text-[10px] text-gray-400 dark:text-slate-400">Bersih, segar, dan ramah di siang hari.</span>
                                </div>
                            </a>

                            <!-- Dark Mode -->
                            <a href="{{ route('theme.switch', 'dark') }}" 
                               class="border-2 {{ $currentTheme === 'dark' ? 'border-[#4D9BE2] bg-white dark:bg-slate-900 shadow-sm' : 'border-gray-100 dark:border-slate-800 bg-[#F8FAFC]/50 dark:bg-slate-800/50 hover:bg-gray-50 dark:hover:bg-slate-800' }} rounded-2xl p-4 flex items-center gap-4 cursor-pointer transition">
                                <div class="w-5 h-5 rounded-full border border-gray-300 dark:border-slate-600 flex items-center justify-center flex-shrink-0">
                                    @if($currentTheme === 'dark')
                                        <div class="w-2.5 h-2.5 bg-[#4D9BE2] rounded-full"></div>
                                    @endif
                                </div>
                                <div class="text-left">
                                    <span class="block text-sm font-bold text-[#2F3951] dark:text-slate-100">Mode Gelap {{ $currentTheme === 'dark' ? '(Aktif)' : '' }}</span>
                                    <span class="text-[10px] text-gray-400 dark:text-slate-400">Nyaman di mata untuk malam hari.</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Pilihan Bahasa -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold text-[#2F3951] dark:text-slate-200">Pilihan Bahasa / Language Selection</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @php
                                $currentLocale = app()->getLocale();
                            @endphp
                            <!-- Bahasa Indonesia -->
                            <a href="{{ route('lang.switch', 'id') }}" 
                               class="border-2 {{ $currentLocale === 'id' ? 'border-[#4D9BE2] bg-white dark:bg-slate-900 shadow-sm' : 'border-gray-100 dark:border-slate-800 bg-[#F8FAFC]/50 dark:bg-slate-800/50 hover:bg-gray-50 dark:hover:bg-slate-800' }} rounded-2xl p-4 flex items-center gap-4 cursor-pointer transition">
                                <div class="w-5 h-5 rounded-full border border-gray-300 dark:border-slate-600 flex items-center justify-center flex-shrink-0">
                                    @if($currentLocale === 'id')
                                        <div class="w-2.5 h-2.5 bg-[#4D9BE2] rounded-full"></div>
                                    @endif
                                </div>
                                <div class="text-left">
                                    <span class="block text-sm font-bold text-[#2F3951] dark:text-slate-100">Bahasa Indonesia</span>
                                    <span class="text-[10px] text-gray-400 dark:text-slate-400">Gunakan Bahasa Indonesia sebagai bahasa utama aplikasi.</span>
                                </div>
                            </a>

                            <!-- English -->
                            <a href="{{ route('lang.switch', 'en') }}" 
                               class="border-2 {{ $currentLocale === 'en' ? 'border-[#4D9BE2] bg-white dark:bg-slate-900 shadow-sm' : 'border-gray-100 dark:border-slate-800 bg-[#F8FAFC]/50 dark:bg-slate-800/50 hover:bg-gray-50 dark:hover:bg-slate-800' }} rounded-2xl p-4 flex items-center gap-4 cursor-pointer transition">
                                <div class="w-5 h-5 rounded-full border border-gray-300 dark:border-slate-600 flex items-center justify-center flex-shrink-0">
                                    @if($currentLocale === 'en')
                                        <div class="w-2.5 h-2.5 bg-[#4D9BE2] rounded-full"></div>
                                    @endif
                                </div>
                                <div class="text-left">
                                    <span class="block text-sm font-bold text-[#2F3951] dark:text-slate-100">English</span>
                                    <span class="text-[10px] text-gray-400 dark:text-slate-400">Use English as the primary language for the application.</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB: BANTUAN --}}
            <div x-show="activeTab === 'bantuan'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-3"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="display: none;">
                
                <h2 class="text-xl font-bold text-[#2F3951] dark:text-slate-100 mb-6">Pusat Bantuan & FAQ</h2>
                <p class="text-gray-400 dark:text-slate-400 text-xs mb-8">Temukan jawaban cepat atas pertanyaan Anda tentang MacaBae.</p>

                <!-- FAQ Accordion -->
                <div class="space-y-4 text-left" x-data="{ openFaq: null }">
                    
                    <!-- FAQ 1 -->
                    <div class="border border-gray-50 dark:border-slate-800 rounded-2xl overflow-hidden bg-[#F8FAFC]/50 dark:bg-slate-800/50">
                        <button @click="openFaq = (openFaq === 1 ? null : 1)" class="w-full flex items-center justify-between px-6 py-4 font-bold text-[#2F3951] dark:text-slate-200 text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition cursor-pointer">
                            Bagaimana cara mengajukan peminjaman buku?
                            <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="openFaq === 1 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openFaq === 1" class="px-6 pb-5 text-xs text-gray-500 dark:text-slate-400 leading-relaxed border-t border-gray-100/50 dark:border-slate-800/50 pt-3 bg-white dark:bg-slate-900" style="display: none;">
                            Cari buku yang ingin Anda pinjam melalui halaman <strong>Katalog</strong>, klik buku tersebut untuk membuka halaman detail. Tentukan durasi pinjam yang Anda inginkan (maksimal 7 hari), kemudian tekan tombol <strong>"Pinjam buku"</strong>. Transaksi Anda akan berstatus <em>Menunggu Persetujuan</em> sampai dikonfirmasi oleh Pustakawan.
                        </div>
                    </div>

                    <!-- FAQ 2 -->
                    <div class="border border-gray-50 dark:border-slate-800 rounded-2xl overflow-hidden bg-[#F8FAFC]/50 dark:bg-slate-800/50">
                        <button @click="openFaq = (openFaq === 2 ? null : 2)" class="w-full flex items-center justify-between px-6 py-4 font-bold text-[#2F3951] dark:text-slate-200 text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition cursor-pointer">
                            Apakah ada denda keterlambatan pengembalian?
                            <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="openFaq === 2 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openFaq === 2" class="px-6 pb-5 text-xs text-gray-500 dark:text-slate-400 leading-relaxed border-t border-gray-100/50 dark:border-slate-800/50 pt-3 bg-white dark:bg-slate-900" style="display: none;">
                            Ya, apabila tanggal pengembalian melewati tanggal jatuh tempo yang telah ditentukan saat peminjaman, Anda akan dikenakan denda keterlambatan sebesar <strong>Rp 1.000 per hari</strong> untuk setiap buku. Informasi denda yang belum dibayar akan tampil di dashboard Anda.
                        </div>
                    </div>

                    <!-- FAQ 3 -->
                    <div class="border border-gray-50 dark:border-slate-800 rounded-2xl overflow-hidden bg-[#F8FAFC]/50 dark:bg-slate-800/50">
                        <button @click="openFaq = (openFaq === 3 ? null : 3)" class="w-full flex items-center justify-between px-6 py-4 font-bold text-[#2F3951] dark:text-slate-200 text-sm hover:bg-gray-50 dark:hover:bg-slate-800 transition cursor-pointer">
                            Bisakah saya membatalkan pengajuan pinjam buku?
                            <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" :class="openFaq === 3 ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="openFaq === 3" class="px-6 pb-5 text-xs text-gray-500 dark:text-slate-400 leading-relaxed border-t border-gray-100/50 dark:border-slate-800/50 pt-3 bg-white dark:bg-slate-900" style="display: none;">
                            Tentu saja! Jika pengajuan peminjaman buku Anda masih berstatus <strong>Menunggu Persetujuan</strong> (belum diproses oleh pustakawan), Anda dapat membatalkannya secara langsung dengan mengklik tombol <strong>"Batalkan"</strong> di halaman Peminjaman Buku.
                        </div>
                    </div>

                </div>
            </div>

            {{-- TAB: TENTANG --}}
            <div x-show="activeTab === 'tentang'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-3"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="display: none;">
                
                <h2 class="text-xl font-bold text-[#2F3951] dark:text-slate-100 mb-6">Tentang Aplikasi</h2>
                
                <div class="flex flex-col items-center text-center space-y-6 py-6">
                    <img src="{{ asset('assets/img/brand/logo-macabae.png') }}" alt="Logo MacaBae" class="h-14">
                    
                    <div class="max-w-md space-y-4">
                        <p class="text-sm text-gray-500 dark:text-slate-400 leading-relaxed">
                            MacaBae adalah platform sirkulasi perpustakaan modern berbasis web yang dikembangkan khusus untuk mempermudah anggota mencari, meminjam, dan mengelola buku perpustakaan secara transparan, mudah, dan efisien.
                        </p>
                        
                        <div class="inline-block px-4 py-1.5 bg-[#F3F7FB] dark:bg-slate-800 border border-[#4D9BE2]/10 dark:border-slate-700 rounded-full text-xs text-[#4D9BE2] font-bold">
                            Versi Aplikasi: 2.3.0-stable
                        </div>

                        <p class="text-[10px] text-gray-400 dark:text-slate-500">
                            © 2026 MacaBae Team. Hak Cipta Dilindungi.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL: GANTI NAMA -->
    <div x-show="showEditNameModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="showEditNameModal = false" 
             class="bg-white dark:bg-slate-900 w-full max-w-md rounded-3xl p-6 shadow-xl border border-gray-100 dark:border-slate-800 transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-base font-bold text-[#2F3951] dark:text-slate-100">Ubah Nama Lengkap</h3>
                <button @click="showEditNameModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('member.pengaturan.updateName') }}" method="POST" class="m-0 space-y-4">
                @csrf
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-2">Nama lengkap baru</label>
                    <input type="text" name="name" value="{{ $user->name }}" required class="w-full border border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-2xl px-5 py-3.5 text-sm text-[#2F3951] font-semibold focus:outline-none focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2]/10 transition">
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-50 dark:border-slate-800">
                    <button type="button" @click="showEditNameModal = false" class="px-5 py-3 bg-gray-50 dark:bg-slate-850 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 rounded-2xl text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-3 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-2xl text-xs font-bold transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: GANTI EMAIL -->
    <div x-show="showEditEmailModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="showEditEmailModal = false" 
             class="bg-white dark:bg-slate-900 w-full max-w-md rounded-3xl p-6 shadow-xl border border-gray-100 dark:border-slate-800 transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-base font-bold text-[#2F3951] dark:text-slate-100">Ubah Alamat Email</h3>
                <button @click="showEditEmailModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('member.pengaturan.updateEmail') }}" method="POST" class="m-0 space-y-4">
                @csrf
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-2">Alamat email baru</label>
                    <input type="email" name="email" value="{{ $user->email }}" required class="w-full border border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-2xl px-5 py-3.5 text-sm text-[#2F3951] font-semibold focus:outline-none focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2]/10 transition">
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-50 dark:border-slate-800">
                    <button type="button" @click="showEditEmailModal = false" class="px-5 py-3 bg-gray-50 dark:bg-slate-850 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 rounded-2xl text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-3 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-2xl text-xs font-bold transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: GANTI NOMOR TELEPON -->
    <div x-show="showEditPhoneModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="showEditPhoneModal = false" 
             class="bg-white dark:bg-slate-900 w-full max-w-md rounded-3xl p-6 shadow-xl border border-gray-100 dark:border-slate-800 transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-base font-bold text-[#2F3951] dark:text-slate-100">Ubah Nomor Telepon</h3>
                <button @click="showEditPhoneModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('member.pengaturan.updatePhone') }}" method="POST" class="m-0 space-y-4">
                @csrf
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-2">Nomor telepon baru</label>
                    <input type="text" name="no_telp" value="{{ $user->no_telp }}" placeholder="Contoh: 0821xxxxxxxx" required class="w-full border border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-2xl px-5 py-3.5 text-sm text-[#2F3951] font-semibold focus:outline-none focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2]/10 transition">
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-50 dark:border-slate-800">
                    <button type="button" @click="showEditPhoneModal = false" class="px-5 py-3 bg-gray-50 dark:bg-slate-850 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 rounded-2xl text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-3 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-2xl text-xs font-bold transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: GANTI PASSWORD -->
    <div x-show="showEditPasswordModal" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4"
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.away="showEditPasswordModal = false" 
             class="bg-white dark:bg-slate-900 w-full max-w-md rounded-3xl p-6 shadow-xl border border-gray-100 dark:border-slate-800 transform transition-all"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-base font-bold text-[#2F3951] dark:text-slate-100">Ubah Password</h3>
                <button @click="showEditPasswordModal = false" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('member.pengaturan.updatePassword') }}" method="POST" class="m-0 space-y-4">
                @csrf
                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" required class="w-full border border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-2xl px-5 py-3.5 text-sm text-[#2F3951] font-semibold focus:outline-none focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2]/10 transition">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-2">Password Baru</label>
                        <input type="password" name="password" required class="w-full border border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-2xl px-5 py-3.5 text-sm text-[#2F3951] font-semibold focus:outline-none focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2]/10 transition">
                    </div>

                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-gray-400 dark:text-slate-500 uppercase tracking-wider mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" required class="w-full border border-gray-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 rounded-2xl px-5 py-3.5 text-sm text-[#2F3951] font-semibold focus:outline-none focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2]/10 transition">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-gray-50 dark:border-slate-800">
                    <button type="button" @click="showEditPasswordModal = false" class="px-5 py-3 bg-gray-50 dark:bg-slate-850 hover:bg-gray-100 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 rounded-2xl text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-3 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-2xl text-xs font-bold transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new QRious({
            element: document.getElementById('member-qr'),
            value: '{{ $user->id }}',
            size: 160,
            backgroundAlpha: 0,
            foreground: '#0f172a'
        });
    });
</script>
@endpush
@endsection
