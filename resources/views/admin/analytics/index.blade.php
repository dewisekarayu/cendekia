@extends('layouts.portal')

@section('title', 'Global Analytics (EWS)')

@section('content')
<div class="mb-6 rounded-2xl bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-5 sm:px-8 sm:py-6 relative overflow-hidden shadow-lg">
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-slate-400">Executive Dashboard</p>
            <h1 class="mt-1 text-xl sm:text-2xl font-extrabold text-white">Global Analytics & EWS</h1>
            <p class="mt-2 text-sm text-slate-300">Pantau performa akademik seluruh kampus dan deteksi mahasiswa berisiko tinggi secara *real-time*.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="px-4 py-2 bg-white/10 backdrop-blur-md rounded-xl text-center border border-white/10">
                <p class="text-[10px] uppercase font-bold text-slate-400">Total Mhs</p>
                <p class="text-xl font-black text-white">{{ $totalMahasiswa }}</p>
            </div>
            <div class="px-4 py-2 bg-rose-500/20 backdrop-blur-md rounded-xl text-center border border-rose-500/30">
                <p class="text-[10px] uppercase font-bold text-rose-300">High Risk</p>
                <p class="text-xl font-black text-rose-400">{{ $globalHighRisk }}</p>
            </div>
        </div>
    </div>
    <div class="absolute -right-6 -top-6 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute -left-12 -bottom-12 w-32 h-32 rounded-full bg-black/20 pointer-events-none"></div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 p-5">
        <h3 class="font-bold text-slate-800 dark:text-white mb-4">Distribusi Risiko Kampus</h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-xs font-bold mb-1">
                    <span class="text-rose-600">Kritis (High Risk)</span>
                    <span class="text-slate-600">{{ $totalMahasiswa > 0 ? round(($globalHighRisk / $totalMahasiswa) * 100) : 0 }}%</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-rose-500" style="width: {{ $totalMahasiswa > 0 ? ($globalHighRisk / $totalMahasiswa) * 100 : 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold mb-1">
                    <span class="text-amber-600">Waspada (Medium Risk)</span>
                    <span class="text-slate-600">{{ $totalMahasiswa > 0 ? round(($globalMediumRisk / $totalMahasiswa) * 100) : 0 }}%</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-500" style="width: {{ $totalMahasiswa > 0 ? ($globalMediumRisk / $totalMahasiswa) * 100 : 0 }}%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-xs font-bold mb-1">
                    <span class="text-emerald-600">Aman (Low Risk)</span>
                    <span class="text-slate-600">{{ $totalMahasiswa > 0 ? round(($globalLowRisk / $totalMahasiswa) * 100) : 0 }}%</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500" style="width: {{ $totalMahasiswa > 0 ? ($globalLowRisk / $totalMahasiswa) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-slate-50/50">
            <h3 class="font-bold text-sm text-slate-800">Distribusi Risiko Berdasarkan Program Studi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="text-left text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-4 py-3 font-bold">Program Studi</th>
                        <th class="px-4 py-3 font-bold text-center">Total Mhs</th>
                        <th class="px-4 py-3 font-bold text-center text-rose-600">High Risk</th>
                        <th class="px-4 py-3 font-bold text-center text-amber-600">Medium Risk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($prodiRiskMap as $prodiName => $stats)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-4 py-3 font-bold text-slate-700">{{ $prodiName }}</td>
                            <td class="px-4 py-3 text-center font-semibold text-slate-600">{{ $stats['total'] }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($stats['high'] > 0)
                                    <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-700 font-bold">{{ $stats['high'] }}</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($stats['medium'] > 0)
                                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 font-bold">{{ $stats['medium'] }}</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
    <div class="p-5 border-b border-rose-100 bg-rose-50 flex items-center justify-between">
        <h3 class="font-bold text-rose-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            Top 10 Mahasiswa Kritis (Pengawasan Khusus)
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-white text-[11px] font-bold uppercase tracking-wide text-gray-400 border-b border-gray-100">
                    <th class="px-5 py-3 text-left w-12">No</th>
                    <th class="px-5 py-3 text-left">Mahasiswa & Prodi</th>
                    <th class="px-5 py-3 text-center">Kehadiran</th>
                    <th class="px-5 py-3 text-center">Rata Nilai</th>
                    <th class="px-5 py-3 text-center">Tugas Bolong</th>
                    <th class="px-5 py-3 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($topAtRiskStudents as $data)
                    <tr class="hover:bg-rose-50/30 transition-colors">
                        <td class="px-5 py-4 text-xs font-bold text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-5 py-4">
                            <div class="font-bold text-slate-800 text-sm">{{ $data->mahasiswa->name }}</div>
                            <div class="text-[11px] text-gray-500">{{ $data->mahasiswa->nip_nim }} &middot; {{ $data->prodi }}</div>
                        </td>
                        <td class="px-5 py-4 text-center font-bold {{ $data->attendance_rate < 75 ? 'text-rose-600' : 'text-slate-600' }}">
                            {{ $data->attendance_rate }}%
                        </td>
                        <td class="px-5 py-4 text-center font-bold {{ $data->avg_score < 60 ? 'text-amber-600' : 'text-slate-600' }}">
                            {{ $data->avg_score ?: '-' }}
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($data->missed > 0)
                                <span class="px-2 py-1 rounded bg-rose-100 text-rose-700 text-xs font-bold">{{ $data->missed }}</span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            @if($data->risk_score >= 70)
                                <span class="px-3 py-1 rounded-full bg-rose-500 text-white text-[10px] font-bold uppercase shadow-sm">High Risk</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-amber-400 text-white text-[10px] font-bold uppercase shadow-sm">Warning</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-gray-500 text-sm">
                            Kondisi kampus stabil. Tidak ada mahasiswa dengan risiko tinggi yang mendesak.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
