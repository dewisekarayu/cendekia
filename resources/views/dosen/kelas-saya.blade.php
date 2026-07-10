@extends('layouts.portal')

@section('title', 'My Classes')
@section('activeMenu', 'Kelas Saya')

@section('content')

<!-- Page Header -->
<div class="bg-[#321270] rounded-xl px-8 py-6 relative overflow-hidden mb-8">
    <div class="mb-6">
        <h1 class="text-xl font-bold text-white">My Classes</h1>
        <p class="text-sm text-white/80 mt-1">Manage and access all your teaching classes this semester.</p>
    </div>
</div>

<!-- Filter Row -->
<div class="flex flex-col sm:flex-row gap-3 mb-6">
    <select class="text-sm border-gray-200 rounded-lg focus:border-[#321270] focus:ring-[#321270] text-gray-600 py-2.5">
        <option>Semester Ganjil 2025/2026</option>
    </select>
    <form id="kelas-search-form" class="relative flex-1 max-w-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input id="kelas-search-input" type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kelas..." autocomplete="off" class="w-full pl-9 text-sm border-gray-200 rounded-lg focus:border-[#321270] focus:ring-[#321270] py-2.5">
    </form>
</div>

<!-- Class Grid -->
@if ($kelasList->isEmpty())
<div class="bg-white rounded-xl border border-gray-100 p-10 text-center shadow-sm">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
    </svg>
    <p class="text-gray-500 text-sm">
        @if ($search !== '')
            Tidak ada kelas yang cocok dengan pencarian "{{ $search }}".
        @else
            Belum ada kelas yang diampu. Data kelas akan muncul di sini setelah ditambahkan oleh Admin.
        @endif
    </p>
</div>
@else
<div id="kelas-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @php
    $topColors = ['bg-blue-900', 'bg-amber-400', 'bg-emerald-500', 'bg-indigo-500'];
    $tagColors = ['text-blue-900 bg-blue-50', 'text-amber-700 bg-amber-50', 'text-emerald-700 bg-emerald-50', 'text-indigo-700 bg-indigo-50'];
    @endphp

    @foreach ($kelasList as $i => $kelas)
    <div class="kelas-card bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden" data-search="{{ strtolower($kelas->mataKuliah->nama_mk ?? '') }} {{ strtolower($kelas->mataKuliah->kode_mk ?? '') }} {{ strtolower($kelas->kode_kelas ?? '') }} {{ strtolower($kelas->mataKuliah->programStudi->nama_prodi ?? '') }}">
        <div class="h-1.5 {{ $topColors[$i % count($topColors)] }}"></div>
        <div class="p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="inline-block text-[10px] font-semibold tracking-wide px-2 py-0.5 rounded {{ $tagColors[$i % count($tagColors)] }}">
                    {{ $kelas->mataKuliah->programStudi->nama_prodi ?? 'Umum' }}
                </span>
                <span class="inline-flex items-center gap-1 text-[10px] font-medium text-emerald-600">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    {{ $kelas->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            <h3 class="font-semibold text-gray-800">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</h3>
            <p class="text-xs text-gray-400 mt-0.5">{{ $kelas->mataKuliah->kode_mk ?? '-' }} • {{ $kelas->mataKuliah->sks ?? 0 }} SKS</p>

            <div class="mt-3 space-y-1.5 text-xs text-gray-500">
                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $kelas->hari }}, {{ $kelas->jam_mulai }} - {{ $kelas->jam_selesai }}
                </div>
                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $kelas->ruangan }}
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <div class="flex items-center gap-1.5 text-xs text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z" />
                    </svg>
                    {{ $kelas->mahasiswa->count() }} students enrolled
                </div>
            </div>

            <a href="{{ route('dosen.kelas-detail', $kelas->id) }}" class="mt-4 block text-center bg-[#321270] hover:bg-[#250d54] text-white text-sm font-medium py-2 rounded-lg transition">
                Manage Class
            </a>
        </div>
    </div>
    @endforeach
</div>
<div id="kelas-empty-state" class="hidden bg-white rounded-xl border border-gray-100 p-10 text-center shadow-sm">
    <p class="text-gray-500 text-sm">Tidak ada kelas yang cocok dengan pencarian.</p>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('kelas-search-form');
    const input = document.getElementById('kelas-search-input');
    const grid = document.getElementById('kelas-grid');
    const emptyState = document.getElementById('kelas-empty-state');
    const cards = Array.from(document.querySelectorAll('.kelas-card'));

    if (!input || !cards.length) return;

    if (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
        });
    }

    const filterCards = () => {
        const query = input.value.toLowerCase().trim();
        let visibleCount = 0;

        cards.forEach((card) => {
            const searchText = (card.dataset.search || '').toLowerCase();
            const isMatch = !query || searchText.includes(query);
            card.classList.toggle('hidden', !isMatch);

            if (isMatch) {
                visibleCount++;
            }
        });

        if (grid) {
            grid.classList.toggle('hidden', visibleCount === 0);
        }

        if (emptyState) {
            emptyState.classList.toggle('hidden', visibleCount !== 0);
            const message = emptyState.querySelector('p');
            if (message) {
                message.textContent = query
                    ? `Tidak ada kelas yang cocok dengan pencarian "${query}".`
                    : 'Tidak ada kelas yang cocok dengan pencarian.';
            }
        }
    };

    input.addEventListener('input', filterCards);
    filterCards();
});
</script>

@endsection