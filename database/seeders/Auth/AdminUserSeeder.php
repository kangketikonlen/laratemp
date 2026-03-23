<?php

namespace Database\Seeders\Auth;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        if (! config('bootstrap_admin.enabled')) {
            return;
        }

        $password = config('bootstrap_admin.password');

        if (blank($password)) {
            Log::warning('Bootstrap admin seeding skipped because BOOTSTRAP_ADMIN_PASSWORD is not configured.');

            return;
        }

        $user = User::query()->updateOrCreate(
            ['username' => config('bootstrap_admin.username')],
            [
                'name' => config('bootstrap_admin.name'),
                'email' => config('bootstrap_admin.email'),
                'password' => Hash::make($password),
            ],
        );

        $user->syncRoles(['admin']);
    }
}
