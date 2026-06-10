<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kunjungan extends Model
{
    protected $fillable = [
        'user_id',
    ];

    /**
     * Get the member user that checked in.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
