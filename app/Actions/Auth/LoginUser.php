<?php

namespace App\Actions\Auth;

use App\Support\ActivityLogs\LogsUserActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginUser
{
    use LogsUserActivity;

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

        $this->logUserActivity(
            activity: 'User logged in',
            category: 'security',
            status: 'success',
            user: Auth::user(),
            request: request(),
        );
    }
}
