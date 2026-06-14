@extends('layouts.app')

@section('title', 'Edit Data Buku')

@section('content')
<div class="w-full flex flex-col text-left md:px-2 max-w mx-auto Box-border">

    <div class="w-full mb-6">
        <h1 class="text-xl font-bold text-[#2F3951] tracking-tight">Edit Informasi Buku</h1>
        <p class="text-gray-400 text-xs mt-2">Perbarui detail data aset koleksi buku MacaBae yang dipilih.</p>
    </div>

    <div class="w-full bg-white rounded-2xl border border-gray-200 shadow overflow-hidden">
        
        <form action="{{ route(auth()->user()->role . '.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 w-full m-0 flex flex-col gap-6">
            @csrf
            @method('PUT') 

            <div class="w-full flex flex-col text-left" x-data="{ imgPreview: '{{ $buku->cover_buku ? asset('storage/' . $buku->cover_buku) : '' }}' }">

    <h3 class="text-xs font-bold text-[#2F3951] uppercase text-gray-400 tracking-wider mb-4 flex items-center">
        <span class="w-1 h-3.5 bg-[#4D9BE2] rounded-full"></span>
        Visual & Media Buku
    </h3>

            <div class="flex flex-col sm:flex-row gap-5 items-start w-full">

                <div class="w-36 h-48 aspect-[3/4] bg-[#FCFCFC] border border-gray-200 rounded-2xl shadow-sm
                            overflow-hidden flex-shrink-0 flex items-center justify-center relative">

                        <template x-if="imgPreview">
                            <img :src="imgPreview" alt="Live Preview Sampul"
                                class="w-full h-full object-cover">
                        </template>

                        <template x-if="!imgPreview">
                            <div class="flex flex-col items-center justify-center text-gray-300 p-4 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-7 h-7 mb-1 text-gray-300">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5
                                            l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5
                                            0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5
                                            1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V6.75zm.375 
                                            0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                                <span class="text-[9px] font-bold uppercase tracking-wider text-gray-400">
                                    Rasio 3:4
                                </span>
                            </div>
                        </template>
                    </div>

                    <div class="flex-1 w-full flex flex-col justify-center min-h-[160px]">

                        <label for="sampul"
                            class="text-[11px] font-bold text-[#2F3951] uppercase tracking-wider mb-2 block">
                            Ganti Sampul Buku
                        </label>

                        <input
                            type="file"
                            name="sampul"
                            id="sampul"
                            accept="image/*"
                            @change="
                                const file = $event.target.files[0];
                                if (file) { 
                                    imgPreview = URL.createObjectURL(file); 
                                } else { 
                                    imgPreview = '{{ $buku->cover_buku ? asset('storage/' . $buku->cover_buku) : '' }}'; 
                                }
                            "
                            style="background-color: #FCFCFC"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-[#2F3951]
                                focus:border-[#4D9BE2]/50 focus:bg-white
                                file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0
                                file:text-xs file:font-semibold file:bg-[#4D9BE2]/10 file:text-[#4D9BE2]
                                hover:file:bg-[#4D9BE2]/20 transition-all cursor-pointer"
                        >

                        <p class="text-[10px] text-gray-400 mt-2 leading-relaxed">
                            Format berkas:
                            <span class="font-semibold text-gray-500">JPG, JPEG, PNG</span>.
                            Maksimal ukuran berkas 2MB.
                            <span class="text-[#4D9BE2]">Biarkan kosong jika tidak ingin mengubah gambar sampul saat ini.</span>
                        </p>

                        @error('sampul')
                            <span class="text-xs text-rose-500 mt-1 font-medium block">
                                {{ $message }}
                            </span>
                        @enderror

                    </div>
                </div>
            </div>

            <div class="mt-4 w-full flex flex-col text-left">
                <h3 class="text-xs font-bold text-[#2F3951] uppercase text-gray-400 tracking-wider mb-4 flex items-center">
                    <span class="w-1 h-3.5 bg-[#4D9BE2] rounded-full"></span>
                    Informasi Utama Buku
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
                    <div class="flex flex-col text-left w-full">
                        <label for="judul" class="text-[11px] font-bold uppercase tracking-wider mb-2">Judul Lengkap Buku <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" id="judul" value="{{ old('judul', $buku->judul) }}" required placeholder="Contoh: Bumi Manusia" style="background-color: #FCFCFC" class="w-full px-4 py-2.5 border border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
                        @error('judul') <span class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col text-left w-full">
                        <label for="isbn" class="text-[11px] font-bold uppercase tracking-wider mb-2">Nomor Standar ISBN <span class="text-rose-500">*</span></label>
                        <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $buku->isbn) }}" required placeholder="Contoh: 9786022916628" style="background-color: #FCFCFC" class="w-full px-4 py-2.5 border border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
                        @error('isbn') <span class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="w-full flex flex-col text-left">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
                    <div class="flex flex-col text-left w-full">
                        <label for="pengarang" class="text-[11px] font-bold uppercase tracking-wider mb-2">Nama Pengarang / Penulis <span class="text-rose-500">*</span></label>
                        <input type="text" name="pengarang" id="pengarang" value="{{ old('pengarang', $buku->pengarang) }}" required placeholder="Contoh: Pramoedya Ananta Toer" style="background-color: #FCFCFC" class="w-full px-4 py-2.5 border border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
                        @error('pengarang') <span class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col text-left w-full">
                        <label for="penerbit" class="text-[11px] font-bold uppercase tracking-wider mb-2">Nama Penerbit</label>
                        <input type="text" name="penerbit" id="penerbit" value="{{ old('penerbit', $buku->penerbit) }}" placeholder="Contoh: Lentera Dipantara" style="background-color: #FCFCFC" class="w-full px-4 py-2.5 border border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
                        @error('penerbit') <span class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col text-left w-full">
                        <label for="tahun_terbit" class="text-[11px] font-bold uppercase tracking-wider mb-2">Tahun Terbit Buku</label>
                        <input type="number" name="tahun_terbit" id="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" placeholder="Contoh: 2015" style="background-color: #FCFCFC" class="w-full px-4 py-2.5 border border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
                        @error('tahun_terbit') <span class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col text-left w-full">
                        <label for="kategori_id" class="text-[11px] font-bold uppercase tracking-wider mb-2">Kategori Buku <span class="text-rose-500">*</span></label>
                        <div class="relative w-full">
                            <select name="kategori_id" id="kategori_id" required style="background-color: #FCFCFC" class="w-full px-4 py-2.5 border border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all appearance-none cursor-pointer">
                                <option value="" disabled>-- Pilih Kategori Buku --</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" data-is-ebook="{{ strtolower($kategori->nama_kategori) === 'e-book' ? '1' : '0' }}" {{ old('kategori_id', $buku->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        </div>
                        @error('kategori_id') <span class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-4 w-full flex flex-col text-left">
                <h3 class="text-xs font-bold text-[#2F3951] uppercase text-gray-400 tracking-wider mb-4 flex items-center">
                    <span class="w-1 h-3.5 bg-[#4D9BE2] rounded-full"></span>
                    Spesifikasi & Sinopsis Buku
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
                    <div class="flex flex-col text-left w-full md:col-span-2">
                        <label for="deskripsi" class="text-[11px] font-bold uppercase tracking-wider mb-2">Deskripsi Buku</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" placeholder="Masukkan deskripsi lengkap buku..." class="w-full px-4 py-2.5 border border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                        @error('deskripsi') <span class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col text-left w-full md:col-span-2">
                        <label for="sinopsis" class="text-[11px] font-bold uppercase tracking-wider mb-2">Sinopsis Buku</label>
                        <textarea name="sinopsis" id="sinopsis" rows="3" placeholder="Masukkan sinopsis atau ringkasan cerita buku..." class="w-full px-4 py-2.5 border border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">{{ old('sinopsis', $buku->sinopsis) }}</textarea>
                        @error('sinopsis') <span class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div id="ebook-pages-section" class="mt-4 w-full flex flex-col text-left hidden">
                <h3 class="text-xs font-bold text-[#2F3951] uppercase text-gray-400 tracking-wider mb-4 flex items-center">
                    <span class="w-1 h-3.5 bg-[#4D9BE2] rounded-full"></span>
                    Konten Halaman E-Book
                </h3>
                
                <div class="grid grid-cols-1 gap-5 w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
                        <div class="flex flex-col text-left w-full">
                            <label for="pdf_file" class="text-[11px] font-bold uppercase tracking-wider mb-2">Unggah File PDF (Otomatis Baca Teks Halaman) <span class="text-xs font-normal text-[#4D9BE2] lowercase">(opsional)</span></label>
                            <input type="file" id="pdf_file" accept="application/pdf" style="background-color: #FCFCFC" class="w-full px-4 py-2 border border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] file:mr-4 file:py-1 file:px-2.5 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-[#4D9BE2]/10 file:text-[#4D9BE2] hover:file:bg-[#4D9BE2]/20 transition-all cursor-pointer">
                            <p class="text-[10px] text-gray-400 mt-2 leading-relaxed">Pilih file PDF untuk otomatis mengisi jumlah halaman dan konten teks halaman di bawah.</p>
                            <!-- Spinner parsing -->
                            <div id="pdf-loading-indicator" class="hidden items-center gap-2 mt-2 text-[#4D9BE2] font-semibold text-xs animate-pulse">
                                <svg class="animate-spin h-4 w-4 text-[#4D9BE2]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Sedang membaca dan mengekstrak isi PDF...</span>
                            </div>
                        </div>

                        <div class="flex flex-col text-left w-full">
                            <label for="halaman" class="text-[11px] font-bold uppercase tracking-wider mb-2">Jumlah Halaman E-Book <span class="text-rose-500">*</span></label>
                            <input type="number" name="halaman" id="halaman" value="{{ old('halaman', $buku->halaman) }}" min="1" max="100" placeholder="Contoh: 10" style="background-color: #FCFCFC" class="w-full px-4 py-2.5 border border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
                            <p class="text-[10px] text-gray-400 mt-2 leading-relaxed">Maksimal 100 halaman.</p>
                            @error('halaman') <span class="text-xs text-rose-500 mt-1 font-medium block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex flex-col text-left w-full mt-2">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-2">
                            <label class="text-[11px] font-bold uppercase tracking-wider">Isi Konten per Halaman <span class="text-rose-500">*</span></label>
                            
                            <!-- Page Navigation Bar -->
                            <div id="ebook-page-nav" class="hidden items-center gap-2">
                                <button type="button" id="btn-prev-page" class="px-2.5 py-1.5 bg-white hover:bg-gray-100 text-gray-600 rounded-lg text-xs font-bold border border-gray-200 transition-all flex items-center gap-1 cursor-pointer">
                                    &larr; Prev
                                </button>
                                <select id="ebook-page-select" class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-bold text-[#2F3951] focus:outline-none cursor-pointer">
                                    <!-- Options populated dynamically -->
                                </select>
                                <button type="button" id="btn-next-page" class="px-2.5 py-1.5 bg-white hover:bg-gray-100 text-gray-600 rounded-lg text-xs font-bold border border-gray-200 transition-all flex items-center gap-1 cursor-pointer">
                                    Next &rarr;
                                </button>
                            </div>
                        </div>

                        <div id="pages-container" class="grid grid-cols-1 gap-4 border border-gray-100 rounded-xl p-3 bg-gray-50/50">
                            <!-- Dynamically generated page textareas go here -->
                        </div>
                        @error('ebook_contents') <span class="text-xs text-rose-500 mt-1 font-medium block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Daftar Isi / Bab Section -->
                    <div class="flex flex-col text-left w-full mt-4 border-t border-gray-100 pt-4">
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-[11px] font-bold uppercase tracking-wider">Daftar Isi / Bab E-Book <span class="text-xs font-normal text-gray-400 lowercase">(bisa diisi manual atau terdeteksi otomatis dari PDF)</span></label>
                            <div class="flex items-center gap-2">
                                <button type="button" id="btn-default-chapters" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-bold transition-all flex items-center gap-1">
                                    Reset ke Bab Default
                                </button>
                                <button type="button" id="btn-add-chapter" class="px-3 py-1.5 bg-[#4D9BE2]/10 hover:bg-[#4D9BE2]/20 text-[#4D9BE2] rounded-lg text-xs font-bold transition-all flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    Tambah Bab
                                </button>
                            </div>
                        </div>
                        <div id="chapters-container" class="flex flex-col gap-2.5 max-h-[250px] overflow-y-auto pr-2 bg-gray-50/50 p-3 rounded-xl border border-gray-100">
                            <!-- Dynamically generated chapters will go here -->
                            <p id="no-chapters-placeholder" class="text-xs text-gray-400 text-center py-4">Belum ada bab yang didefinisikan. Silakan tambah bab manual atau unggah PDF.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 w-full flex flex-col text-left">
                <h3 class="text-xs font-bold text-[#2F3951] uppercase text-gray-400 tracking-wider mb-4 flex items-center">
                    <span class="w-1 h-3.5 bg-[#4D9BE2] rounded-full"></span>
                    Manajemen Stok & Status Logistik
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
                    <div class="flex flex-col text-left w-full" id="stok-wrapper">
                        <label for="stok" class="text-[11px] font-bold uppercase tracking-wider mb-2">Jumlah Stok Fisik Buku <span class="text-rose-500">*</span></label>
                        <input type="number" name="stok" id="stok" value="{{ old('stok', $buku->stok) }}" required placeholder="Contoh: 10" style="background-color: #FCFCFC" class="w-full px-4 py-2.5 border border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
                        @error('stok') <span class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col text-left w-full" id="lokasi-rak-wrapper">
                        <label for="lokasi_rak" class="text-[11px] font-bold uppercase tracking-wider mb-2">Lokasi Penempatan Rak <span class="text-rose-500">*</span></label>
                        <input type="text" name="lokasi_rak" id="lokasi_rak" value="{{ old('lokasi_rak', $buku->lokasi_rak) }}" required placeholder="Contoh: Rak A-02" style="background-color: #FCFCFC" class="w-full px-4 py-2.5 border border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
                        @error('lokasi_rak') <span class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col text-left w-full">
                        <label for="status" class="text-[11px] font-bold uppercase tracking-wider mb-2">Status Visibilitas Buku <span class="text-rose-500">*</span></label>
                        <div class="relative w-full">
                            <select name="status" id="status" required style="background-color: #FCFCFC" class="w-full px-4 py-2.5 border border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all appearance-none cursor-pointer">
                                <option value="aktif" {{ old('status', $buku->status) == 'aktif' ? 'selected' : '' }}>Aktif (Muncul di Member)</option>
                                <option value="non aktif" {{ old('status', $buku->status) == 'non aktif' ? 'selected' : '' }}>Non Aktif (Sembunyikan dari Member)</option>
                                <option value="dipinjam" {{ old('status', $buku->status) == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>
                        </div>
                        @error('status') <span class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="w-full flex items-center justify-end gap-3 pt-5 mt-2">
                <a href="{{ route(auth()->user()->role . '.buku.index') }}" class="px-5 py-2.5 text-gray-500 font-bold rounded-xl text-xs transition-colors whitespace-nowrap">
                    Batal
                </a>
                
                <button type="submit" class="px-6 py-2.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white font-bold rounded-xl text-xs shadow-sm transition-colors duration-200 whitespace-nowrap">
                    Perbarui Data Buku
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<script>
    // Set worker source for pdf.js
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

    document.addEventListener('DOMContentLoaded', function() {
        const kategoriSelect = document.getElementById('kategori_id');
        const stokWrapper = document.getElementById('stok-wrapper');
        const lokasiRakWrapper = document.getElementById('lokasi-rak-wrapper');
        const ebookPagesSection = document.getElementById('ebook-pages-section');
        const pagesContainer = document.getElementById('pages-container');
        const halamanInput = document.getElementById('halaman');
        const stokInput = document.getElementById('stok');
        const lokasiRakInput = document.getElementById('lokasi_rak');
        const pdfFileInput = document.getElementById('pdf_file');
        const pdfLoadingIndicator = document.getElementById('pdf-loading-indicator');
        const chaptersContainer = document.getElementById('chapters-container');
        const btnAddChapter = document.getElementById('btn-add-chapter');
        const btnDefaultChapters = document.getElementById('btn-default-chapters');

        const oldEbookContents = @json(old('ebook_contents') ?? $buku->ebookPages->pluck('content', 'page_number')->toArray() ?? (object)[]);
        const oldHalaman = @json(old('halaman'));
        const oldEbookChapters = @json(old('ebook_chapters') ?? $buku->ebookChapters->toArray() ?? []);

        // Storage for temporary PDF extracted contents
        let pdfExtractedContents = {};
        let chapterCounter = 0;

        const defaultChapterTitles = [
            'Pendahuluan & Pengantar Utama',
            'Dasar-Dasar Konseptual & Teori',
            'Analisis Awal & Pemahaman Kasus',
            'Implementasi Praktis & Metodologi',
            'Studi Kasus & Eksperimen Lapangan',
            'Hasil Pengamatan & Analisis Data',
            'Diskusi Mendalam & Temuan Utama',
            'Tantangan, Hambatan & Solusi Alternatif',
            'Rencana Pengembangan Masa Depan',
            'Kesimpulan Akhir & Daftar Pustaka'
        ];

        function toggleFields() {
            const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
            const isEbook = selectedOption ? selectedOption.getAttribute('data-is-ebook') === '1' : false;

            if (isEbook) {
                if (stokWrapper) stokWrapper.classList.add('hidden');
                if (lokasiRakWrapper) lokasiRakWrapper.classList.add('hidden');
                if (ebookPagesSection) ebookPagesSection.classList.remove('hidden');

                if (stokInput) stokInput.removeAttribute('required');
                if (lokasiRakInput) lokasiRakInput.removeAttribute('required');
                if (halamanInput) {
                    halamanInput.setAttribute('required', 'required');
                    halamanInput.setAttribute('min', '1');
                    halamanInput.setAttribute('max', '100');
                }

                generatePageInputs();
            } else {
                if (stokWrapper) stokWrapper.classList.remove('hidden');
                if (lokasiRakWrapper) lokasiRakWrapper.classList.remove('hidden');
                if (ebookPagesSection) ebookPagesSection.classList.add('hidden');

                if (stokInput) stokInput.setAttribute('required', 'required');
                if (lokasiRakInput) lokasiRakInput.setAttribute('required', 'required');
                if (halamanInput) {
                    halamanInput.removeAttribute('required');
                    halamanInput.removeAttribute('min');
                    halamanInput.removeAttribute('max');
                }
                pagesContainer.innerHTML = '';
                if (chaptersContainer) {
                    chaptersContainer.innerHTML = '';
                    showPlaceholder();
                }
            }
        }

        let activeEbookPage = 1;

        function updatePageSelectOptions() {
            const pageSelect = document.getElementById('ebook-page-select');
            const pageNav = document.getElementById('ebook-page-nav');
            if (!pageSelect || !pageNav) return;
            
            let pageCount = parseInt(halamanInput.value) || 0;
            if (pageCount <= 0) {
                pageNav.classList.add('hidden');
                pageNav.classList.remove('flex');
                return;
            }
            
            pageNav.classList.remove('hidden');
            pageNav.classList.add('flex');
            
            pageSelect.innerHTML = '';
            for (let i = 1; i <= pageCount; i++) {
                const opt = document.createElement('option');
                opt.value = i;
                opt.textContent = `Halaman ${i}`;
                pageSelect.appendChild(opt);
            }
            
            if (activeEbookPage > pageCount) {
                activeEbookPage = pageCount || 1;
            }
            pageSelect.value = activeEbookPage;
        }

        function updateActivePageVisibility() {
            let pageCount = parseInt(halamanInput.value) || 0;
            if (activeEbookPage > pageCount) activeEbookPage = pageCount || 1;
            if (activeEbookPage < 1) activeEbookPage = 1;

            for (let i = 1; i <= pageCount; i++) {
                const pageDiv = document.getElementById(`ebook-page-wrapper-${i}`);
                if (pageDiv) {
                    if (i === activeEbookPage) {
                        pageDiv.classList.remove('hidden');
                    } else {
                        pageDiv.classList.add('hidden');
                    }
                }
            }

            const btnPrev = document.getElementById('btn-prev-page');
            const btnNext = document.getElementById('btn-next-page');
            const pageSelect = document.getElementById('ebook-page-select');

            if (btnPrev) btnPrev.disabled = (activeEbookPage <= 1);
            if (btnNext) btnNext.disabled = (activeEbookPage >= pageCount);
            if (pageSelect) pageSelect.value = activeEbookPage;
        }

        function generatePageInputs(forcePdfValues = false) {
            let pageCount = parseInt(halamanInput.value) || 0;
            if (pageCount < 0) pageCount = 0;
            if (pageCount > 100) {
                pageCount = 100;
                halamanInput.value = 100;
            }

            const currentContents = {};
            const textareas = pagesContainer.querySelectorAll('textarea');
            textareas.forEach(ta => {
                const pageNum = ta.getAttribute('data-page');
                if (pageNum) {
                    currentContents[pageNum] = ta.value;
                }
            });

            pagesContainer.innerHTML = '';

            for (let i = 1; i <= pageCount; i++) {
                // Determine content value
                let val = '';
                if (forcePdfValues) {
                    val = pdfExtractedContents[i] || '';
                } else {
                    val = oldEbookContents[i] || pdfExtractedContents[i] || currentContents[i] || '';
                }
                
                const pageDiv = document.createElement('div');
                pageDiv.id = `ebook-page-wrapper-${i}`;
                pageDiv.className = 'flex flex-col text-left bg-white border border-gray-150 p-3.5 rounded-xl shadow-sm hover:border-[#4D9BE2]/30 transition-all';
                pageDiv.innerHTML = `
                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5 flex items-center justify-between">
                        <span>Halaman ${i}</span>
                        <span class="text-[9px] text-[#4D9BE2] bg-[#4D9BE2]/10 px-1.5 py-0.5 rounded font-semibold">Digital Page</span>
                    </label>
                    <textarea 
                        name="ebook_contents[${i}]" 
                        data-page="${i}"
                        rows="6" 
                        required 
                        placeholder="Tuliskan isi konten untuk halaman ${i}..." 
                        class="w-full px-3 py-2 border border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-lg text-xs text-[#2F3951] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#4D9BE2]/5 transition-all"
                    >${val}</textarea>
                `;
                pagesContainer.appendChild(pageDiv);
            }

            updatePageSelectOptions();
            updateActivePageVisibility();
        }

        // Chapters Management
        function addChapterRow(title = '', startPage = '') {
            const placeholder = document.getElementById('no-chapters-placeholder');
            if (placeholder) placeholder.remove();

            const id = chapterCounter++;
            const rowDiv = document.createElement('div');
            rowDiv.className = 'flex items-center gap-3 w-full bg-white p-2 rounded-lg border border-gray-150 shadow-sm';
            rowDiv.id = `chapter-row-${id}`;
            rowDiv.innerHTML = `
                <div class="flex-1">
                    <input type="text" name="ebook_chapters[${id}][title]" value="${title}" placeholder="Contoh: Bab 1: Pendahuluan" required class="w-full px-3 py-1.5 border border-gray-200 focus:border-[#4D9BE2]/50 rounded-lg text-xs text-[#2F3951] focus:outline-none">
                </div>
                <div class="w-24 flex-shrink-0">
                    <input type="number" name="ebook_chapters[${id}][start_page]" value="${startPage}" placeholder="Hal" required min="1" class="w-full px-3 py-1.5 border border-gray-200 focus:border-[#4D9BE2]/50 rounded-lg text-xs text-[#2F3951] focus:outline-none">
                </div>
                <button type="button" class="btn-remove-chapter text-rose-500 hover:text-rose-700 p-1.5 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                    </svg>
                </button>
            `;

            // Add delete event listener
            rowDiv.querySelector('.btn-remove-chapter').addEventListener('click', function() {
                rowDiv.remove();
                if (chaptersContainer.querySelectorAll('[id^="chapter-row-"]').length === 0) {
                    showPlaceholder();
                }
            });

            chaptersContainer.appendChild(rowDiv);
        }

        function generateDefaultChapters() {
            let pageCount = parseInt(halamanInput.value) || 0;
            if (pageCount <= 0) return;
            if (pageCount > 100) pageCount = 100;

            chaptersContainer.innerHTML = '';
            const pagesPerChapter = Math.ceil(pageCount / 10);
            for (let i = 0; i < 10; i++) {
                const startPage = (i * pagesPerChapter) + 1;
                if (startPage > pageCount) break;
                addChapterRow(`${i + 1}. ${defaultChapterTitles[i]}`, startPage);
            }
        }

        function showPlaceholder() {
            chaptersContainer.innerHTML = `<p id="no-chapters-placeholder" class="text-xs text-gray-400 text-center py-4">Belum ada bab yang didefinisikan. Silakan tambah bab manual atau unggah PDF.</p>`;
        }

        if (btnAddChapter) {
            btnAddChapter.addEventListener('click', () => addChapterRow('', ''));
        }

        if (btnDefaultChapters) {
            btnDefaultChapters.addEventListener('click', generateDefaultChapters);
        }

        kategoriSelect.addEventListener('change', toggleFields);
        
        halamanInput.addEventListener('input', () => {
            generatePageInputs(false);
            const hasChapters = chaptersContainer.querySelectorAll('[id^="chapter-row-"]').length > 0;
            if (!hasChapters) {
                generateDefaultChapters();
            }
        });

        // Navigation Bar Listeners
        const btnPrev = document.getElementById('btn-prev-page');
        const btnNext = document.getElementById('btn-next-page');
        const pageSelect = document.getElementById('ebook-page-select');

        if (btnPrev) {
            btnPrev.addEventListener('click', function() {
                if (activeEbookPage > 1) {
                    activeEbookPage--;
                    updateActivePageVisibility();
                }
            });
        }
        if (btnNext) {
            btnNext.addEventListener('click', function() {
                let pageCount = parseInt(halamanInput.value) || 0;
                if (activeEbookPage < pageCount) {
                    activeEbookPage++;
                    updateActivePageVisibility();
                }
            });
        }
        if (pageSelect) {
            pageSelect.addEventListener('change', function() {
                activeEbookPage = parseInt(this.value) || 1;
                updateActivePageVisibility();
            });
        }

        // PDF Auto-Extraction handler
        if (pdfFileInput) {
            pdfFileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file || file.type !== 'application/pdf') return;

                if (pdfLoadingIndicator) {
                    pdfLoadingIndicator.classList.remove('hidden');
                    pdfLoadingIndicator.classList.add('flex');
                }

                const fileReader = new FileReader();
                fileReader.onload = function() {
                    const typedarray = new Uint8Array(this.result);
                    
                    pdfjsLib.getDocument(typedarray).promise.then(function(pdf) {
                        let pageCount = pdf.numPages;
                        if (pageCount > 100) {
                            pageCount = 100;
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Batas Halaman Terlampaui',
                                    text: 'File PDF memiliki lebih dari 100 halaman. Hanya 100 halaman pertama yang akan diimpor.',
                                    confirmButtonColor: '#4D9BE2'
                                });
                            }
                        }
                        
                        halamanInput.value = pageCount;
                        pdfExtractedContents = {};
                        
                        const extractPromises = [];
                        for (let i = 1; i <= pageCount; i++) {
                            extractPromises.push(
                                pdf.getPage(i).then(function(page) {
                                    return page.getTextContent().then(function(textContent) {
                                        const textItems = textContent.items;
                                        return {
                                            pageNumber: i,
                                            content: textItems.map(item => item.str).join(' ').trim().replace(/\s+/g, ' ')
                                        };
                                    });
                                })
                            );
                        }
                        
                        Promise.all(extractPromises).then(function(pagesData) {
                            pagesData.forEach(data => {
                                pdfExtractedContents[data.pageNumber] = data.content;
                            });
                            
                            generatePageInputs(true);

                            // Auto-detect chapters from pagesData
                            const detectedChapters = [];
                            pagesData.forEach(data => {
                                const text = data.content.trim();
                                const prefix = text.substring(0, 100).trim();
                                const chapterRegex = /^(bab\s+[i|v|x|\d]+|chapter\s+\d+|pendahuluan|kesimpulan|daftar\s+pustaka|penutup)/i;
                                const match = prefix.match(chapterRegex);
                                if (match) {
                                    const firstLine = prefix.split(/[.\n]/)[0].trim();
                                    if (firstLine.length > 3 && firstLine.length < 80) {
                                        detectedChapters.push({
                                            title: firstLine,
                                            start_page: data.pageNumber
                                        });
                                    }
                                }
                            });

                            if (detectedChapters.length > 0) {
                                chaptersContainer.innerHTML = '';
                                detectedChapters.forEach(ch => {
                                    addChapterRow(ch.title, ch.start_page);
                                });
                            } else {
                                // Fallback to proportional generation
                                generateDefaultChapters();
                            }
                            
                            if (pdfLoadingIndicator) {
                                pdfLoadingIndicator.classList.add('hidden');
                                pdfLoadingIndicator.classList.remove('flex');
                            }
                            
                            if (typeof Swal !== 'undefined') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'PDF Berhasil Dibaca',
                                    text: `Konten teks sebanyak ${pageCount} halaman berhasil diekstrak!`,
                                    confirmButtonColor: '#4D9BE2',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }).catch(function(error) {
                        console.error(error);
                        if (pdfLoadingIndicator) {
                            pdfLoadingIndicator.classList.add('hidden');
                            pdfLoadingIndicator.classList.remove('flex');
                        }
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Membaca PDF',
                                text: 'Terjadi kesalahan saat membaca file PDF. Pastikan file tidak rusak.',
                                confirmButtonColor: '#4D9BE2'
                            });
                        }
                    });
                };
                fileReader.readAsArrayBuffer(file);
            });
        }

        // Load existing chapters on load
        if (oldEbookChapters && oldEbookChapters.length > 0) {
            chaptersContainer.innerHTML = '';
            oldEbookChapters.forEach(ch => {
                const title = ch.title || '';
                const startPage = ch.start_page || '';
                if (title && startPage) {
                    addChapterRow(title, startPage);
                }
            });
        }

        if (oldHalaman) {
            halamanInput.value = oldHalaman;
        }
        toggleFields();
    });
</script>
@endpush