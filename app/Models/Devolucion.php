<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Devolucion extends Model
{
    use HasFactory;
    protected $fillable = ['alquiler_id', 'fecha_devolucion_real', 'multa', 'estado'];
    protected $casts = [
        'estado' => 'boolean',
    ];
    public function alquiler(): BelongsTo
    {
        return $this->belongsTo(Alquiler::class);
    }
    public function devolucionPiezas(): HasMany
    {
        return $this->hasMany(DevolucionDisfrazPieza::class);
    }
}
