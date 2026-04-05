<?php

use App\Support\Permissions\PermissionCatalog;

it('returns unique permission names for every catalog entry', function () {
    $names = PermissionCatalog::names();

    expect($names)
        ->toContain('view_users', 'update_permissions', 'view_error_logs')
        ->and($names)->toBe(array_values(array_unique($names)));
});

it('maps permission metadata back to the owning section', function () {
    $metadata = PermissionCatalog::metadata();

    expect($metadata['update_permissions'])->toMatchArray([
        'section_key' => 'permissions',
        'section_title' => 'Permission Settings',
        'name' => 'update_permissions',
        'label' => 'Update',
    ])
        ->and($metadata['view_activity_logs']['section_key'])->toBe('activity_logs')
        ->and($metadata['view_error_logs']['section_title'])->toBe('Error Logs');
});
