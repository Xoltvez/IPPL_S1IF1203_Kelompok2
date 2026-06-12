<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Denda;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // Memproses peminjaman buku baru oleh member
    public function store(Request $request, $bukuId)
    {
        $buku = Buku::findOrFail($bukuId);

        // 1. Validasi Stok
        if ($buku->stok <= 0) {
            return back()->with('error', 'Maaf, stok buku ini sedang habis.');
        }

        // 2. Validasi apakah sedang meminjam buku yang sama
        $alreadyBorrowed = Peminjaman::where('user_id', Auth::id())
            ->where('buku_id', $bukuId)
            ->where('status', 'dipinjam')
            ->exists();

        if ($alreadyBorrowed) {
            return back()->with('error', 'Anda sedang meminjam buku ini. Silakan kembalikan terlebih dahulu sebelum meminjam kembali.');
        }

        // 3. Validasi Durasi (1 sampai 7 hari)
        $duration = (int) $request->input('durasi', 7);
        if ($duration < 1 || $duration > 7) {
            $duration = 7;
        }

        // 4. Gunakan DB Transaction
        $peminjaman = DB::transaction(function () use ($buku, $duration) {
            // Catat Peminjaman dengan status menunggu_konfirmasi (stok tidak dikurangi sekarang)
            return Peminjaman::create([
                'user_id' => Auth::id(),
                'buku_id' => $buku->id,
                'tanggal_pinjam' => Carbon::now()->format('Y-m-d'),
                // Batas waktu kembali sesuai durasi yang dipilih
                'tanggal_kembali' => Carbon::now()->addDays($duration)->format('Y-m-d'), 
                'status' => 'menunggu_konfirmasi',
            ]);
        });

        // Kirim notifikasi ke Pustakawan & Admin
        $user = Auth::user();
        $adminsAndLibrarians = \App\Models\User::whereIn('role', ['admin', 'pustakawan'])->get();
        foreach ($adminsAndLibrarians as $staff) {
            try {
                $staff->notify(new \App\Notifications\SirkulasiNotification(
                    'Pengajuan Peminjaman Baru',
                    "Member {$user->name} mengajukan peminjaman buku '{$buku->judul}'.",
                    'info'
                ));
            } catch (\Exception $e) {
                // Biarkan flow utama berjalan jika pengiriman notifikasi bermasalah
            }
        }

        return redirect()->route('member.peminjaman.index')->with('success', 'Pengajuan peminjaman berhasil dibuat! Silakan tunggu konfirmasi/persetujuan dari pustakawan.');
    }

    // Menampilkan daftar peminjaman aktif milik member yang login
    public function memberPeminjaman()
    {
        $user = Auth::user();
        
        $peminjamans = Peminjaman::with('buku')
            ->where('user_id', $user->id)
            ->whereIn('status', ['dipinjam', 'menunggu_konfirmasi'])
            ->orderBy('tanggal_pinjam', 'desc')
            ->paginate(10);

        return view('member.peminjaman.index', compact('peminjamans'));
    }

    // Menampilkan seluruh riwayat transaksi peminjaman milik member yang login
    public function memberRiwayat(Request $request)
    {
        $user = Auth::user();
        $status = $request->get('status');
        
        $riwayats = Peminjaman::with(['buku', 'denda'])
            ->where('user_id', $user->id)
            ->when($status, function($query, $status) {
                if ($status === 'dipinjam') {
                    return $query->where('status', 'dipinjam');
                } elseif ($status === 'dikembalikan') {
                    return $query->where('status', 'dikembalikan');
                } elseif ($status === 'ditolak') {
                    return $query->where('status', 'ditolak');
                }
                return $query;
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('member.riwayat', compact('riwayats', 'status'));
    }

    // Memproses pengembalian buku oleh member (mandiri)
    public function kembali(Request $request, $id)
    {
        $peminjaman = Peminjaman::with('buku')->findOrFail($id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku ini sudah dikembalikan.');
        }

        $dendaAmount = 0;

        DB::transaction(function () use ($peminjaman, &$dendaAmount) {
            $today = Carbon::today();
            $dueDate = Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();
            
            // Hitung selisih hari antara tanggal kembali (hari ini) dan jatuh tempo
            if ($today->gt($dueDate)) {
                $daysLate = $today->diffInDays($dueDate, true);
                // Denda Rp 1.000 per hari keterlambatan
                $dendaAmount = $daysLate * 1000;
            }

            // Update status peminjaman
            $peminjaman->update([
                'status' => 'dikembalikan'
            ]);

            // Tambahkan denda jika ada keterlambatan
            if ($dendaAmount > 0) {
                Denda::create([
                    'peminjaman_id' => $peminjaman->id,
                    'jumlah_denda' => $dendaAmount,
                    'status_pembayaran' => 'belum_lunas'
                ]);
            }

            // Kembalikan stok buku
            $peminjaman->buku->increment('stok');

            // Proses antrean reservasi jika ada
            \App\Models\Reservasi::checkAndProcessReservations($peminjaman->buku_id);
        });

        // Kirim email konfirmasi jika preferensi notifikasi diaktifkan
        $peminjaman->load('user');
        if ($peminjaman->user && $peminjaman->user->notif_pengembalian) {
            try {
                Mail::to($peminjaman->user->email)->send(new \App\Mail\PengembalianMail($peminjaman, $dendaAmount));
            } catch (\Exception $e) {
                // Biarkan flow utama berjalan jika pengiriman email bermasalah
            }
        }

        // Kirim notifikasi database in-app
        if ($peminjaman->user) {
            try {
                $peminjaman->user->notify(new \App\Notifications\SirkulasiNotification(
                    'Pengembalian Buku Sukses',
                    "Buku '{$peminjaman->buku->judul}' berhasil dikembalikan. Detail pengembalian sudah terkirim melalui Gmail.",
                    'success'
                ));
            } catch (\Exception $e) {
                // Biarkan flow utama berjalan
            }
        }

        if (Auth::user()->role === 'member') {
            return redirect()->route('member.riwayat.index')->with('success', 'Buku berhasil dikembalikan! Terima kasih telah mengembalikan tepat waktu.');
        }
        return redirect()->route(auth()->user()->role . '.pengembalian.index')->with('success', 'Buku berhasil dikembalikan oleh Pustakawan.');
    }

    // Menampilkan seluruh peminjaman aktif untuk pustakawan/admin
    public function pustakawanPeminjaman(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $peminjamans = Peminjaman::with(['user', 'buku'])
            ->when($status, function($query, $status) {
                return $query->where('status', $status);
            }, function($query) {
                return $query->whereIn('status', ['dipinjam', 'menunggu_konfirmasi']);
            })
            ->when($search, function($query, $search) {
                $cleanSearch = $search;
                if (preg_match('/^(?:#)?MBR-(\d+)$/i', $search, $matches)) {
                    $cleanSearch = (int)$matches[1];
                }

                return $query->where(function($q) use ($search, $cleanSearch) {
                    $q->whereHas('user', function($qu) use ($search, $cleanSearch) {
                        $qu->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%")
                           ->orWhere('id', $cleanSearch);
                    })->orWhereHas('buku', function($qb) use ($search) {
                        $qb->where('judul', 'like', "%{$search}%");
                    });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('pustakawan.peminjaman.index', compact('peminjamans', 'search', 'status'));
    }

    // Menampilkan riwayat pengembalian buku untuk pustakawan/admin
    public function pustakawanPengembalian(Request $request)
    {
        $search = $request->get('search');

        $pengembalians = Peminjaman::with(['user', 'buku', 'denda'])
            ->where('status', 'dikembalikan')
            ->when($search, function($query, $search) {
                $cleanSearch = $search;
                if (preg_match('/^(?:#)?MBR-(\d+)$/i', $search, $matches)) {
                    $cleanSearch = (int)$matches[1];
                }

                return $query->where(function($q) use ($search, $cleanSearch) {
                    $q->whereHas('user', function($qu) use ($search, $cleanSearch) {
                        $qu->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%")
                           ->orWhere('id', $cleanSearch);
                    })->orWhereHas('buku', function($qb) use ($search) {
                        $qb->where('judul', 'like', "%{$search}%");
                    });
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('pustakawan.pengembalian.index', compact('pengembalians', 'search'));
    }

    // Memproses pembayaran denda oleh pustakawan/admin
    public function bayarDenda($id)
    {
        $denda = Denda::findOrFail($id);
        $denda->update([
            'status_pembayaran' => 'lunas'
        ]);

        return redirect()->route(auth()->user()->role . '.pengembalian.index')->with('success', 'Denda berhasil dibayar/dilunasi.');
    }

    // Menyetujui pengajuan peminjaman oleh pustakawan/admin
    public function setujui($id)
    {
        $peminjaman = Peminjaman::with('buku')->findOrFail($id);

        if ($peminjaman->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Peminjaman ini sudah diproses.');
        }

        if ($peminjaman->isExpired()) {
            return back()->with('error', 'Persetujuan gagal. Batas waktu pengambilan 6 jam sudah berakhir.');
        }

        if ($peminjaman->buku->stok <= 0) {
            return back()->with('error', 'Stok buku ini sedang kosong. Tidak dapat menyetujui peminjaman.');
        }

        DB::transaction(function () use ($peminjaman) {
            // Hitung durasi awal yang diajukan member
            $startDate = Carbon::parse($peminjaman->tanggal_pinjam);
            $endDate = Carbon::parse($peminjaman->tanggal_kembali);
            $duration = $endDate->diffInDays($startDate, true);
            if ($duration <= 0) {
                $duration = 7; // fallback
            }

            // Update status ke dipinjam dan atur tanggal mulai dari hari persetujuan
            $peminjaman->update([
                'status' => 'dipinjam',
                'tanggal_pinjam' => Carbon::now()->format('Y-m-d'),
                'tanggal_kembali' => Carbon::now()->addDays($duration)->format('Y-m-d'),
            ]);

            // Kurangi stok buku
            $peminjaman->buku->decrement('stok');
        });

        // Kirim email persetujuan jika preferensi notifikasi diaktifkan
        $peminjaman->load('user');
        if ($peminjaman->user && $peminjaman->user->notif_persetujuan) {
            try {
                Mail::to($peminjaman->user->email)->send(new \App\Mail\PersetujuanMail($peminjaman, 'disetujui'));
            } catch (\Exception $e) {
                // Biarkan flow utama berjalan jika pengiriman email bermasalah
            }
        }

        // Kirim notifikasi database in-app
        if ($peminjaman->user) {
            try {
                $peminjaman->user->notify(new \App\Notifications\SirkulasiNotification(
                    'Peminjaman Buku Disetujui',
                    "Pengajuan peminjaman buku '{$peminjaman->buku->judul}' disetujui. Detail peminjaman sudah terkirim melalui Gmail.",
                    'success'
                ));
            } catch (\Exception $e) {
                // Biarkan flow utama berjalan
            }
        }

        return back()->with('success', 'Peminjaman berhasil disetujui! Buku resmi dipinjam.');
    }

    // Menolak pengajuan peminjaman oleh pustakawan/admin
    public function tolak($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Peminjaman ini sudah diproses.');
        }

        $peminjaman->update([
            'status' => 'ditolak'
        ]);

        // Proses antrean reservasi jika ada
        \App\Models\Reservasi::checkAndProcessReservations($peminjaman->buku_id);

        // Kirim email penolakan jika preferensi notifikasi diaktifkan
        $peminjaman->load('user');
        if ($peminjaman->user && $peminjaman->user->notif_persetujuan) {
            try {
                Mail::to($peminjaman->user->email)->send(new \App\Mail\PersetujuanMail($peminjaman, 'ditolak'));
            } catch (\Exception $e) {
                // Biarkan flow utama berjalan jika pengiriman email bermasalah
            }
        }

        // Kirim notifikasi database in-app
        if ($peminjaman->user) {
            try {
                $peminjaman->user->notify(new \App\Notifications\SirkulasiNotification(
                    'Peminjaman Buku Ditolak',
                    "Pengajuan peminjaman buku '{$peminjaman->buku->judul}' ditolak.",
                    'danger'
                ));
            } catch (\Exception $e) {
                // Biarkan flow utama berjalan
            }
        }

        return back()->with('success', 'Pengajuan peminjaman berhasil ditolak.');
    }

    // Membatalkan pengajuan peminjaman oleh member (sebelum disetujui)
    public function cancelRequest($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        // Pastikan hanya pemilik pengajuan yang bisa membatalkan dan status masih pending
        if ($peminjaman->user_id !== Auth::id()) {
            abort(403);
        }

        if ($peminjaman->status !== 'menunggu_konfirmasi') {
            return back()->with('error', 'Pengajuan ini sudah diproses dan tidak bisa dibatalkan.');
        }

        $bukuId = $peminjaman->buku_id;
        $peminjaman->delete();

        // Proses antrean reservasi jika ada
        \App\Models\Reservasi::checkAndProcessReservations($bukuId);

        return back()->with('success', 'Pengajuan peminjaman berhasil dibatalkan.');
    }
}