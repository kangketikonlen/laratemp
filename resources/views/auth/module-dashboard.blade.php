<x-layouts.private-module :title="$module->name" :description="$module->description">
    <div class="private-page">
        <x-private.page-header :title="$module->name" subtitle="Home">
            <x-slot:actions>
                <a href="{{ route('dashboard') }}" class="private-icon-button">
                    <x-ui.icon name="dashboard" class="h-4 w-4" />
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="private-text-button">Logout</button>
                </form>
            </x-slot:actions>
        </x-private.page-header>

        <x-private.notice
            :title="'Selamat datang di dashboard '.$module->name.', '.(auth()->user()?->name ?? auth()->user()?->username).'.'"
            :message="$module->description.' Gunakan menu di sidebar untuk berpindah antar section, atau pilih section utama dari ringkasan berikut.'"
        />

        <x-private.panel
            title="Section Utama"
            description="Ringkasan area kerja yang tersedia di dalam module ini."
            :badge="$navigationItems->count().' section'.($navigationItems->count() === 1 ? '' : 's')"
        >
            @if ($navigationItems->isEmpty())
                <div class="private-panel-empty">
                    No sections are available for this module yet.
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($navigationItems as $item)
                        <x-private.workspace-tile
                            :title="$item->name"
                            :description="$item->description"
                            :href="filled($item->route_name) && \Illuminate\Support\Facades\Route::has($item->route_name) ? route($item->route_name) : null"
                            :suffix-icon="true"
                        />
                    @endforeach
                </div>
            @endif
        </x-private.panel>
    </div>
</x-layouts.private-module>
