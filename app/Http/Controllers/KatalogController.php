<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;

class KatalogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $kategoriId = $request->query('kategori');
        
        $jenis = $request->query('jenis');
        $noKatalog = $request->query('no_katalog');
        $judulDetail = $request->query('judul');
        $pengarangDetail = $request->query('pengarang');
        $penerbitDetail = $request->query('penerbit');

        // Query buku-buku yang berstatus aktif
        $query = Buku::with('kategori')
            ->where('status', 'aktif');

        // Filter pencarian
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('pengarang', 'like', '%' . $search . '%')
                  ->orWhere('isbn', 'like', '%' . $search . '%');
            });
        }

        // Filter kategori
        if ($kategoriId) {
            $query->where('kategori_id', $kategoriId);
        }

        // Filter detail katalog
        if ($jenis) {
            $query->where('jenis', $jenis);
        }
        if ($noKatalog) {
            $query->where(function($q) use ($noKatalog) {
                $q->where('isbn', 'like', '%' . $noKatalog . '%')
                  ->orWhere('id', $noKatalog);
            });
        }
        if ($judulDetail) {
            $query->where('judul', 'like', '%' . $judulDetail . '%');
        }
        if ($pengarangDetail) {
            $query->where('pengarang', 'like', '%' . $pengarangDetail . '%');
        }
        if ($penerbitDetail) {
            $query->where('penerbit', 'like', '%' . $penerbitDetail . '%');
        }

        $bukus = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Ambil semua kategori untuk filter pills
        $categories = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('katalog.index', compact('bukus', 'categories', 'search', 'kategoriId'));
    }
}
