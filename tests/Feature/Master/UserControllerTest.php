<?php

use App\Models\Settings\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

function masterAdminUser(): User
{
    test()->seed(DatabaseSeeder::class);

    return User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
}

it('shows the user index page and its records', function () {
    /** @var TestCase $this */
    $admin = masterAdminUser();
    $user = User::query()->create([
        'name' => 'Editor User',
        'email' => 'editor@example.com',
        'username' => 'editor-user',
        'password' => 'password123',
    ]);
    $user->assignRole('admin');

    $this->actingAs($admin)
        ->get(route('master.users.index'))
        ->assertOk()
        ->assertSeeText('Editor User')
        ->assertSeeText('editor@example.com')
        ->assertSeeText('editor-user');
});

it('creates, updates, and deletes a user account', function () {
    /** @var TestCase $this */
    $admin = masterAdminUser();
    $role = Role::query()->where('name', 'admin')->firstOrFail();
    $permission = Permission::query()->where('name', 'view_roles')->firstOrFail();

    $this->actingAs($admin)
        ->post(route('master.users.store'), [
            'name' => 'Feature User',
            'email' => 'feature-user@example.com',
            'username' => 'feature-user',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'roles' => [$role->name],
            'permissions' => [$permission->name],
        ])
        ->assertRedirect(route('master.users.index'))
        ->assertSessionHas('status');

    $user = User::query()->where('username', 'feature-user')->firstOrFail();

    expect($user->hasRole($role))->toBeTrue()
        ->and($user->hasDirectPermission($permission->name))->toBeTrue();

    $this->actingAs($admin)
        ->put(route('master.users.update', $user), [
            'name' => 'Feature User Updated',
            'email' => 'feature-user-updated@example.com',
            'username' => 'feature-user-updated',
            'roles' => [],
            'permissions' => [],
        ])
        ->assertRedirect(route('master.users.index'))
        ->assertSessionHas('status');

    $user->refresh();

    expect($user->name)->toBe('Feature User Updated')
        ->and($user->email)->toBe('feature-user-updated@example.com')
        ->and($user->username)->toBe('feature-user-updated')
        ->and($user->roles()->count())->toBe(0)
        ->and($user->permissions()->count())->toBe(0);

    $this->actingAs($admin)
        ->delete(route('master.users.destroy', $user))
        ->assertRedirect(route('master.users.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseMissing('users', [
        'id' => $user->id,
    ]);
});

it('does not allow the active user to delete their own account', function () {
    /** @var TestCase $this */
    $admin = masterAdminUser();

    $this->actingAs($admin)
        ->delete(route('master.users.destroy', $admin))
        ->assertRedirect(route('master.users.index'))
        ->assertSessionHasErrors('user');

    expect(User::query()->whereKey($admin->id)->exists())->toBeTrue();
});
