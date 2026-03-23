@php
    $appName = config('app.name');
    $appDescription = 'Secure access to your application';

    try {
        $settings = app(\App\Settings\GeneralSettings::class);
        $appName = filled($settings->app_name ?? null) ? $settings->app_name : $appName;
        $appDescription = filled($settings->app_description ?? null) ? $settings->app_description : $appDescription;
    } catch (\Spatie\LaravelSettings\Exceptions\MissingSettings $exception) {
        //
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appName }} - {{ $appDescription }}</title>
    <meta name="description" content="{{ $appDescription }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-gray-100">

    {{ $slot }}

    @livewireScripts
</body>
</html>
