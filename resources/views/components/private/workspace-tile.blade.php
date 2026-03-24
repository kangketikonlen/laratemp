@props([
    'title',
    'description',
    'href' => null,
    'suffixIcon' => false,
    'cardClass' => '',
])

@php
    $tag = filled($href) ? 'a' : 'div';
@endphp

<{{ $tag }}
    {{ $attributes->class([
        'workspace-tile',
        'workspace-tile--link' => filled($href),
        $cardClass,
    ]) }}
    @if (filled($href))
        href="{{ $href }}"
    @endif
>
    <div class="flex items-center justify-between gap-3">
        <h3 class="text-base font-semibold text-slate-900">{{ $title }}</h3>
        @if ($suffixIcon)
            <x-ui.icon name="chevron-right" class="h-4 w-4 text-slate-400" />
        @endif
    </div>
    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $description }}</p>
</{{ $tag }}>
