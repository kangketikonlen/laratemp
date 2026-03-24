<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

abstract class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage settings') ?? false;
    }

    /**
     * @return array{name: string, email: string, username: string, password?: string}
     */
    public function validatedUserData(): array
    {
        return collect($this->validated())
            ->only(['name', 'email', 'username', 'password'])
            ->filter(fn ($value) => ! is_null($value) && $value !== '')
            ->all();
    }

    /**
     * @return list<string>
     */
    public function validatedRoleNames(): array
    {
        return collect($this->validated('roles', []))
            ->map(fn ($role) => (string) $role)
            ->values()
            ->all();
    }
}
