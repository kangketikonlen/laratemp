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
        <x-private.page-header title="Linimasa Changelog"
            subtitle="Catat rilis, pembaruan, dan perubahan penting aplikasi dalam satu tempat.">
            <x-slot:actions>
                <x-ui.back-dashboard-link />

                @canany(['manage settings', 'create_changelogs'])
                    <x-ui.add-link :href="route('administration.changelogs.create')" label="Tambah changelog" />
                @endcanany
            </x-slot:actions>
        </x-private.page-header>

        @if (session('status'))
            <x-private.feedback :message="session('status')" />
        @endif

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel title="Catatan Rilis"
            description="Gunakan changelog untuk membantu tim memahami apa yang berubah pada setiap versi."
            :badge="$changelogs->total() . ' data'">
            <form method="GET" action="{{ route('administration.changelogs.index') }}" class="private-toolbar">
                <div class="private-search">
                    <x-form.input name="search" :value="$search" icon="clipboard"
                        placeholder="Cari versi, judul, status, atau isi release note..." autocomplete="off" />

                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                </div>

                <div class="private-inline-actions">
                    <x-ui.search-button />

                    @if (filled($search))
                        <a href="{{ route('administration.changelogs.index') }}"
                            class="private-action-link icon-action tooltip-trigger" aria-label="Atur ulang filter"
                            title="Atur ulang filter">
                            <x-ui.icon name="rotate" class="h-4 w-4" />
                        </a>
                    @endif
                </div>
            </form>

            @if ($changelogs->isEmpty())
                <div class="private-panel-empty">
                    Belum ada changelog yang cocok dengan filter saat ini. Tambahkan catatan rilis baru atau ubah kata
                    kunci pencarian.
                </div>
            @else
                <x-private.data-table>
                    <thead>
                        <tr>
                            <x-private.table-sort-heading :href="$sortUrl('version')" label="Versi" :active="$sort === 'version'"
                                :direction="$direction" />
                            <x-private.table-sort-heading :href="$sortUrl('title')" label="Judul" :active="$sort === 'title'"
                                :direction="$direction" />
                            <x-private.table-sort-heading :href="$sortUrl('status')" label="Status" :active="$sort === 'status'"
                                :direction="$direction" />
                            <x-private.table-sort-heading :href="$sortUrl('released_at')" label="Dirilis" :active="$sort === 'released_at'"
                                :direction="$direction" />
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($changelogs as $entry)
                            <tr>
                                <td>
                                    <div class="private-table-primary">{{ $entry->version }}</div>
                                </td>
                                <td>
                                    <div class="private-table-primary">{{ $entry->title }}</div>
                                    @if (filled($entry->previewText()))
                                        <div class="mt-1 private-table-muted">{{ $entry->previewText() }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="private-role-badge private-role-badge--{{ $entry->status }}">{{ ['draft' => 'Draf', 'published' => 'Dipublikasikan', 'archived' => 'Diarsipkan'][$entry->status] ?? \Illuminate\Support\Str::headline($entry->status) }}</span>
                                </td>
                                <td>
                                    <span
                                        class="private-table-muted">{{ $entry->released_at?->format('d M Y') ?: 'Belum dijadwalkan' }}</span>
                                </td>
                                <td>
                                    <div class="private-table-actions">
                                        @canany(['manage settings', 'update_changelogs'])
                                            <x-ui.edit-link :href="route('administration.changelogs.edit', $entry)" label="Ubah changelog" />
                                        @endcanany

                                        @canany(['manage settings', 'delete_changelogs'])
                                            <x-ui.delete-button :action="route('administration.changelogs.destroy', $entry)" label="Hapus changelog"
                                                confirm="Hapus changelog {{ $entry->version }}?" />
                                        @endcanany
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-private.data-table>

                <div class="mt-5">
                    {{ $changelogs->links() }}
                </div>
            @endif
        </x-private.panel>
    </div>
</x-layouts.private-module>
