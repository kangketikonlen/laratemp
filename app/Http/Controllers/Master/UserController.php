<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreUserRequest;
use App\Http\Requests\Master\UpdateUserRequest;
use App\Models\Settings\Role;
use App\Models\User;
use App\Support\ActivityLogs\LogsUserActivity;
use App\Support\Permissions\PermissionCatalog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use LogsUserActivity;

    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));
        $sort = (string) $request->string('sort', 'name');
        $direction = strtolower((string) $request->string('direction', 'asc'));
        $allowedSorts = ['name', 'username', 'email', 'created_at'];

        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'name';
        }

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = 'asc';
        }

        $users = User::query()
            ->with('roles')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($userQuery) use ($search) {
                    $userQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy($sort, $direction)
            ->when($sort !== 'name', fn ($query) => $query->orderBy('name'))
            ->when($sort !== 'username', fn ($query) => $query->orderBy('username'))
            ->paginate(10)
            ->withQueryString();

        return view('auth.master.users.index', [
            'title' => 'User',
            'description' => 'Manage user records from the master section.',
            'search' => $search,
            'sort' => $sort,
            'direction' => $direction,
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        return view('auth.master.users.form', [
            'title' => 'User',
            'description' => 'Create a new user record from the master section.',
            'user' => new User,
            'roles' => Role::query()->orderBy('display_name')->orderBy('name')->get(),
            'selectedRoles' => [],
            'permissionCatalog' => PermissionCatalog::sections(),
            'selectedPermissions' => [],
            'isEdit' => false,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::query()->create($request->validatedUserData());
        $user->syncRoles($request->validatedRoleNames());
        $user->syncPermissions($request->validatedPermissionNames());

        $this->logUserActivity(
            activity: 'Created user account',
            category: 'user',
            status: 'success',
            user: $request->user(),
            request: $request,
            context: [
                'username' => $user->username,
                'roles' => $request->validatedRoleNames(),
            ],
        );

        return redirect()
            ->route('master.users.index')
            ->with('status', "User {$user->username} berhasil dibuat.");
    }

    public function edit(User $user): View
    {
        $user->load('roles', 'permissions');

        return view('auth.master.users.form', [
            'title' => 'User',
            'description' => 'Update an existing user record from the master section.',
            'user' => $user,
            'roles' => Role::query()->orderBy('display_name')->orderBy('name')->get(),
            'selectedRoles' => $user->roles->pluck('name')->all(),
            'permissionCatalog' => PermissionCatalog::sections(),
            'selectedPermissions' => $user->permissions->pluck('name')->all(),
            'isEdit' => true,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validatedUserData());
        $user->syncRoles($request->validatedRoleNames());
        $user->syncPermissions($request->validatedPermissionNames());

        $this->logUserActivity(
            activity: 'Updated user account',
            category: 'user',
            status: 'success',
            user: $request->user(),
            request: $request,
            context: [
                'username' => $user->username,
                'roles' => $request->validatedRoleNames(),
            ],
        );

        return redirect()
            ->route('master.users.index')
            ->with('status', "User {$user->username} berhasil diperbarui.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()?->is($user)) {
            return redirect()
                ->route('master.users.index')
                ->withErrors([
                    'user' => 'Anda tidak dapat menghapus akun yang sedang digunakan.',
                ]);
        }

        $username = $user->username;

        $user->delete();

        $this->logUserActivity(
            activity: 'Deleted user account',
            category: 'user',
            status: 'warning',
            user: $request->user(),
            request: $request,
            context: [
                'username' => $username,
            ],
        );

        return redirect()
            ->route('master.users.index')
            ->with('status', "User {$username} berhasil dihapus.");
    }
}
