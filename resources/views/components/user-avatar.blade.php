@props([
    'name' => 'User',
    'photoUrl' => null,
    'size' => 40,
    'id' => null,
    'class' => '',
])

@php
    $avatarColors = [
        ['#c0e6ff', '#2563eb', '#002B6B'],
        ['#bfdbfe', '#3b82f6', '#1d4ed8'],
        ['#e0f2fe', '#0284c7', '#0369a1'],
        ['#fbcfe8', '#ec4899', '#be185d'],
        ['#ddd6fe', '#8b5cf6', '#6d28d9'],
    ];
    $avatarColor = $avatarColors[abs(crc32($name)) % count($avatarColors)];
    $avatarSvg = sprintf(
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96"><defs><linearGradient id="b" x1="0" y1="0" x2="1" y2="1"><stop stop-color="%s"/><stop offset="1" stop-color="%s"/></linearGradient><linearGradient id="s" x1="0" y1="0" x2="0" y2="1"><stop stop-color="%s"/><stop offset="1" stop-color="%s"/></linearGradient></defs><rect width="96" height="96" rx="48" fill="url(#b)"/><circle cx="75" cy="22" r="12" fill="#fff" opacity=".2"/><path d="M18 96c2-20 14-31 30-31s28 11 30 31" fill="url(#s)"/><path d="M35 62c4 6 9 9 13 9s9-3 13-9v-9H35z" fill="#f4c7a1"/><ellipse cx="48" cy="39" rx="19" ry="23" fill="#f4c7a1"/><path d="M29 39c-2-18 7-28 21-28 15 0 22 12 18 29-3-4-5-10-6-15-8 7-19 10-33 9z" fill="#334155"/><circle cx="41" cy="40" r="1.7" fill="#334155"/><circle cx="55" cy="40" r="1.7" fill="#334155"/><path d="M43 50c3 2 7 2 10 0" fill="none" stroke="#b4535a" stroke-width="2" stroke-linecap="round"/><path d="M32 74l16 13 16-13 7 22H25z" fill="#fff" opacity=".9"/></svg>',
        $avatarColor[0],
        $avatarColor[1],
        $avatarColor[1],
        $avatarColor[2]
    );
    $fallbackUrl = 'data:image/svg+xml;base64,'.base64_encode($avatarSvg);
@endphp

<img
    @if($id) id="{{ $id }}" @endif
    src="{{ $photoUrl ?: $fallbackUrl }}"
    data-fallback="{{ $fallbackUrl }}"
    data-original="{{ $photoUrl ?: $fallbackUrl }}"
    onerror="this.onerror=null; this.src=this.dataset.fallback;"
    class="{{ $class }}"
    width="{{ $size }}"
    height="{{ $size }}"
    alt="{{ $name }}"
    loading="lazy"
    style="width: {{ $size }}px; height: {{ $size }}px; border-radius: 50%; object-fit: cover; background: {{ $avatarColor[0] }}; border: 1.5px solid rgba(0, 43, 107, 0.15); flex-shrink: 0;"
>
