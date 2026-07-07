<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-gray-800">
            Informasi Profil
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Perbarui nama dan email akun kamu.
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">NIP / NIM</label>
            <input type="text" value="{{ $user->nip_nim }}" disabled
                class="w-full border-gray-200 bg-gray-50 text-gray-500 rounded-lg text-sm cursor-not-allowed">
            <p class="text-xs text-gray-400 mt-1">NIP/NIM tidak dapat diubah sendiri. Hubungi Admin jika ada kesalahan data.</p>
        </div>

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                Simpan
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-600"
                >Tersimpan.</p>
            @endif
        </div>
    </form>
</section>