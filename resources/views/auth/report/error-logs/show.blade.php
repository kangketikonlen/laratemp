<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header title="Error Detail" subtitle="Lihat detail lengkap dari satu error yang tercatat otomatis.">
            <x-slot:actions>
                <div class="flex flex-wrap items-center gap-3">
                    @if ($previousLog)
                        <a href="{{ route('report.error-report.show', $previousLog) }}" class="private-action-link icon-action tooltip-trigger" aria-label="Error sebelumnya" title="Error sebelumnya">
                            <x-ui.icon name="chevron-left" class="h-4 w-4" />
                        </a>
                    @else
                        <span class="private-action-link private-action-link--disabled icon-action tooltip-trigger" aria-label="Error sebelumnya tidak tersedia" title="Error sebelumnya tidak tersedia">
                            <x-ui.icon name="chevron-left" class="h-4 w-4" />
                        </span>
                    @endif

                    @if ($nextLog)
                        <a href="{{ route('report.error-report.show', $nextLog) }}" class="private-action-link icon-action tooltip-trigger" aria-label="Error berikutnya" title="Error berikutnya">
                            <x-ui.icon name="chevron-right" class="h-4 w-4" />
                        </a>
                    @else
                        <span class="private-action-link private-action-link--disabled icon-action tooltip-trigger" aria-label="Error berikutnya tidak tersedia" title="Error berikutnya tidak tersedia">
                            <x-ui.icon name="chevron-right" class="h-4 w-4" />
                        </span>
                    @endif

                    <a href="{{ route('report.error-report.index') }}" class="private-action-link icon-action tooltip-trigger" aria-label="Kembali ke log error" title="Kembali ke log error">
                        <x-ui.icon name="arrow-left" class="h-4 w-4" />
                    </a>
                </div>
            </x-slot:actions>
        </x-private.page-header>

        <x-private.panel
            title="{{ class_basename($log->exception_class) }}"
            description="Ringkasan error, lokasi file, pengguna, permintaan, dan trace pendukung ditampilkan di bawah ini."
        >
            <div class="activity-log-detail-grid">
                <div class="activity-log-detail-card">
                    <span class="activity-log-detail-label">Level</span>
                    <span class="private-role-badge private-role-badge--error-{{ $log->level }}">{{ ucfirst($log->level) }}</span>
                </div>

                <div class="activity-log-detail-card">
                    <span class="activity-log-detail-label">Pengguna</span>
                    <span class="activity-log-detail-value">{{ $log->user_identifier ?: 'Tamu / Sistem' }}</span>
                </div>

                <div class="activity-log-detail-card">
                    <span class="activity-log-detail-label">Permintaan</span>
                    <span class="activity-log-detail-value">{{ $log->method ? $log->method.' ' : '' }}{{ $log->path ?: '-' }}</span>
                </div>

                <div class="activity-log-detail-card">
                    <span class="activity-log-detail-label">Waktu Kejadian</span>
                    <span class="activity-log-detail-value">{{ $log->occurred_at?->format('d M Y H:i') }}</span>
                </div>
            </div>

            <div class="activity-log-detail-panel">
                <h3 class="activity-log-detail-heading">Exception</h3>
                <p class="activity-log-detail-copy">{{ $log->exception_class }}</p>
                <p class="activity-log-detail-empty mt-3">{{ $log->message ?: 'Tidak ada pesan exception yang tercatat.' }}</p>
            </div>

            <div class="activity-log-detail-panel">
                <h3 class="activity-log-detail-heading">Lokasi</h3>
                <p class="activity-log-detail-copy">{{ $log->file ?: '-' }}</p>
                <p class="activity-log-detail-empty mt-3">Baris {{ $log->line ?: '-' }} · IP {{ $log->ip_address ?: '-' }}</p>
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
