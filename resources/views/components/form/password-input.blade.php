@props([
    'placeholder' => null,
    'required' => false,
])

<div class="relative">
    <span class="input-icon">
        <x-ui.icon name="lock" class="text-gray-400" />
    </span>

    <input type="password" autocomplete="new-password" placeholder="{{ $placeholder }}"
        @if ($required) required @endif
        {{ $attributes->merge([
            'class' => 'input-base input-with-icon',
        ]) }}>
</div>
