<?php

namespace Database\Seeders;

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
            BaseSeeder::class,
            InstitutionSeeder::class,
        ]);
    }
}
