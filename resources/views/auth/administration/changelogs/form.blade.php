<x-layouts.private-module :title="$title" :description="$description">
    @php
        $releaseDateValue = old('released_at', $changelog->released_at?->format('Y-m-d'));
        $versionValue = old('version', $changelog->version ?: \App\Models\Administration\Changelog::defaultVersion($releaseDateValue));
    @endphp

    <div class="private-page">
        <x-private.page-header
            :title="$isEdit ? 'Edit Changelog' : 'Create Changelog'"
            :subtitle="$isEdit ? 'Perbarui detail rilis dan catatan perubahannya.' : 'Tambahkan catatan rilis baru untuk tim.'"
        >
            <x-slot:actions>
                <a href="{{ route('administration.changelogs.index') }}" class="private-action-link">
                    <span>Back to changelogs</span>
                </a>
            </x-slot:actions>
        </x-private.page-header>

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel
            :title="$isEdit ? 'Form Edit Changelog' : 'Form Create Changelog'"
            description="Siapkan identitas rilis terlebih dahulu, lalu tulis release notes yang akan tampil di dashboard."
        >
            <form
                method="POST"
                action="{{ $isEdit ? route('administration.changelogs.update', $changelog) : route('administration.changelogs.store') }}"
                class="space-y-6"
                data-changelog-form
            >
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <section class="changelog-form-section">
                    <div class="changelog-form-section-head">
                        <div>
                            <h3 class="changelog-form-section-title">Release Setup</h3>
                            <p class="changelog-form-section-copy">
                                Tanggal rilis akan membuat versi default secara otomatis. Anda masih bisa mengganti versi kapan saja jika dibutuhkan.
                            </p>
                        </div>

                        <div class="changelog-form-version-hint">
                            Format default:
                            <span class="changelog-form-version-code">v{{ now()->format('Y.m.d') }}</span>
                        </div>
                    </div>

                    <div class="changelog-form-meta-grid">
                        <x-form.field for="released_at" label="Release Date" :error="$errors->first('released_at')">
                            <x-form.input
                                id="released_at"
                                name="released_at"
                                type="date"
                                icon=""
                                :value="$releaseDateValue"
                                data-changelog-release-date
                            />
                        </x-form.field>

                        <x-form.field for="version" label="Version" :error="$errors->first('version')">
                            <x-form.input
                                id="version"
                                name="version"
                                icon="clipboard"
                                :value="$versionValue"
                                placeholder="v2026.04.05"
                                data-changelog-version
                            />
                        </x-form.field>

                        <x-form.field for="status" label="Status" :error="$errors->first('status')">
                            <select id="status" name="status" class="input-base" required>
                                @foreach (['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $changelog->status ?: 'draft') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </x-form.field>
                    </div>
                </section>

                <section class="changelog-form-section">
                    <div class="changelog-form-section-head">
                        <div>
                            <h3 class="changelog-form-section-title">Release Content</h3>
                        </div>
                    </div>

                    <div class="private-form-grid">
                        <x-form.field for="title" label="Title" :error="$errors->first('title')" class="private-field-span-2">
                            <x-form.input
                                id="title"
                                name="title"
                                icon="clipboard"
                                :value="old('title', $changelog->title)"
                                placeholder="Improve role-based access manager"
                                required
                            />
                        </x-form.field>

                        <x-form.field for="notes" label="Release Notes" :error="$errors->first('notes')" class="private-field-span-2">
                            <x-form.rich-editor
                                id="notes"
                                name="notes"
                                :value="old('notes', $changelog->notes)"
                                placeholder="Tulis detail perubahan, bug fix, dan highlight rilis. Ringkasan singkat akan diambil otomatis dari catatan ini."
                            />
                        </x-form.field>
                    </div>
                </section>

                <div class="private-form-actions">
                    <a href="{{ route('administration.changelogs.index') }}" class="private-action-link">Cancel</a>

                    <x-ui.button type="submit" variant="primary" :block="false">
                        {{ $isEdit ? 'Save Changes' : 'Create Changelog' }}
                    </x-ui.button>
                </div>
            </form>
        </x-private.panel>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('[data-changelog-form]');

            if (! form) {
                return;
            }

            const versionInput = form.querySelector('[data-changelog-version]');
            const releaseDateInput = form.querySelector('[data-changelog-release-date]');

            if (! versionInput || ! releaseDateInput) {
                return;
            }

            const makeVersion = (dateValue) => {
                if (! dateValue) {
                    const today = new Date();
                    const yyyy = today.getFullYear();
                    const mm = String(today.getMonth() + 1).padStart(2, '0');
                    const dd = String(today.getDate()).padStart(2, '0');

                    return `v${yyyy}.${mm}.${dd}`;
                }

                return `v${dateValue.replaceAll('-', '.')}`;
            };

            const initialVersion = versionInput.value;
            const initialExpectedVersion = makeVersion(releaseDateInput.value);
            let versionTouched = initialVersion !== '' && initialVersion !== initialExpectedVersion;

            versionInput.addEventListener('input', () => {
                versionTouched = versionInput.value !== '' && versionInput.value !== makeVersion(releaseDateInput.value);
            });

            releaseDateInput.addEventListener('input', () => {
                if (versionTouched) {
                    return;
                }

                versionInput.value = makeVersion(releaseDateInput.value);
            });
        });
    </script>
</x-layouts.private-module>
