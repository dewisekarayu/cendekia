@extends('layouts.portal')
@section('title', 'Pengaturan')
@section('activeMenu', 'Pengaturan')
@section('content')

<div class="space-y-6">
    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-extrabold text-slate-800">Pengaturan</h1>
        <p class="mt-1 text-sm text-gray-500">Kelola preferensi dan konfigurasi aplikasi Anda</p>
    </div>

    {{-- SETTINGS TABS --}}
    <div class="flex gap-2 border-b border-gray-200 overflow-x-auto">
        @php
            $tabs = [
                ['id' => 'umum', 'label' => 'Umum', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M12 9a3 3 0 100 6 3 3 0 000-6z'],
                ['id' => 'notifikasi', 'label' => 'Notifikasi', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                ['id' => 'privasi', 'label' => 'Privasi & Keamanan', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                ['id' => 'integrasi', 'label' => 'Integrasi', 'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4.243 4.243a2 2 0 113-3l4.243-4.243a1 1 0 111.414 1.414L9.172 12.5a2.5 2.5 0 003.536 3.536l4.243-4.243a1 1 0 00-1.414-1.414L13.828 10.172z'],
            ];
        @endphp
        @foreach ($tabs as $tab)
            <button class="flex items-center gap-2 px-4 py-3 font-semibold text-gray-600 border-b-2 border-transparent hover:text-slate-800 hover:border-gray-300 transition active-tab" data-tab="{{ $tab['id'] }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}"/></svg>
                <span class="hidden sm:inline">{{ $tab['label'] }}</span>
            </button>
        @endforeach
    </div>

    {{-- TAB CONTENT --}}
    {{-- UMUM TAB --}}
    <div id="umum-content" class="tab-content space-y-6">
        {{-- BAHASA & WILAYAH --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-5 py-4">
                <h3 class="font-bold text-slate-800">Bahasa & Wilayah</h3>
            </div>
            <div class="space-y-5 p-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bahasa</label>
                    <select class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                        <option>Bahasa Indonesia</option>
                        <option>English</option>
                        <option>中文</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Zona Waktu</label>
                    <select class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                        <option>UTC+07:00 (WIB - Jakarta)</option>
                        <option>UTC+08:00 (WITA)</option>
                        <option>UTC+09:00 (WIT)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Format Tanggal</label>
                    <select class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                        <option>DD/MM/YYYY</option>
                        <option>MM/DD/YYYY</option>
                        <option>YYYY-MM-DD</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button class="rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Batal</button>
                    <button class="rounded-lg bg-[#321270] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#321270]/90 transition">Simpan</button>
                </div>
            </div>
        </div>

        {{-- TAMPILAN --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-5 py-4">
                <h3 class="font-bold text-slate-800">Tampilan</h3>
            </div>
            <div class="space-y-5 p-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Tema</label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="theme" value="light" checked class="w-4 h-4">
                            <span class="text-sm text-gray-700">☀️ Terang</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="theme" value="dark" class="w-4 h-4">
                            <span class="text-sm text-gray-700">🌙 Gelap</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="theme" value="auto" class="w-4 h-4">
                            <span class="text-sm text-gray-700">🔄 Otomatis</span>
                        </label>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Ukuran Font</label>
                    <div class="flex items-center gap-4">
                        <button class="px-3 py-2 text-xs font-bold text-gray-600 hover:bg-gray-100 rounded-lg transition">A</button>
                        <input type="range" min="12" max="18" value="14" class="flex-1">
                        <button class="px-3 py-2 text-lg font-bold text-gray-600 hover:bg-gray-100 rounded-lg transition">A</button>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button class="rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Batal</button>
                    <button class="rounded-lg bg-[#321270] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#321270]/90 transition">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- NOTIFIKASI TAB --}}
    <div id="notifikasi-content" class="tab-content hidden space-y-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-5 py-4">
                <h3 class="font-bold text-slate-800">Preferensi Notifikasi</h3>
            </div>
            <div class="space-y-4 p-5">
                @php
                    $notifications = [
                        ['title' => 'Pengumpulan Tugas', 'desc' => 'Notifikasi ketika ada tugas yang dikumpulkan', 'email' => true, 'push' => true],
                        ['title' => 'Komentar Forum', 'desc' => 'Notifikasi ketika ada komentar pada forum diskusi', 'email' => true, 'push' => false],
                        ['title' => 'Pertanyaan Mahasiswa', 'desc' => 'Notifikasi ketika ada pertanyaan dari mahasiswa', 'email' => true, 'push' => true],
                        ['title' => 'Pengumuman Admin', 'desc' => 'Notifikasi pengumuman penting dari admin', 'email' => true, 'push' => true],
                        ['title' => 'Laporan Presensi', 'desc' => 'Notifikasi ringkasan presensi harian', 'email' => false, 'push' => false],
                    ];
                @endphp
                @foreach ($notifications as $notif)
                    <div class="flex items-start justify-between pb-4 border-b border-gray-100 last:border-0">
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $notif['title'] }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $notif['desc'] }}</p>
                        </div>
                        <div class="flex gap-3 ml-4">
                            <label class="flex items-center gap-2 cursor-pointer text-xs">
                                <input type="checkbox" {{ $notif['email'] ? 'checked' : '' }} class="w-4 h-4 rounded">
                                <span class="text-gray-600">Email</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer text-xs">
                                <input type="checkbox" {{ $notif['push'] ? 'checked' : '' }} class="w-4 h-4 rounded">
                                <span class="text-gray-600">Push</span>
                            </label>
                        </div>
                    </div>
                @endforeach

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button class="rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">Batal</button>
                    <button class="rounded-lg bg-[#321270] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#321270]/90 transition">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- PRIVASI & KEAMANAN TAB --}}
    <div id="privasi-content" class="tab-content hidden space-y-6">
        {{-- TWO-FACTOR AUTHENTICATION --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-5 py-4">
                <h3 class="font-bold text-slate-800">Autentikasi Dua Faktor</h3>
            </div>
            <div class="flex items-center justify-between p-5">
                <div>
                    <p class="text-sm font-semibold text-gray-800">Aktivkan 2FA</p>
                    <p class="text-xs text-gray-500 mt-0.5">Tingkatkan keamanan akun dengan autentikasi dua faktor</p>
                </div>
                <button class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-gray-300 transition-colors ease-in-out focus:outline-none focus:ring-2 focus:ring-[#321270]/50"
                        role="switch" aria-checked="false">
                    <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                </button>
            </div>
        </div>

        {{-- ACTIVE SESSIONS --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-5 py-4 flex items-center justify-between">
                <h3 class="font-bold text-slate-800">Sesi Aktif</h3>
                <button class="text-xs font-semibold text-red-600 hover:text-red-700">Keluar Dari Semua</button>
            </div>
            <div class="space-y-3 p-5">
                @php
                    $sessions = [
                        ['device' => 'Windows - Chrome', 'ip' => '192.168.1.100', 'location' => 'Jakarta, ID', 'current' => true],
                        ['device' => 'iPhone - Safari', 'ip' => '192.168.1.101', 'location' => 'Jakarta, ID', 'current' => false],
                        ['device' => 'MacBook - Chrome', 'ip' => '192.168.1.102', 'location' => 'Bandung, ID', 'current' => false],
                    ];
                @endphp
                @foreach ($sessions as $session)
                    <div class="flex items-start justify-between p-3 border border-gray-100 rounded-lg hover:bg-gray-50">
                        <div class="flex items-start gap-3 flex-1">
                            <div class="text-xl">{{ str_contains($session['device'], 'Windows') ? '🖥️' : (str_contains($session['device'], 'iPhone') ? '📱' : '💻') }}</div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-semibold text-gray-800">{{ $session['device'] }}</p>
                                    @if ($session['current'])
                                        <span class="text-[10px] font-bold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded">Saat Ini</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $session['ip'] }} • {{ $session['location'] }}</p>
                            </div>
                        </div>
                        @if (!$session['current'])
                            <button class="text-xs font-semibold text-red-600 hover:text-red-700">Keluar</button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- INTEGRASI TAB --}}
    <div id="integrasi-content" class="tab-content hidden space-y-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
            <div class="border-b border-gray-100 px-5 py-4">
                <h3 class="font-bold text-slate-800">Aplikasi & Layanan Terhubung</h3>
            </div>
            <div class="space-y-3 p-5">
                @php
                    $integrations = [
                        ['name' => 'Google Drive', 'status' => true, 'icon' => '🔵'],
                        ['name' => 'Microsoft Office 365', 'status' => true, 'icon' => '🔵'],
                        ['name' => 'Zoom', 'status' => false, 'icon' => '⚪'],
                        ['name' => 'Slack', 'status' => false, 'icon' => '⚪'],
                    ];
                @endphp
                @foreach ($integrations as $integration)
                    <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg hover:bg-gray-50">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">{{ $integration['icon'] }}</span>
                            <p class="text-sm font-semibold text-gray-800">{{ $integration['name'] }}</p>
                        </div>
                        <button class="text-xs font-semibold {{ $integration['status'] ? 'text-red-600 hover:text-red-700' : 'text-[#321270] hover:text-[#321270]/90' }}">
                            {{ $integration['status'] ? 'Putuskan' : 'Hubungkan' }}
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.active-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
            document.getElementById(tabId + '-content').classList.remove('hidden');
            document.querySelectorAll('.active-tab').forEach(t => t.classList.remove('border-[#321270]', 'text-slate-800', 'border-b-2'));
            document.querySelectorAll('.active-tab').forEach(t => t.classList.add('border-transparent'));
            this.classList.remove('border-transparent');
            this.classList.add('border-[#321270]', 'text-slate-800', 'border-b-2');
        });
    });
    
    // Set active tab on load
    document.querySelector('.active-tab').classList.add('border-[#321270]', 'text-slate-800', 'border-b-2');
</script>

@endsection
