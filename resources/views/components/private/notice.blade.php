@props([
    'title',
    'message',
])

<div class="private-panel-soft">
    <p class="text-lg font-semibold">{{ $title }}</p>
    <p class="mt-2 text-sm leading-6 text-sky-700/90">{{ $message }}</p>
</div>
