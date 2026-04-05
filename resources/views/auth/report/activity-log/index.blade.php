<x-layouts.private-module :title="$title" :description="$description">
    @php
        $sortUrl = function (string $column) use ($sort, $direction) {
            $nextDirection = $sort === $column && $direction === 'asc' ? 'desc' : 'asc';

            return request()->fullUrlWithQuery([
                'sort' => $column,
                'direction' => $nextDirection,
                'page' => 1,
            ]);
        };

    @endphp

    <div class="private-page">
        <x-private.page-header title="Linimasa Aktivitas" subtitle="Tinjau histori aktivitas penting dari sistem, pengguna, dan operasional tim.">
            <x-slot:actions>
                <x-ui.back-dashboard-link />
            </x-slot:actions>
        </x-private.page-header>

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel
            title="Data Aktivitas"
            description="Halaman ini menampilkan aktivitas yang dicatat otomatis saat pengguna login, logout, atau melakukan perubahan penting di aplikasi."
            :badge="$logs->total().' data'"
        >
            <form method="GET" action="{{ route('report.activity-log.index') }}" class="private-toolbar">
                <div class="private-search">
                    <x-form.input
                        name="search"
                        :value="$search"
                        icon="clipboard"
                        placeholder="Cari aktivitas, pelaku, kategori, status, atau detail..."
                        autocomplete="off"
                    />

                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                </div>

                <div class="private-inline-actions">
                    <x-ui.search-button />

                    @if (filled($search))
                        <a href="{{ route('report.activity-log.index') }}" class="private-action-link icon-action tooltip-trigger" aria-label="Atur ulang filter" title="Atur ulang filter">
                            <x-ui.icon name="rotate" class="h-4 w-4" />
                        </a>
                    @endif
                </div>
            </form>

            @if ($logs->isEmpty())
                <div class="private-panel-empty">
                    Belum ada log aktivitas yang cocok dengan filter saat ini. Aktivitas akan muncul otomatis setelah pengguna mulai menggunakan aplikasi.
                </div>
            @else
                <x-private.data-table>
                        <thead>
                            <tr>
                                <x-private.table-sort-heading :href="$sortUrl('activity')" label="Aktivitas" :active="$sort === 'activity'" :direction="$direction" />
                                <x-private.table-sort-heading :href="$sortUrl('actor')" label="Pelaku" :active="$sort === 'actor'" :direction="$direction" />
                                <x-private.table-sort-heading :href="$sortUrl('category')" label="Kategori" :active="$sort === 'category'" :direction="$direction" />
                                <x-private.table-sort-heading :href="$sortUrl('status')" label="Status" :active="$sort === 'status'" :direction="$direction" />
                                <x-private.table-sort-heading :href="$sortUrl('logged_at')" label="Waktu Dicatat" :active="$sort === 'logged_at'" :direction="$direction" />
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $entry)
                                <tr>
                                    <td>
                                        <div class="private-table-primary">{{ $entry->activity }}</div>
                                        @if (filled($entry->previewText()))
                                            <div class="mt-1 private-table-muted">{{ $entry->previewText() }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ $entry->actor ?: 'Sistem' }}</span>
                                    </td>
                                    <td>
                                        <span class="private-role-badge private-role-badge--category-{{ $entry->category }}">{{ ucfirst($entry->category) }}</span>
                                    </td>
                                    <td>
                                        <span class="private-role-badge private-role-badge--status-{{ $entry->status }}">{{ ucfirst($entry->status) }}</span>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ $entry->logged_at?->format('d M Y H:i') }}</span>
                                    </td>
                                    <td>
                                        <div class="private-table-actions">
                                            <x-ui.detail-link :href="route('report.activity-log.show', $entry)" label="Lihat detail aktivitas" />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                </x-private.data-table>

                <div class="mt-5">
                    {{ $logs->links() }}
                </div>
            @endif
        </x-private.panel>
    </div>
</x-layouts.private-module>
