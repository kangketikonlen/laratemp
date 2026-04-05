<?php

namespace Database\Seeders\Auth;

use App\Models\Settings\Module;
use App\Models\Settings\NavigationItem;
use App\Models\Settings\Role;
use App\Support\Permissions\PermissionCatalog;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = collect([
            'manage settings',
            ...PermissionCatalog::names(),
        ])->unique()->map(fn (string $permission) => Permission::firstOrCreate([
            'name' => $permission,
            'guard_name' => 'web',
        ]));

        $adminRole = Role::query()->updateOrCreate(
            ['name' => 'admin', 'guard_name' => 'web'],
            [
                'display_name' => 'Administrator',
                'description' => 'Full system access, including user and settings management.',
                'is_system' => true,
            ],
        );

        Module::query()->whereIn('slug', ['master', 'settings', 'administration', 'report'])->delete();

        $generalSettingsModule = Module::query()->updateOrCreate(
            ['slug' => 'general'],
            [
                'name' => 'General Settings',
                'description' => 'Manage application-wide general settings and navigation access.',
                'icon' => 'settings',
                'route_name' => 'general',
                'sort_order' => 10,
                'is_active' => true,
            ],
        );

        $masterNavigation = NavigationItem::query()->updateOrCreate(
            ['slug' => 'general.master'],
            [
                'module_id' => $generalSettingsModule->id,
                'parent_id' => null,
                'name' => 'Master',
                'description' => 'Master data and foundational application resources.',
                'route_name' => 'master.index',
                'sort_order' => 10,
                'is_active' => true,
            ],
        );

        NavigationItem::query()->updateOrCreate(
            ['slug' => 'general.master.users'],
            [
                'module_id' => $generalSettingsModule->id,
                'parent_id' => $masterNavigation->id,
                'name' => 'User',
                'description' => 'Manage users inside the master section.',
                'route_name' => 'master.users.index',
                'sort_order' => 10,
                'is_active' => true,
            ],
        );

        NavigationItem::query()->updateOrCreate(
            ['slug' => 'general.master.roles'],
            [
                'module_id' => $generalSettingsModule->id,
                'parent_id' => $masterNavigation->id,
                'name' => 'Role',
                'description' => 'Manage roles inside the master section.',
                'route_name' => 'master.roles.index',
                'sort_order' => 20,
                'is_active' => true,
            ],
        );

        $settingsNavigation = NavigationItem::query()->updateOrCreate(
            ['slug' => 'general.settings'],
            [
                'module_id' => $generalSettingsModule->id,
                'parent_id' => null,
                'name' => 'Settings',
                'description' => 'Configuration and application setup sections.',
                'route_name' => 'settings.index',
                'sort_order' => 20,
                'is_active' => true,
            ],
        );

        NavigationItem::query()->updateOrCreate(
            ['slug' => 'general.settings.institution'],
            [
                'module_id' => $generalSettingsModule->id,
                'parent_id' => $settingsNavigation->id,
                'name' => 'Institution',
                'description' => 'Manage institution settings inside the settings section.',
                'route_name' => 'settings.institutions.index',
                'sort_order' => 10,
                'is_active' => true,
            ],
        );

        NavigationItem::query()->updateOrCreate(
            ['slug' => 'general.settings.permissions'],
            [
                'module_id' => $generalSettingsModule->id,
                'parent_id' => $settingsNavigation->id,
                'name' => 'Permission',
                'description' => 'Manage permission settings inside the settings section.',
                'route_name' => 'settings.permissions.index',
                'sort_order' => 20,
                'is_active' => true,
            ],
        );

        $administrationNavigation = NavigationItem::query()->updateOrCreate(
            ['slug' => 'general.administration'],
            [
                'module_id' => $generalSettingsModule->id,
                'parent_id' => null,
                'name' => 'Administration',
                'description' => 'Administrative tools and operational controls.',
                'route_name' => 'administration.index',
                'sort_order' => 30,
                'is_active' => true,
            ],
        );

        NavigationItem::query()->updateOrCreate(
            ['slug' => 'general.administration.changelogs'],
            [
                'module_id' => $generalSettingsModule->id,
                'parent_id' => $administrationNavigation->id,
                'name' => 'Changelogs',
                'description' => 'Track application changelog records.',
                'route_name' => 'administration.changelogs.index',
                'sort_order' => 10,
                'is_active' => true,
            ],
        );

        NavigationItem::query()->updateOrCreate(
            ['slug' => 'general.administration.work-progress'],
            [
                'module_id' => $generalSettingsModule->id,
                'parent_id' => $administrationNavigation->id,
                'name' => 'Work Progress',
                'description' => 'Monitor work progress records.',
                'route_name' => 'administration.work-progress.index',
                'sort_order' => 20,
                'is_active' => true,
            ],
        );

        $reportNavigation = NavigationItem::query()->updateOrCreate(
            ['slug' => 'general.report'],
            [
                'module_id' => $generalSettingsModule->id,
                'parent_id' => null,
                'name' => 'Report',
                'description' => 'Reporting modules and summaries.',
                'route_name' => 'report.index',
                'sort_order' => 40,
                'is_active' => true,
            ],
        );

        NavigationItem::query()->updateOrCreate(
            ['slug' => 'general.report.activity-log'],
            [
                'module_id' => $generalSettingsModule->id,
                'parent_id' => $reportNavigation->id,
                'name' => 'Activity Log',
                'description' => 'Review activity log entries.',
                'route_name' => 'report.activity-log.index',
                'sort_order' => 10,
                'is_active' => true,
            ],
        );

        NavigationItem::query()->updateOrCreate(
            ['slug' => 'general.report.error-report'],
            [
                'module_id' => $generalSettingsModule->id,
                'parent_id' => $reportNavigation->id,
                'name' => 'Error Report',
                'description' => 'Review system error reports.',
                'route_name' => 'report.error-report.index',
                'sort_order' => 20,
                'is_active' => true,
            ],
        );

        $adminRole->syncPermissions($permissions);
        $adminRole->modules()->sync([$generalSettingsModule->id]);
    }
}
