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
        <x-private.page-header title="Papan Progres Kerja"
            subtitle="Pantau tugas, pemilik kerja, dan status penyelesaiannya dari satu tempat.">
            <x-slot:actions>
                <x-ui.back-dashboard-link />

                @canany(['manage settings', 'create_work_progress'])
                    <x-ui.add-link :href="route('administration.work-progress.create')" label="Tambah progres" />
                @endcanany
            </x-slot:actions>
        </x-private.page-header>

        @if (session('status'))
            <x-private.feedback :message="session('status')" />
        @endif

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel title="Pelacak Progres"
            description="Gunakan halaman ini untuk memantau pekerjaan yang sedang berjalan, prioritas, dan target penyelesaiannya."
            :badge="$items->total() . ' data'">
            <form method="GET" action="{{ route('administration.work-progress.index') }}" class="private-toolbar">
                <div class="private-search">
                    <x-form.input name="search" :value="$search" icon="clipboard"
                        placeholder="Cari judul, owner, status, priority, atau catatan..." autocomplete="off" />

                    <input type="hidden" name="sort" value="{{ $sort }}">
                    <input type="hidden" name="direction" value="{{ $direction }}">
                </div>

                <div class="private-inline-actions">
                    <x-ui.search-button />

                    @if (filled($search))
                        <a href="{{ route('administration.work-progress.index') }}"
                            class="private-action-link icon-action tooltip-trigger" aria-label="Atur ulang filter"
                            title="Atur ulang filter">
                            <x-ui.icon name="rotate" class="h-4 w-4" />
                        </a>
                    @endif
                </div>
            </form>

            @if ($items->isEmpty())
                <div class="private-panel-empty">
                    Belum ada pekerjaan yang cocok dengan filter saat ini. Tambahkan progress baru atau ubah kata kunci
                    pencarian.
                </div>
            @else
                <x-private.data-table>
                    <thead>
                        <tr>
                            <x-private.table-sort-heading :href="$sortUrl('title')" label="Item Pekerjaan" :active="$sort === 'title'"
                                :direction="$direction" />
                            <x-private.table-sort-heading :href="$sortUrl('owner')" label="Penanggung Jawab" :active="$sort === 'owner'"
                                :direction="$direction" />
                            <x-private.table-sort-heading :href="$sortUrl('status')" label="Status" :active="$sort === 'status'"
                                :direction="$direction" />
                            <x-private.table-sort-heading :href="$sortUrl('priority')" label="Prioritas" :active="$sort === 'priority'"
                                :direction="$direction" />
                            <x-private.table-sort-heading :href="$sortUrl('progress')" label="Progres" :active="$sort === 'progress'"
                                :direction="$direction" />
                            <x-private.table-sort-heading :href="$sortUrl('target_date')" label="Target" :active="$sort === 'target_date'"
                                :direction="$direction" />
                            <th class="text-right">Aksi</th>
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
                                    <span class="private-table-muted">{{ $entry->owner ?: 'Belum ditugaskan' }}</span>
                                </td>
                                <td>
                                    <span
                                        class="private-role-badge private-role-badge--{{ $entry->status }}">{{ ['planned' => 'Direncanakan', 'in_progress' => 'Berjalan', 'done' => 'Selesai', 'blocked' => 'Terhambat'][$entry->status] ?? \Illuminate\Support\Str::headline($entry->status) }}</span>
                                </td>
                                <td>
                                    <span
                                        class="private-role-badge private-role-badge--priority-{{ $entry->priority }}">{{ ['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi'][$entry->priority] ?? ucfirst($entry->priority) }}</span>
                                </td>
                                <td>
                                    <div class="work-progress-meter">
                                        <div class="work-progress-meter-track">
                                            <span class="work-progress-meter-fill"
                                                style="width: {{ max(0, min(100, $entry->progress)) }}%"></span>
                                        </div>
                                        <span class="work-progress-meter-label">{{ $entry->progress }}%</span>
                                    </div>
                                </td>
                                <td>
                                    <span
                                        class="private-table-muted">{{ $entry->target_date?->format('d M Y') ?: 'Tanpa target' }}</span>
                                </td>
                                <td>
                                    <div class="private-table-actions">
                                        @canany(['manage settings', 'update_work_progress'])
                                            <x-ui.edit-link :href="route('administration.work-progress.edit', $entry)" label="Ubah progres" />
                                        @endcanany

                                        @canany(['manage settings', 'delete_work_progress'])
                                            <x-ui.delete-button :action="route('administration.work-progress.destroy', $entry)" label="Hapus progres"
                                                confirm="Hapus progres kerja {{ $entry->title }}?" />
                                        @endcanany
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-private.data-table>

                <div class="mt-5">
                    {{ $items->links() }}
                </div>
            @endif
        </x-private.panel>
    </div>
</x-layouts.private-module>
