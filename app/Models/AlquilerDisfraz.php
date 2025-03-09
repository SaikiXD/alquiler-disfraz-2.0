<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AlquilerDisfraz extends Pivot
{
    protected $fillable = [
        'alquiler_id',
        'disfraz_id',
        'precio_unitario',
        'cantidad',
        'piezas_seleccionadas',
        'status',
    ];
    protected $casts = [
        'piezas_seleccionadas' => 'array', // Guardará solo IDs
    ];
    public function alquiler(): BelongsTo
    {
        return $this->belongsTo(Alquiler::class);
    }

    public function disfraz(): BelongsTo
    {
        return $this->belongsTo(Disfraz::class);
    }
    public function alquilerPiezas(): HasMany
    {
        return $this->hasMany(AlquilerDisfrazPieza::class);
    }
}
