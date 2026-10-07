@extends('layouts.portal')

@section('title', 'Early Warning System (EWS) Analytics')
@section('activeMenu', 'Early Warning System')

@section('content')
<div class="mb-6 rounded-2xl bg-gradient-to-br from-[#260c5a] via-[#3a1480] to-[#511da8] dark:from-slate-900 dark:via-indigo-950 dark:to-purple-950 px-6 py-5 sm:px-8 sm:py-6 relative overflow-hidden shadow-lg">
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-purple-200/70">Dashboard Analytics & Prediksi AI</p>
            <h1 class="mt-1 text-xl sm:text-2xl font-extrabold text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Early Warning System
            </h1>
            <p class="mt-2 text-sm text-purple-100/70">Mendeteksi mahasiswa yang berisiko tidak lulus atau tertinggal berdasarkan absensi dan nilai tugas.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-white/10 backdrop-blur-md rounded-xl text-center border border-white/20">
                <p class="text-[10px] uppercase font-bold text-purple-200">Total Mahasiswa</p>
                <p class="text-xl font-black text-white">{{ $totalStudents }}</p>
            </div>
            <div class="px-4 py-2 bg-rose-500/30 backdrop-blur-md rounded-xl text-center border border-rose-400/30">
                <p class="text-[10px] uppercase font-bold text-rose-200">Berisiko (High)</p>
                <p class="text-xl font-black text-white">{{ $atRiskCount }}</p>
            </div>
        </div>
    </div>
    <div class="absolute -right-6 -top-6 w-48 h-48 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
    <div class="absolute -left-12 -bottom-12 w-32 h-32 rounded-full bg-purple-400/10 blur-xl pointer-events-none"></div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700 overflow-hidden"
     x-data="{
         search: '',
         filterKelas: '',
         filterRisiko: '',
         visible: {{ count($analytics) }},
         matches(text, kelasArray, risiko) {
             const q = this.search.toLowerCase().trim();
             let matchSearch = !q || text.indexOf(q) !== -1;
             
             let matchKelas = true;
             if (this.filterKelas !== '') {
                 matchKelas = kelasArray.includes(this.filterKelas);
             }
             
             let matchRisiko = true;
             if (this.filterRisiko !== '') {
                 matchRisiko = risiko === this.filterRisiko;
             }
             
             return matchSearch && matchKelas && matchRisiko;
         },
         recount() {
             this.$nextTick(() => {
                 this.visible = this.$refs.tbody.querySelectorAll('tr[data-row]:not([style*=\'display: none\'])').length;
             });
         }
     }"
     x-init="$watch('search', () => recount()); $watch('filterKelas', () => recount()); $watch('filterRisiko', () => recount())">

    {{-- Header + pencarian --}}
    <div class="p-5 border-b border-gray-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 space-y-4">
        <div class="flex justify-between items-center">
            <h3 class="font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Hasil Prediksi Performa Mahasiswa
            </h3>
            <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                <span x-text="visible"></span> / {{ count($analytics) }} mahasiswa
            </span>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" x-model="search"
                       placeholder="Cari nama atau NIM mahasiswa..."
                       class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2 pl-9 text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:border-[#321270] focus:ring-1 focus:ring-[#321270] transition">
                <svg class="absolute left-3 top-2.5 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <button type="button" x-show="search" x-cloak @click="search = ''"
                        class="absolute right-3 top-2 text-slate-400 hover:text-slate-600 text-sm">&times;</button>
            </div>
            <div class="w-full sm:w-48">
                <select x-model="filterKelas" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2 text-xs text-slate-800 dark:text-white focus:border-[#321270] focus:ring-1 focus:ring-[#321270] transition">
                    <option value="">Semua Kelas</option>
                    @foreach($availableClasses as $kelasName)
                        <option value="{{ $kelasName }}">{{ $kelasName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-48">
                <select x-model="filterRisiko" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-3.5 py-2 text-xs text-slate-800 dark:text-white focus:border-[#321270] focus:ring-1 focus:ring-[#321270] transition">
                    <option value="">Semua Risiko</option>
                    <option value="Low">Rendah (Low)</option>
                    <option value="Medium">Menengah (Medium)</option>
                    <option value="High">Tinggi (High)</option>
                </select>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50/70 dark:bg-slate-900/30 text-[11px] font-bold uppercase tracking-wide text-gray-400 dark:text-slate-500">
                    <th class="px-5 py-3 text-left w-12">No</th>
                    <th class="px-5 py-3 text-left">Mahasiswa</th>
                    <th class="px-5 py-3 text-left">Kelas</th>
                    <th class="px-5 py-3 text-center">Kehadiran</th>
                    <th class="px-5 py-3 text-center">Avg. Nilai</th>
                    <th class="px-5 py-3 text-left">Alasan Berisiko</th>
                    <th class="px-5 py-3 text-center">Level Risiko</th>
                </tr>
            </thead>
            <tbody x-ref="tbody" class="divide-y divide-gray-50 dark:divide-slate-700/50">
                @forelse($analytics as $data)
                    @php
                        $searchText = strtolower($data->mahasiswa->name . ' ' . $data->mahasiswa->nip_nim);
                    @endphp
                    <tr data-row
                        x-show="matches(@js($searchText), @js($data->kelas_array), @js($data->risk_level))"
                        class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors">
                        <td class="px-5 py-4 text-xs font-bold text-gray-400">{{ $loop->iteration }}</td>
                        <td class="px-5 py-4">
                            <div class="font-bold text-slate-800 dark:text-white text-sm">{{ $data->mahasiswa->name }}</div>
                            <div class="text-[11px] text-gray-500">{{ $data->mahasiswa->nip_nim }}</div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-[11px] text-slate-600 dark:text-slate-400">{{ $data->kelas_string }}</div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <div class="flex flex-col items-center gap-1">
                                <span class="text-xs font-bold {{ $data->attendance_rate < 75 ? 'text-rose-600' : 'text-slate-700 dark:text-slate-300' }}">
                                    {{ $data->attendance_rate }}%
                                </span>
                                <div class="w-16 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full {{ $data->attendance_rate < 75 ? 'bg-rose-500' : 'bg-emerald-500' }}" style="width: {{ $data->attendance_rate }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="font-bold {{ $data->avg_score < 60 ? 'text-rose-600' : 'text-slate-700 dark:text-slate-300' }}">
                                {{ $data->avg_score ?: '-' }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            @if(count($data->reasons) > 0)
                                <ul class="list-disc list-inside text-[11px] text-gray-500 space-y-1">
                                    @foreach($data->reasons as $reason)
                                        <li>{{ $reason }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-[11px] text-gray-400 italic">Performa stabil</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] uppercase tracking-wider {{ $data->color }}">
                                @if($data->risk_level == 'High')
                                    <span class="relative flex h-2 w-2">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                    </span>
                                @endif
                                {{ $data->risk_level }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-600 mb-1">Tidak ada data mahasiswa</p>
                            <p class="text-xs text-gray-500">Belum ada mahasiswa yang tergabung di kelas Anda.</p>
                        </td>
                    </tr>
                @endforelse

                {{-- Hasil pencarian kosong --}}
                @if(count($analytics) > 0)
                    <tr x-show="visible === 0" x-cloak>
                        <td colspan="7" class="px-5 py-10 text-center">
                            <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">Tidak ada mahasiswa yang cocok</p>
                            <p class="text-xs text-gray-500 mt-1">Coba ubah kata kunci pencarian.</p>
                            <button type="button" @click="search = ''"
                                    class="mt-3 text-xs font-bold text-[#321270] dark:text-purple-300 hover:underline">Reset pencarian</button>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
