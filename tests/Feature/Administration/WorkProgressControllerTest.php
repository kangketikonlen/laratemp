<?php

use App\Models\Administration\WorkProgress;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

function administrationWorkProgressAdmin(): User
{
    test()->seed(DatabaseSeeder::class);

    return User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
}

it('shows the work progress index page and its records', function () {
    /** @var TestCase $this */
    $admin = administrationWorkProgressAdmin();

    WorkProgress::query()->create([
        'title' => 'Launch internal portal',
        'owner' => 'Platform Team',
        'status' => 'in_progress',
        'priority' => 'high',
        'progress' => 65,
        'target_date' => '2026-04-20',
        'notes' => 'Keep shipping',
        'created_by' => $admin->username,
        'updated_by' => $admin->username,
    ]);

    $this->actingAs($admin)
        ->get(route('administration.work-progress.index'))
        ->assertOk()
        ->assertSeeText('Launch internal portal')
        ->assertSeeText('Platform Team');
});

it('creates, updates, and deletes a work progress item', function () {
    /** @var TestCase $this */
    $admin = administrationWorkProgressAdmin();

    $this->actingAs($admin)
        ->post(route('administration.work-progress.store'), [
            'title' => 'Prepare rollout',
            'owner' => 'Ops Team',
            'status' => 'planned',
            'priority' => 'medium',
            'progress' => 10,
            'target_date' => '2026-04-15',
            'notes' => '<script>alert(1)</script><p>Queued</p>',
        ])
        ->assertRedirect(route('administration.work-progress.index'))
        ->assertSessionHas('status');

    $item = WorkProgress::query()->where('title', 'Prepare rollout')->firstOrFail();

    expect($item->notes)->not->toContain('<script>');

    $this->actingAs($admin)
        ->put(route('administration.work-progress.update', $item), [
            'title' => 'Prepare rollout updated',
            'owner' => 'Ops Team',
            'status' => 'done',
            'priority' => 'high',
            'progress' => 100,
            'target_date' => '2026-04-16',
            'notes' => '<p>Completed</p>',
        ])
        ->assertRedirect(route('administration.work-progress.index'))
        ->assertSessionHas('status');

    $item->refresh();

    expect($item->title)->toBe('Prepare rollout updated')
        ->and($item->status)->toBe('done')
        ->and($item->progress)->toBe(100);

    $this->actingAs($admin)
        ->delete(route('administration.work-progress.destroy', $item))
        ->assertRedirect(route('administration.work-progress.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseMissing('work_progress', [
        'id' => $item->id,
    ]);
});
