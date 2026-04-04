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

                <a href="{{ route('master.users.create') }}" class="private-text-button">
                    <span>Add User</span>
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
            title="Daftar User"
            description="Kelola akun aplikasi, identitas login, dan assignment role dari satu tempat."
            :badge="$users->total().' records'"
        >
            <form method="GET" action="{{ route('master.users.index') }}" class="private-toolbar">
                <div class="private-search">
                    <x-form.input
                        name="search"
                        :value="$search"
                        icon="user"
                        placeholder="Cari nama, username, atau email..."
                        autocomplete="off"
                    />

                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                </div>

                <div class="private-inline-actions">
                    <x-ui.button type="submit" variant="secondary" :block="false">Search</x-ui.button>

                    @if (filled($search))
                        <a href="{{ route('master.users.index') }}" class="private-action-link">Reset</a>
                    @endif
                </div>
            </form>

            @if ($users->isEmpty())
                <div class="private-panel-empty">
                    Belum ada user yang cocok dengan filter saat ini. Tambahkan user baru atau ubah kata kunci pencarian.
                </div>
            @else
                <div class="private-table-shell">
                    <table class="private-table">
                        <thead>
                            <tr>
                                <th>
                                    <a href="{{ $sortUrl('name') }}" class="private-table-sort">
                                        <span>User</span>
                                        <span aria-hidden="true">{{ $sortIcon('name') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('username') }}" class="private-table-sort">
                                        <span>Username</span>
                                        <span aria-hidden="true">{{ $sortIcon('username') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('email') }}" class="private-table-sort">
                                        <span>Email</span>
                                        <span aria-hidden="true">{{ $sortIcon('email') }}</span>
                                    </a>
                                </th>
                                <th>Role</th>
                                <th>
                                    <a href="{{ $sortUrl('created_at') }}" class="private-table-sort">
                                        <span>Dibuat</span>
                                        <span aria-hidden="true">{{ $sortIcon('created_at') }}</span>
                                    </a>
                                </th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $managedUser)
                                <tr>
                                    <td>
                                        <div class="private-table-primary">{{ $managedUser->name }}</div>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ '@'.$managedUser->username }}</span>
                                    </td>
                                    <td>{{ $managedUser->email }}</td>
                                    <td>
                                        <div class="private-role-list">
                                            @forelse ($managedUser->roles as $role)
                                                <span class="private-role-badge">
                                                    {{ $role->display_name ?? $role->name }}
                                                </span>
                                            @empty
                                                <span class="private-table-muted">No role assigned</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ $managedUser->created_at?->format('d M Y') }}</span>
                                    </td>
                                    <td>
                                        <div class="private-table-actions">
                                            <a href="{{ route('master.users.edit', $managedUser) }}" class="private-action-link">
                                                Edit
                                            </a>

                                            <form
                                                method="POST"
                                                action="{{ route('master.users.destroy', $managedUser) }}"
                                                onsubmit="return confirm('Delete user {{ $managedUser->username }}?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <x-ui.button type="submit" variant="danger" :block="false">
                                                    Delete
                                                </x-ui.button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-5">
                    {{ $users->links() }}
                </div>
            @endif
        </x-private.panel>
    </div>
</x-layouts.private-module>
