<?php

use App\Actions\Auth\LoginUser;
use App\Models\Administration\Changelog;
use App\Models\Settings\Institution;
use App\Livewire\Auth\Login;
use App\Models\Settings\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

it('redirects the root route to login', function () {
    /** @var TestCase $this */
    $this->get('/')->assertRedirect('/login');
});

it('authenticates the seeded admin user with username and password', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $username = (string) config('bootstrap_admin.username');
    $password = (string) config('bootstrap_admin.password');
    $user = User::query()->where('username', $username)->firstOrFail();

    app(LoginUser::class)->handle($username, $password);

    $this->assertAuthenticatedAs($user);
});

it('rejects invalid login credentials', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $username = (string) config('bootstrap_admin.username');

    expect(fn () => app(LoginUser::class)->handle($username, 'wrong-password'))
        ->toThrow(ValidationException::class);

    $this->assertGuest();
});

it('rate limits repeated failed login attempts', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $username = (string) config('bootstrap_admin.username');

    $throttleKey = Str::transliterate(Str::lower($username).'|127.0.0.1');
    RateLimiter::clear($throttleKey);

    foreach (range(1, 5) as $attempt) {
        Livewire::test(Login::class)
            ->set('username', $username)
            ->set('loginPassword', 'wrong-password')
            ->call('login')
            ->assertHasErrors('username');
    }

    Livewire::test(Login::class)
        ->set('username', $username)
        ->set('loginPassword', 'wrong-password')
        ->call('login')
        ->assertHasErrors([
            'username' => fn (array $rules, array $messages): bool => collect($messages)
                ->contains(fn (string $message): bool => str_contains($message, 'Terlalu banyak percobaan login.')),
        ]);

    RateLimiter::clear($throttleKey);
});

it('shows the assigned module list on the dashboard', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $user = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSeeText('Available Modules')
        ->assertSeeText('General Settings')
        ->assertSeeText('general')
        ->assertSeeText('Default Institution')
        ->assertSeeText('Catatan Pembaruan')
        ->assertSeeText('v1.2.0')
        ->assertSeeText('Role and User Access Manager')
        ->assertDontSeeText('Back to dashboard');
});

it('allows the admin to open the general settings module page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $user = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $this->actingAs($user)
        ->get(route('general'))
        ->assertOk()
        ->assertSeeText('Selamat datang di dashboard General Settings')
        ->assertSeeText('General Settings')
        ->assertSeeText('Section Utama')
        ->assertSeeText('Master')
        ->assertSeeText('Settings')
        ->assertSeeText('Administration')
        ->assertSeeText('Report');
});

it('shows the module navbar for authenticated users', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $user = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $this->actingAs($user)
        ->get(route('master.index'))
        ->assertOk()
        ->assertSeeText('Dashboard')
        ->assertSeeText('Master')
        ->assertSeeText('Settings')
        ->assertSeeText('Administration')
        ->assertSeeText('Report')
        ->assertSeeText('Section Dashboard')
        ->assertSeeText('Back to module')
        ->assertSeeText('User')
        ->assertSeeText('Role')
        ->assertDontSeeText('Institution')
        ->assertDontSeeText('Permission');
});

it('shows the master sub navigation items when opening a master child page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $user = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $this->actingAs($user)
        ->get(route('master.users.index'))
        ->assertOk()
        ->assertSeeText('Master')
        ->assertSeeText('User')
        ->assertSeeText('Role')
        ->assertSeeText('Master Data Management')
        ->assertSeeText('Daftar User')
        ->assertSeeText('Add User');
});

it('shows the master roles page for the admin', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $user = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $this->actingAs($user)
        ->get(route('master.roles.index'))
        ->assertOk()
        ->assertSeeText('Master')
        ->assertSeeText('User')
        ->assertSeeText('Role')
        ->assertSeeText('Daftar Role')
        ->assertSeeText('Add Role')
        ->assertSeeText('Administrator');
});

it('shows the settings sub navigation items when opening a settings child page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $user = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $this->actingAs($user)
        ->get(route('settings.permissions.index'))
        ->assertOk()
        ->assertSeeText('Settings')
        ->assertSeeText('Institution')
        ->assertSeeText('Permission')
        ->assertSeeText('Role Access')
        ->assertSeeText('Start With Roles, Then Manage Their Access')
        ->assertSeeText('Administrator');
});

it('allows the admin to update institution branding from the settings page', function () {
    /** @var TestCase $this */
    Storage::fake('public');
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $logo = UploadedFile::fake()->image('logo.png', 300, 300);
    $background = UploadedFile::fake()->image('background.png', 1600, 900);

    $this->actingAs($admin)
        ->put(route('settings.institutions.update'), [
            'name' => 'PT Example Baru',
            'address' => 'Jl. Baru No. 77, Jakarta',
            'email' => 'branding@example.test',
            'website' => 'https://example.test',
            'appUrl' => 'https://app.example.test',
            'contact' => '+62 811 1111 1111',
            'logo' => $logo,
            'background' => $background,
        ])
        ->assertRedirect(route('settings.institutions.index'));

    $institution = Institution::query()->firstOrFail();

    expect($institution->name)->toBe('PT Example Baru')
        ->and($institution->email)->toBe('branding@example.test')
        ->and($institution->logo)->not->toBeNull()
        ->and($institution->background)->not->toBeNull();

    Storage::disk('public')->assertExists($institution->logo);
    Storage::disk('public')->assertExists($institution->background);

    $this->post(route('logout'));

    $this->get(route('login'))
        ->assertOk()
        ->assertSeeText('PT Example Baru')
        ->assertSee(Storage::disk('public')->url($institution->logo), false)
        ->assertSee(Storage::disk('public')->url($institution->background), false);
});

it('allows the admin to assign CRUD permissions to a role from the master roles page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
    $module = \App\Models\Settings\Module::query()->where('slug', 'general')->firstOrFail();

    $this->actingAs($admin)
        ->post(route('master.roles.store'), [
            'name' => 'operator_support',
            'display_name' => 'Operator Support',
            'description' => 'Support role for day-to-day operations.',
            'modules' => [$module->id],
            'permissions' => ['view_users', 'create_users'],
        ])
        ->assertRedirect(route('master.roles.index'));

    $createdRole = Role::query()->where('name', 'operator_support')->firstOrFail();

    expect($createdRole->hasPermissionTo('view_users'))->toBeTrue()
        ->and($createdRole->hasPermissionTo('create_users'))->toBeTrue();
});

it('allows the admin to assign direct CRUD permissions to a user from the master users page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $this->actingAs($admin)
        ->post(route('master.users.store'), [
            'name' => 'Jane Operator',
            'username' => 'jane_operator',
            'email' => 'jane@example.com',
            'password' => 'secret-pass-1',
            'password_confirmation' => 'secret-pass-1',
            'roles' => [],
            'permissions' => ['view_permissions', 'create_permissions'],
        ])
        ->assertRedirect(route('master.users.index'));

    $createdUser = User::query()->where('username', 'jane_operator')->firstOrFail();

    expect($createdUser->hasDirectPermission('view_permissions'))->toBeTrue()
        ->and($createdUser->hasDirectPermission('create_permissions'))->toBeTrue();
});

it('allows the admin to create a permission from the settings page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $this->actingAs($admin)
        ->post(route('settings.permissions.store'), [
            'name' => 'manage_reports',
            'guard_name' => 'web',
        ])
        ->assertRedirect(route('settings.permissions.index'));

    $createdPermission = Permission::query()->where('name', 'manage_reports')->firstOrFail();

    expect($createdPermission->guard_name)->toBe('web');
});

it('allows the admin to update a custom permission from the settings page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
    $permission = Permission::query()->create([
        'name' => 'manage_reports',
        'guard_name' => 'web',
    ]);

    $this->actingAs($admin)
        ->put(route('settings.permissions.update', $permission), [
            'name' => 'manage_audit_reports',
            'guard_name' => 'web',
        ])
        ->assertRedirect(route('settings.permissions.index'));

    $permission->refresh();

    expect($permission->name)->toBe('manage_audit_reports');
});

it('prevents the admin from updating a system permission', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
    $permission = Permission::query()->where('name', 'manage settings')->firstOrFail();

    $this->actingAs($admin)
        ->put(route('settings.permissions.update', $permission), [
            'name' => 'manage_platform_settings',
            'guard_name' => 'web',
        ])
        ->assertRedirect(route('settings.permissions.index'))
        ->assertSessionHasErrors('permission');

    $permission->refresh();

    expect($permission->name)->toBe('manage settings');
});

it('allows the admin to delete a custom permission from the settings page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
    $permission = Permission::query()->create([
        'name' => 'manage_reports',
        'guard_name' => 'web',
    ]);

    $this->actingAs($admin)
        ->delete(route('settings.permissions.destroy', $permission))
        ->assertRedirect(route('settings.permissions.index'));

    $this->assertDatabaseMissing('permissions', [
        'id' => $permission->id,
    ]);
});

it('prevents the admin from deleting a system permission', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
    $permission = Permission::query()->where('name', 'manage settings')->firstOrFail();

    $this->actingAs($admin)
        ->delete(route('settings.permissions.destroy', $permission))
        ->assertRedirect(route('settings.permissions.index'))
        ->assertSessionHasErrors('permission');

    $this->assertDatabaseHas('permissions', [
        'id' => $permission->id,
    ]);
});

it('allows a user with only view permission access to open permission index but not create permission', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $user = User::query()->create([
        'name' => 'Viewer User',
        'username' => 'viewer_user',
        'email' => 'viewer@example.com',
        'password' => 'secret-pass-1',
    ]);

    $user->givePermissionTo('view_permissions');

    $this->actingAs($user)
        ->get(route('settings.permissions.index'))
        ->assertOk()
        ->assertSeeText('Role Access Directory')
        ->assertSeeText('Direct User Access Directory')
        ->assertDontSeeText('Add Custom Access');

    $this->actingAs($user)
        ->get(route('settings.permissions.create'))
        ->assertForbidden();
});

it('allows a user with update permission access to open the role access checklist page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $user = User::query()->create([
        'name' => 'Access Manager',
        'username' => 'access_manager',
        'email' => 'access-manager@example.com',
        'password' => 'secret-pass-1',
    ]);
    $role = Role::query()->where('name', 'admin')->firstOrFail();

    $user->givePermissionTo(['view_permissions', 'update_permissions']);

    $this->actingAs($user)
        ->get(route('settings.permissions.roles.edit', $role))
        ->assertOk()
        ->assertSeeText('Manage Role Access')
        ->assertSeeText('Administrator')
        ->assertSeeText('Access Checklist');
});

it('allows a user with update permission access to open the user direct access checklist page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $manager = User::query()->create([
        'name' => 'Access Manager',
        'username' => 'access_manager_two',
        'email' => 'access-manager-two@example.com',
        'password' => 'secret-pass-1',
    ]);
    $managedUser = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $manager->givePermissionTo(['view_permissions', 'update_permissions']);

    $this->actingAs($manager)
        ->get(route('settings.permissions.users.edit', $managedUser))
        ->assertOk()
        ->assertSeeText('Manage User Access')
        ->assertSeeText('Administrator')
        ->assertSeeText('Direct Access Checklist');
});

it('shows the administration sub navigation items when opening an administration child page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $user = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $this->actingAs($user)
        ->get(route('administration.changelogs.index'))
        ->assertOk()
        ->assertSeeText('Administration')
        ->assertSeeText('Changelogs')
        ->assertSeeText('Work Progress')
        ->assertSeeText('Changelog Timeline')
        ->assertSeeText('Release Notes');
});

it('allows the admin to create a changelog from the administration section', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $this->actingAs($admin)
        ->post(route('administration.changelogs.store'), [
            'title' => 'Role access manager improvements',
            'status' => 'published',
            'released_at' => '2026-04-07',
            'notes' => '<p>Added access checklists for roles and users.</p>',
        ])
        ->assertRedirect(route('administration.changelogs.index'));

    $changelog = Changelog::query()->where('version', 'v2026.04.07')->firstOrFail();

    expect($changelog->title)->toBe('Role access manager improvements')
        ->and($changelog->status)->toBe('published');
});

it('allows the admin to update a changelog from the administration section', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
    $changelog = Changelog::query()->create([
        'version' => 'v1.2.4',
        'title' => 'Old title',
        'status' => 'draft',
    ]);

    $this->actingAs($admin)
        ->put(route('administration.changelogs.update', $changelog), [
            'version' => 'v1.2.4',
            'title' => 'Updated title',
            'status' => 'published',
            'released_at' => '2026-04-06',
            'notes' => '<p>Updated notes.</p>',
        ])
        ->assertRedirect(route('administration.changelogs.index'));

    $changelog->refresh();

    expect($changelog->title)->toBe('Updated title')
        ->and($changelog->status)->toBe('published');
});

it('allows the admin to delete a changelog from the administration section', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
    $changelog = Changelog::query()->create([
        'version' => 'v1.2.0',
        'title' => 'Delete me',
        'status' => 'draft',
    ]);

    $this->actingAs($admin)
        ->delete(route('administration.changelogs.destroy', $changelog))
        ->assertRedirect(route('administration.changelogs.index'));

    $this->assertDatabaseMissing('changelogs', [
        'id' => $changelog->id,
    ]);
});

it('shows the report sub navigation items when opening a report child page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $user = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $this->actingAs($user)
        ->get(route('report.activity-log.index'))
        ->assertOk()
        ->assertSeeText('Report')
        ->assertSeeText('Activity Log')
        ->assertSeeText('Error Report')
        ->assertSeeText('Section Dashboard');
});

it('allows the admin to create a user from the master users page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
    $role = Role::query()->where('name', 'admin')->firstOrFail();

    $this->actingAs($admin)
        ->post(route('master.users.store'), [
            'name' => 'Jane Operator',
            'username' => 'jane_operator',
            'email' => 'jane@example.com',
            'password' => 'secret-pass-1',
            'password_confirmation' => 'secret-pass-1',
            'roles' => [$role->name],
        ])
        ->assertRedirect(route('master.users.index'));

    $createdUser = User::query()->where('username', 'jane_operator')->firstOrFail();

    expect($createdUser->name)->toBe('Jane Operator')
        ->and($createdUser->email)->toBe('jane@example.com')
        ->and($createdUser->hasRole($role))->toBeTrue();
});

it('allows the admin to update a user from the master users page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
    $managedUser = User::query()->create([
        'name' => 'Old Name',
        'username' => 'old_name',
        'email' => 'old@example.com',
        'password' => 'secret-pass-1',
    ]);

    $this->actingAs($admin)
        ->put(route('master.users.update', $managedUser), [
            'name' => 'New Name',
            'username' => 'new_name',
            'email' => 'new@example.com',
            'password' => '',
            'password_confirmation' => '',
            'roles' => [],
        ])
        ->assertRedirect(route('master.users.index'));

    $managedUser->refresh();

    expect($managedUser->name)->toBe('New Name')
        ->and($managedUser->username)->toBe('new_name')
        ->and($managedUser->email)->toBe('new@example.com');
});

it('allows the admin to delete another user from the master users page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
    $managedUser = User::query()->create([
        'name' => 'Delete Me',
        'username' => 'delete_me',
        'email' => 'delete@example.com',
        'password' => 'secret-pass-1',
    ]);

    $this->actingAs($admin)
        ->delete(route('master.users.destroy', $managedUser))
        ->assertRedirect(route('master.users.index'));

    $this->assertDatabaseMissing('users', [
        'id' => $managedUser->id,
    ]);
});

it('prevents the admin from deleting the currently authenticated user', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $this->actingAs($admin)
        ->delete(route('master.users.destroy', $admin))
        ->assertRedirect(route('master.users.index'))
        ->assertSessionHasErrors('user');

    $this->assertDatabaseHas('users', [
        'id' => $admin->id,
    ]);
});

it('allows the admin to create a role from the master roles page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
    $module = \App\Models\Settings\Module::query()->where('slug', 'general')->firstOrFail();

    $this->actingAs($admin)
        ->post(route('master.roles.store'), [
            'name' => 'operator_support',
            'display_name' => 'Operator Support',
            'description' => 'Support role for day-to-day operations.',
            'modules' => [$module->id],
        ])
        ->assertRedirect(route('master.roles.index'));

    $createdRole = Role::query()->where('name', 'operator_support')->firstOrFail();

    expect($createdRole->display_name)->toBe('Operator Support')
        ->and($createdRole->guard_name)->toBe('web')
        ->and($createdRole->description)->toBe('Support role for day-to-day operations.')
        ->and($createdRole->modules()->pluck('modules.id')->all())->toBe([$module->id]);
});

it('allows the admin to update a non-system role from the master roles page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
    $role = Role::query()->create([
        'name' => 'operator_support',
        'guard_name' => 'web',
        'display_name' => 'Operator Support',
        'description' => 'Old description',
        'is_system' => false,
    ]);

    $this->actingAs($admin)
        ->put(route('master.roles.update', $role), [
            'name' => 'operator_lead',
            'display_name' => 'Operator Lead',
            'description' => 'Updated description',
            'modules' => [],
        ])
        ->assertRedirect(route('master.roles.index'));

    $role->refresh();

    expect($role->name)->toBe('operator_lead')
        ->and($role->display_name)->toBe('Operator Lead')
        ->and($role->description)->toBe('Updated description');
});

it('prevents the admin from updating a system role', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
    $role = Role::query()->where('name', 'admin')->firstOrFail();

    $this->actingAs($admin)
        ->put(route('master.roles.update', $role), [
            'name' => 'renamed_admin',
            'display_name' => 'Renamed Admin',
            'description' => 'Should not change',
            'modules' => [],
        ])
        ->assertRedirect(route('master.roles.index'))
        ->assertSessionHasErrors('role');

    $role->refresh();

    expect($role->name)->toBe('admin')
        ->and($role->display_name)->toBe('Administrator');
});

it('allows the admin to delete a non-system role from the master roles page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
    $role = Role::query()->create([
        'name' => 'temporary_role',
        'guard_name' => 'web',
        'display_name' => 'Temporary Role',
        'description' => 'Delete me',
        'is_system' => false,
    ]);

    $this->actingAs($admin)
        ->delete(route('master.roles.destroy', $role))
        ->assertRedirect(route('master.roles.index'));

    $this->assertDatabaseMissing('roles', [
        'id' => $role->id,
    ]);
});

it('prevents the admin from deleting a system role', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $admin = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
    $role = Role::query()->where('name', 'admin')->firstOrFail();

    $this->actingAs($admin)
        ->delete(route('master.roles.destroy', $role))
        ->assertRedirect(route('master.roles.index'))
        ->assertSessionHasErrors('role');

    $this->assertDatabaseHas('roles', [
        'id' => $role->id,
        'name' => 'admin',
    ]);
});
