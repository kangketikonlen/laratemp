<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header title="Error Detail" subtitle="Lihat detail lengkap dari satu error yang tercatat otomatis.">
            <x-slot:actions>
                <div class="flex flex-wrap items-center gap-3">
                    @if ($previousLog)
                        <a href="{{ route('report.error-report.show', $previousLog) }}" class="private-action-link">
                            <span>Previous</span>
                        </a>
                    @else
                        <span class="private-action-link private-action-link--disabled">Previous</span>
                    @endif

                    @if ($nextLog)
                        <a href="{{ route('report.error-report.show', $nextLog) }}" class="private-action-link">
                            <span>Next</span>
                        </a>
                    @else
                        <span class="private-action-link private-action-link--disabled">Next</span>
                    @endif

                    <a href="{{ route('report.error-report.index') }}" class="private-action-link">
                        <span>Back to error logs</span>
                    </a>
                </div>
            </x-slot:actions>
        </x-private.page-header>

        <x-private.panel
            title="{{ class_basename($log->exception_class) }}"
            description="Ringkasan error, lokasi file, user, request, dan trace pendukung ditampilkan di bawah ini."
        >
            <div class="activity-log-detail-grid">
                <div class="activity-log-detail-card">
                    <span class="activity-log-detail-label">Level</span>
                    <span class="private-role-badge private-role-badge--error-{{ $log->level }}">{{ ucfirst($log->level) }}</span>
                </div>

                <div class="activity-log-detail-card">
                    <span class="activity-log-detail-label">User</span>
                    <span class="activity-log-detail-value">{{ $log->user_identifier ?: 'Guest / System' }}</span>
                </div>

                <div class="activity-log-detail-card">
                    <span class="activity-log-detail-label">Request</span>
                    <span class="activity-log-detail-value">{{ $log->method ? $log->method.' ' : '' }}{{ $log->path ?: '-' }}</span>
                </div>

                <div class="activity-log-detail-card">
                    <span class="activity-log-detail-label">Occurred At</span>
                    <span class="activity-log-detail-value">{{ $log->occurred_at?->format('d M Y H:i') }}</span>
                </div>
            </div>

            <div class="activity-log-detail-panel">
                <h3 class="activity-log-detail-heading">Exception</h3>
                <p class="activity-log-detail-copy">{{ $log->exception_class }}</p>
                <p class="activity-log-detail-empty mt-3">{{ $log->message ?: 'No exception message recorded.' }}</p>
            </div>

            <div class="activity-log-detail-panel">
                <h3 class="activity-log-detail-heading">Location</h3>
                <p class="activity-log-detail-copy">{{ $log->file ?: '-' }}</p>
                <p class="activity-log-detail-empty mt-3">Line {{ $log->line ?: '-' }} · IP {{ $log->ip_address ?: '-' }}</p>
            </div>

            <div class="activity-log-detail-panel">
                <h3 class="activity-log-detail-heading">Trace</h3>
                @if (filled($log->trace))
                    <pre class="error-log-trace">{{ $log->trace }}</pre>
                @else
                    <p class="activity-log-detail-empty">Tidak ada trace tambahan untuk error ini.</p>
                @endif
            </div>
        </x-private.panel>
    </div>
</x-layouts.private-module>
