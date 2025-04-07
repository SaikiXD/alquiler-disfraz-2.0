<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaDisfrazSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categoria_disfraz')->insert([
            // Morenada
            ['categoria_id' => 1, 'disfraz_id' => 1],
            ['categoria_id' => 1, 'disfraz_id' => 2],
            ['categoria_id' => 8, 'disfraz_id' => 1],
            ['categoria_id' => 8, 'disfraz_id' => 2],

            // Caporal
            ['categoria_id' => 2, 'disfraz_id' => 3],
            ['categoria_id' => 2, 'disfraz_id' => 4],
            ['categoria_id' => 8, 'disfraz_id' => 3],
            ['categoria_id' => 8, 'disfraz_id' => 4],

            // Tinku
            ['categoria_id' => 3, 'disfraz_id' => 5],
            ['categoria_id' => 3, 'disfraz_id' => 6],
            ['categoria_id' => 6, 'disfraz_id' => 5],
            ['categoria_id' => 6, 'disfraz_id' => 6],

            // Diablada
            ['categoria_id' => 4, 'disfraz_id' => 7],
            ['categoria_id' => 4, 'disfraz_id' => 8],
            ['categoria_id' => 8, 'disfraz_id' => 7],
            ['categoria_id' => 8, 'disfraz_id' => 8],

            // Saya Afroboliviana
            ['categoria_id' => 5, 'disfraz_id' => 9],
            ['categoria_id' => 5, 'disfraz_id' => 10],
            ['categoria_id' => 6, 'disfraz_id' => 9],
            ['categoria_id' => 6, 'disfraz_id' => 10],
        ]);
    }
}
