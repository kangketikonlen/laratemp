<?php

namespace Database\Seeders\Administration;

use App\Models\Administration\Changelog;
use Illuminate\Database\Seeder;

class ChangelogSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            [
                'version' => 'v1.2.0',
                'title' => 'Role and User Access Manager',
                'status' => 'published',
                'released_at' => now()->subDays(1)->toDateString(),
                'notes' => '<p>Role access manager dan direct user access sekarang tersedia dari halaman settings permission.</p>',
                'created_by' => 'System',
                'updated_by' => 'System',
            ],
            [
                'version' => 'v1.1.0',
                'title' => 'Institution Branding Settings',
                'status' => 'published',
                'released_at' => now()->subDays(5)->toDateString(),
                'notes' => '<p>Form institution sekarang mendukung upload logo dan background login.</p>',
                'created_by' => 'System',
                'updated_by' => 'System',
            ],
            [
                'version' => 'v1.0.0',
                'title' => 'Workspace Module Foundation',
                'status' => 'published',
                'released_at' => now()->subDays(12)->toDateString(),
                'notes' => '<p>Fondasi module utama untuk master, settings, administration, dan report sudah siap dipakai.</p>',
                'created_by' => 'System',
                'updated_by' => 'System',
            ],
        ];

        foreach ($entries as $entry) {
            Changelog::query()->updateOrCreate(
                ['version' => $entry['version']],
                $entry,
            );
        }
    }
}
