@extends('layouts.portal')

@section('title', $kelas->mataKuliah->nama_mk ?? 'Materi Kelas')
@section('activeMenu', 'Kelas Saya')

@section('content')

    <div class="bg-gradient-to-r from-[#321270] to-[#4c19a0] dark:from-indigo-950 dark:to-purple-900 rounded-xl px-8 py-6 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 shadow-sm">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xs font-semibold bg-white/15 text-white px-2.5 py-1 rounded">{{ $kelas->mataKuliah->kode_mk ?? '-' }}</span>
                <span class="text-xs text-white/80">Semester {{ $kelas->semester->nama_semester ?? '-' }}</span>
            </div>
            <h1 class="text-xl font-bold text-white">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</h1>
            <p class="text-sm text-white/80 mt-1">
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
            'Penilaian' => ['url' => route('dosen.kelas-tugas.rekap', $kelas->id), 'active' => request()->routeIs('dosen.kelas-tugas.rekap')],
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 p-6 shadow-sm transition-colors duration-200">

                {{-- HEADER + TOOLBAR (rapi & konsisten, aman di kolom sempit) --}}
                <div class="mb-5">
                    <div class="mb-3">
                        <h2 class="text-base font-bold text-gray-800 dark:text-white">Materi</h2>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Cari materi berdasarkan judul, kategori, atau pertemuan.</p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2.5">
                        {{-- Search --}}
                        <label class="relative flex-1 min-w-[160px]">
                            <span class="sr-only">Cari materi</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m1.85-5.15a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input id="searchMateri" type="text" placeholder="Cari materi..."
                                class="w-full h-10 pl-9 pr-3 text-sm border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-gray-700 dark:text-slate-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 outline-none transition">
                        </label>

                        {{-- Filter Pertemuan (Custom Dropdown) --}}
                        <div class="relative w-full xs:w-auto sm:w-44">
                            <button type="button" onclick="toggleMateriDropdown('dropdownPertemuan')"
                                class="w-full h-10 flex items-center justify-between px-3 text-sm border border-gray-200 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-900 text-gray-700 dark:text-slate-300 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 outline-none transition">
                                <span id="pertemuanLabel" class="truncate">Semua Pertemuan</span>
                                <svg class="fill-current h-3.5 w-3.5 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                </svg>
                            </button>

                            <div id="dropdownPertemuan"
                                class="materi-dropdown hidden absolute right-0 sm:left-0 mt-2 w-full min-w-[176px] max-h-60 overflow-y-auto bg-white dark:bg-slate-800 border border-gray-150 dark:border-slate-700 rounded-xl shadow-xl z-20 divide-y divide-gray-100 dark:divide-slate-700/50">
                                <button type="button" onclick="pilihPertemuan('', 'Semua Pertemuan')"
                                    class="w-full text-left px-4 py-2.5 text-xs font-semibold text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                    Semua Pertemuan
                                </button>
                                @for ($i = 1; $i <= 16; $i++)
                                    <button type="button" onclick="pilihPertemuan('{{ $i }}', 'Pertemuan {{ $i }}')"
                                        class="w-full text-left px-4 py-2.5 text-xs font-semibold text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                                        Pertemuan {{ $i }}
                                    </button>
                                @endfor
                            </div>

                            {{-- Simpan value terpilih di sini, dipakai filterMateri() --}}
                            <input type="hidden" id="filterPertemuan" value="">
                        </div>

                        {{-- Tombol Aksi --}}
                        <button onclick="toggleMateriModal('modalMateri')"
                            class="h-10 shrink-0 text-xs font-bold bg-[#321270] dark:bg-[#6c2bd9] text-white px-4 rounded-lg hover:bg-[#321270]/90 dark:hover:bg-[#5b21b6] transition flex items-center justify-center gap-1.5 whitespace-nowrap w-full xs:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Tambah Materi
                        </button>
                    </div>
                </div>

                @if ($materiList->isEmpty())
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-300 dark:text-slate-600 mb-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                        <div class="text-sm font-semibold text-gray-500 dark:text-slate-400">Belum ada materi untuk kelas ini.</div>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">Silakan klik tombol "Tambah Materi" di atas untuk menambahkan.</p>
                    </div>
                @else
                    <div id="materiListWrapper" class="space-y-4">
                        @foreach($materiList as $m)
                            <div class="materi-card p-5 border border-gray-100 dark:border-slate-700/60 bg-slate-50/30 dark:bg-slate-900/10 rounded-xl hover:shadow-md hover:border-purple-200 dark:hover:border-purple-900/50 transition-all duration-200"
                                data-search="{{ strtolower($m->judul . ' ' . ($m->deskripsi ?? '') . ' ' . ($m->kategori ?? '') . ' pertemuan ' . $m->pertemuan_ke) }}"
                                data-pertemuan="{{ $m->pertemuan_ke }}">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">

                                    {{-- Info Kiri: Detail Pertemuan & Judul --}}
                                    <div class="min-w-0 flex-1 space-y-2">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-100 dark:border-purple-900/30">
                                                Pertemuan {{ $m->pertemuan_ke }}
                                            </span>
                                            @if(isset($m->kategori))
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                                    {{ $m->kategori }}
                                                </span>
                                            @endif
                                        </div>

                                        <h3 class="text-sm font-bold text-gray-800 dark:text-white leading-snug">
                                            {{ $m->judul }}
                                        </h3>

                                        @if($m->deskripsi)
                                            <p class="text-xs text-gray-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                                {{ $m->deskripsi }}
                                            </p>
                                        @endif
                                    </div>

                                    {{-- Bagian Kanan: List File & Tombol Aksi --}}
                                    <div class="flex items-center justify-between md:justify-end gap-4 border-t md:border-t-0 pt-3 md:pt-0 border-gray-100 dark:border-slate-800 flex-shrink-0">

                                        {{-- List Files --}}
                                        <div class="flex flex-col gap-1.5 min-w-[150px] max-w-[200px]">
                                            @forelse($m->files as $file)
                                                <a href="{{ route('dosen.materi.buka', [$kelas->id, $m->id, $file->id]) }}"
                                                class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1.5 bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-300 border border-gray-200 dark:border-slate-700 rounded-lg hover:border-purple-500 dark:hover:border-purple-500 hover:text-purple-700 dark:hover:text-purple-300 transition duration-150 shadow-sm"
                                                title="{{ $file->nama_asli }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-gray-400 group-hover:text-purple-500 flex-shrink-0">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                    </svg>
                                                    <span class="truncate text-[11px] font-semibold">{{ $file->nama_asli ?? 'Unduh File' }}</span>
                                                </a>
                                            @empty
                                                <span class="text-xs text-gray-400 dark:text-slate-500 italic text-center md:text-right">Tidak ada lampiran file</span>
                                            @endforelse
                                        </div>

                                        {{-- Action Button (Dropdown Titik Tiga) --}}
                                        <div class="relative">
                                            <button type="button" onclick="toggleMateriDropdown('dropdownMateri{{ $m->id }}')"
                                                    class="p-2 rounded-lg text-gray-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-gray-800 dark:hover:text-white transition border border-transparent hover:border-gray-200 dark:hover:border-slate-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                                </svg>
                                            </button>

                                            <div id="dropdownMateri{{ $m->id }}"
                                                class="materi-dropdown hidden absolute right-0 mt-2 w-36 bg-white dark:bg-slate-800 border border-gray-150 dark:border-slate-700 rounded-xl shadow-xl z-20 overflow-hidden divide-y divide-gray-100 dark:divide-slate-700/50">
                                                <button type="button"
                                                        onclick="toggleMateriModal('modalEditMateri{{ $m->id }}'); toggleMateriDropdown('dropdownMateri{{ $m->id }}')"
                                                        class="w-full text-left px-4 py-2.5 text-xs font-semibold text-gray-600 dark:text-slate-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Edit Materi
                                                </button>

                                                <form action="{{ route('dosen.kelas-materi.hapus', [$kelas->id, $m->id]) }}" method="POST"
                                                    onsubmit="return confirm('Hapus materi &quot;{{ $m->judul }}&quot;? Semua file yang terlampir juga akan terhapus.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="w-full text-left px-4 py-2.5 text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/25 transition flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Hapus Materi
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div id="materiNoResults" class="hidden rounded-xl border border-dashed border-gray-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/30 px-4 py-8 text-center">
                            <p class="text-sm font-semibold text-gray-600 dark:text-slate-300">Tidak ada materi yang cocok dengan pencarian Anda.</p>
                            <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">Coba ubah kata kunci atau pilih pertemuan lain.</p>
                        </div>
                    </div>
                @endif
            </div>

            @foreach($materiList as $m)
                <div id="modalEditMateri{{ $m->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
                    <div class="fixed inset-0 bg-black bg-opacity-40 transition-opacity" onclick="toggleMateriModal('modalEditMateri{{ $m->id }}')"></div>

                    <div class="relative min-h-screen flex items-center justify-center p-4">
                        <div class="relative bg-white dark:bg-slate-800 w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden">

                            <div class="px-8 py-5 bg-gray-50/50 dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 flex items-center gap-3">
                                <div class="bg-[#1e293b] dark:bg-purple-650 p-2 rounded-lg text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-[#1e293b] dark:text-white">Edit Materi</h3>
                            </div>

                            <form action="{{ route('dosen.kelas-materi.update', [$kelas->id, $m->id]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="px-8 py-6 space-y-5">
                                    <div class="space-y-1.5">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Judul Materi</label>
                                        <input type="text" name="judul" value="{{ $m->judul }}" required maxlength="255"
                                            class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-purple-500 focus:outline-none transition text-gray-800 dark:text-gray-100 font-medium">
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Pertemuan Ke-</label>
                                        <div class="relative">
                                            <select name="pertemuan_ke" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-700 rounded-xl text-sm appearance-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-purple-500 focus:outline-none transition text-gray-500 dark:text-gray-300 bg-white dark:bg-slate-900 font-semibold">
                                                @for ($i = 1; $i <= 16; $i++)
                                                    <option value="{{ $i }}" @selected($m->pertemuan_ke === $i) class="dark:bg-slate-900">Minggu Ke-{{ $i }}</option>
                                                @endfor
                                            </select>
                                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Deskripsi Materi</label>
                                        <textarea name="deskripsi" rows="4" maxlength="5000"
                                            class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-purple-500 focus:outline-none resize-none transition text-gray-800 dark:text-gray-100">{{ $m->deskripsi }}</textarea>
                                    </div>

                                    <div class="space-y-1.5">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">File Lampiran Saat Ini</label>
                                        <div class="space-y-2">
                                            @forelse($m->files as $file)
                                                <div class="flex items-center justify-between p-2 bg-slate-50 dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-700">
                                                    <span class="text-xs font-semibold truncate max-w-[200px] text-gray-700 dark:text-slate-300">{{ $file->nama_asli }}</span>
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

                                    <div class="space-y-1.5 mt-3">
                                        <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Tambah File Baru (Optional)</label>
                                        <input type="file" name="file_materi[]" multiple class="w-full text-sm text-gray-500 dark:text-gray-400">
                                    </div>
                                </div>

                                <div class="bg-gray-50/70 dark:bg-slate-900 px-8 py-4 border-t border-gray-100 dark:border-slate-800 flex justify-end gap-3">
                                    <button type="button" onclick="toggleMateriModal('modalEditMateri{{ $m->id }}')" class="px-5 py-2 rounded-lg border border-gray-200 dark:border-slate-700 text-sm font-semibold text-gray-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-5 py-2 rounded-lg bg-[#0f172a] dark:bg-[#6c2bd9] text-sm font-semibold text-white hover:bg-opacity-90 dark:hover:bg-[#5b21b6] transition">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 p-5 shadow-sm transition-colors duration-200">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-2">Deskripsi Mata Kuliah</h3>
                <p class="text-sm text-gray-600 dark:text-slate-300">{{ $kelas->mataKuliah->deskripsi ?? 'Belum ada deskripsi untuk mata kuliah ini.' }}</p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 p-5 shadow-sm transition-colors duration-200">
                <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-4">Class Statistics</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-slate-400 mb-1">
                            <span>Total Mahasiswa</span>
                            <span class="font-bold text-gray-800 dark:text-white">{{ $kelas->mahasiswa->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL TAMBAH MATERI --}}
    <div id="modalMateri" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black bg-opacity-40 transition-opacity" onclick="toggleMateriModal('modalMateri')"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-slate-800 w-full max-w-3xl rounded-2xl shadow-xl overflow-hidden">

                <div class="px-8 py-5 bg-gray-50/50 dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 flex items-center gap-3">
                    <div class="bg-[#1e293b] dark:bg-purple-650 p-2 rounded-lg text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1e293b] dark:text-white">Informasi Materi</h3>
                </div>

                <form action="{{ route('dosen.kelas-materi.store', $kelas->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-8 py-6 space-y-5">

                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Judul Materi</label>
                            <input type="text" name="judul" value="{{ old('judul') }}" required maxlength="255" placeholder="Contoh: Pengenalan Struktur Data Lanjut" class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-purple-500 focus:outline-none transition placeholder-gray-300 dark:placeholder-gray-600 text-gray-800 dark:text-gray-100 font-medium">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Kategori</label>
                                <div class="relative">
                                    <select name="kategori" class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-700 rounded-xl text-sm appearance-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-purple-500 focus:outline-none transition text-gray-500 dark:text-gray-300 bg-white dark:bg-slate-900 font-semibold">
                                        <option value="" class="dark:bg-slate-900">Pilih Kategori</option>
                                        <option value="Teori" class="dark:bg-slate-900">Teori</option>
                                        <option value="Praktikum" class="dark:bg-slate-900">Praktikum</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Pertemuan Ke-</label>
                                <div class="relative">
                                    <select name="pertemuan_ke" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-700 rounded-xl text-sm appearance-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-purple-500 focus:outline-none transition text-gray-500 dark:text-gray-300 bg-white dark:bg-slate-900 font-semibold">
                                        <option value="" class="dark:bg-slate-900">Pilih Minggu</option>
                                        @for ($i = 1; $i <= 16; $i++)
                                            <option value="{{ $i }}" @selected((int) old('pertemuan_ke') === $i) class="dark:bg-slate-900">Minggu Ke-{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Deskripsi Materi</label>
                            <textarea name="deskripsi" rows="4" maxlength="5000" placeholder="Berikan penjelasan singkat mengenai materi ini..." class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-purple-500 focus:outline-none resize-none transition placeholder-gray-300 dark:placeholder-gray-600 text-gray-800 dark:text-gray-100">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Unggah File</label>
                            <div class="border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-2xl p-8 flex flex-col items-center justify-center text-center hover:border-blue-400 dark:hover:border-purple-500 transition cursor-pointer bg-slate-50/50 dark:bg-slate-900" onclick="document.getElementById('materiFileInput').click()">
                                <div class="bg-blue-50 dark:bg-purple-950/40 p-3 rounded-xl mb-3 text-[#321270] dark:text-purple-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Klik atau seret file ke area ini untuk memilih</p>
                                <p class="text-xs text-gray-400 dark:text-slate-500 mt-1">Sistem mendukung banyak file sekaligus</p>
                                <input id="materiFileInput" type="file" name="file_materi[]" multiple class="hidden" onchange="updateFileLabel(this)">
                                <div id="selectedFilesContainer" class="mt-3 text-xs font-semibold text-purple-700 dark:text-purple-300"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50/70 dark:bg-slate-900 px-8 py-4 border-t border-gray-100 dark:border-slate-800 flex justify-end gap-3">
                        <button type="button" onclick="toggleMateriModal('modalMateri')" class="px-5 py-2 rounded-lg border border-gray-200 dark:border-slate-700 text-sm font-semibold text-gray-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-[#321270] dark:bg-[#6c2bd9] text-sm font-semibold text-white hover:bg-opacity-90 dark:hover:bg-[#5b21b6] transition">
                            Unggah Materi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Fungsi untuk membuka / menutup modal
    function toggleMateriModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.toggle('hidden');
        }
    }

    // Fungsi untuk membuka / menutup dropdown titik tiga
    function toggleMateriDropdown(dropdownId) {
        const targetDropdown = document.getElementById(dropdownId);

        // Sembunyikan dropdown lainnya yang sedang terbuka
        document.querySelectorAll('.materi-dropdown').forEach(dropdown => {
            if (dropdown.id !== dropdownId) {
                dropdown.classList.add('hidden');
            }
        });

        if (targetDropdown) {
            targetDropdown.classList.toggle('hidden');
        }
    }

        // Fungsi untuk memilih pertemuan dari custom dropdown filter
    function pilihPertemuan(value, label) {
        const hiddenInput = document.getElementById('filterPertemuan');
        const labelEl = document.getElementById('pertemuanLabel');

        if (hiddenInput) hiddenInput.value = value;
        if (labelEl) labelEl.innerText = label;

        // Tutup dropdown setelah memilih
        document.getElementById('dropdownPertemuan')?.classList.add('hidden');

        // Trigger ulang filter
        const event = new Event('change');
        hiddenInput.dispatchEvent(event);
    }

    // Event listener untuk menutup dropdown apabila pengguna mengklik di luar area dropdown
    window.addEventListener('click', function(e) {
        if (!e.target.closest('.relative')) {
            document.querySelectorAll('.materi-dropdown').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        }
    });

    // Menampilkan daftar nama file yang dipilih di modal Tambah Materi
    function updateFileLabel(input) {
        const container = document.getElementById('selectedFilesContainer');
        container.innerHTML = '';
        if (input.files.length > 0) {
            const list = document.createElement('ul');
            list.className = 'list-disc list-inside text-left space-y-1';
            for (let i = 0; i < input.files.length; i++) {
                const li = document.createElement('li');
                li.innerText = input.files[i].name;
                list.appendChild(li);
            }
            container.appendChild(list);
        }
    }

    // JS Fitur Filter Pencarian & Pertemuan (Instan / Real-time)
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchMateri');
        const filterSelect = document.getElementById('filterPertemuan');
        const cards = document.querySelectorAll('.materi-card');
        const noResults = document.getElementById('materiNoResults');

        function filterMateri() {
            const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
            const selectedPertemuan = filterSelect ? filterSelect.value : '';
            let visibleCount = 0;

            cards.forEach(card => {
                const searchData = card.getAttribute('data-search') || '';
                const cardPertemuan = card.getAttribute('data-pertemuan') || '';

                const matchesQuery = query === '' || searchData.includes(query);
                const matchesPertemuan = selectedPertemuan === '' || cardPertemuan === selectedPertemuan;

                if (matchesQuery && matchesPertemuan) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            if (noResults) {
                if (visibleCount === 0 && cards.length > 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                }
            }
        }

        if (searchInput) searchInput.addEventListener('input', filterMateri);
        if (filterSelect) filterSelect.addEventListener('change', filterMateri);
    });
</script>
@endpush