<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function store(Request $request, $bukuId)
    {
        $buku = Buku::findOrFail($bukuId);

        // 1. Validasi Stok (Sesuai Batasan di SKPL)
        if ($buku->stok <= 0) {
            return back()->with('error', 'Maaf, stok buku ini sedang habis.');
        }

        // 2. Gunakan DB Transaction agar aman
        DB::transaction(function () use ($buku) {
            // Catat Peminjaman
            Peminjaman::create([
                'user_id' => Auth::id(),
                'buku_id' => $buku->id,
                'tanggal_pinjam' => Carbon::now(),
                // Jatuh tempo 7 hari (sesuai aturan umum perpustakaan)
                'tanggal_kembali' => Carbon::now()->addDays(7), 
                'status' => 'dipinjam',
            ]);

            // Kurangi stok buku secara otomatis
            $buku->decrement('stok');
        });

        return redirect()->route('dashboard')->with('success', 'Berhasil meminjam buku! Silakan ambil di meja pustakawan.');
    }
}