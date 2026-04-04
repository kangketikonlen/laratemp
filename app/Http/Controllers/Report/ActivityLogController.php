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
        $sort = ActivityLog::normalizeSort($request->string('sort', ActivityLog::defaultSort())->value());
        $direction = ActivityLog::normalizeDirection($request->string('direction', ActivityLog::defaultDirection())->value());

        $logs = ActivityLog::query()
            ->search($search)
            ->applySort($sort, $direction)
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
        return view('auth.report.activity-log.show', [
            'title' => 'Activity Log',
            'description' => 'Review full activity log details from the report section.',
            'log' => $activityLog,
            'previousLog' => $activityLog->previousLog(),
            'nextLog' => $activityLog->nextLog(),
        ]);
    }
}
