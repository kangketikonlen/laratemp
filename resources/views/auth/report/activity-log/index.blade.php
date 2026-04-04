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
        <x-private.page-header title="Activity Timeline" subtitle="Tinjau histori aktivitas penting dari sistem, user, dan operasional tim.">
            <x-slot:actions>
                <a href="{{ route('dashboard') }}" class="private-action-link">
                    <span>Back to dashboard</span>
                </a>
            </x-slot:actions>
        </x-private.page-header>

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel
            title="Activity Records"
            description="Halaman ini menampilkan aktivitas yang dicatat otomatis saat user login, logout, atau melakukan perubahan penting di aplikasi."
            :badge="$logs->total().' records'"
        >
            <form method="GET" action="{{ route('report.activity-log.index') }}" class="private-toolbar">
                <div class="private-search">
                    <x-form.input
                        name="search"
                        :value="$search"
                        icon="clipboard"
                        placeholder="Cari aktivitas, actor, category, status, atau detail..."
                        autocomplete="off"
                    />

                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                </div>

                <div class="private-inline-actions">
                    <x-ui.button type="submit" variant="secondary" :block="false">Search</x-ui.button>

                    @if (filled($search))
                        <a href="{{ route('report.activity-log.index') }}" class="private-action-link">Reset</a>
                    @endif
                </div>
            </form>

            @if ($logs->isEmpty())
                <div class="private-panel-empty">
                    Belum ada activity log yang cocok dengan filter saat ini. Aktivitas akan muncul otomatis setelah user mulai menggunakan aplikasi.
                </div>
            @else
                <div class="private-table-shell">
                    <table class="private-table">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ $sortUrl('activity') }}" class="private-table-sort">
                                        <span>Activity</span>
                                        <span aria-hidden="true">{{ $sortIcon('activity') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('actor') }}" class="private-table-sort">
                                        <span>Actor</span>
                                        <span aria-hidden="true">{{ $sortIcon('actor') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('category') }}" class="private-table-sort">
                                        <span>Category</span>
                                        <span aria-hidden="true">{{ $sortIcon('category') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('status') }}" class="private-table-sort">
                                        <span>Status</span>
                                        <span aria-hidden="true">{{ $sortIcon('status') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('logged_at') }}" class="private-table-sort">
                                        <span>Logged At</span>
                                        <span aria-hidden="true">{{ $sortIcon('logged_at') }}</span>
                                    </a>
                                </th>
                                <th class="text-right">Action</th>
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
                                        <span class="private-table-muted">{{ $entry->actor ?: 'System' }}</span>
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
                                            <a href="{{ route('report.activity-log.show', $entry) }}" class="private-action-link">
                                                Detail
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-5">
                    {{ $logs->links() }}
                </div>
            @endif
        </x-private.panel>
    </div>
</x-layouts.private-module>
