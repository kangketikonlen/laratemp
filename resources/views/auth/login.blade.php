<x-layouts.auth :title="$page->appName" :description="$page->appDescription">
    <div class="min-h-screen bg-gray-50 md:grid md:grid-cols-[1.2fr_0.8fr] xl:grid-cols-[1.35fr_0.65fr]">
        <div class="relative min-h-[30vh] overflow-hidden md:min-h-screen md:rounded-r-3xl md:shadow-2xl">
            <img src="{{ $page->background }}" class="h-full w-full object-cover">
            <div class="absolute inset-0 flex items-end bg-black/40 md:items-center">
                <div class="px-6 py-8 sm:px-8 md:px-12 md:py-0 lg:px-16">
                    <div
                        class="max-w-2xl rounded-2xl bg-slate-950/55 px-6 py-5 text-white backdrop-blur-sm sm:px-7 sm:py-6">
                        <h1 class="text-3xl font-bold">
                            {{ $page->appName }}
                        </h1>
                        <p class="mt-2 text-lg font-medium text-white/90 sm:text-xl">
                            {{ $page->institutionName }}
                        </p>
                        <p class="mt-4 text-sm leading-6 text-white/80 sm:text-base">
                            {{ $page->appDescription }}
                        </p>
                        <p class="mt-5 text-xs leading-5 text-white/65 sm:text-sm">
                            {{ $page->copyright }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-center px-5 py-8 sm:px-6 sm:py-10 md:px-8 lg:px-10">
            <div class="w-full max-w-md sm:max-w-lg md:max-w-md">
                <div class="mb-6 text-center md:mb-7">
                    <img src="{{ $page->logo }}" class="mx-auto h-40 rounded-3xl object-cover">
                </div>

                <h2 class="mb-6 text-center text-lg font-semibold sm:text-xl">
                    Silahkan login untuk masuk ke sistem
                </h2>

                @livewire('auth.login', ['copyright' => $page->copyright])
            </div>
        </div>
    </div>
</x-layouts.auth>
