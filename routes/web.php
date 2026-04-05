<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Administration\ChangelogController;
use App\Http\Controllers\Administration\WorkProgressController;
use App\Http\Controllers\Report\ActivityLogController;
use App\Http\Controllers\Report\ErrorLogController;
use App\Models\Administration\Changelog;
use App\Models\Administration\WorkProgress;
use App\Models\Report\ActivityLog;
use App\Models\Report\ErrorLog;
use App\Http\Controllers\Master\RoleController;
use App\Http\Controllers\Master\UserController;
use App\Http\Controllers\Settings\InstitutionController;
use App\Http\Controllers\Settings\PermissionController;
use App\Models\Settings\Institution;
use App\Models\Settings\Module;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

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

        $releaseNotes = Changelog::query()
            ->where('status', 'published')
            ->orderByDesc('released_at')
            ->orderByDesc('created_at')
            ->limit(4)
            ->get(['version', 'title', 'notes', 'released_at']);

        $workProgressItems = WorkProgress::query()
            ->whereIn('status', ['planned', 'in_progress', 'blocked'])
            ->orderByRaw("case when status = 'blocked' then 0 when status = 'in_progress' then 1 else 2 end")
            ->orderByDesc('updated_at')
            ->limit(4)
            ->get(['title', 'owner', 'status', 'priority', 'progress', 'target_date']);

        return view('auth.dashboard', [
            'content' => 'dashboard',
            'modules' => $modules,
            'institution' => $institution,
            'releaseNotes' => $releaseNotes,
            'workProgressItems' => $workProgressItems,
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

    Route::middleware('can:view_institutions')->group(function () {
        Route::get('/settings/institutions', [InstitutionController::class, 'index'])->name('settings.institutions.index');
    });

    Route::middleware('can:update_institutions')->group(function () {
        Route::put('/settings/institutions', [InstitutionController::class, 'update'])->name('settings.institutions.update');
    });

    Route::middleware('can:view_permissions')->group(function () {
        Route::get('/settings/permissions', [PermissionController::class, 'index'])->name('settings.permissions.index');
    });

    Route::middleware('can:manage settings')->group(function () {
        Route::get('/settings/permissions/create', [PermissionController::class, 'create'])->name('settings.permissions.create');
        Route::post('/settings/permissions', [PermissionController::class, 'store'])->name('settings.permissions.store');
        Route::get('/settings/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('settings.permissions.edit');
        Route::match(['put', 'patch'], '/settings/permissions/{permission}', [PermissionController::class, 'update'])->name('settings.permissions.update');
        Route::delete('/settings/permissions/{permission}', [PermissionController::class, 'destroy'])->name('settings.permissions.destroy');
    });

    Route::middleware('can:update_permissions')->group(function () {
        Route::get('/settings/permissions/roles/{role}/edit', [PermissionController::class, 'editRole'])->name('settings.permissions.roles.edit');
        Route::match(['put', 'patch'], '/settings/permissions/roles/{role}', [PermissionController::class, 'updateRole'])->name('settings.permissions.roles.update');
        Route::get('/settings/permissions/users/{user}/edit', [PermissionController::class, 'editUser'])->name('settings.permissions.users.edit');
        Route::match(['put', 'patch'], '/settings/permissions/users/{user}', [PermissionController::class, 'updateUser'])->name('settings.permissions.users.update');
    });

    Route::middleware('can:view_users')->group(function () {
        Route::get('/master/users', [UserController::class, 'index'])->name('master.users.index');
    });

    Route::middleware('can:create_users')->group(function () {
        Route::get('/master/users/create', [UserController::class, 'create'])->name('master.users.create');
        Route::post('/master/users', [UserController::class, 'store'])->name('master.users.store');
    });

    Route::middleware('can:update_users')->group(function () {
        Route::get('/master/users/{user}/edit', [UserController::class, 'edit'])->name('master.users.edit');
        Route::match(['put', 'patch'], '/master/users/{user}', [UserController::class, 'update'])->name('master.users.update');
    });

    Route::middleware('can:delete_users')->group(function () {
        Route::delete('/master/users/{user}', [UserController::class, 'destroy'])->name('master.users.destroy');
    });

    Route::middleware('can:view_roles')->group(function () {
        Route::get('/master/roles', [RoleController::class, 'index'])->name('master.roles.index');
    });

    Route::middleware('can:create_roles')->group(function () {
        Route::get('/master/roles/create', [RoleController::class, 'create'])->name('master.roles.create');
        Route::post('/master/roles', [RoleController::class, 'store'])->name('master.roles.store');
    });

    Route::middleware('can:update_roles')->group(function () {
        Route::get('/master/roles/{role}/edit', [RoleController::class, 'edit'])->name('master.roles.edit');
        Route::match(['put', 'patch'], '/master/roles/{role}', [RoleController::class, 'update'])->name('master.roles.update');
    });

    Route::middleware('can:delete_roles')->group(function () {
        Route::delete('/master/roles/{role}', [RoleController::class, 'destroy'])->name('master.roles.destroy');
    });

    Route::middleware('can:manage settings')->group(function () {
        Route::get('/general', function () {
            $user = request()->user();

            abort_unless($user instanceof User, 403);

            $user->load('roles.modules.navigationItems');

            $module = $user->roles
                ->flatMap(fn ($role) => $role->modules)
                ->where('slug', 'general')
                ->first();

            abort_unless($module, 403);

            $isGeneralModule = $module->slug === 'general';

            return view('auth.module-dashboard', [
                'module' => $module,
                'navigationItems' => $module->navigationItems()
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->with(['children' => fn ($query) => $query->where('is_active', true)])
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get(),
                'activitySummaryLogs' => $isGeneralModule
                    ? ActivityLog::query()->latest('logged_at')->limit(3)->get(['id', 'activity', 'actor', 'logged_at', 'details'])
                    : collect(),
                'errorSummaryLogs' => $isGeneralModule
                    ? ErrorLog::query()->latest('occurred_at')->limit(3)->get(['id', 'exception_class', 'message', 'level', 'occurred_at'])
                    : collect(),
            ]);
        })->name('general');
    });

    Route::view('/administration', 'auth.module', [
        'title' => 'Administration',
        'description' => 'Administrative tools and controls will be organized here.',
    ])->name('administration.index');

    Route::middleware('can:view_changelogs')->group(function () {
        Route::get('/administration/changelogs', [ChangelogController::class, 'index'])->name('administration.changelogs.index');
    });

    Route::middleware('can:create_changelogs')->group(function () {
        Route::get('/administration/changelogs/create', [ChangelogController::class, 'create'])->name('administration.changelogs.create');
        Route::post('/administration/changelogs', [ChangelogController::class, 'store'])->name('administration.changelogs.store');
    });

    Route::middleware('can:update_changelogs')->group(function () {
        Route::get('/administration/changelogs/{changelog}/edit', [ChangelogController::class, 'edit'])->name('administration.changelogs.edit');
        Route::match(['put', 'patch'], '/administration/changelogs/{changelog}', [ChangelogController::class, 'update'])->name('administration.changelogs.update');
    });

    Route::middleware('can:delete_changelogs')->group(function () {
        Route::delete('/administration/changelogs/{changelog}', [ChangelogController::class, 'destroy'])->name('administration.changelogs.destroy');
    });

    Route::middleware('can:view_work_progress')->group(function () {
        Route::get('/administration/work-progress', [WorkProgressController::class, 'index'])->name('administration.work-progress.index');
    });

    Route::middleware('can:create_work_progress')->group(function () {
        Route::get('/administration/work-progress/create', [WorkProgressController::class, 'create'])->name('administration.work-progress.create');
        Route::post('/administration/work-progress', [WorkProgressController::class, 'store'])->name('administration.work-progress.store');
    });

    Route::middleware('can:update_work_progress')->group(function () {
        Route::get('/administration/work-progress/{workProgress}/edit', [WorkProgressController::class, 'edit'])->name('administration.work-progress.edit');
        Route::match(['put', 'patch'], '/administration/work-progress/{workProgress}', [WorkProgressController::class, 'update'])->name('administration.work-progress.update');
    });

    Route::middleware('can:delete_work_progress')->group(function () {
        Route::delete('/administration/work-progress/{workProgress}', [WorkProgressController::class, 'destroy'])->name('administration.work-progress.destroy');
    });

    Route::view('/report', 'auth.module', [
        'title' => 'Report',
        'description' => 'Reporting modules and summaries will be available here.',
    ])->name('report.index');

    Route::middleware('can:view_activity_logs')
        ->resource('report/activity-log', ActivityLogController::class)
        ->names('report.activity-log')
        ->only(['index', 'show']);

    Route::middleware('can:view_error_logs')
        ->resource('report/error-logs', ErrorLogController::class)
        ->names('report.error-report')
        ->parameters(['error-logs' => 'errorLog'])
        ->only(['index', 'show']);
});
