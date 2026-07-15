@extends('layouts.portal')
@section('title', app()->getLocale() === 'en' ? 'Settings' : 'Pengaturan')
@section('activeMenu', 'Settings')
@section('content')

@php
    $user = auth()->user();
    $isEn = app()->getLocale() === 'en';
@endphp

{{-- STATS STRIP --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    <div class="group rounded-2xl bg-white border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5" style="background:#002B6B;"></div>
        <p class="text-[28px] font-black text-[#002B6B] leading-none">{{ $totalKelas }}</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Kelas Aktif</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-[#002B6B]/30"></div>
    </div>
    <div class="group rounded-2xl bg-white border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5 bg-emerald-500"></div>
        <p class="text-[28px] font-black text-emerald-600 leading-none">{{ $rataRata ? number_format($rataRata, 1) : '–' }}</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Rata-rata Nilai</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-emerald-400/40"></div>
    </div>
    <div class="group rounded-2xl bg-white border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5 bg-violet-500"></div>
        <p class="text-[28px] font-black text-violet-600 leading-none">{{ $nilaiAkhirList->count() }}</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">MK Dinilai</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-violet-400/40"></div>
    </div>
    <div class="group rounded-2xl bg-white border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5 bg-amber-500"></div>
        <p class="text-[28px] font-black text-amber-500 leading-none">{{ $announcements->count() }}</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Pengumuman</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-amber-400/40"></div>
    </div>
</div>

{{-- MAIN GRID --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: FORMS --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- TABS --}}
        <div x-data="{ tab: 'umum' }">
            <div class="flex gap-1 rounded-2xl bg-white border border-slate-100 shadow-sm p-1.5 mb-5">
                <button @click="tab='umum'"
                    :class="tab==='umum' ? 'bg-[#002B6B] text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M12 9a3 3 0 100 6 3 3 0 000-6z"/>
                    </svg>
                    {{ $isEn ? 'General' : 'Umum' }}
                </button>
                <button @click="tab='notifikasi'"
                    :class="tab==='notifikasi' ? 'bg-[#002B6B] text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Notifikasi
                </button>
            </div>

            {{-- UMUM TAB --}}
            <div x-show="tab==='umum'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="space-y-5">
                    {{-- BAHASA --}}
                    <div class="rounded-2xl bg-white border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-50 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#002B6B] shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5c-.006.314-.016.628-.03 1.412m-.413 3.328A13.045 13.045 0 019 6.412m0 0A13.003 13.003 0 0012 3m0 0a13 13 0 00-3-1m3 1A13 13 0 019.5 6.412"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-800">{{ $isEn ? 'Language' : 'Bahasa' }}</h2>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $isEn ? 'Select your preferred language' : 'Pilih bahasa preferensi Anda' }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('mahasiswa.setting.umum') }}" class="px-6 py-6">
                            @csrf
                            <input type="hidden" name="theme" value="{{ $user->theme ?? 'light' }}">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">
                                    {{ $isEn ? 'Select Language' : 'Pilih Bahasa' }}
                                </label>
                                <select name="language" class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-800 focus:border-[#002B6B] focus:outline-none focus:ring-2 focus:ring-[#002B6B]/10 transition-all">
                                    <option value="id" {{ ($user->language ?? 'id') === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                                    <option value="en" {{ ($user->language ?? 'id') === 'en' ? 'selected' : '' }}>English</option>
                                </select>
                            </div>

                            <div class="mt-6 pt-5 border-t border-gray-50 flex items-center justify-between">
                                <p class="text-xs text-gray-400">{{ $isEn ? 'Language changes will apply immediately' : 'Perubahan bahasa akan langsung diterapkan' }}</p>
                                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#002B6B] px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#003380] active:scale-95 transition-all duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $isEn ? 'Save' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- TAMPILAN (TEMA) --}}
                    <div class="rounded-2xl bg-white border border-slate-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-5 border-b border-gray-50 flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#002B6B] shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M6.343 6.364l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-base font-bold text-slate-800">{{ $isEn ? 'Appearance' : 'Tampilan' }}</h2>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $isEn ? 'Choose application color theme' : 'Pilih tema warna aplikasi' }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('mahasiswa.setting.umum') }}" class="px-6 py-6">
                            @csrf
                            <input type="hidden" name="language" value="{{ $user->language ?? 'id' }}">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-3 uppercase tracking-wide">
                                    {{ $isEn ? 'Theme' : 'Tema' }}
                                </label>
                                <div class="grid grid-cols-3 gap-4">
                                    <label class="theme-card flex flex-col sm:flex-row items-center justify-center gap-3 cursor-pointer p-4 border border-gray-150 rounded-xl hover:bg-gray-50 active:scale-[0.98] transition duration-150">
                                        <input type="radio" name="theme" value="light" onclick="highlightThemeCards()" {{ ($user->theme ?? 'light') === 'light' ? 'checked' : '' }} class="w-4 h-4 text-[#002B6B] focus:ring-[#002B6B]">
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ $isEn ? '☀️ Light' : '☀️ Terang' }}
                                        </span>
                                    </label>
                                    <label class="theme-card flex flex-col sm:flex-row items-center justify-center gap-3 cursor-pointer p-4 border border-gray-150 rounded-xl hover:bg-gray-50 active:scale-[0.98] transition duration-150">
                                        <input type="radio" name="theme" value="dark" onclick="highlightThemeCards()" {{ ($user->theme ?? 'light') === 'dark' ? 'checked' : '' }} class="w-4 h-4 text-[#002B6B] focus:ring-[#002B6B]">
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ $isEn ? '🌙 Dark' : '🌙 Gelap' }}
                                        </span>
                                    </label>
                                    <label class="theme-card flex flex-col sm:flex-row items-center justify-center gap-3 cursor-pointer p-4 border border-gray-150 rounded-xl hover:bg-gray-50 active:scale-[0.98] transition duration-150">
                                        <input type="radio" name="theme" value="auto" onclick="highlightThemeCards()" {{ ($user->theme ?? 'light') === 'auto' ? 'checked' : '' }} class="w-4 h-4 text-[#002B6B] focus:ring-[#002B6B]">
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ $isEn ? '🔄 Auto' : '🔄 Otomatis' }}
                                        </span>
                                    </label>
                                </div>
                            </div>

                            <div class="mt-6 pt-5 border-t border-gray-50 flex items-center justify-between">
                                <p class="text-xs text-gray-400">{{ $isEn ? 'Theme changes will apply immediately' : 'Perubahan tema akan langsung diterapkan' }}</p>
                                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#002B6B] px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#003380] active:scale-95 transition-all duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $isEn ? 'Save' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- NOTIFIKASI TAB --}}
            <div x-show="tab==='notifikasi'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="rounded-2xl bg-white border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-50 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#002B6B] shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">{{ $isEn ? 'Notification Preferences' : 'Preferensi Notifikasi' }}</h2>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $isEn ? 'Manage which notification channels you want to receive' : 'Kelola saluran notifikasi mana yang ingin Anda terima' }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('mahasiswa.setting.notifikasi') }}" class="px-6 py-6">
                        @csrf
                        @php
                            $notifications = [
                                [
                                    'key' => 'materi_baru',
                                    'title' => $isEn ? 'New Material' : 'Materi Baru',
                                    'desc' => $isEn ? 'Notification when a lecturer uploads new material to your class' : 'Notifikasi ketika dosen mengunggah materi baru di kelas Anda',
                                ],
                                [
                                    'key' => 'tugas_baru',
                                    'title' => $isEn ? 'New Assignment' : 'Tugas Baru',
                                    'desc' => $isEn ? 'Notification when a new class assignment is published' : 'Notifikasi ketika tugas kelas baru ditambahkan',
                                ],
                                [
                                    'key' => 'pengumuman_baru',
                                    'title' => $isEn ? 'Announcements' : 'Pengumuman',
                                    'desc' => $isEn ? 'Notification when there are new announcements from admin or lecturer' : 'Notifikasi jika ada pengumuman baru dari admin atau dosen',
                                ],
                                [
                                    'key' => 'nilai_baru',
                                    'title' => $isEn ? 'Graded Assignment' : 'Penilaian Tugas',
                                    'desc' => $isEn ? 'Notification when your assignments have been graded' : 'Notifikasi ketika tugas Anda selesai dinilai oleh dosen',
                                ],
                                [
                                    'key' => 'absensi_dibuka',
                                    'title' => $isEn ? 'Attendance Open' : 'Absensi Dibuka',
                                    'desc' => $isEn ? 'Notification when attendance session for a class is opened' : 'Notifikasi ketika sesi absensi kelas telah dibuka',
                                ],
                                [
                                    'key' => 'pesan_baru',
                                    'title' => $isEn ? 'Forum Comments' : 'Komentar Forum',
                                    'desc' => $isEn ? 'Notification when there are comments or new posts in class discussion forums' : 'Notifikasi ketika ada komentar pada forum diskusi kelas',
                                ],
                            ];
                        @endphp
                        <div class="space-y-4">
                            @foreach ($notifications as $notif)
                                <div class="flex items-start justify-between pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm">{{ $notif['title'] }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $notif['desc'] }}</p>
                                    </div>
                                    <div class="flex gap-3 ml-4">
                                        <label class="flex items-center gap-2 cursor-pointer text-xs select-none">
                                            <input type="checkbox" name="{{ $notif['key'] }}" value="1" {{ $preferences->isEnabled($notif['key']) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-[#002B6B] focus:ring-[#002B6B]">
                                            <span class="text-gray-600 font-medium">
                                                {{ $isEn ? 'Enabled' : 'Aktif' }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 pt-5 border-t border-gray-50 flex items-center justify-between">
                            <p class="text-xs text-gray-400">{{ $isEn ? 'Settings will apply to system alerts' : 'Pengaturan akan berlaku pada peringatan sistem' }}</p>
                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-[#002B6B] px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#003380] active:scale-95 transition-all duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                {{ $isEn ? 'Save' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- RIGHT: INFO CARD --}}
    <div class="lg:col-span-1 space-y-5">

        {{-- FOTO PROFIL CARD --}}
        <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-6 text-center">
            <form action="{{ route('mahasiswa.setting.foto') }}" method="POST" enctype="multipart/form-data" id="formFoto">
                @csrf
                @method('PUT')
                <div class="relative mx-auto mb-3 h-24 w-24 group/avatar">
                    <img id="avatarPreview"
                        src="{{ $user->foto ? asset('storage/'.$user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=002B6B&color=fff&bold=true' }}"
                        alt="Foto Profil"
                        class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-md ring-1 ring-slate-100 mx-auto">

                    <label for="fotoInput"
                        class="absolute inset-0 flex items-center justify-center rounded-full bg-black/50 opacity-0 group-hover/avatar:opacity-100 transition-opacity cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828H9V13z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 19h14" />
                        </svg>
                    </label>
                    <input type="file" id="fotoInput" name="foto" accept="image/*" class="hidden" onchange="previewFoto(event)">
                </div>

                <p class="text-sm font-bold text-slate-800">{{ $user->name }}</p>
                <p class="text-xs text-gray-500 mb-3">{{ $user->email }}</p>

                <div id="fotoActions" class="hidden justify-center gap-2">
                    <button type="submit"
                            class="rounded-full bg-[#002B6B] px-4 py-1.5 text-xs font-semibold text-white hover:bg-[#002B6B]/90 transition">
                        Simpan Foto
                    </button>
                    <button type="button" onclick="batalFoto()"
                            class="rounded-full border border-gray-200 px-4 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                        Batal
                    </button>
                </div>
                @error('foto')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </form>
        </div>

        {{-- Ringkasan Akun --}}
        <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-6">
              <h3 class="text-sm font-bold text-slate-800 mb-4">Ringkasan Akun</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400">Status</span>
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Aktif
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400">NIM</span>
                    <span class="text-xs font-semibold text-slate-700">{{ $user->nip_nim ?? '–' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400">Program Studi</span>
                    <span class="text-xs font-semibold text-slate-700 text-right">{{ $user->programStudi?->nama_prodi ?? '–' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400">Bergabung Sejak</span>
                    <span class="text-xs font-semibold text-slate-700">{{ $user->created_at?->translatedFormat('M Y') ?? '–' }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function previewFoto(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('avatarPreview').src = e.target.result;
            document.getElementById('fotoActions').classList.remove('hidden');
            document.getElementById('fotoActions').classList.add('flex');
        };
        reader.readAsDataURL(file);
    }

    function batalFoto() {
        document.getElementById('fotoInput').value = '';
        document.getElementById('fotoActions').classList.add('hidden');
        document.getElementById('fotoActions').classList.remove('flex');
        document.getElementById('avatarPreview').src = "{{ $user->foto ? asset('storage/'.$user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=002B6B&color=fff&bold=true' }}";
    }

    // Dynamic selection highlighting for Theme Cards
    function highlightThemeCards() {
        document.querySelectorAll('.theme-card').forEach(card => {
            const radio = card.querySelector('input[type="radio"]');
            if (radio && radio.checked) {
                card.classList.remove('border-gray-150', 'bg-white');
                card.classList.add('border-[#002B6B]', 'bg-[#002B6B]/5');
            } else {
                card.classList.remove('border-[#002B6B]', 'bg-[#002B6B]/5');
                card.classList.add('border-gray-150', 'bg-white');
            }
        });
    }

    // Run functions on load
    document.addEventListener('DOMContentLoaded', function() {
        highlightThemeCards();
    });
</script>

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: "{{ app()->getLocale() === 'en' ? 'Success' : 'Berhasil' }}",
            text: "{{ session('success') }}",
            confirmButtonColor: '#002B6B',
        });
    });
</script>
@endif

@endsection