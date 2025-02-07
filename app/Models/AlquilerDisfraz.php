<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AlquilerDisfraz extends Pivot
{
    protected $fillable = ['alquilerdisfraz_id', 'disfraz_id', 'precio_unitario'];
    public function alquiler(): BelongsTo
    {
        return $this->belongsTo(Alquiler::class);
    }

    public function disfraz(): BelongsTo
    {
        return $this->belongsTo(Disfraz::class);
    }
    public function piezas()
    {
        return $this->hasMany(AlquilerPieza::class, 'alquiler_disfraz_id');
    }
}
