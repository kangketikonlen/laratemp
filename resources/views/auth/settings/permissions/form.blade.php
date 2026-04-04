<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header
            :title="$isEdit ? 'Edit Access Rule' : 'Create Access Rule'"
            :subtitle="$isEdit ? 'Perbarui aturan akses custom untuk kebutuhan khusus.' : 'Buat aturan akses custom hanya jika checklist bawaan di form role atau user belum mencukupi.'"
        >
            <x-slot:actions>
                <a href="{{ route('settings.permissions.index') }}" class="private-action-link">
                    <span>Back to permissions</span>
                </a>
            </x-slot:actions>
        </x-private.page-header>

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel
            :title="$isEdit ? 'Custom Access Form' : 'New Custom Access'"
            description="Untuk kebutuhan umum seperti lihat, tambah, ubah, atau hapus data, lebih mudah memakai checklist akses di form role atau user."
        >
            <div class="private-panel-soft mb-5">
                Alur yang disarankan: atur hak akses dari form role atau user. Halaman ini hanya untuk akses tambahan yang belum tersedia di pustaka standar.
            </div>

            <form
                method="POST"
                action="{{ $isEdit ? route('settings.permissions.update', $permission) : route('settings.permissions.store') }}"
                class="private-form-grid"
            >
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <x-form.field for="name" label="Access Key" :error="$errors->first('name')">
                    <x-form.input
                        id="name"
                        name="name"
                        icon="settings"
                        :value="old('name', $permission->name)"
                        placeholder="approve_expense_reports"
                        :disabled="$isProtected"
                        required
                    />

                    <p class="private-helper-text">Gunakan format sederhana seperti `approve_expense_reports` agar mudah dikenali oleh developer dan administrator.</p>
                </x-form.field>

                <x-form.field for="guard_name" label="Application Scope" :error="$errors->first('guard_name')">
                    <x-form.input
                        id="guard_name"
                        name="guard_name"
                        icon="folder"
                        :value="old('guard_name', $permission->guard_name ?: 'web')"
                        placeholder="web"
                        :disabled="$isProtected"
                        required
                    />
                </x-form.field>

                @if ($isProtected)
                    <div class="private-panel-soft private-field-span-2">
                        Akses bawaan sistem tidak dapat diubah dari sini. Jika membutuhkan kemampuan tambahan, buat access rule baru.
                    </div>
                @endif

                <div class="private-form-actions private-field-span-2">
                    <a href="{{ route('settings.permissions.index') }}" class="private-action-link">Cancel</a>

                    @if (! $isProtected)
                        <x-ui.button type="submit" variant="primary" :block="false">
                            {{ $isEdit ? 'Save Changes' : 'Create Permission' }}
                        </x-ui.button>
                    @endif
                </div>
            </form>
        </x-private.panel>
    </div>
</x-layouts.private-module>
