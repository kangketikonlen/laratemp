<?php

namespace App\Livewire\Auth;

use App\Actions\Auth\LoginUser;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    protected int $maxLoginAttempts = 5;

    public string $username = '';
    public string $loginPassword = '';
    public string $copyright = '';

    public function mount(string $copyright = ''): void
    {
        $this->copyright = $copyright;
    }

    public function login()
    {
        $this->username = trim($this->username);
        $this->ensureIsNotRateLimited();

        $credentials = $this->validate([
            'username' => ['required', 'string'],
            'loginPassword' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'loginPassword.required' => 'Password wajib diisi.',
        ]);

        try {
            app(LoginUser::class)->handle(
                username: $credentials['username'],
                password: $credentials['loginPassword'],
            );
        } catch (ValidationException $exception) {
            RateLimiter::hit($this->throttleKey(), 60);

            throw $exception;
        }

        RateLimiter::clear($this->throttleKey());

        $this->skipRender();

        return redirect()->route('dashboard');
    }

    public function render(): View
    {
        return view('livewire.auth.login');
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), $this->maxLoginAttempts)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->username) . '|' . request()->ip());
    }
}
