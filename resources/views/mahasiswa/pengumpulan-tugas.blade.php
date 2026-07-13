{{-- resources/views/pengumpulan_tugas/show.blade.php --}}
@extends('layouts.portal')

@section('title', $tugas->judul ?? 'Detail Tugas')

@section('content')
<div class="w-full px-4 py-8 sm:px-6 lg:px-8">

    <a href="{{ route('mahasiswa.kelas-detail', $tugas->kelas_perkuliahan_id) }}"
       class="inline-flex items-center gap-1.5 mb-4 text-sm font-medium text-slate-500 hover:text-[#002B6B] transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Detail Kelas
    </a>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- ===================== CARD: DETAIL TUGAS ===================== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-6">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-xs text-slate-400 mb-3">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A12.083 12.083 0 0121 13.5c0 2.65-.895 5.09-2.4 7.03M12 14l-6.16-3.42A12.083 12.083 0 003 13.5c0 2.65.895 5.09 2.4 7.03" />
            </svg>
            <span>{{ $tugas->kelasPerkuliahan?->mataKuliah?->nama_mk ?? 'Mata Kuliah' }} · Kelas {{ $tugas->kelasPerkuliahan?->kode_kelas ?? '-' }}</span>
        </div>

        {{-- Header: judul + batas waktu --}}
        <div class="flex items-start justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-900">{{ $tugas->judul }}</h1>
                <p class="text-blue-600 font-medium text-sm mt-0.5">{{ $tugas->sub_judul ?? $tugas->deskripsi_singkat }}</p>
            </div>

            <div class="text-right shrink-0">
                <p class="text-[11px] tracking-wide text-slate-400 font-medium">BATAS WAKTU</p>
                <p class="text-red-500 font-semibold text-sm">
                    {{ \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('j M Y, H.i') }}
                </p>

                @if ($pengumpulan?->is_graded)
                    <span class="inline-block mt-2 bg-emerald-100 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-md">
                        GRADED
                    </span>
                    <p class="text-blue-600 font-bold text-sm mt-1">{{ $pengumpulan->nilai }}/100</p>
                @elseif ($pengumpulan)
                    <span class="inline-block mt-2 bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-md">
                        MENUNGGU PENILAIAN
                    </span>
                @endif
            </div>
        </div>

        <hr class="my-4 border-slate-100">

        {{-- Instruksi --}}
        <h2 class="font-semibold text-slate-800 mb-2">Instruksi Pengerjaan</h2>
        <div class="text-sm text-slate-600 leading-relaxed space-y-2">
            {!! nl2br(e($tugas->instruksi)) !!}
        </div>

        {{-- File lampiran (jika ada) --}}
        @if ($tugas->files->count())
            <div class="mt-4 space-y-2">
                @foreach ($tugas->files as $file)
                    <div class="flex items-center gap-3 bg-slate-50 border border-slate-100 rounded-xl px-4 py-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>

                        {{-- Klik nama file = buka di tab baru --}}
                        <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="flex-1 min-w-0 group">
                            <p class="text-sm font-medium text-slate-700 truncate group-hover:text-blue-600 group-hover:underline transition">
                                {{ $file->nama_asli ?? basename($file->file_path) }}
                            </p>
                            <p class="text-xs text-slate-400">Lampiran tugas</p>
                        </a>

                        {{-- Icon panah = unduh --}}
                        <a href="{{ Storage::url($file->file_path) }}" download
                        title="Unduh file"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-blue-600 hover:bg-blue-50 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ===================== CARD: UPLOAD / STATUS JAWABAN ===================== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        @if ($pengumpulan)
            {{-- Sudah mengumpulkan: tampilkan file-file yang diunggah --}}
            <div class="space-y-2">
                @foreach ($pengumpulan->files as $file)
                    @php
                        $ext = strtolower(pathinfo($file->file_path, PATHINFO_EXTENSION));
                        $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                    @endphp
                    <div class="flex items-center gap-3 border border-slate-100 rounded-xl px-4 py-3 bg-slate-50">
                        <div class="w-9 h-9 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                            @if ($isImage)
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            @endif
                        </div>
                        <p class="flex-1 min-w-0 text-sm text-slate-700 truncate">{{ $file->nama_asli ?? basename($file->file_path) }}</p>
                        <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                        class="text-blue-600 text-xs font-semibold hover:underline shrink-0">Lihat</a>
                    </div>
                @endforeach
            </div>

            @if ($pengumpulan->catatan)
                <p class="text-sm text-slate-500 mt-3"><span class="font-medium text-slate-600">Catatan Anda:</span> {{ $pengumpulan->catatan }}</p>
            @endif

            @if ($pengumpulan->is_graded)
                <div class="mt-4 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
                    <p class="text-sm font-semibold text-blue-700 mb-1">Feedback Dosen</p>
                    <p class="text-sm text-blue-900/80">{{ $pengumpulan->feedback_dosen }}</p>
                </div>
            @endif
        @else
            {{-- Belum mengumpulkan: form upload --}}
            <form action="{{ route('mahasiswa.pengumpulan-tugas.store', $tugas->id) }}" method="POST" enctype="multipart/form-data" x-data="{ fileNames: [] }">
                @csrf

                <label for="file_jawaban"
                    class="flex flex-col items-center justify-center border-2 border-dashed border-slate-200 rounded-2xl py-10 cursor-pointer hover:border-blue-300 hover:bg-blue-50/30 transition">
                    <div class="w-11 h-11 rounded-full bg-blue-100 flex items-center justify-center mb-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                    </div>
                    <p class="font-semibold text-slate-700 text-sm">Unggah Jawaban (bisa lebih dari 1 file)</p>

                    <template x-if="fileNames.length === 0">
                        <p class="text-xs text-slate-400 mt-1">Seret berkas di sini atau klik untuk memilih</p>
                    </template>
                    <template x-if="fileNames.length > 0">
                        <ul class="text-xs text-slate-500 mt-1 space-y-0.5 text-center">
                            <template x-for="name in fileNames" :key="name">
                                <li x-text="name"></li>
                            </template>
                        </ul>
                    </template>

                    <div class="flex flex-wrap items-center justify-center gap-2 mt-4">
                        <span class="text-[11px] font-medium text-slate-500 border border-slate-200 rounded-md px-2 py-0.5">PDF</span>
                        <span class="text-[11px] font-medium text-slate-500 border border-slate-200 rounded-md px-2 py-0.5">DOC</span>
                        <span class="text-[11px] font-medium text-slate-500 border border-slate-200 rounded-md px-2 py-0.5">PPT</span>
                        <span class="text-[11px] font-medium text-slate-500 border border-slate-200 rounded-md px-2 py-0.5">JPG/PNG</span>
                        <span class="text-[11px] font-medium text-slate-500 border border-slate-200 rounded-md px-2 py-0.5">ZIP</span>
                        <span class="text-[11px] font-medium text-slate-500 border border-slate-200 rounded-md px-2 py-0.5">MAX 10MB/FILE</span>
                    </div>

                    <input id="file_jawaban" name="file_jawaban[]" type="file"
                        accept=".pdf,.zip,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png" multiple class="hidden"
                        @change="fileNames = Array.from($event.target.files).map(f => f.name)">
                </label>
                @error('file_jawaban')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
                @error('file_jawaban.*')
                    <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror

                <textarea name="catatan" rows="2" placeholder="Catatan tambahan (opsional)"
                          class="w-full mt-4 text-sm border border-slate-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-300">{{ old('catatan') }}</textarea>

                {{-- Footer status + aksi --}}
                <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-100">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-red-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-slate-700">Belum Diserahkan</p>
                            <p class="text-xs text-slate-400">Segera unggah sebelum batas waktu berakhir</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ url()->previous() }}"
                           class="px-4 py-2 text-sm font-medium text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Kumpulkan Tugas
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection