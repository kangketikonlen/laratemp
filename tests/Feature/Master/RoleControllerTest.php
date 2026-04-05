<?php

use App\Models\Settings\Module;
use App\Models\Settings\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

function roleAdminUser(): User
{
    test()->seed(DatabaseSeeder::class);

    return User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
}

it('shows the role index page and its records', function () {
    /** @var TestCase $this */
    $admin = roleAdminUser();

    $this->actingAs($admin)
        ->get(route('master.roles.index'))
        ->assertOk()
        ->assertSeeText('Administrator');
});

it('creates, updates, and deletes a non-system role', function () {
    /** @var TestCase $this */
    $admin = roleAdminUser();
    $module = Module::query()->where('slug', 'general')->firstOrFail();
    $permission = Permission::query()->where('name', 'view_users')->firstOrFail();

    $this->actingAs($admin)
        ->post(route('master.roles.store'), [
            'name' => 'auditor',
            'display_name' => 'Auditor',
            'description' => '<p>Read only access</p>',
            'modules' => [$module->id],
            'permissions' => [$permission->name],
        ])
        ->assertRedirect(route('master.roles.index'))
        ->assertSessionHas('status');

    $role = Role::query()->where('name', 'auditor')->firstOrFail();

    expect($role->modules->pluck('id')->all())->toBe([$module->id])
        ->and($role->hasPermissionTo($permission->name))->toBeTrue();

    $this->actingAs($admin)
        ->put(route('master.roles.update', $role), [
            'name' => 'reviewer',
            'display_name' => 'Reviewer',
            'description' => '<script>alert(1)</script><p>Updated access</p>',
            'modules' => [],
            'permissions' => [],
        ])
        ->assertRedirect(route('master.roles.index'))
        ->assertSessionHas('status');

    $role->refresh();

    expect($role->name)->toBe('reviewer')
        ->and($role->display_name)->toBe('Reviewer')
        ->and($role->description)->not->toContain('<script>')
        ->and($role->modules()->count())->toBe(0)
        ->and($role->permissions()->count())->toBe(0);

    $this->actingAs($admin)
        ->delete(route('master.roles.destroy', $role))
        ->assertRedirect(route('master.roles.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseMissing('roles', [
        'id' => $role->id,
    ]);
});

it('does not allow system roles to be updated or deleted', function () {
    /** @var TestCase $this */
    $admin = roleAdminUser();
    $role = Role::query()->where('name', 'admin')->firstOrFail();

    $this->actingAs($admin)
        ->put(route('master.roles.update', $role), [
            'name' => 'admin-edited',
            'display_name' => 'Admin Edited',
            'modules' => [],
            'permissions' => [],
        ])
        ->assertRedirect(route('master.roles.index'))
        ->assertSessionHasErrors('role');

    $this->actingAs($admin)
        ->delete(route('master.roles.destroy', $role))
        ->assertRedirect(route('master.roles.index'))
        ->assertSessionHasErrors('role');

    expect($role->fresh()?->name)->toBe('admin');
});
