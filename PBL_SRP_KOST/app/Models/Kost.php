<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kost extends Model
{
    protected $table = 'kost';
    protected $primaryKey = 'id_kost';
    public $timestamps = true;

    protected $fillable = [
        'nama_kost',
        'harga',
        'tipe_kos',
        'sepesifikasi_tipe_kamar',
        'fasilitas_kamar',
        'fasilitas_kamar_mandi',
        'fasilitas_umum',
        'fasilitas_parkir',
        'tempat_terdekat',
        'peraturan_kos',
        'link_original',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function kostKriteria(): HasMany
    {
        return $this->hasMany(KostKriteria::class, 'id_kost', 'id_kost');
    }

    public function hasilRekomendasi(): HasMany
    {
        return $this->hasMany(HasilRekomendasi::class, 'id_kost', 'id_kost');
    }

    public function favorit(): HasMany
    {
        return $this->hasMany(Favorit::class, 'id_kost', 'id_kost');
    }

    public function riwayat(): HasMany
    {
        return $this->hasMany(Riwayat::class, 'id_kost', 'id_kost');
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class, 'id_kost', 'id_kost');
    }

    public function getTipeKosLabelAttribute(): string
    {
        return match($this->tipe_kos) {
            'putra' => 'Putra',
            'putri' => 'Putri',
            'campur' => 'Campur',
            default => '-',
        };
    }

    public function getHargaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
