@props([
    'active' => false,
    'direction' => 'asc',
])

@php
    $icon = !$active ? 'arrows-up-down' : ($direction === 'asc' ? 'arrow-up-small' : 'arrow-down-small');

    $classes = !$active ? 'h-3.5 w-3.5 text-slate-300' : 'h-3.5 w-3.5 text-sky-600';
@endphp

<span aria-hidden="true" class="inline-flex items-center justify-center leading-none">
    <x-ui.icon :name="$icon" :class="$classes" />
</span>
