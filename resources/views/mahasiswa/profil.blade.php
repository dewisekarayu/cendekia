@extends('layouts.portal')
@section('title', 'Profil')
@section('activeMenu', 'Profile')
@section('content')

@php
    $user = auth()->user();
    $isEn = app()->getLocale() === 'en';
@endphp

{{-- STATS STRIP --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    <div class="group rounded-2xl bg-white border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5" style="background:#002B6B;"></div>
        <p class="text-[28px] font-black text-[#002B6B] leading-none">{{ $totalKelas }}</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Kelas Aktif</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-[#002B6B]/30"></div>
    </div>
    <div class="group rounded-2xl bg-white border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5 bg-emerald-500"></div>
        <p class="text-[28px] font-black text-emerald-600 leading-none">{{ $rataRata ? number_format($rataRata, 1) : '–' }}</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Rata-rata Nilai</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-emerald-400/40"></div>
    </div>
    <div class="group rounded-2xl bg-white border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5 bg-violet-500"></div>
        <p class="text-[28px] font-black text-violet-600 leading-none">{{ $nilaiAkhirList->count() }}</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">MK Dinilai</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-violet-400/40"></div>
    </div>
    <div class="group rounded-2xl bg-white border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5 bg-amber-500"></div>
        <p class="text-[28px] font-black text-amber-500 leading-none">{{ $announcements->count() }}</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Pengumuman</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-amber-400/40"></div>
    </div>
</div>

{{-- MAIN GRID --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- LEFT: FORMS --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- TABS --}}
        <div x-data="{ tab: 'profile' }">
            <div class="flex gap-1 rounded-2xl bg-white border border-slate-100 shadow-sm p-1.5 mb-5">
                <button @click="tab='profile'"
                    :class="tab==='profile' ? 'bg-[#002B6B] text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Profil
                </button>
                <button @click="tab='password'"
                    :class="tab==='password' ? 'bg-amber-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                    class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Keamanan
                </button>
            </div>

            {{-- PROFILE TAB --}}
            <div x-show="tab==='profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="rounded-2xl bg-white border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-50 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-[#002B6B] shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Informasi Profil</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Informasi pribadi Anda di platform (ReadOnly)</p>
                        </div>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Nama Lengkap</label>
                                <div class="relative">
                                    <input type="text" value="{{ $user->name }}" disabled
                                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-400 bg-gray-100 cursor-not-allowed">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">NIM</label>
                                <div class="relative">
                                    <input type="text" value="{{ $user->nip_nim ?? '–' }}" disabled
                                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-400 bg-gray-100 cursor-not-allowed pr-10">
                                    <span class="absolute inset-y-0 right-3 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </span>
                                </div>
                                <p class="mt-1.5 text-[11px] text-gray-400">NIM tidak dapat diubah sendiri</p>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Alamat Email</label>
                                <div class="relative">
                                    <input type="email" value="{{ $user->email }}" disabled
                                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-400 bg-gray-100 cursor-not-allowed">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Program Studi</label>
                                <div class="relative">
                                    <input type="text" value="{{ $user->programStudi?->nama_prodi ?? 'Belum ditentukan' }}" disabled
                                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-400 bg-gray-100 cursor-not-allowed pr-10">
                                    <span class="absolute inset-y-0 right-3 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 pt-5 border-t border-gray-50">
                            <p class="text-xs text-gray-400">Hubungi Akademik/Admin jika terdapat kesalahan data pada nama, email, NIM, atau Program Studi.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PASSWORD / KEAMANAN TAB --}}
            <div x-show="tab==='password'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="rounded-2xl bg-white border border-slate-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-50 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Ubah Kata Sandi</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Pastikan gunakan kata sandi yang kuat dan mudah kamu ingat</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('mahasiswa.setting.password') }}" class="px-6 py-6">
                        @csrf @method('PATCH')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Kata Sandi Saat Ini <span class="text-red-400 normal-case">*</span></label>
                                <input type="password" name="current_password"
                                    class="w-full rounded-xl border px-4 py-3 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10 transition-all @error('current_password', 'updatePassword') border-red-300 bg-red-50 @enderror"
                                    style="border-color: {{ $errors->updatePassword->has('current_password') ? '' : '#e5e7eb' }};"
                                    placeholder="Masukkan kata sandi saat ini" required>
                                @error('current_password', 'updatePassword')<p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Kata Sandi Baru <span class="text-red-400 normal-case">*</span></label>
                                <input type="password" name="password"
                                    class="w-full rounded-xl border px-4 py-3 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10 transition-all @error('password', 'updatePassword') border-red-300 bg-red-50 @enderror"
                                    style="border-color: {{ $errors->updatePassword->has('password') ? '' : '#e5e7eb' }};"
                                    placeholder="Minimal 8 karakter" required>
                                @error('password', 'updatePassword')<p class="mt-1.5 text-xs text-red-500 flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Konfirmasi Kata Sandi <span class="text-red-400 normal-case">*</span></label>
                                <input type="password" name="password_confirmation"
                                    class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm text-gray-800 bg-gray-50 focus:outline-none focus:bg-white focus:border-amber-500 focus:ring-2 focus:ring-amber-500/10 transition-all"
                                    placeholder="Ulangi kata sandi baru" required>
                            </div>
                        </div>
                        <div class="mt-6 pt-5 border-t border-gray-50 flex items-center justify-between">
                            <p class="text-xs text-gray-400">Kamu akan tetap login setelah kata sandi diperbarui</p>
                            <button type="submit"
                                class="inline-flex items-center gap-2 rounded-xl bg-[#002B6B] px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#003380] active:scale-95 transition-all duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                                Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    {{-- RIGHT: INFO CARD --}}
    <div class="lg:col-span-1 space-y-5">

        {{-- FOTO PROFIL CARD --}}
        <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-6 text-center">
            <form action="{{ route('mahasiswa.setting.foto') }}" method="POST" enctype="multipart/form-data" id="formFoto">
                @csrf
                @method('PUT')
                <div class="relative mx-auto mb-3 h-24 w-24 group/avatar">
                    <img id="avatarPreview"
                        src="{{ $user->foto ? asset('storage/'.$user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=002B6B&color=fff&bold=true' }}"
                        alt="Foto Profil"
                        class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-md ring-1 ring-slate-100 mx-auto">

                    <label for="fotoInput"
                        class="absolute inset-0 flex items-center justify-center rounded-full bg-black/50 opacity-0 group-hover/avatar:opacity-100 transition-opacity cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828H9V13z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 19h14" />
                        </svg>
                    </label>
                    <input type="file" id="fotoInput" name="foto" accept="image/*" class="hidden" onchange="previewFoto(event)">
                </div>

                <p class="text-sm font-bold text-slate-800">{{ $user->name }}</p>
                <p class="text-xs text-gray-500 mb-3">{{ $user->email }}</p>

                <div id="fotoActions" class="hidden justify-center gap-2">
                    <button type="submit"
                            class="rounded-full bg-[#002B6B] px-4 py-1.5 text-xs font-semibold text-white hover:bg-[#002B6B]/90 transition">
                        Simpan Foto
                    </button>
                    <button type="button" onclick="batalFoto()"
                            class="rounded-full border border-gray-200 px-4 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                        Batal
                    </button>
                </div>
                @error('foto')
                    <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </form>
        </div>

        {{-- Ringkasan Akun --}}
        <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-6">
              <h3 class="text-sm font-bold text-slate-800 mb-4">Ringkasan Akun</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400">Status</span>
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Aktif
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400">NIM</span>
                    <span class="text-xs font-semibold text-slate-700">{{ $user->nip_nim ?? '–' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400">Program Studi</span>
                    <span class="text-xs font-semibold text-slate-700 text-right">{{ $user->programStudi?->nama_prodi ?? '–' }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-400">Bergabung Sejak</span>
                    <span class="text-xs font-semibold text-slate-700">{{ $user->created_at?->translatedFormat('M Y') ?? '–' }}</span>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function previewFoto(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('avatarPreview').src = e.target.result;
            document.getElementById('fotoActions').classList.remove('hidden');
            document.getElementById('fotoActions').classList.add('flex');
        };
        reader.readAsDataURL(file);
    }

    function batalFoto() {
        document.getElementById('fotoInput').value = '';
        document.getElementById('fotoActions').classList.add('hidden');
        document.getElementById('fotoActions').classList.remove('flex');
        document.getElementById('avatarPreview').src = "{{ $user->foto ? asset('storage/'.$user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=002B6B&color=fff&bold=true' }}";
    }
</script>

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: "{{ app()->getLocale() === 'en' ? 'Success' : 'Berhasil' }}",
            text: "{{ session('success') }}",
            confirmButtonColor: '#002B6B',
        });
    });
</script>
@endif

@endsection
