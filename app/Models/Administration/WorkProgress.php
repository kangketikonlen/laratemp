<?php

namespace App\Models\Administration;

use App\Models\Concerns\HasPreviewText;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'owner',
    'status',
    'priority',
    'progress',
    'target_date',
    'notes',
    'created_by',
    'updated_by',
])]
class WorkProgress extends Model
{
    use HasPreviewText;

    protected $table = 'work_progress';

    protected function previewSourceText(): string
    {
        return (string) $this->notes;
    }

    protected function casts(): array
    {
        return [
            'target_date' => 'date',
            'progress' => 'integer',
        ];
    }
}
