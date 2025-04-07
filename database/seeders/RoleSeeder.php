<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usa firstOrCreate para evitar errores si ya existen
        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        // Crear usuario Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
            ]
        );

        // Asignar rol y permisos
        $admin->assignRole($role);
        $admin->givePermissionTo(Permission::all());

        // Usuario Quito
        $quito = User::firstOrCreate(
            ['email' => 'quito@example.com'],
            [
                'name' => 'Quito',
                'password' => Hash::make('quito'),
            ]
        );

        $quito->assignRole($role);
        $quito->givePermissionTo(Permission::all());
    }
}
