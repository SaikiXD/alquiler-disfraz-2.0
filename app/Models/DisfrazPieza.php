<?php

namespace App\Models;

use App\Enums\DisfrazPiezaEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisfrazPieza extends Model
{
    protected $table = 'disfraz_pieza';
    protected $fillable = ['pieza_id', 'disfraz_id', 'stock', 'color', 'size', 'material', 'status'];
    protected $casts = [
        'status' => DisfrazPiezaEnum::class,
    ];
    public function pieza(): BelongsTo
    {
        return $this->belongsTo(Pieza::class);
    }

    public function disfraz(): BelongsTo
    {
        return $this->belongsTo(Disfraz::class);
    }
}
