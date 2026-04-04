<?php

namespace App\Models\Report;

use App\Models\Concerns\HasPreviewText;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
    use HasPreviewText;

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->when($search !== '', function (Builder $activityLogQuery) use ($search) {
            $activityLogQuery->where(function (Builder $logQuery) use ($search) {
                $logQuery
                    ->where('activity', 'like', "%{$search}%")
                    ->orWhere('actor', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('details', 'like', "%{$search}%");
            });
        });
    }

    public function scopeApplySort(Builder $query, string $sort, string $direction): Builder
    {
        return $query
            ->orderBy($sort, $direction)
            ->when($sort !== 'logged_at', fn (Builder $activityLogQuery) => $activityLogQuery->orderByDesc('logged_at'));
    }

    public function previousLog(): ?self
    {
        return self::query()
            ->where(function (Builder $query) {
                $query->where('logged_at', '>', $this->logged_at)
                    ->orWhere(function (Builder $sameTimeQuery) {
                        $sameTimeQuery
                            ->where('logged_at', $this->logged_at)
                            ->where('id', '>', $this->id);
                    });
            })
            ->orderBy('logged_at')
            ->orderBy('id')
            ->first();
    }

    public function nextLog(): ?self
    {
        return self::query()
            ->where(function (Builder $query) {
                $query->where('logged_at', '<', $this->logged_at)
                    ->orWhere(function (Builder $sameTimeQuery) {
                        $sameTimeQuery
                            ->where('logged_at', $this->logged_at)
                            ->where('id', '<', $this->id);
                    });
            })
            ->orderByDesc('logged_at')
            ->orderByDesc('id')
            ->first();
    }

    protected function previewSourceText(): string
    {
        return (string) $this->details;
    }

    public static function allowedSorts(): array
    {
        return ['activity', 'actor', 'category', 'status', 'logged_at'];
    }

    public static function defaultSort(): string
    {
        return 'logged_at';
    }

    public static function defaultDirection(): string
    {
        return 'desc';
    }

    public static function normalizeSort(?string $sort): string
    {
        $sort = (string) $sort;

        if (! in_array($sort, self::allowedSorts(), true)) {
            return self::defaultSort();
        }

        return $sort;
    }

    public static function normalizeDirection(?string $direction): string
    {
        $direction = strtolower((string) $direction);

        if (! in_array($direction, ['asc', 'desc'], true)) {
            return self::defaultDirection();
        }

        return $direction;
    }

    protected function casts(): array
    {
        return [
            'logged_at' => 'datetime',
        ];
    }
}
