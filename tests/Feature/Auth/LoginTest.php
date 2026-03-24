<?php

use App\Actions\Auth\LoginUser;
use App\Livewire\Auth\Login;
use App\Models\Settings\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;
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
        ->assertSeeText('settings.general')
        ->assertSeeText('Default Institution')
        ->assertSeeText('Catatan Pembaruan')
        ->assertDontSeeText('Back to dashboard');
});

it('allows the admin to open the general settings module page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $user = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $this->actingAs($user)
        ->get(route('settings.general'))
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

it('shows the settings sub navigation items when opening a settings child page', function () {
    /** @var TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $user = User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();

    $this->actingAs($user)
        ->get(route('settings.institutions.index'))
        ->assertOk()
        ->assertSeeText('Settings')
        ->assertSeeText('Institution')
        ->assertSeeText('Permission')
        ->assertSeeText('Section Dashboard');
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
        ->assertSeeText('Section Dashboard');
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
