<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

abstract class PermissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAny([
            'manage settings',
            'create_permissions',
            'update_permissions',
        ]) ?? false;
    }

    /**
     * @return array{name: string, guard_name: string}
     */
    public function validatedPermissionData(): array
    {
        return [
            'name' => (string) $this->validated('name'),
            'guard_name' => (string) $this->validated('guard_name', 'web'),
        ];
    }
}
