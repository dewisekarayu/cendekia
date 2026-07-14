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
        position: fixed !important;
        top: 0; left: 0; right: 0; bottom: 0;
        min-height: 100vh;
        width: 100vw;
        z-index: 99999;
        display: flex !important;
        align-items: center;
        justify-content: center;
        background: linear-gradient(160deg, var(--cnd-bg-1) 0%, var(--cnd-bg-2) 55%, #DCE6FF 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        box-sizing: border-box;
        overflow-y: auto;
        padding: 24px 16px;
    }

    .cnd-login-box{ 
        width: 100%; 
        max-width: 420px; 
        margin: auto;
    }

    .cnd-logo-wrap{ text-align: center; margin-bottom: 28px; }
    .cnd-logo-icon{
        display: inline-flex !important;
        align-items: center; justify-content: center;
        width: 68px; height: 68px;
        background: linear-gradient(135deg, var(--cnd-primary) 0%, var(--cnd-primary-dark) 100%);
        border-radius: 20px;
        box-shadow: 0 12px 30px rgba(0,43,107,.2);
        margin-bottom: 16px;
    }
    .cnd-logo-icon svg {
        filter: drop-shadow(0 2px 4px rgba(0,0,0,.1));
    }
    .cnd-title{
        font-size: 34px; font-weight: 800; margin: 0 0 4px 0;
        color: var(--cnd-primary);
        letter-spacing: -1px;
        line-height: 1.2;
    }
    .cnd-subtitle{ color: #64748b; font-size: 13.5px; margin: 0; font-weight: 500; }

    .cnd-card{
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.7);
        border-radius: 24px;
        padding: 32px 28px;
        box-shadow: 0 20px 40px rgba(0,43,107,.06);
        box-sizing: border-box;
    }

    .cnd-card-heading{ margin-bottom: 24px; }
    .cnd-card-heading h2{ font-size: 22px; font-weight: 700; color: var(--cnd-primary); margin: 0 0 4px 0; }
    .cnd-card-heading p{ color: #64748b; font-size: 13.5px; margin: 0; }

    .cnd-alert{
        margin-bottom: 20px; padding: 12px 16px;
        border-radius: 12px; font-size: 13.5px;
        line-height: 1.4;
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
        align-items: center;
        gap: 10px;
    }
    .cnd-alert-error svg{ flex-shrink: 0; }

    .cnd-field{ margin-bottom: 18px; text-align: left; }
    .cnd-label{
        display: block; font-size: 13px; font-weight: 600;
        color: #374151; margin-bottom: 8px;
    }
    .cnd-input-wrap{ position: relative; width: 100%; }
    .cnd-input-icon{
        position: absolute; left: 16px; top: 50%;
        transform: translateY(-50%);
        display: flex !important; align-items: center;
        pointer-events: none; color: #94A3B8;
    }
    .cnd-input{
        width: 100%;
        box-sizing: border-box;
        padding: 12px 16px 12px 46px;
        background: #fff;
        border: 1.5px solid #E2E8F0;
        border-radius: 12px;
        color: #1F2937;
        font-size: 14px;
        transition: all .2s ease;
    }
    .cnd-input.has-toggle{ padding-right: 46px; }
    .cnd-input::placeholder{ color: #9CA3AF; }
    .cnd-input:focus{
        outline: none;
        border-color: var(--cnd-primary);
        box-shadow: 0 0 0 4px rgba(0,43,107,.1);
    }
    .cnd-error-text{ color: #DC2626; font-size: 12px; margin: 6px 0 0 2px; font-weight: 500; }

    .cnd-toggle-pw{
        position: absolute; right: 12px; top: 50%;
        transform: translateY(-50%);
        display: flex !important; align-items: center; justify-content: center;
        background: none; border: none; color: #94A3B8;
        cursor: pointer; transition: .2s; padding: 6px;
        border-radius: 50%;
    }
    .cnd-toggle-pw:hover{ color: var(--cnd-primary); background: rgba(0,43,107,0.05); }

    .cnd-remember{
        display: flex !important;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        user-select: none;
    }
    .cnd-remember input{
        width: 16px; height: 16px; border-radius: 4px;
        accent-color: var(--cnd-primary); cursor: pointer;
        border: 1.5px solid #CBD5E1;
        margin: 0;
    }
    .cnd-remember label{ font-size: 13px; color: #4B5563; cursor: pointer; margin: 0; font-weight: 500; }

    .cnd-submit{
        width: 100%;
        padding: 13px 16px;
        background: linear-gradient(90deg, var(--cnd-primary) 0%, var(--cnd-primary-dark) 100%);
        color: #fff; font-weight: 700; font-size: 14.5px;
        border: none; border-radius: 12px;
        cursor: pointer;
        box-shadow: 0 8px 20px rgba(0,43,107,.2);
        transition: all .15s ease;
    }
    .cnd-submit:hover{ filter: brightness(1.08); box-shadow: 0 10px 24px rgba(0,43,107,.25); }
    .cnd-submit:active{ transform: scale(.98); }

    .cnd-forgot-link{
        color: var(--cnd-primary);
        text-decoration: none;
        font-weight: 600;
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
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
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

            @if ($errors->any())
                <div class="cnd-alert cnd-alert-error">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p style="margin: 0;">{{ $errors->first('credential') ?: ($errors->first('password') ?: 'Login gagal, periksa kembali kredensial Anda') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Combined Credential Input --}}
                <div class="cnd-field">
                    <label for="credential" class="cnd-label">NIM / NIP / Email</label>
                    <div class="cnd-input-wrap">
                        <div class="cnd-input-icon">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <input
                            id="credential"
                            type="text"
                            name="credential"
                            value="{{ old('credential') }}"
                            placeholder="Ketik NIM, NIP, atau Email..."
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
                    <div style="display: flex !important; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                        <label for="password" class="cnd-label" style="margin-bottom: 0;">Password</label>
                        <a href="{{ route('password.request') }}" class="cnd-forgot-link">Lupa Password?</a>
                    </div>
                    <div class="cnd-input-wrap">
                        <div class="cnd-input-icon">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="cnd-input has-toggle"
                            autocomplete="current-password"
                        />
                        <button type="button" id="togglePassword" class="cnd-toggle-pw">
                            <svg id="eyeOpen" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="eyeClose" style="display: none;" width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleBtn = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClose = document.getElementById('eyeClose');

        if(toggleBtn && passwordInput) {
            toggleBtn.addEventListener('click', function() {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeOpen.style.display = 'none';
                    eyeClose.style.display = 'block';
                } else {
                    passwordInput.type = 'password';
                    eyeOpen.style.display = 'block';
                    eyeClose.style.display = 'none';
                }
            });
        }
    });
</script>

</x-guest-layout>