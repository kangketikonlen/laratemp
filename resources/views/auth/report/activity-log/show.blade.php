<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header title="Detail Aktivitas" subtitle="Lihat detail lengkap dari satu log aktivitas yang tercatat otomatis.">
            <x-slot:actions>
                <div class="flex flex-wrap items-center gap-3">
                    @if ($previousLog)
                        <a href="{{ route('report.activity-log.show', $previousLog) }}" class="private-action-link icon-action tooltip-trigger" aria-label="Aktivitas sebelumnya" title="Aktivitas sebelumnya">
                            <x-ui.icon name="chevron-left" class="h-4 w-4" />
                        </a>
                    @else
                        <span class="private-action-link private-action-link--disabled icon-action tooltip-trigger" aria-label="Aktivitas sebelumnya tidak tersedia" title="Aktivitas sebelumnya tidak tersedia">
                            <x-ui.icon name="chevron-left" class="h-4 w-4" />
                        </span>
                    @endif

                    @if ($nextLog)
                        <a href="{{ route('report.activity-log.show', $nextLog) }}" class="private-action-link icon-action tooltip-trigger" aria-label="Aktivitas berikutnya" title="Aktivitas berikutnya">
                            <x-ui.icon name="chevron-right" class="h-4 w-4" />
                        </a>
                    @else
                        <span class="private-action-link private-action-link--disabled icon-action tooltip-trigger" aria-label="Aktivitas berikutnya tidak tersedia" title="Aktivitas berikutnya tidak tersedia">
                            <x-ui.icon name="chevron-right" class="h-4 w-4" />
                        </span>
                    @endif

                    <a href="{{ route('report.activity-log.index') }}" class="private-action-link icon-action tooltip-trigger" aria-label="Kembali ke log aktivitas" title="Kembali ke log aktivitas">
                        <x-ui.icon name="arrow-left" class="h-4 w-4" />
                    </a>
                </div>
            </x-slot:actions>
        </x-private.page-header>

        <x-private.panel
            title="{{ $log->activity }}"
            description="Informasi actor, kategori, status, waktu, dan detail pendukung ditampilkan lengkap di bawah ini."
        >
            <div class="activity-log-detail-grid">
                <div class="activity-log-detail-card">
                    <span class="activity-log-detail-label">Pelaku</span>
                    <span class="activity-log-detail-value">{{ $log->actor ?: 'Sistem' }}</span>
                </div>

                <div class="activity-log-detail-card">
                    <span class="activity-log-detail-label">Kategori</span>
                    <span class="private-role-badge private-role-badge--category-{{ $log->category }}">{{ ucfirst($log->category) }}</span>
                </div>

                <div class="activity-log-detail-card">
                    <span class="activity-log-detail-label">Status</span>
                    <span class="private-role-badge private-role-badge--status-{{ $log->status }}">{{ ucfirst($log->status) }}</span>
                </div>

                <div class="activity-log-detail-card">
                    <span class="activity-log-detail-label">Waktu Dicatat</span>
                    <span class="activity-log-detail-value">{{ $log->logged_at?->format('d M Y H:i') }}</span>
                </div>
            </div>

            <div class="activity-log-detail-panel">
                <h3 class="activity-log-detail-heading">Ringkasan Aktivitas</h3>
                <p class="activity-log-detail-copy">{{ $log->activity }}</p>
            </div>

            <div class="activity-log-detail-panel">
                <h3 class="activity-log-detail-heading">Detail Lengkap</h3>
                @if (filled($log->details))
                    <div class="activity-log-detail-richtext">{!! $log->details !!}</div>
                @else
                    <p class="activity-log-detail-empty">Tidak ada detail tambahan untuk aktivitas ini.</p>
                @endif
            </div>
        </x-private.panel>
    </div>
</x-layouts.private-module>
