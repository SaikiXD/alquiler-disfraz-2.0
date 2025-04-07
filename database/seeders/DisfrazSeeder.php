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
        $disfraces = [
            // Morenada
            [
                'nombre' => 'Traje de Morenada',
                'descripcion' =>
                    'Traje típico de la danza Morenada para varón, incluye chaqueta bordada, pantalón y máscara metálica.',
                'image_path' => 'disfraces/morenadah.jpg',
                'genero' => 'masculino',
                'precio_alquiler' => 120.0,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Traje de Morenada',
                'descripcion' =>
                    'Pollera bordada, blusa brillante y sombrero con plumas para la danza Morenada femenina.',
                'image_path' => 'disfraces/morenadam.png',
                'genero' => 'femenino',
                'precio_alquiler' => 110.0,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Caporales
            [
                'nombre' => 'Traje de Caporal',
                'descripcion' => 'Traje de Caporal con botas, sombrero y bordados brillantes para varón.',
                'image_path' => 'disfraces/caporalh.jpg',
                'genero' => 'masculino',
                'precio_alquiler' => 130.0,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Traje de Caporal',
                'descripcion' => 'Mini pollera bordada, blusa ajustada y botas altas para la danza Caporal femenina.',
                'image_path' => 'disfraces/caporalm.jpg',
                'genero' => 'femenino',
                'precio_alquiler' => 120.0,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Tinku
            [
                'nombre' => 'Traje de Tinku',
                'descripcion' =>
                    'Traje tradicional del Tinku masculino, incluye poncho, gorro y pantalón de colores vivos.',
                'image_path' => 'disfraces/tinkuh.jpg',
                'genero' => 'masculino',
                'precio_alquiler' => 90.0,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Traje de Tinku',
                'descripcion' => 'Traje de Tinku para mujer con pollera amplia, chalina y adornos en la cabeza.',
                'image_path' => 'disfraces/tinkum.jpg',
                'genero' => 'femenino',
                'precio_alquiler' => 85.0,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Diablada
            [
                'nombre' => 'Traje de Diablada',
                'descripcion' => 'Traje de diablo con máscara elaborada, capa y bordados brillantes para varón.',
                'image_path' => 'disfraces/diabladah.jpg',
                'genero' => 'masculino',
                'precio_alquiler' => 140.0,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Traje de Diablada',
                'descripcion' => 'Traje femenino de Diablada con pollera, corset bordado y máscara opcional.',
                'image_path' => 'disfraces/diabladam.jpg',
                'genero' => 'femenino',
                'precio_alquiler' => 135.0,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Saya Afroboliviana
            [
                'nombre' => 'Traje de Saya',
                'descripcion' => 'Traje varonil de Saya afroboliviana con camisa, pantalón y sombrero.',
                'image_path' => 'disfraces/sayah.png',
                'genero' => 'masculino',
                'precio_alquiler' => 100.0,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Traje de Saya',
                'descripcion' => 'Traje de Saya femenino con pollera amplia, blusa colorida y pañuelo.',
                'image_path' => 'disfraces/sayam.jpg',
                'genero' => 'femenino',
                'precio_alquiler' => 95.0,
                'status' => 'disponible',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($disfraces as $disfraz) {
            Disfraz::create($disfraz);
        }
    }
}
