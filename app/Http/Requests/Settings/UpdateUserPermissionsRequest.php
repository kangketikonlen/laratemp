<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserPermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAny([
            'manage settings',
            'update_users',
            'update_permissions',
        ]) ?? false;
    }

    public function rules(): array
    {
        return [
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::exists('permissions', 'name')->where('guard_name', 'web')],
        ];
    }

    /**
     * @return list<string>
     */
    public function validatedPermissionNames(): array
    {
        return collect($this->validated('permissions', []))
            ->map(fn ($permission) => (string) $permission)
            ->values()
            ->all();
    }
}
