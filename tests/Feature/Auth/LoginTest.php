<?php

use App\Actions\Auth\LoginUser;
use App\Livewire\Auth\Login;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;

it('redirects the root route to login', function () {
    /** @var \Tests\TestCase $this */
    $this->get('/')->assertRedirect('/login');
});

it('authenticates the seeded admin user with username and password', function () {
    /** @var \Tests\TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $username = (string) config('bootstrap_admin.username');
    $password = (string) config('bootstrap_admin.password');
    $user = User::query()->where('username', $username)->firstOrFail();

    app(LoginUser::class)->handle($username, $password);

    $this->assertAuthenticatedAs($user);
});

it('rejects invalid login credentials', function () {
    /** @var \Tests\TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $username = (string) config('bootstrap_admin.username');

    expect(fn () => app(LoginUser::class)->handle($username, 'wrong-password'))
        ->toThrow(ValidationException::class);

    $this->assertGuest();
});

it('rate limits repeated failed login attempts', function () {
    /** @var \Tests\TestCase $this */
    $this->seed(DatabaseSeeder::class);
    $username = (string) config('bootstrap_admin.username');

    $throttleKey = Str::transliterate(Str::lower($username) . '|127.0.0.1');
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
