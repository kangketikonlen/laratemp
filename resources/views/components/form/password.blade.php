@props([
    'icon' => 'lock',
    'type' => 'password',
])

<div x-data="{ show: false }" class="relative">
    @if ($icon)
        <span class="input-icon">
            <x-ui.icon :name="$icon" class="text-gray-400" />
        </span>
    @endif

    <input
        :type="show ? 'text' : '{{ $type }}'"
        {{ $attributes->merge([
            'class' => 'input-base ' . ($icon ? 'pl-10 ' : '') . 'pr-10',
        ]) }}
    >

    <button
        type="button"
        @click="show = !show"
        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"
    >
        <x-ui.icon name="eye" />
    </button>
</div>
