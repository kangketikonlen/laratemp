@props([
    'catalog' => [],
    'error' => null,
    'inputName' => 'permissions',
    'selected' => [],
])

<x-form.field
    label="What Can They Access?"
    :error="$error"
    class="private-field-span-2"
>
    <div class="permission-matrix-intro">
        Pilih akses yang benar-benar dibutuhkan. Checklist ini langsung menentukan halaman mana yang bisa dibuka dan aksi mana yang bisa dilakukan.
    </div>

    <div class="permission-matrix">
        @foreach ($catalog as $section)
            <section class="permission-matrix-section" x-data="permissionMatrixSection()">
                <div class="permission-matrix-head">
                    <div>
                        <h3 class="permission-matrix-title">{{ $section['title'] }}</h3>
                        <p class="permission-matrix-copy">{{ $section['description'] }}</p>
                    </div>

                    <button
                        type="button"
                        class="permission-matrix-toggle"
                        @click="toggleAll"
                        x-text="allChecked ? 'Clear {{ $section['bulk_label'] }}' : '{{ $section['bulk_label'] }}'"
                    ></button>
                </div>

                <div class="permission-matrix-grid">
                    @foreach ($section['permissions'] as $permission)
                        <label class="permission-matrix-card">
                            <input
                                type="checkbox"
                                name="{{ $inputName }}[]"
                                value="{{ $permission['name'] }}"
                                data-permission-checkbox
                                @checked(in_array($permission['name'], $selected, true))
                            >

                            <span>
                                <span class="permission-matrix-card-title">{{ $section['title'] }} · {{ $permission['label'] }}</span>
                                <span class="permission-matrix-card-copy">{{ $permission['description'] }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</x-form.field>
