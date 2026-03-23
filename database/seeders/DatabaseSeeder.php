<?php

namespace Database\Seeders;

use Database\Seeders\Auth\AdminUserSeeder;
use Database\Seeders\Auth\RoleAndPermissionSeeder;
use Database\Seeders\Settings\GeneralSettingsSeeder;
use Database\Seeders\Settings\InstitutionSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            AdminUserSeeder::class,
            GeneralSettingsSeeder::class,
            InstitutionSeeder::class,
        ]);
    }
}
