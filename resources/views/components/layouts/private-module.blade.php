@props([
    'title' => config('app.name'),
    'description' => 'Application workspace',
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
            return request()->routeIs(\Illuminate\Support\Str::beforeLast($routeName, '.index').'.*');
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
        $primaryRole = $authUser->roles->first()?->display_name ?? $authUser->roles->first()?->name ?? 'Workspace User';

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - {{ $description }}</title>
    <meta name="description" content="{{ $description }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-gray-100">
    <div class="min-h-screen bg-[#f5f7fb] lg:flex">
        <aside class="w-full bg-[#262626] text-white lg:min-h-screen lg:w-70 lg:flex-none">
            <div class="flex h-full flex-col px-5 py-6">
                <div class="rounded-[28px] border border-white/10 bg-white/3 px-4 py-5 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full border-2 border-sky-300/60 bg-sky-400/15 text-xl font-semibold text-sky-100">
                        {{ strtoupper(str($authUser?->name ?? $authUser?->username ?? 'U')->substr(0, 1)) }}
                    </div>
                    <p class="mt-4 text-sm font-medium text-white/95">{{ $authUser?->name ?? $authUser?->username }}</p>
                    <p class="mt-1 text-xs leading-5 text-white/65">{{ $primaryRole }}</p>
                    <p class="text-xs leading-5 text-white/65">{{ config('app.name') }}</p>
                </div>

                <nav class="mt-6 space-y-2">
                    <a
                        href="{{ $moduleDashboardRoute }}"
                        @class([
                            'flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition',
                            'bg-white/8 text-white shadow-[inset_0_0_0_1px_rgba(255,255,255,0.05)]' => $isOnModuleDashboard,
                            'text-white/75 hover:bg-white/6 hover:text-white' => ! $isOnModuleDashboard,
                        ])
                    >
                        <x-ui.icon name="dashboard" class="h-4 w-4" />
                        <span>Module Home</span>
                    </a>

                    @foreach ($navbarItems as $item)
                        @php
                            $hasRoute = filled($item->route_name) && \Illuminate\Support\Facades\Route::has($item->route_name);
                            $isCurrent = $activeNavbar?->is($item) ?? false;
                            $children = $isCurrent ? $subnavbarItems : collect();
                            $icon = $navIconMap[$item->name] ?? 'sparkles';
                        @endphp

                        <div class="space-y-2">
                            @if ($hasRoute)
                                <a
                                    href="{{ route($item->route_name) }}"
                                    @class([
                                        'flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition',
                                        'bg-white/8 text-white shadow-[inset_0_0_0_1px_rgba(255,255,255,0.05)]' => $isCurrent,
                                        'text-white/75 hover:bg-white/6 hover:text-white' => ! $isCurrent,
                                    ])
                                >
                                    <x-ui.icon :name="$icon" class="h-4 w-4" />
                                    <span class="flex-1">{{ $item->name }}</span>
                                    <x-ui.icon name="chevron-right" class="h-4 w-4 text-white/45" />
                                </a>
                            @else
                                <span class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-white/75">
                                    <x-ui.icon :name="$icon" class="h-4 w-4" />
                                    <span class="flex-1">{{ $item->name }}</span>
                                </span>
                            @endif

                            @if ($children->isNotEmpty())
                                <div class="ml-5 space-y-1 border-l border-white/10 pl-4">
                                    @foreach ($children as $child)
                                        @php
                                            $childHasRoute = filled($child->route_name) && \Illuminate\Support\Facades\Route::has($child->route_name);
                                            $childCurrent = $childHasRoute && $routeMatches($child->route_name);
                                        @endphp

                                        @if ($childHasRoute)
                                            <a
                                                href="{{ route($child->route_name) }}"
                                                @class([
                                                    'block rounded-lg px-3 py-2 text-sm transition',
                                                    'bg-sky-400/15 font-medium text-sky-100' => $childCurrent,
                                                    'text-white/60 hover:bg-white/5 hover:text-white/90' => ! $childCurrent,
                                                ])
                                            >
                                                {{ $child->name }}
                                            </a>
                                        @else
                                            <span class="block rounded-lg px-3 py-2 text-sm text-white/60">
                                                {{ $child->name }}
                                            </span>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </nav>

                <div class="mt-auto pt-6 text-xs text-white/40">
                    Crafted for {{ config('app.name') }}
                </div>
            </div>
        </aside>

        <main class="min-w-0 flex-1 px-5 py-6 sm:px-8 lg:px-10">
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
