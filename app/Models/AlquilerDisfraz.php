<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class AlquilerDisfraz extends Pivot
{
    protected $fillable = [
        'alquilerdisfraz_id',
        'disfraz_id',
        'precio_unitario',
        'cantidad',
        'piezas_seleccionadas',
        'piezas_reservadas',
    ];
    protected $casts = [
        'piezas_seleccionadas' => 'array', // Guardará solo IDs
        'piezas_reservadas' => 'array', // Guardará ID y cantidad de cada pieza
    ];
    public function alquiler(): BelongsTo
    {
        return $this->belongsTo(Alquiler::class);
    }

    public function disfraz(): BelongsTo
    {
        return $this->belongsTo(Disfraz::class);
    }
}
