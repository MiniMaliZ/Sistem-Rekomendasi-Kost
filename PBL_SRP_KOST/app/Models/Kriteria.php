<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kriteria extends Model
{
    protected $table = 'kriteria';
    protected $primaryKey = 'id_kriteria';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'nama_kriteria',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function kostKriteria(): HasMany
    {
        return $this->hasMany(KostKriteria::class, 'id_kriteria', 'id_kriteria');
    }

    public function userPreferensi(): HasMany
    {
        return $this->hasMany(UserPreferensi::class, 'id_kriteria', 'id_kriteria');
    }
}
