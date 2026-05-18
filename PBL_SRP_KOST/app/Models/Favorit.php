<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorit extends Model
{
    protected $table = 'favorit';
    protected $primaryKey = 'id_favorit';
    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'id_kost',
    ];

    protected $casts = [
        'created_at' => 'datetime',
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
