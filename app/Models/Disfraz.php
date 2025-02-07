<?php

namespace App\Models;

use App\Enums\DisfrazStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disfraz extends Model
{
    protected $fillable = ['name', 'description', 'gender', 'image_path', 'price'];
    protected $casts = [
        'status' => DisfrazStatusEnum::class,
    ];
    public function piezas()
    {
        return $this->belongsToMany(Pieza::class)->withTimestamps()->withPivot('stock', 'color', 'size', 'material');
    }
    public function categorias()
    {
        return $this->belongsToMany(Categoria::class)->withTimestamps();
    }
    public function alquileres()
    {
        return $this->belongsToMany(Alquiler::class, 'alquiler_disfraz')
            ->withPivot('precio_unitario')
            ->withTimestamps();
    }
    public static function obtenerPrecio(int $id): ?float
    {
        return self::where('id', $id)->value('price');
    }
}
