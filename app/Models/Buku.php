<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Buku extends Model
{
    protected $table = 'bukus'; 

    protected $fillable = [
        'isbn', 
        'judul', 
        'pengarang', 
        'penerbit', 
        'tahun_terbit', 
        'stok', 
        'lokasi_rak',
        'status', 
        'kategori_id',
        'cover_buku',
        'deskripsi',
        'sinopsis',
        'lebar',
        'panjang',
        'berat',
        'bahasa',
        'halaman',
        'jenis',
        'tanggal_terbit'
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function ulasans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ulasan::class);
    }

    public function ebookPages(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EbookPage::class);
    }

    public function ebookChapters(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(EbookChapter::class)->orderBy('start_page', 'asc');
    }
}
