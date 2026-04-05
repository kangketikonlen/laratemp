@props([
    'action',
    'label' => 'Hapus',
    'tooltip' => null,
    'confirm' => 'Apakah Anda yakin ingin menghapus item ini?',
])

<form method="POST" action="{{ $action }}" onsubmit="return confirm(@js($confirm))"
    {{ $attributes->class([]) }}>
    @csrf
    @method('DELETE')

    <x-ui.button type="submit" variant="danger" :block="false" icon="trash" :label="$label" :tooltip="$tooltip ?? $label"
        :icon-only="true" />
</form>
