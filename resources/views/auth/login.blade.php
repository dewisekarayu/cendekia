<x-guest-layout>

    {{-- Session status (e.g. password reset success) --}}
    @if (session('status'))
        <div class="anim-in mb-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('status') }}
        </div>
    @endif

    {{-- Heading --}}
    <div class="anim-in mb-8">
        <h2 class="text-2xl font-black text-slate-900 sm:text-3xl">Selamat Datang 👋</h2>
        <p class="mt-1.5 text-sm text-gray-500 leading-relaxed">
            Masuk ke portal akademik Cendekia menggunakan NIP atau NIM dan kata sandi kamu.
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="anim-in flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- NIP / NIM --}}
        <div class="anim-in-1">
            <label for="nip_nim" class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1.5">
                NIP / NIM
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <input
                    id="nip_nim"
                    name="nip_nim"
                    type="text"
                    value="{{ old('nip_nim') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Contoh: ADM0001 / 197900001 / 202400001"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3 pl-10 pr-4 text-sm text-gray-800 placeholder-gray-400
                           focus:border-[#002B6B] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#002B6B]/10 transition
                           @error('nip_nim') border-red-300 bg-red-50 @enderror">
            </div>
        </div>

        {{-- Password --}}
        <div class="anim-in-2" x-data="{ show: false }">
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-xs font-bold uppercase tracking-wide text-gray-500">
                    Kata Sandi
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-semibold text-[#002B6B] hover:underline">
                        Lupa password?
                    </a>
                @endif
            </div>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <input
                    :type="show ? 'text' : 'password'"
                    id="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3 pl-10 pr-12 text-sm text-gray-800 placeholder-gray-400
                           focus:border-[#002B6B] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#002B6B]/10 transition
                           @error('password') border-red-300 bg-red-50 @enderror">
                <button
                    type="button"
                    @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-gray-400 hover:text-gray-600 transition"
                    :aria-label="show ? 'Sembunyikan' : 'Tampilkan'">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Remember me --}}
        <div class="anim-in-2 flex items-center gap-2.5">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                class="h-4 w-4 rounded border-gray-300 text-[#002B6B] focus:ring-[#002B6B]/30 cursor-pointer">
            <label for="remember_me" class="text-sm text-gray-600 cursor-pointer select-none">
                Ingat saya di perangkat ini
            </label>
        </div>

        {{-- Submit --}}
        <div class="anim-in-3 pt-1">
            <button
                type="submit"
                class="group relative flex w-full items-center justify-center gap-2 overflow-hidden rounded-xl bg-[#002B6B]
                       px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-900/25
                       hover:bg-blue-800 active:scale-[0.98] transition-all duration-150">
                <span>Masuk ke Dashboard</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </div>

        {{-- Demo credentials --}}
        <div class="anim-in-4 rounded-xl border border-dashed border-gray-200 bg-gray-50 p-4">
            <p class="mb-2 text-[11px] font-bold uppercase tracking-wide text-gray-400">Akun Demo</p>
            <div class="space-y-1.5 text-xs text-gray-600">
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block h-1.5 w-1.5 rounded-full bg-blue-500"></span> Admin
                    </span>
                    <span class="font-mono text-gray-500">ADM0001 / admin123</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block h-1.5 w-1.5 rounded-full bg-violet-500"></span> Dosen
                    </span>
                    <span class="font-mono text-gray-500">197900001 / dosen123</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-1.5">
                        <span class="inline-block h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Mahasiswa
                    </span>
                    <span class="font-mono text-gray-500">202400001 / mahasiswa123</span>
                </div>
            </div>
        </div>

        {{-- Date --}}
        <p class="anim-in-4 text-center text-[11px] text-gray-400">
            {{ now()->translatedFormat('l, d F Y') }}
        </p>
    </form>

</x-guest-layout>
