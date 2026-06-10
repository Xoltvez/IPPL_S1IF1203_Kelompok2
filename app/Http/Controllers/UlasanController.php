<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    /**
     * Store or update a book review.
     */
    public function store(Request $request, $bukuId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:1000',
        ]);

        $buku = Buku::findOrFail($bukuId);

        Ulasan::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'buku_id' => $buku->id,
            ],
            [
                'rating' => $request->rating,
                'komentar' => $request->komentar,
            ]
        );

        return redirect()->back()->with('success', 'Ulasan Anda berhasil dikirim!');
    }
}
