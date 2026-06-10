<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KunjunganController extends Controller
{
    /**
     * Display a listing of offline library visits for today.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $kunjungans = Kunjungan::with('user')
            ->whereDate('created_at', Carbon::today())
            ->when($search, function($query, $search) {
                $cleanSearch = $search;
                if (preg_match('/^(?:#)?MBR-(\d+)$/i', $search, $matches)) {
                    $cleanSearch = (int)$matches[1];
                }
                
                return $query->whereHas('user', function($q) use ($search, $cleanSearch) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('id', $cleanSearch);
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('pustakawan.kunjungan.index', compact('kunjungans', 'search'));
    }

    /**
     * Store a newly created offline visit in storage (presensi check-in).
     */
    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|string',
        ]);

        $search = $request->input('member_id');
        
        // Parse ID member format
        $cleanSearch = $search;
        if (preg_match('/^(?:#)?MBR-(\d+)$/i', $search, $matches)) {
            $cleanSearch = (int)$matches[1];
        }

        // Cari user yang rolenya 'member'
        $user = User::where('role', 'member')
            ->where(function($q) use ($search, $cleanSearch) {
                $q->where('id', $cleanSearch)
                  ->orWhere('email', $search);
            })
            ->first();

        if (!$user) {
            return back()->with('error', 'Presensi gagal. Anggota tidak ditemukan. Pastikan scan QR Code / input ID valid.');
        }

        // Catat kunjungan
        Kunjungan::create([
            'user_id' => $user->id
        ]);

        return back()->with('success', "Presensi berhasil! Selamat datang, {$user->name}.");
    }
}
