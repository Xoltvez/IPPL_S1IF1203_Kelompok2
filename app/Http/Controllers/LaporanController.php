<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Denda;
use App\Models\Buku;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    // Halaman Ringkasan Laporan dan Filter
    public function index(Request $request)
    {
        // Rentang tanggal default: awal bulan ini s.d hari ini
        $start = $request->input('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'));
        $end = $request->input('tanggal_selesai', now()->format('Y-m-d'));
        $status = $request->input('status', 'semua');

        // Query transaksi dasar
        $query = Peminjaman::with(['user', 'buku', 'denda'])
            ->whereBetween('tanggal_pinjam', [$start, $end]);

        if ($status !== 'semua') {
            if ($status === 'terlambat') {
                $query->where('status', 'dipinjam')
                    ->where('tanggal_kembali', '<', now()->format('Y-m-d'));
            } else {
                $query->where('status', $status);
            }
        }

        // Kalkulasi Ringkasan Data (Metrics)
        $totalPeminjaman = Peminjaman::whereBetween('tanggal_pinjam', [$start, $end])->count();
        
        $totalPengembalian = Peminjaman::whereBetween('tanggal_pinjam', [$start, $end])
            ->where('status', 'dikembalikan')->count();

        // Mengambil denda di rentang waktu peminjaman tersebut
        $dendaQuery = Denda::whereHas('peminjaman', function($q) use ($start, $end) {
            $q->whereBetween('tanggal_pinjam', [$start, $end]);
        });
        
        $totalDendaLunas = (float) (clone $dendaQuery)->where('status_pembayaran', 'lunas')->sum('jumlah_denda');
        $totalDendaBelumLunas = (float) (clone $dendaQuery)->where('status_pembayaran', 'belum_lunas')->sum('jumlah_denda');

        // Top 5 Buku Terpopuler
        $topBuku = Peminjaman::select('buku_id', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pinjam', [$start, $end])
            ->groupBy('buku_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->with('buku')
            ->get();

        // Top 5 Member Teraktif
        $topMembers = Peminjaman::select('user_id', DB::raw('count(*) as total'))
            ->whereBetween('tanggal_pinjam', [$start, $end])
            ->groupBy('user_id')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->with('user')
            ->get();

        // List transaksi untuk tabel laporan (dengan pagination)
        $transactions = $query->orderBy('tanggal_pinjam', 'desc')->paginate(15)->withQueryString();

        return view('admin.laporan.index', compact(
            'transactions', 'totalPeminjaman', 'totalPengembalian',
            'totalDendaLunas', 'totalDendaBelumLunas', 'topBuku', 'topMembers',
            'start', 'end', 'status'
        ));
    }

    // Tampilan Khusus Print
    public function print(Request $request)
    {
        $start = $request->input('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'));
        $end = $request->input('tanggal_selesai', now()->format('Y-m-d'));
        $status = $request->input('status', 'semua');

        $query = Peminjaman::with(['user', 'buku', 'denda'])
            ->whereBetween('tanggal_pinjam', [$start, $end]);

        if ($status !== 'semua') {
            if ($status === 'terlambat') {
                $query->where('status', 'dipinjam')
                    ->where('tanggal_kembali', '<', now()->format('Y-m-d'));
            } else {
                $query->where('status', $status);
            }
        }

        $transactions = $query->orderBy('tanggal_pinjam', 'desc')->get();

        // Re-calculate stats
        $totalPeminjaman = Peminjaman::whereBetween('tanggal_pinjam', [$start, $end])->count();
        $totalPengembalian = Peminjaman::whereBetween('tanggal_pinjam', [$start, $end])
            ->where('status', 'dikembalikan')->count();

        $dendaQuery = Denda::whereHas('peminjaman', function($q) use ($start, $end) {
            $q->whereBetween('tanggal_pinjam', [$start, $end]);
        });
        
        $totalDendaLunas = (float) (clone $dendaQuery)->where('status_pembayaran', 'lunas')->sum('jumlah_denda');
        $totalDendaBelumLunas = (float) (clone $dendaQuery)->where('status_pembayaran', 'belum_lunas')->sum('jumlah_denda');

        return view('admin.laporan.print', compact(
            'transactions', 'totalPeminjaman', 'totalPengembalian',
            'totalDendaLunas', 'totalDendaBelumLunas',
            'start', 'end', 'status'
        ));
    }

    // Ekspor Excel/CSV Stream
    public function export(Request $request)
    {
        $start = $request->input('tanggal_mulai', now()->startOfMonth()->format('Y-m-d'));
        $end = $request->input('tanggal_selesai', now()->format('Y-m-d'));
        $status = $request->input('status', 'semua');

        $query = Peminjaman::with(['user', 'buku', 'denda'])
            ->whereBetween('tanggal_pinjam', [$start, $end]);

        if ($status !== 'semua') {
            if ($status === 'terlambat') {
                $query->where('status', 'dipinjam')
                    ->where('tanggal_kembali', '<', now()->format('Y-m-d'));
            } else {
                $query->where('status', $status);
            }
        }

        $transactions = $query->orderBy('tanggal_pinjam', 'desc')->get();

        $filename = "laporan-sirkulasi-" . $start . "-ke-" . $end . ".csv";

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel alignment
            fputs($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Headers
            fputcsv($file, [
                'No', 'ID Transaksi', 'Nama Member', 'Email Member', 'Judul Buku', 'ISBN', 'Tanggal Pinjam', 'Jatuh Tempo', 'Status', 'Denda (Rp)', 'Status Pembayaran Denda'
            ]);

            foreach ($transactions as $index => $t) {
                $dendaAmount = $t->denda ? $t->denda->jumlah_denda : 0;
                $statusDenda = $t->denda ? ($t->denda->status_pembayaran === 'lunas' ? 'Lunas' : 'Belum Lunas') : '-';
                
                fputcsv($file, [
                    $index + 1,
                    'TRX-' . str_pad($t->id, 6, '0', STR_PAD_LEFT),
                    $t->user->name ?? '-',
                    $t->user->email ?? '-',
                    $t->buku->judul ?? '-',
                    $t->buku->isbn ?? '-',
                    $t->tanggal_pinjam,
                    $t->tanggal_kembali,
                    ucfirst($t->status),
                    $dendaAmount,
                    $statusDenda
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
