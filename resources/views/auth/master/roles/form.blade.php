<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header
            :title="$isEdit ? 'Edit Role' : 'Create Role'"
            :subtitle="$isEdit ? 'Perbarui identitas role dan assignment module.' : 'Tambahkan role baru ke sistem.'"
        >
            <x-slot:actions>
                <a href="{{ route('master.roles.index') }}" class="private-action-link">
                    <span>Back to roles</span>
                </a>
            </x-slot:actions>
        </x-private.page-header>

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel
            :title="$isEdit ? 'Form Edit Role' : 'Form Create Role'"
            description="Isi identifier role, nama tampilan, deskripsi, dan module yang akan dapat diakses."
        >
            <form
                method="POST"
                action="{{ $isEdit ? route('master.roles.update', $role) : route('master.roles.store') }}"
                class="private-form-grid"
            >
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <x-form.field for="name" label="Role Name" :error="$errors->first('name')">
                    <x-form.input
                        id="name"
                        name="name"
                        icon="folder"
                        :value="old('name', $role->name)"
                        placeholder="admin_support"
                        required
                    />
                </x-form.field>

                <x-form.field for="display_name" label="Display Name" :error="$errors->first('display_name')">
                    <x-form.input
                        id="display_name"
                        name="display_name"
                        icon="folder"
                        :value="old('display_name', $role->display_name)"
                        placeholder="Admin Support"
                    />
                </x-form.field>

                <x-form.field for="description" label="Description" :error="$errors->first('description')" class="private-field-span-2">
                    <x-form.rich-editor
                        id="description"
                        name="description"
                        :value="old('description', $role->description)"
                        placeholder="Jelaskan tanggung jawab atau cakupan akses untuk role ini."
                    />
                </x-form.field>

                <x-form.field
                    label="Modules"
                    :error="$errors->first('modules') ?: $errors->first('modules.*')"
                    class="private-field-span-2"
                >
                    <div class="private-checkbox-grid">
                        @forelse ($modules as $module)
                            @php
                                $moduleId = $module->id;
                                $checked = in_array($moduleId, old('modules', $selectedModules), true);
                            @endphp

                            <label class="private-checkbox-card">
                                <input
                                    type="checkbox"
                                    name="modules[]"
                                    value="{{ $moduleId }}"
                                    @checked($checked)
                                >
                                <span>
                                    <span class="private-checkbox-title">{{ $module->name }}</span>
                                    <span class="private-checkbox-description">
                                        {{ $module->description ?: 'Module tanpa deskripsi tambahan.' }}
                                    </span>
                                </span>
                            </label>
                        @empty
                            <div class="private-panel-empty">
                                Belum ada module yang tersedia. Tambahkan module terlebih dahulu sebelum assign ke role.
                            </div>
                        @endforelse
                    </div>
                </x-form.field>

                <div class="private-form-actions private-field-span-2">
                    <a href="{{ route('master.roles.index') }}" class="private-action-link">Cancel</a>

                    <x-ui.button type="submit" variant="primary" :block="false">
                        {{ $isEdit ? 'Save Changes' : 'Create Role' }}
                    </x-ui.button>
                </div>
            </form>
        </x-private.panel>
    </div>
</x-layouts.private-module>
