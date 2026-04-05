@props([
    'label' => 'Cari',
    'tooltip' => null,
    'type' => 'submit',
])

<x-ui.button :type="$type" variant="secondary" :block="false" icon="search" :label="$label" :tooltip="$tooltip ?? $label"
    :icon-only="true" class="btn-search" {{ $attributes }} />
