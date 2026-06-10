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

        $bukus = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Ambil semua kategori untuk filter pills
        $categories = Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('katalog.index', compact('bukus', 'categories', 'search', 'kategoriId'));
    }
}
