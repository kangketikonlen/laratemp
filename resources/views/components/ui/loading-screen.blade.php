@props([
    'target' => null,
    'title' => 'Memproses...',
    'message' => 'Mohon tunggu sebentar.',
])

<div wire:loading.delay.flex @if ($target) wire:target="{{ $target }}" @endif
    class="loading-screen">
    <div class="loading-screen__card">
        <div class="loading-spinner"></div>

        <div class="space-y-1 text-center">
            <p class="font-medium text-gray-900">{{ $title }}</p>
            <p class="text-sm text-gray-500">{{ $message }}</p>
        </div>
    </div>
</div>
