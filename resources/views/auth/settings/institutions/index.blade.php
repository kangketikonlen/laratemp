<x-layouts.private-module :title="$title" :description="$description">
    <div class="private-page">
        <x-private.page-header title="Pengaturan Institusi"
            subtitle="Kelola identitas institusi, logo aplikasi, dan background halaman login.">
            <x-slot:actions>
                <x-ui.back-dashboard-link />
            </x-slot:actions>
        </x-private.page-header>

        @if (session('status'))
            <x-private.feedback :message="session('status')" />
        @endif

        @if ($errors->any())
            <x-private.feedback :message="$errors->first()" variant="danger" />
        @endif

        <form method="POST" action="{{ route('settings.institutions.update') }}" enctype="multipart/form-data"
            class="space-y-8">
            @csrf
            @method('PUT')

            <x-private.settings-section title="Detail Institusi"
                description="Data ini menjadi identitas utama aplikasi dan membantu pengguna mengenali ruang kerja yang sedang dipakai.">
                <div class="private-form-grid">
                    <x-form.field for="name" label="Nama Institusi" :error="$errors->first('name')">
                        <x-form.input id="name" name="name" icon="folder" :value="old('name', $institution->name)"
                            placeholder="Institusi Default" required />
                    </x-form.field>

                    <x-form.field for="contact" label="Kontak" :error="$errors->first('contact')">
                        <x-form.input id="contact" name="contact" icon="user" :value="old('contact', $institution->contact)"
                            placeholder="+62 812 0000 0000" />
                    </x-form.field>

                    <x-form.field for="address" label="Alamat" :error="$errors->first('address')" class="private-field-span-2">
                        <x-form.input id="address" name="address" icon="folder" :value="old('address', $institution->address)"
                            placeholder="Alamat institusi" required />
                    </x-form.field>

                    <x-form.field for="email" label="Email" :error="$errors->first('email')">
                        <x-form.input id="email" name="email" type="email" icon="user" :value="old('email', $institution->email)"
                            placeholder="info@example.com" required />
                    </x-form.field>

                    <x-form.field for="website" label="Situs Web" :error="$errors->first('website')">
                        <x-form.input id="website" name="website" type="url" icon="folder" :value="old('website', $institution->website)"
                            placeholder="https://example.com" required />
                    </x-form.field>

                    <x-form.field for="appUrl" label="URL Aplikasi" :error="$errors->first('appUrl')" class="private-field-span-2">
                        <x-form.input id="appUrl" name="appUrl" type="url" icon="folder" :value="old('appUrl', $institution->appUrl)"
                            placeholder="https://app.example.com" required />
                    </x-form.field>
                </div>
            </x-private.settings-section>

            <x-private.settings-section title="Branding Login"
                description="Atur visual halaman login agar terasa lebih sesuai dengan identitas institusi.">
                <div class="institution-upload-grid">
                    <x-form.upload-card id="logo" input-name="logo" title="Logo Aplikasi"
                        description="Dipakai pada kartu login. Cocok untuk logo persegi atau horizontal."
                        label="Unggah Logo Baru" :error="$errors->first('logo')"
                        helper="Gunakan logo persegi atau horizontal agar hasilnya lebih bersih. Batas upload 10 MB."
                        :preview-url="$logoPreviewUrl" preview-alt="Logo institusi" preview-class="private-image-preview--logo"
                        empty-message="Logo institusi belum diunggah." :logo-preview="true" :badge="$logoPreviewUrl ? 'Aktif' : 'Bawaan'" />

                    <x-form.upload-card id="background" input-name="background" title="Latar Login"
                        description="Dipakai pada sisi kiri halaman login. Disarankan gambar landscape hingga 4 MB."
                        label="Unggah Latar Baru" :error="$errors->first('background')"
                        helper="Pilih gambar dengan area fokus yang tetap jelas saat di-crop." :preview-url="$backgroundPreviewUrl"
                        preview-alt="Latar login" empty-message="Background login belum diunggah." :badge="$backgroundPreviewUrl ? 'Aktif' : 'Bawaan'" />
                </div>
            </x-private.settings-section>

            <div class="private-form-actions private-field-span-2">
                <x-ui.cancel-link :href="route('settings.institutions.index')" />

                <x-ui.save-button label="Simpan pengaturan institusi" />
            </div>
        </form>
    </div>
</x-layouts.private-module>
