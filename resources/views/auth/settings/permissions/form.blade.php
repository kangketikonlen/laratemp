<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header :title="$isEdit ? 'Ubah Aturan Akses' : 'Tambah Aturan Akses'" :subtitle="$isEdit
            ? 'Perbarui aturan akses kustom untuk kebutuhan khusus.'
            : 'Buat aturan akses kustom hanya jika checklist bawaan di form role atau pengguna belum mencukupi.'">
            <x-slot:actions>
                <a href="{{ route('settings.permissions.index') }}"
                    class="private-action-link icon-action tooltip-trigger" aria-label="Kembali ke akses"
                    title="Kembali ke akses">
                    <x-ui.icon name="arrow-left" class="h-4 w-4" />
                </a>
            </x-slot:actions>
        </x-private.page-header>

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel :title="$isEdit ? 'Form Akses Kustom' : 'Akses Kustom Baru'"
            description="Untuk kebutuhan umum seperti lihat, tambah, ubah, atau hapus data, lebih mudah memakai checklist akses di form role atau pengguna.">
            <div class="private-panel-soft mb-5">
                Alur yang disarankan: atur hak akses dari form role atau pengguna. Halaman ini hanya untuk akses
                tambahan yang belum tersedia di pustaka standar.
            </div>

            <form method="POST"
                action="{{ $isEdit ? route('settings.permissions.update', $permission) : route('settings.permissions.store') }}"
                class="private-form-grid">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <x-form.field for="name" label="Kunci Akses" :error="$errors->first('name')">
                    <x-form.input id="name" name="name" icon="settings" :value="old('name', $permission->name)"
                        placeholder="approve_expense_reports" :disabled="$isProtected" required />

                    <p class="private-helper-text">Gunakan format sederhana seperti `approve_expense_reports` agar mudah
                        dikenali oleh developer dan administrator.</p>
                </x-form.field>

                <x-form.field for="guard_name" label="Cakupan Aplikasi" :error="$errors->first('guard_name')">
                    <x-form.input id="guard_name" name="guard_name" icon="folder" :value="old('guard_name', $permission->guard_name ?: 'web')" placeholder="web"
                        :disabled="$isProtected" required />
                </x-form.field>

                @if ($isProtected)
                    <div class="private-panel-soft private-field-span-2">
                        Akses bawaan sistem tidak dapat diubah dari sini. Jika membutuhkan kemampuan tambahan, buat
                        aturan akses baru.
                    </div>
                @endif

                <div class="private-form-actions private-field-span-2">
                    <x-ui.cancel-link :href="route('settings.permissions.index')" />

                    @if (!$isProtected)
                        <x-ui.save-button :label="$isEdit ? 'Simpan perubahan' : 'Simpan akses'" />
                    @endif
                </div>
            </form>
        </x-private.panel>
    </div>
</x-layouts.private-module>
