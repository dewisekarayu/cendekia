@extends('layouts.portal')

@section('title', 'Tugas & Proyek')
@section('activeMenu', 'Kelas Saya')

@section('content')

    <!-- Class Header Banner -->
    <div class="bg-[#321270] dark:bg-gradient-to-r dark:from-indigo-950 dark:to-purple-900 rounded-xl px-8 py-6 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xs font-semibold bg-white/15 text-white px-2.5 py-1 rounded">{{ $kelas->mataKuliah->kode_mk ?? '-' }}</span>
                <span class="text-xs text-white/80">Semester {{ $kelas->semester->nama_semester ?? '-' }}</span>
            </div>
            <h1 class="text-xl font-bold text-white">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</h1>
            <p class="text-sm text-blue-200 dark:text-purple-200 mt-1">
                {{ $kelas->hari }}, {{ $kelas->jam_mulai }} - {{ $kelas->jam_selesai }} • {{ $kelas->ruangan }}
            </p>
        </div>
    </div>

    <x-flash-message />

    {{-- TABS --}}
    @php
        $tabLinks = [
            'Beranda'      => ['url' => route('dosen.kelas-detail', $kelas->id), 'active' => request()->routeIs('dosen.kelas-detail')],
            'Absensi'      => ['url' => route('dosen.absensi.index', $kelas->id),  'active' => request()->routeIs('dosen.absensi.*')],
            'Materi'       => ['url' => route('dosen.kelas-materi', $kelas->id), 'active' => request()->routeIs('dosen.kelas-materi')],
            'Tugas'        => ['url' => route('dosen.kelas-tugas', $kelas->id),  'active' => request()->routeIs('dosen.kelas-tugas')],
            'Forum'        => ['url' => route('dosen.kelas-forum', $kelas->id),  'active' => request()->routeIs('dosen.kelas-forum')],
            'Rekap Tugas'  => ['url' => route('dosen.kelas-tugas.rekap', $kelas->id), 'active' => request()->routeIs('dosen.kelas-tugas.rekap')],
        ];
    @endphp
    <div class="mb-5 flex items-center gap-1 overflow-x-auto rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-1.5 shadow-sm transition-colors duration-200">
        @foreach ($tabLinks as $label => $tab)
            <a href="{{ $tab['url'] }}"
            class="whitespace-nowrap rounded-xl px-4 py-2 text-xs font-bold transition
                {{ $tab['active']
                    ? 'bg-[#321270] dark:bg-purple-700 text-white shadow-sm shadow-purple-900/20'
                    : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-800 dark:hover:text-white' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 p-5 shadow-sm transition-colors duration-200">
            <p class="text-sm text-gray-500 dark:text-slate-400">Total Tugas</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $tugasList->count() }}</p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 p-5 shadow-sm transition-colors duration-200">
            <p class="text-sm text-gray-500 dark:text-slate-400">Perlu Dinilai</p>
            <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">
                {{ $perluDinilai ?? 0 }}
            </p>
        </div>
        <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 p-5 shadow-sm transition-colors duration-200">
            <p class="text-sm text-gray-500 dark:text-slate-400">Total Mahasiswa</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $kelas->mahasiswa->count() }}</p>
        </div>
    </div>

    <!-- Header + Filter + Create Assignment Button -->
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between mb-5 pb-4 border-b border-gray-100 dark:border-slate-700">
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Daftar Tugas</h2>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Kelola dan pantau tugas mahasiswa</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <label class="relative block">
                <span class="sr-only">Cari tugas</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input id="searchTugas" type="text" placeholder="Cari tugas..." class="w-full sm:w-52 pl-9 pr-3 py-2 text-sm border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-gray-700 dark:text-slate-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 outline-none">
            </label>

            {{-- Filter Status --}}
            <div id="filterStatusTugas" class="inline-flex items-center gap-1 rounded-lg border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-900 p-1">
                <button type="button" data-filter="semua" class="filter-status-btn is-active px-3 py-1.5 text-xs font-bold rounded-md transition">Semua</button>
                <button type="button" data-filter="open" class="filter-status-btn px-3 py-1.5 text-xs font-bold rounded-md transition">Terbuka</button>
                <button type="button" data-filter="closed" class="filter-status-btn px-3 py-1.5 text-xs font-bold rounded-md transition">Ditutup</button>
            </div>

            <button type="button" onclick="toggleModal('modalTugas')"
                    class="inline-flex items-center gap-2 bg-[#321270] dark:bg-[#6c2bd9] hover:bg-[#230c50] dark:hover:bg-[#5b21b6] text-white text-sm font-semibold px-[18px] py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Buat Tugas Baru
            </button>
        </div>
    </div>

    <!-- Assignments Table -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors duration-200">
        @if ($tugasList->isEmpty())
            <div class="p-10 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-gray-300 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 dark:text-slate-400 text-sm mb-4">Belum ada tugas untuk kelas ini.</p>
                <button type="button" onclick="toggleModal('modalTugas')"
                    class="inline-flex items-center gap-1.5 text-xs font-bold bg-[#321270] dark:bg-[#6c2bd9] text-white px-4 py-2 rounded-lg hover:bg-[#321270]/90 dark:hover:bg-[#5b21b6] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Buat Tugas Pertama
                </button>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 dark:text-slate-400 text-xs border-b border-gray-100 dark:border-slate-700">
                        <th class="px-5 py-3 font-medium">Tugas</th>
                        <th class="px-5 py-3 font-medium">
                            <button type="button" id="sortDeadlineBtn" class="inline-flex items-center gap-1 hover:text-gray-700 dark:hover:text-slate-200 transition">
                                Deadline
                                <svg id="sortDeadlineIcon" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </th>
                        <th class="px-5 py-3 font-medium">Pengumpulan</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tugasTableBody" class="divide-y divide-gray-100 dark:divide-slate-700/50">
                    @foreach ($tugasList as $tugas)
                        @php
                            $isClosed = $tugas->isTutup();
                            $submitted = $tugas->submitted_count ?? 0;
                            $total = $kelas->mahasiswa->count();
                            $persen = $total > 0 ? round(($submitted / $total) * 100) : 0;
                        @endphp
                        <tr class="tugas-row hover:bg-gray-50/50 dark:hover:bg-slate-900/20 transition duration-150"
                            data-status="{{ $isClosed ? 'closed' : 'open' }}"
                            data-deadline="{{ $tugas->deadline->timestamp }}"
                            data-search="{{ strtolower($tugas->judul . ' ' . ($tugas->instruksi ?? '') . ' ' . ($tugas->bobot_nilai ?? '')) }}">
                            <td class="px-5 py-3">
                                <p class="font-bold text-gray-800 dark:text-white">{{ $tugas->judul }}</p>
                                <p class="text-xs text-gray-400 dark:text-slate-500">Bobot: {{ $tugas->bobot_nilai }}%</p>
                            </td>
                            <td class="px-5 py-3">
                                <p class="text-gray-500 dark:text-slate-300">{{ $tugas->deadline->format('d M Y, H:i') }}</p>
                                <p class="text-[11px] font-semibold {{ $isClosed ? 'text-red-500 dark:text-red-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                    @if ($tugas->is_closed && !$tugas->deadline->isPast())
                                        Ditutup manual
                                    @elseif ($tugas->deadline->isPast())
                                        Berakhir {{ $tugas->deadline->diffForHumans() }}
                                    @else
                                        {{ $tugas->deadline->diffForHumans() }}
                                    @endif
                                </p>
                            </td>
                            <td class="px-5 py-3">
                                <p class="text-gray-600 dark:text-slate-300 mb-1">{{ $submitted }}/{{ $total }} terkumpul</p>
                                <div class="w-28 h-1.5 rounded-full bg-gray-100 dark:bg-slate-700 overflow-hidden">
                                    <div class="h-full bg-[#321270] dark:bg-purple-500 rounded-full" style="width: {{ $persen }}%"></div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                @if ($isClosed)
                                    <span class="inline-block text-[10px] font-semibold text-rose-700 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/40 px-2.5 py-1 rounded-md">Ditutup</span>
                                @else
                                    <span class="inline-block text-[10px] font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2.5 py-1 rounded-md">Terbuka</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-2">
                                    @if ($isClosed)
                                        <button type="button"
                                                onclick="openModalBukaTugas({{ $tugas->id }}, '{{ addslashes($tugas->judul) }}', '{{ $tugas->deadline->translatedFormat('d M Y, H:i') }}')"
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-300 bg-emerald-50 dark:bg-emerald-950/40 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 px-3 py-1.5 rounded-md transition duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Buka Kembali
                                        </button>
                                    @endif

                                    <a href="{{ route('dosen.tugas.submissions', [$kelas->id, $tugas->id]) }}"
                                    class="inline-block text-xs font-semibold text-blue-900 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/40 hover:bg-blue-100 dark:hover:bg-blue-900/40 px-3 py-1.5 rounded-md transition duration-150">
                                        Lihat Pengumpulan
                                    </a>

                                    <div class="relative inline-block text-left">
                                        <button type="button" onclick="toggleDropdown('dropdownTugas{{ $tugas->id }}')"
                                                class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-slate-700 transition align-middle">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500 dark:text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>

                                        <div id="dropdownTugas{{ $tugas->id }}"
                                            class="dropdown-tugas hidden absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 border border-gray-100 dark:border-slate-700 rounded-xl shadow-lg z-20 overflow-hidden divide-y divide-gray-100 dark:divide-slate-700/60">

                                            <div class="py-1">
                                                @if ($isClosed)
                                                    <button type="button"
                                                            onclick="toggleDropdown('dropdownTugas{{ $tugas->id }}'); openModalBukaTugas({{ $tugas->id }}, '{{ addslashes($tugas->judul) }}', '{{ $tugas->deadline->translatedFormat('d M Y, H:i') }}')"
                                                            class="w-full text-left px-4 py-2 text-sm text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 transition flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        Buka Kembali
                                                    </button>
                                                @else
                                                    <form action="{{ route('dosen.kelas-tugas.tutup', [$kelas->id, $tugas->id]) }}" method="POST"
                                                        onsubmit="return confirm('Tutup pengumpulan tugas ini sekarang? Mahasiswa tidak akan dapat mengumpulkan jawaban baru sampai Anda membukanya kembali.');">
                                                        @csrf
                                                        <button type="submit"
                                                                class="w-full text-left px-4 py-2 text-sm text-amber-600 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-950/30 transition flex items-center gap-2">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                            </svg>
                                                            Tutup Pengumpulan
                                                        </button>
                                                    </form>
                                                @endif

                                                <button type="button" onclick="toggleDropdown('dropdownTugas{{ $tugas->id }}'); toggleModal('modalEditTugas{{ $tugas->id }}')"
                                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-700 transition flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit
                                                </button>
                                            </div>

                                            <div class="py-1">
                                                <form action="{{ route('dosen.kelas-tugas.destroy', [$kelas->id, $tugas->id]) }}" method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus tugas ini? Semua data pengumpulan dan lampiran terkait juga akan ikut terhapus dan tidak bisa dikembalikan.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div id="tugasNoResults" class="hidden px-5 py-10 text-center">
                <p class="text-sm font-semibold text-gray-500 dark:text-slate-400">Tidak ada tugas dengan status ini.</p>
            </div>
        @endif
    </div>

    {{-- ===== MODAL EDIT TUGAS (di luar tabel, aman dari HTML tidak valid) ===== --}}
    @foreach ($tugasList as $tugas)
        <div id="modalEditTugas{{ $tugas->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="toggleModal('modalEditTugas{{ $tugas->id }}')"></div>

            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white dark:bg-slate-800 w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden">

                    <div class="px-8 py-6 bg-gray-50/50 dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 flex items-center gap-4">
                        <div class="bg-[#321270] dark:bg-purple-700 p-2 rounded-lg text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#1e293b] dark:text-white">Edit Tugas</h3>
                    </div>

                    <form action="{{ route('dosen.kelas-tugas.update', [$kelas->id, $tugas->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="px-8 py-6 space-y-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Judul Tugas</label>
                                <input type="text" name="judul" value="{{ $tugas->judul }}" required maxlength="255"
                                    class="w-full px-4 py-2 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 focus:outline-none transition text-gray-800 dark:text-gray-100">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Deadline</label>
                                    <input type="datetime-local" name="deadline" value="{{ $tugas->deadline->format('Y-m-d\TH:i') }}" required
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 focus:outline-none transition text-gray-800 dark:text-gray-100">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Bobot Nilai (Poin)</label>
                                    <input type="number" name="poin" value="{{ $tugas->bobot_nilai }}" required min="1" max="100"
                                        class="w-full px-4 py-2 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 focus:outline-none transition text-gray-800 dark:text-gray-100">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Instruksi Tugas</label>
                                <textarea name="instruksi" rows="5" required maxlength="5000"
                                <textarea name="instruksi" rows="5" required maxlength="5000" placeholder="Tuliskan detail instruksi pengerjaan tugas di sini..."
                                    class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 focus:outline-none resize-none">{{ $tugas->instruksi }}</textarea>
                            </div>
                            
                            <div class="space-y-2" x-data="{ linkInput: '{{ $tugas->link_lampiran }}', previewUrl() { 
                                if(!this.linkInput) return null;
                                let ytMatch = this.linkInput.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/);
                                if(ytMatch) return { type: 'youtube', id: ytMatch[1] };
                                return { type: 'link', url: this.linkInput };
                            } }">
                                <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Tautan Eksternal (YouTube, Drive, dll) (Opsional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                    </div>
                                    <input type="url" name="link_lampiran" x-model="linkInput" placeholder="https://..."
                                        class="pl-10 w-full px-4 py-3 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 focus:outline-none">
                                </div>
                                
                                <template x-if="previewUrl()">
                                    <div class="mt-3 border border-gray-200 dark:border-slate-700 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-800/50">
                                        <template x-if="previewUrl().type === 'youtube'">
                                            <iframe :src="'https://www.youtube.com/embed/' + previewUrl().id" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-64 object-cover"></iframe>
                                        </template>
                                        <template x-if="previewUrl().type === 'link'">
                                            <div class="p-4 flex items-center gap-3">
                                                <div class="bg-blue-100 dark:bg-blue-900/40 p-2.5 rounded-lg text-blue-600 dark:text-blue-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </div>
                                                <div class="overflow-hidden">
                                                    <p class="text-sm font-bold text-gray-800 dark:text-slate-200 truncate" x-text="previewUrl().url"></p>
                                                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Tautan Eksternal Terlampir</p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700 dark:text-slate-300">File Lampiran Saat Ini</label>
                                <div class="space-y-2">
                                    @forelse($tugas->files as $file)
                                        <div class="flex items-center justify-between p-2 bg-slate-50 dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700">
                                            <span class="text-xs font-semibold truncate max-w-[220px] text-gray-700 dark:text-slate-300">{{ $file->nama_asli }}</span>
                                            <label class="inline-flex items-center gap-1 text-xs text-red-600 cursor-pointer">
                                                <input type="checkbox" name="hapus_files[]" value="{{ $file->id }}" class="rounded text-red-600 focus:ring-red-500">
                                                Hapus
                                            </label>
                                        </div>
                                    @empty
                                        <p class="text-xs italic text-gray-400">Tidak ada lampiran saat ini.</p>
                                    @endforelse
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Tambah Lampiran Baru (Opsional)</label>
                                <input type="file" name="template[]" multiple
                                    accept=".pdf,.doc,.docx,.zip,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png"
                                    class="w-full text-sm text-gray-500 dark:text-gray-400">
                            </div>
                        </div>

                        <div class="bg-gray-50/50 dark:bg-slate-900 px-8 py-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                            <button type="button" onclick="toggleModal('modalEditTugas{{ $tugas->id }}')" class="px-6 py-2.5 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 font-semibold hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#321270] dark:bg-[#6c2bd9] text-white font-semibold hover:bg-opacity-90 dark:hover:bg-[#5b21b6] transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    {{-- ===== MODAL TAMBAH TUGAS ===== --}}
    <div id="modalTugas" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="toggleModal('modalTugas')"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-slate-800 w-full max-w-3xl rounded-2xl shadow-xl overflow-hidden">

                <div class="px-8 py-6 bg-gray-50/50 dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 flex items-center gap-4">
                    <div class="bg-[#321270] dark:bg-purple-700 p-2 rounded-lg text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#1e293b] dark:text-white">Informasi Tugas</h3>
                </div>

                <form action="{{ route('dosen.kelas-tugas.store', $kelas->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-8 py-6 space-y-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Judul Tugas</label>
                            <input type="text" name="judul" value="{{ old('judul') }}" required maxlength="255" placeholder="Contoh: Implementasi Stack dan Queue" class="w-full px-4 py-2 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 focus:outline-none transition text-gray-800 dark:text-gray-100">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Deadline</label>
                                <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" required class="w-full px-4 py-2 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 focus:outline-none transition text-gray-800 dark:text-gray-100">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Bobot Nilai (Poin)</label>
                                <input type="number" name="poin" value="{{ old('poin', 100) }}" required min="1" max="100" class="w-full px-4 py-2 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 focus:outline-none transition text-gray-800 dark:text-gray-100">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                                <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Instruksi Tugas</label>
                                <div class="flex flex-wrap items-center gap-2">
                                    <input type="number" id="ai_jumlah_soal" class="w-16 text-xs border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-md px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-purple-500" placeholder="Jml" value="5" min="1" max="50" title="Jumlah Soal">
                                    <select id="ai_tipe_soal" class="text-xs border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-md px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                        <option value="Soal Essay">Soal Essay</option>
                                        <option value="Soal Pilihan Ganda">Soal Pilihan Ganda</option>
                                        <option value="Soal Pilihan Ganda Kompleks (Lebih dari 1 Jawaban Benar)">Soal Pilihan Ganda Kompleks</option>
                                        <option value="Campuran (Pilihan Ganda & Essay)">Campuran</option>
                                    </select>
                                    <button type="button" onclick="generateAiInstruksi(this)" class="text-xs flex items-center gap-1 bg-purple-100 text-purple-700 hover:bg-purple-200 px-2.5 py-1.5 rounded-md font-bold transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        Instruksi AI
                                    </button>
                                    <button type="button" onclick="generatePdfFromText(this, 'tugas')" class="text-xs flex items-center gap-1 bg-red-100 text-red-700 hover:bg-red-200 px-2.5 py-1.5 rounded-md font-bold transition-all shadow-sm">
                                        📄 Jadikan PDF
                                    </button>
                                </div>
                            </div>
                            <textarea name="instruksi" rows="5" required maxlength="5000" placeholder="Tuliskan detail instruksi pengerjaan tugas di sini..." class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 focus:outline-none resize-none">{{ old('instruksi') }}</textarea>
                        </div>
                        
                        <div class="space-y-2" x-data="{ linkInput: '{{ old('link_lampiran') }}', previewUrl() { 
                            if(!this.linkInput) return null;
                            let ytMatch = this.linkInput.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/);
                            if(ytMatch) return { type: 'youtube', id: ytMatch[1] };
                            return { type: 'link', url: this.linkInput };
                        } }">
                            <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Tautan Eksternal (YouTube, Drive, dll) (Opsional)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                    </svg>
                                </div>
                                <input type="url" name="link_lampiran" x-model="linkInput" placeholder="https://..."
                                    class="pl-10 w-full px-4 py-3 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 focus:outline-none">
                            </div>
                            
                            <template x-if="previewUrl()">
                                <div class="mt-3 border border-gray-200 dark:border-slate-700 rounded-xl overflow-hidden bg-slate-50 dark:bg-slate-800/50">
                                    <template x-if="previewUrl().type === 'youtube'">
                                        <iframe :src="'https://www.youtube.com/embed/' + previewUrl().id" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-64 object-cover"></iframe>
                                    </template>
                                    <template x-if="previewUrl().type === 'link'">
                                        <div class="p-4 flex items-center gap-3">
                                            <div class="bg-blue-100 dark:bg-blue-900/40 p-2.5 rounded-lg text-blue-600 dark:text-blue-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                </svg>
                                            </div>
                                            <div class="overflow-hidden">
                                                <p class="text-sm font-bold text-gray-800 dark:text-slate-200 truncate" x-text="previewUrl().url"></p>
                                                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Tautan Eksternal Terlampir</p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-slate-300">File Lampiran Template (Opsional)</label>
                            <label class="block w-full border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-2xl p-8 flex flex-col items-center justify-center text-center hover:border-blue-400 dark:hover:border-purple-500 transition cursor-pointer bg-slate-50/50 dark:bg-slate-900">
                                <div class="bg-blue-50 dark:bg-purple-950/40 p-4 rounded-xl mb-3 text-[#321270] dark:text-purple-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-gray-800 dark:text-slate-200">Klik atau seret file ke sini (bisa lebih dari 1)</p>
                                <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">PDF, DOCX, PPT, ZIP, JPG, PNG (Maksimal 25MB/file)</p>
                                <input type="file" id="fileInput" name="template[]" multiple
                                    accept=".pdf,.doc,.docx,.zip,.ppt,.pptx,.xls,.xlsx,.jpg,.jpeg,.png"
                                    class="hidden" onchange="updateTugasFileName(this)">
                                <ul id="tugasFileNameDisplay" class="text-sm text-emerald-600 dark:text-emerald-400 font-semibold mt-2 hidden space-y-0.5"></ul>
                            </label>
                        </div>
                    </div>

                    <div class="bg-gray-50/50 dark:bg-slate-900 px-8 py-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                        <button type="button" onclick="toggleModal('modalTugas')" class="px-6 py-2.5 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 font-semibold hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#321270] dark:bg-[#6c2bd9] text-white font-semibold flex items-center gap-2 hover:bg-opacity-90 dark:hover:bg-[#5b21b6] transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                            Publikasikan Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ===== MODAL BUKA KEMBALI TUGAS ===== --}}
    <div id="modalBukaTugas" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="closeModalBukaTugas()"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-slate-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden border border-slate-100 dark:border-slate-700">

                <div class="px-6 py-5 bg-gradient-to-r from-emerald-600 to-teal-700 text-white flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold leading-tight">Buka Kembali Tugas</h3>
                            <p class="text-xs text-emerald-100 mt-0.5">Tentukan batas waktu baru untuk pengumpulan</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeModalBukaTugas()" class="p-1 rounded-lg hover:bg-white/10 text-white/80 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form id="formBukaTugas" method="POST" action="">
                    @csrf
                    <div class="p-6 space-y-4">
                        {{-- Info box tugas --}}
                        <div class="p-3.5 bg-slate-50 dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-700">
                            <p class="text-xs text-slate-400">Judul Tugas</p>
                            <p id="bukaTugasJudul" class="text-sm font-bold text-slate-800 dark:text-white mt-0.5">-</p>
                            <p class="text-xs text-slate-500 mt-1">
                                Batas waktu sebelumnya: <span id="bukaTugasDeadlineLama" class="font-semibold text-rose-500">-</span>
                            </p>
                        </div>

                        {{-- Input Deadline Baru --}}
                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300">
                                Batas Waktu Baru (Deadline) <span class="text-rose-500">*</span>
                            </label>
                            <input type="datetime-local" id="bukaTugasDeadlineInput" name="deadline" required
                                class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none transition text-sm font-medium text-slate-800 dark:text-slate-100">
                            <p class="text-[11px] text-slate-400">
                                Mahasiswa dapat mengumpulkan kembali tugas hingga tanggal dan jam yang Anda tentukan di atas.
                            </p>
                        </div>

                        {{-- Quick Presets --}}
                        <div class="space-y-1.5">
                            <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Pilihan Cepat Perpanjangan:</span>
                            <div class="flex flex-wrap gap-1.5">
                                <button type="button" onclick="setPresetDeadline(1)" class="px-2.5 py-1 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:border-emerald-300 hover:text-emerald-700 transition font-medium text-slate-600 dark:text-slate-300">+1 Hari</button>
                                <button type="button" onclick="setPresetDeadline(3)" class="px-2.5 py-1 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:border-emerald-300 hover:text-emerald-700 transition font-medium text-slate-600 dark:text-slate-300">+3 Hari</button>
                                <button type="button" onclick="setPresetDeadline(7)" class="px-2.5 py-1 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:border-emerald-300 hover:text-emerald-700 transition font-medium text-slate-600 dark:text-slate-300">+1 Minggu</button>
                                <button type="button" onclick="setPresetDeadline(14)" class="px-2.5 py-1 text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 hover:border-emerald-300 hover:text-emerald-700 transition font-medium text-slate-600 dark:text-slate-300">+2 Minggu</button>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-900 px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-2.5">
                        <button type="button" onclick="closeModalBukaTugas()" class="px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-700 text-xs font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition shadow-sm flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Buka Kembali Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Fungsi umum untuk membuka/tutup modal apa saja
        // Buka modal secara otomatis jika ada error validasi saat submit form tambah
        @if($errors->any() && old('batas_waktu') !== null)
            document.addEventListener('DOMContentLoaded', function() {
                toggleModal('modalTugas');
            });
        @endif

        function toggleModal(modalID) {
            const modal = document.getElementById(modalID);
            if (modal) {
                modal.classList.toggle("hidden");
                document.body.classList.toggle("overflow-hidden");
            }
        }

        function openModalBukaTugas(tugasId, judul, deadlineLama) {
            const modal = document.getElementById('modalBukaTugas');
            const form = document.getElementById('formBukaTugas');
            const judulEl = document.getElementById('bukaTugasJudul');
            const dlEl = document.getElementById('bukaTugasDeadlineLama');
            const input = document.getElementById('bukaTugasDeadlineInput');

            const url = "{{ route('dosen.kelas-tugas.buka', [$kelas->id, ':tugasId']) }}".replace(':tugasId', tugasId);
            form.action = url;
            judulEl.textContent = judul;
            dlEl.textContent = deadlineLama;

            const now = new Date();
            const minIso = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
            input.min = minIso;

            setPresetDeadline(3);

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModalBukaTugas() {
            const modal = document.getElementById('modalBukaTugas');
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        function setPresetDeadline(days) {
            const target = new Date();
            target.setDate(target.getDate() + days);
            target.setHours(23, 59, 0, 0);
            const iso = new Date(target.getTime() - target.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
            const input = document.getElementById('bukaTugasDeadlineInput');
            if (input) input.value = iso;
        }

        // Menutup modal ketika user mengklik area background luar (overlay)
        window.onclick = function(event) {
            document.querySelectorAll('[id^="modalEditTugas"], #modalTugas, #modalBukaTugas').forEach(modal => {
                if (event.target === modal && !modal.classList.contains('hidden')) {
                    if (modal.id === 'modalBukaTugas') {
                        closeModalBukaTugas();
                    } else {
                        toggleModal(modal.id);
                    }
                }
            });

            // Tutup dropdown titik tiga kalau klik di luar dropdown/tombolnya
            if (!event.target.closest('.dropdown-tugas') && !event.target.closest('[onclick^="toggleDropdown"]')) {
                document.querySelectorAll('.dropdown-tugas').forEach(el => el.classList.add('hidden'));
            }
        }

        // Fungsi menampilkan daftar nama file yang diunggah
        function updateTugasFileName(input) {
            const display = document.getElementById('tugasFileNameDisplay');
            if (!display) return;

            display.innerHTML = '';

            if (input.files.length > 0) {
                const fragment = document.createDocumentFragment();

                Array.from(input.files).forEach(file => {
                    const li = document.createElement('li');
                    li.textContent = file.name;
                    fragment.appendChild(li);
                });

                display.appendChild(fragment);
                display.classList.remove('hidden');
            } else {
                display.classList.add('hidden');
            }
        }

        // Buka/tutup dropdown titik tiga, tutup dropdown lain yang lagi terbuka
        function toggleDropdown(id) {
            document.querySelectorAll('.dropdown-tugas').forEach(el => {
                if (el.id !== id) el.classList.add('hidden');
            });
            const dropdown = document.getElementById(id);
            if (dropdown) dropdown.classList.toggle('hidden');
        }

        // ===== Fitur Filter Status (Semua / Terbuka / Ditutup) =====
        document.addEventListener('DOMContentLoaded', function () {
            const filterButtons = document.querySelectorAll('.filter-status-btn');
            const rows = document.querySelectorAll('.tugas-row');
            const noResults = document.getElementById('tugasNoResults');
            const searchInput = document.getElementById('searchTugas');
            let activeStatus = 'semua';

            function applyFilter() {
                const query = (searchInput?.value || '').toLowerCase().trim();
                let visibleCount = 0;

                rows.forEach(row => {
                    const matchesStatus = activeStatus === 'semua' || row.getAttribute('data-status') === activeStatus;
                    const matchesSearch = !query || (row.getAttribute('data-search') || '').includes(query);
                    const isVisible = matchesStatus && matchesSearch;

                    row.classList.toggle('hidden', !isVisible);
                    if (isVisible) visibleCount++;
                });

                if (noResults) {
                    noResults.classList.toggle('hidden', visibleCount !== 0 || rows.length === 0);
                }
            }

            filterButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    filterButtons.forEach(b => b.classList.remove('is-active'));
                    this.classList.add('is-active');
                    activeStatus = this.getAttribute('data-filter');
                    applyFilter();
                });
            });

            searchInput?.addEventListener('input', applyFilter);

            // ===== Fitur Urutkan berdasarkan Deadline =====
            const sortBtn = document.getElementById('sortDeadlineBtn');
            const sortIcon = document.getElementById('sortDeadlineIcon');
            const tbody = document.getElementById('tugasTableBody');
            let ascending = true;

            if (sortBtn && tbody) {
                sortBtn.addEventListener('click', function () {
                    const rowsArray = Array.from(tbody.querySelectorAll('.tugas-row'));
                    rowsArray.sort((a, b) => {
                        const da = parseInt(a.getAttribute('data-deadline'), 10);
                        const db = parseInt(b.getAttribute('data-deadline'), 10);
                        return ascending ? da - db : db - da;
                    });
                    rowsArray.forEach(row => tbody.appendChild(row));
                    ascending = !ascending;
                    sortIcon.style.transform = ascending ? 'rotate(0deg)' : 'rotate(180deg)';
                });
            }
        });

        async function generateAiInstruksi(btn) {
            const form = btn.closest('form') || btn.closest('.space-y-6');
            const judul = form.querySelector('input[name="judul"]').value;
            const poin = form.querySelector('input[name="poin"]')?.value || '100';
            const instruksiInput = form.querySelector('textarea[name="instruksi"]');
            
            if (!judul) {
                alert('Silakan isi "Judul Tugas" terlebih dahulu agar AI memahami tugasnya!');
                return;
            }
            
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Menyusun instruksi...';
            btn.disabled = true;
            
            try {
                const response = await fetch('{{ route("dosen.ai-assistant.generate-instruksi") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ judul, poin })
                });
                
                const data = await response.json();
                if (data.success) {
                    instruksiInput.value = data.instruksi;
                } else {
                    alert('Gagal: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                alert('Terjadi kesalahan jaringan.');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }

        async function generatePdfFromText(btn, type) {
            const form = btn.closest('form') || btn.closest('.space-y-6') || btn.closest('.space-y-5');
            const judul = form.querySelector('input[name="judul"]').value;
            const contentInput = form.querySelector(type === 'tugas' ? 'textarea[name="instruksi"]' : 'textarea[name="deskripsi"]');
            const fileInput = form.querySelector('input[type="file"]');
            
            if (!judul) {
                alert('Silakan isi Judul terlebih dahulu agar AI mengetahui topik soal yang akan dibuat!');
                return;
            }
            
            const inputJumlah = form.querySelector('#ai_jumlah_soal');
            const jumlahSoal = inputJumlah ? inputJumlah.value : '5';
            const dropdown = form.querySelector('#ai_tipe_soal');
            const tipeSoal = dropdown ? dropdown.value : 'Soal Essay';
            const combinedTipeSoal = jumlahSoal + ' ' + tipeSoal;
            
            const originalText = btn.innerHTML;
            btn.innerHTML = 'AI Menyusun...';
            btn.disabled = true;
            
            try {
                // Panggil endpoint baru yang akan generate soal menggunakan AI lalu mereturn PDF
                const response = await fetch('{{ route("dosen.ai-assistant.generate-ai-pdf-soal") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ title: judul, tipe_soal: combinedTipeSoal })
                });
                
                if (!response.ok) {
                    let errMessage = 'Gagal membuat PDF';
                    try {
                        const errData = await response.json();
                        if (errData.error) errMessage = errData.error;
                    } catch (e) {}
                    throw new Error(errMessage);
                }
                
                const blob = await response.blob();
                const fileName = judul.toLowerCase().replace(/[^a-z0-9]/g, '-') + '-soal.pdf';
                const file = new File([blob], fileName, { type: "application/pdf" });
                
                const dt = new DataTransfer();
                if (fileInput.files.length > 0) {
                    for (let i = 0; i < fileInput.files.length; i++) {
                        dt.items.add(fileInput.files[i]);
                    }
                }
                dt.items.add(file);
                fileInput.files = dt.files;
                
                // Trigger change event to update the UI
                const event = new Event('change');
                fileInput.dispatchEvent(event);
                
                // Isi textarea dengan instruksi otomatis jika kosong
                if (!contentInput.value) {
                    contentInput.value = "Silakan unduh file PDF terlampir untuk melihat daftar soal. Kerjakan dengan teliti dan kumpulkan jawaban Anda sesuai dengan batas waktu yang ditentukan.";
                }
                
                showToast('Berhasil! AI telah membuatkan soal dalam bentuk PDF dan melampirkannya ke form.', 'success');
            } catch (error) {
                showToast('Terjadi kesalahan: ' + error.message, 'danger');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
    </script>

    <style>
        .filter-status-btn {
            color: rgb(100 116 139);
        }
        .filter-status-btn.is-active {
            background-color: #321270;
            color: #fff;
        }
        .dark .filter-status-btn {
            color: rgb(148 163 184);
        }
        .dark .filter-status-btn.is-active {
            background-color: #6c2bd9;
            color: #fff;
        }
        #sortDeadlineIcon {
            transition: transform 0.15s ease;
        }
    </style>

@endsection