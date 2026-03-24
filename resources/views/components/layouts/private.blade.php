@props([
    'title' => config('app.name'),
    'description' => 'Application workspace',
])

<x-layouts.private-role :title="$title" :description="$description">
    {{ $slot }}
</x-layouts.private-role>
