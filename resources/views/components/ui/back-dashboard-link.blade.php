@props([
    'label' => 'Kembali ke dashboard',
    'tooltip' => null,
])

<a href="{{ route('dashboard') }}" aria-label="{{ $label }}" title="{{ $tooltip ?? $label }}"
    {{ $attributes->class(['private-icon-button tooltip-trigger']) }}>
    <x-ui.icon name="dashboard" class="h-4 w-4" />
</a>
