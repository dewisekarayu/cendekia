<x-guest-layout>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">

        @csrf

        <!-- NIP/NIM -->

        <div>

            <x-input-label for="nip_nim" :value="__('NIP / NIM')" class="text-sm font-medium text-gray-700" />

            <div class="relative mt-1">

                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />

                    </svg>

                </div>

                <x-text-input id="nip_nim" class="block w-full pl-9" type="text" name="nip_nim" :value="old('nip_nim')"

                    placeholder="Masukkan NIP atau NIM" required autofocus autocomplete="username" />

            </div>

            <x-input-error :messages="$errors->get('nip_nim')" class="mt-2" />

        </div>

        <!-- Password -->

        <div class="mt-4">

            <div class="flex items-center justify-between">

                <x-input-label for="password" :value="__('Kata Sandi')" class="text-sm font-medium text-gray-700" />

            </div>

            <div class="relative mt-1" x-data="{ show: false }">

                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">

                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />

                    </svg>

                </div>

                <input :type="show ? 'text' : 'password'" id="password"

                    class="block w-full pl-9 pr-20 border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"

                    name="password" required autocomplete="current-password">

                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs font-medium text-blue-900">

                    <span x-text="show ? 'Sembunyikan' : 'Tampilkan'"></span>

                </button>

            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />

        </div>

        <!-- Remember Me -->

        <div class="block mt-4">

            <label for="remember_me" class="inline-flex items-center">

                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-900 shadow-sm focus:ring-blue-500" name="remember">

                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya di perangkat ini') }}</span>

            </label>

        </div>

        <div class="flex items-center justify-end mt-2">

            @if (Route::has('password.request'))

                <a class="text-sm text-blue-900 hover:underline" href="{{ route('password.request') }}">

                    {{ __('Lupa Password?') }}

                </a>

            @endif

        </div>

        <!-- Submit -->

        <div class="mt-6">

            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-blue-900 hover:bg-blue-800 text-white font-medium py-2.5 rounded-md transition">

                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">

                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l4-4m0 0l-4-4m4 4H3m8 8a9 9 0 100-18 9 9 0 000 18z" />

                </svg>

                {{ __('Masuk') }}

            </button>

        </div>

        <!-- Footer Info -->

        <p class="mt-6 text-center text-xs text-gray-400">

            {{ now()->translatedFormat('d F Y') }}

        </p>

    </form>

</x-guest-layout>
