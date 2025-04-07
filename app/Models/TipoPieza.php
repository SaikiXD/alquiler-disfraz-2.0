<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPieza extends Model
{
    use HasFactory;
    public function tallas()
    {
        return $this->belongsToMany(Talla::class, 'tipo_pieza_talla')->withTimestamps();
    }
    protected $fillable = ['name', 'description'];
}
