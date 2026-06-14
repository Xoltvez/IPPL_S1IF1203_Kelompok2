<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Reservasi;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservasiController extends Controller
{
    // Menampilkan daftar reservasi member yang sedang login
    public function index()
    {
        $user = Auth::user();
        
        $reservasis = Reservasi::with('buku')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('member.reservasi.index', compact('reservasis'));
    }

    // Memproses pembuatan reservasi buku
    public function store(Request $request, $bukuId)
    {
        $buku = Buku::findOrFail($bukuId);

        // 1. Validasi: Reservasi hanya untuk buku yang stoknya habis atau sedang dipinjam
        // Hitung stok riil tersedia
        $activeRequestsCount = Peminjaman::where('buku_id', $bukuId)
            ->where('status', 'menunggu_konfirmasi')
            ->count();
        $availableStock = $buku->stok - $activeRequestsCount;

        if ($availableStock > 0 && $buku->status == 'aktif') {
            return back()->with('error', 'Buku ini masih memiliki stok yang tersedia untuk dipinjam langsung.');
        }

        // 2. Validasi: Apakah user sedang meminjam buku ini secara aktif
        $isBorrowed = Peminjaman::where('user_id', Auth::id())
            ->where('buku_id', $bukuId)
            ->where('status', 'dipinjam')
            ->exists();

        if ($isBorrowed) {
            return back()->with('error', 'Anda sedang meminjam buku ini secara aktif. Tidak dapat melakukan reservasi.');
        }

        // 3. Validasi: Apakah user sudah memiliki pengajuan peminjaman untuk buku ini yang sedang menunggu konfirmasi
        $hasPendingRequest = Peminjaman::where('user_id', Auth::id())
            ->where('buku_id', $bukuId)
            ->where('status', 'menunggu_konfirmasi')
            ->exists();

        if ($hasPendingRequest) {
            return back()->with('error', 'Anda sudah memiliki pengajuan peminjaman aktif untuk buku ini yang sedang menunggu persetujuan.');
        }

        // 4. Validasi: Apakah user sudah mereservasi buku ini dan statusnya masih menunggu
        $alreadyReserved = Reservasi::where('user_id', Auth::id())
            ->where('buku_id', $bukuId)
            ->where('status', 'menunggu')
            ->exists();

        if ($alreadyReserved) {
            return back()->with('error', 'Anda sudah terdaftar dalam antrean reservasi untuk buku ini.');
        }

        $request->validate([
            'durasi' => 'required|integer|min:1|max:7',
        ]);

        // 5. Buat reservasi baru
        Reservasi::create([
            'user_id' => Auth::id(),
            'buku_id' => $bukuId,
            'durasi' => $request->durasi,
            'status' => 'menunggu',
        ]);

        return redirect()->route('member.reservasi.index')->with('success', 'Buku berhasil direservasi! Anda telah masuk dalam daftar antrean.');
    }

    // Memproses pembatalan reservasi oleh member
    public function cancel($id)
    {
        $reservasi = Reservasi::findOrFail($id);

        // Pastikan kepemilikan reservasi
        if ($reservasi->user_id !== Auth::id()) {
            abort(403);
        }

        // Hanya bisa dibatalkan jika masih menunggu
        if ($reservasi->status !== 'menunggu') {
            return back()->with('error', 'Reservasi ini tidak dapat dibatalkan karena statusnya sudah diproses.');
        }

        $reservasi->update([
            'status' => 'dibatalkan',
        ]);

        return back()->with('success', 'Reservasi buku berhasil dibatalkan.');
    }
}
