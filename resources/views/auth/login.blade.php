<x-guest-layout>

<div class="fixed inset-0 z-[9999] flex items-center justify-center w-screen h-screen overflow-y-auto px-4 py-12 bg-gradient-to-br from-[#F4F7FF] via-[#E9EFFF] to-[#DCE6FF]">
    <div class="w-full max-w-[880px] lg:grid lg:grid-cols-2 lg:items-center lg:gap-16">

        {{-- ===== KIRI: LOGO / HEADER ===== --}}
        <div class="text-center lg:text-left mb-10 lg:mb-0">
            <div class="inline-flex items-center justify-center w-[68px] h-[68px] rounded-[20px] bg-gradient-to-br from-[#002B6B] to-[#001A40] shadow-[0_10px_26px_rgba(0,43,107,0.28)] mb-5">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="9.5" stroke="#CDDCFF" stroke-width="1.6"/>
                    <path d="M8 12.5l2.7 2.7L16.5 9" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h1 class="text-[34px] lg:text-[42px] font-extrabold text-[#002B6B] tracking-tight mb-2">Cendekia</h1>
            <p class="text-slate-500 text-[13.5px] lg:text-[15px] lg:max-w-[320px]">Platform Pembelajaran Digital</p>
        </div>

        {{-- ===== KANAN: LOGIN FORM ===== --}}
        <div class="w-full max-w-[400px] mx-auto lg:mx-0">
            <div class="bg-white border border-[#E3E9F7] rounded-[26px] px-9 py-10 shadow-[0_20px_50px_rgba(0,43,107,0.12)]">

                @if (session('status'))
                    <div class="mb-5 px-4 py-3.5 rounded-2xl text-[13.5px] bg-emerald-50 border border-emerald-200 text-emerald-700">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Login Errors --}}
                @if ($errors->any())
                    <div class="mb-5 px-4 py-3.5 rounded-2xl text-[13.5px] bg-red-50 border border-red-200 text-red-700 flex items-start gap-2.5">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20" class="shrink-0 mt-0.5">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p>{{ $errors->first('login') ?: $errors->first('password') ?: 'Login gagal, periksa kembali kredensial Anda' }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email / NIM / NIDN Input (digabung) --}}
                    <div class="mb-5">
                        <label for="login" class="block text-[13.5px] font-semibold text-gray-800 mb-2">Email / NIM / NIDN</label>
                        <div class="relative">
                            <div class="absolute left-4 inset-y-0 flex items-center pointer-events-none text-slate-400">
                                <svg width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input
                                id="login"
                                type="text"
                                name="login"
                                value="{{ old('login') }}"
                                placeholder="nama@example.com / 20241001 / 19790000001"
                                required
                                autofocus
                                autocomplete="username"
                                class="w-full box-border pl-[46px] pr-4 py-3.5 bg-[#F4F7FF] border-[1.5px] border-[#DCE6FF] rounded-2xl text-gray-800 text-[14.5px] placeholder-gray-400 transition focus:outline-none focus:border-[#002B6B] focus:bg-white focus:ring-[3px] focus:ring-[#002B6B]/10"
                            />
                        </div>
                        @error('login')
                            <p class="text-red-600 text-xs mt-2 ml-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Input --}}
                    <div class="mb-5">
                        <label for="password" class="block text-[13.5px] font-semibold text-gray-800 mb-2">Password</label>
                        <div class="relative" x-data="{ show: false }">
                            <div class="absolute left-4 inset-y-0 flex items-center pointer-events-none text-slate-400">
                                <svg width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input
                                :type="show ? 'text' : 'password'"
                                id="password"
                                name="password"
                                required
                                placeholder="••••••••"
                                autocomplete="current-password"
                                class="w-full box-border pl-[46px] pr-[46px] py-3.5 bg-[#F4F7FF] border-[1.5px] border-[#DCE6FF] rounded-2xl text-gray-800 text-[14.5px] placeholder-gray-400 transition focus:outline-none focus:border-[#002B6B] focus:bg-white focus:ring-[3px] focus:ring-[#002B6B]/10"
                            />
                            <button type="button" @click="show = !show"
                                    class="absolute right-3.5 inset-y-0 flex items-center px-1 text-slate-400 hover:text-[#002B6B] transition">
                                <svg x-show="!show" width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="show" width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-600 text-xs mt-2 ml-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center gap-2.5 pt-1.5 mb-2">
                        <input id="remember_me" type="checkbox" name="remember"
                               class="w-4 h-4 rounded accent-[#002B6B] cursor-pointer">
                        <label for="remember_me" class="text-[13.5px] text-gray-600 cursor-pointer">Ingat saya di perangkat ini</label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                            class="w-full mt-2 py-3.5 px-4 rounded-2xl font-bold text-[14.5px] text-white bg-gradient-to-r from-[#002B6B] to-[#001A40] shadow-[0_10px_25px_rgba(0,43,107,0.3)] transition hover:brightness-110 hover:-translate-y-0.5 active:scale-[0.97]">
                        Masuk ke Dashboard
                    </button>

                    {{-- Divider --}}
                    <div class="mt-6.5 pt-5.5 border-t border-[#E3E9F7] text-center">
                        <p class="text-xs text-slate-400">Belum tahu kredensial Anda? Hubungi bagian akademik</p>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <p class="text-center text-xs text-slate-400 mt-6.5">&copy; {{ now()->year }} Cendekia Learning Platform</p>
        </div>

    </div>
</div>

</x-guest-layout>   