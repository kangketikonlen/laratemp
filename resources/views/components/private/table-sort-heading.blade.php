@props(['href', 'label', 'active' => false, 'direction' => 'asc', 'thClass' => null])

<th @class([$thClass])>
    <a href="{{ $href }}" class="private-table-sort">
        <span>{{ $label }}</span>
        <x-ui.sort-indicator :active="$active" :direction="$direction" />
    </a>
</th>
