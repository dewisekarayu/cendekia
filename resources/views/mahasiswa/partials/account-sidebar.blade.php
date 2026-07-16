{{--
    Shared right-column identity card — used on Profil and Setting pages
    so both share the same visual identity. Expects $user (default: auth()->user()).
--}}
@php
    $user = $user ?? auth()->user();
@endphp

<div class="space-y-4">
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm text-center">
        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-[#002B6B] text-2xl font-bold text-white">
            {{ strtoupper(substr($user->name ?? '?', 0, 1)) }}
        </div>
        <h3 class="mt-4 text-base font-bold text-slate-800">{{ $user->name }}</h3>
        <p class="text-sm text-gray-500">{{ $user->email }}</p>
    </div>

    <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm">
        <h3 class="mb-4 text-sm font-bold text-slate-800">Ringkasan Akun</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500">Status</span>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-[11px] font-bold text-emerald-700">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Aktif
                </span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500">NIM</span>
                <span class="font-bold text-slate-800">{{ $user->nip_nim ?? '-' }}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500">Program Studi</span>
                <span class="font-bold text-slate-800">{{ $user->programStudi?->nama_prodi ?? '-' }}</span>
            </div>
            <div class="flex items-center justify-between text-xs">
                <span class="text-gray-500">Bergabung Sejak</span>
                <span class="font-bold text-slate-800">{{ $user->created_at?->translatedFormat('M Y') ?? '-' }}</span>
            </div>
        </div>
    </div>
</div>