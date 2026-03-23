<x-layouts.auth>
    <div class="min-h-screen bg-gray-50 p-6">
        <div class="mx-auto max-w-5xl rounded-xl bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                    <p class="mt-2 text-sm text-gray-600">
                        {{ $content ?? 'dashboard' }}
                    </p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-ui.button type="submit" variant="danger" :block="false">
                        Logout
                    </x-ui.button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.auth>
