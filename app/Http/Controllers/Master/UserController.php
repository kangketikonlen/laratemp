<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\StoreUserRequest;
use App\Http\Requests\Master\UpdateUserRequest;
use App\Models\Settings\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->string('search'));

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
            ->orderBy('name')
            ->orderBy('username')
            ->paginate(10)
            ->withQueryString();

        return view('auth.master.users.index', [
            'title' => 'User',
            'description' => 'Manage user records from the master section.',
            'search' => $search,
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
            'isEdit' => false,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::query()->create($request->validatedUserData());
        $user->syncRoles($request->validatedRoleNames());

        return redirect()
            ->route('master.users.index')
            ->with('status', "User {$user->username} berhasil dibuat.");
    }

    public function edit(User $user): View
    {
        $user->load('roles');

        return view('auth.master.users.form', [
            'title' => 'User',
            'description' => 'Update an existing user record from the master section.',
            'user' => $user,
            'roles' => Role::query()->orderBy('display_name')->orderBy('name')->get(),
            'selectedRoles' => $user->roles->pluck('name')->all(),
            'isEdit' => true,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validatedUserData());
        $user->syncRoles($request->validatedRoleNames());

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

        return redirect()
            ->route('master.users.index')
            ->with('status', "User {$username} berhasil dihapus.");
    }
}
