@extends('layouts.admin')

@section('title', 'Pengaturan')
@section('activeMenu', 'Pengaturan')

@section('content')
<div class="space-y-6" x-data="{ activeTab: '{{ request()->get('tab', 'profil') }}' }">
    
    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-900/40 text-[#002B6B] dark:text-blue-400 border border-blue-100 dark:border-blue-800/60">
                    <i class="bi bi-gear-fill text-xs"></i>
                    Konfigurasi & Akun
                </span>
            </div>
            <h1 class="page-title mb-1 text-2xl sm:text-3xl font-extrabold text-[#002B6B] dark:text-white">
                Pengaturan Sistem & Profil
            </h1>
            <nav style="--bs-breadcrumb-divider: '›';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Pengaturan</li>
                </ol>
            </nav>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.notification-preferences.index') }}" 
               class="btn btn-light border bg-white dark:bg-slate-800 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 d-inline-flex align-items-center gap-2 px-3.5 py-2 text-sm font-semibold shadow-sm"
               style="border-radius: 0.75rem;">
                <i class="bi bi-bell"></i>
                <span>Notifikasi Civitas</span>
            </a>
        </div>
    </div>

    {{-- SUCCESS ALERT --}}
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-300 shadow-sm flex items-center justify-between gap-3 animate-fade-in" role="alert">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-900/60 text-emerald-600 dark:text-emerald-300 flex items-center justify-center flex-shrink-0">
                    <i class="bi bi-check-circle-fill fs-5"></i>
                </div>
                <div>
                    <strong class="font-bold block text-sm">Berhasil Disimpan!</strong>
                    <span class="text-xs text-emerald-700 dark:text-emerald-400">{{ session('success') }}</span>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ERROR ALERT --}}
    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/50 text-red-800 dark:text-red-300 shadow-sm flex items-start gap-3" role="alert">
            <div class="w-9 h-9 rounded-xl bg-red-100 dark:bg-red-900/60 text-red-600 dark:text-red-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            </div>
            <div class="flex-1 text-sm">
                <strong class="font-bold block mb-1">Terjadi kesalahan pada data formulir:</strong>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-700 dark:text-red-300">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- STAT MINI OVERVIEW --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon blue" style="flex-shrink: 0;">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <div style="min-width: 0; overflow: hidden;">
                    <div class="label">Peran Akun</div>
                    <div class="number text-blue-700 dark:text-blue-400" style="font-size: 1rem; font-weight: 800; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Administrator</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon green">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="label">Total Pengguna</div>
                    <div class="number">{{ number_format($totalUsers ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon blue">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div>
                    <div class="label">Total Dosen</div>
                    <div class="number">{{ number_format($totalDosen ?? 0) }}</div>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-card-icon amber">
                    <i class="bi bi-mortarboard"></i>
                </div>
                <div>
                    <div class="label">Mahasiswa</div>
                    <div class="number">{{ number_format($totalMahasiswa ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- SETTINGS TABS --}}
    <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 overflow-x-auto pb-px">
        <button type="button" @click="activeTab = 'profil'"
                :class="activeTab === 'profil' ? 'border-[#002B6B] text-[#002B6B] dark:border-blue-400 dark:text-blue-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 font-medium'"
                class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm transition whitespace-nowrap">
            <i class="bi bi-person-gear"></i>
            <span>Profil & Keamanan</span>
        </button>
        <button type="button" @click="activeTab = 'tampilan'"
                :class="activeTab === 'tampilan' ? 'border-[#002B6B] text-[#002B6B] dark:border-blue-400 dark:text-blue-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 font-medium'"
                class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm transition whitespace-nowrap">
            <i class="bi bi-palette"></i>
            <span>Tampilan & Bahasa</span>
        </button>
        <button type="button" @click="activeTab = 'notifikasi'"
                :class="activeTab === 'notifikasi' ? 'border-[#002B6B] text-[#002B6B] dark:border-blue-400 dark:text-blue-400 font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 font-medium'"
                class="flex items-center gap-2 px-4 py-3 border-b-2 text-sm transition whitespace-nowrap">
            <i class="bi bi-bell"></i>
            <span>Notifikasi Admin</span>
        </button>
    </div>

    {{-- TAB 1: PROFIL & KEAMANAN --}}
    <div x-show="activeTab === 'profil'" class="space-y-6" x-transition>
        
        {{-- Profil Form --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm overflow-hidden p-5 sm:p-7">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100 dark:border-slate-700/60">
                <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-[#002B6B] dark:text-blue-400 font-extrabold text-sm flex items-center justify-center border border-blue-100 dark:border-blue-900/50">
                    <i class="bi bi-person"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white m-0">Informasi Profil Administrator</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Perbarui nama lengkap, alamat email resmi, dan nomor identitas</p>
                </div>
            </div>

            <form action="{{ route('admin.setting.profile') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" class="form-control w-full"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" class="form-control w-full"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="nip_nim" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            NIP / Nomor Pegawai
                        </label>
                        <input type="text" name="nip_nim" id="nip_nim" class="form-control w-full"
                               value="{{ old('nip_nim', $user->nip_nim) }}" placeholder="Contoh: 198501012010121001">
                        @error('nip_nim')
                            <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Nomor Telepon / WhatsApp
                        </label>
                        <input type="text" name="phone" id="phone" class="form-control w-full"
                               value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-700/60 flex justify-end">
                    <button type="submit" class="btn btn-primary px-5 py-2.5 flex items-center gap-2">
                        <i class="bi bi-check2-circle fs-5"></i>
                        <span>Simpan Profil</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Ganti Password Form --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm overflow-hidden p-5 sm:p-7">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100 dark:border-slate-700/60">
                <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 font-extrabold text-sm flex items-center justify-center border border-amber-200 dark:border-amber-900/50">
                    <i class="bi bi-key"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white m-0">Keamanan & Kata Sandi</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Gunakan kata sandi kombinasi huruf, angka, dan simbol untuk keamanan maksimal</p>
                </div>
            </div>

            <form action="{{ route('admin.setting.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="current_password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Kata Sandi Saat Ini <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="current_password" id="current_password" class="form-control w-full" required>
                        @error('current_password')
                            <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Kata Sandi Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" id="password" class="form-control w-full" required>
                        @error('password')
                            <span class="text-red-500 text-xs mt-1 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1.5">
                            Konfirmasi Sandi Baru <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control w-full" required>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-700/60 flex justify-end">
                    <button type="submit" class="btn btn-primary px-5 py-2.5 flex items-center gap-2">
                        <i class="bi bi-shield-check fs-5"></i>
                        <span>Perbarui Kata Sandi</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

    {{-- TAB 2: TAMPILAN & BAHASA --}}
    <div x-show="activeTab === 'tampilan'" class="space-y-6" x-transition>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm overflow-hidden p-5 sm:p-7">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100 dark:border-slate-700/60">
                <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-[#002B6B] dark:text-blue-400 font-extrabold text-sm flex items-center justify-center border border-blue-100 dark:border-blue-900/50">
                    <i class="bi bi-palette"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white m-0">Preferensi Antarmuka & Bahasa</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Sesuaikan mode tema dan bahasa portal akademik Anda</p>
                </div>
            </div>

            <form action="{{ route('admin.setting.umum') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Pilihan Tema --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-3">
                        Mode Tema Tampilan
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer flex items-center gap-3 hover:border-blue-500 transition {{ old('theme', $user->theme ?? 'light') === 'light' ? 'bg-blue-50/50 border-blue-500 dark:bg-blue-950/30' : '' }}">
                            <input type="radio" name="theme" value="light" class="text-blue-600 focus:ring-blue-500" {{ old('theme', $user->theme ?? 'light') === 'light' ? 'checked' : '' }}>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-sun text-amber-500 fs-5"></i>
                                <div>
                                    <span class="text-sm font-bold text-slate-800 dark:text-white block">Mode Terang</span>
                                    <span class="text-xs text-slate-400">Tampilan default</span>
                                </div>
                            </div>
                        </label>

                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer flex items-center gap-3 hover:border-blue-500 transition {{ old('theme', $user->theme) === 'dark' ? 'bg-blue-50/50 border-blue-500 dark:bg-blue-950/30' : '' }}">
                            <input type="radio" name="theme" value="dark" class="text-blue-600 focus:ring-blue-500" {{ old('theme', $user->theme) === 'dark' ? 'checked' : '' }}>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-moon-stars text-indigo-500 fs-5"></i>
                                <div>
                                    <span class="text-sm font-bold text-slate-800 dark:text-white block">Mode Gelap</span>
                                    <span class="text-xs text-slate-400">Nyaman di mata</span>
                                </div>
                            </div>
                        </label>

                        <label class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 cursor-pointer flex items-center gap-3 hover:border-blue-500 transition {{ old('theme', $user->theme) === 'auto' ? 'bg-blue-50/50 border-blue-500 dark:bg-blue-950/30' : '' }}">
                            <input type="radio" name="theme" value="auto" class="text-blue-600 focus:ring-blue-500" {{ old('theme', $user->theme) === 'auto' ? 'checked' : '' }}>
                            <div class="flex items-center gap-2">
                                <i class="bi bi-display text-slate-500 fs-5"></i>
                                <div>
                                    <span class="text-sm font-bold text-slate-800 dark:text-white block">Otomatis</span>
                                    <span class="text-xs text-slate-400">Ikuti sistem OS</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Bahasa --}}
                <div>
                    <label for="language" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                        Bahasa Antarmuka
                    </label>
                    <select name="language" id="language" class="form-select max-w-sm">
                        <option value="id" @selected(old('language', $user->language ?? 'id') === 'id')>🇮🇩 Bahasa Indonesia (ID)</option>
                        <option value="en" @selected(old('language', $user->language) === 'en')>🇬🇧 English (EN)</option>
                    </select>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-700/60 flex justify-end">
                    <button type="submit" class="btn btn-primary px-5 py-2.5 flex items-center gap-2">
                        <i class="bi bi-check2-circle fs-5"></i>
                        <span>Simpan Preferensi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- TAB 3: NOTIFIKASI ADMIN --}}
    <div x-show="activeTab === 'notifikasi'" class="space-y-6" x-transition>
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm overflow-hidden p-5 sm:p-7">
            <div class="flex items-center gap-3 pb-4 mb-5 border-b border-slate-100 dark:border-slate-700/60">
                <div class="w-9 h-9 rounded-xl bg-blue-50 dark:bg-blue-950/50 text-[#002B6B] dark:text-blue-400 font-extrabold text-sm flex items-center justify-center border border-blue-100 dark:border-blue-900/50">
                    <i class="bi bi-bell"></i>
                </div>
                <div>
                    <h2 class="text-base font-bold text-slate-900 dark:text-white m-0">Preferensi Notifikasi Administrator</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Tentukan pemberitahuan email yang ingin diterima oleh akun administrator Anda</p>
                </div>
            </div>

            <form action="{{ route('admin.setting.notifikasi') }}" method="POST" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl border border-slate-200/80 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                        <label class="flex items-start gap-3 cursor-pointer select-none m-0">
                            <input type="checkbox" name="pengguna_baru" value="1" 
                                   @checked($preferences && $preferences->isEnabled('pengguna_baru'))
                                   class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500 cursor-pointer mt-0.5">
                            <div>
                                <span class="text-sm font-bold text-slate-800 dark:text-white block">👤 Pendaftaran Pengguna Baru</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 m-0">Terima email notifikasi saat mahasiswa atau dosen baru terdaftar</p>
                            </div>
                        </label>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200/80 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                        <label class="flex items-start gap-3 cursor-pointer select-none m-0">
                            <input type="checkbox" name="pesan_baru" value="1" 
                                   @checked($preferences && $preferences->isEnabled('pesan_baru'))
                                   class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500 cursor-pointer mt-0.5">
                            <div>
                                <span class="text-sm font-bold text-slate-800 dark:text-white block">💬 Tiket Bantuan / Pesan Masuk</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 m-0">Pemberitahuan tiket bantuan atau pesan helpdesk baru</p>
                            </div>
                        </label>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200/80 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                        <label class="flex items-start gap-3 cursor-pointer select-none m-0">
                            <input type="checkbox" name="pengumuman_baru" value="1" 
                                   @checked($preferences && $preferences->isEnabled('pengumuman_baru'))
                                   class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500 cursor-pointer mt-0.5">
                            <div>
                                <span class="text-sm font-bold text-slate-800 dark:text-white block">📢 Siaran Pengumuman Kampus</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 m-0">Salinan notifikasi saat pengumuman global dipublikasikan</p>
                            </div>
                        </label>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200/80 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/30">
                        <label class="flex items-start gap-3 cursor-pointer select-none m-0">
                            <input type="checkbox" name="pengumpulan_tugas" value="1" 
                                   @checked($preferences && $preferences->isEnabled('pengumpulan_tugas'))
                                   class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500 cursor-pointer mt-0.5">
                            <div>
                                <span class="text-sm font-bold text-slate-800 dark:text-white block">📬 Log Sistem & Pengumpulan</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 m-0">Notifikasi ringkasan pengumpulan tugas berkala</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                    <a href="{{ route('admin.notification-preferences.index') }}" class="text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                        <i class="bi bi-people me-1"></i> Buka Manajemen Notifikasi Semua Civitas →
                    </a>
                    <button type="submit" class="btn btn-primary px-5 py-2.5 flex items-center gap-2">
                        <i class="bi bi-check2-circle fs-5"></i>
                        <span>Simpan Notifikasi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
