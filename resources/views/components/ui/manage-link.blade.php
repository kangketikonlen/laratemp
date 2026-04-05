@props(['href', 'label' => 'Kelola', 'tooltip' => null])

<a href="{{ $href }}" aria-label="{{ $label }}" title="{{ $tooltip ?? $label }}"
    {{ $attributes->class(['private-action-link private-action-link--manage icon-action tooltip-trigger']) }}>
    <x-ui.icon name="sliders" class="h-4 w-4" />
</a>
