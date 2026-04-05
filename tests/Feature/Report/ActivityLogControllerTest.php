<?php

use App\Models\Report\ActivityLog;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

function reportActivityAdmin(): User
{
    test()->seed(DatabaseSeeder::class);

    return User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
}

it('shows the activity log index and detail pages', function () {
    /** @var TestCase $this */
    $admin = reportActivityAdmin();

    ActivityLog::query()->create([
        'activity' => 'Earlier event',
        'actor' => $admin->username,
        'category' => 'security',
        'status' => 'success',
        'logged_at' => now()->subMinute(),
        'details' => '<p>Earlier details</p>',
        'created_by' => $admin->username,
        'updated_by' => $admin->username,
    ]);

    $log = ActivityLog::query()->create([
        'activity' => 'Viewed report',
        'actor' => $admin->username,
        'category' => 'operations',
        'status' => 'info',
        'logged_at' => now(),
        'details' => '<p>Viewed details</p>',
        'created_by' => $admin->username,
        'updated_by' => $admin->username,
    ]);

    $this->actingAs($admin)
        ->get(route('report.activity-log.index'))
        ->assertOk()
        ->assertSeeText('Viewed report');

    $this->actingAs($admin)
        ->get(route('report.activity-log.show', $log))
        ->assertOk()
        ->assertSeeText('Viewed report')
        ->assertSeeText($admin->username);
});
