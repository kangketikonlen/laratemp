<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\LaravelSettings\SettingsRepositories\SettingsRepository;

class BaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedRolesAndPermissions();
        $this->seedAdminUser();
        $this->seedSettings();
    }

    protected function seedRolesAndPermissions(): void
    {
        // Reset cache (important for spatie)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'access dashboard',
            'manage users',
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $adminRole->syncPermissions($permissions);
    }

    protected function seedAdminUser(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'), // change later in production
            ]
        );

        $user->assignRole('admin');
    }

    protected function seedSettings(): void
    {
        $repo = app(SettingsRepository::class);

        $repo->updatePropertiesPayload('general', [
            'app_name' => config('app.name', 'Laravel'),
            'maintenance_mode' => false,
        ]);
    }
}