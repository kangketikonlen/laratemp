@props([
    'variant' => 'danger',
    'icon' => null,
    'title' => null,
    'message' => null,
])

@php
    $styles = [
        'danger' => [
            'class' => 'alert alert-danger',
            'icon' => 'alert-circle',
        ],
        'info' => [
            'class' => 'alert alert-info',
            'icon' => 'search',
        ],
    ][$variant] ?? [
        'class' => 'alert alert-danger',
        'icon' => 'alert-circle',
    ];

    $iconName = $icon ?: $styles['icon'];
@endphp

<div {{ $attributes->class($styles['class']) }}>
    @if ($iconName)
        <x-ui.icon :name="$iconName" class="mt-0.5 h-4 w-4 shrink-0" />
    @endif

    <div class="min-w-0 flex-1">
        @if ($title)
            <p class="font-medium leading-5">{{ $title }}</p>
        @endif

        @if ($message || filled($slot))
            <p @class(['leading-5', 'mt-1' => $title])>
                {{ $message ?: $slot }}
            </p>
        @endif
    </div>
</div>
