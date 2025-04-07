<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $models = ['Role', 'Permission'];
        $actions = ['view-any', 'view', 'create', 'update', 'delete', 'restore', 'force-delete'];

        foreach ($models as $model) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action} {$model}",
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
