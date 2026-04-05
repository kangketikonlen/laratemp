<?php

use App\Models\Settings\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

function settingsPermissionAdmin(): User
{
    test()->seed(DatabaseSeeder::class);

    return User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
}

it('shows the permission index page and creates a custom permission', function () {
    /** @var TestCase $this */
    $admin = settingsPermissionAdmin();

    $this->actingAs($admin)
        ->get(route('settings.permissions.index'))
        ->assertOk()
        ->assertSeeText('Akses Role')
        ->assertSeeText('Daftar Akses Langsung Pengguna');

    $this->actingAs($admin)
        ->post(route('settings.permissions.store'), [
            'name' => 'export_reports',
            'guard_name' => 'web',
        ])
        ->assertRedirect(route('settings.permissions.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseHas('permissions', [
        'name' => 'export_reports',
        'guard_name' => 'web',
    ]);
});

it('updates role and user permissions, and protects system permissions', function () {
    /** @var TestCase $this */
    $admin = settingsPermissionAdmin();
    $role = Role::query()->create([
        'name' => 'report-operator',
        'guard_name' => 'web',
        'display_name' => 'Report Operator',
        'description' => 'Handles report permissions',
        'is_system' => false,
    ]);
    $permission = Permission::query()->where('name', 'view_error_logs')->firstOrFail();
    $protectedPermission = Permission::query()->where('name', 'manage settings')->firstOrFail();

    $user = User::query()->create([
        'name' => 'Scoped User',
        'email' => 'scoped-user@example.com',
        'username' => 'scoped-user',
        'password' => 'password123',
    ]);

    $this->actingAs($admin)
        ->put(route('settings.permissions.roles.update', $role), [
            'permissions' => [$permission->name],
        ])
        ->assertRedirect(route('settings.permissions.index'))
        ->assertSessionHas('status');

    $this->actingAs($admin)
        ->put(route('settings.permissions.users.update', $user), [
            'permissions' => [$permission->name],
        ])
        ->assertRedirect(route('settings.permissions.index'))
        ->assertSessionHas('status');

    expect($role->fresh()->hasDirectPermission($permission->name))->toBeTrue()
        ->and($user->fresh()->hasDirectPermission($permission->name))->toBeTrue();

    $this->actingAs($admin)
        ->put(route('settings.permissions.update', $protectedPermission), [
            'name' => 'manage-settings-updated',
            'guard_name' => 'web',
        ])
        ->assertRedirect(route('settings.permissions.index'))
        ->assertSessionHasErrors('permission');

    $this->actingAs($admin)
        ->delete(route('settings.permissions.destroy', $protectedPermission))
        ->assertRedirect(route('settings.permissions.index'))
        ->assertSessionHasErrors('permission');

    expect($protectedPermission->fresh()?->name)->toBe('manage settings');
});
