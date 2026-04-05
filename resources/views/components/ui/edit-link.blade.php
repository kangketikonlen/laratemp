@props([
    'href',
    'label' => 'Ubah',
    'tooltip' => null,
])

<a href="{{ $href }}" aria-label="{{ $label }}" title="{{ $tooltip ?? $label }}" {{ $attributes->class(['private-action-link private-action-link--edit icon-action tooltip-trigger']) }}>
    <x-ui.icon name="pencil-square" class="h-4 w-4" />
</a>
