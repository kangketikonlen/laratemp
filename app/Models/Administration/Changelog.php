<?php

namespace App\Models\Administration;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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
    public static function defaultVersion(null|string|\DateTimeInterface $releaseDate = null): string
    {
        $date = $releaseDate instanceof \DateTimeInterface
            ? Carbon::instance($releaseDate)
            : ($releaseDate ? Carbon::parse($releaseDate) : now());

        return 'v'.$date->format('Y.m.d');
    }

    public function previewText(int $limit = 140): ?string
    {
        $source = filled($this->summary)
            ? (string) $this->summary
            : trim(strip_tags((string) $this->notes));

        if ($source === '') {
            return null;
        }

        return Str::limit($source, $limit);
    }

    protected function casts(): array
    {
        return [
            'released_at' => 'date',
        ];
    }
}
