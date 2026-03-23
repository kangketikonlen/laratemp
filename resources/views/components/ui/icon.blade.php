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
        <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }} fill="none" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5 9.75 7.5 9.75 7.5-3.75 7.5-9.75 7.5S2.25 12 2.25 12z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15a3 3 0 100-6 3 3 0 000 6z"/>
        </svg>
    @break

@endswitch