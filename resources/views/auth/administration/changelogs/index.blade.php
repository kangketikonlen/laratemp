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

        $sortIcon = function (string $column) use ($sort, $direction) {
            if ($sort !== $column) {
                return '<>';
            }

            return $direction === 'asc' ? '^' : 'v';
        };
    @endphp

    <div class="private-page">
        <x-private.page-header title="Changelog Timeline" subtitle="Catat rilis, pembaruan, dan perubahan penting aplikasi dalam satu tempat.">
            <x-slot:actions>
                <a href="{{ route('general') }}" class="private-action-link">
                    <span>Back to module</span>
                </a>

                @canany(['manage settings', 'create_changelogs'])
                    <a href="{{ route('administration.changelogs.create') }}" class="private-text-button">
                        <span>Add Changelog</span>
                    </a>
                @endcanany
            </x-slot:actions>
        </x-private.page-header>

        @if (session('status'))
            <x-private.feedback :message="session('status')" />
        @endif

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel
            title="Release Notes"
            description="Gunakan changelog untuk membantu tim memahami apa yang berubah pada setiap versi."
            :badge="$changelogs->total().' records'"
        >
            <form method="GET" action="{{ route('administration.changelogs.index') }}" class="private-toolbar">
                <div class="private-search">
                    <x-form.input
                        name="search"
                        :value="$search"
                        icon="clipboard"
                        placeholder="Cari versi, judul, status, atau isi release note..."
                        autocomplete="off"
                    />

                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                </div>

                <div class="private-inline-actions">
                    <x-ui.button type="submit" variant="secondary" :block="false">Search</x-ui.button>

                    @if (filled($search))
                        <a href="{{ route('administration.changelogs.index') }}" class="private-action-link">Reset</a>
                    @endif
                </div>
            </form>

            @if ($changelogs->isEmpty())
                <div class="private-panel-empty">
                    Belum ada changelog yang cocok dengan filter saat ini. Tambahkan catatan rilis baru atau ubah kata kunci pencarian.
                </div>
            @else
                <div class="private-table-shell">
                    <table class="private-table">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ $sortUrl('version') }}" class="private-table-sort">
                                        <span>Version</span>
                                        <span aria-hidden="true">{{ $sortIcon('version') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('title') }}" class="private-table-sort">
                                        <span>Title</span>
                                        <span aria-hidden="true">{{ $sortIcon('title') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('status') }}" class="private-table-sort">
                                        <span>Status</span>
                                        <span aria-hidden="true">{{ $sortIcon('status') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('released_at') }}" class="private-table-sort">
                                        <span>Released</span>
                                        <span aria-hidden="true">{{ $sortIcon('released_at') }}</span>
                                    </a>
                                </th>
                                <th class="text-right">Action</th>
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
                                        <span class="private-role-badge private-role-badge--{{ $entry->status }}">{{ \Illuminate\Support\Str::headline($entry->status) }}</span>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ $entry->released_at?->format('d M Y') ?: 'Not scheduled' }}</span>
                                    </td>
                                    <td>
                                        <div class="private-table-actions">
                                            @canany(['manage settings', 'update_changelogs'])
                                                <a href="{{ route('administration.changelogs.edit', $entry) }}" class="private-action-link">
                                                    Edit
                                                </a>
                                            @endcanany

                                            @canany(['manage settings', 'delete_changelogs'])
                                                <form
                                                    method="POST"
                                                    action="{{ route('administration.changelogs.destroy', $entry) }}"
                                                    onsubmit="return confirm('Delete changelog {{ $entry->version }}?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <x-ui.button type="submit" variant="danger" :block="false">
                                                        Delete
                                                    </x-ui.button>
                                                </form>
                                            @endcanany
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-5">
                    {{ $changelogs->links() }}
                </div>
            @endif
        </x-private.panel>
    </div>
</x-layouts.private-module>
