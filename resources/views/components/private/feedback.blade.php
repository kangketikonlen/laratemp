@props(['message', 'variant' => 'info'])

<div {{ $attributes->class(['alert', 'alert-danger' => $variant === 'danger', 'alert-info' => $variant === 'info']) }}>
    <x-ui.icon name="alert-circle" class="mt-0.5 h-4 w-4 shrink-0" />
    <span>{{ $message }}</span>
</div>
