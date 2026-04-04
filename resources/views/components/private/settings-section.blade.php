@props([
    'title',
    'description' => null,
])

<section {{ $attributes->class(['institution-settings-section']) }}>
    <div class="institution-settings-section-head">
        <div>
            <h3 class="institution-settings-section-title">{{ $title }}</h3>
            @if (filled($description))
                <p class="institution-settings-section-copy">{{ $description }}</p>
            @endif
        </div>
    </div>

    {{ $slot }}
</section>
