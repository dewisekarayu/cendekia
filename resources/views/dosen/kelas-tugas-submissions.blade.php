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
        <p class="text-sm text-gray-500">
            {{ $submissions->where('status', \App\Models\PengumpulanTugas::STATUS_DINILAI)->count() }}/{{ $submissions->count() }} sudah dinilai
        </p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-400 text-xs border-b border-gray-100">
                    <th class="px-5 py-3 font-medium">Mahasiswa</th>
                    <th class="px-5 py-3 font-medium">Waktu Kumpul</th>
                    <th class="px-5 py-3 font-medium">File Jawaban</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Nilai</th>
                    <th class="px-5 py-3 font-medium text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($submissions as $p)
                    @php
                        $isLate = $p->is_late;
                        $sudahKumpul = $p->status !== \App\Models\PengumpulanTugas::STATUS_BELUM_DIKUMPUL;
                    @endphp
                    <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-800">{{ $p->mahasiswa->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $p->mahasiswa->nim ?? $p->mahasiswa->email ?? '' }}</p>
                        </td>
                        <td class="px-5 py-3 text-gray-500">
                            {{ $p->waktu_kumpul?->format('d M Y, H:i') ?? '-' }}
                            @if ($isLate)
                                <span class="block text-[10px] font-semibold text-red-600">Terlambat</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if ($p->file_jawaban)
                                <a href="{{ Storage::url($p->file_jawaban) }}" target="_blank" class="text-blue-700 hover:underline text-xs">Lihat File</a>
                            @else
                                <span class="text-gray-300 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if ($p->status === \App\Models\PengumpulanTugas::STATUS_DINILAI)
                                <span class="inline-block text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-1 rounded">Dinilai</span>
                            @elseif ($sudahKumpul)
                                <span class="inline-block text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-1 rounded">Menunggu Nilai</span>
                            @else
                                <span class="inline-block text-[10px] font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">Belum Kumpul</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 font-semibold text-gray-800">
                            {{ $p->nilai ?? '-' }}
                        </td>
                        <td class="px-5 py-3 text-right">
                            <button type="button" onclick="toggleModal('modalNilai{{ $p->id }}')"
                                class="inline-block text-xs font-medium text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition"
                                {{ !$sudahKumpul ? 'disabled' : '' }}>
                                {{ $p->status === \App\Models\PengumpulanTugas::STATUS_DINILAI ? 'Edit Nilai' : 'Beri Nilai' }}
                            </button>
                        </td>
                    </tr>

                    {{-- Modal Nilai per mahasiswa --}}
                    <div id="modalNilai{{ $p->id }}" class="fixed inset-0 z-50 hidden overflow-y-auto">
                        <div class="fixed inset-0 bg-black bg-opacity-50"></div>
                        <div class="relative min-h-screen flex items-center justify-center p-4">
                            <div class="relative bg-white w-full max-w-lg rounded-2xl shadow-xl overflow-hidden">
                                <div class="px-6 py-5 border-b border-gray-100">
                                    <h3 class="text-lg font-bold text-[#1e293b]">Nilai — {{ $p->mahasiswa->name ?? '-' }}</h3>
                                </div>
                                <form action="{{ route('dosen.tugas.nilai', [$kelas->id, $tugas->id, $p->id]) }}" method="POST">
                                    @csrf
                                    <div class="px-6 py-5 space-y-4">
                                        @if ($p->catatan)
                                            <div>
                                                <p class="text-xs font-bold text-gray-500 mb-1">Catatan Mahasiswa</p>
                                                <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $p->catatan }}</p>
                                            </div>
                                        @endif
                                        <div class="space-y-2">
                                            <label class="text-sm font-bold text-gray-700">Nilai (0-100)</label>
                                            <input type="number" name="nilai" min="0" max="100" required
                                                value="{{ old('nilai', $p->nilai) }}"
                                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-bold text-gray-700">Feedback</label>
                                            <textarea name="feedback_dosen" rows="3" maxlength="2000"
                                                placeholder="Catatan / masukan untuk mahasiswa..."
                                                class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:outline-none resize-none">{{ old('feedback_dosen', $p->feedback_dosen) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                                        <button type="button" onclick="toggleModal('modalNilai{{ $p->id }}')" class="px-5 py-2 rounded-lg border border-gray-200 text-gray-600 font-semibold hover:bg-gray-100 transition">Batal</button>
                                        <button type="submit" class="px-5 py-2 rounded-lg bg-[#321270] text-white font-semibold hover:bg-opacity-90 transition">Simpan Nilai</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-400 py-10 text-sm">Belum ada mahasiswa di kelas ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <script>
        function toggleModal(modalID) {
            document.getElementById(modalID).classList.toggle("hidden");
            document.body.classList.toggle("overflow-hidden");
        }
    </script>

@endsection