<?php

use Livewire\Component;

new class extends Component
{
    //
};
?>

<form wire:submit.prevent="login" class="space-y-5">
    <x-form.input wire:model="username" placeholder="Username" />

    @error('username')
        <span class="text-red-500 text-sm">{{ $message }}</span>
    @enderror

    <x-form.password wire:model="password" placeholder="Password" />

    <x-form.select
        wire:model="academic_period"
        placeholder="Pilih Periode Akademik"
        search-placeholder="Cari periode akademik..."
        :options="[
            ['value' => '2024-ganjil', 'label' => '2024/2025 Ganjil'],
            ['value' => '2024-genap', 'label' => '2024/2025 Genap'],
            ['value' => '2025-pendek', 'label' => '2025 Semester Pendek'],
        ]"
    />

    <button class="btn-primary">Login</button>

    <button type="button" class="btn-secondary">
        <span class="text-green-600 font-bold">G</span>
        Login dengan Google
    </button>
</form>
