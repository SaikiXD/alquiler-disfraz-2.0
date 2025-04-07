<?php

namespace Database\Seeders;

use App\Models\Disfraz;
use App\Models\Pieza;
use App\Models\TipoPieza;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('permissions:sync'); //generar permisos
        $this->call([
            ClienteSeeder::class,
            TallaSeeder::class,
            TipoPiezaSeeder::class,
            TipoPiezaTallaSeeder::class,
            CategoriaSeeder::class,
            DisfrazSeeder::class,
            CategoriaDisfrazSeeder::class,
            PiezaSeeder::class,
            DisfrazPiezaSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
