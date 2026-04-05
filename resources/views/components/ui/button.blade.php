@props([
    'variant' => 'primary',
    'block' => true,
    'type' => 'button',
    'loadingTarget' => null,
    'loadingLabel' => null,
    'icon' => null,
    'label' => null,
    'tooltip' => null,
    'iconOnly' => false,
])

@php
    $classes =
        [
            'primary' => 'btn btn-primary',
            'secondary' => 'btn btn-secondary',
            'danger' => 'btn btn-danger',
        ][$variant] ?? 'btn btn-primary';
@endphp

<button type="{{ $type }}"
    @if ($loadingTarget) wire:loading.attr="disabled" wire:target="{{ $loadingTarget }}" @endif
    @if ($label || $tooltip) aria-label="{{ $label ?? $tooltip }}" title="{{ $tooltip ?? $label }}" @endif
    {{ $attributes->class([$classes, 'w-full' => $block, 'btn-icon icon-action tooltip-trigger' => $iconOnly]) }}>
    @if ($loadingTarget && $loadingLabel)
        <span wire:loading.remove wire:target="{{ $loadingTarget }}">
            @if ($icon)
                <span class="inline-flex items-center justify-center leading-none"><x-ui.icon :name="$icon"
                        class="h-4 w-4" /></span>
            @endif

            @if ($iconOnly)
                <span class="sr-only">{{ $label ?? ($tooltip ?? trim((string) $slot)) }}</span>
            @else
                {{ $slot }}
            @endif
        </span>

        <span wire:loading.inline-flex wire:target="{{ $loadingTarget }}" class="items-center gap-2">
            <span class="loading-spinner loading-spinner--button"></span>
            @if ($iconOnly)
            <span class="sr-only">{{ $loadingLabel }}</span>@else<span>{{ $loadingLabel }}</span>
            @endif
        </span>
    @else
        @if ($icon)
            <span class="inline-flex items-center justify-center leading-none"><x-ui.icon :name="$icon"
                    class="h-4 w-4" /></span>
        @endif

        @if ($iconOnly)
            <span class="sr-only">{{ $label ?? ($tooltip ?? trim((string) $slot)) }}</span>
        @else
            {{ $slot }}
        @endif
    @endif
</button>
