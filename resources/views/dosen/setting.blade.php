@extends('layouts.portal')
@section('title', app()->getLocale() === 'en' ? 'Settings' : 'Pengaturan')
@section('activeMenu', 'Settings')
@section('content')

@php
    $isEn = app()->getLocale() === 'en';
@endphp

<div class="space-y-6">
    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white transition-colors duration-200">
            {{ $isEn ? 'Settings' : 'Pengaturan' }}
        </h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 transition-colors duration-200">
            {{ $isEn ? 'Manage your preferences and application configuration' : 'Kelola preferensi dan konfigurasi aplikasi Anda' }}
        </p>
    </div>

    {{-- SETTINGS TABS --}}
    <div class="flex gap-2 border-b border-gray-200 dark:border-slate-700 overflow-x-auto transition-colors duration-200">
        @php
            $tabs = [
                [
                    'id' => 'umum',
                    'label' => $isEn ? 'General' : 'Umum',
                    'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M12 9a3 3 0 100 6 3 3 0 000-6z'
                ],
                [
                    'id' => 'notifikasi',
                    'label' => $isEn ? 'Notifications' : 'Notifikasi',
                    'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'
                ],
            ];
        @endphp
        @foreach ($tabs as $tab)
            <button class="flex items-center gap-2 px-4 py-3 font-semibold text-gray-500 dark:text-gray-400 border-b-2 border-transparent hover:text-slate-800 dark:hover:text-white hover:border-gray-300 transition active-tab" data-tab="{{ $tab['id'] }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}"/></svg>
                <span class="hidden sm:inline">{{ $tab['label'] }}</span>
            </button>
        @endforeach
    </div>

    {{-- TAB CONTENT --}}
    {{-- UMUM TAB --}}
    <div id="umum-content" class="tab-content space-y-6">
        {{-- BAHASA --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm transition-colors duration-200">
            <div class="border-b border-gray-100 dark:border-slate-700 px-5 py-4">
                <h3 class="font-bold text-slate-800 dark:text-white">
                    {{ $isEn ? 'Language' : 'Bahasa' }}
                </h3>
            </div>
            <form method="POST" action="{{ route('dosen.setting.umum') }}" class="space-y-5 p-5">
                @csrf
                <input type="hidden" name="theme" value="{{ $user->theme ?? 'light' }}">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        {{ $isEn ? 'Select Language' : 'Pilih Bahasa' }}
                    </label>
                    <select name="language" class="w-full rounded-lg border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-sm text-gray-800 dark:text-gray-100 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                        <option value="id" {{ ($user->language ?? 'id') === 'id' ? 'selected' : '' }} class="dark:bg-slate-900 dark:text-gray-100">Bahasa Indonesia</option>
                        <option value="en" {{ ($user->language ?? 'id') === 'en' ? 'selected' : '' }} class="dark:bg-slate-900 dark:text-gray-100">English</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100 dark:border-slate-700">
                    <a href="{{ route('dosen.setting') }}" class="rounded-lg border border-gray-200 dark:border-slate-700 px-6 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                        {{ $isEn ? 'Cancel' : 'Batal' }}
                    </a>
                    <button type="submit" class="rounded-lg bg-[#321270] dark:bg-[#6c2bd9] px-6 py-2.5 text-sm font-semibold text-white dark:hover:bg-[#5b21b6] hover:bg-[#321270]/90 transition">
                        {{ $isEn ? 'Save' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>

        {{-- TAMPILAN --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm transition-colors duration-200">
            <div class="border-b border-gray-100 dark:border-slate-700 px-5 py-4">
                <h3 class="font-bold text-slate-800 dark:text-white">
                    {{ $isEn ? 'Appearance' : 'Tampilan' }}
                </h3>
            </div>
            <form method="POST" action="{{ route('dosen.setting.umum') }}" class="space-y-5 p-5">
                @csrf
                <input type="hidden" name="language" value="{{ $user->language ?? 'id' }}">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                        {{ $isEn ? 'Theme' : 'Tema' }}
                    </label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="theme-card flex items-center gap-3 cursor-pointer p-4 border border-gray-200 dark:border-slate-700 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition duration-150">
                            <input type="radio" name="theme" value="light" onclick="highlightThemeCards()" {{ ($user->theme ?? 'light') === 'light' ? 'checked' : '' }} class="w-4 h-4 text-[#321270] focus:ring-[#321270] dark:bg-slate-900 dark:border-slate-700">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ $isEn ? '☀️ Light' : '☀️ Terang' }}
                            </span>
                        </label>
                        <label class="theme-card flex items-center gap-3 cursor-pointer p-4 border border-gray-200 dark:border-slate-700 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition duration-150">
                            <input type="radio" name="theme" value="dark" onclick="highlightThemeCards()" {{ ($user->theme ?? 'light') === 'dark' ? 'checked' : '' }} class="w-4 h-4 text-[#321270] focus:ring-[#321270] dark:bg-slate-900 dark:border-slate-700">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ $isEn ? '🌙 Dark' : '🌙 Gelap' }}
                            </span>
                        </label>
                        <label class="theme-card flex items-center gap-3 cursor-pointer p-4 border border-gray-200 dark:border-slate-700 rounded-xl hover:bg-gray-50 dark:hover:bg-slate-700 transition duration-150">
                            <input type="radio" name="theme" value="auto" onclick="highlightThemeCards()" {{ ($user->theme ?? 'light') === 'auto' ? 'checked' : '' }} class="w-4 h-4 text-[#321270] focus:ring-[#321270] dark:bg-slate-900 dark:border-slate-700">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                {{ $isEn ? '🔄 Auto' : '🔄 Otomatis' }}
                            </span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100 dark:border-slate-700">
                    <a href="{{ route('dosen.setting') }}" class="rounded-lg border border-gray-200 dark:border-slate-700 px-6 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                        {{ $isEn ? 'Cancel' : 'Batal' }}
                    </a>
                    <button type="submit" class="rounded-lg bg-[#321270] dark:bg-[#6c2bd9] px-6 py-2.5 text-sm font-semibold text-white dark:hover:bg-[#5b21b6] hover:bg-[#321270]/90 transition">
                        {{ $isEn ? 'Save' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- NOTIFIKASI TAB --}}
    <div id="notifikasi-content" class="tab-content hidden space-y-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm transition-colors duration-200">
            <div class="border-b border-gray-100 dark:border-slate-700 px-5 py-4">
                <h3 class="font-bold text-slate-800 dark:text-white">
                    {{ $isEn ? 'Notification Preferences' : 'Preferensi Notifikasi' }}
                </h3>
            </div>
            <form method="POST" action="{{ route('dosen.setting.notifikasi') }}" class="space-y-4 p-5">
                @csrf
                @php
                    $notifications = [
                        [
                            'key' => 'pengumpulan_tugas',
                            'title' => $isEn ? 'Assignment Submission' : 'Pengumpulan Tugas',
                            'desc' => $isEn ? 'Notification when students submit their homework assignments' : 'Notifikasi ketika ada tugas yang dikumpulkan oleh mahasiswa',
                        ],
                        [
                            'key' => 'pesan_baru',
                            'title' => $isEn ? 'Forum Comments' : 'Komentar Forum',
                            'desc' => $isEn ? 'Notification when there are comments or new posts in class discussion forums' : 'Notifikasi ketika ada komentar pada forum diskusi kelas',
                        ],
                        [
                            'key' => 'pengumuman_baru',
                            'title' => $isEn ? 'Admin Announcements' : 'Pengumuman Admin',
                            'desc' => $isEn ? 'Notification when admin publishes university or school level announcements' : 'Notifikasi pengumuman penting dari admin',
                        ],
                    ];
                @endphp
                @foreach ($notifications as $notif)
                    <div class="flex items-start justify-between pb-4 border-b border-gray-100 dark:border-slate-700 last:border-0">
                        <div>
                            <p class="font-semibold text-gray-800 dark:text-white text-sm">{{ $notif['title'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $notif['desc'] }}</p>
                        </div>
                        <div class="flex gap-3 ml-4">
                            <label class="flex items-center gap-2 cursor-pointer text-xs">
                                <input type="checkbox" name="{{ $notif['key'] }}" value="1" {{ $preferences->isEnabled($notif['key']) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 dark:border-slate-700 dark:bg-slate-900 text-[#321270] dark:text-purple-600 focus:ring-[#321270] dark:focus:ring-offset-slate-800">
                                <span class="text-gray-600 dark:text-gray-300 font-medium">
                                    {{ $isEn ? 'Enabled' : 'Aktif' }}
                                </span>
                            </label>
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-slate-700">
                    <a href="{{ route('dosen.setting') }}" class="rounded-lg border border-gray-200 dark:border-slate-700 px-6 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                        {{ $isEn ? 'Cancel' : 'Batal' }}
                    </a>
                    <button type="submit" class="rounded-lg bg-[#321270] dark:bg-[#6c2bd9] px-6 py-2.5 text-sm font-semibold text-white dark:hover:bg-[#5b21b6] hover:bg-[#321270]/90 transition">
                        {{ $isEn ? 'Save' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.querySelectorAll('.active-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
            document.getElementById(tabId + '-content').classList.remove('hidden');
            document.querySelectorAll('.active-tab').forEach(t => {
                t.classList.remove('border-[#321270]', 'text-slate-800', 'dark:text-white', 'border-b-2');
                t.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            });
            this.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
            this.classList.add('border-[#321270]', 'text-slate-800', 'dark:text-white', 'border-b-2');
        });
    });
    
    // Set active tab on load
    const activeTabButton = document.querySelector('.active-tab');
    if (activeTabButton) {
        activeTabButton.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
        activeTabButton.classList.add('border-[#321270]', 'text-slate-800', 'dark:text-white', 'border-b-2');
    }

    // Dynamic selection highlighting for Theme Cards
    function highlightThemeCards() {
        document.querySelectorAll('.theme-card').forEach(card => {
            const radio = card.querySelector('input[type="radio"]');
            if (radio && radio.checked) {
                card.classList.remove('border-gray-200', 'dark:border-slate-700', 'bg-white', 'dark:bg-slate-800');
                card.classList.add('border-[#321270]', 'dark:border-[#9061f9]', 'bg-[#321270]/5', 'dark:bg-[#9061f9]/10');
            } else {
                card.classList.remove('border-[#321270]', 'dark:border-[#9061f9]', 'bg-[#321270]/5', 'dark:bg-[#9061f9]/10');
                card.classList.add('border-gray-200', 'dark:border-slate-700', 'bg-white', 'dark:bg-slate-800');
            }
        });
    }

    // Run theme card highlighting on load
    highlightThemeCards();
</script>

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: "{{ app()->getLocale() === 'en' ? 'Success' : 'Berhasil' }}",
            text: "{{ session('success') }}",
            confirmButtonColor: '#321270',
        });
    });
</script>
@endif
@endpush

@endsection