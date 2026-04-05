<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header :title="$title" subtitle="Dashboard Bagian">
            <x-slot:actions>
                <x-ui.back-dashboard-link />
            </x-slot:actions>
        </x-private.page-header>

        <x-private.notice
            :title="'Anda sedang berada di section '.$title.'.'"
            :message="$description.' Gunakan sidebar untuk berpindah ke section lain atau ke sub navigation yang tersedia.'"
        />
    </div>
</x-layouts.private-module>
