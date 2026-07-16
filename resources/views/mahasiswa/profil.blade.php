@extends('layouts.portal')
@section('title', 'Profil')
@section('activeMenu', 'Profil')
@section('content')

<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Profil</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola informasi dan keamanan akun Anda</p>
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

    {{-- SUMMARY CARDS (shared with Setting page) --}}
    @include('mahasiswa.partials.stat-cards', [
        'kelasAktif'      => $totalKelas ?? 0,
        'rataRataNilai'   => $rataRata ? number_format($rataRata, 1) : '0.0',
        'mkDinilai'       => $nilaiAkhirList->count() ?? 0,
        'pengumumanCount' => $announcements->count() ?? 0,
    ])

    {{-- MAIN CONTENT --}}
    <div class="grid gap-6 lg:grid-cols-3">

        {{-- LEFT: TAB + CONTENT --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Tab bar (single tab here, kept for visual consistency with Setting page) --}}
            <div class="flex gap-2 overflow-x-auto rounded-full bg-white border border-gray-200 p-1.5 w-fit shadow-sm">
                <span class="whitespace-nowrap rounded-full px-5 py-2 text-xs font-semibold inline-flex items-center gap-1.5 bg-[#002B6B] text-white shadow-sm shadow-blue-900/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Keamanan
                </span>
            </div>

            {{-- KEAMANAN --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h3 class="font-bold text-slate-800">Ubah Kata Sandi</h3>
                    <p class="mt-0.5 text-xs text-gray-400">Pastikan gunakan kata sandi yang kuat dan mudah kamu ingat</p>
                </div>

                <form method="POST" action="{{ route('mahasiswa.setting.password') }}" class="space-y-5 p-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" placeholder="Masukkan password saat ini" required
                               class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#002B6B] focus:outline-none focus:ring-2 focus:ring-[#002B6B]/10 transition">
                        @error('current_password')
                            <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Kata Sandi Baru</label>
                            <input type="password" name="password" placeholder="Minimal 8 karakter" required minlength="8"
                                   class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#002B6B] focus:outline-none focus:ring-2 focus:ring-[#002B6B]/10 transition">
                            @error('password')
                                <p class="mt-1.5 text-xs font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Konfirmasi Kata Sandi</label>
                            <input type="password" name="password_confirmation" placeholder="Ulangi password baru" required minlength="8"
                                   class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#002B6B] focus:outline-none focus:ring-2 focus:ring-[#002B6B]/10 transition">
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-gray-100 pt-4">
                        <p class="text-xs text-gray-400">Kamu akan tetap login setelah kata sandi diperbarui</p>
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-[#002B6B] px-6 py-2.5 text-sm font-bold text-white hover:bg-blue-800 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Perbarui Kata Sandi
                        </button>
                    </div>
                </form>
            </div>
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