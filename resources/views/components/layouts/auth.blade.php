@props([
    'title' => config('app.name'),
    'description' => 'Akses aman ke aplikasi Anda',
])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ $description }}</title>
    <meta name="description" content="{{ $description }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-gray-100">

    {{ $slot }}

    @livewireScripts
</body>
</html>
