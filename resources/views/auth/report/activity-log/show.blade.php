<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header title="Activity Detail" subtitle="Lihat detail lengkap dari satu activity log yang tercatat otomatis.">
            <x-slot:actions>
                <div class="flex flex-wrap items-center gap-3">
                    @if ($previousLog)
                        <a href="{{ route('report.activity-log.show', $previousLog) }}" class="private-action-link">
                            <span>Previous</span>
                        </a>
                    @else
                        <span class="private-action-link private-action-link--disabled">Previous</span>
                    @endif

                    @if ($nextLog)
                        <a href="{{ route('report.activity-log.show', $nextLog) }}" class="private-action-link">
                            <span>Next</span>
                        </a>
                    @else
                        <span class="private-action-link private-action-link--disabled">Next</span>
                    @endif

                    <a href="{{ route('report.activity-log.index') }}" class="private-action-link">
                        <span>Back to activity log</span>
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
                    <span class="activity-log-detail-label">Actor</span>
                    <span class="activity-log-detail-value">{{ $log->actor ?: 'System' }}</span>
                </div>

                <div class="activity-log-detail-card">
                    <span class="activity-log-detail-label">Category</span>
                    <span class="private-role-badge private-role-badge--category-{{ $log->category }}">{{ ucfirst($log->category) }}</span>
                </div>

                <div class="activity-log-detail-card">
                    <span class="activity-log-detail-label">Status</span>
                    <span class="private-role-badge private-role-badge--status-{{ $log->status }}">{{ ucfirst($log->status) }}</span>
                </div>

                <div class="activity-log-detail-card">
                    <span class="activity-log-detail-label">Logged At</span>
                    <span class="activity-log-detail-value">{{ $log->logged_at?->format('d M Y H:i') }}</span>
                </div>
            </div>

            <div class="activity-log-detail-panel">
                <h3 class="activity-log-detail-heading">Activity Summary</h3>
                <p class="activity-log-detail-copy">{{ $log->activity }}</p>
            </div>

            <div class="activity-log-detail-panel">
                <h3 class="activity-log-detail-heading">Full Detail</h3>
                @if (filled($log->details))
                    <div class="activity-log-detail-richtext">{!! $log->details !!}</div>
                @else
                    <p class="activity-log-detail-empty">Tidak ada detail tambahan untuk aktivitas ini.</p>
                @endif
            </div>
        </x-private.panel>
    </div>
</x-layouts.private-module>
