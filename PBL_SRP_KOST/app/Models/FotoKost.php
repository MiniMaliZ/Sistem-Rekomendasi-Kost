<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoKost extends Model
{
    protected $table = 'foto_kost';

    protected $primaryKey = 'id_foto';

    public $timestamps = false;

    protected $fillable = [
        'id_kost',
        'foto_bangunan',
        'foto_kamar',
        'foto_kamar_mandi',
    ];

    protected $appends = [
        'foto_bangunan_url',
        'foto_kamar_url',
        'foto_kamar_mandi_url'
    ];

    /**
     * Mengubah path gambar menjadi URL yang bisa diakses browser
     */
    private function getImageUrl($path)
    {
        if (empty($path)) {
            return asset('images/no-image.jpg');
        }

        return asset($path);
    }

    public function getFotoBangunanUrlAttribute()
    {
        return $this->getImageUrl($this->foto_bangunan);
    }

    public function getFotoKamarUrlAttribute()
    {
        return $this->getImageUrl($this->foto_kamar);
    }

    public function getFotoKamarMandiUrlAttribute()
    {
        return $this->getImageUrl($this->foto_kamar_mandi);
    }

    public function kost()
    {
        return $this->belongsTo(Kost::class, 'id_kost', 'id_kost');
    }
}