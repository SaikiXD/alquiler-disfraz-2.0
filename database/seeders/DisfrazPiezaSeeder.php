<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisfrazPiezaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Datos base
        $piezas = [
            // Disfraz 1: Tinku (stock 30)
            [1, 1, 30, 30.0, '#8B0000', 'M', 'lana', 'unisex'],
            [1, 2, 30, 45.0, '#A0522D', 'L', 'lana', 'masculino'],
            [1, 3, 30, 15.0, '#FFD700', 'L', 'tela', 'unisex'],
            [1, 4, 30, 25.0, '#FFFFFF', 'L', 'algodón', 'unisex'],
            [1, 5, 30, 20.0, '#654321', 'único', 'cuero', 'masculino'],

            // Disfraz 2: Caporal (stock 20)
            [2, 6, 20, 35.0, '#000000', 'L', 'fieltro', 'unisex'],
            [2, 7, 20, 55.0, '#4169E1', 'L', 'satinado', 'masculino'],
            [2, 8, 20, 40.0, '#1E90FF', 'L', 'satinado', 'masculino'],
            [2, 9, 20, 30.0, '#696969', '42', 'cuero', 'unisex'],

            // Disfraz 3: Sailor Moon (stock 50)
            [3, 10, 50, 100.0, '#FF69B4', 'M', 'licra', 'femenino'],
            [3, 11, 50, 10.0, '#FFD700', 'único', 'plástico', 'femenino'],
            [3, 12, 50, 8.0, '#FF0000', 'único', 'seda', 'femenino'],

            // Disfraz 4: Kirito (stock 15)
            [4, 13, 15, 60.0, '#000000', 'L', 'cuero sintético', 'masculino'],
            [4, 14, 15, 25.0, '#2F4F4F', 'único', 'plástico rígido', 'masculino'],
            [4, 15, 15, 30.0, '#1C1C1C', 'L', 'algodón', 'masculino'],
            [4, 16, 15, 10.0, '#333333', 'M', 'cuero', 'unisex'],

            // Disfraz 5: Spider-Man (stock 40)
            [5, 17, 40, 120.0, '#FF0000', 'L', 'licra', 'unisex'],
            [5, 18, 40, 20.0, '#0000FF', 'único', 'licra', 'unisex'],
        ];

        $estadosExtra = ['reservado', 'dañado', 'perdido'];

        $datos = [];

        foreach ($piezas as [$disfraz_id, $pieza_id, $stock, $price, $color, $size, $material, $gender]) {
            // Entrada original con estado "disponible"
            $datos[] = [
                'disfraz_id' => $disfraz_id,
                'pieza_id' => $pieza_id,
                'stock' => $stock,
                'price' => $price,
                'color' => $color,
                'size' => $size,
                'material' => $material,
                'gender' => $gender,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // 3 estados con stock 0
            foreach ($estadosExtra as $estado) {
                $datos[] = [
                    'disfraz_id' => $disfraz_id,
                    'pieza_id' => $pieza_id,
                    'stock' => 0,
                    'price' => $price,
                    'color' => $color,
                    'size' => $size,
                    'material' => $material,
                    'gender' => $gender,
                    'status' => $estado,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('disfraz_pieza')->insert($datos);
    }
}
