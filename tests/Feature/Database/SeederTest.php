<?php

use App\Models\Settings\Institution;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

it('seeds the default auth and settings data', function () {
    /** @var \Tests\TestCase $this */
    $this->seed(DatabaseSeeder::class);

    $admin = User::query()->where('username', config('bootstrap_admin.username'))->first();
    $role = Role::query()->where('name', 'admin')->first();
    $institution = Institution::query()->first();

    expect($admin)->not->toBeNull()
        ->and($role)->not->toBeNull()
        ->and($institution)->not->toBeNull()
        ->and($institution->address)->not->toBeEmpty();

    expect($admin->hasRole('admin'))->toBeTrue();

    expect(Permission::query()->pluck('name')->sort()->values()->all())
        ->toBe(['access dashboard', 'manage settings', 'manage users']);
});

it('seeds the general settings payload', function () {
    /** @var \Tests\TestCase $this */
    $this->seed(DatabaseSeeder::class);

    $settings = app(\App\Settings\GeneralSettings::class);

    expect($settings->app_name)->toBe('LaraTemp')
        ->and($settings->app_description)->toContain('boilerplate Laravel')
        ->and($settings->app_copyright)->toContain('Default Institution');
});
