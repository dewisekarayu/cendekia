{{--
    Shared summary cards — used on Dashboard, Profil, and Setting pages
    so the header identity stays consistent everywhere.

    Expected variables (pass with sensible fallbacks from the controller):
    - $kelasAktif      (int)   jumlah kelas aktif
    - $rataRataNilai   (float) rata-rata nilai mahasiswa
    - $mkDinilai       (int)   jumlah mata kuliah yang sudah dinilai
    - $pengumumanCount (int)   jumlah pengumuman belum dibaca / total
--}}
@php
    $statCards = [
        [
            'label' => 'Kelas Aktif',
            'value' => $kelasAktif ?? 0,
            'icon'  => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
            'color' => 'text-blue-600 bg-blue-50',
        ],
        [
            'label' => 'Rata-rata Nilai',
            'value' => $rataRataNilai ?? 0,
            'icon'  => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
            'color' => 'text-emerald-600 bg-emerald-50',
        ],
        [
            'label' => 'MK Dinilai',
            'value' => $mkDinilai ?? 0,
            'icon'  => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'color' => 'text-violet-600 bg-violet-50',
        ],
        [
            'label' => 'Pengumuman',
            'value' => $pengumumanCount ?? 0,
            'icon'  => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z',
            'color' => 'text-amber-600 bg-amber-50',
        ],
    ];
@endphp

<div class="mb-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
    @foreach ($statCards as $card)
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/80 bg-white p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $card['color'] }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-xl font-extrabold leading-tight text-slate-800 truncate">{{ $card['value'] }}</p>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-400 truncate">{{ $card['label'] }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>