<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header
            :title="$isEdit ? 'Edit Work Progress' : 'Create Work Progress'"
            :subtitle="$isEdit ? 'Perbarui status pekerjaan dan target penyelesaiannya.' : 'Tambahkan item pekerjaan baru untuk dipantau tim.'"
        >
            <x-slot:actions>
                <a href="{{ route('administration.work-progress.index') }}" class="private-action-link">
                    <span>Back to work progress</span>
                </a>
            </x-slot:actions>
        </x-private.page-header>

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <x-private.panel
            :title="$isEdit ? 'Form Edit Work Progress' : 'Form Create Work Progress'"
            description="Atur item pekerjaan, pemilik, prioritas, progres, dan catatan update dalam satu form yang rapi."
        >
            <form
                method="POST"
                action="{{ $isEdit ? route('administration.work-progress.update', $item) : route('administration.work-progress.store') }}"
                class="space-y-6"
            >
                @csrf
                @if ($isEdit)
                    @method('PUT')
                @endif

                <section class="changelog-form-section">
                    <div class="changelog-form-section-head">
                        <div>
                            <h3 class="changelog-form-section-title">Work Setup</h3>
                            <p class="changelog-form-section-copy">
                                Tentukan item pekerjaan, siapa yang menangani, prioritas, dan target dasarnya terlebih dahulu.
                            </p>
                        </div>
                    </div>

                    <div class="private-form-grid">
                        <x-form.field for="title" label="Work Item" :error="$errors->first('title')" class="private-field-span-2">
                            <x-form.input
                                id="title"
                                name="title"
                                icon="clipboard"
                                :value="old('title', $item->title)"
                                placeholder="Finalize release access checklist"
                                required
                            />
                        </x-form.field>

                        <x-form.field for="owner" label="Owner" :error="$errors->first('owner')">
                            <x-form.input
                                id="owner"
                                name="owner"
                                icon="user"
                                :value="old('owner', $item->owner)"
                                placeholder="Team member or PIC"
                            />
                        </x-form.field>

                        <x-form.field for="target_date" label="Target Date" :error="$errors->first('target_date')">
                            <x-form.input
                                id="target_date"
                                name="target_date"
                                type="date"
                                icon=""
                                :value="old('target_date', $item->target_date?->format('Y-m-d'))"
                            />
                        </x-form.field>

                        <x-form.field for="status" label="Status" :error="$errors->first('status')">
                            <select id="status" name="status" class="input-base" required>
                                @foreach (['planned' => 'Planned', 'in_progress' => 'In Progress', 'done' => 'Done', 'blocked' => 'Blocked'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $item->status ?: 'planned') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </x-form.field>

                        <x-form.field for="priority" label="Priority" :error="$errors->first('priority')">
                            <select id="priority" name="priority" class="input-base" required>
                                @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('priority', $item->priority ?: 'medium') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </x-form.field>
                    </div>
                </section>

                <section class="changelog-form-section">
                    <div class="changelog-form-section-head">
                        <div>
                            <h3 class="changelog-form-section-title">Progress Update</h3>
                            <p class="changelog-form-section-copy">
                                Isi persentase progres dan catatan kerja terbaru agar tim mudah membaca kondisi terakhir.
                            </p>
                        </div>
                    </div>

                    <div class="private-form-grid">
                        <x-form.field for="progress" label="Progress Percent" :error="$errors->first('progress')">
                            <x-form.input
                                id="progress"
                                name="progress"
                                type="number"
                                icon=""
                                min="0"
                                max="100"
                                step="1"
                                :value="old('progress', $item->progress ?? 0)"
                                required
                            />
                            <p class="private-helper-text">Gunakan angka 0 sampai 100 untuk menunjukkan progress pekerjaan.</p>
                        </x-form.field>

                        <div class="work-progress-form-preview">
                            <span class="work-progress-form-preview-label">Quick Guide</span>
                            <p class="work-progress-form-preview-copy">
                                `Planned` untuk pekerjaan yang belum dimulai, `In Progress` untuk yang sedang berjalan, `Done` jika selesai, dan `Blocked` jika ada hambatan.
                            </p>
                        </div>

                        <x-form.field for="notes" label="Progress Notes" :error="$errors->first('notes')" class="private-field-span-2">
                            <x-form.rich-editor
                                id="notes"
                                name="notes"
                                :value="old('notes', $item->notes)"
                                placeholder="Tulis update terbaru, blocker, kebutuhan tindak lanjut, atau highlight pekerjaan."
                            />
                        </x-form.field>
                    </div>
                </section>

                <div class="private-form-actions">
                    <a href="{{ route('administration.work-progress.index') }}" class="private-action-link">Cancel</a>

                    <x-ui.button type="submit" variant="primary" :block="false">
                        {{ $isEdit ? 'Save Changes' : 'Create Progress' }}
                    </x-ui.button>
                </div>
            </form>
        </x-private.panel>
    </div>
</x-layouts.private-module>
