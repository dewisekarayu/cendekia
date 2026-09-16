@extends('layouts.admin')

@section('title', 'Preferensi Notifikasi: ' . $user->name)
@section('activeMenu', 'Pengaturan')

@section('content')
<div class="space-y-6">
    
    {{-- Page Header & Breadcrumbs --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-900/40 text-[#002B6B] dark:text-blue-400 border border-blue-100 dark:border-blue-800/60">
                    <i class="bi bi-bell-fill text-xs"></i>
                    Konfigurasi Notifikasi Pengguna
                </span>
            </div>
            <h1 class="page-title mb-1 text-2xl sm:text-3xl font-extrabold text-[#002B6B] dark:text-white">
                Preferensi Notifikasi Email
            </h1>
            <nav style="--bs-breadcrumb-divider: '›';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.notification-preferences.index') }}">Pengaturan Notifikasi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
                </ol>
            </nav>
        </div>

        <div>
            <a href="{{ route('admin.notification-preferences.index') }}" 
               class="btn btn-light border bg-white dark:bg-slate-800 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 d-inline-flex align-items-center gap-2 px-3.5 py-2 text-sm font-semibold shadow-sm"
               style="border-radius: 0.75rem;">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali ke Daftar</span>
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
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- ERROR ALERT --}}
    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-900/50 text-red-800 dark:text-red-300 shadow-sm flex items-start gap-3" role="alert">
            <div class="w-9 h-9 rounded-xl bg-red-100 dark:bg-red-900/60 text-red-600 dark:text-red-400 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="bi bi-exclamation-triangle-fill fs-5"></i>
            </div>
            <div class="flex-1 text-sm">
                <strong class="font-bold block mb-1">Gagal menyimpan data:</strong>
                <ul class="list-disc list-inside space-y-0.5 text-xs text-red-700 dark:text-red-300">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- User Profile Summary Card --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/40 text-[#002B6B] dark:text-blue-400 font-black text-xl flex items-center justify-center border border-blue-100 dark:border-blue-800/60 flex-shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white m-0 truncate">{{ $user->name }}</h2>
                    @foreach($user->getRoleNames() as $role)
                        <span class="badge-code" style="font-size: 0.75rem;">
                            {{ ucfirst($role) }}
                        </span>
                    @endforeach
                </div>
                <div class="flex items-center gap-4 text-xs text-slate-500 dark:text-slate-400 flex-wrap">
                    <span class="flex items-center gap-1.5"><i class="bi bi-envelope"></i> {{ $user->email }}</span>
                    @if($user->nip_nim)
                        <span class="flex items-center gap-1.5"><i class="bi bi-person-vcard"></i> NIM/NIP: {{ $user->nip_nim }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Notification Preferences Form --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/90 dark:border-slate-700 shadow-sm overflow-hidden">
        
        <div class="p-5 sm:p-6 border-b border-slate-100 dark:border-slate-700/60 flex items-center justify-between flex-wrap gap-3">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white m-0">Kanal & Topik Notifikasi Email</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 m-0">Centang notifikasi yang ingin dikirimkan ke email pengguna</p>
            </div>

            {{-- Quick Bulk Actions (External Forms) --}}
            <div class="flex items-center gap-2 flex-wrap">
                <button type="submit" form="formEnableAll" class="btn btn-sm btn-light border text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800 text-xs font-bold px-3 py-1.5" style="border-radius: 0.6rem;">
                    <i class="bi bi-check-all me-1"></i> Aktifkan Semua
                </button>
                <button type="submit" form="formDisableAll" class="btn btn-sm btn-light border text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-950/40 border-red-200 dark:border-red-800 text-xs font-bold px-3 py-1.5" style="border-radius: 0.6rem;" onclick="return confirm('Nonaktifkan semua preferensi notifikasi untuk pengguna ini?')">
                    <i class="bi bi-x-lg me-1"></i> Nonaktifkan Semua
                </button>
                <button type="submit" form="formResetDefault" class="btn btn-sm btn-light border text-slate-700 dark:text-slate-300 text-xs font-bold px-3 py-1.5" style="border-radius: 0.6rem;" onclick="return confirm('Reset preferensi notifikasi ke default?')">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Default
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.notification-preferences.update', $user) }}" id="formPreferences" class="p-5 sm:p-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($notificationTypes as $key => $info)
                    <div class="p-4 rounded-xl border border-slate-200/80 dark:border-slate-700 hover:border-blue-400 dark:hover:border-blue-500 transition-all bg-slate-50/50 dark:bg-slate-900/30">
                        <label for="{{ $key }}" class="flex items-start gap-3 cursor-pointer select-none m-0">
                            <input type="checkbox" 
                                   name="{{ $key }}" 
                                   id="{{ $key }}" 
                                   value="1" 
                                   @checked($preferences && $preferences->isEnabled($key))
                                   class="w-5 h-5 rounded text-blue-600 focus:ring-blue-500 cursor-pointer mt-0.5">
                            <div class="flex-1 min-w-0">
                                <span class="text-sm font-bold text-slate-800 dark:text-white block">{{ $info['name'] }}</span>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 mb-1 leading-relaxed">{{ $info['description'] }}</p>
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-slate-400">
                                    <i class="bi bi-people-fill text-[10px]"></i> {{ $info['audience'] }}
                                </span>
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>

            {{-- Form Footer --}}
            <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between">
                <a href="{{ route('admin.notification-preferences.index') }}"
                   class="btn btn-light border px-4 py-2 text-sm font-semibold rounded-xl text-slate-700 dark:text-slate-200">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary px-5 py-2.5 d-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-check2-circle fs-5"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>

    </div>

    {{-- Invisible Auxiliary Action Forms --}}
    <form id="formEnableAll" method="POST" action="{{ route('admin.notification-preferences.enableAll', $user) }}" class="d-none">
        @csrf @method('PUT')
    </form>
    <form id="formDisableAll" method="POST" action="{{ route('admin.notification-preferences.disableAll', $user) }}" class="d-none">
        @csrf @method('PUT')
    </form>
    <form id="formResetDefault" method="POST" action="{{ route('admin.notification-preferences.reset', $user) }}" class="d-none">
        @csrf @method('PUT')
    </form>

    {{-- Tips Card --}}
    <div class="p-4 rounded-xl bg-blue-50/60 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/40 text-xs text-slate-600 dark:text-slate-300">
        <div class="font-bold text-[#002B6B] dark:text-blue-400 mb-1 flex items-center gap-1.5">
            <i class="bi bi-info-circle-fill"></i> Catatan Sistem Notifikasi:
        </div>
        <ul class="list-disc list-inside space-y-0.5 text-slate-500 dark:text-slate-400">
            <li>Notifikasi yang dinonaktifkan tidak akan diteruskan ke alamat email pengguna.</li>
            <li>Perubahan konfigurasi notifikasi langsung aktif secara instan.</li>
            <li>Pengguna juga tetap dapat mengatur preferensi mandiri melalui menu Pengaturan profil mereka.</li>
        </ul>
    </div>

</div>
@endsection
