@extends('layouts.portal')

@section('title', 'Tugas & Proyek')
@section('activeMenu', 'Kelas Saya')

@section('content')

    <!-- Class Header Banner -->
    <div class="bg-blue-900 rounded-xl px-8 py-6 mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xs font-semibold bg-white/15 text-white px-2.5 py-1 rounded">{{ $kelas->mataKuliah->kode_mk ?? '-' }}</span>
                <span class="text-xs text-blue-200">Semester {{ $kelas->semester->nama_semester ?? '-' }}</span>
            </div>
            <h1 class="text-xl font-bold text-white">{{ $kelas->mataKuliah->nama_mk ?? '-' }}</h1>
            <p class="text-sm text-blue-200 mt-1">
                {{ $kelas->hari }}, {{ $kelas->jam_mulai }} - {{ $kelas->jam_selesai }} • {{ $kelas->ruangan }}
            </p>
        </div>
        <a href="#" class="shrink-0 bg-white text-blue-900 text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-blue-50 transition text-center">
            Start Virtual Class
        </a>
    </div>

    <!-- Tabs -->
    @php
        $tabLinks = [
            'Beranda' => route('dosen.kelas-detail', $kelas->id),
            'Materi' => '#',
            'Tugas & Proyek' => route('dosen.kelas-tugas', $kelas->id),
            'Forum Diskusi' => '#',
            'Peserta' => '#',
            'Penilaian' => '#',
        ];
        $activeTab = 'Tugas & Proyek';
    @endphp
    <div class="flex items-center gap-6 border-b border-gray-200 mb-6 overflow-x-auto">
        @foreach ($tabLinks as $label => $url)
            <a href="{{ $url }}" class="pb-3 text-sm font-medium whitespace-nowrap transition
                {{ $label === $activeTab ? 'text-blue-900 border-b-2 border-blue-900' : 'text-gray-500 hover:text-gray-700' }}">
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
                {{ \App\Models\PengumpulanTugas::whereIn('tugas_id', $tugasList->pluck('id'))->where('status', 'dikumpulkan')->count() }}
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
        <a href="#" class="inline-flex items-center gap-2 bg-blue-900 hover:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            Create New Assignment
        </a>
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
                                <a href="#" class="inline-block text-xs font-medium text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition">
                                    View Submissions
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection