<x-layouts.auth>

<div class="grid md:grid-cols-2 min-h-screen">
    <!-- LEFT SIDE -->
    <div class="relative hidden md:block">
        <img src="{{ asset(config('app.auth_background', 'https://placehold.co/1920x1080')) }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40 flex items-center">
            <div class="text-white px-12">
                <h1 class="text-3xl font-bold">
                    {{ config('app.name') }}
                </h1>
                <p class="mt-3 text-sm opacity-80">
                    {{ __('Secure access to your application') }}
                </p>
            </div>
        </div>
    </div>

    <!-- RIGHT SIDE -->
    <div class="flex items-center justify-center bg-gray-50">
        <div class="w-full max-w-md p-8 bg-white shadow-xl rounded-xl">
            <!-- LOGO -->
            <div class="mb-6 text-center">
                <img src="{{ asset(config('app.logo', 'https://placehold.co/200x200')) }}" class="h-12 mx-auto">
            </div>
            <!-- TITLE -->
            <h2 class="text-lg font-semibold text-center mb-6">
                Login
            </h2>
            <!-- LIVEWIRE LOGIN -->
            @livewire('auth.login')
        </div>
    </div>
</div>

</x-layouts.auth>