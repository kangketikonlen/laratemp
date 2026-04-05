@props([
    'label' => 'Keluar',
    'tooltip' => null,
])

<form method="POST" action="{{ route('logout') }}" {{ $attributes->class([]) }}>
    @csrf

    <x-ui.button
        type="submit"
        variant="danger"
        :block="false"
        icon="door-open"
        :label="$label"
        :tooltip="$tooltip ?? $label"
        :icon-only="true"
        class="rounded-2xl"
    />
</form>
