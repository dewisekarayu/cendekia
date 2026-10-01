@extends('layouts.portal')

@section('title', 'Self-Analytics & EWS')

@section('content')
<div class="mb-6 rounded-2xl bg-gradient-to-r from-blue-700 to-indigo-800 px-6 py-5 sm:px-8 sm:py-6 relative overflow-hidden shadow-lg">
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-wide text-blue-200/70">Early Warning System Mahasiswa</p>
            <h1 class="mt-1 text-xl sm:text-2xl font-extrabold text-white">Self-Analytics EWS</h1>
            <p class="mt-2 text-sm text-blue-100/70">Pantau performa akademik Anda secara mandiri agar terhindar dari risiko gagal mata kuliah.</p>
        </div>
    </div>
    <div class="absolute -right-6 -top-6 w-48 h-48 rounded-full bg-white/5 pointer-events-none"></div>
    <div class="absolute -left-12 -bottom-12 w-32 h-32 rounded-full bg-black/10 pointer-events-none"></div>
</div>

{{-- Global Status Cards --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $globalAttendance < 75 ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase">Rata-rata Kehadiran</p>
            <h3 class="text-xl font-black text-slate-800 dark:text-white">{{ $globalAttendance }}%</h3>
        </div>
    </div>
    
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $globalAvgScore < 60 ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase">Rata-rata Nilai Tugas</p>
            <h3 class="text-xl font-black text-slate-800 dark:text-white">{{ $globalAvgScore ?: '-' }}</h3>
        </div>
    </div>
    
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $globalMissed > 0 ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-bold text-gray-400 uppercase">Tugas Terlewat</p>
            <h3 class="text-xl font-black text-slate-800 dark:text-white">{{ $globalMissed }} <span class="text-sm font-normal text-gray-500">tugas</span></h3>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200/80 dark:border-slate-700 overflow-hidden">
    <div class="p-5 border-b border-gray-100 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
        <h3 class="font-bold text-slate-800 dark:text-white">Prediksi Risiko Per Kelas</h3>
    </div>
    
    <div class="divide-y divide-gray-100 dark:divide-slate-700">
        @forelse($analyticsPerClass as $data)
            <div class="p-5 hover:bg-slate-50/50 transition-colors flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                        <h4 class="font-extrabold text-slate-800 dark:text-white text-base">{{ $data->kelas->mataKuliah->nama_mk }}</h4>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border {{ $data->status_color }}">
                            {{ $data->status_label }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500">{{ $data->kelas->kode_kelas }} &middot; Dosen: {{ $data->kelas->dosen->name }}</p>
                    
                    @if(count($data->reasons) > 0)
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach($data->reasons as $reason)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded bg-rose-50 text-rose-600 text-[10px] font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                      <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $reason }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
                
                <div class="flex items-center gap-6 md:w-auto w-full md:border-l border-gray-200 md:pl-6">
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Kehadiran</p>
                        <p class="font-black text-lg {{ $data->attendance_rate < 75 ? 'text-rose-600' : 'text-emerald-600' }}">{{ $data->attendance_rate }}%</p>
                    </div>
                    <div class="text-center">
                        <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Rata Nilai</p>
                        <p class="font-black text-lg {{ $data->avg_score < 60 ? 'text-amber-600' : 'text-slate-700' }}">{{ $data->avg_score ?: '-' }}</p>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-12 text-center text-gray-500">
                <p>Anda belum terdaftar di kelas manapun semester ini.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
