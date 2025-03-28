<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Disfraz;
use App\Models\Pieza;
use App\Models\DisfrazPieza;
use Illuminate\Support\Facades\DB;

class DisfrazSeeder extends Seeder
{
    public function run()
    {
        DB::table('disfrazs')->insert([
            [
                'name' => 'Tinku',
                'description' =>
                    'Traje tradicional guerrero de la cultura andina, usado en rituales y danzas de combate.',
                'image_path' => 'tinku.jpg',
                'price' => 130.0,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Caporal',
                'description' => 'Disfraz típico de la danza Caporales, con bordados brillantes y botas altas.',
                'image_path' => 'caporal.jpg',
                'price' => 150.0,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sailor Moon',
                'description' => 'Traje de la heroína mágica del anime clásico. Ideal para cosplay y eventos otaku.',
                'image_path' => 'sailor-moon.jpg',
                'price' => 170.0,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kirito (SAO)',
                'description' => 'Disfraz del espadachín negro de Sword Art Online. Con abrigo largo y accesorios.',
                'image_path' => 'kirito.jpg',
                'price' => 180.0,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Spider-Man',
                'description' => 'Icónico traje del superhéroe arácnido, ideal para niños y adultos.',
                'image_path' => 'spiderman.jpg',
                'price' => 160.0,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
