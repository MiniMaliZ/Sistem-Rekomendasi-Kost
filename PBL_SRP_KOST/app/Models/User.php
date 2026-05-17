<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'foto_url',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function kriteria(): HasMany
    {
        return $this->hasMany(Kriteria::class, 'id_user', 'id_user');
    }

    public function hasilRekomendasi(): HasMany
    {
        return $this->hasMany(HasilRekomendasi::class, 'id_user', 'id_user');
    }

    public function favorit(): HasMany
    {
        return $this->hasMany(Favorit::class, 'id_user', 'id_user');
    }

    public function riwayat(): HasMany
    {
        return $this->hasMany(Riwayat::class, 'id_user', 'id_user');
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class, 'id_user', 'id_user');
    }
}
