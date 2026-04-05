@props([
    'title' => config('app.name'),
    'description' => 'Ruang kerja aplikasi',
])

@php
    /** @var \App\Models\User|null $authUser */
    $authUser = request()->user();
    $routeMatches = function (?string $routeName): bool {
        if (blank($routeName) || ! \Illuminate\Support\Facades\Route::has($routeName)) {
            return false;
        }

        if (request()->routeIs($routeName)) {
            return true;
        }

        if (\Illuminate\Support\Str::endsWith($routeName, '.index')) {
            return request()->routeIs(\Illuminate\Support\Str::beforeLast($routeName, '.index').'.*.*');
        }

        return false;
    };

    $navbarItems = collect();
    $subnavbarItems = collect();
    $activeModule = null;
    $activeNavbar = null;
    $primaryRole = null;
    $moduleDashboardRoute = route('dashboard');
    $isOnModuleDashboard = false;

    if ($authUser instanceof \App\Models\User) {
        $authUser->loadMissing('roles.modules.navigationItems.parent');
        $primaryRole = $authUser->roles->first()?->display_name ?? $authUser->roles->first()?->name ?? 'Pengguna Workspace';

        $assignedModules = $authUser->roles
            ->flatMap(fn ($role) => $role->modules)
            ->filter(fn ($module) => $module->is_active)
            ->unique('id')
            ->sortBy(['sort_order', 'name'])
            ->values();

        $activeModule = $assignedModules->first(function ($module) use ($routeMatches) {
            if ($routeMatches($module->route_name)) {
                return true;
            }

            return $module->navigationItems->contains(fn ($item) => $routeMatches($item->route_name));
        }) ?? $assignedModules->first();

        if ($activeModule) {
            if (filled($activeModule->route_name) && \Illuminate\Support\Facades\Route::has($activeModule->route_name)) {
                $moduleDashboardRoute = route($activeModule->route_name);
                $isOnModuleDashboard = request()->routeIs($activeModule->route_name);
            }

            $moduleNavigation = $activeModule->navigationItems
                ->where('is_active', true)
                ->sortBy(['sort_order', 'name'])
                ->values();

            $navbarItems = $moduleNavigation->whereNull('parent_id')->values();

            $activeNavbar = $navbarItems->first(function ($item) use ($moduleNavigation, $routeMatches) {
                if ($routeMatches($item->route_name)) {
                    return true;
                }

                return $moduleNavigation
                    ->where('parent_id', $item->id)
                    ->contains(fn ($child) => $routeMatches($child->route_name));
            });

            if ($activeNavbar) {
                $subnavbarItems = $moduleNavigation
                    ->where('parent_id', $activeNavbar->id)
                    ->values();
            }
        }
    }

    $navIconMap = [
        'Master' => 'folder',
        'Settings' => 'settings',
        'Administration' => 'clipboard',
        'Report' => 'chart',
    ];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ $description }}</title>
    <meta name="description" content="{{ $description }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-gray-100">
    <div class="workspace-shell">
        <aside class="workspace-sidebar">
            <div class="workspace-sidebar-body">
                <div class="workspace-sidebar-card">
                    <div class="workspace-sidebar-avatar">
                        {{ strtoupper(str($authUser?->name ?? $authUser?->username ?? 'U')->substr(0, 1)) }}
                    </div>
                    <p class="workspace-user-name">{{ $authUser?->name ?? $authUser?->username }}</p>
                    <p class="workspace-user-meta">{{ $primaryRole }}</p>
                    <p class="workspace-app-meta">{{ config('app.name') }}</p>
                </div>

                <nav class="workspace-nav">
                    <a
                        href="{{ $moduleDashboardRoute }}"
                        @class([
                            'workspace-nav-link',
                            'workspace-nav-link--active' => $isOnModuleDashboard,
                            'workspace-nav-link--idle' => ! $isOnModuleDashboard,
                        ])
                    >
                        <x-ui.icon name="dashboard" class="h-4 w-4" />
                        <span>Beranda Modul</span>
                    </a>

                    @foreach ($navbarItems as $item)
                        @php
                            $hasRoute = filled($item->route_name) && \Illuminate\Support\Facades\Route::has($item->route_name);
                            $isCurrent = $activeNavbar?->is($item) ?? false;
                            $children = $isCurrent ? $subnavbarItems : collect();
                            $icon = $navIconMap[$item->name] ?? 'sparkles';
                        @endphp

                        <div class="workspace-nav-group">
                            @if ($hasRoute)
                                <a
                                    href="{{ route($item->route_name) }}"
                                    @class([
                                        'workspace-nav-link',
                                        'workspace-nav-link--active' => $isCurrent,
                                        'workspace-nav-link--idle' => ! $isCurrent,
                                    ])
                                >
                                    <x-ui.icon :name="$icon" class="h-4 w-4" />
                                    <span class="workspace-nav-label-text">{{ $item->name }}</span>
                                    <x-ui.icon name="chevron-right" class="workspace-nav-chevron" />
                                </a>
                            @else
                                <span class="workspace-nav-label">
                                    <x-ui.icon :name="$icon" class="h-4 w-4" />
                                    <span class="workspace-nav-label-text">{{ $item->name }}</span>
                                </span>
                            @endif

                            @if ($children->isNotEmpty())
                                <div class="workspace-subnav">
                                    @foreach ($children as $child)
                                        @php
                                            $childHasRoute = filled($child->route_name) && \Illuminate\Support\Facades\Route::has($child->route_name);
                                            $childCurrent = $childHasRoute && $routeMatches($child->route_name);
                                        @endphp

                                        @if ($childHasRoute)
                                            <a
                                                href="{{ route($child->route_name) }}"
                                                @class([
                                                    'workspace-subnav-link',
                                                    'workspace-subnav-link--active' => $childCurrent,
                                                    'workspace-subnav-link--idle' => ! $childCurrent,
                                                ])
                                            >
                                                {{ $child->name }}
                                            </a>
                                        @else
                                            <span class="workspace-subnav-link workspace-subnav-link--idle">
                                                {{ $child->name }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </nav>

                <div class="workspace-footer">
                    Dibuat untuk {{ config('app.name') }}
                </div>
            </div>
        </aside>

        <main class="workspace-main">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
