<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlquilerPieza extends Model
{
    protected $table = 'alquiler_pieza';
    protected $fillable = ['alquiler_id', 'disfraz_id', 'cantidad'];
    public function alquiler(): BelongsTo
    {
        return $this->belongsTo(Alquiler::class);
    }

    public function pieza(): BelongsTo
    {
        return $this->belongsTo(Pieza::class);
    }
}
