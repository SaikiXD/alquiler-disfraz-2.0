<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisfrazPiezaSeeder extends Seeder
{
    public function run(): void
    {
        $estado_extra = ['alquilado', 'retirado'];
        $registros = [];

        // Asignación de piezas a disfraces
        $disfrazPiezas = [
            1 => range(1, 5), // Morenada Hombre
            2 => range(6, 10), // Morenada Mujer
            3 => range(11, 15), // Caporal Hombre
            4 => range(16, 20), // Caporal Mujer
            5 => range(21, 25), // Tinku Hombre
            6 => range(26, 30), // Tinku Mujer
            7 => range(31, 35), // Diablada Hombre
            8 => range(36, 40), // Diablada Mujer
            9 => range(41, 45), // Saya Hombre
            10 => range(46, 50), // Saya Mujer
        ];

        foreach ($disfrazPiezas as $disfraz_id => $piezas) {
            foreach ($piezas as $pieza_id) {
                // Estado disponible con stock entre 15 y 50
                $registros[] = [
                    'disfraz_id' => $disfraz_id,
                    'pieza_id' => $pieza_id,
                    'stock' => rand(15, 50),
                    'status' => 'disponible',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Estados extra con stock 0
                foreach ($estado_extra as $estado) {
                    $registros[] = [
                        'disfraz_id' => $disfraz_id,
                        'pieza_id' => $pieza_id,
                        'stock' => 0,
                        'status' => $estado,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        DB::table('disfraz_pieza')->insert($registros);
    }
}
