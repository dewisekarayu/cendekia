@extends('layouts.portal')

@section('title', 'Tugas & Proyek')
@section('activeMenu', 'Kelas Saya')

@section('content')

    <!-- Class Header Banner -->
    <div class="bg-[#321270] rounded-xl px-8 py-6 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xs font-semibold bg-white/15 text-white px-2.5 py-1 rounded">{{ $kelas->mataKuliah->kode_mk ?? '-' }}</span>
                <span class="text-xs text-white/80">Semester {{ $kelas->semester->nama_semester ?? '-' }}</span>
            </div>
            <h1 class="text-xl font-bold text-white">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</h1>
            <p class="text-sm text-blue-200 mt-1">
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
            'Penilaian'    => ['url' => route('dosen.gradebook', ['kelas_id' => $kelas->id]), 'active' => request()->routeIs('dosen.gradebook')],
        ];
    @endphp
    <div class="mb-5 flex items-center gap-1 overflow-x-auto rounded-2xl border border-slate-200/80 bg-white p-1.5 shadow-sm">
        @foreach ($tabLinks as $label => $tab)
            <a href="{{ $tab['url'] }}"
            class="whitespace-nowrap rounded-xl px-4 py-2 text-xs font-semibold transition
                {{ $tab['active']
                    ? 'bg-[#321270] text-white shadow-sm shadow-purple-900/20'
                    : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Tugas</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $tugasList->count() }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <p class="text-sm text-gray-500">Perlu Dinilai</p>
            <p class="text-2xl font-bold text-amber-600 mt-1">
                {{ $perluDinilai ?? 0 }}
            </p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <p class="text-sm text-gray-500">Total Mahasiswa</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $kelas->mahasiswa->count() }}</p>
        </div>
    </div>

    <!-- Create Assignment Button -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-800">Daftar Tugas</h2>
        <button onclick="toggleModal('modalTugas')" class="inline-flex items-center gap-2 bg-[#321270] hover:bg-opacity-90 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Create New Assignment
        </button>
    </div>

    <!-- Assignments Table -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        @if ($tugasList->isEmpty())
            <div class="p-10 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 text-sm">Belum ada tugas untuk kelas ini. Klik "Create New Assignment" untuk mulai.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 text-xs border-b border-gray-100">
                        <th class="px-5 py-3 font-medium">Assignment</th>
                        <th class="px-5 py-3 font-medium">Deadline</th>
                        <th class="px-5 py-3 font-medium">Submissions</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tugasList as $tugas)
                        @php
                            $isPastDeadline = $tugas->deadline < now();
                            $submitted = $tugas->submitted_count ?? 0;
                            $total = $kelas->mahasiswa->count();
                        @endphp
                        <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50">
                            <td class="px-5 py-3">
                                <p class="font-medium text-gray-800">{{ $tugas->judul }}</p>
                                <p class="text-xs text-gray-400">Bobot: {{ $tugas->bobot_nilai }}%</p>
                            </td>
                            <td class="px-5 py-3 text-gray-500">
                                {{ $tugas->deadline->format('d M Y, H:i') }}
                            </td>
                            <td class="px-5 py-3 text-gray-600">
                                {{ $submitted }}/{{ $total }} submitted
                            </td>
                            <td class="px-5 py-3">
                                @if ($isPastDeadline)
                                    <span class="inline-block text-[10px] font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded">Closed</span>
                                @else
                                    <span class="inline-block text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-1 rounded">Open</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('dosen.tugas.submissions', [$kelas->id, $tugas->id]) }}" class="inline-block text-xs font-medium text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition">
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
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

            <div class="relative min-h-screen flex items-center justify-center p-4">
                <div class="relative bg-white w-full max-w-3xl rounded-2xl shadow-xl overflow-hidden">
                    
                    <div class="px-8 py-6 border-b border-gray-100 flex items-center gap-4">
                        <div class="bg-[#321270] p-2 rounded-lg text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-[#1e293b]">Informasi Tugas</h3>
                    </div>

                    <form action="{{ route('dosen.kelas-tugas.store', $kelas->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="px-8 py-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-gray-700">Assignment Title</label>
                                    <input type="text" name="judul" value="{{ old('judul') }}" required maxlength="255" placeholder="Contoh: Implementasi Stack dan Queue" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-gray-700">Subject/Class</label>
                                    <select name="kelas_id" class="w-full px-4 py-2 border border-gray-200 rounded-xl appearance-none bg-no-repeat bg-[right_1rem_center] focus:ring-2 focus:ring-blue-500 focus:outline-none transition" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%20fill%3D%22none%22%20stroke%3D%22currentColor%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C/polyline%3E%3C/svg%3E');">
                                        <option value="">Pilih Mata Kuliah</option>
                                        <option value="{{ $kelas->id }}">{{ $kelas->mataKuliah->nama_mk }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2 relative">
                                    <label class="text-sm font-bold text-gray-700">Deadline</label>
                                    <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-bold text-gray-700">Total Points</label>
                                    <input type="number" name="poin" value="{{ old('poin', 100) }}" required min="1" max="100" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700">Instructions</label>
                                <div class="border border-gray-200 rounded-xl overflow-hidden">
                                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200 flex gap-4 text-gray-600">
                                        <button type="button" class="hover:text-black font-serif font-bold">B</button>
                                        <button type="button" class="hover:text-black italic">I</button>
                                        <button type="button" class="hover:text-black underline">U</button>
                                        <span class="border-r border-gray-300"></span>
                                        <button type="button"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg></button>
                                        <button type="button"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.587-1.587a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></button>
                                    </div>
                                    <textarea name="instruksi" rows="5" required maxlength="5000" placeholder="Tuliskan detail instruksi pengerjaan tugas di sini..." class="w-full px-4 py-3 focus:outline-none resize-none">{{ old('instruksi') }}</textarea>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-bold text-gray-700">File Attachment Templates</label>
                                <div class="border-2 border-dashed border-gray-200 rounded-2xl p-8 flex flex-col items-center justify-center text-center hover:border-blue-400 transition cursor-pointer" onclick="document.getElementById('fileInput').click()">
                                    <div class="bg-blue-50 p-4 rounded-xl mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#321270]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-800">Klik atau seret file ke sini</p>
                                    <p class="text-xs text-gray-400 mt-1">PDF, DOCX, ZIP (Maksimal 25MB)</p>
                                    <input type="file" id="fileInput" name="template" accept=".pdf,.doc,.docx,.zip,.ppt,.pptx,.xls,.xlsx" class="hidden" onchange="updateTugasFileName(this)">
                                    <p id="tugasFileNameDisplay" class="text-sm text-emerald-600 font-medium mt-2 hidden"></p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-8 py-6 flex justify-end gap-3">
                            <button type="button" onclick="toggleModal('modalTugas')" class="px-6 py-2.5 rounded-lg border border-gray-200 text-gray-600 font-semibold hover:bg-gray-100 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#321270] text-white font-semibold flex items-center gap-2 hover:bg-opacity-90 transition">
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
    </div>

    <script>
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
            document.body.classList.toggle("overflow-hidden");
        }

        window.onclick = function(event) {
            let modal = document.getElementById('modalTugas');
            if (event.target == modal.firstElementChild) {
                toggleModal('modalTugas');
            }
        }

        function updateTugasFileName(input) {
            const display = document.getElementById('tugasFileNameDisplay');
            if (input.files.length > 0) {
                display.innerText = "Dipilih: " + input.files[0].name;
                display.classList.remove('hidden');
            } else {
                display.classList.add('hidden');
            }
        }
    </script>

@endsection
