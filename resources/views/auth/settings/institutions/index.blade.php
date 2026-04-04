<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header
            title="Institution Settings"
            subtitle="Kelola identitas institusi, logo aplikasi, dan background halaman login."
        >
            <x-slot:actions>
                <a href="{{ route('dashboard') }}" class="private-action-link">
                    <span>Back to dashboard</span>
                </a>
            </x-slot:actions>
        </x-private.page-header>

        @if (session('status'))
            <x-private.feedback :message="session('status')" />
        @endif

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <section class="institution-settings-hero">
            <div>
                <p class="institution-settings-kicker">Visual Identity</p>
                <h2 class="institution-settings-title">{{ $institution->name ?: 'Institution Branding' }}</h2>
                <p class="institution-settings-copy">
                    Susun identitas institusi dan perbarui tampilan halaman login dari satu workspace yang lebih rapi.
                </p>
            </div>
        </section>

        <x-private.panel
            title="Institution Profile"
            description="Perubahan logo dan background akan langsung dipakai di halaman login."
        >
            <form
                method="POST"
                action="{{ route('settings.institutions.update') }}"
                enctype="multipart/form-data"
                class="space-y-8"
            >
                @csrf
                @method('PUT')

                <x-private.settings-section
                    title="Institution Details"
                    description="Data ini menjadi identitas utama aplikasi dan membantu pengguna mengenali workspace yang sedang dipakai."
                >
                    <div class="private-form-grid">
                        <x-form.field for="name" label="Institution Name" :error="$errors->first('name')">
                            <x-form.input
                                id="name"
                                name="name"
                                icon="folder"
                                :value="old('name', $institution->name)"
                                placeholder="Default Institution"
                                required
                            />
                        </x-form.field>

                        <x-form.field for="contact" label="Contact" :error="$errors->first('contact')">
                            <x-form.input
                                id="contact"
                                name="contact"
                                icon="user"
                                :value="old('contact', $institution->contact)"
                                placeholder="+62 812 0000 0000"
                            />
                        </x-form.field>

                        <x-form.field for="address" label="Address" :error="$errors->first('address')" class="private-field-span-2">
                            <x-form.input
                                id="address"
                                name="address"
                                icon="folder"
                                :value="old('address', $institution->address)"
                                placeholder="Alamat institusi"
                                required
                            />
                        </x-form.field>

                        <x-form.field for="email" label="Email" :error="$errors->first('email')">
                            <x-form.input
                                id="email"
                                name="email"
                                type="email"
                                icon="user"
                                :value="old('email', $institution->email)"
                                placeholder="info@example.com"
                                required
                            />
                        </x-form.field>

                        <x-form.field for="website" label="Website" :error="$errors->first('website')">
                            <x-form.input
                                id="website"
                                name="website"
                                type="url"
                                icon="folder"
                                :value="old('website', $institution->website)"
                                placeholder="https://example.com"
                                required
                            />
                        </x-form.field>

                        <x-form.field for="appUrl" label="Application URL" :error="$errors->first('appUrl')" class="private-field-span-2">
                            <x-form.input
                                id="appUrl"
                                name="appUrl"
                                type="url"
                                icon="folder"
                                :value="old('appUrl', $institution->appUrl)"
                                placeholder="https://app.example.com"
                                required
                            />
                        </x-form.field>
                    </div>
                </x-private.settings-section>

                <x-private.settings-section
                    title="Login Branding"
                    description="Atur visual halaman login agar terasa lebih sesuai dengan identitas institusi."
                >
                    <div class="institution-upload-grid">
                        <x-form.upload-card
                            id="logo"
                            input-name="logo"
                            title="Application Logo"
                            description="Dipakai pada kartu login. Cocok untuk logo persegi atau horizontal."
                            label="Upload New Logo"
                            :error="$errors->first('logo')"
                            helper="Gunakan logo persegi atau horizontal agar hasilnya lebih bersih. Batas upload 10 MB."
                            :preview-url="$logoPreviewUrl"
                            preview-alt="Institution logo"
                            preview-class="private-image-preview--logo"
                            empty-message="Logo institusi belum diunggah."
                            :logo-preview="true"
                            :badge="$logoPreviewUrl ? 'Active' : 'Default'"
                        />

                        <x-form.upload-card
                            id="background"
                            input-name="background"
                            title="Login Background"
                            description="Dipakai pada sisi kiri halaman login. Disarankan gambar landscape hingga 4 MB."
                            label="Upload New Background"
                            :error="$errors->first('background')"
                            helper="Pilih gambar dengan area fokus yang tetap jelas saat di-crop."
                            :preview-url="$backgroundPreviewUrl"
                            preview-alt="Login background"
                            empty-message="Background login belum diunggah."
                            :badge="$backgroundPreviewUrl ? 'Active' : 'Default'"
                        />
                    </div>
                </x-private.settings-section>

                <div class="private-form-actions private-field-span-2">
                    <a href="{{ route('dashboard') }}" class="private-action-link">Cancel</a>

                    <x-ui.button type="submit" variant="primary" :block="false">
                        Save Institution Settings
                    </x-ui.button>
                </div>
            </form>
        </x-private.panel>
    </div>
</x-layouts.private-module>
