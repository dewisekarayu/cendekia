@extends('layouts.portal')
@section('title', 'Profil')
@section('activeMenu', 'Profil')
@section('content')

@php
    $user = auth()->user();
@endphp

<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Profil Dosen</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola informasi pribadi dan akun Anda</p>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- PROFILE CARD --}}
        <div class="lg:col-span-1">
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="h-32 bg-gradient-to-r from-[#321270] to-[#4a1fa8]"></div>
                
                <div class="relative px-5 pb-5">
                    <div class="mb-4 -mt-12 flex items-end justify-between">
                        <div class="flex h-24 w-24 shrink-0 items-center justify-center rounded-full border-4 border-white bg-[#321270] text-2xl font-bold text-white shadow-lg">
                            {{ strtoupper(substr($user->name ?? '?', 0, 1)) }}
                        </div>
                        <a href="#edit-profil" class="inline-flex items-center gap-1.5 rounded-lg bg-[#321270] px-4 py-2 text-xs font-semibold text-white hover:bg-[#321270]/90 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                    </div>
                    
                    <div class="space-y-1 text-center">
                        <h3 class="text-lg font-bold text-slate-800">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-[#321270]/10 px-3 py-1 text-xs font-semibold text-[#321270]">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Dosen
                        </div>
                    </div>

                    <div class="mt-4 space-y-3 border-t border-gray-100 pt-4">
                        @php
                            $stats = [
                                ['label' => 'Kelas Diampu', 'value' => $user->kelasPerkuliahan?->count() ?? 0],
                                ['label' => 'Mahasiswa', 'value' => $user->mahasiswa?->count() ?? 0],
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
                
                <form class="space-y-5 p-5">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" value="{{ $user->name }}" placeholder="Nama lengkap"
                                   class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Email</label>
                            <input type="email" value="{{ $user->email }}" placeholder="Email" disabled
                                   class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-500">
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" placeholder="Nomor telepon"
                                   class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Nomor Induk Dosen (NID)</label>
                            <input type="text" placeholder="NID"
                                   class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Biografi</label>
                        <textarea placeholder="Tulis biografi singkat Anda..." rows="4"
                                  class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="reset"
                                class="rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="rounded-lg bg-[#321270] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#321270]/90 transition">
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
                
                <form class="space-y-5 p-5">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Password Saat Ini</label>
                        <input type="password" placeholder="Masukkan password saat ini"
                               class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Password Baru</label>
                            <input type="password" placeholder="Masukkan password baru"
                                   class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                            <input type="password" placeholder="Konfirmasi password baru"
                                   class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="reset"
                                class="rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="rounded-lg bg-[#321270] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#321270]/90 transition">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- PREFERENSI --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h3 class="font-bold text-slate-800">Preferensi</h3>
                </div>
                
                <div class="space-y-4 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Notifikasi Email</p>
                            <p class="text-xs text-gray-500 mt-0.5">Terima notifikasi penting melalui email</p>
                        </div>
                        <button class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-emerald-500 transition-colors ease-in-out focus:outline-none focus:ring-2 focus:ring-[#321270]/50"
                                role="switch" aria-checked="true">
                            <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-5"></span>
                        </button>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">Mode Gelap</p>
                                <p class="text-xs text-gray-500 mt-0.5">Gunakan tema gelap untuk antarmuka</p>
                            </div>
                            <button class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-gray-300 transition-colors ease-in-out focus:outline-none focus:ring-2 focus:ring-[#321270]/50"
                                    role="switch" aria-checked="false">
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
