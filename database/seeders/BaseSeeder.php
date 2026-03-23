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
            ['username' => 'support'],
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('PWD@1945.,'), // change later in production
            ]
        );

        $user->assignRole('admin');
    }

    protected function seedSettings(): void
    {
        $repo = app(SettingsRepository::class);

        $repo->updatePropertiesPayload('general', [
            'app_name' => 'LaraTemp',
            'app_description' => 'LaraTemp adalah boilerplate Laravel yang menyediakan arsitektur dasar, konfigurasi, dan komponen reusable untuk mempercepat development serta menjaga konsistensi antar proyek.',
            'app_copyright' => '© 2026 | Default Institution | Alamat institusi dapat diatur melalui pengaturan aplikasi.',
            'maintenance_mode' => false,
        ]);
    }
}
