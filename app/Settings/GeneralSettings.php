<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $app_name;
    public bool $maintenance_mode;

    public static function group(): string
    {
        return 'general';
    }
}