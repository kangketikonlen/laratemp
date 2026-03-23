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
            {{ $copyright }}
        </p>
    </form>

    <x-ui.loading-screen
        target="login"
        title="Sedang masuk"
        message="Kami sedang memverifikasi akun Anda."
    />
</div>
