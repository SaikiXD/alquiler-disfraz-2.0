<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PiezaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('piezas')->insert([
            // Tinku
            ['tipo_id' => 2, 'name' => 'Gorro andino', 'created_at' => now(), 'updated_at' => now()],
            ['tipo_id' => 1, 'name' => 'Poncho tradicional', 'created_at' => now(), 'updated_at' => now()],
            ['tipo_id' => 1, 'name' => 'Faja colorida', 'created_at' => now(), 'updated_at' => now()],
            ['tipo_id' => 1, 'name' => 'Pantalón blanco', 'created_at' => now(), 'updated_at' => now()],
            ['tipo_id' => 3, 'name' => 'Látigo de cuero', 'created_at' => now(), 'updated_at' => now()],

            // Caporal
            ['tipo_id' => 2, 'name' => 'Sombrero de caporal', 'created_at' => now(), 'updated_at' => now()],
            ['tipo_id' => 1, 'name' => 'Chaqueta bordada', 'created_at' => now(), 'updated_at' => now()],
            ['tipo_id' => 1, 'name' => 'Pantalón con detalles', 'created_at' => now(), 'updated_at' => now()],
            ['tipo_id' => 2, 'name' => 'Botas con cascabeles', 'created_at' => now(), 'updated_at' => now()],

            // Sailor Moon
            ['tipo_id' => 5, 'name' => 'Uniforme Sailor Moon', 'created_at' => now(), 'updated_at' => now()],
            ['tipo_id' => 2, 'name' => 'Tiara lunar', 'created_at' => now(), 'updated_at' => now()],
            ['tipo_id' => 2, 'name' => 'Moño rojo', 'created_at' => now(), 'updated_at' => now()],

            // Kirito
            ['tipo_id' => 1, 'name' => 'Abrigo negro largo', 'created_at' => now(), 'updated_at' => now()],
            ['tipo_id' => 3, 'name' => 'Espada negra', 'created_at' => now(), 'updated_at' => now()],
            ['tipo_id' => 1, 'name' => 'Pantalón oscuro', 'created_at' => now(), 'updated_at' => now()],
            ['tipo_id' => 2, 'name' => 'Guantes oscuros', 'created_at' => now(), 'updated_at' => now()],

            // Spider-Man
            ['tipo_id' => 5, 'name' => 'Traje enterizo Spider-Man', 'created_at' => now(), 'updated_at' => now()],
            ['tipo_id' => 2, 'name' => 'Máscara Spider-Man', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
