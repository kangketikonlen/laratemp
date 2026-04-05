<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\StorePermissionRequest;
use App\Http\Requests\Settings\UpdatePermissionRequest;
use App\Http\Requests\Settings\UpdateRolePermissionsRequest;
use App\Http\Requests\Settings\UpdateUserPermissionsRequest;
use App\Models\Settings\Role;
use App\Models\User;
use App\Support\Permissions\PermissionCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    private const PROTECTED_PERMISSIONS = [
        'manage settings',
    ];

    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $sort = (string) $request->string('sort', 'display_name');
        $direction = strtolower((string) $request->string('direction', 'asc'));
        $allowedSorts = ['display_name', 'name', 'users_count', 'permissions_count'];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'display_name';
        }

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'asc';
        }

        $roles = Role::query()
            ->withCount(['users', 'permissions'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($roleQuery) use ($search) {
                    $roleQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('display_name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $direction)
            ->when($sort !== 'display_name', fn ($query) => $query->orderByRaw('COALESCE(display_name, name) asc'))
            ->when($sort !== 'name', fn ($query) => $query->orderBy('name'))
            ->paginate(10)
            ->withQueryString();

        $users = User::query()
            ->withCount(['permissions', 'roles'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($userQuery) use ($search) {
                    $userQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->orderBy('username')
            ->paginate(10, ['*'], 'users_page')
            ->withQueryString();

        return view('auth.settings.permissions.index', [
            'title' => 'Permission',
            'description' => 'Manage permission records from the settings section.',
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
            'roles' => $roles,
            'users' => $users,
            'catalogPermissionsCount' => count(PermissionCatalog::names()),
        ]);
    }

    public function editRole(Role $role): View
    {
        $role->load('permissions');

        return view('auth.settings.permissions.role-form', [
            'title' => 'Permission',
            'description' => 'Manage role access from the settings section.',
            'role' => $role,
            'permissionCatalog' => PermissionCatalog::sections(),
            'selectedPermissions' => $role->permissions->pluck('name')->all(),
            'scopeLabel' => 'Role Access',
            'backLabel' => 'Back to access manager',
            'saveLabel' => 'Save Role Access',
        ]);
    }

    public function updateRole(UpdateRolePermissionsRequest $request, Role $role): RedirectResponse
    {
        $role->syncPermissions($request->validatedPermissionNames());
        $roleLabel = $role->display_name ?: $role->name;

        return redirect()
            ->route('settings.permissions.index')
            ->with('status', "Akses untuk role {$roleLabel} berhasil diperbarui.");
    }

    public function editUser(User $user): View
    {
        $user->load('permissions', 'roles');

        return view('auth.settings.permissions.user-form', [
            'title' => 'Permission',
            'description' => 'Manage user direct access from the settings section.',
            'user' => $user,
            'permissionCatalog' => PermissionCatalog::sections(),
            'selectedPermissions' => $user->permissions->pluck('name')->all(),
        ]);
    }

    public function updateUser(UpdateUserPermissionsRequest $request, User $user): RedirectResponse
    {
        $user->syncPermissions($request->validatedPermissionNames());
        $userLabel = $user->name ?: $user->username;

        return redirect()
            ->route('settings.permissions.index')
            ->with('status', "Akses langsung untuk user {$userLabel} berhasil diperbarui.");
    }

    public function create(): View
    {
        return view('auth.settings.permissions.form', [
            'title' => 'Permission',
            'description' => 'Create a new permission record from the settings section.',
            'permission' => new Permission(['guard_name' => 'web']),
            'isEdit' => false,
            'isProtected' => false,
        ]);
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        $permission = Permission::query()->create($request->validatedPermissionData());

        return redirect()
            ->route('settings.permissions.index')
            ->with('status', "Permission {$permission->name} berhasil dibuat.");
    }

    public function edit(Permission $permission): View
    {
        return view('auth.settings.permissions.form', [
            'title' => 'Permission',
            'description' => 'Update an existing permission record from the settings section.',
            'permission' => $permission,
            'isEdit' => true,
            'isProtected' => $this->isProtected($permission),
        ]);
    }

    public function update(UpdatePermissionRequest $request, Permission $permission): RedirectResponse
    {
        if ($this->isProtected($permission)) {
            return redirect()
                ->route('settings.permissions.index')
                ->withErrors([
                    'permission' => 'Permission sistem tidak dapat diubah.',
                ]);
        }

        $permission->update($request->validatedPermissionData());

        return redirect()
            ->route('settings.permissions.index')
            ->with('status', "Permission {$permission->name} berhasil diperbarui.");
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        if ($this->isProtected($permission)) {
            return redirect()
                ->route('settings.permissions.index')
                ->withErrors([
                    'permission' => 'Permission sistem tidak dapat dihapus.',
                ]);
        }

        $permissionName = $permission->name;
        $permission->delete();

        return redirect()
            ->route('settings.permissions.index')
            ->with('status', "Permission {$permissionName} berhasil dihapus.");
    }

    private function isProtected(Permission $permission): bool
    {
        return in_array($permission->name, self::PROTECTED_PERMISSIONS, true);
    }
}
