<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-8 px-4">
        <div class="w-full max-w-md">
            {{-- Logo/Header --}}
            <div class="text-center mb-8">
                <div class="flex items-center justify-center gap-2 mb-4">
                    <div class="bg-gradient-to-br from-[#321270] to-[#7c3aed] p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-white mb-1">Cendekia</h1>
                <p class="text-sm text-slate-400">Learning Management System</p>
            </div>

            {{-- Card --}}
            <div class="bg-slate-800/50 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-8 shadow-2xl">
                <x-auth-session-status class="mb-4 p-4 bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 rounded-lg text-sm" :status="session('status')" />

                {{-- Login Errors --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-500/20 border border-red-500/30 rounded-lg">
                        <p class="text-red-300 text-sm font-medium">
                            {{ $errors->first('nip_nim') ?: ($errors->first('password') ?: 'Login gagal, cek kembali NIM dan password') }}
                        </p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- NIM/NIDN Input (PRIMARY) --}}
                    <div>
                        <label for="nip_nim" class="block text-sm font-semibold text-slate-300 mb-2">
                            NIM / NIDN
                            <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input 
                                id="nip_nim" 
                                type="text" 
                                name="nip_nim" 
                                value="{{ old('nip_nim') }}"
                                placeholder="Cth: 20241001 atau 19790000001"
                                required
                                class="w-full pl-12 pr-4 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#321270] focus:border-transparent transition"
                                autocomplete="username"
                            />
                        </div>
                        <p class="text-xs text-slate-400 mt-2">Masukkan NIM (mahasiswa) atau NIDN (dosen)</p>
                        @error('nip_nim')
                            <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-300 mb-2">
                            Password
                            <span class="text-red-400">*</span>
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
                                placeholder="Masukkan password"
                                class="w-full pl-12 pr-12 py-3 bg-slate-700/50 border border-slate-600 rounded-lg text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-[#321270] focus:border-transparent transition"
                                autocomplete="current-password"
                            />
                            <button 
                                type="button" 
                                @click="show = !show"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-300 transition"
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
                            <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="flex items-center pt-2">
                        <input 
                            id="remember_me" 
                            type="checkbox" 
                            name="remember"
                            class="w-4 h-4 rounded border-slate-600 bg-slate-700/50 text-[#321270] focus:ring-2 focus:ring-[#321270]"
                        />
                        <label for="remember_me" class="ml-2 text-sm text-slate-400">
                            Ingat saya di perangkat ini
                        </label>
                    </div>

                    {{-- Submit Button --}}
                    <button 
                        type="submit"
                        class="w-full mt-6 px-4 py-3 bg-gradient-to-r from-[#321270] to-[#7c3aed] hover:from-[#250d54] hover:to-[#6d28d9] text-white font-semibold rounded-lg transition duration-200 shadow-lg hover:shadow-xl"
                    >
                        Masuk ke Dashboard
                    </button>
                </form>

                {{-- Test Accounts Info --}}
                <div class="mt-6 pt-6 border-t border-slate-700">
                    <p class="text-xs text-slate-400 text-center mb-3">
                        <strong>📚 Demo Credentials:</strong>
                    </p>
                    <div class="space-y-2 text-xs text-slate-500">
                        <div class="bg-slate-900/50 p-3 rounded border border-slate-700/50">
                            <p class="text-slate-300 font-mono font-semibold">👨‍🎓 Mahasiswa</p>
                            <p class="text-slate-400 font-mono mt-1">NIM: 20241001</p>
                            <p class="text-slate-400 font-mono">Password: password123</p>
                        </div>
                        <div class="bg-slate-900/50 p-3 rounded border border-slate-700/50">
                            <p class="text-slate-300 font-mono font-semibold">👨‍🏫 Dosen</p>
                            <p class="text-slate-400 font-mono mt-1">NIDN: 19790000001</p>
                            <p class="text-slate-400 font-mono">Password: password123</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <p class="text-center text-xs text-slate-500 mt-6">
                © {{ now()->year }} Kampus Cendekia. All rights reserved.
            </p>
        </div>
    </div>

</x-guest-layout>
