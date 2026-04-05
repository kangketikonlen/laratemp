@props([
    'title' => config('app.name'),
    'description' => 'Ruang kerja aplikasi',
])

<x-layouts.private-role :title="$title" :description="$description">
    {{ $slot }}
</x-layouts.private-role>
