<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlquilerDisfraz extends Model
{
    protected $table = 'alquiler_disfraz';
    protected $fillable = [
        'alquiler_id',
        'disfraz_id',
        'precio_alquiler',
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
    public static function obtenerCantidad(int $disfrazId, int $alquilerId): ?float
    {
        return self::where('disfraz_id', $disfrazId)->where('alquiler_id', $alquilerId)->value('cantidad');
    }
}
