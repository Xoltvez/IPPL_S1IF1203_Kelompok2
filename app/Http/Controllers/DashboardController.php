<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;

        // Logika untuk Admin dan Pustakawan (Manajemen)
        if ($role == 'admin' || $role == 'pustakawan') {
            
            // Statistik Umum
            $totalBuku = Buku::count();
            $totalStok = Buku::sum('stok') ?? 0;
            $totalKategori = Kategori::count();
            $totalMember = User::where('role', 'member')->count();
            
            // Khusus data untuk Admin (Jumlah Pustakawan)
            $totalPustakawan = User::where('role', 'pustakawan')->count();

            // Statistik Sirkulasi
            $butuhVerifikasi = DB::table('peminjamans')->where('status', 'menunggu_konfirmasi')->count();
            $sedangDipinjam = DB::table('peminjamans')->where('status', 'dipinjam')->count();
            $totalTerlambat = 0; // Bisa dikembangkan dengan pengecekan tgl_kembali

            // Log Aktivitas Terbaru
            $aktivitasTerbaru = DB::table('peminjamans')
                ->join('users', 'peminjamans.user_id', '=', 'users.id')
                ->join('bukus', 'peminjamans.buku_id', '=', 'bukus.id')
                ->select('users.name as nama_member', 'bukus.judul as judul_buku', 'peminjamans.status', 'peminjamans.created_at')
                ->orderBy('peminjamans.created_at', 'desc')
                ->limit(5)
                ->get();

            // Tentukan view berdasarkan role (jika ingin tampilan berbeda sedikit)
            // Atau bisa gunakan satu view yang sama namun dengan kondisi @if di dalamnya
            $view = 'pustakawan.dashboard';

            return view($view, [
                'totalBuku' => $totalBuku,
                'totalStok' => $totalStok,
                'totalKategori' => $totalKategori,
                'totalMember' => $totalMember,
                'totalPustakawan' => $totalPustakawan,
                'butuhVerifikasi' => $butuhVerifikasi,
                'sedangDipinjam' => $sedangDipinjam,
                'totalTerlambat' => $totalTerlambat,
                'aktivitasTerbaru' => $aktivitasTerbaru
            ]);

        } else {
            // === LOGIKA DASHBOARD MEMBER ===
            $user_id = $user->id;
            $search = $request->get('search');

            $stats = [
                'total_pinjam' => DB::table('peminjamans')->where('user_id', $user_id)->count(),
                'sedang_dipinjam' => DB::table('peminjamans')->where('user_id', $user_id)->where('status', 'dipinjam')->count(),
                'kategori_tersedia' => Kategori::count(),
            ];

            $kategoriList = Kategori::limit(5)->get();

            $bukuPopuler = Buku::where('status', 'aktif')
                ->when($search, function($query, $search) {
                    return $query->where(function($q) use ($search) {
                        $q->where('judul', 'like', "%{$search}%")
                          ->orWhere('isbn', 'like', "%{$search}%")
                          ->orWhere('pengarang', 'like', "%{$search}%")
                          ->orWhereHas('kategori', function($k) use ($search) {
                              $k->where('nama_kategori', 'like', "%{$search}%");
                          });
                    });
                })
                ->latest()->limit(6)->get();

            $bukuDipinjam = collect(); // Logic peminjaman aktif bisa ditaruh di sini

            return view('member.dashboard', compact('bukuPopuler', 'bukuDipinjam', 'stats', 'kategoriList'));
        }
    }
}