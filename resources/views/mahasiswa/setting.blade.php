@extends('layouts.portal')
@section('title', 'Settings')
@section('activeMenu', 'Settings')
@section('content')

@php $user = auth()->user(); @endphp

<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Pengaturan</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola informasi dan keamanan akun Anda</p>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- PROFILE CARD --}}
        <div class="lg:col-span-1">
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="h-32 bg-gradient-to-r from-[#0066ff] to-[#0052cc]"></div>
                
                <div class="relative px-5 pb-5">
                    <div class="mb-4 -mt-12 flex items-end justify-between">
                        <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full border-4 border-white bg-[#0066ff] text-2xl font-bold text-white shadow-lg">
                            {{ strtoupper(substr($user->name ?? '?', 0, 1)) }}
                        </div>
                    </div>
                    
                    <div class="space-y-1 text-center">
                        <h3 class="text-lg font-bold text-slate-800">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        <p class="text-sm text-gray-500">NIM: {{ $user->nip_nim }}</p>
                        <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-[#0066ff]/10 px-3 py-1 text-xs font-semibold text-[#0066ff]">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Mahasiswa
                        </div>
                    </div>

                    <div class="mt-4 space-y-3 border-t border-gray-100 pt-4">
                        @php
                            $stats = [
                                ['label' => 'Program Studi', 'value' => $user->programStudi?->nama_prodi ?? '-'],
                                ['label' => 'Bergabung', 'value' => $user->created_at->translatedFormat('d M Y')],
                            ];
                        @endphp
                        @foreach ($stats as $stat)
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-500">{{ $stat['label'] }}</span>
                                <span class="font-bold text-slate-800">{{ $stat['value'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM CARD --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- INFORMASI PRIBADI --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h3 class="font-bold text-slate-800">Informasi Pribadi</h3>
                </div>
                
                <form class="space-y-5 p-5" method="POST" action="{{ route('mahasiswa.setting.profile') }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ $user->name }}" placeholder="Nama lengkap"
                                   class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#0066ff] focus:outline-none focus:ring-2 focus:ring-[#0066ff]/10 transition @error('name') border-red-400 @enderror">
                            @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" placeholder="Email"
                                   class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#0066ff] focus:outline-none focus:ring-2 focus:ring-[#0066ff]/10 transition @error('email') border-red-400 @enderror">
                            @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">NIM</label>
                            <input type="text" value="{{ $user->nip_nim }}" placeholder="NIM" disabled
                                   class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Program Studi</label>
                            <input type="text" value="{{ $user->programStudi?->nama_prodi ?? '-' }}" disabled
                                   class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="reset"
                                class="rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="rounded-lg bg-[#0066ff] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#0052cc] transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- KEAMANAN --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h3 class="font-bold text-slate-800">Keamanan Akun</h3>
                </div>
                
                <form class="space-y-5 p-5" method="POST" action="{{ route('mahasiswa.setting.password') }}">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Password Saat Ini</label>
                        <input type="password" name="current_password" placeholder="Masukkan password saat ini"
                               class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#0066ff] focus:outline-none focus:ring-2 focus:ring-[#0066ff]/10 transition @error('current_password') border-red-400 @enderror">
                        @error('current_password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Password Baru</label>
                            <input type="password" name="password" placeholder="Masukkan password baru"
                                   class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#0066ff] focus:outline-none focus:ring-2 focus:ring-[#0066ff]/10 transition @error('password') border-red-400 @enderror">
                            @error('password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" placeholder="Konfirmasi password baru"
                                   class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#0066ff] focus:outline-none focus:ring-2 focus:ring-[#0066ff]/10 transition">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="reset"
                                class="rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit" class="rounded-lg bg-[#0066ff] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#0052cc] transition">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
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
        <div x-data="{ tab: 'profile' }">
            <div class="flex gap-1 rounded-2xl bg-white border border-slate-100 shadow-sm p-1.5 mb-5">
                <button @click="tab='profile'"
                    :class="tab==='profile' ? 'bg-[#002B6B] text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Profil
                </button>
                <button @click="tab='password'"
                    :class="tab==='password' ? 'bg-amber-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Keamanan
                </button>
            </div>

            {{-- PROFILE TAB --}}
            <div x-show="tab==='profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="rounded-2xl bg-white border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-50 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#002B6B] shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Informasi Profil</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Perbarui nama dan email yang tampil di platform</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('mahasiswa.setting.profile') }}" class="px-6 py-6">
                        @csrf @method('PATCH')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Nama Lengkap <span class="text-red-400 normal-case">*</span></label>
                                <input type="text" name="name" value="{{ old('name', $u->name) }}"
                                    class="w-full rounded-xl border px-4 py-3 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:bg-white focus:border-[#002B6B] focus:ring-2 focus:ring-[#002B6B]/10 transition-all @error('name') border-red-300 bg-red-50 @enderror"
                                    style="border-color: {{ $errors->has('name') ? '' : '#e5e7eb' }};"
                                    placeholder="Nama lengkap kamu" required>
                                @error('name')<p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">NIM</label>
                                <div class="relative">
                                    <input type="text" value="{{ $u->nip_nim ?? '–' }}" disabled
                                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-400 bg-gray-100 cursor-not-allowed pr-10">
                                    <span class="absolute inset-y-0 right-3 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </span>
                                </div>
                                <p class="mt-1.5 text-[11px] text-gray-400">NIM tidak dapat diubah sendiri</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Alamat Email <span class="text-red-400 normal-case">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $u->email) }}"
                                    class="w-full rounded-xl border px-4 py-3 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:bg-white focus:border-[#002B6B] focus:ring-2 focus:ring-[#002B6B]/10 transition-all @error('email') border-red-300 bg-red-50 @enderror"
                                    style="border-color: {{ $errors->has('email') ? '' : '#e5e7eb' }};"
                                    placeholder="email@student.ac.id" required>
                                @error('email')<p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Program Studi</label>
                                <div class="relative">
                                    <input type="text" value="{{ $u->programStudi?->nama_prodi ?? 'Belum ditentukan' }}" disabled
                                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-400 bg-gray-100 cursor-not-allowed pr-10">
                                    <span class="absolute inset-y-0 right-3 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 pt-5 border-t border-gray-50 flex items-center justify-between">
                            <p class="text-xs text-gray-400">Perubahan akan langsung berlaku setelah disimpan</p>
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-[#002B6B] px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#003380] active:scale-95 transition-all duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- PASSWORD / KEAMANAN TAB --}}
            <div x-show="tab==='password'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="rounded-2xl bg-white border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-50 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Ubah Kata Sandi</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Pastikan gunakan kata sandi yang kuat dan mudah kamu ingat</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('mahasiswa.setting.password') }}" class="px-6 py-6">
                        @csrf @method('PATCH')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Kata Sandi Saat Ini <span class="text-red-400 normal-case">*</span></label>
                                <input type="password" name="current_password"
                                    class="w-full rounded-xl border px-4 py-3 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10 transition-all @error('current_password', 'updatePassword') border-red-300 bg-red-50 @enderror"
                                    style="border-color: {{ $errors->updatePassword->has('current_password') ? '' : '#e5e7eb' }};"
                                    placeholder="Masukkan kata sandi saat ini" required>
                                @error('current_password', 'updatePassword')<p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Kata Sandi Baru <span class="text-red-400 normal-case">*</span></label>
                                <input type="password" name="password"
                                    class="w-full rounded-xl border px-4 py-3 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10 transition-all @error('password', 'updatePassword') border-red-300 bg-red-50 @enderror"
                                    style="border-color: {{ $errors->updatePassword->has('password') ? '' : '#e5e7eb' }};"
                                    placeholder="Minimal 8 karakter" required>
                                @error('password', 'updatePassword')<p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Konfirmasi Kata Sandi <span class="text-red-400 normal-case">*</span></label>
                                <input type="password" name="password_confirmation"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10 transition-all"
                                    placeholder="Ulangi kata sandi baru" required>
                            </div>
                        </div>
                        <div class="mt-6 pt-5 border-t border-gray-50 flex items-center justify-between">
                            <p class="text-xs text-gray-400">Kamu akan tetap login setelah kata sandi diperbarui</p>
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-amber-600 active:scale-95 transition-all duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: INFO CARD --}}
    <div class="lg:col-span-1 space-y-5">
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
                    <span class="text-xs font-semibold text-slate-700">{{ $u->nip_nim ?? '–' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400">Program Studi</span>
                    <span class="text-xs font-semibold text-slate-700 text-right">{{ $u->programStudi?->nama_prodi ?? '–' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400">Bergabung Sejak</span>
                    <span class="text-xs font-semibold text-slate-700">{{ $u->created_at?->translatedFormat('M Y') ?? '–' }}</span>
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-6 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mx-auto text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="mt-2 text-xs text-slate-500">Data NIM dan Program Studi hanya dapat diubah melalui admin akademik.</p>
        </div>
    </div>
</div>

@endsection
