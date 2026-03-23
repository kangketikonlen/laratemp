@props([
    'variant' => 'primary',
    'block' => true,
    'type' => 'button',
    'loadingTarget' => null,
    'loadingLabel' => null,
])

@php
    $classes = [
        'primary' => 'btn btn-primary',
        'secondary' => 'btn btn-secondary',
        'danger' => 'btn btn-danger',
    ][$variant] ?? 'btn btn-primary';
@endphp

<button
    type="{{ $type }}"
    @if ($loadingTarget)
        wire:loading.attr="disabled"
        wire:target="{{ $loadingTarget }}"
    @endif
    {{ $attributes->class([$classes, 'w-full' => $block]) }}
>
    @if ($loadingTarget && $loadingLabel)
        <span wire:loading.remove wire:target="{{ $loadingTarget }}">
            {{ $slot }}
        </span>

        <span wire:loading.inline-flex wire:target="{{ $loadingTarget }}" class="items-center gap-2">
            <span class="loading-spinner loading-spinner--button"></span>
            <span>{{ $loadingLabel }}</span>
        </span>
    @else
        {{ $slot }}
    @endif
</button>
