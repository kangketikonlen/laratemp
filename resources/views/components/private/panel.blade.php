@props(['title', 'description' => null, 'badge' => null])

<div {{ $attributes->class(['private-panel']) }}>
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="private-panel-title">{{ $title }}</h2>
            @if (filled($description))
                <p class="private-panel-description">{{ $description }}</p>
            @endif
        </div>

        @if (filled($badge))
            <span class="private-panel-badge">{{ $badge }}</span>
        @endif
    </div>

    <div class="mt-5">
        {{ $slot }}
    </div>
</div>
