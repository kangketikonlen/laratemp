<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Master\RoleController;
use App\Http\Controllers\Master\UserController;
use App\Models\Settings\Institution;
use App\Models\Settings\Module;
use App\Models\Settings\NavigationItem;
use App\Models\Settings\Role;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

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

        /** @var Collection<int, Module> $modules */
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
        Route::get('/master/users', [UserController::class, 'index'])->name('master.users.index');
        Route::get('/master/users/create', [UserController::class, 'create'])->name('master.users.create');
        Route::post('/master/users', [UserController::class, 'store'])->name('master.users.store');
        Route::get('/master/users/{user}/edit', [UserController::class, 'edit'])->name('master.users.edit');
        Route::match(['put', 'patch'], '/master/users/{user}', [UserController::class, 'update'])->name('master.users.update');
        Route::delete('/master/users/{user}', [UserController::class, 'destroy'])->name('master.users.destroy');

        Route::get('/master/roles', [RoleController::class, 'index'])->name('master.roles.index');
        Route::get('/master/roles/create', [RoleController::class, 'create'])->name('master.roles.create');
        Route::post('/master/roles', [RoleController::class, 'store'])->name('master.roles.store');
        Route::get('/master/roles/{role}/edit', [RoleController::class, 'edit'])->name('master.roles.edit');
        Route::match(['put', 'patch'], '/master/roles/{role}', [RoleController::class, 'update'])->name('master.roles.update');
        Route::delete('/master/roles/{role}', [RoleController::class, 'destroy'])->name('master.roles.destroy');

        Route::get('/general', function () {
            $user = request()->user();

            abort_unless($user instanceof User, 403);

            $user->load('roles.modules.navigationItems');

            $module = $user->roles
                ->flatMap(fn ($role) => $role->modules)
                ->where('slug', 'general')
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
        })->name('general');
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
