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
            'sampul'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // name input dari blade tetap 'sampul'
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
            'cover_buku'   => $pathCover, // SINKRONISASI: Masuk ke kolom database 'cover_buku'
        ]);

        return redirect()->route('pustakawan.buku.index')->with('success', 'Buku baru berhasil ditambahkan ke sistem MacaBae!');
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

        return redirect()->route('pustakawan.buku.index')->with('success', 'Data buku berhasil diperbarui!');
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

        return redirect()->route('pustakawan.buku.index')->with('success', 'Buku telah berhasil dihapus dari sistem MacaBae.');
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

        return redirect()->route('pustakawan.buku.index')->with('success', 'Buku-buku terpilih berhasil dihapus massal!');
    }
}