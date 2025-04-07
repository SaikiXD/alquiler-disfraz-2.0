<?php

namespace App\Models;

use App\Enums\DisfrazPiezaEnum;
use App\Enums\DisfrazPiezaStatusEnum;
use App\Enums\DisfrazStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disfraz extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'descripcion', 'image_path', 'genero', 'precio_alquiler', 'status'];
    protected $casts = [
        'status' => DisfrazStatusEnum::class,
    ];
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
        return self::where('id', $id)->value('precio_alquiler');
    }
    public function getStockDisponibleAttribute(): int
    {
        // Obtiene las piezas disponibles del disfraz
        $piezasDisponibles = $this->disfrazPiezas()->where('status', DisfrazPiezaStatusEnum::DISPONIBLE->value)->get();
        // Si no hay piezas disponibles, no hay stock
        if ($piezasDisponibles->isEmpty()) {
            return 0;
        }
        if ($this->status === DisfrazStatusEnum::DISPONIBLE) {
            return $piezasDisponibles->min('stock') ?? 0;
        }
        return $piezasDisponibles->where('stock', '>', 0)->min('stock') ?? 0;
        // Devuelve el stock mínimo entre todas las piezas disponibles
    }
    public function actualizarEstado()
    {
        $totalPiezas = $this->disfrazPiezas()->where('status', DisfrazPiezaStatusEnum::DISPONIBLE->value)->count();
        $piezasAlquiladas = $this->disfrazPiezas()
            ->where('status', DisfrazPiezaStatusEnum::DISPONIBLE->value)
            ->where('stock', '>', 0)
            ->count();
        if ($piezasAlquiladas === 0) {
            $this->status = DisfrazStatusEnum::NO_DISPONiBLE->value;
        } elseif ($piezasAlquiladas === $totalPiezas) {
            $this->status = DisfrazStatusEnum::DISPONIBLE->value;
        } else {
            $this->status = DisfrazStatusEnum::INCOMPLETO->value;
        }

        $this->save();
    }
}
