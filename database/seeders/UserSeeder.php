<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Crear el rol admin si no existe
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);
        $adminRole->givePermissionTo(Permission::where('guard_name', 'web')->get());

        // 2. Insertar usuarios
        DB::table('users')->insert([
            [
                'name' => 'Personal',
                'email' => 'personal@example.com',
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

        // 3. Asignar rol
        $admin = User::where('email', 'quito@example.com')->first();
        $admin?->assignRole('admin');
    }
}
