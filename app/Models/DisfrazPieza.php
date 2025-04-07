<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisfrazPieza extends Model
{
    protected $table = 'disfraz_pieza';
    protected $fillable = ['disfraz_id', 'pieza_id', 'stock', 'status'];
    public function disfraz(): BelongsTo
    {
        return $this->belongsTo(Disfraz::class);
    }

    public function pieza(): BelongsTo
    {
        return $this->belongsTo(Pieza::class);
    }
}
