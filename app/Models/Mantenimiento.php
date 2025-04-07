<?php

namespace App\Models;

use App\Enums\MantenimientoEstadoEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mantenimiento extends Model
{
    protected $fillable = ['devolucion_disfraz_pieza_id', 'pieza_id', 'estado', 'cantidad', 'costo', 'detalles'];
    protected $casts = [
        'estado' => MantenimientoEstadoEnum::class,
    ];
    public function devolucionDisfrazPieza(): BelongsTo
    {
        return $this->belongsTo(DevolucionDisfrazPieza::class);
    }

    public function Pieza(): BelongsTo
    {
        return $this->belongsTo(Pieza::class);
    }
}
