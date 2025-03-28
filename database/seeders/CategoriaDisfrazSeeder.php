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
            // Tinku
            ['categoria_id' => 1, 'disfraz_id' => 1],
            ['categoria_id' => 8, 'disfraz_id' => 1],
            ['categoria_id' => 10, 'disfraz_id' => 1],

            // Caporal
            ['categoria_id' => 1, 'disfraz_id' => 2],
            ['categoria_id' => 8, 'disfraz_id' => 2],

            // Sailor Moon
            ['categoria_id' => 2, 'disfraz_id' => 3],
            ['categoria_id' => 9, 'disfraz_id' => 3],
            ['categoria_id' => 4, 'disfraz_id' => 3],

            // Kirito
            ['categoria_id' => 2, 'disfraz_id' => 4],
            ['categoria_id' => 5, 'disfraz_id' => 4],
            ['categoria_id' => 4, 'disfraz_id' => 4],
            ['categoria_id' => 10, 'disfraz_id' => 4],

            // Spider-Man
            ['categoria_id' => 3, 'disfraz_id' => 5],
            ['categoria_id' => 9, 'disfraz_id' => 5],
            ['categoria_id' => 4, 'disfraz_id' => 5],
            ['categoria_id' => 7, 'disfraz_id' => 5],
        ]);
    }
}
