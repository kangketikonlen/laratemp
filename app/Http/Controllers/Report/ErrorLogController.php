<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Report\ErrorLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ErrorLogController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $sort = ErrorLog::normalizeSort($request->string('sort', ErrorLog::defaultSort())->value());
        $direction = ErrorLog::normalizeDirection($request->string('direction', ErrorLog::defaultDirection())->value());

        $logs = ErrorLog::query()
            ->search($search)
            ->applySort($sort, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('auth.report.error-logs.index', [
            'title' => 'Error Logs',
            'description' => 'Review captured application errors from the report section.',
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
            'logs' => $logs,
        ]);
    }

    public function show(ErrorLog $errorLog): View
    {
        return view('auth.report.error-logs.show', [
            'title' => 'Error Logs',
            'description' => 'Review full error details from the report section.',
            'log' => $errorLog,
            'previousLog' => $errorLog->previousLog(),
            'nextLog' => $errorLog->nextLog(),
        ]);
    }
}
