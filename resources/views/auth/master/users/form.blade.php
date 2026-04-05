<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header :title="$isEdit ? 'Ubah Pengguna' : 'Tambah Pengguna'" :subtitle="$isEdit ? 'Perbarui data pengguna dan penetapan role.' : 'Tambahkan pengguna baru ke sistem.'">
            <x-slot:actions>
                <a href="{{ route('master.users.index') }}" class="private-action-link icon-action tooltip-trigger" aria-label="Kembali ke pengguna" title="Kembali ke pengguna">
                    <x-ui.icon name="arrow-left" class="h-4 w-4" />
                </a>
            </x-slot:actions>
        </x-private.page-header>

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel :title="$isEdit ? 'Form Ubah Pengguna' : 'Form Tambah Pengguna'" description="Isi identitas utama, kredensial login, dan role yang akan melekat pada akun.">
            <form method="POST" action="{{ $isEdit ? route('master.users.update', $user) : route('master.users.store') }}" class="private-form-grid">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <x-form.field for="name" label="Nama Lengkap" :error="$errors->first('name')">
                    <x-form.input id="name" name="name" icon="user" :value="old('name', $user->name)" placeholder="Nama lengkap user" required />
                </x-form.field>

                <x-form.field for="username" label="Username" :error="$errors->first('username')">
                    <x-form.input id="username" name="username" icon="user" :value="old('username', $user->username)" placeholder="username_login" required />
                </x-form.field>

                <x-form.field for="email" label="Email" :error="$errors->first('email')" class="private-field-span-2">
                    <x-form.input id="email" name="email" type="email" icon="user" :value="old('email', $user->email)" placeholder="user@example.com" required />
                </x-form.field>

                <x-form.field for="password" :label="$isEdit ? 'Password Baru' : 'Password'" :error="$errors->first('password')">
                    <x-form.password-input id="password" name="password" :placeholder="$isEdit ? 'Kosongkan jika tidak ingin mengubah password' : 'Minimal 8 karakter'" :required="! $isEdit" />
                </x-form.field>

                <x-form.field for="password_confirmation" label="Konfirmasi Password" class="private-field">
                    <x-form.password-input id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" :required="! $isEdit" />
                </x-form.field>

                <x-form.field label="Role" :error="$errors->first('roles') ?: $errors->first('roles.*')" class="private-field-span-2">
                    <div class="private-checkbox-grid">
                        @forelse ($roles as $role)
                            @php
                                $roleName = $role->name;
                                $roleLabel = $role->display_name ?? $roleName;
                                $checked = in_array($roleName, old('roles', $selectedRoles), true);
                            @endphp

                            <label class="private-checkbox-card">
                                <input type="checkbox" name="roles[]" value="{{ $roleName }}" @checked($checked)>
                                <span>
                                    <span class="private-checkbox-title">{{ $roleLabel }}</span>
                                    <span class="private-checkbox-description">
                                        {{ $role->description ?: 'Role tanpa deskripsi tambahan.' }}
                                    </span>
                                </span>
                            </label>
                        @empty
                            <div class="private-panel-empty">
                                Belum ada role yang tersedia. Tambahkan role terlebih dahulu sebelum menetapkan ke pengguna.
                            </div>
                        @endforelse
                    </div>
                </x-form.field>

                <x-form.permission-matrix :catalog="$permissionCatalog" input-name="permissions" :selected="old('permissions', $selectedPermissions)" :error="$errors->first('permissions') ?: $errors->first('permissions.*')" />

                <div class="private-form-actions private-field-span-2">
                    <x-ui.cancel-link :href="route('master.users.index')" />

                    <x-ui.save-button :label="$isEdit ? 'Simpan perubahan' : 'Simpan pengguna'" />
                </div>
            </form>
        </x-private.panel>
    </div>
</x-layouts.private-module>
