@props(['title', 'description', 'href' => null, 'suffixIcon' => false, 'cardClass' => ''])

@php
    $tag = filled($href) ? 'a' : 'div';
@endphp

<{{ $tag }} {{ $attributes->class(['workspace-tile', 'workspace-tile--link' => filled($href), $cardClass]) }}
    @if (filled($href)) href="{{ $href }}" @endif>
    <div class="workspace-tile-head">
        <h3 class="workspace-tile-title">{{ $title }}</h3>
        @if ($suffixIcon)
            <x-ui.icon name="chevron-right" class="workspace-tile-icon" />
        @endif
    </div>
    <p class="workspace-tile-copy">{{ $description }}</p>
    </{{ $tag }}>
