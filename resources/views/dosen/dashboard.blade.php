@extends('layouts.portal')

@section('title', 'Dashboard')
@section('activeMenu', 'Dashboard')

@section('content')

    <!-- Welcome Banner -->
    <div class="bg-blue-900 rounded-xl px-8 py-6 relative overflow-hidden mb-8">
        <div class="relative z-10">
            <h1 class="text-xl font-bold text-white">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="text-blue-200 text-sm mt-1 max-w-md">
                You have {{ $kelasList->count() }} classes this semester and {{ $tugasPerluDinilai }} assignments waiting to be graded.
            </p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute -right-4 -bottom-6 w-32 h-32 text-blue-800 opacity-60" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 14l9-5-9-5-9 5 9 5z"/>
            <path d="M12 14l6.16-3.42A12.02 12.02 0 0112 21.5a12.02 12.02 0 01-6.16-10.92L12 14z"/>
        </svg>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-lg bg-blue-50 flex items-center justify-center text-blue-900 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Classes Teaching</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $kelasList->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Assignments to Grade</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $tugasPerluDinilai }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Students</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalMahasiswa }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- My Classes -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">My Classes</h2>
            <a href="{{ route('dosen.kelas-saya') }}" class="text-sm font-medium text-blue-900 hover:underline">View All →</a>
        </div>

        @php
            $topColors = ['bg-blue-900', 'bg-amber-400', 'bg-emerald-500', 'bg-indigo-500'];
        @endphp

        @if ($kelasList->isEmpty())
            <div class="bg-white rounded-xl border border-gray-100 p-8 text-center shadow-sm">
                <p class="text-gray-500 text-sm">Belum ada kelas yang diampu. Data kelas akan muncul di sini setelah ditambahkan oleh Admin.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($kelasList as $i => $kelas)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="h-1.5 {{ $topColors[$i % count($topColors)] }}"></div>
                        <div class="p-5">
                            <span class="inline-block text-[10px] font-semibold tracking-wide text-blue-900 bg-blue-50 px-2 py-0.5 rounded mb-2">
                                {{ $kelas->mataKuliah?->programStudi?->nama_prodi ?? 'Umum' }}
                            </span>
                            <h3 class="font-semibold text-gray-800">{{ $kelas->mataKuliah?->nama_mk ?? '-' }}</h3>

                            <div class="mt-3 space-y-1.5 text-xs text-gray-500">
                                <div class="flex items-center gap-1.5">
                                    {{ $kelas->kode_kelas }}
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $kelas->hari }}, {{ substr($kelas->jam_mulai, 0, 5) }}
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z" />
                                    </svg>
                                    {{ $kelas->mahasiswa->count() }} Students enrolled
                                </div>
                            </div>

                            <a href="{{ route('dosen.kelas-detail', $kelas->id) }}" class="mt-4 block text-center bg-blue-900 hover:bg-blue-800 text-white text-sm font-medium py-2 rounded-lg transition">
                                Manage Class
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Recent Submissions -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-base font-bold text-gray-800">Recent Submissions</h2>
        </div>

        @if ($submissions->isEmpty())
            <div class="p-8 text-center">
                <p class="text-gray-500 text-sm">Belum ada tugas yang dikumpulkan mahasiswa.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 text-xs border-b border-gray-100">
                        <th class="px-5 py-3 font-medium">Student</th>
                        <th class="px-5 py-3 font-medium">Assignment</th>
                        <th class="px-5 py-3 font-medium">Timestamp</th>
                        <th class="px-5 py-3 font-medium text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($submissions as $item)
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-blue-900 flex items-center justify-center text-white text-xs font-semibold shrink-0">
                                        {{ substr($item->mahasiswa?->name ?? '-', 0, 1) }}
                                    </div>
                                    <p class="font-medium text-gray-800">{{ $item->mahasiswa?->name ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <p class="font-medium text-blue-900">{{ $item->tugas?->judul ?? '-' }}</p>
                                <p class="text-xs text-gray-400">{{ $item->tugas?->kelasPerkuliahan?->mataKuliah?->nama_mk ?? '-' }}</p>
                            </td>
                            <td class="px-5 py-3 text-gray-500">
                                {{ $item->waktu_kumpul?->diffForHumans() ?? '-' }}
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="#" class="inline-block text-xs font-medium text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition">
                                    Grade Now
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection