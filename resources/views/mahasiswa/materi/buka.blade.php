@extends('layouts.portal')
@section('title', $materi->judul ?? 'Detail Materi')
@section('content')

{{-- ===== BREADCRUMB / BACK ===== --}}
    <div class="mb-4">
        <a href="{{ route('mahasiswa.kelas-detail', $kelas->id) }}"
           class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 hover:text-[#002B6B] transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Kelas
        </a>
    </div>

    {{-- ===== CARD ===== --}}
    <div class="rounded-2xl border border-slate-200/80 bg-white p-6 sm:p-8 shadow-sm">

        {{-- Top meta line --}}
        <div class="mb-4 flex items-center gap-2 text-xs font-semibold text-[#002B6B]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0121 12c0 2.21-.895 4.21-2.343 5.657L12 21l-6.657-3.343A8.001 8.001 0 013 12a12.083 12.083 0 012.84-3.422L12 14z"/></svg>
            <span>{{ $kelas->mataKuliah?->programStudi?->nama_prodi ?? 'Program Studi' }} / {{ $kelas->semester?->nama_semester ?? 'Semester -' }}</span>
        </div>

        {{-- Title --}}
        <h1 class="text-xl font-extrabold leading-snug text-slate-800 sm:text-2xl">
            {{ $kelas->mataKuliah?->nama_mk ?? 'Mata Kuliah' }}
        </h1>
        <p class="mt-1 text-base font-semibold text-blue-600">
            Materi Pertemuan {{ $materi->pertemuan_ke }}: {{ $materi->judul }}
        </p>

        <hr class="my-5 border-slate-100">

        {{-- Deskripsi --}}
        <h2 class="mb-2 text-sm font-bold text-slate-800">Deskripsi Materi</h2>
        @if ($materi->deskripsi)
            <p class="whitespace-pre-line text-sm leading-relaxed text-slate-600">
                {{ $materi->deskripsi }}
            </p>
        @else
            <p class="text-sm italic text-slate-400">Belum ada deskripsi untuk materi ini.</p>
        @endif

        {{-- File --}}
        <div class="mt-6">
            @if ($materi->file_path)
                @php
                    $icons = [
                        'pdf'   => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
                        'ppt'   => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                        'video' => 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    ];
                    $iconPath = $icons[$materi->tipe_file ?? ''] ?? 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253';
                @endphp
                <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-[#002B6B]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}"/></svg>
                    </div>

                    {{-- Nama file: klik = buka file di tab baru --}}
                    <a href="{{ route('mahasiswa.materi.preview', [$kelas->id, $materi->id]) }}" target="_blank" class="min-w-0 flex-1 group">
                        <p class="truncate text-sm font-semibold text-slate-800 group-hover:text-[#002B6B] group-hover:underline transition">
                            {{ basename($materi->file_path) }}
                        </p>
                        @if ($materi->tipe_file)
                            <p class="text-[11px] uppercase tracking-wide text-slate-400">{{ $materi->tipe_file }}</p>
                        @endif
                    </a>

                    {{-- Icon kecil: klik = unduh file --}}
                    <a href="{{ route('mahasiswa.materi.unduh', [$kelas->id, $materi->id]) }}"
                    title="Unduh file"
                    class="shrink-0 rounded-lg p-2 text-slate-400 hover:bg-blue-50 hover:text-[#002B6B] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                </div>
            @endif
        </div>

    </div>
@endsection