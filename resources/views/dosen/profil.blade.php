@extends('layouts.portal')
@section('title', 'Profil')
@section('activeMenu', 'Profil')
@section('content')

{{-- STATS STRIP --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    <div class="group rounded-2xl bg-white border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5" style="background:#321270;"></div>
        <p class="text-[28px] font-black text-[#321270] leading-none">{{ $totalKelas }}</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Kelas Diampu</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-[#321270]/30"></div>
    </div>
    <div class="group rounded-2xl bg-white border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5 bg-emerald-500"></div>
        <p class="text-[28px] font-black text-emerald-600 leading-none">Aktif</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Status Akun</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-emerald-400/40"></div>
    </div>
    <div class="group rounded-2xl bg-white border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5 bg-amber-500"></div>
        <p class="text-[28px] font-black text-amber-500 leading-none">{{ $announcements->count() }}</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Pengumuman</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-amber-400/40"></div>
    </div>
    <div class="group rounded-2xl bg-white border border-slate-100 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5 bg-violet-500"></div>
        <p class="text-lg font-black text-violet-600 leading-tight">{{ $user->created_at->translatedFormat('d M Y') }}</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 uppercase tracking-wide">Bergabung</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-violet-400/40"></div>
    </div>
</div>

<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Profil Dosen</h1>
            <p class="mt-1 text-sm text-gray-500">Kelola informasi pribadi dan akun Anda</p>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700 font-medium">
            {{ session('success') }}
        </div>
    @endif

    {{-- MAIN CONTENT --}}
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- PROFILE CARD --}}
        <div class="lg:col-span-1">
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-t-4 border-[#321270] px-5 pt-6 pb-5">
                    <div class="space-y-1 text-center">
                        {{-- AVATAR EDITABLE --}}
                        <form action="{{ route('dosen.profil.foto') }}" method="POST" enctype="multipart/form-data" id="formFoto">
                            @csrf
                            @method('PUT')
                            <div class="relative mx-auto mb-3 h-24 w-24 group/avatar">
                                <img id="avatarPreview"
                                     src="{{ $user->foto ? asset('storage/'.$user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=321270&color=fff&bold=true' }}"
                                     alt="Foto Profil"
                                     class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-md ring-1 ring-slate-100">

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
                                        class="rounded-full bg-[#321270] px-4 py-1.5 text-xs font-semibold text-white hover:bg-[#321270]/90 transition">
                                    Simpan Foto
                                </button>
                                <button type="button" onclick="batalFoto()"
                                        class="rounded-full border border-gray-200 px-4 py-1.5 text-xs font-semibold text-gray-600 hover:bg-gray-50 transition">
                                    Batal
                                </button>
                            </div>
                            @error('foto')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </form>

                        <h3 class="text-lg font-bold text-slate-800">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-[#321270]/10 px-3 py-1 text-xs font-semibold text-[#321270]">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Dosen
                        </div>
                    </div>

                    @if($announcements->isNotEmpty())
                        <div class="mt-4 space-y-2 border-t border-gray-100 pt-4">
                            <p class="text-xs font-semibold text-gray-500">Pengumuman Terbaru</p>
                            @foreach($announcements as $a)
                                <div class="rounded-lg bg-gray-50 px-3 py-2 text-xs text-gray-700 truncate">
                                    {{ $a->judul }}
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- FORM CARD WITH TABS --}}
        <div class="lg:col-span-2">
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                {{-- TAB SWITCH --}}
                <div class="flex border-b border-gray-100 px-5 pt-4 gap-1">
                    <button type="button" onclick="switchTab('informasi')" id="tabBtnInformasi"
                            class="tab-btn relative px-4 py-2.5 text-sm font-semibold rounded-t-lg transition-colors text-[#321270]">
                        Informasi Pribadi
                        <span id="tabIndicatorInformasi" class="absolute left-0 right-0 -bottom-px h-0.5 bg-[#321270] rounded-full"></span>
                    </button>
                    <button type="button" onclick="switchTab('keamanan')" id="tabBtnKeamanan"
                            class="tab-btn relative px-4 py-2.5 text-sm font-semibold rounded-t-lg transition-colors text-gray-400">
                        Keamanan Akun
                        <span id="tabIndicatorKeamanan" class="absolute left-0 right-0 -bottom-px h-0.5 bg-[#321270] rounded-full hidden"></span>
                    </button>
                </div>

                {{-- TAB: INFORMASI PRIBADI --}}
                <div id="tabInformasi" class="tab-panel">
                    <form action="{{ route('dosen.profil.update') }}" method="POST" class="space-y-5 p-5">
                        @csrf
                        @method('PUT')

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Nama lengkap"
                                       class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                                @error('name')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Email"
                                       class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                                @error('email')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="tel" name="telepon" value="{{ old('telepon', $user->telepon) }}" placeholder="Nomor telepon"
                                       class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                                @error('telepon')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Nomor Induk Dosen (NID)</label>
                                <input type="text" name="nip_nim" value="{{ old('nip_nim', $user->nip_nim) }}" placeholder="NID"
                                       class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                                @error('nip_nim')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="reset"
                                    class="rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <button type="submit" class="rounded-lg bg-[#321270] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#321270]/90 transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- TAB: KEAMANAN AKUN --}}
                <div id="tabKeamanan" class="tab-panel hidden">
                    <form action="{{ route('dosen.profil.password') }}" method="POST" class="space-y-5 p-5">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-2">Password Saat Ini</label>
                            <input type="password" name="current_password" placeholder="Masukkan password saat ini"
                                   class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                            @error('current_password')
                                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Password Baru</label>
                                <input type="password" name="password" placeholder="Masukkan password baru"
                                       class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                                @error('password')
                                    <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" placeholder="Konfirmasi password baru"
                                       class="w-full rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:border-[#321270] focus:outline-none focus:ring-2 focus:ring-[#321270]/10 transition">
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="reset"
                                    class="rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                Batal
                            </button>
                            <button type="submit" class="rounded-lg bg-[#321270] px-6 py-2.5 text-sm font-semibold text-white hover:bg-[#321270]/90 transition">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        const panels = ['informasi', 'keamanan'];
        panels.forEach(function (name) {
            const cap = name.charAt(0).toUpperCase() + name.slice(1);
            const panel = document.getElementById('tab' + cap);
            const btn = document.getElementById('tabBtn' + cap);
            const indicator = document.getElementById('tabIndicator' + cap);

            if (name === tab) {
                panel.classList.remove('hidden');
                btn.classList.add('text-[#321270]');
                btn.classList.remove('text-gray-400');
                indicator.classList.remove('hidden');
            } else {
                panel.classList.add('hidden');
                btn.classList.remove('text-[#321270]');
                btn.classList.add('text-gray-400');
                indicator.classList.add('hidden');
            }
        });
    }

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
        document.getElementById('avatarPreview').src = "{{ $user->foto ? asset('storage/'.$user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=321270&color=fff&bold=true' }}";
    }
</script>

@endsection