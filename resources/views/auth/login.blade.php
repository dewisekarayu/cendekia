<x-guest-layout>

<style>
    :root{
        --cnd-primary: #002B6B;
        --cnd-primary-dark: #001A40;
        --cnd-light: #CDDCFF;
        --cnd-bg-1: #F4F7FF;
        --cnd-bg-2: #E9EFFF;
    }

    .cnd-login-overlay{
        position: relative !important;
        min-height: 100vh;
        z-index: 9999;
        display: flex !important;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 48px 16px;
        background: linear-gradient(160deg, var(--cnd-bg-1) 0%, var(--cnd-bg-2) 55%, #DCE6FF 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .cnd-login-box{ width: 100%; max-width: 400px; }

    /* Logo header */
    .cnd-logo-wrap{ text-align: center; margin-bottom: 40px; }
    .cnd-logo-icon{
        display: inline-flex !important;
        align-items: center; justify-content: center;
        width: 80px; height: 80px;
        background: linear-gradient(135deg, var(--cnd-primary) 0%, var(--cnd-primary-dark) 100%);
        border-radius: 24px;
        box-shadow: 0 15px 35px rgba(0,43,107,.35);
        margin-bottom: 24px;
    }
    .cnd-logo-icon svg {
        filter: drop-shadow(0 2px 4px rgba(0,0,0,.1));
    }
    .cnd-title{
        font-size: 42px; font-weight: 900; margin: 0 0 8px 0;
        color: var(--cnd-primary);
        letter-spacing: -1px;
    }
    .cnd-subtitle{ color: #64748b; font-size: 14px; margin: 0; font-weight: 500; }

    /* Card */
    .cnd-card{
        border: 1px solid #89abfaff;
        border-radius: 26px;
        padding: 40px 36px;
        box-shadow: 0 20px 50px rgba(0,43,107,.12);
    }

    .cnd-card-heading{ margin-bottom: 28px; }
    .cnd-card-heading h2{ font-size: 24px; font-weight: 700; color: var(--cnd-primary); margin: 0 0 6px 0; }
    .cnd-card-heading p{ color: #64748b; font-size: 13.5px; margin: 0; }

    /* Alerts */
    .cnd-alert{
        margin-bottom: 22px; padding: 14px 16px;
        border-radius: 14px; font-size: 13.5px;
    }
    .cnd-alert-success{
        background: #ECFDF5;
        border: 1px solid #A7F3D0;
        color: #047857;
    }
    .cnd-alert-error{
        background: #FEF2F2;
        border: 1px solid #FECACA;
        color: #B91C1C;
        display: flex !important;
        align-items: flex-start;
        gap: 10px;
    }
    .cnd-alert-error svg{ flex-shrink: 0; margin-top: 2px; }

    /* Form */
    .cnd-field{ margin-bottom: 20px; }
    .cnd-label{
        display: block; font-size: 13.5px; font-weight: 600;
        color: #1F2937; margin-bottom: 9px;
    }
    .cnd-input-wrap{ position: relative; }
    .cnd-input-icon{
        position: absolute; left: 16px; top: 0; bottom: 0;
        display: flex !important; align-items: center;
        pointer-events: none; color: #94A3B8;
    }
    .cnd-input{
        width: 100%;
        box-sizing: border-box;
        padding: 13px 16px 13px 46px;
        background: var(--cnd-bg-1);
        border: 1.5px solid #DCE6FF;
        border-radius: 14px;
        color: #1F2937;
        font-size: 14.5px;
        transition: .2s;
    }
    .cnd-input.has-toggle{ padding-right: 46px; }
    .cnd-input::placeholder{ color: #9CA3AF; }
    .cnd-input:focus{
        outline: none;
        border-color: var(--cnd-primary);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(0,43,107,.12);
    }
    .cnd-error-text{ color: #DC2626; font-size: 12px; margin: 8px 0 0 2px; }

    .cnd-toggle-pw{
        position: absolute; right: 14px; top: 0; bottom: 0;
        display: flex !important; align-items: center;
        background: none; border: none; color: #94A3B8;
        cursor: pointer; transition: .2s; padding: 0 4px;
    }
    .cnd-toggle-pw:hover{ color: var(--cnd-primary); }

    .cnd-remember{
        display: flex !important;
        align-items: center;
        gap: 10px;
        padding-top: 6px;
        margin-bottom: 8px;
    }
    .cnd-remember input{
        width: 16px; height: 16px; border-radius: 4px;
        accent-color: var(--cnd-primary); cursor: pointer;
    }
    .cnd-remember label{ font-size: 13.5px; color: #4B5563; cursor: pointer; margin: 0; }

    .cnd-submit{
        width: 100%;
        margin-top: 8px;
        padding: 14px 16px;
        background: linear-gradient(90deg, var(--cnd-primary) 0%, var(--cnd-primary-dark) 100%);
        color: #fff; font-weight: 700; font-size: 14.5px;
        border: none; border-radius: 14px;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(0,43,107,.3);
        transition: .15s;
    }
    .cnd-submit:hover{ filter: brightness(1.1); transform: translateY(-1px); }
    .cnd-submit:active{ transform: scale(.97); }

    /* Forgot Password Link */
    .cnd-forgot-link{
        color: var(--cnd-primary);
        text-decoration: none;
        font-weight: 500;
        transition: all .2s ease;
        font-size: 13px;
    }
    .cnd-forgot-link:hover{
        color: var(--cnd-primary-dark);
        text-decoration: underline;
    }
</style>

<div class="cnd-login-overlay">
    <div class="cnd-login-box">

        {{-- Logo/Header --}}
        <div class="cnd-logo-wrap">
            <div class="cnd-logo-icon">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="9.5" stroke="#CDDCFF" stroke-width="1.6"/>
                    <path d="M8 12.5l2.7 2.7L16.5 9" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h1 class="cnd-title">Cendekia</h1>
            <p class="cnd-subtitle">Platform Pembelajaran Digital</p>
        </div>

        {{-- Login Card --}}
        <div class="cnd-card">
            <div class="cnd-card-heading">
                <h2>Masuk</h2>
                <p>Gunakan NIM, NIP, atau email Anda</p>
            </div>

            @if (session('status'))
                <div class="cnd-alert cnd-alert-success">{{ session('status') }}</div>
            @endif

            {{-- Login Errors --}}
            @if ($errors->any())
                <div class="cnd-alert cnd-alert-error">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p>{{ $errors->first('credential') ?: ($errors->first('password') ?: 'Login gagal, periksa kembali kredensial Anda') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Combined Credential Input (NIM/NIP/Email) --}}
                <div class="cnd-field">
                    <label for="credential" class="cnd-label">NIM / NIP / Email</label>
                    <div class="cnd-input-wrap">
                        <div class="cnd-input-icon">
                            <svg width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <input
                            id="credential"
                            type="text"
                            name="credential"
                            value="{{ old('credential') }}"
                            placeholder="20241001, 19790000001, atau nama@example.com"
                            required
                            class="cnd-input"
                            autocomplete="username"
                        />
                    </div>
                    @error('credential')
                        <p class="cnd-error-text">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Input --}}
                <div class="cnd-field">
                    <div style="display: flex !important; align-items: center; justify-content: space-between; margin-bottom: 9px;">
                        <label for="password" class="cnd-label" style="margin-bottom: 0;">Password</label>
                        <a href="{{ route('password.request') }}" class="cnd-forgot-link">Lupa Password?</a>
                    </div>
                    <div class="cnd-input-wrap" x-data="{ show: false }">
                        <div class="cnd-input-icon">
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
                            class="cnd-input has-toggle"
                            autocomplete="current-password"
                        />
                        <button type="button" @click="show = !show" class="cnd-toggle-pw">
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

</x-guest-layout>
