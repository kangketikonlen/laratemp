<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreRoleRequest;
use App\Http\Requests\Master\UpdateRoleRequest;
use App\Models\Settings\Module;
use App\Models\Settings\Role;
use App\Support\Permissions\PermissionCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $sort = (string) $request->string('sort', 'display_name');
        $direction = strtolower((string) $request->string('direction', 'asc'));
        $allowedSorts = ['display_name', 'name', 'users_count', 'is_system'];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'display_name';
        }

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'asc';
        }

        $roles = Role::query()
            ->with(['modules'])
            ->withCount(['users', 'modules'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($roleQuery) use ($search) {
                    $roleQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('display_name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $direction)
            ->when($sort !== 'is_system', fn ($query) => $query->orderByDesc('is_system'))
            ->when($sort !== 'display_name', fn ($query) => $query->orderByRaw('COALESCE(display_name, name) asc'))
            ->when($sort !== 'name', fn ($query) => $query->orderBy('name'))
            ->paginate(10)
            ->withQueryString();

        return view('auth.master.roles.index', [
            'title' => 'Role',
            'description' => 'Manage role records from the master section.',
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
            'roles' => $roles,
        ]);
    }

    public function create(): View
    {
        return view('auth.master.roles.form', [
            'title' => 'Role',
            'description' => 'Create a new role record from the master section.',
            'role' => new Role(['guard_name' => 'web']),
            'modules' => Module::query()->orderBy('sort_order')->orderBy('name')->get(),
            'selectedModules' => [],
            'permissionCatalog' => PermissionCatalog::sections(),
            'selectedPermissions' => [],
            'isEdit' => false,
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::query()->create($request->validatedRoleData());
        $role->modules()->sync($request->validatedModuleIds());
        $role->syncPermissions($request->validatedPermissionNames());

        return redirect()
            ->route('master.roles.index')
            ->with('status', "Role {$role->name} berhasil dibuat.");
    }

    public function edit(Role $role): View
    {
        $role->load('modules');

        return view('auth.master.roles.form', [
            'title' => 'Role',
            'description' => 'Update an existing role record from the master section.',
            'role' => $role,
            'modules' => Module::query()->orderBy('sort_order')->orderBy('name')->get(),
            'selectedModules' => $role->modules->pluck('id')->all(),
            'permissionCatalog' => PermissionCatalog::sections(),
            'selectedPermissions' => $role->permissions->pluck('name')->all(),
            'isEdit' => true,
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role): RedirectResponse
    {
        if ($role->is_system) {
            return redirect()
                ->route('master.roles.index')
                ->withErrors([
                    'role' => 'Role sistem tidak dapat diubah.',
                ]);
        }

        $role->update($request->validatedRoleData());
        $role->modules()->sync($request->validatedModuleIds());
        $role->syncPermissions($request->validatedPermissionNames());

        return redirect()
            ->route('master.roles.index')
            ->with('status', "Role {$role->name} berhasil diperbarui.");
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->is_system) {
            return redirect()
                ->route('master.roles.index')
                ->withErrors([
                    'role' => 'Role sistem tidak dapat dihapus.',
                ]);
        }

        $roleName = $role->name;
        $role->delete();

        return redirect()
            ->route('master.roles.index')
            ->with('status', "Role {$roleName} berhasil dihapus.");
    }
}
