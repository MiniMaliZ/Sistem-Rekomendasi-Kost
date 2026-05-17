<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KostKriteria extends Model
{
    protected $table = 'kost_kriteria';
    protected $primaryKey = 'id_kost_kriteria';
    public $timestamps = false;

    protected $fillable = [
        'id_kost',
        'id_kriteria',
        'nilai',
    ];

    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class, 'id_kost', 'id_kost');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
