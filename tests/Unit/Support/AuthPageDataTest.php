<?php

use App\Settings\GeneralSettings;
use App\Support\Auth\AuthPageData;

it('resolves auth page data from configured settings', function () {
    config()->set('app.name', 'LaraTemp');
    config()->set('app.auth_background', 'images/auth-background.jpg');
    config()->set('app.logo', 'images/logo.svg');

    $settings = mock(GeneralSettings::class);
    $settings->app_name = 'Workspace Portal';
    $settings->app_description = 'A focused sign-in experience';

    app()->instance(GeneralSettings::class, $settings);

    $data = AuthPageData::resolve();

    expect($data->appName)->toBe('Workspace Portal')
        ->and($data->appDescription)->toBe('A focused sign-in experience')
        ->and($data->institutionName)->toBe('LaraTemp')
        ->and($data->background)->toContain('/images/auth-background.jpg')
        ->and($data->logo)->toContain('/images/logo.svg')
        ->and($data->copyright)->toContain('LaraTemp');
});

it('falls back gracefully when settings are not available', function () {
    config()->set('app.name', 'LaraTemp');

    $settings = mock(GeneralSettings::class);
    $settings->app_name = '';
    $settings->app_description = '';

    app()->instance(GeneralSettings::class, $settings);

    $data = AuthPageData::resolve();

    expect($data->appName)->toBe('LaraTemp')
        ->and($data->appDescription)->toBe('Secure access to your application')
        ->and($data->institutionAddress)->toContain('Alamat institusi');
});
