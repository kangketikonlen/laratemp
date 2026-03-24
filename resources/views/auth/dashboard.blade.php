<x-layouts.private-role title="Dashboard" description="Application home">
    <div class="dashboard-shell dashboard-page">
        <div class="dashboard-page-inner">
            <section class="dashboard-hero">
                <div class="dashboard-hero-content">
                    <div class="max-w-3xl">
                        <p class="dashboard-kicker">Private Workspace</p>
                        <h1 class="dashboard-title">Dashboard</h1>
                        <p class="dashboard-summary">
                            Ringkasan cepat untuk workspace internal Anda. Pantau institusi, buka module utama, dan lihat catatan perubahan terbaru dari satu landing page.
                        </p>
                    </div>

                    <div class="dashboard-header-actions">
                        <div class="dashboard-date-card">
                            <p class="dashboard-date-label">Today</p>
                            <p class="dashboard-date-value">{{ now()->translatedFormat('d M Y') }}</p>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-ui.button type="submit" variant="danger" :block="false" class="rounded-2xl px-5 py-3">
                                Logout
                            </x-ui.button>
                        </form>
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
                                {{ $institution?->name ?? 'Institution belum tersedia' }}
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

                        <div class="dashboard-stats-grid">
                            @foreach ($highlights as $highlight)
                                <div class="dashboard-stat">
                                    <p class="dashboard-stat-label">{{ $highlight['label'] }}</p>
                                    <p class="dashboard-stat-value">{{ $highlight['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </article>

                    <article class="dashboard-surface">
                        <div class="dashboard-section-head">
                            <div>
                                <p class="dashboard-section-kicker">Release Notes</p>
                                <h2 class="dashboard-section-title">Catatan Pembaruan</h2>
                            </div>
                            <span class="dashboard-release-badge">
                                {{ now()->format('Y.m.d') }}
                            </span>
                        </div>

                        <div class="dashboard-notes">
                            @foreach ($releaseNotes as $note)
                                <div class="dashboard-note">
                                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                                    <p>{{ $note }}</p>
                                </div>
                            @endforeach
                        </div>
                    </article>
                </div>

                <article class="dashboard-surface">
                    <div class="dashboard-access-head">
                        <div>
                            <p class="dashboard-access-kicker">Workspace Access</p>
                            <h2 class="dashboard-access-title">Available Modules</h2>
                            <p class="dashboard-access-copy">
                                Module utama yang bisa langsung dibuka dari dashboard.
                            </p>
                        </div>

                        <span class="private-panel-badge">
                            {{ $modules->count() }} module{{ $modules->count() === 1 ? '' : 's' }}
                        </span>
                    </div>

                    <div class="dashboard-modules">
                        @if ($modules->isEmpty())
                            <div class="private-panel-empty px-6 py-12 text-center">
                                No modules are assigned to your account yet.
                            </div>
                        @else
                            <div class="dashboard-modules-grid">
                                @foreach ($modules as $module)
                                    @php
                                        $hasRoute = filled($module->route_name) && \Illuminate\Support\Facades\Route::has($module->route_name);
                                        $tag = $hasRoute ? 'a' : 'div';
                                        $iconName = $module->icon === 'settings' ? 'settings' : 'sparkles';
                                    @endphp

                                    <{{ $tag }}
                                        @class([
                                            'group',
                                            'dashboard-module-card',
                                            'dashboard-module-card--link' => $hasRoute,
                                        ])
                                        @if ($hasRoute)
                                            href="{{ route($module->route_name) }}"
                                        @endif
                                    >
                                        <div class="dashboard-module-glow"></div>

                                        <div class="relative min-h-55">
                                            <div class="dashboard-module-icon">
                                                <x-ui.icon :name="$iconName" class="h-7 w-7" />
                                            </div>

                                            <div class="mt-5 space-y-3">
                                                <div class="min-w-0">
                                                    <h3 class="text-base font-semibold text-slate-900">{{ $module->name }}</h3>
                                                    <p class="mt-1 text-[11px] uppercase tracking-[0.24em] text-slate-400">{{ $module->slug }}</p>
                                                </div>

                                                @if ($module->is_active)
                                                    <span class="dashboard-module-status">
                                                        Active
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="mt-4 text-sm leading-6 text-slate-600">
                                                {{ $module->description }}
                                            </p>

                                            @if ($hasRoute)
                                                <div class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-sky-700">
                                                    <span>Open module</span>
                                                    <span aria-hidden="true" class="transition group-hover:translate-x-1">-></span>
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
