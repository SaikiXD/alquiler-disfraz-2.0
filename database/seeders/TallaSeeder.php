<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TallaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tallas')->insert([
            // Ropa general
            [
                'name' => 'XS',
                'description' => 'Extra pequeña. Ideal para niños o personas muy delgadas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'S',
                'description' => 'Pequeña. Talla común para adolescentes o personas delgadas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'M',
                'description' => 'Mediana. Talla estándar para la mayoría de adultos.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'L',
                'description' => 'Grande. Ideal para personas con complexión robusta.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'XL',
                'description' => 'Extra grande. Usada por personas con contextura más grande.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Calzados (tallas de botas o zapatos)
            [
                'name' => '36',
                'description' => 'Talla de calzado pequeña. Usada por adolescentes o mujeres.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '37',
                'description' => 'Talla de calzado común femenina.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '38',
                'description' => 'Talla de calzado estándar (europeo).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '39',
                'description' => 'Talla de calzado intermedia (europeo).',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '40',
                'description' => 'Talla de calzado común para varones.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => '41',
                'description' => 'Talla de calzado grande. Común en varones altos.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Única',
                'description' => 'Talla universal para piezas sin variación de tamaño.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
