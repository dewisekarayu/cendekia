@props([
    'icon',
    'color' => 'blue',
    'label',
    'value',
    'change' => null,   // e.g. '12%', '-3%', '0%'
])

@php
    $trend = 'flat';
    if (!is_null($change)) {
        $numeric = (float) str_replace('%', '', $change);
        $trend = $numeric > 0 ? 'up' : ($numeric < 0 ? 'down' : 'flat');
    }
@endphp

<div class="stat-card">
    <div class="stat-card-top">
        <div class="stat-card-icon {{ $color }}">
            <i class="bi bi-{{ $icon }}"></i>
        </div>

        @if(!is_null($change))
            <span class="stat-card-badge {{ $trend }}">
                @if($trend === 'up')
                    <i class="bi bi-arrow-up-short"></i>
                @elseif($trend === 'down')
                    <i class="bi bi-arrow-down-short"></i>
                @endif
                {{ $change }}
            </span>
        @endif
    </div>

    <div class="label">{{ $label }}</div>
    <div class="number">{{ $value }}</div>
</div>