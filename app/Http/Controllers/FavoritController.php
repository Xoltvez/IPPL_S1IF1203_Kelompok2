<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Favorit;
use Illuminate\Support\Facades\Auth;

class FavoritController extends Controller
{
    // Menampilkan daftar buku yang difavoritkan/disimpan oleh user dengan pencarian & filter kategori
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->query('search');
        $kategoriId = $request->query('kategori');

        // Query buku favorit user
        $query = $user->favoritBukus()->with('kategori');

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

        $bukuFavorit = $query->orderBy('favorits.created_at', 'desc')->paginate(15)->withQueryString();

        // Ambil semua kategori untuk filter pills
        $categories = \App\Models\Kategori::orderBy('nama_kategori', 'asc')->get();

        return view('favorit.index', compact('bukuFavorit', 'categories', 'search', 'kategoriId'));
    }

    // Toggle simpan/hapus buku favorit
    public function toggle(Request $request, $id)
    {
        $user = Auth::user();
        $buku = Buku::findOrFail($id);

        // Cari apakah relasi favorit sudah ada
        $favorit = Favorit::where('user_id', $user->id)
            ->where('buku_id', $buku->id)
            ->first();

        if ($favorit) {
            // Jika ada, hapus dari favorit
            $favorit->delete();
            $message = 'Buku berhasil dihapus dari daftar tersimpan!';
        } else {
            // Jika belum ada, tambahkan ke favorit
            Favorit::create([
                'user_id' => $user->id,
                'buku_id' => $buku->id,
            ]);
            $message = 'Buku berhasil disimpan ke daftar favorit Anda!';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_favorited' => !$favorit
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    // Menghapus semua buku favorit user
    public function clear()
    {
        $user = Auth::user();
        $user->favoritBukus()->detach(); // Menghapus semua hubungan favorit

        return redirect()->back()->with('success', 'Semua buku tersimpan berhasil dihapus dari daftar favorit Anda!');
    }
}
