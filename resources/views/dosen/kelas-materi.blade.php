@extends('layouts.portal')

@section('title', $kelas->mataKuliah->nama_mk ?? 'Materi Kelas')
@section('activeMenu', 'Kelas Saya')

@section('content')

    <div class="bg-[#321270] rounded-xl px-8 py-6 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
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

    @php
        $tabLinks = [
            'Beranda' => route('dosen.kelas-detail', $kelas->id),
            'Materi' => route('dosen.kelas-materi', $kelas->id),
            'Tugas & Proyek' => route('dosen.kelas-tugas', $kelas->id),
            'Pengumuman' => route('dosen.kelas-pengumuman.index', $kelas->id),
            'Penilaian' => route('dosen.gradebook', ['kelas_id' => $kelas->id]),
        ];
        $activeTab = 'Materi';
    @endphp
    <div class="flex items-center gap-6 border-b border-gray-200 mb-6 overflow-x-auto">
        @foreach ($tabLinks as $label => $url)
            <a href="{{ $url }}" class="pb-3 text-sm font-medium whitespace-nowrap transition
                {{ $label === $activeTab ? 'text-[#321270] border-b-2 border-[#321270]' : 'text-gray-500 hover:text-gray-700' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-800">Materi</h2>
                    <button onclick="toggleMateriModal('modalMateri')" class="text-xs font-semibold bg-[#321270] text-white px-3 py-2 rounded-lg hover:bg-[#321270]/90 transition flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Materi
                    </button>
                </div>

                @if ($materiList->isEmpty())
                    <div class="text-sm text-gray-500 py-4">Belum ada materi untuk kelas ini.</div>
                @else
                    <ul class="space-y-3">
                        @foreach($materiList as $m)
                            <li class="p-4 border rounded-lg">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-800">Pertemuan {{ $m->pertemuan_ke }} - {{ $m->judul }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($m->deskripsi, 200) }}</div>
                                    </div>
                                    <div class="text-right">
                                        @if($m->file_path)
                                            <a href="{{ route('mahasiswa.materi.buka', [$kelas->id, $m->id]) }}" target="_blank" rel="noopener" class="text-sm px-3 py-1 bg-[#321270] text-white rounded">Buka</a>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 mb-2">Deskripsi Mata Kuliah</h3>
                <p class="text-sm text-gray-600">{{ $kelas->mataKuliah->deskripsi ?? 'Belum ada deskripsi untuk mata kuliah ini.' }}</p>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Class Statistics</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                            <span>Total Mahasiswa</span>
                            <span class="font-semibold text-gray-800">{{ $kelas->mahasiswa->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalMateri" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="fixed inset-0 bg-black bg-opacity-40 transition-opacity"></div>

        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white w-full max-w-3xl rounded-2xl shadow-xl overflow-hidden">
                
                <div class="px-8 py-5 bg-gray-50/50 border-b border-gray-100 flex items-center gap-3">
                    <div class="bg-[#1e293b] p-2 rounded-lg text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-[#1e293b]">Informasi Materi</h3>
                </div>

                <form action="{{ route('dosen.kelas-materi.store', $kelas->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="px-8 py-6 space-y-5">
                        
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700">Judul Materi</label>
                            <input type="text" name="judul" value="{{ old('judul') }}" required maxlength="255" placeholder="Contoh: Pengenalan Struktur Data Lanjut" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition placeholder-gray-300">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-sm font-bold text-slate-700">Kategori</label>
                                <div class="relative">
                                    <select name="kategori" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm appearance-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition text-gray-500 bg-white">
                                        <option value="">Pilih Kategori</option>
                                        <option value="Teori">Teori</option>
                                        <option value="Praktikum">Praktikum</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-bold text-slate-700">Pertemuan Ke-</label>
                                <div class="relative">
                                    <select name="pertemuan_ke" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm appearance-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none transition text-gray-500 bg-white">
                                        <option value="">Pilih Minggu</option>
                                        @for ($i = 1; $i <= 16; $i++)
                                            <option value="{{ $i }}" @selected((int) old('pertemuan_ke') === $i)>Minggu Ke-{{ $i }}</option>
                                        @endfor
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700">Deskripsi Materi</label>
                            <textarea name="deskripsi" rows="4" maxlength="5000" placeholder="Berikan penjelasan singkat mengenai materi ini..." class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:outline-none resize-none transition placeholder-gray-300">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-slate-700">Unggah File</label>
                            <div class="border-2 border-dashed border-gray-200 rounded-2xl p-8 flex flex-col items-center justify-center text-center hover:border-blue-400 transition cursor-pointer bg-slate-50/50" onclick="document.getElementById('materiFileInput').click()">
                                <div class="bg-blue-50 p-3 rounded-xl mb-3 text-[#321270]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-800">Seret dan lepas file di sini</p>
                                <p class="text-xs text-gray-400 mt-0.5">atau <span class="text-blue-600 underline font-medium">pilih file</span> dari komputer Anda</p>
                                <p class="text-[11px] text-gray-400 mt-3">PDF, PPT, DOCX, atau MP4 (Maksimal 100MB)</p>
                                <input type="file" id="materiFileInput" name="file_materi" class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp4,.zip" onchange="updateFileName(this)">
                                <p id="fileNameDisplay" class="text-sm text-emerald-600 font-medium mt-2 hidden"></p>
                            </div>
                        </div>

                    </div>

                    <div class="bg-gray-50/70 px-8 py-4 border-t border-gray-100 flex justify-end gap-3">
                        <button type="button" onclick="toggleMateriModal('modalMateri')" class="px-5 py-2 rounded-lg border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-100 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-lg bg-[#0f172a] text-sm font-semibold text-white hover:bg-opacity-90 transition">
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
            if(input.files.length > 0) {
                display.innerText = "Dipilih: " + input.files[0].name;
                display.classList.remove('hidden');
            } else {
                display.classList.add('hidden');
            }
        }
    </script>

@endsection
