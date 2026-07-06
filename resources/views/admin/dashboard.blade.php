@extends('layouts.portal')

@section('title', 'Dashboard Admin')
@section('activeMenu', 'Dashboard')

@section('content')

    <!-- Welcome Banner -->
    <div class="bg-blue-900 rounded-xl px-8 py-6 relative overflow-hidden mb-8">
        <div class="relative z-10">
            <h1 class="text-xl font-bold text-white">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="text-blue-200 text-sm mt-1 max-w-md">
                Kelola seluruh data akademik dan pantau aktivitas sistem dari sini.
            </p>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute -right-4 -bottom-6 w-32 h-32 text-blue-800 opacity-60" fill="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" fill="none" stroke="currentColor" stroke-width="1"/>
        </svg>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-900 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.42A12.02 12.02 0 0112 21.5a12.02 12.02 0 01-6.16-10.92L12 14z" />
                </svg>
            </div>
            <p class="text-sm text-gray-500">Total Dosen</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalDosen }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z" />
                </svg>
            </div>
            <p class="text-sm text-gray-500">Total Mahasiswa</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalMahasiswa }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5" />
                </svg>
            </div>
            <p class="text-sm text-gray-500">Program Studi</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalProdi }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <p class="text-sm text-gray-500">Mata Kuliah</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalMataKuliah }}</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm">
            <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <p class="text-sm text-gray-500">Kelas Aktif</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalKelasAktif }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Program Studi Overview -->
        <div class="lg:col-span-2">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Program Studi</h2>

            @if ($prodiList->isEmpty())
                <div class="bg-white rounded-xl border border-gray-100 p-8 text-center shadow-sm">
                    <p class="text-gray-500 text-sm mb-3">Belum ada program studi.</p>
                    <a href="{{ route('admin.program-studi.create') }}" class="text-sm font-medium text-blue-900 hover:underline">+ Tambah Program Studi</a>
                </div>
            @else
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-gray-400 text-xs border-b border-gray-100">
                                <th class="px-5 py-3 font-medium">Program Studi</th>
                                <th class="px-5 py-3 font-medium">Mata Kuliah</th>
                                <th class="px-5 py-3 font-medium text-right">Mahasiswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($prodiList as $prodi)
                                <tr class="border-b border-gray-50 last:border-0">
                                    <td class="px-5 py-3">
                                        <p class="font-medium text-gray-800">{{ $prodi->nama_prodi }}</p>
                                        <p class="text-xs text-gray-400">{{ $prodi->kode_prodi }} • {{ $prodi->jenjang }}</p>
                                    </td>
                                    <td class="px-5 py-3 text-gray-500">{{ $prodi->mata_kuliah_count }}</td>
                                    <td class="px-5 py-3 text-right text-gray-500">{{ $mahasiswaPerProdi[$prodi->id] ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Recent Users -->
        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-4">Pengguna Terbaru</h2>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm divide-y divide-gray-50">
                @forelse ($recentUsers as $user)
                    <div class="p-4 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-900 text-white flex items-center justify-center text-xs font-semibold shrink-0">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ $user->getRoleNames()->first() ?? '-' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="p-4 text-sm text-gray-400">Belum ada pengguna.</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection