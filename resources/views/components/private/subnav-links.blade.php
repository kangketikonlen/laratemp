@props(['parentTitle', 'items' => collect()])

<x-private.panel :title="'Pilih halaman di ' . $parentTitle"
    description="Buka salah satu halaman berikut untuk melanjutkan pekerjaan Anda di section ini." :badge="$items->count() . ' halaman'"
    {{ $attributes->class(['mt-6 sm:mt-8']) }}>
    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($items as $item)
            @php
                $hasRoute = filled($item->route_name) && \Illuminate\Support\Facades\Route::has($item->route_name);
                $itemDescription = filled($item->description)
                    ? $item->description
                    : 'Buka halaman ' . $item->name . ' dari section ' . $parentTitle . '.';
            @endphp

            <x-private.workspace-tile :title="$item->name" :description="$itemDescription" :href="$hasRoute ? route($item->route_name) : null" :suffix-icon="$hasRoute" />
        @endforeach
    </div>
</x-private.panel>
