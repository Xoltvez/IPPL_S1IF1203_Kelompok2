@extends('layouts.app')

@section('title', 'Edit Data Buku')

@section('content')
<div class="w-full flex flex-col text-left md:px-2 max-w mx-auto Box-border">

    <div class="w-full mb-6">
        <h1 class="text-xl font-bold text-[#2F3951] tracking-tight">Edit Informasi Buku</h1>
        <p class="text-gray-400 text-xs mt-2">Perbarui detail data aset koleksi buku MacaBae yang dipilih.</p>
    </div>

    <div class="w-full bg-white rounded-2xl border border-gray-200 shadow overflow-hidden">
        
        <form action="{{ route('pustakawan.buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 w-full m-0 flex flex-col gap-6">
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
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id', $buku->kategori_id) == $kategori->id ? 'selected' : '' }}>
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
                    Manajemen Stok & Status Logistik
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 w-full">
                    <div class="flex flex-col text-left w-full">
                        <label for="stok" class="text-[11px] font-bold uppercase tracking-wider mb-2">Jumlah Stok Fisik Buku <span class="text-rose-500">*</span></label>
                        <input type="number" name="stok" id="stok" value="{{ old('stok', $buku->stok) }}" required placeholder="Contoh: 10" style="background-color: #FCFCFC" class="w-full px-4 py-2.5 border border-gray-200 focus:border-[#4D9BE2]/50 focus:bg-white rounded-xl text-sm text-[#2F3951] placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-[#4D9BE2]/5 transition-all">
                        @error('stok') <span class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex flex-col text-left w-full">
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
                <a href="{{ route('pustakawan.buku.index') }}" class="px-5 py-2.5 text-gray-500 font-bold rounded-xl text-xs transition-colors whitespace-nowrap">
                    Kembali
                </a>
                
                <button type="submit" class="px-6 py-2.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white font-bold rounded-xl text-xs shadow-sm transition-colors duration-200 whitespace-nowrap">
                    Perbarui Data Buku
                </button>
            </div>

        </form>
    </div>
</div>
@endsection