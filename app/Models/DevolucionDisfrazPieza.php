<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevolucionDisfrazPieza extends Model
{
    protected $table = 'devolucion_disfraz_pieza';
    protected $fillable = ['devolucion_id', 'alquiler_disfraz_pieza_id', 'cantidad', 'estado_pieza'];
    public function devolucion(): BelongsTo
    {
        return $this->belongsTo(Devolucion::class);
    }

    public function alquilerDisfrazPieza(): BelongsTo
    {
        return $this->belongsTo(alquilerDisfrazPieza::class);
    }
}
