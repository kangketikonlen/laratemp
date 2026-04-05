<?php

namespace App\Http\Requests\Settings;

use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class UpdatePermissionRequest extends PermissionRequest
{
    public function rules(): array
    {
        /** @var Permission $permission */
        $permission = $this->route('permission');

        return [
            'name' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('permissions', 'name')->where('guard_name', $this->input('guard_name', 'web'))->ignore($permission->id)],
            'guard_name' => ['required', 'string', 'max:255'],
        ];
    }
}
