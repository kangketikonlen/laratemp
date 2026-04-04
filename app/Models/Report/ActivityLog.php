<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'activity',
    'actor',
    'category',
    'status',
    'logged_at',
    'details',
    'created_by',
    'updated_by',
])]
class ActivityLog extends Model
{
    public function previewText(int $limit = 140): ?string
    {
        $source = trim(strip_tags((string) $this->details));

        if ($source === '') {
            return null;
        }

        return Str::limit($source, $limit);
    }

    protected function casts(): array
    {
        return [
            'logged_at' => 'datetime',
        ];
    }
}
