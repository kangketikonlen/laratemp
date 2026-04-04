<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
    protected $table = 'work_progress';

    public function previewText(int $limit = 140): ?string
    {
        $source = trim(strip_tags((string) $this->notes));

        if ($source === '') {
            return null;
        }

        return Str::limit($source, $limit);
    }

    protected function casts(): array
    {
        return [
            'target_date' => 'date',
            'progress' => 'integer',
        ];
    }
}
