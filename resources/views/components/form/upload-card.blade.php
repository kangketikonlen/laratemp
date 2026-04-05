@props([
    'badge' => 'Bawaan',
    'description' => null,
    'error' => null,
    'helper' => null,
    'id',
    'inputName',
    'label',
    'previewAlt' => null,
    'previewClass' => '',
    'previewUrl' => null,
    'title',
    'emptyMessage' => 'Belum ada file yang diunggah.',
    'accept' => 'image/*',
    'logoPreview' => false,
])

<div class="institution-upload-card">
    <div class="institution-upload-meta">
        <div>
            <h4 class="institution-upload-title">{{ $title }}</h4>
            @if (filled($description))
                <p class="institution-upload-copy">{{ $description }}</p>
            @endif
        </div>

        <span class="institution-upload-badge">{{ $badge }}</span>
    </div>

    @if (filled($previewUrl))
        <div @class([
            'institution-upload-preview-shell',
            'institution-upload-preview-shell--logo' => $logoPreview,
        ])>
            <img src="{{ $previewUrl }}" alt="{{ $previewAlt ?: $title }}" @class(['private-image-preview', $previewClass])>
        </div>
    @else
        <div @class([
            'institution-upload-empty',
            'institution-upload-preview-shell--logo' => $logoPreview,
        ])>
            {{ $emptyMessage }}
        </div>
    @endif

    <x-form.field :for="$id" :label="$label" :error="$error">
        <input id="{{ $id }}" name="{{ $inputName }}" type="file" accept="{{ $accept }}" class="input-file-base">

        @if (filled($helper))
            <p class="private-helper-text">{{ $helper }}</p>
        @endif
    </x-form.field>
</div>
