<?php

use App\Actions\Auth\LoginUser;
use App\Models\Settings\Institution;
use App\Settings\GeneralSettings;
use Livewire\Component;

new class extends Component
{
    public string $username = '';
    public string $loginPassword = '';
    public string $copyright = '';

    public function mount(): void
    {
        $appName = config('app.name');
        $institutionName = 'Default Institution';
        $institutionAddress = 'Alamat institusi dapat diatur melalui pengaturan aplikasi.';

        try {
            $settings = app(GeneralSettings::class);
            $appName = filled($settings->app_name ?? null) ? $settings->app_name : $appName;
        } catch (\Spatie\LaravelSettings\Exceptions\MissingSettings $exception) {
            //
        }

        try {
            $institution = Institution::query()->first();
            $institutionName = filled($institution?->name) ? $institution->name : $institutionName;
            $institutionAddress = filled($institution?->address) ? $institution->address : $institutionAddress;
        } catch (\Throwable $exception) {
            //
        }

        $this->copyright = '© ' . now()->year . ' | ' . $institutionName . ' | ' . $institutionAddress;
    }

    public function login()
    {
        $credentials = $this->validate([
            'username' => ['required', 'string'],
            'loginPassword' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'loginPassword.required' => 'Password wajib diisi.',
        ]);

        app(LoginUser::class)->handle(
            username: $credentials['username'],
            password: $credentials['loginPassword'],
        );

        $this->skipRender();

        return redirect()->route('dashboard');
    }
};
?>

<div>
    <form wire:submit.prevent="login" class="space-y-5">
        @error('username')
            <x-ui.alert title="Login gagal" :message="$message" />
        @enderror

        <x-form.input wire:model.live="username" placeholder="Username" />

        @error('loginPassword')
            <x-ui.alert title="Password tidak valid" :message="$message" />
        @enderror

        <x-form.password wire:model.live="loginPassword" name="password" placeholder="Password" />

        <x-ui.button type="submit" loading-target="login" loading-label="Sedang masuk...">
            Login
        </x-ui.button>

        <p class="text-center text-sm text-gray-500">
            {{ $this->copyright }}
        </p>
    </form>

    <x-ui.loading-screen
        target="login"
        title="Sedang masuk"
        message="Kami sedang memverifikasi akun Anda."
    />
</div>
