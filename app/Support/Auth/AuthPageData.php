<?php

namespace App\Support\Auth;

use App\Models\Settings\Institution;
use App\Settings\GeneralSettings;
use Spatie\LaravelSettings\Exceptions\MissingSettings;
use Throwable;

final readonly class AuthPageData
{
    public function __construct(
        public string $appName,
        public string $appDescription,
        public string $institutionName,
        public string $institutionAddress,
        public string $copyright,
        public string $background,
        public string $logo,
    ) {}

    public static function resolve(): self
    {
        $appName = config('app.name');
        $appDescription = 'Secure access to your application';
        $institutionName = config('app.name');
        $institutionAddress = 'Alamat institusi dapat diatur melalui pengaturan aplikasi.';

        try {
            $settings = app(GeneralSettings::class);
            $appName = filled($settings->app_name ?? null) ? $settings->app_name : $appName;
            $appDescription = filled($settings->app_description ?? null) ? $settings->app_description : $appDescription;
        } catch (MissingSettings $exception) {
            //
        }

        try {
            $institution = Institution::query()->first();
            $institutionName = filled($institution?->name) ? $institution->name : $institutionName;
            $institutionAddress = filled($institution?->address) ? $institution->address : $institutionAddress;
        } catch (Throwable $exception) {
            //
        }

        return new self(
            appName: $appName,
            appDescription: $appDescription,
            institutionName: $institutionName,
            institutionAddress: $institutionAddress,
            copyright: '© ' . now()->year . ' | ' . $institutionName . ' | ' . $institutionAddress,
            background: asset(config('app.auth_background', 'https://placehold.co/1920x1080')),
            logo: asset(config('app.logo', 'https://placehold.co/200x200')),
        );
    }
}
