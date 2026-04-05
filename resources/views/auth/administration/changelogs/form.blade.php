<x-layouts.private-module :title="$title" :description="$description">
    @php
        $releaseDateValue = old('released_at', $changelog->released_at?->format('Y-m-d'));
        $versionValue = old(
            'version',
            $changelog->version ?: \App\Models\Administration\Changelog::defaultVersion($releaseDateValue),
        );
    @endphp

    <div class="private-page">
        <x-private.page-header :title="$isEdit ? 'Ubah Changelog' : 'Tambah Changelog'" :subtitle="$isEdit
            ? 'Perbarui detail rilis dan catatan perubahannya.'
            : 'Tambahkan catatan rilis baru untuk tim.'">
            <x-slot:actions>
                <a href="{{ route('administration.changelogs.index') }}"
                    class="private-action-link icon-action tooltip-trigger" aria-label="Kembali ke changelog"
                    title="Kembali ke changelog">
                    <x-ui.icon name="arrow-left" class="h-4 w-4" />
                </a>
            </x-slot:actions>
        </x-private.page-header>

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel :title="$isEdit ? 'Form Ubah Changelog' : 'Form Tambah Changelog'"
            description="Siapkan identitas rilis terlebih dahulu, lalu tulis catatan rilis yang akan tampil di dashboard.">
            <form method="POST"
                action="{{ $isEdit ? route('administration.changelogs.update', $changelog) : route('administration.changelogs.store') }}"
                class="space-y-6" data-changelog-form>
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <section class="changelog-form-section">
                    <div class="changelog-form-section-head">
                        <div>
                            <h3 class="changelog-form-section-title">Pengaturan Rilis</h3>
                            <p class="changelog-form-section-copy">
                                Tanggal rilis akan membuat versi default secara otomatis. Anda masih bisa mengganti
                                versi kapan saja jika dibutuhkan.
                            </p>
                        </div>

                        <div class="changelog-form-version-hint">
                            Format default:
                            <span class="changelog-form-version-code">v{{ now()->format('Y.m.d') }}</span>
                        </div>
                    </div>

                    <div class="changelog-form-meta-grid">
                        <x-form.field for="released_at" label="Tanggal Rilis" :error="$errors->first('released_at')"><x-form.input
                                id="released_at" name="released_at" type="date" icon="" :value="$releaseDateValue"
                                data-changelog-release-date /></x-form.field>

                        <x-form.field for="version" label="Versi" :error="$errors->first('version')">
                            <x-form.input id="version" name="version" icon="clipboard" :value="$versionValue"
                                placeholder="v2026.04.05" data-changelog-version />
                        </x-form.field>

                        <x-form.field for="status" label="Status" :error="$errors->first('status')">
                            <select id="status" name="status" class="input-base" required>
                                @foreach (['draft' => 'Draf', 'published' => 'Dipublikasikan', 'archived' => 'Diarsipkan'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $changelog->status ?: 'draft') === $value)>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </x-form.field>
                    </div>
                </section>

                <section class="changelog-form-section">
                    <div class="changelog-form-section-head">
                        <div>
                            <h3 class="changelog-form-section-title">Konten Rilis</h3>
                        </div>
                    </div>

                    <div class="private-form-grid">
                        <x-form.field for="title" label="Judul" :error="$errors->first('title')"
                            class="private-field-span-2"><x-form.input id="title" name="title" icon="clipboard"
                                :value="old('title', $changelog->title)" placeholder="Perbaiki manajer akses berbasis role"
                                required /></x-form.field>

                        <x-form.field for="notes" label="Catatan Rilis" :error="$errors->first('notes')"
                            class="private-field-span-2">
                            <x-form.rich-editor id="notes" name="notes" :value="old('notes', $changelog->notes)"
                                placeholder="Tulis detail perubahan, perbaikan bug, dan sorotan rilis. Ringkasan singkat akan diambil otomatis dari catatan ini." />
                        </x-form.field>
                    </div>
                </section>

                <div class="private-form-actions">
                    <x-ui.cancel-link :href="route('administration.changelogs.index')" />

                    <x-ui.save-button :label="$isEdit ? 'Simpan perubahan' : 'Simpan changelog'" />
                </div>
            </form>
        </x-private.panel>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('[data-changelog-form]');

            if (!form) {
                return;
            }

            const versionInput = form.querySelector('[data-changelog-version]');
            const releaseDateInput = form.querySelector('[data-changelog-release-date]');

            if (!versionInput || !releaseDateInput) {
                return;
            }

            const makeVersion = (dateValue) => {
                if (!dateValue) {
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
                versionTouched = versionInput.value !== '' && versionInput.value !== makeVersion(
                    releaseDateInput.value);
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
