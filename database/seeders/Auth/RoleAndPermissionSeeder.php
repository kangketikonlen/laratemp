<?php

namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect([
            'access dashboard',
            'manage users',
            'manage settings',
        ])->map(fn (string $permission) => Permission::firstOrCreate(['name' => $permission]));

        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $adminRole->syncPermissions($permissions);
    }
}
