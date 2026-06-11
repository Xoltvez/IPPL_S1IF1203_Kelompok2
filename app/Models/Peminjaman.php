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

    // Cek apakah pengajuan peminjaman sudah kadaluwarsa (lebih dari 6 jam)
    public function isExpired()
    {
        return $this->status === 'menunggu_konfirmasi' && $this->created_at && $this->created_at->addHours(6)->isPast();
    }

    // Mendapatkan batas waktu pengambilan buku (6 jam setelah created_at)
    public function getPickupDeadlineAttribute()
    {
        return $this->created_at ? $this->created_at->addHours(6) : null;
    }
}

