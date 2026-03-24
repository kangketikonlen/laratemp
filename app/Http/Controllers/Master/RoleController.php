<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreRoleRequest;
use App\Http\Requests\Master\UpdateRoleRequest;
use App\Models\Settings\Module;
use App\Models\Settings\Role;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

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
            ->orderByDesc('is_system')
            ->orderBy('display_name')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('auth.master.roles.index', [
            'title' => 'Role',
            'description' => 'Manage role records from the master section.',
            'search' => $search,
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
            'isEdit' => false,
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        $role = Role::query()->create($request->validatedRoleData());
        $role->modules()->sync($request->validatedModuleIds());

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
