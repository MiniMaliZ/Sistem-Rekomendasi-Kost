<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilRekomendasi extends Model
{
    protected $table = 'hasil_rekomendasi';
    protected $primaryKey = 'id_rekomendasi';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'id_kost',
        'skor',
        'rangking',
    ];

    protected $casts = [
        'skor' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class, 'id_kost', 'id_kost');
    }
}
