@props(['icon', 'color' => 'blue', 'title', 'value'])

<div class="stat-card">
    <div>
        <h3>{{ $title }}</h3>
        <div class="number">{{ $value }}</div>
    </div>
    <div class="stat-card-icon {{ $color }}">
        <i class="bi bi-{{ $icon }}"></i>
    </div>
</div>
