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
        <x-private.page-header title="Akses Role" subtitle="Pilih role yang ingin diatur, lalu buka checklist aksesnya.">
            <x-slot:actions>
                <x-ui.back-dashboard-link />

                @can('manage settings')
                    <x-ui.add-link :href="route('settings.permissions.create')" label="Tambah akses kustom" />
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
                <p class="institution-settings-kicker">Alur Akses</p>
                <h2 class="institution-settings-title">Mulai dari Role, Lalu Kelola Aksesnya</h2>
                <p class="institution-settings-copy">
                    Halaman ini menampilkan semua role yang ada di sistem. Buka salah satu role untuk mencentang akses yang boleh mereka gunakan.
                </p>
            </div>

            <div class="institution-settings-hero-grid">
                <div class="institution-settings-stat">
                    <span class="institution-settings-stat-label">Akses Sistem</span>
                    <span class="institution-settings-stat-value">{{ $catalogPermissionsCount }} aksi standar</span>
                </div>
                <div class="institution-settings-stat">
                    <span class="institution-settings-stat-label">Role Tersedia</span>
                    <span class="institution-settings-stat-value">{{ $roles->total() }} role</span>
                </div>
            </div>
        </section>

        @can('manage settings')
            <x-private.notice title="Alat Akses Lanjutan" message="Aturan akses kustom hanya ditampilkan untuk administrator tingkat lanjut. Admin biasa cukup memakai alur kelola akses pada role atau pengguna." />
        @endcan

        <x-private.panel title="Daftar Akses Role" description="Kelola akses berdasarkan role agar pengaturan pengguna tetap rapi dan konsisten." :badge="$roles->total().' data'">
            <form method="GET" action="{{ route('settings.permissions.index') }}" class="private-toolbar">
                <div class="private-search">
                    <x-form.input name="search" :value="$search" icon="settings" placeholder="Cari role, display name, atau deskripsi..." autocomplete="off" />

                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                </div>

                <div class="private-inline-actions">
                    <x-ui.search-button />

                    @if (filled($search))
                        <a href="{{ route('settings.permissions.index') }}" class="private-action-link icon-action tooltip-trigger" aria-label="Atur ulang filter" title="Atur ulang filter">
                            <x-ui.icon name="rotate" class="h-4 w-4" />
                        </a>
                    @endif
                </div>
            </form>

            @if ($roles->isEmpty())
                <div class="private-panel-empty">
                    Belum ada role yang cocok dengan filter saat ini. Tambahkan role baru atau ubah kata kunci pencarian.
                </div>
            @else
                <x-private.data-table>
                        <thead>
                            <tr>
                                <x-private.table-sort-heading :href="$sortUrl('display_name')" label="Role" :active="$sort === 'display_name'" :direction="$direction" />
                                <x-private.table-sort-heading :href="$sortUrl('name')" label="Nama Sistem" :active="$sort === 'name'" :direction="$direction" />
                                <x-private.table-sort-heading :href="$sortUrl('permissions_count')" label="Access" :active="$sort === 'permissions_count'" :direction="$direction" />
                                <x-private.table-sort-heading :href="$sortUrl('users_count')" label="Pengguna" :active="$sort === 'users_count'" :direction="$direction" />
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
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
                                            {{ number_format($managedRole->permissions_count) }} aturan akses
                                        </span>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">
                                            {{ number_format($managedRole->users_count) }} pengguna
                                        </span>
                                    </td>
                                    <td>
                                        @if ($managedRole->is_system)
                                            <span class="private-role-badge">Sistem</span>
                                        @else
                                            <span class="private-table-muted">Kustom</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="private-table-actions">
                                            <x-ui.manage-link :href="route('settings.permissions.roles.edit', $managedRole)" label="Kelola akses role" />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                </x-private.data-table>

                <div class="mt-5">
                    {{ $roles->links() }}
                </div>
            @endif
        </x-private.panel>

        <x-private.panel title="Daftar Akses Langsung Pengguna" description="Gunakan akses langsung per pengguna hanya untuk kebutuhan khusus yang tidak cocok dikelola dari role." :badge="$users->total().' data'">
            @if ($users->isEmpty())
                <div class="private-panel-empty">
                    Belum ada pengguna yang cocok dengan filter saat ini.
                </div>
            @else
                <x-private.data-table>
                        <thead>
                            <tr>
                                <th>Pengguna</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Akses Langsung</th>
                                <th class="text-right">Aksi</th>
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
                                        <span class="private-table-muted">{{ number_format($managedUser->roles_count) }} role</span>
                                    </td>
                                    <td>
                                        <span class="private-table-muted">{{ number_format($managedUser->permissions_count) }} aturan akses langsung</span>
                                    </td>
                                    <td>
                                        <div class="private-table-actions">
                                            <x-ui.manage-link :href="route('settings.permissions.users.edit', $managedUser)" label="Kelola akses langsung" />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                </x-private.data-table>

                <div class="mt-5">
                    {{ $users->links() }}
                </div>
            @endif
        </x-private.panel>
    </div>
</x-layouts.private-module>
