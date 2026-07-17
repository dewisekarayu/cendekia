@extends('layouts.portal')
@section('title', $materi->judul ?? 'Detail Materi')
@section('content')

    @php
        $formatSize = function ($bytes) {
            if (!$bytes || $bytes <= 0) return null;
            $units = ['B', 'KB', 'MB', 'GB'];
            $i = 0;
            while ($bytes >= 1024 && $i < count($units) - 1) {
                $bytes /= 1024;
                $i++;
            }
            return round($bytes, $i === 0 ? 0 : 1) . ' ' . $units[$i];
        };
    @endphp

{{-- ===== TOP BAR: Kembali + Aksi ===== --}}
    <div class="mb-4 flex items-center justify-between gap-3">
        <a href="{{ route('dosen.kelas-materi', $kelas->id) }}"
           class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 dark:text-slate-400 hover:text-[#321270] dark:hover:text-purple-400 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Kelas
        </a>

        <div class="flex items-center gap-2">
            <button type="button" onclick="toggleMateriModal('modalEditMateriDetail')"
                class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-3 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Materi
            </button>

            <form action="{{ route('dosen.kelas-materi.hapus', [$kelas->id, $materi->id]) }}" method="POST"
                onsubmit="return confirm('Hapus materi &quot;{{ $materi->judul }}&quot;? Semua file yang terlampir juga akan terhapus.');">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-3 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-950/25 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    {{-- ===== HERO HEADER (selaras dengan halaman Materi) ===== --}}
    <div class="bg-gradient-to-r from-[#321270] to-[#4c19a0] dark:from-indigo-950 dark:to-purple-900 rounded-xl px-8 py-6 mb-6 shadow-sm">
        <div class="flex items-center gap-2 mb-2">
            <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-white/15 text-white px-2.5 py-1 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0121 12c0 2.21-.895 4.21-2.343 5.657L12 21l-6.657-3.343A8.001 8.001 0 013 12a12.083 12.083 0 012.84-3.422L12 14z"/></svg>
                {{ $kelas->mataKuliah?->programStudi?->nama_prodi ?? 'Program Studi' }}
            </span>
            <span class="text-xs text-white/80">{{ $kelas->semester?->nama_semester ?? 'Semester -' }}</span>
        </div>
        <h1 class="text-xl font-bold text-white">{{ $kelas->mataKuliah?->nama_mk ?? 'Mata Kuliah' }}</h1>
        <p class="text-sm text-white/80 mt-1 flex items-center gap-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold bg-white/15 text-white">
                Pertemuan {{ $materi->pertemuan_ke }}
            </span>
            {{ $materi->judul }}
            @if(isset($materi->kategori) && $materi->kategori)
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-white/10 text-white/90">
                    {{ $materi->kategori }}
                </span>
            @endif
        </p>
    </div>

    {{-- ===== CARD ===== --}}
    <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 sm:p-8 shadow-sm transition-colors duration-200">

        {{-- Deskripsi --}}
        <div class="flex items-center gap-2 mb-3">
            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-950/60 text-[#321270] dark:text-purple-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-white">Deskripsi Materi</h2>
        </div>

        @if ($materi->deskripsi)
            <p class="whitespace-pre-line text-sm leading-relaxed text-slate-600 dark:text-slate-300 pl-9">
                {{ $materi->deskripsi }}
            </p>
        @else
            <p class="text-sm italic text-slate-400 dark:text-slate-500 pl-9">Belum ada deskripsi untuk materi ini.</p>
        @endif

        <hr class="my-6 border-slate-100 dark:border-slate-700">

        {{-- File --}}
        <div class="flex items-center gap-2 mb-4">
            <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-purple-50 dark:bg-purple-950/60 text-[#321270] dark:text-purple-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
            </div>
            <h2 class="text-sm font-bold text-slate-800 dark:text-white">Lampiran File</h2>
            @if ($materi->files->isNotEmpty())
                <span class="text-xs font-semibold text-slate-400 dark:text-slate-500">({{ $materi->files->count() }})</span>
            @endif

        </div>

        <div class="space-y-3">
            @if ($materi->files->isNotEmpty())
                @php
                    $icons = [
                        'pdf'   => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
                        'ppt'   => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                        'pptx'  => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                        'mp4'   => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    ];

                    // Warna aksen berbeda per tipe file supaya lebih hidup, tetap dalam keluarga ungu/pendukung
                    $accents = [
                        'pdf'  => ['bg' => 'bg-rose-50 dark:bg-rose-950/40', 'text' => 'text-rose-600 dark:text-rose-300'],
                        'ppt'  => ['bg' => 'bg-orange-50 dark:bg-orange-950/40', 'text' => 'text-orange-600 dark:text-orange-300'],
                        'pptx' => ['bg' => 'bg-orange-50 dark:bg-orange-950/40', 'text' => 'text-orange-600 dark:text-orange-300'],
                        'mp4'  => ['bg' => 'bg-purple-50 dark:bg-purple-950/60', 'text' => 'text-[#321270] dark:text-purple-300'],
                        'doc'  => ['bg' => 'bg-blue-50 dark:bg-blue-950/40', 'text' => 'text-blue-600 dark:text-blue-300'],
                        'docx' => ['bg' => 'bg-blue-50 dark:bg-blue-950/40', 'text' => 'text-blue-600 dark:text-blue-300'],
                        'xls'  => ['bg' => 'bg-emerald-50 dark:bg-emerald-950/40', 'text' => 'text-emerald-600 dark:text-emerald-300'],
                        'xlsx' => ['bg' => 'bg-emerald-50 dark:bg-emerald-950/40', 'text' => 'text-emerald-600 dark:text-emerald-300'],
                    ];
                @endphp
                @foreach ($materi->files as $file)
                    @php
                        $tipe = $file->tipe_file ?? '';
                        $iconPath = $icons[$tipe] ?? 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253';
                        $accent = $accents[$tipe] ?? ['bg' => 'bg-purple-50 dark:bg-purple-950/60', 'text' => 'text-[#321270] dark:text-purple-300'];

                        $fileSize = null;
                        $fileDate = null;
                        try {
                            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($file->file_path)) {
                                $fileSize = $formatSize(\Illuminate\Support\Facades\Storage::disk('public')->size($file->file_path));
                                $fileDate = \Illuminate\Support\Carbon::createFromTimestamp(
                                    \Illuminate\Support\Facades\Storage::disk('public')->lastModified($file->file_path)
                                )->translatedFormat('d M Y, H:i');
                            }
                        } catch (\Throwable $e) {
                            // biarkan null jika gagal membaca metadata file
                        }

                        $previewUrl = route('dosen.materi.preview', [$kelas->id, $materi->id, $file->id]);
                        $downloadUrl = route('dosen.materi.unduh', [$kelas->id, $materi->id, $file->id]);
                    @endphp
                    <div class="group rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/40 hover:border-purple-200 dark:hover:border-purple-900/50 hover:shadow-md transition-all duration-200 overflow-hidden">
                        <div class="flex items-center gap-4 p-4">
                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg {{ $accent['bg'] }} {{ $accent['text'] }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}"/></svg>
                            </div>

                            <a href="{{ $previewUrl }}" target="_blank" class="min-w-0 flex-1">
                                <p class="truncate text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-[#321270] dark:group-hover:text-purple-300 group-hover:underline transition">
                                    {{ $file->nama_asli ?? basename($file->file_path) }}
                                </p>
                                <p class="flex flex-wrap items-center gap-x-1.5 text-[11px] text-slate-400 dark:text-slate-500 mt-0.5">
                                    @if ($file->tipe_file)
                                        <span class="uppercase tracking-wide font-semibold">{{ $file->tipe_file }}</span>
                                    @endif
                                    @if ($fileSize)
                                        <span>•</span><span>{{ $fileSize }}</span>
                                    @endif
                                    @if ($fileDate)
                                        <span>•</span><span>Diunggah {{ $fileDate }}</span>
                                    @endif
                                </p>
                            </a>

                            <button type="button" onclick="copyMateriLink('{{ $downloadUrl }}', this)"
                                title="Salin link file"
                                class="shrink-0 rounded-lg p-2 text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-slate-700 dark:hover:text-slate-200 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 copy-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </button>

                            <a href="{{ $downloadUrl }}"
                            title="Unduh file"
                            class="shrink-0 inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-xs font-bold text-[#321270] dark:text-purple-300 bg-purple-50 dark:bg-purple-950/40 hover:bg-[#321270] hover:text-white dark:hover:bg-[#6c2bd9] dark:hover:text-white transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                <span class="hidden sm:inline">Unduh</span>
                            </a>
                        </div>

                        {{-- Preview inline untuk video --}}
                        @if ($tipe === 'mp4')
                            <div class="border-t border-slate-200 dark:border-slate-700 bg-black">
                                <video controls preload="metadata" class="w-full max-h-[420px]">
                                    <source src="{{ $previewUrl }}" type="video/mp4">
                                    Browser Anda tidak mendukung pemutaran video.
                                </video>
                            </div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="flex flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900 p-10 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-300 dark:text-slate-600 mb-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400 mb-3">File belum diunggah untuk materi ini.</p>
                    <button type="button" onclick="toggleMateriModal('modalEditMateriDetail')"
                        class="inline-flex items-center gap-1.5 text-xs font-bold bg-[#321270] dark:bg-[#6c2bd9] text-white px-4 py-2 rounded-lg hover:bg-[#321270]/90 dark:hover:bg-[#5b21b6] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah File
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- ===== MODAL EDIT MATERI ===== --}}
    <div id="modalEditMateriDetail" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black bg-opacity-40 transition-opacity" onclick="toggleMateriModal('modalEditMateriDetail')"></div>

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

                <form action="{{ route('dosen.kelas-materi.update', [$kelas->id, $materi->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="px-8 py-6 space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Judul Materi</label>
                            <input type="text" name="judul" value="{{ $materi->judul }}" required maxlength="255"
                                class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-purple-500 focus:outline-none transition text-gray-800 dark:text-gray-100 font-medium">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Pertemuan Ke-</label>
                            <div class="relative">
                                <select name="pertemuan_ke" required class="w-full px-4 py-2.5 border border-gray-200 dark:border-slate-700 rounded-xl text-sm appearance-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-purple-500 focus:outline-none transition text-gray-500 dark:text-gray-300 bg-white dark:bg-slate-900 font-semibold">
                                    @for ($i = 1; $i <= 16; $i++)
                                        <option value="{{ $i }}" @selected($materi->pertemuan_ke === $i) class="dark:bg-slate-900">Minggu Ke-{{ $i }}</option>
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
                                class="w-full px-4 py-3 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 dark:focus:border-purple-500 focus:outline-none resize-none transition text-gray-800 dark:text-gray-100">{{ $materi->deskripsi }}</textarea>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">File Lampiran Saat Ini</label>
                            <div class="space-y-2">
                                @forelse($materi->files as $file)
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
                        <button type="button" onclick="toggleMateriModal('modalEditMateriDetail')" class="px-5 py-2 rounded-lg border border-gray-200 dark:border-slate-700 text-sm font-semibold text-gray-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
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

@endsection

@push('scripts')
<script>
    function toggleMateriModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.toggle('hidden');
        }
    }

    function copyMateriLink(url, btn) {
        const absoluteUrl = new URL(url, window.location.origin).href;
        navigator.clipboard.writeText(absoluteUrl).then(() => {
            const icon = btn.querySelector('.copy-icon');
            const original = icon.innerHTML;
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />';
            btn.classList.add('text-green-600');
            setTimeout(() => {
                icon.innerHTML = original;
                btn.classList.remove('text-green-600');
            }, 1500);
        }).catch(() => {
            alert('Gagal menyalin link. Silakan salin manual: ' + absoluteUrl);
        });
    }
</script>
@endpush