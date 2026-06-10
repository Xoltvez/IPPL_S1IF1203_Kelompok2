<?php

namespace App\Http\Controllers;

use App\Models\Buku; 
use App\Models\Kategori; 
use Illuminate\Http\Request;
// WAJIB: Menggunakan facade Storage untuk hapus-simpan file gambar
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    // Menampilkan daftar buku dengan fitur pencarian & pembagian halaman (Pagination)
    public function index(Request $request)
    {
        $search = $request->get('search');

        $datas = Buku::with('kategori')
            ->when($search, function($query, $search) {
                return $query->where('judul', 'like', "%{$search}%")
                            ->orWhere('isbn', 'like', "%{$search}%")
                            ->orWhere('pengarang', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->withQueryString();
        
        return view('pustakawan.buku.index', compact('datas'));
    }

    // Menampilkan detail buku untuk member
    public function show($id)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        
        $user = auth()->user();
        $isBorrowed = false;
        $isFavorited = false;
        $isReserved = false;
        if ($user) {
            $isBorrowed = \Illuminate\Support\Facades\DB::table('peminjamans')
                ->where('user_id', $user->id)
                ->where('buku_id', $id)
                ->where('status', 'dipinjam')
                ->exists();
                
            $isFavorited = \App\Models\Favorit::where('user_id', $user->id)
                ->where('buku_id', $id)
                ->exists();

            $isReserved = \App\Models\Reservasi::where('user_id', $user->id)
                ->where('buku_id', $id)
                ->where('status', 'menunggu')
                ->exists();
        }

        $activeRequestsCount = \App\Models\Peminjaman::where('buku_id', $id)
            ->where('status', 'menunggu_konfirmasi')
            ->count();

        $dbReviews = \App\Models\Ulasan::where('buku_id', $id)->with('user')->latest()->get();
        $totalReviews = $dbReviews->count();
        if ($totalReviews > 0) {
            $rating = round($dbReviews->avg('rating'), 1);
            $reviews = $dbReviews->map(function($ulasan) {
                return [
                    'nama' => $ulasan->user->name ?? 'Anggota',
                    'tanggal' => $ulasan->created_at->translatedFormat('d F Y'),
                    'rating' => $ulasan->rating,
                    'komentar' => $ulasan->komentar,
                ];
            })->toArray();
        } else {
            $rating = 4.9;
            $totalReviews = 1024;
            $reviews = [
                [
                    'nama' => 'Rizky Pratama',
                    'tanggal' => '12 Mei 2025',
                    'rating' => 5,
                    'komentar' => 'Buku yang sangat mudah dipahami dan aplikatif. Cocok untuk membangun kebiasaan sehari-hari.',
                ],
                [
                    'nama' => 'Ahmad Fauzi',
                    'tanggal' => '10 April 2025',
                    'rating' => 4,
                    'komentar' => 'Buku yang sangat bagus untuk memotivasi diri. Penjelasannya runut dan logis.',
                ]
            ];
        }

        return view('buku.show', compact('buku', 'isBorrowed', 'isFavorited', 'isReserved', 'activeRequestsCount', 'rating', 'totalReviews', 'reviews'));
    }


    // Memproses penyimpanan buku baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'isbn'         => 'required|unique:bukus,isbn',
            'judul'        => 'required|string|max:255',
            'kategori_id'  => 'required|exists:kategoris,id',
            'pengarang'    => 'required|string|max:255',
            'penerbit'     => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1000|max:'.now()->year,
            'stok'         => 'required|integer|min:0',
            'lokasi_rak'   => 'required|string|max:255',
            'status'       => 'required|in:aktif,non aktif,dipinjam',
            'sampul'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi'    => 'nullable|string',
            'sinopsis'     => 'nullable|string',
            'lebar'        => 'nullable|string|max:255',
            'panjang'      => 'nullable|string|max:255',
            'berat'        => 'nullable|string|max:255',
            'bahasa'       => 'nullable|string|max:255',
            'halaman'      => 'nullable|integer|min:0',
            'jenis'        => 'nullable|string|max:255',
            'tanggal_terbit'=> 'nullable|date',
        ]);

        $pathCover = null;
        if ($request->hasFile('sampul')) {
            // File gambar disimpan ke folder 'storage/app/public/cover_buku'
            $pathCover = $request->file('sampul')->store('cover_buku', 'public');
        }

        Buku::create([
            'isbn'         => $request->isbn,
            'judul'        => $request->judul,
            'pengarang'    => $request->pengarang,
            'penerbit'     => $request->penerbit ?? '-',
            'tahun_terbit' => $request->tahun_terbit ?? now()->year,
            'stok'         => $request->stok,
            'lokasi_rak'   => $request->lokasi_rak,
            'kategori_id'  => $request->kategori_id,
            'status'       => $request->status,
            'cover_buku'   => $pathCover,
            'deskripsi'    => $request->deskripsi,
            'sinopsis'     => $request->sinopsis,
            'lebar'        => $request->lebar ?? '15.0 cm',
            'panjang'      => $request->panjang ?? '23.0 cm',
            'berat'        => $request->berat ?? '0.45 kg',
            'bahasa'       => $request->bahasa ?? 'Indonesia',
            'halaman'      => $request->halaman ?? rand(180, 420),
            'jenis'        => $request->jenis ?? 'Buku Fisik',
            'tanggal_terbit'=> $request->tanggal_terbit ?? now()->subYears(rand(2, 8))->format('Y-m-d'),
        ]);

        return redirect()->route(auth()->user()->role . '.buku.index')->with('success', 'Buku baru berhasil ditambahkan ke sistem MacaBae!');
    }

    // Menampilkan form tambah buku
    public function create(Request $request)
    {
        $kategoris = Kategori::all();
        $selectedKategoriId = $request->query('kategori_id');

        return view('pustakawan.buku.create', compact('kategoris', 'selectedKategoriId'));
    }

    // Menampilkan form edit dengan data buku yang sudah ada
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all();
        
        return view('pustakawan.buku.edit', compact('buku', 'kategoris'));
    }

    // Memproses perubahan data buku ke database
    public function update(Request $request, $id)
    {
        $request->validate([
            'isbn'         => 'required|unique:bukus,isbn,'.$id,
            'judul'        => 'required|string|max:255',
            'kategori_id'  => 'required|exists:kategoris,id',
            'pengarang'    => 'required|string|max:255',
            'penerbit'     => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer|min:1000|max:'.now()->year,
            'stok'         => 'required|integer|min:0',
            'lokasi_rak'   => 'required|string|max:255',
            'status'       => 'required|in:aktif,non aktif,dipinjam',
            'sampul'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi'    => 'nullable|string',
            'sinopsis'     => 'nullable|string',
            'lebar'        => 'nullable|string|max:255',
            'panjang'      => 'nullable|string|max:255',
            'berat'        => 'nullable|string|max:255',
            'bahasa'       => 'nullable|string|max:255',
            'halaman'      => 'nullable|integer|min:0',
            'jenis'        => 'nullable|string|max:255',
            'tanggal_terbit'=> 'nullable|date',
        ]);

        $buku = Buku::findOrFail($id);
        
        // Menyusun data yang akan diupdate secara spesifik
        $data = [
            'isbn'         => $request->isbn,
            'judul'        => $request->judul,
            'kategori_id'  => $request->kategori_id,
            'pengarang'    => $request->pengarang,
            'penerbit'     => $request->penerbit ?? '-',
            'tahun_terbit' => $request->tahun_terbit ?? now()->year,
            'stok'         => $request->stok,
            'lokasi_rak'   => $request->lokasi_rak,
            'status'       => $request->status,
            'deskripsi'    => $request->deskripsi,
            'sinopsis'     => $request->sinopsis,
            'lebar'        => $buku->lebar ?? '15.0 cm',
            'panjang'      => $buku->panjang ?? '23.0 cm',
            'berat'        => $buku->berat ?? '0.45 kg',
            'bahasa'       => $buku->bahasa ?? 'Indonesia',
            'halaman'      => $buku->halaman ?? rand(180, 420),
            'jenis'        => $buku->jenis ?? 'Buku Fisik',
            'tanggal_terbit'=> $buku->tanggal_terbit ?? now()->subYears(rand(2, 8))->format('Y-m-d'),
        ];

        // Cek apakah user mengupload file sampul baru
        if ($request->hasFile('sampul')) {
            // Hapus gambar lama dari storage jika ada
            if ($buku->cover_buku && Storage::disk('public')->exists($buku->cover_buku)) {
                Storage::disk('public')->delete($buku->cover_buku);
            }
            // Simpan gambar baru
            $data['cover_buku'] = $request->file('sampul')->store('cover_buku', 'public');
        } else {
            // JIKA TIDAK UPLOAD, pertahankan path cover buku yang lama
            $data['cover_buku'] = $buku->cover_buku;
        }

        $buku->update($data);

        // Proses antrean reservasi jika ada tambahan stok
        \App\Models\Reservasi::checkAndProcessReservations($buku->id);

        return redirect()->route(auth()->user()->role . '.buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    // Menghapus data buku dari sistem
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        
        // Hapus file fisik gambar cover_buku sebelum datanya hilang dari DB
        if ($buku->cover_buku && Storage::disk('public')->exists($buku->cover_buku)) {
            Storage::disk('public')->delete($buku->cover_buku);
        }

        $buku->delete();

        return redirect()->route(auth()->user()->role . '.buku.index')->with('success', 'Buku telah berhasil dihapus dari sistem MacaBae.');
    }
    
    // Menghapus banyak data sekaligus (Bulk Delete)
    public function destroyMultiple(Request $request)
    {
        $ids = $request->input('ids');
        
        if (!$ids || count($ids) === 0) {
            return redirect()->back()->with('error', 'Pilih minimal satu buku untuk dihapus.');
        }

        $bukus = Buku::whereIn('id', $ids)->get();
        foreach ($bukus as $buku) {
            if ($buku->cover_buku && Storage::disk('public')->exists($buku->cover_buku)) {
                Storage::disk('public')->delete($buku->cover_buku);
            }
        }

        Buku::whereIn('id', $ids)->delete();

        return redirect()->route(auth()->user()->role . '.buku.index')->with('success', 'Buku-buku terpilih berhasil dihapus massal!');
    }

    // Aksi Membaca E-Book (Digital Reader)
    public function read($id, Request $request)
    {
        $buku = Buku::with('kategori')->findOrFail($id);
        
        // Cek apakah kategori e-book
        if (!$buku->kategori || strtolower($buku->kategori->nama_kategori) !== 'e-book') {
            return redirect()->route('buku.show', $id)->with('error', 'Buku ini bukan merupakan kategori E-Book.');
        }

        $totalPages = $buku->halaman ?? 216;
        $page = (int) $request->query('page', 1);

        if ($page < 1) { $page = 1; }
        if ($page > $totalPages) { $page = $totalPages; }

        // Load Bookmarks User
        $bookmarks = \App\Models\Bookmark::where('user_id', auth()->id())
            ->where('buku_id', $id)
            ->orderBy('page_number', 'asc')
            ->pluck('page_number')
            ->toArray();

        $isBookmarked = in_array($page, $bookmarks);

        // Generate Daftar Isi (10 Bab) secara proporsional
        $chapters = [];
        if (str_contains(strtolower($buku->judul), 'mie ayam')) {
            $chapterTitles = [
                'Apartemen yang Terlalu Sunyi',
                'Tubuh yang Tak Pernah Diterima',
                'Lingkungan yang Diam',
                'Dua Puluh Empat Jam Terakhir',
                'Hal-Hal yang Tak Pernah Dilakukan',
                'Pesta untuk Diri Sendiri',
                'Selamat Ulang Tahun yang Terakhir',
                'Botol Obat di Tangan Kanan',
                'Anjuran Kecil di Balik Label',
                'Seporsi Mie Ayam Sebelum Mati'
            ];
        } else {
            $chapterTitles = [
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
        }

        $pagesPerChapter = (int) ceil($totalPages / 10);
        for ($i = 0; $i < 10; $i++) {
            $startPage = ($i * $pagesPerChapter) + 1;
            $endPage = ($i + 1) * $pagesPerChapter;
            if ($endPage > $totalPages || $i === 9) {
                $endPage = $totalPages;
            }
            $chapters[] = [
                'index' => $i + 1,
                'title' => ($i + 1) . '. ' . $chapterTitles[$i],
                'start_page' => $startPage,
                'end_page' => $endPage
            ];
        }

        // Tentukan Bab mana yang sedang aktif berdasarkan Halaman saat ini
        $activeChapter = null;
        foreach ($chapters as $ch) {
            if ($page >= $ch['start_page'] && $page <= $ch['end_page']) {
                $activeChapter = $ch;
                break;
            }
        }

        // Generate Konten Halaman Aktif
        $content = $this->generateEbookContent($buku->judul, $page, $activeChapter['title'] ?? 'Bab');

        return view('buku.read', compact(
            'buku', 'page', 'totalPages', 'bookmarks', 'isBookmarked', 'chapters', 'activeChapter', 'content'
        ));
    }

    // Aksi Tandai/Hapus Halaman dari Bookmark
    public function toggleBookmark($id, Request $request)
    {
        $page = (int) $request->input('page_number');
        $userId = auth()->id();

        $bookmark = \App\Models\Bookmark::where('user_id', $userId)
            ->where('buku_id', $id)
            ->where('page_number', $page)
            ->first();

        if ($bookmark) {
            $bookmark->delete();
            $msg = 'Halaman ' . $page . ' berhasil dihapus dari penanda!';
        } else {
            \App\Models\Bookmark::create([
                'user_id' => $userId,
                'buku_id' => $id,
                'page_number' => $page
            ]);
            $msg = 'Halaman ' . $page . ' berhasil ditandai!';
        }

        return back()->with('success', $msg);
    }

    // Helper untuk konten digital dinamis
    private function generateEbookContent($bukuTitle, $page, $chapterTitle)
    {
        if (str_contains(strtolower($bukuTitle), 'mie ayam') && $page === 1) {
            return "Ale, seorang pria berusia tiga puluh tujuh tahun, hidup dengan tubuh yang tak pernah ia sukai dan dunia yang tak pernah benar-benar menerimanya. Sejak kecil, ia tumbuh tanpa dukungan, tanpa teman, dan dengan luka yang terus bertambah seiring waktu.\n\nIa telah mencoba banyak cara untuk berubah. Menjadi lebih baik, lebih layak diterima. Namun semua usaha itu selalu berakhir dengan kegagalan. Bahkan keluarganya sendiri tak pernah benar-benar ada saat ia membutuhkan sandaran.\n\nDua puluh empat jam dari sekarang, Ale berencana mengakhiri hidupnya.\nIa membersihkan apartemennya, makan makanan mahal yang selama ini ia hindari, bernyanyi sepuasnya hingga mabuk. Semua ia lakukan dengan rapi, seolah ingin pergi tanpa meninggalkan kekacauan.\nKetika waktu itu tiba, Ale berdiri dengan pakaian hitam, topi ulang tahun di kepalanya, dan sebuah botol obat di tangannya.\n\n\"Selamat ulang tahun yang terakhir, Ale.\"\nNamun tepat sebelum menelan obat-obatan itu, ia terhenti oleh satu hal sederhana—anjuran di kemasan: dikonsumsi setelah makan. Perutnya berbunyi pelan.\n\nUntuk pertama kalinya dalam hidupnya, Ale memutuskan sesuatu atas kehendak sendiri.\nIa memilih makan seporsi mie ayam sebelum mati.";
        }
        
        $paragraphs = [];
        $paragraphs[] = "Memasuki halaman $page, kisah mengenai perkembangan dalam bab \"" . substr($chapterTitle, 3) . "\" semakin mendalam. Berbagai dinamika yang terjadi memberikan gambaran jelas tentang pergulatan batin tokoh-tokoh utama dan bagaimana tantangan demi tantangan mulai menghadang langkah mereka.";
        $paragraphs[] = "Di tengah situasi yang penuh ketidakpastian ini, keputusan penting harus segera diambil oleh pihak terkait. Setiap detil kejadian di halaman ini menyoroti bagaimana latar belakang masalah di masa lalu ikut mempengaruhi cara pandang dalam menyelesaikan konflik yang kian meruncing dari waktu ke waktu.";
        $paragraphs[] = "Tidak ada jalan mudah untuk melarikan diri dari kenyataan. Dengan langkah perlahan, pencarian jawaban atas pertanyaan-pertanyaan besar di bab ini terus bergulir, menuntun pembaca pada sebuah pemahaman baru tentang arti dari perjuangan dan penerimaan diri.";
        
        return implode("\n\n", $paragraphs);
    }
}