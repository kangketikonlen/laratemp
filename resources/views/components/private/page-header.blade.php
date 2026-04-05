@props(['title', 'subtitle' => null])

<div class="private-page-header">
    <div>
        <h1 class="private-page-title">{{ $title }}</h1>
        @if (filled($subtitle))
            <p class="private-page-subtitle">{{ $subtitle }}</p>
        @endif
    </div>

    @if (trim($actions ?? '') !== '')
        <div class="flex items-center gap-3">
            {{ $actions }}
        </div>
    @endif
</div>
