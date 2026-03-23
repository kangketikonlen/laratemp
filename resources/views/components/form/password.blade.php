@props([
    'icon' => 'lock',
])

@php
    $wireModelAttribute = collect(array_keys($attributes->getAttributes()))
        ->first(fn (string $key) => str_starts_with($key, 'wire:model'));
    $wireModel = $wireModelAttribute ? $attributes->get($wireModelAttribute) : null;
    $inputName = $attributes->get('name') ?: $wireModel;
@endphp

<div class="relative">
    @if ($icon)
        <span class="input-icon">
            <x-ui.icon :name="$icon" class="text-gray-400" />
        </span>
    @endif

    <input
        type="password"
        @if ($wireModelAttribute && $wireModel)
            {{ $wireModelAttribute }}="{{ $wireModel }}"
        @endif
        {{ $attributes->merge([
            'name' => $inputName,
            'class' => 'input-base ' . ($icon ? 'input-with-icon ' : '') . 'input-with-action',
        ])->except($wireModelAttribute ? [$wireModelAttribute] : []) }}
    >
</div>
