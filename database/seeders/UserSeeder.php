<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'), // Contraseña encriptada
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Usuario1',
                'email' => 'user1@example.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Quito',
                'email' => 'quito@example.com',
                'password' => Hash::make('quito'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
