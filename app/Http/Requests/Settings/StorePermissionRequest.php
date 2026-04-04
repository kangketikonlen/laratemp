<?php

namespace App\Http\Requests\Settings;

use Illuminate\Validation\Rule;

class StorePermissionRequest extends PermissionRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('permissions', 'name')->where('guard_name', $this->input('guard_name', 'web'))],
            'guard_name' => ['required', 'string', 'max:255'],
        ];
    }
}
