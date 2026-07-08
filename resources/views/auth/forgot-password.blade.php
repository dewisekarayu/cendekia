<x-guest-layout>

    {{-- Back link --}}
    <div class="anim-in mb-6">
        <a href="{{ route('login') }}"
           class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 hover:text-[#002B6B] transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Login
        </a>
    </div>

    {{-- Icon --}}
    <div class="anim-in mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#002B6B]/10 text-[#002B6B]">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
    </div>

    {{-- Heading --}}
    <div class="anim-in-1 mb-7">
        <h2 class="text-2xl font-black text-slate-900">Lupa Password?</h2>
        <p class="mt-2 text-sm leading-relaxed text-gray-500">
            Masukkan alamat email yang terdaftar. Kami akan mengirimkan tautan untuk membuat password baru dalam beberapa menit.
        </p>
    </div>

    {{-- Status message --}}
    @if (session('status'))
        <div class="anim-in mb-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3.5 text-sm text-emerald-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="font-semibold">Email terkirim!</p>
                <p class="mt-0.5 text-xs text-emerald-600">{{ session('status') }}</p>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        @if ($errors->any())
            <div class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-4 w-4 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- Email --}}
        <div class="anim-in-2">
            <label for="email" class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1.5">
                Alamat Email
            </label>
            <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="nama@cendekia.ac.id"
                    class="w-full rounded-xl border border-gray-200 bg-gray-50 py-3 pl-10 pr-4 text-sm text-gray-800 placeholder-gray-400
                           focus:border-[#002B6B] focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#002B6B]/10 transition
                           @error('email') border-red-300 bg-red-50 @enderror">
            </div>
        </div>

        {{-- Submit --}}
        <div class="anim-in-3 pt-1">
            <button
                type="submit"
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-[#002B6B] px-6 py-3.5 text-sm font-bold text-white
                       shadow-lg shadow-blue-900/25 hover:bg-blue-800 active:scale-[0.98] transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Kirim Link Reset Password
            </button>
        </div>
    </form>

</x-guest-layout>
