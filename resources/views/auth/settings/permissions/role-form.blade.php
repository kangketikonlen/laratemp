<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header title="Kelola Akses Role"
            subtitle="Atur halaman dan aksi apa saja yang bisa dipakai oleh role ini.">
            <x-slot:actions>
                <a href="{{ route('settings.permissions.index') }}"
                    class="private-action-link icon-action tooltip-trigger" aria-label="Kembali ke akses role"
                    title="Kembali ke akses role">
                    <x-ui.icon name="arrow-left" class="h-4 w-4" />
                </a>
            </x-slot:actions>
        </x-private.page-header>

        @if (session('status'))
            <x-private.feedback :message="session('status')" />
        @endif

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel title="Checklist Akses"
            description="Pilih akses seperlunya agar role tetap aman dan mudah dipahami.">
            <form method="POST" action="{{ route('settings.permissions.roles.update', $role) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <x-form.permission-matrix :catalog="$permissionCatalog" input-name="permissions" :selected="old('permissions', $selectedPermissions)"
                    :error="$errors->first('permissions') ?: $errors->first('permissions.*')" />

                <div class="private-form-actions">
                    <x-ui.cancel-link :href="route('settings.permissions.index')" />

                    <x-ui.save-button label="Simpan akses role" />
                </div>
            </form>
        </x-private.panel>
    </div>
</x-layouts.private-module>
