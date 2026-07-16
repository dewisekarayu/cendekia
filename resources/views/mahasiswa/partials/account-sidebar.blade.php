{{--
    PROFILE + IDENTITY CARD (merged)
    Gabungan kartu foto profil (editable) dan ringkasan akun (identitas),
    dipakai di halaman Profil & Setting untuk dosen maupun mahasiswa.
    Expects $user (default: auth()->user()), optional $announcements.
--}}
@php
    $user = $user ?? auth()->user();
    $announcements = $announcements ?? collect();
    $isDosen = method_exists($user, 'hasRole') ? $user->hasRole('dosen') : ($user->role ?? null) === 'dosen';
    $idLabel = $isDosen ? 'NIP' : 'NIM';
    $roleLabel = $isDosen ? 'Dosen' : 'Mahasiswa';
@endphp

<div class="lg:col-span-1 space-y-4">
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm transition-colors duration-200">
        <div class="border-t-4 border-[#321270] dark:border-purple-600 px-5 pt-6 pb-5">
            <div class="space-y-1 text-center">
                {{-- AVATAR EDITABLE --}}
                <form action="{{ route($isDosen ? 'dosen.profil.foto' : 'mahasiswa.setting.foto') }}" method="POST" enctype="multipart/form-data" id="formFoto">
                    @csrf
                    @method('PUT')
                    <div class="relative mx-auto mb-3 h-24 w-24 group/avatar">
                        <img id="avatarPreview"
                             src="{{ $user->foto ? asset('storage/'.$user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=321270&color=fff&bold=true' }}"
                             alt="Foto Profil"
                             class="h-24 w-24 rounded-full object-cover border-4 border-white dark:border-slate-800 shadow-md ring-1 ring-slate-100 dark:ring-slate-700">

                        <label for="fotoInput"
                               class="absolute inset-0 flex items-center justify-center rounded-full bg-black/50 opacity-0 group-hover/avatar:opacity-100 transition-opacity cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828H9V13z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 19h14" />
                            </svg>
                        </label>
                        <input type="file" id="fotoInput" name="foto" accept="image/*" class="hidden" onchange="previewFoto(event)">
                    </div>

                    <div id="fotoActions" class="hidden justify-center gap-2 mb-3">
                        <button type="submit"
                                class="rounded-full bg-[#321270] dark:bg-purple-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-[#321270]/90 dark:hover:bg-purple-800 transition">
                            Simpan Foto
                        </button>
                        <button type="button" onclick="batalFoto()"
                                class="rounded-full border border-gray-200 dark:border-slate-700 px-4 py-1.5 text-xs font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-800 transition">
                            Batal
                        </button>
                    </div>
                    @error('foto')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </form>

                <h3 class="text-lg font-bold text-slate-800 dark:text-white">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-[#321270]/10 dark:bg-purple-950/40 px-3 py-1 text-xs font-semibold text-[#321270] dark:text-purple-300">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    {{ $roleLabel }}
                </div>
            </div>
        </div>

        {{-- RINGKASAN AKUN --}}
        <div class="border-t border-gray-100 dark:border-slate-700 px-5 py-5">
            <h3 class="mb-4 text-sm font-bold text-slate-800 dark:text-white">Ringkasan Akun</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-500 dark:text-slate-400">Status</span>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-1 text-[11px] font-bold text-emerald-700 dark:text-emerald-400">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Aktif
                    </span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-500 dark:text-slate-400">{{ $idLabel }}</span>
                    <span class="font-bold text-slate-800 dark:text-white">{{ $user->nip_nim ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-500 dark:text-slate-400">Program Studi</span>
                    <span class="font-bold text-slate-800 dark:text-white">{{ $user->programStudi?->nama_prodi ?? '-' }}</span>
                </div>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-500 dark:text-slate-400">Bergabung Sejak</span>
                    <span class="font-bold text-slate-800 dark:text-white">{{ $user->created_at?->translatedFormat('M Y') ?? '-' }}</span>
                </div>
            </div>
        </div>

        {{-- PENGUMUMAN --}}
        @if($announcements->isNotEmpty())
            <div class="border-t border-gray-100 dark:border-slate-700 px-5 py-5 space-y-2">
                <p class="text-xs font-semibold text-gray-500 dark:text-slate-400">Pengumuman Terbaru</p>
                @foreach($announcements as $a)
                    <div class="rounded-lg bg-gray-50 dark:bg-slate-900/50 px-3 py-2 text-xs text-gray-700 dark:text-slate-300 truncate">
                        {{ $a->judul }}
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<script>
(function () {
    const fileInput = document.getElementById('fotoInput');
    const preview = document.getElementById('avatarPreview');
    const actions = document.getElementById('fotoActions');
    const originalSrc = preview.src;

    // Tampilkan preview + tombol Simpan/Batal begitu user pilih file baru
    window.previewFoto = function (event) {
        const file = event.target.files && event.target.files[0];
        if (!file) return;
        preview.src = URL.createObjectURL(file);
        actions.classList.remove('hidden');
        actions.classList.add('flex');
    };

    // Batalkan pilihan foto, kembalikan ke foto lama
    window.batalFoto = function () {
        fileInput.value = '';
        preview.src = originalSrc;
        actions.classList.add('hidden');
        actions.classList.remove('flex');
    };

    // Submit form dibiarkan jalan normal (reload halaman) — controller
    // updateFoto() sekarang redirect balik ke halaman profil, bukan JSON.
})();
</script>