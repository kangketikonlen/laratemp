<?php

namespace App\Support\ActivityLogs;

use App\Models\Report\ActivityLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

trait LogsUserActivity
{
    protected function logUserActivity(
        string $activity,
        string $category = 'operations',
        string $status = 'info',
        ?Authenticatable $user = null,
        ?Request $request = null,
        array $context = [],
    ): void {
        $actor = $user?->username ?? $user?->name ?? 'System';
        $details = $this->formatActivityDetails($request, $context);

        ActivityLog::query()->create([
            'activity' => $activity,
            'actor' => $actor,
            'category' => $category,
            'status' => $status,
            'logged_at' => now(),
            'details' => $details,
            'created_by' => $actor,
            'updated_by' => $actor,
        ]);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    protected function formatActivityDetails(?Request $request, array $context = []): ?string
    {
        $entries = [];

        if ($request) {
            if (filled($request->path())) {
                $entries[] = 'Path: '.$request->path();
            }

            if (filled($request->ip())) {
                $entries[] = 'IP: '.$request->ip();
            }
        }

        foreach ($context as $label => $value) {
            if (is_null($value) || $value === '') {
                continue;
            }

            if (is_array($value)) {
                $value = implode(', ', $value);
            }

            $entries[] = ucfirst(str_replace('_', ' ', (string) $label)).': '.$value;
        }

        if ($entries === []) {
            return null;
        }

        return '<ul><li>'.implode('</li><li>', array_map('e', $entries)).'</li></ul>';
    }
}
