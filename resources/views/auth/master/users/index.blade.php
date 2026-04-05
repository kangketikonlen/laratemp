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
        <x-private.page-header :title="$title" subtitle="Manajemen Data Master">
            <x-slot:actions>
                <x-ui.back-dashboard-link />

                <x-ui.add-link :href="route('master.users.create')" label="Tambah pengguna" />
            </x-slot:actions>
        </x-private.page-header>

        @if (session('status'))
            <x-private.feedback :message="session('status')" />
        @endif

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel title="Daftar User"
            description="Kelola akun aplikasi, identitas login, dan penetapan role dari satu tempat." :badge="$users->total() . ' data'">
            <form method="GET" action="{{ route('master.users.index') }}" class="private-toolbar">
                <div class="private-search">
                    <x-form.input name="search" :value="$search" icon="user"
                        placeholder="Cari nama, username, atau email..." autocomplete="off" />

                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                </div>

                <div class="private-inline-actions">
                    <x-ui.search-button />

                    @if (filled($search))
                        <a href="{{ route('master.users.index') }}"
                            class="private-action-link icon-action tooltip-trigger" aria-label="Atur ulang filter"
                            title="Atur ulang filter">
                            <x-ui.icon name="rotate" class="h-4 w-4" />
                        </a>
                    @endif
                </div>
            </form>

            @if ($users->isEmpty())
                <div class="private-panel-empty">
                    Belum ada pengguna yang cocok dengan filter saat ini. Tambahkan pengguna baru atau ubah kata kunci
                    pencarian.
                </div>
            @else
                <x-private.data-table>
                    <thead>
                        <tr>
                            <x-private.table-sort-heading :href="$sortUrl('name')" label="User" :active="$sort === 'name'"
                                :direction="$direction" />
                            <x-private.table-sort-heading :href="$sortUrl('username')" label="Username" :active="$sort === 'username'"
                                :direction="$direction" />
                            <x-private.table-sort-heading :href="$sortUrl('email')" label="Email" :active="$sort === 'email'"
                                :direction="$direction" />
                            <th>Role</th>
                            <x-private.table-sort-heading :href="$sortUrl('created_at')" label="Dibuat" :active="$sort === 'created_at'"
                                :direction="$direction" />
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $managedUser)
                            <tr>
                                <td>
                                    <div class="private-table-primary">{{ $managedUser->name }}</div>
                                </td>
                                <td>
                                    <span class="private-table-muted">{{ '@' . $managedUser->username }}</span>
                                </td>
                                <td>{{ $managedUser->email }}</td>
                                <td>
                                    <div class="private-role-list">
                                        @forelse ($managedUser->roles as $role)
                                            <span class="private-role-badge">
                                                {{ $role->display_name ?? $role->name }}
                                            </span>
                                        @empty
                                            <span class="private-table-muted">Belum ada role</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="private-table-muted">{{ $managedUser->created_at?->format('d M Y') }}</span>
                                </td>
                                <td>
                                    <div class="private-table-actions">
                                        <x-ui.edit-link :href="route('master.users.edit', $managedUser)" label="Ubah pengguna" />

                                        <x-ui.delete-button :action="route('master.users.destroy', $managedUser)" label="Hapus pengguna"
                                            confirm="Hapus pengguna {{ $managedUser->username }}?" />
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
