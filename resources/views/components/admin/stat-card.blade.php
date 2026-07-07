@props(['icon', 'color' => 'blue', 'title', 'value'])

<div class="stat-card d-flex flex-row align-items-center gap-3">
    <div class="stat-card-icon {{ $color }} shrink-0">
        <i class="bi bi-{{ $icon }}"></i>
    </div>
    <div>
        <div class="label">{{ $title }}</div>
        <div class="number">{{ $value }}</div>
    </div>
</div>
