@props([
    'href',
    'label' => 'Lihat detail',
    'tooltip' => null,
])

<a
    href="{{ $href }}"
    aria-label="{{ $label }}"
    title="{{ $tooltip ?? $label }}"
    {{ $attributes->class(['private-action-link private-action-link--detail icon-action tooltip-trigger']) }}
>
    <x-ui.icon name="document-text" class="h-4 w-4" />
</a>
