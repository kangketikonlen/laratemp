<?php

namespace App\Http\Requests\Master;

use Illuminate\Validation\Rule;

class StoreRoleRequest extends RoleRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('roles', 'name')->where('guard_name', 'web')],
            'display_name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'modules' => ['nullable', 'array'],
            'modules.*' => ['integer', 'distinct', 'exists:modules,id'],
        ];
    }
}
