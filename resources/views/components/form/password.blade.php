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
        class="input-action-button right-3"
        :aria-label="show ? 'Hide password' : 'Show password'"
    >
        <x-ui.icon x-show="show" name="eye" class="h-5 w-5" />
        <x-ui.icon x-show="!show" name="eye-slash" class="h-5 w-5" />
    </button>
</div>
