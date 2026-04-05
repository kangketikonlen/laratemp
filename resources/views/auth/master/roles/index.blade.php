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

                <x-ui.add-link :href="route('master.roles.create')" label="Tambah role" />
            </x-slot:actions>
        </x-private.page-header>

        @if (session('status'))
            <x-private.feedback :message="session('status')" />
        @endif

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel title="Daftar Role"
            description="Kelola role aplikasi, nama tampilan, dan akses modul dari satu ruang kerja." :badge="$roles->total() . ' data'">
            <form method="GET" action="{{ route('master.roles.index') }}" class="private-toolbar">
                <div class="private-search">
                    <x-form.input name="search" :value="$search" icon="folder"
                        placeholder="Cari name, display name, atau deskripsi..." autocomplete="off" />

                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                </div>

                <div class="private-inline-actions">
                    <x-ui.search-button />

                    @if (filled($search))
                        <a href="{{ route('master.roles.index') }}"
                            class="private-action-link icon-action tooltip-trigger" aria-label="Atur ulang filter"
                            title="Atur ulang filter">
                            <x-ui.icon name="rotate" class="h-4 w-4" />
                        </a>
                    @endif
                </div>
            </form>

            @if ($roles->isEmpty())
                <div class="private-panel-empty">
                    Belum ada role yang cocok dengan filter saat ini. Tambahkan role baru atau ubah kata kunci
                    pencarian.
                </div>
            @else
                <x-private.data-table>
                    <thead>
                        <tr>
                            <x-private.table-sort-heading :href="$sortUrl('display_name')" label="Role" :active="$sort === 'display_name'"
                                :direction="$direction" />
                            <x-private.table-sort-heading :href="$sortUrl('name')" label="Nama" :active="$sort === 'name'"
                                :direction="$direction" />
                            <th>Modul</th>
                            <x-private.table-sort-heading :href="$sortUrl('users_count')" label="Pengguna" :active="$sort === 'users_count'"
                                :direction="$direction" />
                            <x-private.table-sort-heading :href="$sortUrl('is_system')" label="Status" :active="$sort === 'is_system'"
                                :direction="$direction" />
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
                                            <span class="private-table-muted">Belum ada modul</span>
                                        @endforelse
                                    </div>
                                </td>
                                <td>
                                    <span class="private-table-muted">{{ number_format($managedRole->users_count) }}
                                        pengguna</span>
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
                                        @if ($managedRole->is_system)
                                            <span class="private-table-muted">Role terlindungi</span>
                                        @else
                                            <x-ui.edit-link :href="route('master.roles.edit', $managedRole)" label="Ubah role" />

                                            <x-ui.delete-button :action="route('master.roles.destroy', $managedRole)" label="Hapus role"
                                                confirm="Hapus role {{ $managedRole->name }}?" />
                                        @endif
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
    </div>
</x-layouts.private-module>
