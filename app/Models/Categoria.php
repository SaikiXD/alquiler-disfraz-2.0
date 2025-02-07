<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    public function disfrazs()
    {
        return $this->belongsToMany(Disfraz::class)
            ->withTimestamps();
    }
    protected $fillable = ['name','description','status'];
}
