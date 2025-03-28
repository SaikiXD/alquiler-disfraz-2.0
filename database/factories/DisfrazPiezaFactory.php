<?php

namespace Database\Factories;

use App\Models\Disfraz;
use App\Models\Pieza;
use App\Models\DisfrazPieza;
use Illuminate\Database\Eloquent\Factories\Factory;

class DisfrazPiezaFactory extends Factory
{
    protected $model = DisfrazPieza::class;

    public function definition()
    {
        return [
            'disfraz_id' => Disfraz::factory(), // Si no se lo pasas, creará un Disfraz nuevo
            'pieza_id' => Pieza::factory(), // Igual para Pieza
            'stock' => $this->faker->numberBetween(1, 10),
            'price' => $this->faker->randomFloat(2, 10, 300),
            'color' => $this->faker->safeColorName(),
            'size' => $this->faker->randomElement(['XS', 'S', 'M', 'L', 'XL']),
            'material' => $this->faker->randomElement(['Algodón', 'Poliéster', 'Seda', 'Lana']),
            'gender' => $this->faker->randomElement(['masculino', 'femenino', 'unisex']),
            'status' => 'disponible', // por defecto
        ];
    }

    /**
     * States para cada status.
     */
    public function disponible()
    {
        return $this->state(
            fn() => [
                'status' => 'disponible',
                'stock' => $this->faker->numberBetween(1, 10),
            ]
        );
    }

    public function reservado()
    {
        return $this->state(
            fn() => [
                'status' => 'reservado',
                'stock' => 0,
            ]
        );
    }

    public function danado()
    {
        return $this->state(
            fn() => [
                'status' => 'dañado', // o 'danado' según tu migración
                'stock' => 0,
            ]
        );
    }

    public function perdido()
    {
        return $this->state(
            fn() => [
                'status' => 'perdido',
                'stock' => 0,
            ]
        );
    }
}
