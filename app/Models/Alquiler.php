<?php

namespace App\Models;

use App\Enums\AlquilerStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alquiler extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'image_path_garantia',
        'description',
        'tipo_garantia',
        'valor_garantia',
        'fecha_alquiler',
        'fecha_devolucion',
    ];
    protected $casts = [
        'status' => AlquilerStatusEnum::class,
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /*public function disfraces()
    {
        return $this->belongsToMany(Disfraz::class, 'alquiler_disfraz')
            ->withPivot('cantidad', 'precio_unitario')
            ->withTimestamps();
    }*/
    public function alquilerDisfrazs(): HasMany
    {
        return $this->hasMany(AlquilerDisfraz::class);
    }
}
