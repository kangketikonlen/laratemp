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
        <x-private.page-header title="Role Access" subtitle="Pilih role yang ingin diatur, lalu buka checklist aksesnya.">
            <x-slot:actions>
                <a href="{{ route('dashboard') }}" class="private-action-link">
                    <span>Back to dashboard</span>
                </a>

                @can('manage settings')
                    <a href="{{ route('settings.permissions.create') }}" class="private-text-button">
                        <span>Add Custom Access</span>
                    </a>
                @endcan
            </x-slot:actions>
        </x-private.page-header>

        @if (session('status'))
            <x-private.feedback :message="session('status')" />
        @endif

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <section class="institution-settings-hero">
            <div>
                <p class="institution-settings-kicker">Access Flow</p>
                <h2 class="institution-settings-title">Start With Roles, Then Manage Their Access</h2>
                <p class="institution-settings-copy">
                    Halaman ini menampilkan semua role yang ada di sistem. Buka salah satu role untuk mencentang akses yang boleh mereka gunakan.
                </p>
            </div>

            <div class="institution-settings-hero-grid">
                <div class="institution-settings-stat">
                    <span class="institution-settings-stat-label">System Access</span>
                    <span class="institution-settings-stat-value">{{ $catalogPermissionsCount }} standard actions</span>
                </div>
                <div class="institution-settings-stat">
                    <span class="institution-settings-stat-label">Available Roles</span>
                    <span class="institution-settings-stat-value">{{ $roles->total() }} roles</span>
                </div>
            </div>
        </section>

        @can('manage settings')
            <x-private.notice
                title="Advanced Access Tools"
                message="Custom access rules hanya ditampilkan untuk administrator tingkat lanjut. Admin biasa cukup memakai flow Manage Access pada role atau user."
            />
        @endcan

        <x-private.panel
            title="Role Access Directory"
            description="Kelola akses berdasarkan role agar pengaturan user tetap rapi dan konsisten."
            :badge="$roles->total().' records'"
        >
            <form method="GET" action="{{ route('settings.permissions.index') }}" class="private-toolbar">
                <div class="private-search">
                    <x-form.input
                        name="search"
                        :value="$search"
                        icon="settings"
                        placeholder="Cari role, display name, atau deskripsi..."
                        autocomplete="off"
                    />

                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                </div>

                <div class="private-inline-actions">
                    <x-ui.button type="submit" variant="secondary" :block="false">Search</x-ui.button>

                    @if (filled($search))
                        <a href="{{ route('settings.permissions.index') }}" class="private-action-link">Reset</a>
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
                                        <span>System Name</span>
                                        <span aria-hidden="true">{{ $sortIcon('name') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('permissions_count') }}" class="private-table-sort">
                                        <span>Access</span>
                                        <span aria-hidden="true">{{ $sortIcon('permissions_count') }}</span>
                                    </a>
                                </th>
                                <th>
                                    <a href="{{ $sortUrl('users_count') }}" class="private-table-sort">
                                        <span>Users</span>
                                        <span aria-hidden="true">{{ $sortIcon('users_count') }}</span>
                                    </a>
                                </th>
                                <th>Status</th>
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

                                        <div class="mt-1 private-table-muted">
                                            {{ filled($managedRole->description) ? \Illuminate\Support\Str::limit(trim(strip_tags($managedRole->description)), 120) : 'Role tanpa deskripsi tambahan.' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mt-1 permission-library-code">
                                            {{ $managedRole->name }} · {{ $managedRole->guard_name }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">
                                            {{ number_format($managedRole->permissions_count) }} access rule(s)
                                        </span>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">
                                            {{ number_format($managedRole->users_count) }} user(s)
                                        </span>
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
                                            <a href="{{ route('settings.permissions.roles.edit', $managedRole) }}" class="private-action-link">
                                                Manage Access
                                            </a>
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

        <x-private.panel
            title="Direct User Access Directory"
            description="Gunakan akses langsung per user hanya untuk kebutuhan khusus yang tidak cocok dikelola dari role."
            :badge="$users->total().' records'"
        >
            @if ($users->isEmpty())
                <div class="private-panel-empty">
                    Belum ada user yang cocok dengan filter saat ini.
                </div>
            @else
                <div class="private-table-shell">
                    <table class="private-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Direct Access</th>
                                <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $managedUser)
                                <tr>
                                    <td>
                                        <div class="private-table-primary">{{ $managedUser->name }}</div>
                                        <div class="mt-1 private-table-muted">{{ $managedUser->email }}</div>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ '@'.$managedUser->username }}</span>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ number_format($managedUser->roles_count) }} role(s)</span>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ number_format($managedUser->permissions_count) }} direct access rule(s)</span>
                                    </td>
                                    <td>
                                        <div class="private-table-actions">
                                            <a href="{{ route('settings.permissions.users.edit', $managedUser) }}" class="private-action-link">
                                                Manage Direct Access
                                            </a>
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
