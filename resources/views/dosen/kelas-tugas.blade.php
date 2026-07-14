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

    <!-- Create Assignment Button -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 pb-4 border-b border-gray-100 dark:border-slate-700">
        {{-- Sisi Kiri: Judul --}}
        <div>
            <h2 class="text-xl font-bold text-gray-800 dark:text-white tracking-tight">Daftar Tugas</h2>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Kelola dan pantau tugas mahasiswa</p>
        </div>

        {{-- Sisi Kanan: Aksi (Tombol-Tombol) --}}
        <div class="flex flex-wrap items-center gap-3">
            {{-- Tombol Rekap Nilai --}}
            <a href="{{ route('dosen.kelas-tugas.rekap', $kelas->id) }}"
            class="inline-flex items-center text-xs font-semibold text-[#321270] dark:text-purple-300 bg-purple-50 dark:bg-purple-950/40 hover:bg-purple-100 dark:hover:bg-purple-900/40 px-4 py-2.5 rounded-xl transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Lihat Rekap Nilai
            </a>

            {{-- Tombol Tambah Tugas Baru --}}
            <button onclick="toggleModal('modalTugas')" 
                    class="inline-flex items-center gap-2 bg-[#321270] dark:bg-[#6c2bd9] hover:bg-[#230c50] dark:hover:bg-[#5b21b6] text-white text-sm font-semibold px-[18px] py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow active:scale-[0.98]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Create New Assignment
            </button>
        </div>
    </div>

    <!-- Assignments Table -->
    <div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors duration-200">
        @if ($tugasList->isEmpty())
            <div class="p-10 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-gray-300 dark:text-slate-650 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 dark:text-slate-400 text-sm">Belum ada tugas untuk kelas ini. Klik "Create New Assignment" untuk mulai.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 dark:text-slate-550 text-xs border-b border-gray-100 dark:border-slate-700">
                        <th class="px-5 py-3 font-medium">Assignment</th>
                        <th class="px-5 py-3 font-medium">Deadline</th>
                        <th class="px-5 py-3 font-medium">Submissions</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-55 dark:divide-slate-700/50">
                    @foreach ($tugasList as $tugas)
                        @php
                            $isPastDeadline = $tugas->deadline < now();
                            $submitted = $tugas->submitted_count ?? 0;
                            $total = $kelas->mahasiswa->count();
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-900/20 transition duration-150">
                            <td class="px-5 py-3">
                                <p class="font-bold text-gray-800 dark:text-white">{{ $tugas->judul }}</p>
                                <p class="text-xs text-gray-400 dark:text-slate-500">Bobot: {{ $tugas->bobot_nilai }}%</p>
                            </td>
                            <td class="px-5 py-3 text-gray-500 dark:text-slate-350">
                                {{ $tugas->deadline->format('d M Y, H:i') }}
                            </td>
                            <td class="px-5 py-3 text-gray-600 dark:text-slate-300">
                                {{ $submitted }}/{{ $total }} submitted
                            </td>
                            <td class="px-5 py-3">
                                @if ($isPastDeadline)
                                    <span class="inline-block text-[10px] font-semibold text-gray-600 dark:text-slate-350 bg-gray-100 dark:bg-slate-700 px-2 py-1 rounded">Closed</span>
                                @else
                                    <span class="inline-block text-[10px] font-semibold text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/40 px-2 py-1 rounded">Open</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('dosen.tugas.submissions', [$kelas->id, $tugas->id]) }}" class="inline-block text-xs font-semibold text-blue-900 dark:text-blue-300 bg-blue-50 dark:bg-blue-950/40 hover:bg-blue-100 dark:hover:bg-blue-900/40 px-3 py-1.5 rounded-md transition duration-150">
                                    View Submissions
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div id="modalTugas" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="toggleModal('modalTugas')"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white dark:bg-slate-850 w-full max-w-3xl rounded-2xl shadow-xl overflow-hidden">
                
                <div class="px-8 py-6 bg-gray-50/50 dark:bg-slate-900 border-b border-gray-100 dark:border-slate-800 flex items-center gap-4">
                    <div class="bg-[#321270] dark:bg-purple-650 p-2 rounded-lg text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-[#1e293b] dark:text-white">Informasi Tugas</h3>
                </div>

                <form action="{{ route('dosen.kelas-tugas.store', $kelas->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-8 py-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Assignment Title</label>
                                <input type="text" name="judul" value="{{ old('judul') }}" required maxlength="255" placeholder="Contoh: Implementasi Stack dan Queue" class="w-full px-4 py-2 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 focus:outline-none transition text-gray-800 dark:text-gray-100">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Subject/Class</label>
                                <select name="kelas_id" class="w-full px-4 py-2 border border-gray-200 dark:border-slate-700 rounded-xl appearance-none bg-no-repeat bg-[right_1rem_center] focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 focus:outline-none transition text-gray-800 dark:text-gray-100 bg-white dark:bg-slate-900 font-semibold" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%20fill%3D%22none%22%20stroke%3D%22currentColor%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C/polyline%3E%3C/svg%3E');">
                                    <option value="" class="dark:bg-slate-900 text-gray-400">Pilih Mata Kuliah</option>
                                    <option value="{{ $kelas->id }}" class="dark:bg-slate-900">{{ $kelas->mataKuliah->nama_mk }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2 relative">
                                <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Deadline</label>
                                <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" required class="w-full px-4 py-2 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 focus:outline-none transition text-gray-800 dark:text-gray-100">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Total Points</label>
                                <input type="number" name="poin" value="{{ old('poin', 100) }}" required min="1" max="100" class="w-full px-4 py-2 border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl focus:ring-2 focus:ring-blue-500 dark:focus:ring-purple-500 focus:outline-none transition text-gray-800 dark:text-gray-100">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-slate-300">Instructions</label>
                            <div class="border border-gray-200 dark:border-slate-700 rounded-xl overflow-hidden">
                                <div class="bg-gray-50 dark:bg-slate-900 px-4 py-2 border-b border-gray-200 dark:border-slate-700 flex gap-4 text-gray-600 dark:text-slate-350">
                                    <button type="button" class="hover:text-black dark:hover:text-white font-serif font-bold">B</button>
                                    <button type="button" class="hover:text-black dark:hover:text-white italic">I</button>
                                    <button type="button" class="hover:text-black dark:hover:text-white underline">U</button>
                                    <span class="border-r border-gray-300 dark:border-slate-700"></span>
                                    <button type="button"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg></button>
                                    <button type="button"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.587-1.587a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></button>
                                </div>
                                <textarea name="instruksi" rows="5" required maxlength="5000" placeholder="Tuliskan detail instruksi pengerjaan tugas di sini..." class="w-full px-4 py-3 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 focus:outline-none resize-none">{{ old('instruksi') }}</textarea>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-gray-700 dark:text-slate-300">File Attachment Templates</label>
                            <div class="border-2 border-dashed border-gray-200 dark:border-slate-700 rounded-2xl p-8 flex flex-col items-center justify-center text-center hover:border-blue-400 dark:hover:border-purple-500 transition cursor-pointer bg-slate-50/50 dark:bg-slate-900" onclick="document.getElementById('fileInput').click()">
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
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50/50 dark:bg-slate-900 px-8 py-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                        <button type="button" onclick="toggleModal('modalTugas')" class="px-6 py-2.5 rounded-lg border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 font-semibold hover:bg-gray-100 dark:hover:bg-slate-850 transition">
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

    <script>
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
            document.body.classList.toggle("overflow-hidden");
        }

        function updateTugasFileName(input) {
            const display = document.getElementById('tugasFileNameDisplay');
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
