<?php

namespace App\Http\Requests\Administration;

use Illuminate\Validation\Rule;

class StoreChangelogRequest extends ChangelogRequest
{
    public function rules(): array
    {
        return [
            'version' => ['nullable', 'string', 'max:50', 'unique:changelogs,version'],
            'title' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in(['draft', 'published', 'archived'])],
            'released_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
