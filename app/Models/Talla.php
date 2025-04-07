<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Talla extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'];
    public function tipos()
    {
        return $this->belongsToMany(TipoPieza::class, 'tipo_pieza_talla')->withTimestamps();
    }
}
