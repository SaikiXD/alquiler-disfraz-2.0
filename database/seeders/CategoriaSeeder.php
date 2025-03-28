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
                'name' => 'Folklore Boliviano',
                'description' => 'Disfraces típicos de danzas y culturas bolivianas.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Anime y Manga',
                'description' => 'Cosplays de personajes de anime y manga japonés.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Superhéroes',
                'description' => 'Trajes de personajes heroicos del cine y cómics.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cuerpo Completo',
                'description' => 'Disfraces que se alquilan como una unidad indivisible.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ciencia Ficción',
                'description' => 'Disfraces inspirados en mundos futuristas o tecnológicos.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Villanos',
                'description' => 'Disfraces de antagonistas populares de ficción.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Halloween',
                'description' => 'Disfraces usados tradicionalmente en fiestas de disfraces o terror.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Danza',
                'description' => 'Disfraces asociados a bailes y expresiones culturales.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Niños y niñas',
                'description' => 'Disfraces enfocados en público infantil.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Época y Fantasía',
                'description' => 'Trajes inspirados en periodos históricos o mundos imaginarios.',
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
