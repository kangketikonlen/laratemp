<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginUser
{
    public function handle(string $username, string $password): void
    {
        if (! Auth::attempt([
            'username' => $username,
            'password' => $password,
        ])) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password tidak sesuai.',
            ]);
        }

        session()->regenerate();
    }
}
