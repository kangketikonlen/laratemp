<?php

namespace Database\Seeders\Settings;

use Illuminate\Database\Seeder;
use Spatie\LaravelSettings\SettingsRepositories\SettingsRepository;

class GeneralSettingsSeeder extends Seeder
{
    public function run(): void
    {
        app(SettingsRepository::class)->updatePropertiesPayload('general', [
            'app_name' => 'LaraTemp',
            'app_description' => 'LaraTemp adalah boilerplate Laravel yang menyediakan arsitektur dasar, konfigurasi, dan komponen reusable untuk mempercepat development serta menjaga konsistensi antar proyek.',
            'app_copyright' => '© 2026 | Default Institution | Alamat institusi akan mengikuti data institusi.',
            'maintenance_mode' => false,
        ]);
    }
}
