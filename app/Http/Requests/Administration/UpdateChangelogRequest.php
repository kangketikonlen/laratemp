<?php

namespace App\Http\Requests\Administration;

use App\Models\Administration\Changelog;
use Illuminate\Validation\Rule;

class UpdateChangelogRequest extends ChangelogRequest
{
    public function rules(): array
    {
        /** @var Changelog $changelog */
        $changelog = $this->route('changelog');

        return [
            'version' => ['nullable', 'string', 'max:50', Rule::unique('changelogs', 'version')->ignore($changelog->id)],
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in(['draft', 'published', 'archived'])],
            'released_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
