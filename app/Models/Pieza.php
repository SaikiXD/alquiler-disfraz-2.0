<?php

namespace App\Models;
use App\Enums\PiezaStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pieza extends Model
{
    use HasFactory;
    protected $fillable = ['tipo_pieza_id', 'talla_id', 'name', 'valor_reposicion', 'color', 'material', 'notas'];
    public function tipo()
    {
        return $this->belongsTo(TipoPieza::class);
    }
    public function talla()
    {
        return $this->belongsTo(Talla::class);
    }

    public function disfrazPiezas()
    {
        return $this->hasMany(DisfrazPieza::class);
    }
}
