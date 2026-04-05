<?php

use App\Models\Settings\Institution;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

function settingsInstitutionAdmin(): User
{
    test()->seed(DatabaseSeeder::class);

    return User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
}

it('shows and updates institution settings', function () {
    /** @var TestCase $this */
    Storage::fake('public');

    $admin = settingsInstitutionAdmin();

    $this->actingAs($admin)
        ->get(route('settings.institutions.index'))
        ->assertOk()
        ->assertSeeText('Institution');

    $this->actingAs($admin)
        ->put(route('settings.institutions.update'), [
            'name' => 'Updated Institution',
            'address' => 'Jl. Kebon Jeruk No. 1',
            'email' => 'institution@example.com',
            'website' => 'https://institution.example.com',
            'appUrl' => 'https://app.example.com',
            'contact' => '021-555-1234',
            'logo' => UploadedFile::fake()->image('logo.png'),
            'background' => UploadedFile::fake()->image('background.jpg', 1600, 900),
        ])
        ->assertRedirect(route('settings.institutions.index'))
        ->assertSessionHas('status');

    $institution = Institution::query()->firstOrFail();

    expect($institution->name)->toBe('Updated Institution')
        ->and($institution->updated_by)->toBe($admin->username)
        ->and($institution->logo)->not->toBeNull()
        ->and($institution->background)->not->toBeNull();

    Storage::disk('public')->assertExists($institution->logo);
    Storage::disk('public')->assertExists($institution->background);
});
