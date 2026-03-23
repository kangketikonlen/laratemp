<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $this->addUsernameColumnIfMissing();
        $this->backfillMissingUsernames();
        $this->addUsernameIndexIfMissing();
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'username')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if ($this->hasUniqueIndex('users', 'users_username_unique')) {
                $table->dropUnique(['username']);
            }

            $table->dropColumn('username');
        });
    }

    protected function addUsernameColumnIfMissing(): void
    {
        if (Schema::hasColumn('users', 'username')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('email_verified_at');
        });
    }

    protected function backfillMissingUsernames(): void
    {
        DB::table('users')
            ->select(['id', 'name', 'email', 'username'])
            ->orderBy('id')
            ->get()
            ->each(function (object $user): void {
                if (filled($user->username)) {
                    return;
                }

                $baseUsername = Str::slug($user->name ?: Str::before($user->email, '@'), separator: '_');
                $baseUsername = $baseUsername !== '' ? $baseUsername : 'user';

                $username = $baseUsername;
                $suffix = 1;

                while (
                    DB::table('users')
                        ->where('username', $username)
                        ->where('id', '!=', $user->id)
                        ->exists()
                ) {
                    $username = "{$baseUsername}_{$suffix}";
                    $suffix++;
                }

                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['username' => $username]);
            });
    }

    protected function addUsernameIndexIfMissing(): void
    {
        if ($this->hasUniqueIndex('users', 'users_username_unique')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->unique('username');
        });
    }

    protected function hasUniqueIndex(string $table, string $indexName): bool
    {
        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::raw('DATABASE()'))
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->exists();
    }
};
