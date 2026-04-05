<?php

namespace App\Http\Requests\Administration;

use App\Support\RichText\SanitizesRichText;
use Illuminate\Foundation\Http\FormRequest;

abstract class WorkProgressRequest extends FormRequest
{
    use SanitizesRichText;

    public function authorize(): bool
    {
        return $this->user()?->canAny([
            'manage settings',
            'create_work_progress',
            'update_work_progress',
        ]) ?? false;
    }

    /**
     * @return array{
     *     title: string,
     *     status: string,
     *     priority: string,
     *     progress: int,
     *     owner?: string,
     *     target_date?: string,
     *     notes?: string|null
     * }
     */
    public function validatedWorkProgressData(): array
    {
        $data = collect($this->validated())
            ->only(['title', 'owner', 'status', 'priority', 'progress', 'target_date', 'notes'])
            ->filter(fn ($value) => ! is_null($value) && $value !== '')
            ->all();

        if (array_key_exists('notes', $data)) {
            $data['notes'] = $this->sanitizeRichText($data['notes']);
        }

        return $data;
    }
}
