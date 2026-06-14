@extends('layouts.app')

@section('title', __('Panduan Aplikasi'))

@section('content')
<div class="w-full flex flex-col Box-border"
     x-data="{
        activeTab: 'daftar-login',
        searchQuery: '',
        guides: [
            {
                category: 'daftar-login',
                title: '{{ __('Cara Daftar Akun') }}',
                icon: '📝',
                steps: [
                    '{{ __('Buka halaman Daftar.') }}',
                    '{{ __('Isi data diri (Nama, Email, No. Telepon).') }}',
                    '{{ __('Pastikan data yang diisi sudah benar.') }}',
                    '{{ __('Klik tombol Daftar.') }}',
                    '{{ __('Tunggu notifikasi pendaftaran berhasil.') }}',
                    '{{ __('Setelah terdaftar, lanjut ke halaman Login.') }}'
                ]
            },
            {
                category: 'daftar-login',
                title: '{{ __('Cara Login') }}',
                icon: '🔐',
                steps: [
                    '{{ __('Buka halaman Login.') }}',
                    '{{ __('Masukkan Email / Username dan Password.') }}',
                    '{{ __('Klik tombol Login.') }}',
                    '{{ __('Jika data benar, kamu akan masuk ke dashboard.') }}'
                ]
            },
            {
                category: 'daftar-login',
                title: '{{ __('Lupa Password') }}',
                icon: '❓',
                steps: [
                    '{{ __('Klik Lupa Password di halaman login.') }}',
                    '{{ __('Masukkan email yang terdaftar.') }}',
                    '{{ __('Cek email untuk tautan reset password.') }}',
                    '{{ __('Buat password baru dan login kembali.') }}'
                ]
            },
            {
                category: 'cari-buku',
                title: '{{ __('Mencari Buku di Katalog') }}',
                icon: '🔍',
                steps: [
                    '{{ __('Buka menu Katalog di sidebar kiri.') }}',
                    '{{ __('Ketik judul buku, pengarang, atau kata kunci lainnya pada kolom pencarian di bagian atas.') }}',
                    '{{ __('Tekan Enter atau klik ikon cari untuk menampilkan hasil.') }}'
                ]
            },
            {
                category: 'cari-buku',
                title: '{{ __('Menggunakan Filter Kategori') }}',
                icon: '🏷️',
                steps: [
                    '{{ __('Pada halaman Katalog, Anda akan melihat daftar kategori di bawah bilah pencarian.') }}',
                    '{{ __('Klik salah satu kategori (misalnya: E-Book, Fiksi, Komik) untuk menyaring daftar buku.') }}',
                    '{{ __('Klik kategori lagi untuk membersihkan filter.') }}'
                ]
            },
            {
                category: 'pinjam-buku',
                title: '{{ __('Mengajukan Peminjaman Buku') }}',
                icon: '📖',
                steps: [
                    '{{ __('Cari buku yang Anda inginkan di Katalog, lalu klik cover buku untuk membuka detail.') }}',
                    '{{ __('Di bagian detail buku, pilih durasi peminjaman (1 hingga 7 hari).') }}',
                    '{{ __('Klik tombol Pinjam buku. Status transaksi akan menjadi Menunggu Persetujuan.') }}'
                ]
            },
            {
                category: 'pinjam-buku',
                title: '{{ __('Alur Persetujuan Pustakawan') }}',
                icon: '⏳',
                steps: [
                    '{{ __('Setiap pengajuan pinjam harus disetujui oleh Pustakawan di meja sirkulasi.') }}',
                    '{{ __('Selama status masih Menunggu Persetujuan, Anda dapat membatalkan pengajuan secara mandiri.') }}',
                    '{{ __('Setelah disetujui, status berubah menjadi Dipinjam dan stok buku otomatis berkurang.') }}'
                ]
            },
            {
                category: 'reservasi-buku',
                title: '{{ __('Reservasi Buku Sedang Dipinjam') }}',
                icon: '📅',
                steps: [
                    '{{ __('Jika buku yang ingin Anda pinjam sedang habis atau dipinjam anggota lain, Anda dapat melakukan reservasi.') }}',
                    '{{ __('Klik tombol Reservasi di halaman detail buku yang stoknya kosong.') }}',
                    '{{ __('Buku akan masuk daftar antrean Anda dan Anda akan diberi tahu saat buku siap diambil.') }}'
                ]
            },
            {
                category: 'pengembalian-perpanjangan',
                title: '{{ __('Mengembalikan Buku') }}',
                icon: '🔄',
                steps: [
                    '{{ __('Masuk ke menu Peminjaman buku di sidebar kiri.') }}',
                    '{{ __('Cari buku yang ingin Anda kembalikan dari daftar aktif.') }}',
                    '{{ __('Klik tombol Kembalikan untuk melaporkan pengembalian ke sistem.') }}'
                ]
            },
            {
                category: 'pengembalian-perpanjangan',
                title: '{{ __('Batas Waktu & Keterlambatan') }}',
                icon: '⏰',
                steps: [
                    '{{ __('Pastikan Anda mengembalikan buku sebelum tanggal jatuh tempo yang tertera di badge biru.') }}',
                    '{{ __('Keterlambatan akan dikenakan denda akumulatif per hari secara otomatis.') }}'
                ]
            },
            {
                category: 'denda',
                title: '{{ __('Ketentuan Denda Keterlambatan') }}',
                icon: '💸',
                steps: [
                    '{{ __('Keterlambatan mengembalikan buku dikenakan denda sebesar Rp 1.000 per hari untuk setiap buku.') }}',
                    '{{ __('Total denda akan tampil di dashboard Anda secara real-time.') }}'
                ]
            },
            {
                category: 'denda',
                title: '{{ __('Cara Pembayaran Denda') }}',
                icon: '💳',
                steps: [
                    '{{ __('Pembayaran denda hanya dapat dilakukan secara tunai/langsung kepada Pustakawan di meja sirkulasi.') }}',
                    '{{ __('Setelah membayar, Pustakawan akan memperbarui status denda Anda menjadi Lunas di sistem perpustakaan.') }}'
                ]
            },
            {
                category: 'pengaturan-akun',
                title: '{{ __('Mengubah Foto Profil') }}',
                icon: '🖼️',
                steps: [
                    '{{ __('Masuk ke halaman Pengaturan -> Profil.') }}',
                    '{{ __('Klik Rubah foto, lalu pilih gambar dari perangkat Anda (maksimal 2MB).') }}',
                    '{{ __('Foto Anda akan diperbarui di dashboard dan header navbar atas.') }}'
                ]
            },
            {
                category: 'pengaturan-akun',
                title: '{{ __('Mengubah Data Akun & Sandi') }}',
                icon: '⚙️',
                steps: [
                    '{{ __('Klik tombol ganti di sebelah kolom Nama, Email, Nomor Telepon, atau Kata Sandi.') }}',
                    '{{ __('Isi data baru pada pop-up modal yang muncul, lalu klik Simpan Perubahan.') }}'
                ]
            },
            {
                category: 'faq-bantuan',
                title: '{{ __('Menghubungi Layanan Bantuan') }}',
                icon: '📞',
                steps: [
                    '{{ __('Jika Anda menemui kendala teknis atau masalah sirkulasi, hubungi kami di email help@macabae.com.') }}',
                    '{{ __('Anda juga dapat mengunjungi meja bantuan perpustakaan pada jam operasional kerja.') }}'
                ]
            },
            {
                category: 'faq-bantuan',
                title: '{{ __('Pusat Bantuan Cepat') }}',
                icon: '💡',
                steps: [
                    '{{ __('Gunakan kolom pencarian di bagian atas halaman Panduan ini untuk mencari solusi instan atas kendala Anda.') }}'
                ]
            }
        ],
        filteredGuides() {
            if (this.searchQuery.trim() === '') {
                return this.guides.filter(g => g.category === this.activeTab);
            }
            const query = this.searchQuery.toLowerCase();
            return this.guides.filter(g => 
                g.title.toLowerCase().includes(query) || 
                g.steps.some(step => step.toLowerCase().includes(query))
            );
        }
     }">

    <!-- Search & Filter Bar -->
    <div class="mb-8 flex items-center gap-4">
        <!-- Search Input -->
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input type="text" 
                   x-model="searchQuery"
                   placeholder="{{ __('Cari kendalamu') }}" 
                   class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700/50 focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2] rounded-2xl text-sm transition-all outline-none dark:text-slate-100 shadow-sm">
        </div>

        <!-- Search Button -->
        <button class="px-6 py-3.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-2xl shadow-sm text-sm font-bold transition-all transform active:scale-95 flex items-center justify-center whitespace-nowrap cursor-pointer">
            {{ __('Cari') }}
        </button>
    </div>

    <!-- Main Container -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
        
        <!-- Left Sub-menu / Navigation (Tabs) -->
        <div class="lg:col-span-1 bg-white dark:bg-slate-800 rounded-3xl border border-gray-100 dark:border-slate-700/50 p-6 shadow-sm space-y-1.5 h-fit">
            <button @click="activeTab = 'daftar-login'; searchQuery = ''" 
                    :class="activeTab === 'daftar-login' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                {{ __('Daftar & Login') }}
            </button>
            <button @click="activeTab = 'cari-buku'; searchQuery = ''" 
                    :class="activeTab === 'cari-buku' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                {{ __('Cari Buku') }}
            </button>
            <button @click="activeTab = 'pinjam-buku'; searchQuery = ''" 
                    :class="activeTab === 'pinjam-buku' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                {{ __('Pinjam Buku') }}
            </button>
            <button @click="activeTab = 'reservasi-buku'; searchQuery = ''" 
                    :class="activeTab === 'reservasi-buku' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                {{ __('Reservasi Buku') }}
            </button>
            <button @click="activeTab = 'pengembalian-perpanjangan'; searchQuery = ''" 
                    :class="activeTab === 'pengembalian-perpanjangan' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                {{ __('Pengembalian & Perpanjangan') }}
            </button>
            <button @click="activeTab = 'denda'; searchQuery = ''" 
                    :class="activeTab === 'denda' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                {{ __('Denda') }}
            </button>
            <button @click="activeTab = 'pengaturan-akun'; searchQuery = ''" 
                    :class="activeTab === 'pengaturan-akun' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                {{ __('Pengaturan Akun') }}
            </button>
            <button @click="activeTab = 'faq-bantuan'; searchQuery = ''" 
                    :class="activeTab === 'faq-bantuan' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                {{ __('FAQ & Bantuan') }}
            </button>
        </div>

        <!-- Right Content Section -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Search status message -->
            <template x-if="searchQuery !== ''">
                <div class="text-xs text-gray-500 dark:text-slate-400 font-medium">
                    {{ __('Menampilkan hasil pencarian untuk') }} "<span class="font-bold text-[#4D9BE2]" x-text="searchQuery"></span>":
                </div>
            </template>

            <!-- Guide Cards -->
            <div class="space-y-6 min-h-[400px]">
                <template x-for="(guide, index) in filteredGuides()" :key="index">
                    <div class="bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700/50 p-6 rounded-3xl shadow-sm space-y-4 transition-all hover:shadow-md">
                        <h3 class="text-base font-bold text-[#2F3951] dark:text-slate-100 flex items-center gap-2">
                            <span x-text="guide.icon" class="text-xl"></span>
                            <span x-text="guide.title"></span>
                        </h3>
                        
                        <ol class="space-y-2 text-xs text-gray-500 dark:text-slate-400 pl-1 list-none">
                            <template x-for="(step, sIndex) in guide.steps" :key="sIndex">
                                <li class="flex items-start gap-2.5 leading-relaxed">
                                    <span class="font-bold text-[#4D9BE2] min-w-[15px]" x-text="(sIndex + 1) + '.'"></span>
                                    <span x-text="step"></span>
                                </li>
                            </template>
                        </ol>
                    </div>
                </template>

                <!-- Empty State -->
                <template x-if="filteredGuides().length === 0">
                    <div class="flex flex-col items-center justify-center py-20 text-center space-y-4">
                        <div class="text-4xl">🔍</div>
                        <div class="max-w-md">
                            <h4 class="text-sm font-bold text-[#2F3951] dark:text-slate-200">{{ __('Panduan tidak ditemukan') }}</h4>
                            <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">
                                {{ __('Coba ketikkan kata kunci lain atau pilih salah satu kategori di menu sebelah kiri.') }}
                            </p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
