@props([
    'href',
    'label' => 'Batal',
    'tooltip' => null,
])

<a
    href="{{ $href }}"
    aria-label="{{ $label }}"
    title="{{ $tooltip ?? $label }}"
    {{ $attributes->class(['private-action-link private-action-link--cancel icon-action tooltip-trigger']) }}
>
    <x-ui.icon name="x-circle" class="h-4 w-4" />
</a>
