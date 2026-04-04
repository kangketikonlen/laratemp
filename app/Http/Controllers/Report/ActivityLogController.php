<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Report\ActivityLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $sort = (string) $request->string('sort', 'logged_at');
        $direction = strtolower((string) $request->string('direction', 'desc'));
        $allowedSorts = ['activity', 'actor', 'category', 'status', 'logged_at'];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'logged_at';
        }

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $logs = ActivityLog::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($logQuery) use ($search) {
                    $logQuery
                        ->where('activity', 'like', "%{$search}%")
                        ->orWhere('actor', 'like', "%{$search}%")
                        ->orWhere('category', 'like', "%{$search}%")
                        ->orWhere('status', 'like', "%{$search}%")
                        ->orWhere('details', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $direction)
            ->when($sort !== 'logged_at', fn ($query) => $query->orderByDesc('logged_at'))
            ->paginate(10)
            ->withQueryString();

        return view('auth.report.activity-log.index', [
            'title' => 'Activity Log',
            'description' => 'Review activity log entries from the report section.',
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
            'logs' => $logs,
        ]);
    }

    public function show(ActivityLog $activityLog): View
    {
        $previousLog = ActivityLog::query()
            ->where(function ($query) use ($activityLog) {
                $query->where('logged_at', '>', $activityLog->logged_at)
                    ->orWhere(function ($sameTimeQuery) use ($activityLog) {
                        $sameTimeQuery
                            ->where('logged_at', $activityLog->logged_at)
                            ->where('id', '>', $activityLog->id);
                    });
            })
            ->orderBy('logged_at')
            ->orderBy('id')
            ->first();

        $nextLog = ActivityLog::query()
            ->where(function ($query) use ($activityLog) {
                $query->where('logged_at', '<', $activityLog->logged_at)
                    ->orWhere(function ($sameTimeQuery) use ($activityLog) {
                        $sameTimeQuery
                            ->where('logged_at', $activityLog->logged_at)
                            ->where('id', '<', $activityLog->id);
                    });
            })
            ->orderByDesc('logged_at')
            ->orderByDesc('id')
            ->first();

        return view('auth.report.activity-log.show', [
            'title' => 'Activity Log',
            'description' => 'Review full activity log details from the report section.',
            'log' => $activityLog,
            'previousLog' => $previousLog,
            'nextLog' => $nextLog,
        ]);
    }
}
