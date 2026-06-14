<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Denda;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DendaController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'belum_lunas');
        $search = $request->input('search');

        // Total counts for badge display
        $countUnpaid = Denda::where('status_pembayaran', 'belum_lunas')->count();
        $countOverdue = Peminjaman::where('status', 'dipinjam')
            ->where('tanggal_kembali', '<', Carbon::today())
            ->count();
        $countPaid = Denda::where('status_pembayaran', 'lunas')->count();

        $unpaidFines = null;
        $overdueLoans = null;
        $paidFines = null;

        if ($tab === 'belum_lunas') {
            $query = Denda::where('status_pembayaran', 'belum_lunas')
                ->with(['peminjaman.user', 'peminjaman.buku']);

            if ($search) {
                $query->whereHas('peminjaman', function ($q) use ($search) {
                    $q->whereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                    })->orWhereHas('buku', function ($qb) use ($search) {
                        $qb->where('judul', 'like', "%{$search}%");
                    });
                });
            }

            $unpaidFines = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        } elseif ($tab === 'terlambat') {
            $query = Peminjaman::where('status', 'dipinjam')
                ->where('tanggal_kembali', '<', Carbon::today())
                ->with(['user', 'buku']);

            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                    })->orWhereHas('buku', function ($qb) use ($search) {
                        $qb->where('judul', 'like', "%{$search}%");
                    });
                });
            }

            $overdueLoans = $query->orderBy('tanggal_kembali', 'asc')->paginate(10)->withQueryString();
        } elseif ($tab === 'riwayat') {
            $query = Denda::where('status_pembayaran', 'lunas')
                ->with(['peminjaman.user', 'peminjaman.buku']);

            if ($search) {
                $query->whereHas('peminjaman', function ($q) use ($search) {
                    $q->whereHas('user', function ($qu) use ($search) {
                        $qu->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
                    })->orWhereHas('buku', function ($qb) use ($search) {
                        $qb->where('judul', 'like', "%{$search}%");
                    });
                });
            }

            $paidFines = $query->orderBy('updated_at', 'desc')->paginate(10)->withQueryString();
        }

        return view('pustakawan.denda.index', compact(
            'tab', 'search', 'countUnpaid', 'countOverdue', 'countPaid',
            'unpaidFines', 'overdueLoans', 'paidFines'
        ));
    }

    public function bayar(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:tunai,qris,transfer_bank',
        ]);

        $denda = Denda::findOrFail($id);
        $denda->update([
            'status_pembayaran' => 'lunas',
            'metode_pembayaran' => $request->metode_pembayaran
        ]);

        return redirect()->route(auth()->user()->role . '.denda.index', ['tab' => 'riwayat'])
            ->with('success', 'Denda berhasil dibayar menggunakan metode: ' . strtoupper(str_replace('_', ' ', $request->metode_pembayaran)));
    }
}
