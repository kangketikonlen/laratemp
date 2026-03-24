<x-layouts.private-role title="Dashboard" description="Application home">
    <div class="dashboard-shell min-h-screen px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-7xl space-y-6">
            <section class="dashboard-hero">
                <div class="flex flex-col gap-6 px-6 py-7 lg:flex-row lg:items-start lg:justify-between lg:px-8">
                    <div class="max-w-3xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-sky-600">Private Workspace</p>
                        <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">
                            Dashboard
                        </h1>
                        <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600 sm:text-base">
                            Ringkasan cepat untuk workspace internal Anda. Pantau institusi, buka module utama, dan lihat catatan perubahan terbaru dari satu landing page.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-right">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Today</p>
                            <p class="mt-1 text-sm font-semibold text-slate-700">{{ now()->translatedFormat('d M Y') }}</p>
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

            <section class="grid gap-6 xl:grid-cols-[0.88fr_2.12fr]">
                <div class="space-y-6">
                    <article class="dashboard-surface">
                        <div class="flex flex-col items-center text-center">
                            <div class="flex h-18 w-18 items-center justify-center rounded-3xl bg-sky-100 text-sky-700">
                                <x-ui.icon name="building" class="h-9 w-9" />
                            </div>

                            <h2 class="mt-4 text-xl font-semibold text-slate-900">
                                {{ $institution?->name ?? 'Institution belum tersedia' }}
                            </h2>

                            <p class="mt-2 max-w-sm text-sm leading-6 text-slate-500">
                                {{ $institution?->address ?? 'Alamat institusi belum diatur.' }}
                            </p>

                            <div class="mt-4 flex flex-wrap items-center justify-center gap-2 text-xs text-slate-500">
                                @if (filled($institution?->website))
                                    <span class="rounded-full bg-slate-100 px-3 py-1">{{ $institution->website }}</span>
                                @endif
                                @if (filled($institution?->contact))
                                    <span class="rounded-full bg-slate-100 px-3 py-1">{{ $institution->contact }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-1 2xl:grid-cols-2">
                            @foreach ($highlights as $highlight)
                                <div class="dashboard-stat">
                                    <p class="dashboard-stat-label">{{ $highlight['label'] }}</p>
                                    <p class="dashboard-stat-value">{{ $highlight['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </article>

                    <article class="dashboard-surface">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-600">Release Notes</p>
                                <h2 class="mt-2 text-xl font-semibold text-slate-900">Catatan Pembaruan</h2>
                            </div>
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                {{ now()->format('Y.m.d') }}
                            </span>
                        </div>

                        <div class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
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
                    <div class="flex flex-col gap-3 border-b border-slate-200 pb-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.3em] text-sky-600">Workspace Access</p>
                            <h2 class="mt-2 text-2xl font-semibold text-slate-900">Available Modules</h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Module utama yang bisa langsung dibuka dari dashboard.
                            </p>
                        </div>

                        <span class="private-panel-badge">
                            {{ $modules->count() }} module{{ $modules->count() === 1 ? '' : 's' }}
                        </span>
                    </div>

                    <div class="mt-6">
                        @if ($modules->isEmpty())
                            <div class="private-panel-empty px-6 py-12 text-center">
                                No modules are assigned to your account yet.
                            </div>
                        @else
                            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
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
