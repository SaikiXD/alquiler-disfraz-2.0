<?php

namespace App\Models;
use App\Enums\PiezaStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pieza extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'tipo_id'];
    protected $casts = [
        'estado' => PiezaStatusEnum::class,
    ];
    public function disfrazs()
    {
        return $this->belongsToMany(Disfraz::class)->withTimestamps()->withPivot('stock', 'color', 'size', 'material');
    }
    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'tipo_id');
    }
}
