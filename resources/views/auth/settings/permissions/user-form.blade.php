<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header
            title="Manage User Access"
            subtitle="Atur akses langsung untuk user ini di luar role yang mereka miliki."
        >
            <x-slot:actions>
                <a href="{{ route('settings.permissions.index') }}" class="private-action-link">
                    <span>Back to access manager</span>
                </a>
            </x-slot:actions>
        </x-private.page-header>

        @if (session('status'))
            <x-private.feedback :message="session('status')" />
        @endif

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <section class="institution-settings-hero">
            <div>
                <p class="institution-settings-kicker">Direct User Access</p>
                <h2 class="institution-settings-title">{{ $user->name }}</h2>
                <p class="institution-settings-copy">
                    Gunakan akses langsung hanya untuk pengecualian khusus. Untuk kebutuhan umum, tetap utamakan pengaturan lewat role.
                </p>
            </div>

            <div class="institution-settings-hero-grid">
                <div class="institution-settings-stat">
                    <span class="institution-settings-stat-label">Username</span>
                    <span class="institution-settings-stat-value">{{ $user->username }}</span>
                </div>
                <div class="institution-settings-stat">
                    <span class="institution-settings-stat-label">Current Direct Access</span>
                    <span class="institution-settings-stat-value">{{ $user->permissions->count() }} actions</span>
                </div>
            </div>
        </section>

        <x-private.panel
            title="Direct Access Checklist"
            description="Checklist ini menambah hak akses khusus untuk user ini tanpa mengubah role yang mereka punya."
        >
            <div class="private-panel-soft mb-5">
                Rekomendasi: pakai role untuk akses utama, lalu gunakan direct access hanya bila benar-benar perlu override khusus.
            </div>

            <form method="POST" action="{{ route('settings.permissions.users.update', $user) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <x-form.permission-matrix
                    :catalog="$permissionCatalog"
                    input-name="permissions"
                    :selected="old('permissions', $selectedPermissions)"
                    :error="$errors->first('permissions') ?: $errors->first('permissions.*')"
                />

                <div class="private-form-actions">
                    <a href="{{ route('settings.permissions.index') }}" class="private-action-link">Cancel</a>

                    <x-ui.button type="submit" variant="primary" :block="false">
                        Save Direct Access
                    </x-ui.button>
                </div>
            </form>
        </x-private.panel>
    </div>
</x-layouts.private-module>
