<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EbookChapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'buku_id',
        'title',
        'start_page',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
