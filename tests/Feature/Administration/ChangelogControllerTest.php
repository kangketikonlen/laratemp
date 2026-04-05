<?php

use App\Models\Administration\Changelog;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Tests\TestCase;

function administrationChangelogAdmin(): User
{
    test()->seed(DatabaseSeeder::class);

    return User::query()->where('username', config('bootstrap_admin.username'))->firstOrFail();
}

it('shows the changelog index page and its records', function () {
    /** @var TestCase $this */
    $admin = administrationChangelogAdmin();

    Changelog::query()->create([
        'version' => 'v2.0.0',
        'title' => 'Big Release',
        'status' => 'published',
        'released_at' => '2026-04-01',
        'notes' => 'Release notes',
        'created_by' => $admin->username,
        'updated_by' => $admin->username,
    ]);

    $this->actingAs($admin)
        ->get(route('administration.changelogs.index'))
        ->assertOk()
        ->assertSeeText('Big Release')
        ->assertSeeText('v2.0.0');
});

it('creates, updates, and deletes a changelog', function () {
    /** @var TestCase $this */
    $admin = administrationChangelogAdmin();

    $this->actingAs($admin)
        ->post(route('administration.changelogs.store'), [
            'title' => 'New Release',
            'status' => 'draft',
            'released_at' => '2026-04-05',
            'notes' => '<script>alert(1)</script><p>Initial notes</p>',
        ])
        ->assertRedirect(route('administration.changelogs.index'))
        ->assertSessionHas('status');

    $changelog = Changelog::query()->where('title', 'New Release')->firstOrFail();

    expect($changelog->version)->toBe('v2026.04.05')
        ->and($changelog->notes)->not->toContain('<script>');

    $this->actingAs($admin)
        ->put(route('administration.changelogs.update', $changelog), [
            'version' => 'v2026.04.06',
            'title' => 'New Release Updated',
            'status' => 'published',
            'released_at' => '2026-04-06',
            'notes' => '<p>Published notes</p>',
        ])
        ->assertRedirect(route('administration.changelogs.index'))
        ->assertSessionHas('status');

    expect($changelog->fresh()->title)->toBe('New Release Updated');

    $this->actingAs($admin)
        ->delete(route('administration.changelogs.destroy', $changelog))
        ->assertRedirect(route('administration.changelogs.index'))
        ->assertSessionHas('status');

    $this->assertDatabaseMissing('changelogs', [
        'id' => $changelog->id,
    ]);
});
