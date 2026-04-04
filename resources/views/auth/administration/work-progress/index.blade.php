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
        <x-private.page-header title="Work Progress Board" subtitle="Pantau tugas, pemilik kerja, dan status penyelesaiannya dari satu tempat.">
            <x-slot:actions>
                <a href="{{ route('dashboard') }}" class="private-action-link">
                    <span>Back to dashboard</span>
                </a>

                @canany(['manage settings', 'create_work_progress'])
                    <a href="{{ route('administration.work-progress.create') }}" class="private-text-button">
                        <span>Add Progress</span>
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
            title="Progress Tracker"
            description="Gunakan halaman ini untuk memantau pekerjaan yang sedang berjalan, prioritas, dan target penyelesaiannya."
            :badge="$items->total().' records'"
        >
            <form method="GET" action="{{ route('administration.work-progress.index') }}" class="private-toolbar">
                <div class="private-search">
                    <x-form.input
                        name="search"
                        :value="$search"
                        icon="clipboard"
                        placeholder="Cari judul, owner, status, priority, atau catatan..."
                        autocomplete="off"
                    />

                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                </div>

                <div class="private-inline-actions">
                    <x-ui.button type="submit" variant="secondary" :block="false">Search</x-ui.button>

                    @if (filled($search))
                        <a href="{{ route('administration.work-progress.index') }}" class="private-action-link">Reset</a>
                    @endif
                </div>
            </form>

            @if ($items->isEmpty())
                <div class="private-panel-empty">
                    Belum ada pekerjaan yang cocok dengan filter saat ini. Tambahkan progress baru atau ubah kata kunci pencarian.
                </div>
            @else
                <div class="private-table-shell">
                    <table class="private-table">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ $sortUrl('title') }}" class="private-table-sort">
                                        <span>Work Item</span>
                                        <span aria-hidden="true">{{ $sortIcon('title') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('owner') }}" class="private-table-sort">
                                        <span>Owner</span>
                                        <span aria-hidden="true">{{ $sortIcon('owner') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('status') }}" class="private-table-sort">
                                        <span>Status</span>
                                        <span aria-hidden="true">{{ $sortIcon('status') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('priority') }}" class="private-table-sort">
                                        <span>Priority</span>
                                        <span aria-hidden="true">{{ $sortIcon('priority') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('progress') }}" class="private-table-sort">
                                        <span>Progress</span>
                                        <span aria-hidden="true">{{ $sortIcon('progress') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('target_date') }}" class="private-table-sort">
                                        <span>Target</span>
                                        <span aria-hidden="true">{{ $sortIcon('target_date') }}</span>
                                    </a>
                                </th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $entry)
                                <tr>
                                    <td>
                                        <div class="private-table-primary">{{ $entry->title }}</div>
                                        @if (filled($entry->previewText()))
                                            <div class="mt-1 private-table-muted">{{ $entry->previewText() }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ $entry->owner ?: 'Unassigned' }}</span>
                                    </td>
                                    <td>
                                        <span class="private-role-badge private-role-badge--{{ $entry->status }}">{{ \Illuminate\Support\Str::headline($entry->status) }}</span>
                                    </td>
                                    <td>
                                        <span class="private-role-badge private-role-badge--priority-{{ $entry->priority }}">{{ ucfirst($entry->priority) }}</span>
                                    </td>
                                    <td>
                                        <div class="work-progress-meter">
                                            <div class="work-progress-meter-track">
                                                <span class="work-progress-meter-fill" style="width: {{ max(0, min(100, $entry->progress)) }}%"></span>
                                            </div>
                                            <span class="work-progress-meter-label">{{ $entry->progress }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ $entry->target_date?->format('d M Y') ?: 'No target' }}</span>
                                    </td>
                                    <td>
                                        <div class="private-table-actions">
                                            @canany(['manage settings', 'update_work_progress'])
                                                <a href="{{ route('administration.work-progress.edit', $entry) }}" class="private-action-link">
                                                    Edit
                                                </a>
                                            @endcanany

                                            @canany(['manage settings', 'delete_work_progress'])
                                                <form
                                                    method="POST"
                                                    action="{{ route('administration.work-progress.destroy', $entry) }}"
                                                    onsubmit="return confirm('Delete work progress {{ $entry->title }}?')"
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
                    {{ $items->links() }}
                </div>
            @endif
        </x-private.panel>
    </div>
</x-layouts.private-module>
