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
                'name' => 'Morenada',
                'description' =>
                    'Trajes tradicionales de la danza Morenada, usados en festividades como el Gran Poder o el Carnaval de Oruro.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Caporales',
                'description' =>
                    'Vestimenta típica de los Caporales, danza moderna y energética reconocida en Bolivia y el extranjero.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tinku',
                'description' => 'Trajes coloridos de la danza guerrera Tinku, propia del altiplano boliviano.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Diablada',
                'description' =>
                    'Disfraces de la emblemática danza Diablada, con máscaras, capas y bordados brillantes.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Saya Afroboliviana',
                'description' => 'Vestimenta usada en la danza Saya, expresión cultural de la comunidad afroboliviana.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Danzas Regionales',
                'description' =>
                    'Otros trajes típicos de danzas como Tobas, Kullawada, Llamerada, Pujllay, Chacarera, etc.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Infantil',
                'description' => 'Trajes folclóricos adaptados para niños y niñas en eventos escolares o festivales.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Época y Fantasía',
                'description' => 'Trajes con inspiración histórica o estética tradicional fusionada con lo fantástico.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Otros Folclóricos',
                'description' => 'Categoría general para trajes folclóricos que no encajan en una sola danza o estilo.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cosplay / Fiestas',
                'description' =>
                    'Disfraces no folclóricos como superhéroes, anime, Halloween u otros temáticos para eventos especiales.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
