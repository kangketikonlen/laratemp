<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header :title="$isEdit ? 'Ubah Role' : 'Tambah Role'" :subtitle="$isEdit ? 'Perbarui identitas role dan penetapan modul.' : 'Tambahkan role baru ke sistem.'">
            <x-slot:actions>
                <a href="{{ route('master.roles.index') }}" class="private-action-link icon-action tooltip-trigger"
                    aria-label="Kembali ke role" title="Kembali ke role">
                    <x-ui.icon name="arrow-left" class="h-4 w-4" />
                </a>
            </x-slot:actions>
        </x-private.page-header>

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel :title="$isEdit ? 'Form Ubah Role' : 'Form Tambah Role'"
            description="Isi identifier role, nama tampilan, deskripsi, dan modul yang akan dapat diakses.">
            <form method="POST"
                action="{{ $isEdit ? route('master.roles.update', $role) : route('master.roles.store') }}"
                class="private-form-grid">
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <x-form.field for="name" label="Nama Role" :error="$errors->first('name')">
                    <x-form.input id="name" name="name" icon="folder" :value="old('name', $role->name)"
                        placeholder="admin_support" required />
                </x-form.field>

                <x-form.field for="display_name" label="Nama Tampilan" :error="$errors->first('display_name')">
                    <x-form.input id="display_name" name="display_name" icon="folder" :value="old('display_name', $role->display_name)"
                        placeholder="Admin Support" />
                </x-form.field>

                <x-form.field for="description" label="Deskripsi" :error="$errors->first('description')" class="private-field-span-2">
                    <x-form.rich-editor id="description" name="description" :value="old('description', $role->description)"
                        placeholder="Jelaskan tanggung jawab atau cakupan akses untuk role ini." />
                </x-form.field>

                <x-form.field label="Modul" :error="$errors->first('modules') ?: $errors->first('modules.*')" class="private-field-span-2">
                    <div class="private-checkbox-grid">
                        @forelse ($modules as $module)
                            @php
                                $moduleId = $module->id;
                                $checked = in_array($moduleId, old('modules', $selectedModules), true);
                            @endphp

                            <label class="private-checkbox-card">
                                <input type="checkbox" name="modules[]" value="{{ $moduleId }}"
                                    @checked($checked)>
                                <span>
                                    <span class="private-checkbox-title">{{ $module->name }}</span>
                                    <span class="private-checkbox-description">
                                        {{ $module->description ?: 'Modul tanpa deskripsi tambahan.' }}
                                    </span>
                                </span>
                            </label>
                        @empty
                            <div class="private-panel-empty">
                                Belum ada modul yang tersedia. Tambahkan modul terlebih dahulu sebelum menetapkan ke
                                role.
                            </div>
                        @endforelse
                    </div>
                </x-form.field>

                <x-form.permission-matrix :catalog="$permissionCatalog" input-name="permissions" :selected="old('permissions', $selectedPermissions)"
                    :error="$errors->first('permissions') ?: $errors->first('permissions.*')" />

                <div class="private-form-actions private-field-span-2">
                    <x-ui.cancel-link :href="route('master.roles.index')" />

                    <x-ui.save-button :label="$isEdit ? 'Simpan perubahan' : 'Simpan role'" />
                </div>
            </form>
        </x-private.panel>
    </div>
</x-layouts.private-module>
