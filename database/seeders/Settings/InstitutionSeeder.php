<?php

namespace Database\Seeders\Settings;

use App\Models\Settings\Institution;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        Institution::query()->updateOrCreate(
            ['email' => 'info@laratemp.test'],
            [
                'name' => "Default Institution",
                'address' => 'Jl. Contoh No. 123, Pontianak',
                'website' => 'https://laratemp.test',
                'appUrl' => config('app.url', 'http://localhost:8000'),
                'contact' => '+62 561 000 000',
                'created_by' => 'System',
                'updated_by' => null,
            ],
        );
    }
}
