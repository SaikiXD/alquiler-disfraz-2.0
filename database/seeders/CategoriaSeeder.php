<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categorias')->insert([
            [
                'name' => 'Fantasía',
                'description' =>
                    'Categoría que incluye disfraces relacionados con elementos fantásticos como hadas, magos, y criaturas mitológicas.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Históricos',
                'description' =>
                    'Disfraces basados en personajes y estilos de épocas pasadas, como guerreros romanos o piratas.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Profesiones',
                'description' =>
                    'Incluye disfraces representativos de diferentes profesiones, como médicos, policías o bomberos.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Superhéroes',
                'description' => 'Disfraces inspirados en personajes de cómics, películas y series de superhéroes.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Terror',
                'description' =>
                    'Categoría de disfraces relacionados con personajes y temáticas de terror, como zombis, vampiros y brujas.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Animales',
                'description' => 'Disfraces inspirados en animales de todo tipo, ideales para niños y adultos.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cultura Pop',
                'description' =>
                    'Disfraces de personajes y elementos icónicos de la cultura pop, como series, películas y videojuegos.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Infantiles',
                'description' =>
                    'Disfraces diseñados especialmente para niños pequeños con temas divertidos y coloridos.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
