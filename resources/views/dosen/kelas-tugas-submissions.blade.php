@extends('layouts.portal')

@section('title', 'Penilaian Tugas')
@section('activeMenu', 'Kelas Saya')

@section('content')
    <div class="bg-[#321270] rounded-xl px-8 py-6 mb-6">
        <div class="flex items-center gap-2 mb-2">
            <span class="text-xs font-semibold bg-white/15 text-white px-2.5 py-1 rounded">{{ $kelas->mataKuliah->kode_mk ?? '-' }}</span>
            <span class="text-xs text-white/80">Bobot Nilai: {{ $tugas->bobot_nilai }}%</span>
        </div>
        <h1 class="text-xl font-bold text-white">{{ $tugas->judul }}</h1>
        <p class="text-sm text-blue-200 mt-1">
            Deadline: {{ $tugas->deadline->format('d M Y, H:i') }}
        </p>
    </div>

    <x-flash-message />

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('dosen.kelas-tugas', $kelas->id) }}" class="text-sm text-gray-500 hover:text-gray-800">&larr; Kembali ke Daftar Tugas</a>
        <p class="text-sm text-gray-500" id="ringkasanDinilai">
            {{ $submissions->where('status', \App\Models\PengumpulanTugas::STATUS_DINILAI)->count() }}/{{ $submissions->count() }} sudah dinilai
        </p>
    </div>

    {{-- Toolbar isi nilai serentak --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-5 py-4 mb-4 flex flex-wrap items-center gap-3">
        <span class="text-sm font-semibold text-gray-700">Isi nilai sama untuk semua:</span>
        <input type="number" id="nilaiSemuaInput" min="0" max="100" placeholder="0-100"
            class="w-24 px-3 py-1.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
        <button type="button" onclick="isiNilaiSemua()"
                class="text-xs font-semibold text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-lg transition">
            Isi ke Semua
        </button>

        <button type="button" onclick="isiNolBelumKumpul()"
                class="text-xs font-semibold text-red-700 bg-red-50 hover:bg-red-100 px-3 py-2 rounded-lg transition">
            Isi 0 untuk yang Belum Kumpul
        </button>

        <div class="ml-auto">
            <button type="submit" form="formNilaiSemua"
                    class="text-sm font-semibold text-white bg-[#321270] hover:bg-opacity-90 px-5 py-2.5 rounded-lg transition">
                Simpan Semua Nilai
            </button>
        </div>
    </div>

    <form id="formNilaiSemua" action="{{ route('dosen.tugas.nilai.bulk', [$kelas->id, $tugas->id]) }}" method="POST">
        @csrf

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 text-xs border-b border-gray-100">
                        <th class="px-5 py-3 font-medium">Mahasiswa</th>
                        <th class="px-5 py-3 font-medium">Waktu Kumpul</th>
                        <th class="px-5 py-3 font-medium">File Jawaban</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                        <th class="px-5 py-3 font-medium">Nilai</th>
                        <th class="px-5 py-3 font-medium text-right">Catatan / Simpan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($submissions as $p)
                        @php
                            $isLate = $p->is_late;
                            $sudahKumpul = $p->status !== \App\Models\PengumpulanTugas::STATUS_BELUM_DIKUMPUL;
                        @endphp
                        <tr class="border-b border-gray-50 last:border-0 {{ $sudahKumpul ? 'hover:bg-gray-50/50' : 'bg-gray-50/60' }}" id="row-{{ $p->id }}">
                            <td class="px-5 py-3">
                                <p class="font-medium {{ $sudahKumpul ? 'text-gray-800' : 'text-gray-500' }}">{{ $p->mahasiswa->name ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $p->mahasiswa->nim ?? $p->mahasiswa->email ?? '' }}</p>
                            </td>
                            <td class="px-5 py-3 text-gray-500">
                                {{ $p->waktu_kumpul?->format('d M Y, H:i') ?? '-' }}
                                @if ($isLate)
                                    <span class="block text-[10px] font-semibold text-red-600">Terlambat</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @if ($p->files->count() === 1)
                                    <a href="{{ Storage::url($p->files->first()->file_path) }}" target="_blank"
                                    class="inline-flex items-center gap-1.5 text-blue-700 hover:underline text-xs">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        <span class="truncate max-w-[130px]">{{ $p->files->first()->nama_asli ?? basename($p->files->first()->file_path) }}</span>
                                    </a>
                                @elseif ($p->files->count() > 1)
                                    <button type="button" onclick="toggleModal('modalFile{{ $p->id }}')"
                                            class="inline-flex items-center gap-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 px-2.5 py-1 rounded-md transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        {{ $p->files->count() }} File
                                    </button>
                                @else
                                    <span class="text-gray-300 text-xs italic">Belum ada file</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <span id="status-{{ $p->id }}" class="inline-block text-[10px] font-semibold px-2 py-1 rounded
                                    @if ($p->status === \App\Models\PengumpulanTugas::STATUS_DINILAI) text-emerald-700 bg-emerald-50
                                    @elseif ($sudahKumpul) text-amber-700 bg-amber-50
                                    @else text-gray-500 bg-gray-200 @endif">
                                    @if ($p->status === \App\Models\PengumpulanTugas::STATUS_DINILAI) Dinilai
                                    @elseif ($sudahKumpul) Menunggu Nilai
                                    @else Belum Kumpul @endif
                                </span>
                            </td>
                            <td class="px-5 py-3">
                                <input type="number" min="0" max="100"
                                    name="nilai[{{ $p->id }}]"
                                    id="nilai-{{ $p->id }}"
                                    data-status="{{ $sudahKumpul ? 'sudah' : 'belum' }}"
                                    data-nama="{{ $p->mahasiswa->name ?? 'mahasiswa ini' }}"
                                    value="{{ $p->nilai }}"
                                    placeholder="{{ $sudahKumpul ? '-' : 'blm kumpul' }}"
                                    class="nilai-input w-20 px-2 py-1.5 border rounded-lg text-sm font-semibold focus:ring-2 focus:ring-blue-500 focus:outline-none
                                        {{ $sudahKumpul ? 'border-gray-200 text-gray-800' : 'border-gray-200 text-gray-800 placeholder:text-gray-300 placeholder:italic placeholder:text-[11px]' }}">
                                <input type="hidden" name="feedback_dosen[{{ $p->id }}]" id="feedback-{{ $p->id }}" value="{{ $p->feedback_dosen }}">
                            </td>
                            <td class="px-5 py-3 text-right whitespace-nowrap">
                                @if ($sudahKumpul)
                                    <button type="button" onclick="openCatatan('{{ $p->id }}', {{ Js::from($p->catatan) }})"
                                            class="text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 px-2.5 py-1.5 rounded-md transition mr-1">
                                        Catatan
                                    </button>
                                @else
                                    <span class="invisible text-xs px-2.5 py-1.5 mr-1">Catatan</span>
                                @endif
                                <button type="button"
                                        data-url="{{ route('dosen.tugas.nilai', [$kelas->id, $tugas->id, $p->id]) }}"
                                        onclick="simpanSatu('{{ $p->id }}', this.dataset.url)"
                                        class="text-xs font-medium text-blue-900 bg-blue-50 hover:bg-blue-100 px-2.5 py-1.5 rounded-md transition">
                                    Simpan
                                </button>
                            </td>
                        </tr>

                        {{-- Modal Catatan (feedback + catatan mahasiswa) --}}
                        @if ($sudahKumpul)
                            <tr>
                                <td colspan="6" class="p-0 border-0">
                                    <div id="modalCatatan{{ $p->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
                                        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
                                        <div class="relative min-h-screen flex items-center justify-center p-4">
                                            <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden">
                                                <div class="px-6 py-5 border-b border-gray-100">
                                                    <h3 class="text-lg font-bold text-[#1e293b]">Catatan — {{ $p->mahasiswa->name ?? '-' }}</h3>
                                                </div>
                                                <div class="px-6 py-5 space-y-4">
                                                    @if ($p->catatan)
                                                        <div>
                                                            <p class="text-xs font-bold text-gray-500 mb-1">Catatan Mahasiswa</p>
                                                            <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $p->catatan }}</p>
                                                        </div>
                                                    @endif
                                                    <div class="space-y-2">
                                                        <label class="text-sm font-bold text-gray-700">Feedback untuk mahasiswa</label>
                                                        <textarea id="catatanTextarea{{ $p->id }}" rows="4" maxlength="2000"
                                                            placeholder="Catatan / masukan untuk mahasiswa..."
                                                            class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none">{{ $p->feedback_dosen }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                                                    <button type="button" onclick="toggleModal('modalCatatan{{ $p->id }}')" class="px-5 py-2 rounded-lg border border-gray-200 text-gray-600 font-semibold hover:bg-gray-100 transition">Batal</button>
                                                    <button type="button" onclick="terapkanCatatan('{{ $p->id }}')" class="px-5 py-2 rounded-lg bg-[#321270] text-white font-semibold hover:bg-opacity-90 transition">Terapkan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif

                        {{-- Modal Daftar File Jawaban --}}
                        @if ($p->files->count() > 1)
                            <tr>
                                <td colspan="6" class="p-0 border-0">
                                    <div id="modalFile{{ $p->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
                                        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
                                        <div class="relative min-h-screen flex items-center justify-center p-4">
                                            <div class="relative bg-white w-full max-w-md rounded-2xl shadow-xl overflow-hidden">
                                                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                                                    <h3 class="text-lg font-bold text-[#1e293b]">File Jawaban — {{ $p->mahasiswa->name ?? '-' }}</h3>
                                                    <button type="button" onclick="toggleModal('modalFile{{ $p->id }}')" class="text-gray-400 hover:text-gray-600">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <div class="px-6 py-5 space-y-2">
                                                    @foreach ($p->files as $file)
                                                        <a href="{{ Storage::url($file->file_path) }}" target="_blank"
                                                           class="flex items-center gap-3 border border-gray-100 rounded-xl px-4 py-3 hover:bg-gray-50 transition">
                                                            <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                                </svg>
                                                            </div>
                                                            <p class="flex-1 min-w-0 text-sm text-slate-700 truncate">{{ $file->nama_asli ?? basename($file->file_path) }}</p>
                                                            <span class="text-blue-600 text-xs font-semibold shrink-0">Lihat</span>
                                                        </a>
                                                    @endforeach
                                                </div>
                                                <div class="bg-gray-50 px-6 py-4 flex justify-end">
                                                    <button type="button" onclick="toggleModal('modalFile{{ $p->id }}')" class="px-5 py-2 rounded-lg border border-gray-200 text-gray-600 font-semibold hover:bg-gray-100 transition">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-400 py-10 text-sm">Belum ada mahasiswa di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </form>

    @push('scripts')
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
            ?? "{{ csrf_token() }}";

        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
            document.body.classList.toggle("overflow-hidden");
        }

        const SwalToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (el) => {
                el.addEventListener('mouseenter', Swal.stopTimer);
                el.addEventListener('mouseleave', Swal.resumeTimer);
            },
        });

        function showToast(message, type = 'success') {
            const iconMap = { success: 'success', warning: 'warning', error: 'error' };

            SwalToast.fire({
                icon: iconMap[type] ?? 'success',
                title: message,
            });
        }

        function isiNilaiSemua() {
            const nilai = document.getElementById('nilaiSemuaInput').value;

            if (nilai === '' || nilai < 0 || nilai > 100) {
                showToast('Isi angka 0-100 dulu ya.', 'error');
                return;
            }

            // hanya isi yang statusnya "sudah kumpul", biar ga ketimpa isi 0 yang belum kumpul
            const inputs = document.querySelectorAll('.nilai-input[data-status="sudah"]');

            if (inputs.length === 0) {
                showToast('Belum ada mahasiswa yang mengumpulkan tugas.', 'warning');
                return;
            }

            inputs.forEach(input => {
                input.value = nilai;
                input.classList.add('ring-2', 'ring-amber-400', 'bg-amber-50');
                setTimeout(() => input.classList.remove('ring-2', 'ring-amber-400', 'bg-amber-50'), 1000);
            });

            showToast(`Nilai ${nilai} diisi ke ${inputs.length} mahasiswa yang sudah kumpul. Jangan lupa klik "Simpan Semua Nilai".`, 'success');
        }

        async function isiNolBelumKumpul() {
            const inputs = document.querySelectorAll('.nilai-input[data-status="belum"]');

            if (inputs.length === 0) {
                showToast('Semua mahasiswa sudah mengumpulkan tugas.', 'warning');
                return;
            }

            const result = await Swal.fire({
                title: 'Isi nilai 0?',
                html: `Ini akan mengisi nilai <b>0</b> untuk <b>${inputs.length} mahasiswa</b> yang belum mengumpulkan tugas.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, isi 0',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                reverseButtons: true,
            });

            if (!result.isConfirmed) return;

            inputs.forEach(input => {
                input.value = 0;
                input.classList.add('ring-2', 'ring-red-400', 'bg-red-50');
                setTimeout(() => input.classList.remove('ring-2', 'ring-red-400', 'bg-red-50'), 1000);
            });

            showToast(`Nilai 0 diisi ke ${inputs.length} mahasiswa yang belum kumpul. Jangan lupa klik "Simpan Semua Nilai".`, 'warning');
        }

        function openCatatan(id, catatanMahasiswa) {
            toggleModal('modalCatatan' + id);
        }

        function terapkanCatatan(id) {
            const textareaVal = document.getElementById('catatanTextarea' + id).value;
            document.getElementById('feedback-' + id).value = textareaVal;
            toggleModal('modalCatatan' + id);
        }

        async function simpanSatu(id, url) {
            const input = document.getElementById('nilai-' + id);
            const nilai = input.value;
            const feedback = document.getElementById('feedback-' + id).value;
            const belumKumpul = input.dataset.status === 'belum';
            const nama = input.dataset.nama;

            if (nilai === '') {
                showToast('Isi nilai dulu sebelum simpan.', 'error');
                return;
            }

            if (belumKumpul) {
                const result = await Swal.fire({
                    title: 'Belum mengumpulkan tugas',
                    html: `<b>${nama}</b> belum mengumpulkan tugas ini. Yakin ingin menyimpan nilai <b>${nilai}</b>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, simpan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#321270',
                    cancelButtonColor: '#6b7280',
                    reverseButtons: true,
                });

                if (!result.isConfirmed) return;
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ nilai, feedback_dosen: feedback }),
            })
            .then(res => {
                if (!res.ok) throw new Error('Gagal menyimpan');
                return res.json();
            })
            .then(data => {
                const statusEl = document.getElementById('status-' + id);
                statusEl.textContent = 'Dinilai';
                statusEl.className = 'inline-block text-[10px] font-semibold px-2 py-1 rounded text-emerald-700 bg-emerald-50';
                showToast(`Nilai untuk ${nama} berhasil disimpan.`, 'success');
            })
            .catch(() => showToast('Gagal menyimpan nilai, coba lagi.', 'error'));
        }
    </script>
    @endpush

@endsection