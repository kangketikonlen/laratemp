<?php

namespace App\Models\Administration;

use App\Models\Concerns\HasPreviewText;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

#[Fillable([
    'version',
    'title',
    'status',
    'released_at',
    'notes',
    'created_by',
    'updated_by',
])]
class Changelog extends Model
{
    use HasPreviewText;

    public static function defaultVersion(null|string|\DateTimeInterface $releaseDate = null): string
    {
        $date = $releaseDate instanceof \DateTimeInterface
            ? Carbon::instance($releaseDate)
            : ($releaseDate ? Carbon::parse($releaseDate) : now());

        return 'v'.$date->format('Y.m.d');
    }

    protected function previewSourceText(): string
    {
        return filled($this->summary)
            ? (string) $this->summary
            : (string) $this->notes;
    }

    protected function casts(): array
    {
        return [
            'released_at' => 'date',
        ];
    }
}
