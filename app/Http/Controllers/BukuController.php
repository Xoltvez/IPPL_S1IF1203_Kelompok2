<?php

namespace App\Http\Controllers;

use App\Models\Buku; 
use App\Models\Kategori; 
use Illuminate\Http\Request;

class BukuController extends Controller
{

    public function index()
    {
        $datas = Buku::with('kategori')->get();
        
        return view('pustakawan.buku.index', compact('datas'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input (Keamanan Data)
        $request->validate([
            'isbn'         => 'required|unique:bukus,isbn',
            'judul'        => 'required|string|max:255',
            'kategori_id'  => 'required|exists:kategoris,id',
            'pengarang'    => 'required',
            'stok'         => 'required|integer|min:0',
            'lokasi_rak'   => 'required',
        ]);

        // 2. Simpan ke Database
        \App\Models\Buku::create([
            'isbn'         => $request->isbn,
            'judul'        => $request->judul,
            'pengarang'    => $request->pengarang,
            'penerbit'     => $request->penerbit ?? '-',
            'tahun_terbit' => $request->tahun_terbit ?? 2026,
            'stok'         => $request->stok,
            'lokasi_rak'   => $request->lokasi_rak,
            'kategori_id'  => $request->kategori_id,
        ]);

        // 3. Redirect kembali dengan pesan sukses
        return redirect()->route('pustakawan.buku.index')->with('success', 'Buku baru berhasil ditambahkan ke sistem MacaBae!');
    }

    public function create()
    {
        $kategoris = Kategori::all(); 
        
        return view('pustakawan.buku.create', compact('kategoris'));
    }

    // Menampilkan form edit dengan data buku yang sudah ada
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategoris = Kategori::all();
        return view('pustakawan.buku.edit', compact('buku', 'kategoris'));
    }

    // Memproses perubahan data ke database
    public function update(Request $request, $id)
    {
        $request->validate([
            'isbn' => 'required|unique:bukus,isbn,'.$id, // Unik kecuali untuk ID ini sendiri
            'judul' => 'required',
            'stok' => 'required|numeric',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($request->all());

        return redirect()->route('pustakawan.buku.index')->with('success', 'Data buku berhasil diperbarui!');
    }

    // Menghapus data buku
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('pustakawan.buku.index')->with('success', 'Buku telah dihapus dari sistem.');
    }
}