@props([
    'label' => 'Simpan',
    'tooltip' => null,
    'type' => 'submit',
])

<x-ui.button :type="$type" variant="primary" :block="false" icon="save" :label="$label" :tooltip="$tooltip ?? $label"
    :icon-only="true" {{ $attributes }} />
