@extends('layouts.portal')
@section('title', 'Pengaturan')
@section('activeMenu', 'Setting')
@section('content')

@php
    $currentTab = request()->get('tab', 'umum');
@endphp

<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Setting</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola preferensi umum dan notifikasi akun Anda</p>
        </div>
    </div>

    {{-- FLASH MESSAGE --}}
    @if (session('success'))
        <div class="flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- SUMMARY CARDS (shared with Profil page) --}}
    @include('mahasiswa.partials.stat-cards', [
        'kelasAktif'      => $totalKelas ?? 0,
        'rataRataNilai'   => $rataRata ? number_format($rataRata, 1) : '0.0',
        'mkDinilai'       => $nilaiAkhirList->count() ?? 0,
        'pengumumanCount' => $announcements->count() ?? 0,
    ])

    {{-- MAIN CONTENT --}}
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- LEFT: TABS + CONTENT --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Tab bar --}}
            <div class="flex gap-2 overflow-x-auto rounded-full bg-white border border-gray-200 p-1.5 w-fit shadow-sm">
                @php
                    $tabs = [
                        'umum' => ['label' => 'Umum', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                        'notifikasi' => ['label' => 'Notifikasi', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                    ];
                @endphp
                @foreach ($tabs as $key => $tab)
                    <a href="{{ request()->fullUrlWithQuery(['tab' => $key]) }}"
                       class="whitespace-nowrap rounded-full px-5 py-2 text-xs font-semibold inline-flex items-center gap-1.5 transition {{ $currentTab === $key ? 'bg-[#002B6B] text-white shadow-sm shadow-blue-900/20' : 'text-gray-500 hover:text-[#002B6B]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}" />
                        </svg>
                        {{ $tab['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- UMUM --}}
            @if ($currentTab === 'umum')
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h3 class="font-bold text-slate-800">Preferensi Umum</h3>
                    <p class="mt-0.5 text-xs text-gray-400">Atur bahasa dan tampilan aplikasi</p>
                </div>

                <form method="POST" action="{{ route('mahasiswa.setting.umum') }}" class="space-y-5 p-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Bahasa</label>
                        <select name="language"
                                class="w-full max-w-xs rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-[#002B6B] focus:outline-none focus:ring-2 focus:ring-[#002B6B]/10 transition">
                            <option value="id" {{ ($user->language ?? 'id') === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                            <option value="en" {{ ($user->language ?? 'id') === 'en' ? 'selected' : '' }}>English</option>
                        </select>
                        @error('language')
                            <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-4">Tema Tampilan</label>
                        <div class="grid grid-cols-3 gap-3 max-w-md" id="themeSelector">
                            @php
                                $themeOptions = [
                                    [
                                        'value' => 'light',
                                        'label' => 'Terang',
                                        'icon' => 'M12 3v1m0 16v1m9-9h-1m-16 0H1m15.364 2.636l.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z',
                                        'preview' => 'bg-white border-yellow-400'
                                    ],
                                    [
                                        'value' => 'dark',
                                        'label' => 'Gelap',
                                        'icon' => 'M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z',
                                        'preview' => 'bg-slate-900 border-blue-500'
                                    ],
                                    [
                                        'value' => 'auto',
                                        'label' => 'Otomatis',
                                        'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z',
                                        'preview' => 'bg-gradient-to-br from-gray-100 to-gray-200 border-purple-400'
                                    ],
                                ];
                            @endphp
                            @foreach ($themeOptions as $option)
                                <label class="group relative cursor-pointer">
                                    <input 
                                        type="radio" 
                                        name="theme" 
                                        value="{{ $option['value'] }}" 
                                        class="sr-only theme-radio"
                                        data-preview="{{ $option['preview'] }}"
                                        {{ ($user->theme ?? 'light') === $option['value'] ? 'checked' : '' }}
                                        onchange="previewTheme(this)">
                                    
                                    <div class="relative overflow-hidden rounded-2xl border-2 border-gray-200 dark:border-gray-700 transition-all duration-300 group-has-[:checked]:border-[#002B6B] group-has-[:checked]:shadow-lg">
                                        {{-- Preview Box --}}
                                        <div class="h-20 {{ $option['preview'] }} flex items-center justify-center relative">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 {{ $option['value'] === 'light' ? 'text-yellow-500' : ($option['value'] === 'dark' ? 'text-blue-400' : 'text-purple-500') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $option['icon'] }}" />
                                            </svg>
                                        </div>
                                        
                                        {{-- Label --}}
                                        <div class="px-3 py-2.5 text-center bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700">
                                            <p class="text-xs font-bold text-gray-700 dark:text-gray-300 group-has-[:checked]:text-[#002B6B] dark:group-has-[:checked]:text-blue-400">{{ $option['label'] }}</p>
                                        </div>
                                        
                                        {{-- Checked indicator --}}
                                        <div class="absolute top-1 right-1 hidden group-has-[:checked]:flex items-center justify-center w-6 h-6 bg-[#002B6B] rounded-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        
                        {{-- Live Preview Notice --}}
                        <div class="mt-4 p-3 rounded-lg bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/30">
                            <p class="text-xs text-blue-700 dark:text-blue-300">
                                <span class="font-semibold">💡 Tips:</span> Tema akan diterapkan secara real-time setelah menyimpan perubahan.
                            </p>
                        </div>
                        
                        @error('theme')
                            <p class="mt-2 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end border-t border-gray-100 pt-4">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-[#002B6B] px-6 py-2.5 text-sm font-bold text-white hover:bg-blue-800 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
            @endif

            {{-- NOTIFIKASI --}}
            @if ($currentTab === 'notifikasi')
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h3 class="font-bold text-slate-800">Preferensi Notifikasi</h3>
                    <p class="mt-0.5 text-xs text-gray-400">Pilih notifikasi apa saja yang ingin kamu terima</p>
                </div>

                <form method="POST" action="{{ route('mahasiswa.setting.notifikasi') }}" class="p-5">
                    @csrf

                    @php
                        $notifOptions = [
                            ['key' => 'tugas_baru', 'label' => 'Tugas Baru', 'desc' => 'Diberitahu saat dosen membuat tugas baru', 'color' => 'text-amber-600 bg-amber-50', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            ['key' => 'materi_baru', 'label' => 'Materi Baru', 'desc' => 'Diberitahu saat ada materi kuliah baru diunggah', 'color' => 'text-blue-600 bg-blue-50', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                            ['key' => 'pengumuman_baru', 'label' => 'Pengumuman', 'desc' => 'Diberitahu saat dosen atau admin membuat pengumuman baru', 'color' => 'text-violet-600 bg-violet-50', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
                            ['key' => 'nilai_baru', 'label' => 'Nilai Baru', 'desc' => 'Diberitahu saat dosen menginput atau memperbarui nilai kamu', 'color' => 'text-emerald-600 bg-emerald-50', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.196-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                            ['key' => 'absensi_dibuka', 'label' => 'Sesi Absensi Dibuka', 'desc' => 'Diberitahu saat dosen membuka sesi absensi di kelas', 'color' => 'text-rose-600 bg-rose-50', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                            ['key' => 'pesan_baru', 'label' => 'Pesan Forum', 'desc' => 'Diberitahu saat ada balasan baru di forum diskusi kelas', 'color' => 'text-slate-600 bg-slate-100', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
                        ];
                    @endphp

                    <div class="divide-y divide-gray-100">
                        @foreach ($notifOptions as $opt)
                            <div class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $opt['color'] }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $opt['icon'] }}" />
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-slate-800">{{ $opt['label'] }}</p>
                                    <p class="text-xs text-gray-400">{{ $opt['desc'] }}</p>
                                </div>
                                <label class="relative inline-flex shrink-0 cursor-pointer items-center">
                                    <input type="checkbox" name="{{ $opt['key'] }}" value="1" class="peer sr-only"
                                           {{ ($preferences->{$opt['key']} ?? true) ? 'checked' : '' }}>
                                    <div class="h-6 w-11 rounded-full bg-gray-200 transition peer-checked:bg-[#002B6B] after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow-sm after:transition-all peer-checked:after:translate-x-5"></div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end border-t border-gray-100 pt-4 mt-2">
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-[#002B6B] px-6 py-2.5 text-sm font-bold text-white hover:bg-blue-800 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>

        {{-- RIGHT: SHARED IDENTITY SIDEBAR --}}
        <div>
            <div class="sticky top-24">
                @include('mahasiswa.partials.account-sidebar', ['user' => $user])
            </div>
        </div>
    </div>
</div>

@endsection

<script>
function previewTheme(radio) {
    // Get theme value
    const theme = radio.value;
    const html = document.documentElement;
    
    // Temporarily apply theme for preview (without submitting form)
    if (theme === 'dark') {
        html.classList.add('dark');
        localStorage.setItem('theme-preview', 'dark');
    } else if (theme === 'light') {
        html.classList.remove('dark');
        localStorage.setItem('theme-preview', 'light');
    } else if (theme === 'auto') {
        // For auto, check system preference
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }
        localStorage.setItem('theme-preview', 'auto');
    }
}

// Initialize theme selector styling
document.addEventListener('DOMContentLoaded', function() {
    const themeRadios = document.querySelectorAll('.theme-radio');
    themeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            previewTheme(this);
        });
    });
});
</script>

<style>
/* Enhanced Settings Page Dark Mode */
html.dark .rounded-2xl.border.border-slate-200\/80 {
    border-color: rgba(30, 41, 59, 0.5) !important;
    background-color: #1e293b !important;
}

html.dark .border-b.border-gray-100 {
    border-color: rgba(71, 85, 105, 0.4) !important;
}

html.dark .bg-blue-50.dark\:bg-blue-500\/10 {
    background-color: rgba(30, 144, 255, 0.1) !important;
    border-color: rgba(30, 144, 255, 0.2) !important;
}

html.dark .text-blue-700.dark\:text-blue-300 {
    color: #7dd3fc !important;
}

html.dark select {
    background-color: #0f172a !important;
    color: #f8fafc !important;
    border-color: #334155 !important;
}

html.dark select option {
    background-color: #1e293b !important;
    color: #f8fafc !important;
}

/* Theme Selector Cards Dark Mode */
html.dark .group-has-\[\:checked\]\:border-\[\#002B6B\] {
    border-color: #60a5fa !important;
}

html.dark .bg-white.dark\:bg-gray-800 {
    background-color: #1e293b !important;
}

html.dark .text-gray-700.dark\:text-gray-300 {
    color: #cbd5e1 !important;
}

html.dark .border-gray-100.dark\:border-gray-700 {
    border-color: #334155 !important;
}

html.dark .border-gray-200.dark\:border-gray-700 {
    border-color: #334155 !important;
}

html.dark .bg-slate-900 {
    background-color: #0f172a !important;
}
</style>
