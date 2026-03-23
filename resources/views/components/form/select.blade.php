@props([
    'icon' => null,
])

<div class="relative">
    @if ($icon)
        <span class="input-icon">
            <x-ui.icon :name="$icon" class="text-gray-400" />
        </span>
    @endif

    <select
        {{ $attributes->merge([
            'class' => 'input-base ' . ($icon ? 'pl-10' : ''),
        ]) }}
    >
        {{ $slot }}
    </select>
</div>
