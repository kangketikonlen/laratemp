<?php

use App\Http\Controllers\Auth\AuthController;
use App\Models\Settings\Institution;
use App\Models\Settings\Role;
use App\Models\Settings\NavigationItem;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Collection;

Route::redirect('/', '/login');

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        $user = request()->user();

        abort_unless($user instanceof User, 403);

        $user->load('roles.modules');

        /** @var Collection<int, \App\Models\Settings\Module> $modules */
        $modules = $user->roles
            ->flatMap(fn ($role) => $role->modules)
            ->where('is_active', true)
            ->unique('id')
            ->sortBy(['sort_order', 'name'])
            ->values();

        $institution = Institution::query()->first();

        $highlights = [
            ['label' => 'Pengguna Aktif', 'value' => number_format(User::query()->count())],
            ['label' => 'Role Terdaftar', 'value' => number_format(Role::query()->count())],
            ['label' => 'Permission Aktif', 'value' => number_format(Permission::query()->count())],
            ['label' => 'Menu Tersedia', 'value' => number_format(NavigationItem::query()->count())],
        ];

        $releaseNotes = [
            'Perbaikan struktur modul, navbar, dan subnavbar berbasis role.',
            'Dashboard sekarang menampilkan entry module sebagai landing page aplikasi.',
            'General Settings sudah menjadi module utama dengan section terpisah.',
            'Branch Master, Settings, Administration, dan Report siap dikembangkan lebih lanjut.',
        ];

        return view('auth.dashboard', [
            'content' => 'dashboard',
            'modules' => $modules,
            'institution' => $institution,
            'highlights' => $highlights,
            'releaseNotes' => $releaseNotes,
        ]);
    })->name('dashboard');

    Route::view('/master', 'auth.module', [
        'title' => 'Master',
        'description' => 'Master data and foundational resources live here.',
    ])->name('master.index');

    Route::view('/master/users', 'auth.module', [
        'title' => 'User',
        'description' => 'Manage user records from the master section.',
    ])->name('master.users.index');

    Route::view('/master/roles', 'auth.module', [
        'title' => 'Role',
        'description' => 'Manage role records from the master section.',
    ])->name('master.roles.index');

    Route::view('/settings', 'auth.module', [
        'title' => 'Settings',
        'description' => 'Choose a settings module from the navigation below.',
    ])->name('settings.index');

    Route::view('/settings/institutions', 'auth.module', [
        'title' => 'Institution',
        'description' => 'Manage institution records from the settings section.',
    ])->name('settings.institutions.index');

    Route::view('/settings/permissions', 'auth.module', [
        'title' => 'Permission',
        'description' => 'Manage permission records from the settings section.',
    ])->name('settings.permissions.index');

    Route::middleware('can:manage settings')->group(function () {
        Route::get('/general-settings', function () {
            $user = request()->user();

            abort_unless($user instanceof User, 403);

            $user->load('roles.modules.navigationItems');

            $module = $user->roles
                ->flatMap(fn ($role) => $role->modules)
                ->where('slug', 'settings.general')
                ->first();

            abort_unless($module, 403);

            return view('auth.module-dashboard', [
                'module' => $module,
                'navigationItems' => $module->navigationItems()
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get(),
            ]);
        })->name('settings.general');
    });

    Route::view('/administration', 'auth.module', [
        'title' => 'Administration',
        'description' => 'Administrative tools and controls will be organized here.',
    ])->name('administration.index');

    Route::view('/administration/changelogs', 'auth.module', [
        'title' => 'Changelogs',
        'description' => 'Review application changelogs from the administration section.',
    ])->name('administration.changelogs.index');

    Route::view('/administration/work-progress', 'auth.module', [
        'title' => 'Work Progress',
        'description' => 'Review work progress from the administration section.',
    ])->name('administration.work-progress.index');

    Route::view('/report', 'auth.module', [
        'title' => 'Report',
        'description' => 'Reporting modules and summaries will be available here.',
    ])->name('report.index');

    Route::view('/report/activity-log', 'auth.module', [
        'title' => 'Activity Log',
        'description' => 'Review activity log entries from the report section.',
    ])->name('report.activity-log.index');

    Route::view('/report/error-report', 'auth.module', [
        'title' => 'Error Report',
        'description' => 'Review error reports from the report section.',
    ])->name('report.error-report.index');
});
