@props([
    'icon' => null,
    'options' => [],
    'placeholder' => 'Select an option',
    'searchPlaceholder' => 'Search...',
    'emptyText' => 'No results found.',
    'optionValue' => 'value',
    'optionLabel' => 'label',
])

@php
    $normalizedOptions = collect($options)
        ->map(function ($option) use ($optionValue, $optionLabel) {
            if (is_string($option) || is_numeric($option)) {
                return [
                    'value' => (string) $option,
                    'label' => (string) $option,
                ];
            }

            return [
                'value' => (string) data_get($option, $optionValue, ''),
                'label' => (string) data_get($option, $optionLabel, ''),
            ];
        })
        ->filter(fn (array $option) => $option['label'] !== '')
        ->values();

    $inputAttributes = $attributes->except(['class']);
    $fieldName = $inputAttributes->get('name');
    $rawInitialValue = $fieldName
        ? old($fieldName, $inputAttributes->get('value', ''))
        : $inputAttributes->get('value', '');
    $initialValue = is_scalar($rawInitialValue) || $rawInitialValue === null
        ? (string) ($rawInitialValue ?? '')
        : '';
@endphp

<div
    x-data="{
        open: false,
        search: '',
        selectedValue: @js($initialValue),
        options: @js($normalizedOptions),
        get filteredOptions() {
            const term = this.search.trim().toLowerCase();

            if (!term) {
                return this.options;
            }

            return this.options.filter((option) => option.label.toLowerCase().includes(term));
        },
        get selectedOption() {
            return this.options.find((option) => option.value === this.selectedValue) ?? null;
        },
        get displayLabel() {
            return this.selectedOption?.label ?? @js($placeholder);
        },
        syncValue() {
            this.$refs.input.value = this.selectedValue;
            this.$refs.input.dispatchEvent(new Event('input', { bubbles: true }));
            this.$refs.input.dispatchEvent(new Event('change', { bubbles: true }));
        },
        selectOption(option) {
            this.selectedValue = option.value;
            this.open = false;
            this.search = '';
            this.syncValue();
        },
        clearSelection() {
            this.selectedValue = '';
            this.search = '';
            this.syncValue();
        },
        focusSearch() {
            this.$nextTick(() => this.$refs.search?.focus());
        },
        init() {
            this.syncValue();
        },
    }"
    x-on:click.outside="open = false"
    class="relative"
>
    <input
        x-ref="input"
        type="hidden"
        value="{{ $initialValue }}"
        {{ $inputAttributes }}
    >

    @if ($icon)
        <span class="input-icon">
            <x-ui.icon :name="$icon" class="text-gray-400" />
        </span>
    @endif

    <button
        type="button"
        x-on:click="open = !open; if (open) focusSearch()"
        class="{{ trim('input-base relative flex items-center justify-between gap-3 pr-10 text-left ' . ($icon ? 'pl-10' : '')) }}"
    >
        <span
            class="block truncate"
            :class="selectedOption ? 'text-gray-900' : 'text-gray-400'"
            x-text="displayLabel"
        ></span>

        <x-ui.icon
            name="chevron-down"
            class="absolute right-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400 transition"
            ::class="{ 'rotate-180': open }"
        />
    </button>

    <div
        x-cloak
        x-show="open"
        x-transition.origin.top
        class="absolute z-20 mt-2 w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg"
    >
        <div class="border-b border-gray-100 p-3">
            <div class="relative">
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <x-ui.icon name="search" class="h-4 w-4" />
                </span>

                <input
                    x-ref="search"
                    x-model="search"
                    type="text"
                    placeholder="{{ $searchPlaceholder }}"
                    class="input-base h-10 border-gray-200 bg-white pl-9 text-sm"
                >
            </div>
        </div>

        <div class="max-h-60 overflow-y-auto py-2">
            <button
                x-show="selectedValue"
                type="button"
                x-on:click="clearSelection()"
                class="flex w-full items-center px-4 py-2 text-left text-sm text-gray-500 transition hover:bg-gray-50"
            >
                Clear selection
            </button>

            <template x-if="filteredOptions.length === 0">
                <div class="px-4 py-3 text-sm text-gray-500">
                    {{ $emptyText }}
                </div>
            </template>

            <template x-for="option in filteredOptions" :key="option.value">
                <button
                    type="button"
                    x-on:click="selectOption(option)"
                    class="flex w-full items-center justify-between gap-3 px-4 py-2 text-left text-sm text-gray-700 transition hover:bg-blue-50"
                    :class="option.value === selectedValue ? 'bg-blue-50 text-blue-700' : ''"
                >
                    <span class="truncate" x-text="option.label"></span>

                    <x-ui.icon
                        name="check"
                        class="h-4 w-4 text-blue-600"
                        x-show="option.value === selectedValue"
                    />
                </button>
            </template>
        </div>
    </div>
</div>
