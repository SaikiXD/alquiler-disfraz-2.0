<?php

namespace App\Models;

use App\Enums\AlquilerStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Alquiler extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'image_path_garantia',
        'detalles_garantia',
        'tipo_garantia',
        'valor_garantia',
        'fecha_alquiler',
        'fecha_devolucion',
        'status',
    ];
    protected $casts = [
        'status' => AlquilerStatusEnum::class,
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    public function alquilerDisfrazs(): HasMany
    {
        return $this->hasMany(AlquilerDisfraz::class);
    }
    public function devolucion(): HasMany
    {
        return $this->hasMany(Devolucion::class);
    }
    public function getTotalAttribute(): int
    {
        $subTotal = $this->alquilerDisfrazs()->sum(DB::raw('cantidad * precio_alquiler'));
        return $subTotal + $this->valor_garantia;
    }
}
