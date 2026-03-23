<?php

use App\Actions\Auth\LoginUser;
use App\Settings\GeneralSettings;
use Livewire\Component;

new class extends Component
{
    protected string $defaultCopyright = '© 2026 LARATEMP. All rights reserved.';

    public string $username = '';
    public string $loginPassword = '';
    public string $copyright = '';

    public function mount(): void
    {
        $this->copyright = $this->defaultCopyright;

        try {
            $settings = app(GeneralSettings::class);
            $this->copyright = filled($settings->app_copyright ?? null)
                ? $settings->app_copyright
                : $this->defaultCopyright;
        } catch (\Spatie\LaravelSettings\Exceptions\MissingSettings $exception) {
            //
        }
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
