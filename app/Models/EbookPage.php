<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EbookPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'buku_id',
        'page_number',
        'content',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }
}
