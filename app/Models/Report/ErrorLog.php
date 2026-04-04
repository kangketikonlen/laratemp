<?php

namespace App\Models\Report;

use App\Models\Concerns\HasPreviewText;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'exception_class',
    'message',
    'level',
    'path',
    'method',
    'ip_address',
    'user_identifier',
    'file',
    'line',
    'trace',
    'occurred_at',
])]
class ErrorLog extends Model
{
    use HasPreviewText;

    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->when($search !== '', function (Builder $errorLogQuery) use ($search) {
            $errorLogQuery->where(function (Builder $logQuery) use ($search) {
                $logQuery
                    ->where('exception_class', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%")
                    ->orWhere('path', 'like', "%{$search}%")
                    ->orWhere('method', 'like', "%{$search}%")
                    ->orWhere('user_identifier', 'like', "%{$search}%")
                    ->orWhere('file', 'like', "%{$search}%");
            });
        });
    }

    public function scopeApplySort(Builder $query, string $sort, string $direction): Builder
    {
        return $query
            ->orderBy($sort, $direction)
            ->when($sort !== 'occurred_at', fn (Builder $errorLogQuery) => $errorLogQuery->orderByDesc('occurred_at'));
    }

    public function previousLog(): ?self
    {
        return self::query()
            ->where(function (Builder $query) {
                $query->where('occurred_at', '>', $this->occurred_at)
                    ->orWhere(function (Builder $sameTimeQuery) {
                        $sameTimeQuery
                            ->where('occurred_at', $this->occurred_at)
                            ->where('id', '>', $this->id);
                    });
            })
            ->orderBy('occurred_at')
            ->orderBy('id')
            ->first();
    }

    public function nextLog(): ?self
    {
        return self::query()
            ->where(function (Builder $query) {
                $query->where('occurred_at', '<', $this->occurred_at)
                    ->orWhere(function (Builder $sameTimeQuery) {
                        $sameTimeQuery
                            ->where('occurred_at', $this->occurred_at)
                            ->where('id', '<', $this->id);
                    });
            })
            ->orderByDesc('occurred_at')
            ->orderByDesc('id')
            ->first();
    }

    protected function previewSourceText(): string
    {
        return (string) $this->trace;
    }

    public static function allowedSorts(): array
    {
        return ['exception_class', 'level', 'path', 'method', 'user_identifier', 'occurred_at'];
    }

    public static function defaultSort(): string
    {
        return 'occurred_at';
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
            'occurred_at' => 'datetime',
            'line' => 'integer',
        ];
    }
}
