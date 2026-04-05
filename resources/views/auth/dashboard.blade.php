<x-layouts.private-role title="Dashboard" description="Beranda aplikasi">
    <div class="dashboard-shell dashboard-page">
        <div class="dashboard-page-inner">
            <section class="dashboard-hero">
                <div class="dashboard-hero-content">
                    <div class="max-w-3xl">
                        <p class="dashboard-kicker">Ruang Kerja Internal</p>
                        <h1 class="dashboard-title">Dashboard</h1>
                        <p class="dashboard-summary">
                            Ringkasan cepat untuk ruang kerja internal Anda. Pantau institusi, buka modul utama, dan
                            lihat catatan perubahan terbaru dari satu halaman utama.
                        </p>
                    </div>

                    <div class="dashboard-header-actions">
                        <div class="dashboard-date-card">
                            <p class="dashboard-date-label">Hari ini</p>
                            <p class="dashboard-date-value">{{ now()->translatedFormat('d M Y') }}</p>
                        </div>

                        <x-ui.logout-button />
                    </div>
                </div>
            </section>

            <section class="dashboard-grid">
                <div class="dashboard-stack">
                    <article class="dashboard-surface">
                        <div class="dashboard-profile">
                            <div class="dashboard-profile-icon">
                                <x-ui.icon name="building" class="h-9 w-9" />
                            </div>

                            <h2 class="dashboard-profile-title">
                                {{ $institution?->name ?? 'Institusi belum tersedia' }}
                            </h2>

                            <p class="dashboard-profile-copy">
                                {{ $institution?->address ?? 'Alamat institusi belum diatur.' }}
                            </p>

                            <div class="dashboard-profile-meta">
                                @if (filled($institution?->website))
                                    <span class="dashboard-pill">{{ $institution->website }}</span>
                                @endif
                                @if (filled($institution?->contact))
                                    <span class="dashboard-pill">{{ $institution->contact }}</span>
                                @endif
                            </div>
                        </div>

                    </article>

                    <article class="dashboard-surface">
                        <div class="dashboard-section-head">
                            <div>
                                <p class="dashboard-section-kicker">Catatan Rilis</p>
                                <h2 class="dashboard-section-title">Catatan Pembaruan</h2>
                            </div>
                            <span class="dashboard-release-badge">
                                {{ now()->format('Y.m.d') }}
                            </span>
                        </div>

                        <div class="dashboard-notes">
                            @if ($releaseNotes->isEmpty())
                                <div class="dashboard-note">
                                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-slate-400"></span>
                                    <p>Belum ada release note yang dipublikasikan.</p>
                                </div>
                            @else
                                @foreach ($releaseNotes as $note)
                                    <div class="dashboard-note">
                                        <span class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                        <div>
                                            <p class="font-medium text-slate-800">{{ $note->version }} ·
                                                {{ $note->title }}</p>
                                            @if (filled($note->previewText()))
                                                <p class="mt-1">{{ $note->previewText() }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </article>

                    <article class="dashboard-surface">
                        <div class="dashboard-section-head">
                            <div>
                                <p class="dashboard-section-kicker">Progres Kerja</p>
                                <h2 class="dashboard-section-title">Agenda Berjalan</h2>
                            </div>
                            <span class="dashboard-release-badge">
                                {{ $workProgressItems->count() }} aktif
                            </span>
                        </div>

                        <div class="dashboard-progress-list">
                            @if ($workProgressItems->isEmpty())
                                <div class="dashboard-progress-card dashboard-progress-card--empty">
                                    <p class="dashboard-progress-empty-title">Belum ada pekerjaan aktif.</p>
                                    <p class="dashboard-progress-empty-copy">Tambahkan item di administration work
                                        progress untuk menampilkan agenda tim di dashboard.</p>
                                </div>
                            @else
                                @foreach ($workProgressItems as $item)
                                    <div class="dashboard-progress-card">
                                        <div class="dashboard-progress-head">
                                            <div class="min-w-0">
                                                <p class="dashboard-progress-title">{{ $item->title }}</p>
                                                <p class="dashboard-progress-meta">
                                                    {{ $item->owner ?: 'Belum ditugaskan' }}
                                                    @if ($item->target_date)
                                                        · Target {{ $item->target_date->format('d M Y') }}
                                                    @endif
                                                </p>
                                            </div>

                                            <div class="dashboard-progress-tags">
                                                <span
                                                    class="private-role-badge private-role-badge--{{ $item->status }}">{{ ['planned' => 'Direncanakan', 'in_progress' => 'Berjalan', 'done' => 'Selesai', 'blocked' => 'Terhambat'][$item->status] ?? \Illuminate\Support\Str::headline($item->status) }}</span>
                                                <span
                                                    class="private-role-badge private-role-badge--priority-{{ $item->priority }}">{{ ['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi'][$item->priority] ?? ucfirst($item->priority) }}</span>
                                            </div>
                                        </div>

                                        <div class="dashboard-progress-meter">
                                            <div class="dashboard-progress-meter-track">
                                                <span class="dashboard-progress-meter-fill"
                                                    style="width: {{ max(0, min(100, $item->progress)) }}%"></span>
                                            </div>
                                            <span class="dashboard-progress-percent">{{ $item->progress }}%</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </article>
                </div>

                <article class="dashboard-surface">
                    <div class="dashboard-access-head">
                        <div>
                            <p class="dashboard-access-kicker">Akses Workspace</p>
                            <h2 class="dashboard-access-title">Modul Tersedia</h2>
                            <p class="dashboard-access-copy">
                                Modul utama yang bisa langsung dibuka dari dashboard.
                            </p>
                        </div>

                        <span class="private-panel-badge">
                            {{ $modules->count() }} modul
                        </span>
                    </div>

                    <div class="dashboard-modules">
                        @if ($modules->isEmpty())
                            <div class="private-panel-empty px-6 py-12 text-center">
                                Belum ada modul yang ditugaskan ke akun Anda.
                            </div>
                        @else
                            <div class="dashboard-modules-grid">
                                @foreach ($modules as $module)
                                    @php
                                        $hasRoute =
                                            filled($module->route_name) &&
                                            \Illuminate\Support\Facades\Route::has($module->route_name);
                                        $tag = $hasRoute ? 'a' : 'div';
                                        $iconName = $module->icon === 'settings' ? 'settings' : 'sparkles';
                                    @endphp

                                    <{{ $tag }} @class([
                                        'group',
                                        'dashboard-module-card',
                                        'dashboard-module-card--link' => $hasRoute,
                                    ])
                                        @if ($hasRoute) href="{{ route($module->route_name) }}" @endif>
                                        <div class="dashboard-module-glow"></div>

                                        <div class="relative min-h-55">
                                            <div class="dashboard-module-icon">
                                                <x-ui.icon :name="$iconName" class="h-7 w-7" />
                                            </div>

                                            <div class="mt-5 space-y-3">
                                                <div class="min-w-0">
                                                    <h3 class="text-base font-semibold text-slate-900">
                                                        {{ $module->name }}</h3>
                                                    <p
                                                        class="mt-1 text-[11px] uppercase tracking-[0.24em] text-slate-400">
                                                        {{ $module->slug }}</p>
                                                </div>

                                                @if ($module->is_active)
                                                    <span class="dashboard-module-status">
                                                        Aktif
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="mt-4 text-sm leading-6 text-slate-600">
                                                {{ $module->description }}
                                            </p>

                                            @if ($hasRoute)
                                                <div
                                                    class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-sky-700">
                                                    <span>Buka modul</span>
                                                    <span aria-hidden="true"
                                                        class="transition group-hover:translate-x-1">-></span>
                                                </div>
                                            @endif
                                        </div>
                                        </{{ $tag }}>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </article>
            </section>
        </div>
    </div>
</x-layouts.private-role>
