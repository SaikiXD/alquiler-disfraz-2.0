<?php

namespace Database\Factories;

use App\Models\Disfraz;
use Illuminate\Database\Eloquent\Factories\Factory;

class DisfrazFactory extends Factory
{
    protected $model = Disfraz::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),

            // Usa un path genérico o una imagen ficticia
            'image_path' => 'disfraces/' . $this->faker->lexify('????') . '.jpg',
            'price' => $this->faker->randomFloat(2, 10, 300),
            'status' => $this->faker->randomElement(['disponible', 'incompleto', 'no_disponible']),
        ];
    }
}
