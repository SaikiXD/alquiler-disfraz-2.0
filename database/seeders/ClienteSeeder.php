<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = DB::table('users')->where('email', 'quito@example.com')->first();
        DB::table('clientes')->insert([
            [
                'user_id' => $user1->id ?? null, // Sin usuario vinculado
                'name' => 'Juan Pérez',
                'ci' => 12345678,
                'email' => 'juan.perez@example.com',
                'address' => 'Av. Libertador 123',
                'phone' => 76543210,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user1->id ?? null,
                'name' => 'María Gómez',
                'ci' => 23456789,
                'email' => 'maria.gomez@example.com',
                'address' => 'Calle Bolívar 456',
                'phone' => 76543211,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user1->id ?? null,
                'name' => 'Carlos Rodríguez',
                'ci' => 34567890,
                'email' => 'carlos.rodriguez@example.com',
                'address' => 'Zona Sur 789',
                'phone' => 76543212,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user1->id ?? null,
                'name' => 'Ana López',
                'ci' => 45678901,
                'email' => 'ana.lopez@example.com',
                'address' => 'Villa Progreso 101',
                'phone' => 76543213,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user1->id ?? null,
                'name' => 'Pedro Castillo',
                'ci' => 56789012,
                'email' => 'pedro.castillo@example.com',
                'address' => 'Urbanización Central 303',
                'phone' => 76543214,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user1->id ?? null,
                'name' => 'Laura Fernández',
                'ci' => 67890123,
                'email' => 'laura.fernandez@example.com',
                'address' => 'Barrio Norte 404',
                'phone' => 76543215,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user1->id ?? null,
                'name' => 'Jorge Martínez',
                'ci' => 78901234,
                'email' => 'jorge.martinez@example.com',
                'address' => 'Zona Este 505',
                'phone' => 76543216,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user1->id ?? null,
                'name' => 'Lucía Sánchez',
                'ci' => 89012345,
                'email' => 'lucia.sanchez@example.com',
                'address' => 'Centro Histórico 606',
                'phone' => 76543217,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user1->id ?? null,
                'name' => 'Diego Ramírez',
                'ci' => 90123456,
                'email' => 'diego.ramirez@example.com',
                'address' => 'Residencial Oeste 707',
                'phone' => 76543218,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $user1->id ?? null,
                'name' => 'Camila Herrera',
                'ci' => 12345098,
                'email' => 'camila.herrera@example.com',
                'address' => 'Ciudad Nueva 808',
                'phone' => 76543219,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
