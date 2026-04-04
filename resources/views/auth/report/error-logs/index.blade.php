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
        <x-private.page-header title="Error Timeline" subtitle="Tinjau error yang tercatat otomatis dari aplikasi untuk kebutuhan troubleshooting dan audit.">
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
            title="Error Records"
            description="Halaman ini menampilkan exception yang dicatat otomatis saat aplikasi mengalami error."
            :badge="$logs->total().' records'"
        >
            <form method="GET" action="{{ route('report.error-report.index') }}" class="private-toolbar">
                <div class="private-search">
                    <x-form.input
                        name="search"
                        :value="$search"
                        icon="clipboard"
                        placeholder="Cari class error, pesan, path, method, user, atau file..."
                        autocomplete="off"
                    />

                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                </div>

                <div class="private-inline-actions">
                    <x-ui.button type="submit" variant="secondary" :block="false">Search</x-ui.button>

                    @if (filled($search))
                        <a href="{{ route('report.error-report.index') }}" class="private-action-link">Reset</a>
                    @endif
                </div>
            </form>

            @if ($logs->isEmpty())
                <div class="private-panel-empty">
                    Belum ada error log yang cocok dengan filter saat ini. Error akan muncul otomatis jika aplikasi menangkap exception.
                </div>
            @else
                <div class="private-table-shell">
                    <table class="private-table">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ $sortUrl('exception_class') }}" class="private-table-sort">
                                        <span>Error</span>
                                        <span aria-hidden="true">{{ $sortIcon('exception_class') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('level') }}" class="private-table-sort">
                                        <span>Level</span>
                                        <span aria-hidden="true">{{ $sortIcon('level') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('path') }}" class="private-table-sort">
                                        <span>Path</span>
                                        <span aria-hidden="true">{{ $sortIcon('path') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('user_identifier') }}" class="private-table-sort">
                                        <span>User</span>
                                        <span aria-hidden="true">{{ $sortIcon('user_identifier') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('occurred_at') }}" class="private-table-sort">
                                        <span>Occurred At</span>
                                        <span aria-hidden="true">{{ $sortIcon('occurred_at') }}</span>
                                    </a>
                                </th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $entry)
                                <tr>
                                    <td>
                                        <div class="private-table-primary">{{ class_basename($entry->exception_class) }}</div>
                                        <div class="mt-1 private-table-muted">{{ $entry->message ?: 'No message' }}</div>
                                    </td>
                                    <td>
                                        <span class="private-role-badge private-role-badge--error-{{ $entry->level }}">{{ ucfirst($entry->level) }}</span>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ $entry->method ? $entry->method.' ' : '' }}{{ $entry->path ?: '-' }}</span>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ $entry->user_identifier ?: 'Guest / System' }}</span>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ $entry->occurred_at?->format('d M Y H:i') }}</span>
                                    </td>
                                    <td>
                                        <div class="private-table-actions">
                                            <a href="{{ route('report.error-report.show', $entry) }}" class="private-action-link">
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
