@props(['name'])

@switch($name)

    @case('user')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6.75a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 18a7.5 7.5 0 0115 0"/>
        </svg>
    @break

    @case('lock')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.5a4.5 4.5 0 10-9 0v3m-1.5 0h12a1.5 1.5 0 011.5 1.5v6a1.5 1.5 0 01-1.5 1.5h-12A1.5 1.5 0 014.5 18v-6A1.5 1.5 0 016 10.5z"/>
        </svg>
    @break

    @case('eye')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6z"/>
        </svg>
    @break

    @case('eye-slash')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.58 10.58a2 2 0 0 0 2.84 2.84"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.88 5.09A10.94 10.94 0 0 1 12 4.88c6 0 9.75 7.12 9.75 7.12a20.1 20.1 0 0 1-4.04 4.95"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.61 6.61A20.64 20.64 0 0 0 2.25 12s3.75 7.12 9.75 7.12a10.7 10.7 0 0 0 4.21-.83"/>
        </svg>
    @break

    @case('alert-circle')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="9"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v5"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16h.01"/>
        </svg>
    @break

    @case('settings')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317a1.724 1.724 0 0 1 3.35 0 1.724 1.724 0 0 0 2.573 1.066 1.724 1.724 0 0 1 2.898 1.675 1.724 1.724 0 0 0 .775 2.42 1.724 1.724 0 0 1 0 3.044 1.724 1.724 0 0 0-.775 2.42 1.724 1.724 0 0 1-2.898 1.675 1.724 1.724 0 0 0-2.573 1.066 1.724 1.724 0 0 1-3.35 0 1.724 1.724 0 0 0-2.573-1.066 1.724 1.724 0 0 1-2.898-1.675 1.724 1.724 0 0 0-.775-2.42 1.724 1.724 0 0 1 0-3.044 1.724 1.724 0 0 0 .775-2.42 1.724 1.724 0 0 1 2.898-1.675 1.724 1.724 0 0 0 2.573-1.066z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12a3 3 0 1 0 6 0 3 3 0 0 0-6 0z"/>
        </svg>
    @break

    @case('building')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M5.25 21V6.75A.75.75 0 0 1 6 6h3.75a.75.75 0 0 0 .75-.75V3.75A.75.75 0 0 1 11.25 3h6.75a.75.75 0 0 1 .75.75V21M8.25 9.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6h1.5m-1.5 3h1.5m-1.5 3h1.5"/>
        </svg>
    @break

    @case('sparkles')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18l-.813-2.096a4.5 4.5 0 0 0-2.091-2.091L4 13l2.096-.813a4.5 4.5 0 0 0 2.091-2.091L9 8l.813 2.096a4.5 4.5 0 0 0 2.091 2.091L14 13l-2.096.813a4.5 4.5 0 0 0-2.091 2.091ZM18.259 8.715 18 10l-.259-1.285A2.25 2.25 0 0 0 16.715 7.74L15.43 7.5l1.285-.259A2.25 2.25 0 0 0 17.74 6.215L18 4.93l.259 1.285a2.25 2.25 0 0 0 1.026 1.026l1.285.259-1.285.24a2.25 2.25 0 0 0-1.026.975ZM16.894 20.567 16.5 22l-.394-1.433a1.875 1.875 0 0 0-1.173-1.173L13.5 19l1.433-.394a1.875 1.875 0 0 0 1.173-1.173L16.5 16l.394 1.433a1.875 1.875 0 0 0 1.173 1.173L19.5 19l-1.433.394a1.875 1.875 0 0 0-1.173 1.173Z"/>
        </svg>
    @break

    @case('dashboard')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h6v6h-6zm10.5 0h6v9h-6zm-10.5 10.5h6v4.5h-6zm10.5-1.5h6v6h-6z"/>
        </svg>
    @break

    @case('chevron-right')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m9 6 6 6-6 6"/>
        </svg>
    @break

    @case('folder')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75A2.25 2.25 0 0 1 6 4.5h3.22c.596 0 1.17.237 1.591.659l1.03 1.03c.422.422.995.659 1.592.659H18A2.25 2.25 0 0 1 20.25 9v8.25A2.25 2.25 0 0 1 18 19.5H6a2.25 2.25 0 0 1-2.25-2.25V6.75z"/>
        </svg>
    @break

    @case('clipboard')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 4.5h2.25A2.25 2.25 0 0 1 20.25 6.75v11.25A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6.75A2.25 2.25 0 0 1 6 4.5h2.25"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5A1.5 1.5 0 0 1 10.5 3h3A1.5 1.5 0 0 1 15 4.5v1.5H9V4.5z"/>
        </svg>
    @break

    @case('chart')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5h15M7.5 16.5v-6m4.5 6V7.5m4.5 9v-3"/>
        </svg>
    @break

@endswitch
