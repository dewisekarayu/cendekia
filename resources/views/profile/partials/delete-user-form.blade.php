<section>
    <header class="mb-4">
        <h2 class="text-lg font-bold text-red-700">
            Hapus Akun
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            Setelah akun dihapus, semua data terkait akan hilang permanen.
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="text-sm font-medium text-white bg-red-600 hover:bg-red-700 px-5 py-2 rounded-lg transition"
    >Hapus Akun</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-gray-800">
                Yakin ingin menghapus akun?
            </h2>

            <p class="mt-1 text-sm text-gray-500">
                Masukkan password untuk konfirmasi penghapusan akun secara permanen.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Password" class="sr-only" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Password"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition">
                    Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>