<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlquilerDisfrazPieza extends Model
{
    protected $table = 'alquiler_disfraz_pieza';
    protected $fillable = ['alquiler_disfraz_id', 'pieza_id', 'cantidad_reservada'];
    public function alquilerDisfraz(): BelongsTo
    {
        return $this->belongsTo(AlquilerDisfraz::class);
    }

    public function pieza(): BelongsTo
    {
        return $this->belongsTo(Pieza::class);
    }
    public function devolucionPiezas(): HasMany
    {
        return $this->hasMany(DevolucionDisfrazPieza::class);
    }
}
