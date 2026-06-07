<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPreferensi extends Model
{
    protected $table = 'user_preferensi';
    protected $primaryKey = 'id_preferensi';
    public $timestamps = true;

    protected $fillable = [
        'id_kriteria',
        'bobot',
    ];

    protected $casts = [
        'bobot' => 'decimal:2',
    ];

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
