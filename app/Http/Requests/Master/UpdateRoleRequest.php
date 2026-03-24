<?php

namespace App\Http\Requests\Master;

use App\Models\Settings\Role;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends RoleRequest
{
    public function rules(): array
    {
        /** @var Role $role */
        $role = $this->route('role');

        return [
            'name' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('roles', 'name')->where('guard_name', 'web')->ignore($role->id)],
            'display_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'modules' => ['nullable', 'array'],
            'modules.*' => ['integer', 'distinct', 'exists:modules,id'],
        ];
    }
}
