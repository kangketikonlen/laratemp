<?php

use App\Models\Administration\Changelog;
use Carbon\CarbonImmutable;

it('builds a default changelog version from the provided date', function () {
    expect(Changelog::defaultVersion('2026-04-07'))->toBe('v2026.04.07')
        ->and(Changelog::defaultVersion(CarbonImmutable::parse('2026-04-08')))->toBe('v2026.04.08');
});

it('prefers summary text when generating a changelog preview', function () {
    $changelog = new Changelog([
        'notes' => '<p>Detailed release notes that should not be preferred.</p>',
    ]);
    $changelog->summary = '<p>Short release summary</p>';

    expect($changelog->previewText())->toBe('Short release summary');
});
