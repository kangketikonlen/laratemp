@props([
    'for' => null,
    'label',
    'error' => null,
])

<div {{ $attributes->class(['private-field']) }}>
    <label @if (filled($for)) for="{{ $for }}" @endif
        class="private-label">{{ $label }}</label>

    {{ $slot }}

    @if (filled($error))
        <p class="private-error">{{ $error }}</p>
    @endif
</div>
