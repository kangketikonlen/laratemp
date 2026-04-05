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
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 7.5h15"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 16.5h15"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 7.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 16.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
        </svg>
    @break

    @case('sliders')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 4.5v6m0 0a2.25 2.25 0 1 0 0 4.5m0-4.5A2.25 2.25 0 1 1 6 15m0 0v4.5m12-15v2.25m0 0a2.25 2.25 0 1 0 0 4.5m0-4.5A2.25 2.25 0 1 1 18 11.25m0 0v8.25M12 4.5v10.5m0 0a2.25 2.25 0 1 0 0 4.5m0-4.5A2.25 2.25 0 1 1 12 19.5"/>
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

    @case('chevron-left')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m15 6-6 6 6 6"/>
        </svg>
    @break

    @case('arrow-left')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
        </svg>
    @break

    @case('arrow-right')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
        </svg>
    @break

    @case('plus')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
    @break

    @case('plus-circle')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
        </svg>
    @break

    @case('search')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m1.35-5.15a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z"/>
        </svg>
    @break

    @case('rotate')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992V4.356M2.985 19.644v-4.992h4.992m11.181-2.658a7.5 7.5 0 0 0-12.728-5.304L3 9.348m18 5.304-3.43 3.43a7.5 7.5 0 0 1-12.728-5.304"/>
        </svg>
    @break

    @case('arrows-up-down')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 6 3.75-3.75L15.75 6"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 18-3.75 3.75L8.25 18"/>
        </svg>
    @break

    @case('arrow-up-small')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m7.5 10.5 4.5-4.5 4.5 4.5"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18V6.75"/>
        </svg>
    @break

    @case('arrow-down-small')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.5 13.5-4.5 4.5-4.5-4.5"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v11.25"/>
        </svg>
    @break

    @case('pencil')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a2.25 2.25 0 1 1 3.182 3.182L10.582 17.13a4.5 4.5 0 0 1-1.897 1.13L6 19l.74-2.685a4.5 4.5 0 0 1 1.13-1.897l8.992-8.931ZM19.5 7.125 16.875 4.5"/>
        </svg>
    @break

    @case('pencil-square')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 1 1 3.182 3.182L9.75 16.963 6 18l1.037-3.75L16.862 3.487Z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12v6.75A2.25 2.25 0 0 1 18.75 21H5.25A2.25 2.25 0 0 1 3 18.75V5.25A2.25 2.25 0 0 1 5.25 3H12"/>
        </svg>
    @break

    @case('trash')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0V4.875A2.25 2.25 0 0 0 13.5 2.625h-3a2.25 2.25 0 0 0-2.25 2.25v.518m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
        </svg>
    @break

    @case('logout')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m-3 0 3-3m0 0-3-3m3 3H9"/>
        </svg>
    @break

    @case('door-open')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 3.75H6.75A2.25 2.25 0 0 0 4.5 6v12a2.25 2.25 0 0 0 2.25 2.25h6.75"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 12h10.5m0 0-3.75-3.75M21 12l-3.75 3.75"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 3.75v16.5"/>
        </svg>
    @break

    @case('save')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75H6.75A2.25 2.25 0 0 0 4.5 6v12A2.25 2.25 0 0 0 6.75 20.25h10.5A2.25 2.25 0 0 0 19.5 18V6.75L16.5 3.75ZM8.25 3.75v4.5h6v-4.5m-6 12h7.5"/>
        </svg>
    @break

    @case('x-mark')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
        </svg>
    @break

    @case('x-circle')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="m14.25 9.75-4.5 4.5m0-4.5 4.5 4.5"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
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

    @case('document-text')
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-8.625a2.625 2.625 0 0 0-2.625-2.625H7.125A2.625 2.625 0 0 0 4.5 5.625v12.75A2.625 2.625 0 0 0 7.125 21h5.625"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5h7.5M8.25 11.25h7.5M8.25 15h4.5"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M18 21l3-3m0 0-3-3m3 3h-6"/>
        </svg>
    @break

@endswitch
