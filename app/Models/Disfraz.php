<?php

namespace App\Models;

use App\Enums\DisfrazStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disfraz extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'image_path', 'price', 'status'];
    protected $casts = [
        'status' => DisfrazStatusEnum::class,
    ];
    /*
    public function piezas()
    {
        return $this->belongsToMany(Pieza::class)
            ->withTimestamps()
            ->withPivot('stock', 'color', 'size', 'material', 'status');
    }*/
    public function categorias()
    {
        return $this->belongsToMany(Categoria::class)->withTimestamps();
    }
    public function alquilerDisfrazs(): HasMany
    {
        return $this->hasMany(AlquilerDisfraz::class);
    }
    public function disfrazPiezas()
    {
        return $this->hasMany(DisfrazPieza::class);
    }
    public static function obtenerPrecio(int $id): ?float
    {
        return self::where('id', $id)->value('price');
    }
    public function getStockDisponibleAttribute(): int
    {
        // Obtiene las piezas disponibles del disfraz
        $piezasDisponibles = $this->disfrazPiezas()->where('status', 'disponible')->get();
        // Si no hay piezas disponibles, no hay stock
        if ($piezasDisponibles->isEmpty()) {
            return 0;
        }
        // Devuelve el stock mínimo entre todas las piezas disponibles
        return $piezasDisponibles->min('stock');
    }
}
