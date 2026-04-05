@props(['href', 'label' => 'Tambah', 'tooltip' => null])

<a href="{{ $href }}" aria-label="{{ $label }}" title="{{ $tooltip ?? $label }}"
    {{ $attributes->class(['private-action-link private-action-link--add icon-action tooltip-trigger']) }}>
    <x-ui.icon name="plus-circle" class="h-4 w-4" />
</a>
