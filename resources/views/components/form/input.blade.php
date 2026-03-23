@props([
    'icon' => 'user',
    'type' => 'text',
])

<div class="relative">
    @if ($icon)
        <span class="input-icon">
            <x-ui.icon :name="$icon" class="text-gray-400" />
        </span>
    @endif

    <input
        type="{{ $type }}"
        {{ $attributes->merge([
            'class' => 'input-base ' . ($icon ? 'pl-10' : ''),
        ]) }}
    >
</div>
