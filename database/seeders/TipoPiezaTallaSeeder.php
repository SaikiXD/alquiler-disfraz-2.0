<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoPiezaTallaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_pieza_talla')->insert([
            // Sombrero (tallas S–L)
            ['tipo_pieza_id' => 1, 'talla_id' => 2],
            ['tipo_pieza_id' => 1, 'talla_id' => 3],
            ['tipo_pieza_id' => 1, 'talla_id' => 4],

            // Chaqueta (XS–XL)
            ['tipo_pieza_id' => 2, 'talla_id' => 1],
            ['tipo_pieza_id' => 2, 'talla_id' => 2],
            ['tipo_pieza_id' => 2, 'talla_id' => 3],
            ['tipo_pieza_id' => 2, 'talla_id' => 4],
            ['tipo_pieza_id' => 2, 'talla_id' => 5],

            // Blusa (XS–XL)
            ['tipo_pieza_id' => 3, 'talla_id' => 1],
            ['tipo_pieza_id' => 3, 'talla_id' => 2],
            ['tipo_pieza_id' => 3, 'talla_id' => 3],
            ['tipo_pieza_id' => 3, 'talla_id' => 4],
            ['tipo_pieza_id' => 3, 'talla_id' => 5],

            // Pollera (XS–L)
            ['tipo_pieza_id' => 4, 'talla_id' => 1],
            ['tipo_pieza_id' => 4, 'talla_id' => 2],
            ['tipo_pieza_id' => 4, 'talla_id' => 3],
            ['tipo_pieza_id' => 4, 'talla_id' => 4],

            // Pantalón (XS–XL)
            ['tipo_pieza_id' => 5, 'talla_id' => 1],
            ['tipo_pieza_id' => 5, 'talla_id' => 2],
            ['tipo_pieza_id' => 5, 'talla_id' => 3],
            ['tipo_pieza_id' => 5, 'talla_id' => 4],
            ['tipo_pieza_id' => 5, 'talla_id' => 5],

            // Faja (S–L)
            ['tipo_pieza_id' => 6, 'talla_id' => 2],
            ['tipo_pieza_id' => 6, 'talla_id' => 3],
            ['tipo_pieza_id' => 6, 'talla_id' => 4],

            // Botas (36–41)
            ['tipo_pieza_id' => 7, 'talla_id' => 6],
            ['tipo_pieza_id' => 7, 'talla_id' => 7],
            ['tipo_pieza_id' => 7, 'talla_id' => 8],
            ['tipo_pieza_id' => 7, 'talla_id' => 9],
            ['tipo_pieza_id' => 7, 'talla_id' => 10],
            ['tipo_pieza_id' => 7, 'talla_id' => 11],

            // Rebozo (M–L)
            ['tipo_pieza_id' => 8, 'talla_id' => 3],
            ['tipo_pieza_id' => 8, 'talla_id' => 4],

            // Máscara (S–L)
            ['tipo_pieza_id' => 9, 'talla_id' => 2],
            ['tipo_pieza_id' => 9, 'talla_id' => 3],
            ['tipo_pieza_id' => 9, 'talla_id' => 4],
        ]);
    }
}
