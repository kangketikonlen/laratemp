<?php

use App\Models\Report\ActivityLog;
use App\Models\Report\ErrorLog;

it('normalizes invalid activity log sort and direction values', function () {
    expect(ActivityLog::normalizeSort('invalid-column'))->toBe(ActivityLog::defaultSort())
        ->and(ActivityLog::normalizeSort('actor'))->toBe('actor')
        ->and(ActivityLog::normalizeDirection('sideways'))->toBe(ActivityLog::defaultDirection())
        ->and(ActivityLog::normalizeDirection('ASC'))->toBe('asc');
});

it('normalizes invalid error log sort and direction values', function () {
    expect(ErrorLog::normalizeSort('invalid-column'))->toBe(ErrorLog::defaultSort())
        ->and(ErrorLog::normalizeSort('level'))->toBe('level')
        ->and(ErrorLog::normalizeDirection('sideways'))->toBe(ErrorLog::defaultDirection())
        ->and(ErrorLog::normalizeDirection('DESC'))->toBe('desc');
});

it('builds plain-text previews from report log content', function () {
    $activityLog = new ActivityLog([
        'details' => '<p>Created <strong>user</strong> account successfully.</p>',
    ]);
    $errorLog = new ErrorLog([
        'trace' => "<div>#0 App\\Service\\Foo</div>\n<div>#1 App\\Http\\Controller</div>",
    ]);

    expect($activityLog->previewText(20))->toBe('Created user account...')
        ->and($errorLog->previewText())->toContain('#0 App\Service\Foo');
});
