<?php

namespace App\Http\Requests\Administration;

use Illuminate\Validation\Rule;

class StoreWorkProgressRequest extends WorkProgressRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'owner' => ['nullable', 'string', 'max:120'],
            'status' => ['required', 'string', Rule::in(['planned', 'in_progress', 'done', 'blocked'])],
            'priority' => ['required', 'string', Rule::in(['low', 'medium', 'high'])],
            'progress' => ['required', 'integer', 'between:0,100'],
            'target_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
