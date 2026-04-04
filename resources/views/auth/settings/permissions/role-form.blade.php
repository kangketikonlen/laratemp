<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header
            title="Manage Role Access"
            subtitle="Atur halaman dan aksi apa saja yang bisa dipakai oleh role ini."
        >
            <x-slot:actions>
                <a href="{{ route('settings.permissions.index') }}" class="private-action-link">
                    <span>Back to role access</span>
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
                <p class="institution-settings-kicker">Role Access</p>
                <h2 class="institution-settings-title">{{ $role->display_name ?: $role->name }}</h2>
                <p class="institution-settings-copy">
                    Centang akses yang boleh digunakan oleh role ini. Perubahan akan langsung memengaruhi semua user yang memakai role tersebut.
                </p>
            </div>

            <div class="institution-settings-hero-grid">
                <div class="institution-settings-stat">
                    <span class="institution-settings-stat-label">Role Name</span>
                    <span class="institution-settings-stat-value">{{ $role->name }}</span>
                </div>
                <div class="institution-settings-stat">
                    <span class="institution-settings-stat-label">Current Access</span>
                    <span class="institution-settings-stat-value">{{ $role->permissions->count() }} actions</span>
                </div>
            </div>
        </section>

        <x-private.panel
            title="Access Checklist"
            description="Pilih akses seperlunya agar role tetap aman dan mudah dipahami."
        >
            <form method="POST" action="{{ route('settings.permissions.roles.update', $role) }}" class="space-y-6">
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
                        Save Role Access
                    </x-ui.button>
                </div>
            </form>
        </x-private.panel>
    </div>
</x-layouts.private-module>
