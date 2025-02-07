<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisfrazPieza extends Model
{
    protected $table = 'disfraz_pieza';
    protected $fillable = ['pieza_id', 'disfraz_id', 'stock', 'color', 'size', 'material'];
    public function pieza(): BelongsTo
    {
        return $this->belongsTo(Pieza::class);
    }

    public function disfraz(): BelongsTo
    {
        return $this->belongsTo(Disfraz::class);
    }
    public static function obtenerPiezasPorDisfraz($disfrazId)
    {
        return self::where('disfraz_id', $disfrazId)->pluck('pieza_id', 'pieza_id');
    }
}
