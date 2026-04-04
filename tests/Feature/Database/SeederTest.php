<?php

use App\Models\Settings\Module;
use App\Models\Settings\NavigationItem;
use App\Models\Settings\Role;
use App\Models\Settings\Institution;
use App\Models\User;
use App\Settings\GeneralSettings;
use Database\Seeders\DatabaseSeeder;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

it('seeds the default auth and settings data', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);

    $admin = User::query()->where('username', config('bootstrap_admin.username'))->first();
    $role = Role::query()->where('name', 'admin')->first();
    $institution = Institution::query()->first();
    $modules = Module::query()
        ->orderBy('sort_order')
        ->orderBy('name')
        ->pluck('slug')
        ->all();
    $settingsNavbar = NavigationItem::query()
        ->whereRelation('module', 'slug', 'general')
        ->whereNull('parent_id')
        ->orderBy('sort_order')
        ->orderBy('name')
        ->pluck('slug')
        ->all();
    $masterSubnavbar = NavigationItem::query()
        ->whereRelation('parent', 'slug', 'general.master')
        ->orderBy('sort_order')
        ->orderBy('name')
        ->pluck('slug')
        ->all();
    $settingsSubnavbar = NavigationItem::query()
        ->whereRelation('parent', 'slug', 'general.settings')
        ->orderBy('sort_order')
        ->orderBy('name')
        ->pluck('slug')
        ->all();
    $administrationSubnavbar = NavigationItem::query()
        ->whereRelation('parent', 'slug', 'general.administration')
        ->orderBy('sort_order')
        ->orderBy('name')
        ->pluck('slug')
        ->all();
    $reportSubnavbar = NavigationItem::query()
        ->whereRelation('parent', 'slug', 'general.report')
        ->orderBy('sort_order')
        ->orderBy('name')
        ->pluck('slug')
        ->all();
    expect($admin)->not->toBeNull()
        ->and($role)->not->toBeNull()
        ->and($institution)->not->toBeNull()
        ->and($institution->address)->not->toBeEmpty()
        ->and($role->display_name)->toBe('Administrator')
        ->and($role->is_system)->toBeTrue()
        ->and($modules)->toBe(['general'])
        ->and($settingsNavbar)->toBe([
            'general.master',
            'general.settings',
            'general.administration',
            'general.report',
        ])
        ->and($masterSubnavbar)->toBe([
            'general.master.users',
            'general.master.roles',
        ])
        ->and($settingsSubnavbar)->toBe([
            'general.settings.institution',
            'general.settings.permissions',
        ])
        ->and($administrationSubnavbar)->toBe([
            'general.administration.changelogs',
            'general.administration.work-progress',
        ])
        ->and($reportSubnavbar)->toBe([
            'general.report.activity-log',
            'general.report.error-report',
        ]);

    expect($admin->hasRole('admin'))->toBeTrue();
    expect($role->modules()->orderBy('sort_order')->pluck('slug')->all())
        ->toBe(['general']);

    expect(Permission::query()->pluck('name')->sort()->values()->all())
        ->toBe(['manage settings']);
});

it('seeds the general settings payload', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);

    $settings = app(GeneralSettings::class);

    expect($settings->app_name)->toBe('LaraTemp')
        ->and($settings->app_description)->toContain('boilerplate Laravel')
        ->and($settings->app_copyright)->toContain('Default Institution');
});
