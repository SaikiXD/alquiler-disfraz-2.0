<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoPiezaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_piezas')->insert([
            // Piezas comunes
            [
                'name' => 'Sombrero',
                'description' => 'Accesorio para la cabeza en trajes típicos.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chaqueta',
                'description' => 'Parte superior del vestuario tradicional.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Blusa',
                'description' => 'Prenda femenina superior que forma parte del traje típico.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pollera',
                'description' => 'Falda tradicional usada en danzas folklóricas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pantalón',
                'description' => 'Prenda inferior masculina usada en trajes tradicionales.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Faja',
                'description' => 'Cinturón decorativo que acompaña a muchos trajes.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Botas',
                'description' => 'Calzado típico, muchas veces adornado o bordado.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rebozo',
                'description' => 'Manta tradicional usada sobre los hombros o la cabeza.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Máscara',
                'description' => 'Elemento facial representativo de ciertos personajes o danzas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Piezas adicionales específicas
            [
                'name' => 'Pechera',
                'description' => 'Accesorio decorativo para el pecho, típico en morenadas y diabladas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Capa',
                'description' => 'Capa bordada usada especialmente en danzas como Diablada y Morenada.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chalina',
                'description' => 'Bufanda decorativa o simbólica, usada por varones y mujeres.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Escapulario',
                'description' => 'Accesorio religioso o decorativo que cuelga del cuello.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Espuelas',
                'description' => 'Accesorio metálico que se coloca en las botas de caporales.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cascabeles',
                'description' => 'Usados en botas o cinturones, especialmente en caporales y tobas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tocado',
                'description' => 'Accesorio de plumas o bordados que se lleva en la cabeza.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cinturón',
                'description' => 'Cinturón adicional al de la faja, a veces metálico o decorativo.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Medias',
                'description' => 'Usadas especialmente en trajes femeninos, a veces decoradas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
