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
        <x-private.page-header :title="$title" subtitle="Master Data Management">
            <x-slot:actions>
                <a href="{{ route('dashboard') }}" class="private-action-link">
                    <span>Back to dashboard</span>
                </a>

                <a href="{{ route('master.roles.create') }}" class="private-text-button">
                    <span>Add Role</span>
                </a>
            </x-slot:actions>
        </x-private.page-header>

        @if (session('status'))
            <x-private.feedback :message="session('status')" />
        @endif

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel
            title="Daftar Role"
            description="Kelola role aplikasi, nama tampilan, dan akses module dari satu workspace."
            :badge="$roles->total().' records'"
        >
            <form method="GET" action="{{ route('master.roles.index') }}" class="private-toolbar">
                <div class="private-search">
                    <x-form.input
                        name="search"
                        :value="$search"
                        icon="folder"
                        placeholder="Cari name, display name, atau deskripsi..."
                        autocomplete="off"
                    />

                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                </div>

                <div class="private-inline-actions">
                    <x-ui.button type="submit" variant="secondary" :block="false">Search</x-ui.button>

                    @if (filled($search))
                        <a href="{{ route('master.roles.index') }}" class="private-action-link">Reset</a>
                    @endif
                </div>
            </form>

            @if ($roles->isEmpty())
                <div class="private-panel-empty">
                    Belum ada role yang cocok dengan filter saat ini. Tambahkan role baru atau ubah kata kunci pencarian.
                </div>
            @else
                <div class="private-table-shell">
                    <table class="private-table">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ $sortUrl('display_name') }}" class="private-table-sort">
                                        <span>Role</span>
                                        <span aria-hidden="true">{{ $sortIcon('display_name') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('name') }}" class="private-table-sort">
                                        <span>Name</span>
                                        <span aria-hidden="true">{{ $sortIcon('name') }}</span>
                                    </a>
                                </th>
                                <th>Modules</th>
                                <th>
                                    <a href="{{ $sortUrl('users_count') }}" class="private-table-sort">
                                        <span>Users</span>
                                        <span aria-hidden="true">{{ $sortIcon('users_count') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('is_system') }}" class="private-table-sort">
                                        <span>Status</span>
                                        <span aria-hidden="true">{{ $sortIcon('is_system') }}</span>
                                    </a>
                                </th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $managedRole)
                                <tr>
                                    <td>
                                        <div class="private-table-primary">
                                            {{ $managedRole->display_name ?: $managedRole->name }}
                                        </div>

                                        @if (filled($managedRole->description))
                                            <div class="mt-1 private-table-muted">
                                                {{ \Illuminate\Support\Str::limit(trim(strip_tags($managedRole->description)), 120) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ $managedRole->name }}</span>
                                    </td>
                                    <td>
                                        <div class="private-role-list">
                                            @forelse ($managedRole->modules as $module)
                                                <span class="private-role-badge">{{ $module->name }}</span>
                                            @empty
                                                <span class="private-table-muted">No module assigned</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ number_format($managedRole->users_count) }} user(s)</span>
                                    </td>
                                    <td>
                                        @if ($managedRole->is_system)
                                            <span class="private-role-badge">System</span>
                                        @else
                                            <span class="private-table-muted">Custom</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="private-table-actions">
                                            @if ($managedRole->is_system)
                                                <span class="private-table-muted">Protected role</span>
                                            @else
                                                <a href="{{ route('master.roles.edit', $managedRole) }}" class="private-action-link">
                                                    Edit
                                                </a>

                                                <form
                                                    method="POST"
                                                    action="{{ route('master.roles.destroy', $managedRole) }}"
                                                    onsubmit="return confirm('Delete role {{ $managedRole->name }}?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')

                                                    <x-ui.button type="submit" variant="danger" :block="false">
                                                        Delete
                                                    </x-ui.button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-5">
                    {{ $roles->links() }}
                </div>
            @endif
        </x-private.panel>
    </div>
</x-layouts.private-module>
