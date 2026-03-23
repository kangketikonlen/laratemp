<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<div>
    <form wire:submit.prevent="login" class="space-y-5">
        <x-form.input wire:model="username" placeholder="Username" />

        @error('username')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror

        <x-form.password wire:model="password" placeholder="Password" />

        <x-form.select>
            <option>Pilih Periode Akademik</option>
        </x-form.select>

        <button class="btn-primary">Login</button>

        <button type="button" class="btn-secondary">
            <span class="text-green-600 font-bold">G</span>
            Login dengan Google
        </button>
    </form>
</div>
