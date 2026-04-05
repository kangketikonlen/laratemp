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
        <x-private.page-header title="Linimasa Error" subtitle="Tinjau error yang tercatat otomatis dari aplikasi untuk kebutuhan penelusuran masalah dan audit.">
            <x-slot:actions>
                <x-ui.back-dashboard-link />
            </x-slot:actions>
        </x-private.page-header>

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel
            title="Data Error"
            description="Halaman ini menampilkan exception yang dicatat otomatis saat aplikasi mengalami error."
            :badge="$logs->total().' data'"
        >
            <form method="GET" action="{{ route('report.error-report.index') }}" class="private-toolbar">
                <div class="private-search">
                    <x-form.input
                        name="search"
                        :value="$search"
                        icon="clipboard"
                        placeholder="Cari kelas error, pesan, path, metode, pengguna, atau file..."
                        autocomplete="off"
                    />

                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                </div>

                <div class="private-inline-actions">
                    <x-ui.search-button />

                    @if (filled($search))
                        <a href="{{ route('report.error-report.index') }}" class="private-action-link icon-action tooltip-trigger" aria-label="Atur ulang filter" title="Atur ulang filter">
                            <x-ui.icon name="rotate" class="h-4 w-4" />
                        </a>
                    @endif
                </div>
            </form>

            @if ($logs->isEmpty())
                <div class="private-panel-empty">
                    Belum ada log error yang cocok dengan filter saat ini. Error akan muncul otomatis jika aplikasi menangkap exception.
                </div>
            @else
                <x-private.data-table>
                        <thead>
                            <tr>
                                <x-private.table-sort-heading :href="$sortUrl('exception_class')" label="Error" :active="$sort === 'exception_class'" :direction="$direction" />
                                <x-private.table-sort-heading :href="$sortUrl('level')" label="Level" :active="$sort === 'level'" :direction="$direction" />
                                <x-private.table-sort-heading :href="$sortUrl('path')" label="Path" :active="$sort === 'path'" :direction="$direction" />
                                <x-private.table-sort-heading :href="$sortUrl('user_identifier')" label="User" :active="$sort === 'user_identifier'" :direction="$direction" />
                                <x-private.table-sort-heading :href="$sortUrl('occurred_at')" label="Waktu Kejadian" :active="$sort === 'occurred_at'" :direction="$direction" />
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $entry)
                                <tr>
                                    <td>
                                        <div class="private-table-primary">{{ class_basename($entry->exception_class) }}</div>
                                        <div class="mt-1 private-table-muted">{{ $entry->message ?: 'Tanpa pesan' }}</div>
                                    </td>
                                    <td>
                                        <span class="private-role-badge private-role-badge--error-{{ $entry->level }}">{{ ucfirst($entry->level) }}</span>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ $entry->method ? $entry->method.' ' : '' }}{{ $entry->path ?: '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ $entry->user_identifier ?: 'Tamu / Sistem' }}</span>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ $entry->occurred_at?->format('d M Y H:i') }}</span>
                                    </td>
                                    <td>
                                        <div class="private-table-actions">
                                            <x-ui.detail-link :href="route('report.error-report.show', $entry)" label="Lihat detail error" />
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
