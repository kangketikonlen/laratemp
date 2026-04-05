<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstitutionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->canAny(['manage settings', 'update_institutions']) ?? false;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'website' => ['required', 'url', 'max:255'],
            'appUrl' => ['required', 'url', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:10240'],
            'background' => ['nullable', 'image', 'max:4096'],
        ];
    }
}
