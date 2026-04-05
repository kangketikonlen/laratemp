<?php

use App\Models\Report\ErrorLog;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

function reportErrorAdmin(): User
{
    test()->seed(DatabaseSeeder::class);

    return User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
}

it('shows the error log index and detail pages', function () {
    /** @var TestCase $this */
    $admin = reportErrorAdmin();

    ErrorLog::query()->create([
        'exception_class' => RuntimeException::class,
        'message' => 'Previous error',
        'level' => 'warning',
        'path' => '/report',
        'method' => 'GET',
        'user_identifier' => $admin->username,
        'file' => base_path('app/Http/Controllers/Report/ErrorLogController.php'),
        'line' => 10,
        'trace' => '#0 previous trace',
        'occurred_at' => now()->subMinute(),
    ]);

    $log = ErrorLog::query()->create([
        'exception_class' => RuntimeException::class,
        'message' => 'Important error',
        'level' => 'error',
        'path' => '/report/error-logs',
        'method' => 'GET',
        'user_identifier' => $admin->username,
        'file' => base_path('app/Http/Controllers/Report/ErrorLogController.php'),
        'line' => 42,
        'trace' => '#0 current trace',
        'occurred_at' => now(),
    ]);

    $this->actingAs($admin)
        ->get(route('report.error-report.index'))
        ->assertOk()
        ->assertSeeText('Important error');

    $this->actingAs($admin)
        ->get(route('report.error-report.show', $log))
        ->assertOk()
        ->assertSeeText('Important error')
        ->assertSeeText($admin->username);
});
