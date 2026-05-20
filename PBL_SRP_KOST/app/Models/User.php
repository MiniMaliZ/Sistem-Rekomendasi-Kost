<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * URL foto profil — path relatif agar tetap benar di Laragon (bukan localhost dari APP_URL).
     */
    protected function avatarUrl(): Attribute
    {
        return Attribute::get(function () {
            $default = '/images/profile_user.png';

            if (empty($this->foto_url)) {
                return $default;
            }

            if (filter_var($this->foto_url, FILTER_VALIDATE_URL)) {
                return $this->foto_url;
            }

            $filename = basename($this->foto_url);

            $publicPath = public_path('uploads/profile/' . $filename);
            if (is_file($publicPath)) {
                return '/uploads/profile/' . $filename . '?v=' . filemtime($publicPath);
            }

            $storagePath = storage_path('app/public/profile/' . $filename);
            if (is_file($storagePath)) {
                return '/storage/profile/' . $filename . '?v=' . filemtime($storagePath);
            }

            return $default;
        });
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
