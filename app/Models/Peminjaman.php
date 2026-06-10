<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id', 'buku_id', 'tanggal_pinjam', 'tanggal_kembali', 'status'
    ];

    // Relasi ke User (Siapa yang pinjam)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Buku (Buku apa yang dipinjam)
    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    // Relasi ke Denda
    public function denda()
    {
        return $this->hasOne(Denda::class);
    }
}
