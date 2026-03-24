<?php

namespace App\Http\Requests\Master;

use App\Support\RichText\SanitizesRichText;
use Illuminate\Foundation\Http\FormRequest;

abstract class RoleRequest extends FormRequest
{
    use SanitizesRichText;

    public function authorize(): bool
    {
        return $this->user()?->can('manage settings') ?? false;
    }

    /**
     * @return array{name: string, guard_name: string, display_name?: string, description?: string}
     */
    public function validatedRoleData(): array
    {
        $data = collect($this->validated())
            ->only(['name', 'display_name', 'description'])
            ->filter(fn ($value) => ! is_null($value) && $value !== '')
            ->all();

        if (array_key_exists('description', $data)) {
            $data['description'] = $this->sanitizeRichText($data['description']);
        }

        return collect($data)
            ->filter(fn ($value) => ! is_null($value) && $value !== '')
            ->merge(['guard_name' => 'web'])
            ->all();
    }

    /**
     * @return list<int>
     */
    public function validatedModuleIds(): array
    {
        return collect($this->validated('modules', []))
            ->map(fn ($moduleId) => (int) $moduleId)
            ->values()
            ->all();
    }
}
