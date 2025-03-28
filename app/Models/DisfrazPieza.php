<?php

namespace App\Models;

use App\Enums\DisfrazPiezaEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class DisfrazPieza extends Pivot
{
    use HasFactory;

    public $incrementing = true;
    protected $table = 'disfraz_pieza';
    protected $fillable = ['pieza_id', 'disfraz_id', 'stock', 'color', 'price', 'size', 'material', 'gender', 'status'];
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
