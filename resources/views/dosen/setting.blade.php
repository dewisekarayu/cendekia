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
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Zona Waktu</label>
                    <select class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                        <option>UTC+07:00 (WIB - Jakarta)</option>
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