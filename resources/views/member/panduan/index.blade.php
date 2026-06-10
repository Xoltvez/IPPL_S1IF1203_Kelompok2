@extends('layouts.app')

@section('title', 'Panduan Aplikasi')

@section('content')
<div class="w-full flex flex-col Box-border"
     x-data="{
        activeTab: 'daftar-login',
        searchQuery: '',
        guides: [
            {
                category: 'daftar-login',
                title: 'Cara Daftar Akun',
                icon: '📝',
                steps: [
                    'Buka halaman Daftar.',
                    'Isi data diri (Nama, Email, No. Telepon).',
                    'Pastikan data yang diisi sudah benar.',
                    'Klik tombol Daftar.',
                    'Tunggu notifikasi pendaftaran berhasil.',
                    'Setelah terdaftar, lanjut ke halaman Login.'
                ]
            },
            {
                category: 'daftar-login',
                title: 'Cara Login',
                icon: '🔐',
                steps: [
                    'Buka halaman Login.',
                    'Masukkan Email / Username dan Password.',
                    'Klik tombol Login.',
                    'Jika data benar, kamu akan masuk ke dashboard.'
                ]
            },
            {
                category: 'daftar-login',
                title: 'Lupa Password',
                icon: '❓',
                steps: [
                    'Klik Lupa Password di halaman login.',
                    'Masukkan email yang terdaftar.',
                    'Cek email untuk tautan reset password.',
                    'Buat password baru dan login kembali.'
                ]
            },
            {
                category: 'cari-buku',
                title: 'Mencari Buku di Katalog',
                icon: '🔍',
                steps: [
                    'Buka menu Katalog di sidebar kiri.',
                    'Ketik judul buku, pengarang, atau kata kunci lainnya pada kolom pencarian di bagian atas.',
                    'Tekan Enter atau klik ikon cari untuk menampilkan hasil.'
                ]
            },
            {
                category: 'cari-buku',
                title: 'Menggunakan Filter Kategori',
                icon: '🏷️',
                steps: [
                    'Pada halaman Katalog, Anda akan melihat daftar kategori di bawah bilah pencarian.',
                    'Klik salah satu kategori (misalnya: E-Book, Fiksi, Komik) untuk menyaring daftar buku.',
                    'Klik kategori lagi untuk membersihkan filter.'
                ]
            },
            {
                category: 'pinjam-buku',
                title: 'Mengajukan Peminjaman Buku',
                icon: '📖',
                steps: [
                    'Cari buku yang Anda inginkan di Katalog, lalu klik cover buku untuk membuka detail.',
                    'Di bagian detail buku, pilih durasi peminjaman (1 hingga 7 hari).',
                    'Klik tombol Pinjam buku. Status transaksi akan menjadi Menunggu Persetujuan.'
                ]
            },
            {
                category: 'pinjam-buku',
                title: 'Alur Persetujuan Pustakawan',
                icon: '⏳',
                steps: [
                    'Setiap pengajuan pinjam harus disetujui oleh Pustakawan di meja sirkulasi.',
                    'Selama status masih Menunggu Persetujuan, Anda dapat membatalkan pengajuan secara mandiri.',
                    'Setelah disetujui, status berubah menjadi Dipinjam dan stok buku otomatis berkurang.'
                ]
            },
            {
                category: 'reservasi-buku',
                title: 'Reservasi Buku Sedang Dipinjam',
                icon: '📅',
                steps: [
                    'Jika buku yang ingin Anda pinjam sedang habis atau dipinjam anggota lain, Anda dapat melakukan reservasi.',
                    'Klik tombol Reservasi di halaman detail buku yang stoknya kosong.',
                    'Buku akan masuk daftar antrean Anda dan Anda akan diberi tahu saat buku siap diambil.'
                ]
            },
            {
                category: 'pengembalian-perpanjangan',
                title: 'Mengembalikan Buku',
                icon: '🔄',
                steps: [
                    'Masuk ke menu Peminjaman buku di sidebar kiri.',
                    'Cari buku yang ingin Anda kembalikan dari daftar aktif.',
                    'Klik tombol Kembalikan untuk melaporkan pengembalian ke sistem.'
                ]
            },
            {
                category: 'pengembalian-perpanjangan',
                title: 'Batas Waktu & Keterlambatan',
                icon: '⏰',
                steps: [
                    'Pastikan Anda mengembalikan buku sebelum tanggal jatuh tempo yang tertera di badge biru.',
                    'Keterlambatan akan dikenakan denda akumulatif per hari secara otomatis.'
                ]
            },
            {
                category: 'denda',
                title: 'Ketentuan Denda Keterlambatan',
                icon: '💸',
                steps: [
                    'Keterlambatan mengembalikan buku dikenakan denda sebesar Rp 1.000 per hari untuk setiap buku.',
                    'Total denda akan tampil di dashboard Anda secara real-time.'
                ]
            },
            {
                category: 'denda',
                title: 'Cara Pembayaran Denda',
                icon: '💳',
                steps: [
                    'Pembayaran denda hanya dapat dilakukan secara tunai/langsung kepada Pustakawan di meja sirkulasi.',
                    'Setelah membayar, Pustakawan akan memperbarui status denda Anda menjadi Lunas di sistem perpustakaan.'
                ]
            },
            {
                category: 'pengaturan-akun',
                title: 'Mengubah Foto Profil',
                icon: '🖼️',
                steps: [
                    'Masuk ke halaman Pengaturan -> Profil.',
                    'Klik Rubah foto, lalu pilih gambar dari perangkat Anda (maksimal 2MB).',
                    'Foto Anda akan diperbarui di dashboard dan header navbar atas.'
                ]
            },
            {
                category: 'pengaturan-akun',
                title: 'Mengubah Data Akun & Sandi',
                icon: '⚙️',
                steps: [
                    'Klik tombol ganti di sebelah kolom Nama, Email, Nomor Telepon, atau Kata Sandi.',
                    'Isi data baru pada pop-up modal yang muncul, lalu klik Simpan Perubahan.'
                ]
            },
            {
                category: 'faq-bantuan',
                title: 'Menghubungi Layanan Bantuan',
                icon: '📞',
                steps: [
                    'Jika Anda menemui kendala teknis atau masalah sirkulasi, hubungi kami di email help@macabae.com.',
                    'Anda juga dapat mengunjungi meja bantuan perpustakaan pada jam operasional kerja.'
                ]
            },
            {
                category: 'faq-bantuan',
                title: 'Pusat Bantuan Cepat',
                icon: '💡',
                steps: [
                    'Gunakan kolom pencarian di bagian atas halaman Panduan ini untuk mencari solusi instan atas kendala Anda.'
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
                   placeholder="Cari kendalamu" 
                   class="w-full pl-12 pr-4 py-3.5 bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 focus:border-[#4D9BE2] focus:ring-1 focus:ring-[#4D9BE2] rounded-2xl text-sm transition-all outline-none dark:text-slate-100 shadow-sm">
        </div>

        <!-- Search Button -->
        <button class="px-6 py-3.5 bg-[#4D9BE2] hover:bg-[#3D8BCF] text-white rounded-2xl shadow-sm text-sm font-bold transition-all transform active:scale-95 flex items-center justify-center whitespace-nowrap cursor-pointer">
            Cari
        </button>
    </div>

    <!-- Main Container -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
        
        <!-- Left Sub-menu / Navigation (Tabs) -->
        <div class="lg:col-span-1 bg-white dark:bg-slate-900 rounded-3xl border border-gray-100 dark:border-slate-800 p-6 shadow-sm space-y-1.5 h-fit">
            <button @click="activeTab = 'daftar-login'; searchQuery = ''" 
                    :class="activeTab === 'daftar-login' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                Daftar & Login
            </button>
            <button @click="activeTab = 'cari-buku'; searchQuery = ''" 
                    :class="activeTab === 'cari-buku' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                Cari Buku
            </button>
            <button @click="activeTab = 'pinjam-buku'; searchQuery = ''" 
                    :class="activeTab === 'pinjam-buku' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                Pinjam Buku
            </button>
            <button @click="activeTab = 'reservasi-buku'; searchQuery = ''" 
                    :class="activeTab === 'reservasi-buku' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                Reservasi Buku
            </button>
            <button @click="activeTab = 'pengembalian-perpanjangan'; searchQuery = ''" 
                    :class="activeTab === 'pengembalian-perpanjangan' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                Pengembalian & Perpanjangan
            </button>
            <button @click="activeTab = 'denda'; searchQuery = ''" 
                    :class="activeTab === 'denda' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                Denda
            </button>
            <button @click="activeTab = 'pengaturan-akun'; searchQuery = ''" 
                    :class="activeTab === 'pengaturan-akun' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                Pengaturan Akun
            </button>
            <button @click="activeTab = 'faq-bantuan'; searchQuery = ''" 
                    :class="activeTab === 'faq-bantuan' && searchQuery === '' ? 'bg-[#4D9BE2] text-white shadow-sm' : 'text-gray-500 dark:text-slate-400 hover:bg-[#F3F7FB] dark:hover:bg-slate-800 hover:text-[#4D9BE2] dark:hover:text-[#4D9BE2]'" 
                    class="w-full text-left px-5 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 cursor-pointer">
                FAQ & Bantuan
            </button>
        </div>

        <!-- Right Content Section -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Search status message -->
            <template x-if="searchQuery !== ''">
                <div class="text-xs text-gray-500 dark:text-slate-400 font-medium">
                    Menampilkan hasil pencarian untuk "<span class="font-bold text-[#4D9BE2]" x-text="searchQuery"></span>":
                </div>
            </template>

            <!-- Guide Cards -->
            <div class="space-y-6 min-h-[400px]">
                <template x-for="(guide, index) in filteredGuides()" :key="index">
                    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 p-6 rounded-3xl shadow-sm space-y-4 transition-all hover:shadow-md">
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
                            <h4 class="text-sm font-bold text-[#2F3951] dark:text-slate-200">Panduan tidak ditemukan</h4>
                            <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">
                                Coba ketikkan kata kunci lain atau pilih salah satu kategori di menu sebelah kiri.
                            </p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
