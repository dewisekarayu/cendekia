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
            'Materi'       => ['url' => route('dosen.kelas-materi', $kelas->id), 'active' => request()->routeIs('dosen.kelas-materi')],
            'Tugas'        => ['url' => route('dosen.kelas-tugas', $kelas->id),  'active' => request()->routeIs('dosen.kelas-tugas')],
            'Absensi'      => ['url' => route('dosen.absensi.index', $kelas->id),  'active' => request()->routeIs('dosen.absensi.*')],
            'Penilaian'    => ['url' => route('dosen.gradebook', ['kelas_id' => $kelas->id]), 'active' => request()->routeIs('dosen.gradebook')],
        ];
    @endphp
    <div class="mb-5 flex items-center gap-1 overflow-x-auto rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-1.5 shadow-sm transition-colors duration-200">
        @foreach ($tabLinks as $label => $tab)
            <a href="{{ $tab['url'] }}"
            class="whitespace-nowrap rounded-xl px-4 py-2 text-xs font-bold transition
                {{ $tab['active']
                    ? 'bg-[#321270] dark:bg-purple-650 text-white shadow-sm shadow-purple-900/20'
                    : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-800 dark:hover:text-white' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 p-6 shadow-sm transition-colors duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-800 dark:text-white">Materi</h2>
                    <button onclick="toggleMateriModal('modalMateri')" class="text-xs font-bold bg-[#321270] dark:bg-[#6c2bd9] text-white px-3 py-2 rounded-lg hover:bg-[#321270]/90 dark:hover:bg-[#5b21b6] transition flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Materi
                    </button>
                </div>

                @if ($materiList->isEmpty())
                    <div class="text-sm text-gray-500 dark:text-slate-400 py-4">Belum ada materi untuk kelas ini.</div>
                @else
                    <ul class="space-y-3">
                        @foreach($materiList as $m)
                            <li class="p-4 border dark:border-slate-700 rounded-lg">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="text-sm font-bold text-gray-800 dark:text-white">Pertemuan {{ $m->pertemuan_ke }} - {{ $m->judul }}</div>
                                        <div class="text-xs text-gray-500 dark:text-slate-400 mt-1">{{ Str::limit($m->deskripsi, 200) }}</div>
                                    </div>
                                    <div class="text-right space-y-1">
                                        @forelse($m->files as $file)
                                            <a href="{{ route('dosen.materi.buka', [$kelas->id, $m->id, $file->id]) }}"
                                            class="block text-xs px-3 py-1 bg-[#321270] dark:bg-purple-950/40 text-white dark:text-purple-300 rounded hover:bg-[#250d54] dark:hover:bg-purple-600 dark:hover:text-white truncate max-w-[160px] transition duration-150">
                                                {{ $file->nama_asli ?? 'Buka File' }}
                                            </a>
                                        @empty
                                            <span class="text-xs text-gray-400 dark:text-slate-500">Tidak ada file</span>
                                        @endforelse
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

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

    <div id="modalMateri" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black bg-opacity-40 transition-opacity" onclick="toggleMateriModal('modalMateri')"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-slate-850 w-full max-w-3xl rounded-2xl shadow-xl overflow-hidden">
                
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
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-200">Seret dan lepas file di sini</p>
                                <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5">atau <span class="text-blue-600 dark:text-purple-400 underline font-medium">pilih file</span> dari komputer Anda</p>
                                <p class="text-[11px] text-gray-400 dark:text-slate-500 mt-3">PDF, PPT, DOCX, XLS, MP4, ZIP, JPG, PNG (Maksimal 100MB/file)</p>
                                <input type="file" id="materiFileInput" name="file_materi[]" multiple class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.zip,.jpg,.jpeg,.png" onchange="updateFileName(this)">
                                <ul id="fileNameDisplay" class="text-sm text-emerald-600 dark:text-emerald-400 font-medium mt-2 hidden space-y-0.5 text-left"></ul>
                            </div>
                        </div>

                    </div>

                    <div class="bg-gray-50/70 dark:bg-slate-900 px-8 py-4 border-t border-gray-100 dark:border-slate-800 flex justify-end gap-3">
                        <button type="button" onclick="toggleMateriModal('modalMateri')" class="px-5 py-2 rounded-lg border border-gray-200 dark:border-slate-700 text-sm font-semibold text-gray-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-[#0f172a] dark:bg-[#6c2bd9] text-sm font-semibold text-white hover:bg-opacity-90 dark:hover:bg-[#5b21b6] transition">
                            Simpan Materi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleMateriModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.toggle("hidden");
            document.body.classList.toggle("overflow-hidden");
        }

        function updateFileName(input) {
            const display = document.getElementById('fileNameDisplay');
            display.innerHTML = '';

            if (input.files.length > 0) {
                Array.from(input.files).forEach(file => {
                    const li = document.createElement('li');
                    li.textContent = file.name;
                    display.appendChild(li);
                });
                display.classList.remove('hidden');
            } else {
                display.classList.add('hidden');
            }
        }
    </script>

@endsection
