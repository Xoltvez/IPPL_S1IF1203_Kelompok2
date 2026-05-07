<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $role = Auth::user()->role;

        if ($role == 'pustakawan') {
            $totalBuku = \App\Models\Buku::count();
            $totalStok = \App\Models\Buku::sum('stok');
            $totalKategori = \App\Models\Buku::distinct('kategori_id')->count('kategori_id');

            $totalMember = \App\Models\User::where('role', 'member')->count();

            return view('pustakawan.dashboard', [
                'totalBuku' => $totalBuku,
                'totalStok' => $totalStok,
                'totalKategori' => $totalKategori,
                'totalMember' => $totalMember 
            ]);
        } else {
            return view('member.dashboard');
        }
    }
}
