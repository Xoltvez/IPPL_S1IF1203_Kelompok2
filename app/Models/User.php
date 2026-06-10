<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'foto',
        'no_telp',
        'notif_persetujuan',
        'notif_pengembalian',
        'notif_jatuh_tempo',
        'notif_rekomendasi',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notif_persetujuan' => 'boolean',
            'notif_pengembalian' => 'boolean',
            'notif_jatuh_tempo' => 'boolean',
            'notif_rekomendasi' => 'boolean',
        ];
    }

    public function favoritBukus(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Buku::class, 'favorits', 'user_id', 'buku_id')->withTimestamps();
    }

    public function bookmarks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    public function ulasans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Ulasan::class);
    }
}
