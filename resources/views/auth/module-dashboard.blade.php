<x-layouts.private-module :title="$module->name" :description="$module->description">
    <div class="private-page">
        <x-private.page-header :title="$module->name" subtitle="Beranda">
            <x-slot:actions>
                <x-ui.back-dashboard-link />
                <x-ui.logout-button />
            </x-slot:actions>
        </x-private.page-header>

        <x-private.notice :title="'Selamat datang di dashboard '.$module->name.', '.(auth()->user()?->name ?? auth()->user()?->username).'.'" :message="$module->description.' Modul ini merangkum pengelolaan data master, pengaturan aplikasi, administrasi operasional, dan laporan internal dalam satu ruang kerja.'" />

        <x-private.panel title="Bagian Utama" description="Ringkasan bagian utama di dalam Pengaturan Umum untuk membantu Anda langsung masuk ke area kerja yang dibutuhkan." :badge="$navigationItems->count().' bagian'">
            @if ($navigationItems->isEmpty())
                <div class="private-panel-empty">
                    Belum ada bagian yang tersedia untuk modul ini.
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($navigationItems as $item)
                        @php
                            $childNames = $item->children
                                ->pluck('name')
                                ->filter()
                                ->values();

                            $summary = $childNames->isEmpty()
                                ? $item->description
                                : 'Berisi '.collect($childNames->take(3))->join(', ').($childNames->count() > 3 ? ', dan lainnya.' : '.');
                        @endphp

                        <x-private.workspace-tile :title="$item->name" :description="$summary" :href="filled($item->route_name) && \Illuminate\Support\Facades\Route::has($item->route_name) ? route($item->route_name) : null" :suffix-icon="true" />
                    @endforeach
                </div>
            @endif
        </x-private.panel>

        @if ($module->slug === 'general')
            <div class="grid gap-6 xl:grid-cols-2">
                <x-private.panel title="Ringkasan Aktivitas" description="Aktivitas pengguna dan sistem terbaru yang tercatat otomatis dari Pengaturan Umum." :badge="$activitySummaryLogs->count().' terbaru'">
                    @if ($activitySummaryLogs->isEmpty())
                        <div class="private-panel-empty">
                            Belum ada log aktivitas terbaru untuk diringkas.
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($activitySummaryLogs as $entry)
                                <a href="{{ route('report.activity-log.show', $entry) }}" class="workspace-tile workspace-tile--link block">
                                    <div class="workspace-tile-head">
                                        <h3 class="workspace-tile-title">{{ $entry->activity }}</h3>
                                        <span class="private-role-badge">{{ $entry->logged_at?->format('d M H:i') }}</span>
                                    </div>
                                    <p class="workspace-tile-copy">
                                        {{ $entry->actor ?: 'Sistem' }}
                                        @if (filled($entry->previewText(80)))
                                            · {{ $entry->previewText(80) }}
                                        @endif
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </x-private.panel>

                <x-private.panel title="Ringkasan Error" description="Ringkasan error terbaru agar tim cepat melihat masalah penting di modul ini." :badge="$errorSummaryLogs->count().' terbaru'">
                    @if ($errorSummaryLogs->isEmpty())
                        <div class="private-panel-empty">
                            Belum ada log error terbaru untuk diringkas.
                        </div>
                    @else
                        <div class="space-y-3">
                            @foreach ($errorSummaryLogs as $entry)
                                <a href="{{ route('report.error-report.show', $entry) }}" class="workspace-tile workspace-tile--link block">
                                    <div class="workspace-tile-head">
                                        <h3 class="workspace-tile-title">{{ class_basename($entry->exception_class) }}</h3>
                                        <span class="private-role-badge private-role-badge--error-{{ $entry->level }}">{{ ucfirst($entry->level) }}</span>
                                    </div>
                                    <p class="workspace-tile-copy">
                                        {{ $entry->message ?: 'Tanpa pesan error' }}
                                        @if ($entry->occurred_at)
                                            · {{ $entry->occurred_at->format('d M H:i') }}
                                        @endif
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </x-private.panel>
            </div>
        @endif
    </div>
</x-layouts.private-module>
