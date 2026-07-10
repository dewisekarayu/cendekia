<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 py-12 px-4">
        <div class="w-full max-w-sm">
            {{-- Logo/Header --}}
            <div class="text-center mb-12">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="bg-gradient-to-br from-[#7c3aed] to-[#321270] p-4 rounded-2xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-purple-400 to-blue-300 bg-clip-text text-transparent mb-2">Cendekia</h1>
                <p class="text-slate-400 text-sm">Platform Pembelajaran Digital</p>
            </div>

            {{-- Login Card --}}
            <div class="bg-slate-800/40 backdrop-blur-2xl border border-slate-700/50 rounded-3xl p-10 shadow-2xl">
                {{-- Welcome Text --}}
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-white mb-2">Masuk</h2>
                    <p class="text-slate-400 text-sm">Gunakan email, NIM/NIDN, dan password Anda</p>
                </div>

                <x-auth-session-status class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 rounded-lg text-sm" :status="session('status')" />

                {{-- Login Errors --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-xl flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-red-300 text-sm">
                            {{ $errors->first('email') ?: ($errors->first('nip_nim') ?: ($errors->first('password') ?: 'Login gagal, periksa kembali kredensial Anda')) }}
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email Input --}}
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-200 mb-3">
                            Email
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}"
                                placeholder="nama@example.com"
                                required
                                class="w-full pl-12 pr-4 py-3 bg-slate-700/30 border border-slate-600/50 rounded-xl text-white placeholder-slate-500/70 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500/50 focus:bg-slate-700/50 transition duration-200"
                                autocomplete="email"
                            />
                        </div>
                        @error('email')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIM/NIDN Input --}}
                    <div>
                        <label for="nip_nim" class="block text-sm font-semibold text-slate-200 mb-3">
                            NIM / NIDN
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <input 
                                id="nip_nim" 
                                type="text" 
                                name="nip_nim" 
                                value="{{ old('nip_nim') }}"
                                placeholder="20241001 atau 19790000001"
                                required
                                class="w-full pl-12 pr-4 py-3 bg-slate-700/30 border border-slate-600/50 rounded-xl text-white placeholder-slate-500/70 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500/50 focus:bg-slate-700/50 transition duration-200"
                                autocomplete="username"
                            />
                        </div>
                        @error('nip_nim')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Input --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-200 mb-3">
                            Password
                        </label>
                        <div class="relative" x-data="{ show: false }">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input 
                                :type="show ? 'text' : 'password'" 
                                id="password"
                                name="password" 
                                required
                                placeholder="••••••••"
                                class="w-full pl-12 pr-12 py-3 bg-slate-700/30 border border-slate-600/50 rounded-xl text-white placeholder-slate-500/70 focus:outline-none focus:ring-2 focus:ring-purple-500/50 focus:border-purple-500/50 focus:bg-slate-700/50 transition duration-200"
                                autocomplete="current-password"
                            />
                            <button 
                                type="button" 
                                @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-slate-300 transition"
                            >
                                <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center pt-2">
                        <input 
                            id="remember_me" 
                            type="checkbox" 
                            name="remember"
                            class="w-4 h-4 rounded bg-slate-700/50 border-slate-600 text-purple-600 focus:ring-2 focus:ring-purple-500 cursor-pointer"
                        />
                        <label for="remember_me" class="ml-3 text-sm text-slate-400 cursor-pointer">
                            Ingat saya di perangkat ini
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button 
                        type="submit"
                        class="w-full mt-8 px-4 py-3 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-semibold rounded-xl transition duration-200 shadow-lg hover:shadow-xl hover:scale-105 active:scale-95"
                    >
                        Masuk ke Dashboard
                    </button>

                    {{-- Divider --}}
                    <div class="relative mt-8 pt-8 border-t border-slate-700/50">
                        <p class="text-xs text-slate-500 text-center">
                            Belum tahu kredensial Anda? Hubungi bagian akademik
                        </p>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <p class="text-center text-xs text-slate-600 mt-8">
                © {{ now()->year }} Cendekia Learning Platform
            </p>
        </div>
    </div>

</x-guest-layout>
