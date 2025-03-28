<?php

namespace Database\Factories;

use App\Models\Pieza;
use App\Models\Tipo;
use Illuminate\Database\Eloquent\Factories\Factory;

class PiezaFactory extends Factory
{
    protected $model = Pieza::class;

    public function definition()
    {
        return [
            // Si tu tabla 'piezas' requiere un 'tipo_id', podrías:
            // 'tipo_id' => Tipo::factory(), // o asignar un valor existente
            'tipo_id' => $this->faker->numberBetween(1, 3),
            // por ejemplo, fijo si no tienes la factory de Tipo,
            'name' => $this->faker->word(),
        ];
    }
}
