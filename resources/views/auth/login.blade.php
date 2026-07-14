<x-guest-layout>
<<<<<<< HEAD
<style>
    :root {
        --cnd-primary: #003b93;
        --cnd-primary-dark: #002359;
        --cnd-primary-light: #3b82f6;
        --cnd-light: #eef4ff;
        --cnd-bg-input: #f4f8ff;
        --cnd-text-main: #1e293b;
        --cnd-text-muted: #64748b;
    }

    body {
        margin: 0;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        background-color: #f8fbff;
    }

    .cnd-login-overlay {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        padding: 40px 20px;
        box-sizing: border-box;
        background: 
            radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.12) 0%, transparent 40%),
            radial-gradient(circle at 90% 80%, rgba(99, 102, 241, 0.12) 0%, transparent 40%),
            linear-gradient(135deg, #f0f6ff 0%, #e0ecff 50%, #f8fbff 100%);
    }

    /* Decorative Ambient Orbs */
    .cnd-orb-1 {
        position: absolute;
        top: -100px;
        left: -100px;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        background: rgba(59, 130, 246, 0.15);
        filter: blur(120px);
        pointer-events: none;
    }

    .cnd-orb-2 {
        position: absolute;
        bottom: -100px;
        right: -100px;
        width: 450px;
        height: 450px;
        border-radius: 50%;
        background: rgba(99, 102, 241, 0.15);
        filter: blur(120px);
        pointer-events: none;
    }

    /* Outer Wrapper Box */
    .cnd-login-box { 
        width: 100%; 
        max-width: 480px; /* Diperlebar sedikit agar menampung padding card luar */
        position: relative;
        z-index: 10;
    }

    /* === CARD LUARAN (OUTER CARD) === */
    .cnd-outer-card {
        background: rgba(255, 255, 255, 0.4);
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        border-radius: 36px;
        padding: 24px;
        box-shadow:
            0 40px 80px -20px rgba(0, 59, 147, 0.15),
            inset 0 1px 1px rgba(255, 255, 255, 0.7);
    }

    /* Logo header */
    .cnd-logo-wrap { text-align: center; margin-bottom: 28px; margin-top: 8px; }
    .cnd-logo-icon {
        display: inline-flex;
        align-items: center; 
        justify-content: center;
        width: 68px; 
        height: 68px;
        background: linear-gradient(135deg, var(--cnd-primary) 0%, var(--cnd-primary-dark) 100%);
        border-radius: 20px;
        box-shadow: 0 12px 25px rgba(0, 59, 147, 0.2);
        margin-bottom: 16px;
        transition: transform 0.3s ease;
    }
    .cnd-logo-icon:hover {
        transform: translateY(-2px);
    }
    .cnd-title {
        font-size: 30px; 
        font-weight: 800; 
        margin: 0 0 4px 0;
        color: var(--cnd-primary);
        letter-spacing: -0.5px;
    }
    .cnd-subtitle { 
        color: var(--cnd-text-muted); 
        font-size: 13.5px; 
        margin: 0; 
        font-weight: 500; 
    }

    /* Inner Card (Kartu Form Utama) */
    .cnd-card {
        position: relative;
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.8);
        border-radius: 24px;
        padding: 36px 32px;
        box-shadow: 0 10px 30px -10px rgba(0, 59, 147, 0.05);
    }

    .cnd-card-heading { margin-bottom: 24px; }
    .cnd-card-heading h2 { font-size: 20px; font-weight: 700; color: var(--cnd-text-main); margin: 0 0 4px 0; }
    .cnd-card-heading p { color: var(--cnd-text-muted); font-size: 13.5px; margin: 0; }

    /* Alerts */
    .cnd-alert {
        margin-bottom: 20px; 
        padding: 12px 14px;
        border-radius: 12px; 
        font-size: 13px;
        font-weight: 500;
        line-height: 1.4;
    }
    .cnd-alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }
    .cnd-alert-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }
    .cnd-alert-error svg { flex-shrink: 0; margin-top: 2px; }

    /* Form */
    .cnd-field { margin-bottom: 20px; }
    .cnd-label {
        display: block; 
        font-size: 13px; 
        font-weight: 600;
        color: var(--cnd-text-main); 
        margin-bottom: 8px;
    }
    .cnd-input-wrap { position: relative; }
    .cnd-input-icon {
        position: absolute; 
        left: 16px; 
        top: 50%;
        transform: translateY(-50%);
        display: flex; 
        align-items: center;
        pointer-events: none; 
        color: #94a3b8;
    }
    .cnd-input {
        width: 100%;
        box-sizing: border-box;
        padding: 13px 16px 13px 48px;
        background: var(--cnd-bg-input);
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        color: var(--cnd-text-main);
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .cnd-input.has-toggle { padding-right: 48px; }
    .cnd-input::placeholder { color: #94a3b8; }
    .cnd-input:focus {
        outline: none;
        border-color: var(--cnd-primary-light);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .cnd-error-text { color: #dc2626; font-size: 12px; margin: 6px 0 0 2px; font-weight: 500; }

    .cnd-toggle-pw {
        position: absolute; 
        right: 16px; 
        top: 50%;
        transform: translateY(-50%);
        display: flex; 
        align-items: center;
        background: none; 
        border: none; 
        color: #94a3b8;
        cursor: pointer; 
        transition: color 0.2s; 
        padding: 0;
    }
    .cnd-toggle-pw:hover { color: var(--cnd-primary-light); }

    .cnd-remember {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 22px;
        user-select: none;
    }
    .cnd-remember input {
        width: 16px; 
        height: 16px; 
        border-radius: 4px;
        border: 1.5px solid #cbd5e1;
        accent-color: var(--cnd-primary); 
        cursor: pointer;
    }
    .cnd-remember label { 
        font-size: 13px; 
        color: var(--cnd-text-muted); 
        cursor: pointer; 
        margin: 0; 
        font-weight: 500;
    }

    .cnd-submit {
        width: 100%;
        padding: 14px 16px;
        background: linear-gradient(90deg, var(--cnd-primary) 0%, var(--cnd-primary-dark) 100%);
        color: #fff; 
        font-weight: 700; 
        font-size: 14.5px;
        border: none; 
        border-radius: 12px;
        cursor: pointer;
        box-shadow: 0 10px 20px rgba(0, 59, 147, 0.15);
        transition: all 0.2s ease;
    }
    .cnd-submit:hover { 
        filter: brightness(1.05); 
        transform: translateY(-1px); 
        box-shadow: 0 12px 24px rgba(0, 59, 147, 0.2);
    }
    .cnd-submit:active { 
        transform: translateY(0); 
        filter: brightness(0.95);
    }

    .cnd-forgot-link {
        color: var(--cnd-primary-light);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
        font-size: 12.5px;
    }
    .cnd-forgot-link:hover {
        color: var(--cnd-primary);
        text-decoration: underline;
    }
</style>

<div class="cnd-login-overlay">
    <div class="cnd-orb-1"></div>
    <div class="cnd-orb-2"></div>

    <div class="cnd-login-box">
        <div class="cnd-outer-card">
            
            {{-- Logo/Header di dalam card luaran agar menyatu --}}
            <div class="cnd-logo-wrap">
                <div class="cnd-logo-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="9.5" stroke="#CDDCFF" stroke-width="1.6"/>
                        <path d="M8 12.5l2.7 2.7L16.5 9" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h1 class="cnd-title">Cendekia</h1>
                <p class="cnd-subtitle">Platform Pembelajaran Digital</p>
            </div>

            {{-- Login Card Utama (Inner Card) --}}
            <div class="cnd-card">
                <div class="cnd-card-heading">
                    <h2>Masuk</h2>
                    <p>Gunakan NIM, NIP, atau email Anda</p>
                </div>

                @if (session('status'))
                    <div class="cnd-alert cnd-alert-success">{{ session('status') }}</div>
=======

<div class="fixed inset-0 z-[9999] flex items-center justify-center w-screen h-screen overflow-y-auto px-4 py-12 bg-gradient-to-br from-[#F4F7FF] via-[#E9EFFF] to-[#DCE6FF]">
    <div class="w-full max-w-[420px] flex flex-col items-center justify-center">

        {{-- ===== ATAS: LOGO / HEADER ===== --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-[68px] h-[68px] rounded-[20px] bg-gradient-to-br from-[#002B6B] to-[#001A40] shadow-[0_10px_26px_rgba(0,43,107,0.28)] mb-4">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="9.5" stroke="#CDDCFF" stroke-width="1.6"/>
                    <path d="M8 12.5l2.7 2.7L16.5 9" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h1 class="text-[34px] lg:text-[38px] font-extrabold text-[#002B6B] tracking-tight mb-1">Cendekia</h1>
            <p class="text-slate-500 text-[13.5px] lg:text-[14.5px]">Platform Pembelajaran Digital</p>
        </div>

        {{-- ===== BAWAH: LOGIN FORM ===== --}}
        <div class="w-full">
            <div class="bg-white border border-[#E3E9F7] rounded-[26px] px-9 py-10 shadow-[0_20px_50px_rgba(0,43,107,0.12)]">

                @if (session('status'))
                    <div class="mb-5 px-4 py-3.5 rounded-2xl text-[13.5px] bg-emerald-50 border border-emerald-200 text-emerald-700">
                        {{ session('status') }}
                    </div>
>>>>>>> de3a8aff6ee0297a45b2732ec54b6f7ee96472d3
                @endif

                {{-- Login Errors --}}
                @if ($errors->any())
<<<<<<< HEAD
                    <div class="cnd-alert cnd-alert-error">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p>{{ $errors->first('credential') ?: ($errors->first('password') ?: 'Login gagal, periksa kembali kredensial Anda') }}</p>
=======
                    <div class="mb-5 px-4 py-3.5 rounded-2xl text-[13.5px] bg-red-50 border border-red-200 text-red-700 flex items-start gap-2.5">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20" class="shrink-0 mt-0.5">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p>{{ $errors->first('login') ?: $errors->first('password') ?: 'Login gagal, periksa kembali kredensial Anda' }}</p>
>>>>>>> de3a8aff6ee0297a45b2732ec54b6f7ee96472d3
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

<<<<<<< HEAD
                    {{-- Combined Credential Input (NIM/NIP/Email) --}}
                    <div class="cnd-field">
                        <label for="credential" class="cnd-label">NIM / NIP / Email</label>
                        <div class="cnd-input-wrap">
                            <div class="cnd-input-icon">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <input
                                id="credential"
                                type="text"
                                name="credential"
                                value="{{ old('credential') }}"
                                placeholder="Contoh: 20241001 atau nama@email.com"
                                required
                                class="cnd-input"
                                autocomplete="username"
                            />
                        </div>
                        @error('credential')
                            <p class="cnd-error-text">{{ $message }}</p>
=======
                    {{-- Email / NIM / NIDN Input --}}
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
                                placeholder="nama@example.com / 20241001"
                                required
                                autofocus
                                autocomplete="username"
                                class="w-full box-border pl-[46px] pr-4 py-3.5 bg-[#F4F7FF] border-[1.5px] border-[#DCE6FF] rounded-2xl text-gray-800 text-[14.5px] placeholder-gray-400 transition focus:outline-none focus:border-[#002B6B] focus:bg-white focus:ring-[3px] focus:ring-[#002B6B]/10"
                            />
                        </div>
                        @error('login')
                            <p class="text-red-600 text-xs mt-2 ml-0.5">{{ $message }}</p>
>>>>>>> de3a8aff6ee0297a45b2732ec54b6f7ee96472d3
                        @enderror
                    </div>

                    {{-- Password Input --}}
<<<<<<< HEAD
                    <div class="cnd-field">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                            <label for="password" class="cnd-label" style="margin-bottom: 0;">Password</label>
                            <a href="{{ route('password.request') }}" class="cnd-forgot-link">Lupa Password?</a>
                        </div>
                        <div class="cnd-input-wrap" x-data="{ show: false }">
                            <div class="cnd-input-icon">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
=======
                    <div class="mb-5">
                        <label for="password" class="block text-[13.5px] font-semibold text-gray-800 mb-2">Password</label>
                        <div class="relative" x-data="{ show: false }">
                            <div class="absolute left-4 inset-y-0 flex items-center pointer-events-none text-slate-400">
                                <svg width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor">
>>>>>>> de3a8aff6ee0297a45b2732ec54b6f7ee96472d3
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <input
                                :type="show ? 'text' : 'password'"
                                id="password"
                                name="password"
                                required
                                placeholder="••••••••"
<<<<<<< HEAD
                                class="cnd-input has-toggle"
                                autocomplete="current-password"
                            />
                            <button type="button" @click="show = !show" class="cnd-toggle-pw">
                                <svg x-show="!show" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="show" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
=======
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
>>>>>>> de3a8aff6ee0297a45b2732ec54b6f7ee96472d3
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
<<<<<<< HEAD
                            <p class="cnd-error-text">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="cnd-remember">
                        <input id="remember_me" type="checkbox" name="remember" />
                        <label for="remember_me">Ingat saya di perangkat ini</label>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit" class="cnd-submit">Masuk ke Dashboard</button>
                </form>
            </div>
            
        </div>
        </div>
=======
                            <p class="text-red-600 text-xs mt-2 ml-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Remember Me & Lupa Password (Dibuat sejajar) --}}
                    <div class="flex items-center justify-between pt-1.5 mb-5">
                        <div class="flex items-center gap-2.5">
                            <input id="remember_me" type="checkbox" name="remember"
                                   class="w-4 h-4 rounded accent-[#002B6B] cursor-pointer">
                            <label for="remember_me" class="text-[13.5px] text-gray-600 cursor-pointer select-none">Ingat saya</label>
                        </div>
                        
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[13.5px] font-medium text-[#002B6B] hover:underline transition">
                                Lupa Password?
                            </a>
                        @endif
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                            class="w-full py-3.5 px-4 rounded-2xl font-bold text-[14.5px] text-white bg-gradient-to-r from-[#002B6B] to-[#001A40] shadow-[0_10px_25px_rgba(0,43,107,0.3)] transition hover:brightness-110 hover:-translate-y-0.5 active:scale-[0.97]">
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
>>>>>>> de3a8aff6ee0297a45b2732ec54b6f7ee96472d3
</div>
</x-guest-layout>