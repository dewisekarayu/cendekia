@extends('layouts.portal')

@section('title', 'Dashboard Dosen')
@section('activeMenu', 'Dashboard')

@section('content')

@php
    $hour = now()->hour;
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $topColors = ['bg-blue-600', 'bg-purple-600', 'bg-emerald-600', 'bg-amber-500', 'bg-indigo-600', 'bg-rose-500'];
@endphp

<div class="space-y-6">

    {{-- HERO SECTION --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#260c5a] via-[#3a1480] to-[#511da8] dark:from-slate-900 dark:via-indigo-950 dark:to-purple-950 px-6 py-7 sm:px-8 shadow-md text-white">
        <div class="pointer-events-none absolute -right-12 -top-12 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -left-10 -bottom-10 h-36 w-36 rounded-full bg-purple-400/10 blur-xl"></div>
        
        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0 flex-1">
                <div class="mb-2 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-lg border border-white/20 bg-white/15 px-3 py-1 text-xs font-bold tracking-wide text-white backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </span>
                    <span class="rounded-lg border border-white/10 bg-black/20 px-2.5 py-1 text-xs font-medium text-purple-100 backdrop-blur-sm">
                        Semester Ganjil 2025/2026
                    </span>
                </div>

                <h1 class="text-2xl font-black text-white sm:text-3xl tracking-tight leading-tight">
                    {{ $greeting }}, {{ auth()->user()->name }}
                </h1>

                {{-- Smart Overview Box --}}
                <div id="ai_insight_container" class="mt-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl p-4 flex items-start gap-3.5 max-w-2xl shadow-sm">
                    <div class="bg-purple-300/20 p-2 rounded-lg shrink-0 mt-0.5 text-purple-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] font-bold text-purple-200 uppercase tracking-widest mb-1">Cendekia AI Insight</p>
                        <p id="ai_insight_text" class="text-xs sm:text-sm font-medium leading-relaxed text-purple-50">
                            Selamat datang di portal akademik. Anda mengampu <strong class="text-white">{{ $kelasList->count() }} kelas aktif</strong> dengan total <strong class="text-white">{{ $totalMahasiswa }} mahasiswa</strong>. 
                            @if($tugasPerluDinilai > 0)
                                Ada <strong class="text-amber-300">{{ $tugasPerluDinilai }} tugas</strong> mahasiswa yang siap untuk dievaluasi.
                            @else
                                Seluruh evaluasi tugas mahasiswa telah terselesaikan dengan baik.
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Right Quick Actions in Hero --}}
            <div class="flex flex-row lg:flex-col gap-2.5 shrink-0">
                <a href="{{ route('dosen.schedule') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 hover:bg-white/20 px-4 py-2.5 text-xs font-bold text-white backdrop-blur-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Jadwal Mengajar
                </a>
                <a href="{{ route('dosen.ai-assistant') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 hover:bg-white/20 px-4 py-2.5 text-xs font-bold text-white backdrop-blur-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                    Asisten AI Dosen
                </a>
                <a href="{{ route('dosen.kelas-pengumuman.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 hover:bg-white/20 px-4 py-2.5 text-xs font-bold text-white backdrop-blur-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    Buat Pengumuman
                </a>
            </div>
        </div>
    </div>

    {{-- JADWAL HARI INI ALERT (IF ANY) --}}
    @if(isset($kelasHariIni) && $kelasHariIni->isNotEmpty())
        <div class="rounded-2xl border border-purple-200/80 dark:border-purple-800/40 bg-gradient-to-r from-purple-50/90 to-indigo-50/70 dark:from-purple-950/40 dark:to-slate-800 p-4 sm:p-5 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#321270] text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-[#321270] dark:text-purple-300">Agenda Hari Ini ({{ $todayName }})</p>
                        <p class="text-sm font-bold text-slate-800 dark:text-white">
                            Anda memiliki {{ $kelasHariIni->count() }} sesi kelas perkuliahan terjadwal hari ini.
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($kelasHariIni as $kh)
                        <a href="{{ route('dosen.kelas-detail', $kh->id) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-white dark:bg-slate-700 px-3 py-1.5 text-xs font-bold text-slate-800 dark:text-white border border-slate-200/80 dark:border-slate-600 hover:border-purple-400 shadow-sm transition">
                            <span>{{ $kh->mataKuliah?->nama_mk ?? 'Kelas' }}</span>
                            <span class="text-purple-600 dark:text-purple-400">({{ substr($kh->jam_mulai, 0, 5) }})</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- STATS SUMMARY GRID CARDS --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        {{-- Total Kelas Diampu --}}
        <a href="{{ route('dosen.kelas-saya') }}" class="group flex items-center justify-between rounded-2xl border border-slate-200/70 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm hover:border-purple-300 dark:hover:border-purple-600 hover:shadow-md transition">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-purple-100 dark:bg-purple-950/60 text-[#321270] dark:text-purple-300 group-hover:scale-105 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 dark:text-slate-500">Total Kelas Diampu</p>
                    <p class="text-2xl font-black text-slate-800 dark:text-white mt-0.5">{{ $kelasList->count() }}</p>
                    <p class="text-[11px] font-medium text-purple-600 dark:text-purple-400 mt-0.5">Kelola semua kelas &rarr;</p>
                </div>
            </div>
        </a>

        {{-- Tugas Perlu Dinilai --}}
        <div class="flex items-center justify-between rounded-2xl border border-slate-200/70 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $tugasPerluDinilai > 0 ? 'bg-amber-100 text-amber-600 dark:bg-amber-950/60 dark:text-amber-300' : 'bg-slate-100 text-slate-400 dark:bg-slate-900/60 dark:text-slate-500' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 dark:text-slate-500">Tugas Perlu Dinilai</p>
                    <p class="text-2xl font-black {{ $tugasPerluDinilai > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-800 dark:text-white' }} mt-0.5">
                        {{ $tugasPerluDinilai }}
                    </p>
                    <p class="text-[11px] font-medium {{ $tugasPerluDinilai > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-slate-400' }} mt-0.5">
                        {{ $tugasPerluDinilai > 0 ? 'Menunggu evaluasi penilaian' : 'Semua berkas telah dinilai' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Total Bimbingan Mahasiswa --}}
        <div class="flex items-center justify-between rounded-2xl border border-slate-200/70 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 dark:text-slate-500">Total Mahasiswa Terdaftar</p>
                    <p class="text-2xl font-black text-slate-800 dark:text-white mt-0.5">{{ number_format($totalMahasiswa, 0, ',', '.') }}</p>
                    <p class="text-[11px] font-medium text-emerald-600 dark:text-emerald-400 mt-0.5">Terdaftar di seluruh kelas</p>
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT TASKS SUBMISSIONS SECTION --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm transition-colors duration-200">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 px-6 py-4 bg-slate-50/50 dark:bg-slate-900/30">
            <div>
                <h2 class="text-sm font-bold text-slate-800 dark:text-white tracking-tight">Pengumpulan Tugas Terbaru</h2>
                <p class="text-xs text-slate-400 dark:text-slate-500">Berkas tugas yang baru saja dikirimkan oleh mahasiswa</p>
            </div>
            @if ($submissions->isNotEmpty())
                <a href="{{ route('dosen.kelas-tugas.rekap', ['id' => $submissions->first()->tugas?->kelas_perkuliahan_id]) }}" class="inline-flex items-center gap-1 text-xs font-bold text-[#321270] dark:text-purple-400 hover:underline">Buka Rekap Nilai &rarr;</a>
            @endif
        </div>

        @if ($submissions->isEmpty())
            <div class="py-12 text-center text-sm font-medium text-slate-400 dark:text-slate-500">Belum ada tugas yang dikumpulkan oleh mahasiswa.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[600px] align-middle">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-700 bg-slate-50/40 dark:bg-slate-900/20 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            <th class="px-6 py-3 text-left">Nama Mahasiswa</th>
                            <th class="px-6 py-3 text-left">Judul Tugas / Kuliah</th>
                            <th class="px-6 py-3 text-left">Waktu Pengiriman</th>
                            <th class="px-6 py-3 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60">
                        @foreach ($submissions as $item)
                            <tr class="hover:bg-purple-50/20 dark:hover:bg-purple-950/20 transition duration-150">
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#321270] dark:bg-purple-950 text-xs font-bold text-white dark:text-purple-300 shadow-sm">
                                            {{ strtoupper(substr($item->mahasiswa?->name ?? '?', 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-700 dark:text-slate-200 max-w-[160px] truncate block">{{ $item->mahasiswa?->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <p class="font-bold text-[#321270] dark:text-purple-300 max-w-[220px] truncate">{{ $item->tugas?->judul ?? '-' }}</p>
                                    <p class="text-[11px] font-medium text-slate-400 dark:text-slate-500 max-w-[220px] truncate mt-0.5">{{ $item->tugas?->kelasPerkuliahan?->mataKuliah?->nama_mk ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-3.5 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $item->waktu_kumpul?->diffForHumans() ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-center">
                                    <a href="{{ route('dosen.kelas-tugas.rekap', ['id' => $item->tugas?->kelas_perkuliahan_id]) }}"
                                        class="inline-flex items-center rounded-xl bg-[#321270]/10 dark:bg-purple-950/40 px-3.5 py-1.5 text-xs font-bold text-[#321270] dark:text-purple-300 hover:bg-[#321270] dark:hover:bg-purple-700 hover:text-white dark:hover:text-white transition duration-150">
                                        Buka Evaluasi
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', async function() {
        try {
            const response = await fetch('{{ route("dosen.ai-assistant.generate-dashboard-insight") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
            const data = await response.json();
            
            const insightText = document.getElementById('ai_insight_text');
            if (data.success && data.message) {
                insightText.innerHTML = data.message;
            }
        } catch (error) {
            // Silently retain the default structured greeting summary
        }
    });
</script>

@endsection