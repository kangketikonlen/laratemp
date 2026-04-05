<?php

namespace App\Http\Requests\Administration;

use App\Models\Administration\Changelog;
use App\Support\RichText\SanitizesRichText;
use Illuminate\Foundation\Http\FormRequest;

abstract class ChangelogRequest extends FormRequest
{
    use SanitizesRichText;

    public function authorize(): bool
    {
        return $this->user()?->canAny([
            'manage settings',
            'create_changelogs',
            'update_changelogs',
        ]) ?? false;
    }

    protected function prepareForValidation(): void
    {
        if (filled($this->input('version'))) {
            return;
        }

        $this->merge([
            'version' => Changelog::defaultVersion($this->input('released_at')),
        ]);
    }

    /**
     * @return array{
     *     version: string,
     *     title: string,
     *     status: string,
     *     released_at?: string,
     *     notes?: string|null
     * }
     */
    public function validatedChangelogData(): array
    {
        $data = collect($this->validated())
            ->only(['version', 'title', 'status', 'released_at', 'notes'])
            ->filter(fn ($value) => ! is_null($value) && $value !== '')
            ->all();

        if (array_key_exists('notes', $data)) {
            $data['notes'] = $this->sanitizeRichText($data['notes']);
        }

        return $data;
    }
}
