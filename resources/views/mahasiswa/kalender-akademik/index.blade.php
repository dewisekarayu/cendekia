@extends('layouts.portal')
@section('title', 'Kalender Akademik')
@section('activeMenu', 'Jadwal')
@section('content')

<style>
    /* Cegah halaman bisa digeser ke samping di HP */
    html, body { max-width: 100%; overflow-x: hidden; overscroll-behavior-x: none; }
    @supports (overflow: clip) { html, body { overflow-x: clip; } }
    body { touch-action: pan-y pinch-zoom; }
</style>

<div x-data="calendar()" class="min-h-screen overflow-x-hidden bg-slate-50 dark:bg-slate-900 py-4 sm:py-6 px-3 sm:px-6 lg:px-8 mb-12">
    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6">

        {{-- Breadcrumb & Header --}}
        <div class="space-y-3 sm:space-y-4">
            <nav class="flex items-center gap-2 text-[10px] sm:text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-slate-500">
                <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-[#002B6B] dark:hover:text-blue-400 transition-colors">Dashboard</a>
                <svg class="w-3 h-3 flex-shrink-0 text-gray-300 dark:text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-600 dark:text-slate-300">Kalender Akademik</span>
            </nav>

            <div class="relative bg-gradient-to-r from-[#002B6B] via-[#053d8f] to-[#001f52] rounded-2xl sm:rounded-3xl p-4 sm:p-6 text-white shadow-xl overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="flex items-center gap-3 sm:gap-4 relative z-10">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center flex-shrink-0 shadow-inner">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-lg sm:text-2xl font-extrabold tracking-tight text-white">Kalender Akademik</h1>
                        <p class="text-[11px] sm:text-xs text-blue-100/80 mt-0.5 leading-snug">
                            <span class="sm:hidden">Ketuk tanggal untuk melihat agenda.</span>
                            <span class="hidden sm:inline">Pantau seluruh agenda, batas waktu tugas, serta jadwal ujian semester Anda di sini. Klik tanggal untuk melihat detail agenda.</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-6 items-start">

            {{-- Calendar Main Block --}}
            <div class="lg:col-span-3 min-w-0 space-y-4 sm:space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/80 overflow-hidden">

                    {{-- Controls Bar --}}
                    <div class="p-3 sm:p-6 border-b border-gray-100 dark:border-slate-700/60 space-y-3 sm:space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            {{-- Month Nav --}}
                            <div class="flex items-center justify-between sm:justify-start gap-1 bg-slate-50 dark:bg-slate-900 p-1 rounded-xl w-full sm:w-fit border border-gray-100 dark:border-slate-800">
                                <button @click="changeMonth(-1)" aria-label="Bulan sebelumnya" class="p-2.5 sm:p-2 flex-shrink-0 rounded-lg hover:bg-white dark:hover:bg-slate-800 text-gray-600 dark:text-gray-400 transition active:scale-90">
                                    <svg class="w-5 h-5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <h2 class="text-base sm:text-sm font-extrabold text-gray-800 dark:text-white px-2 sm:px-3 sm:min-w-[160px] text-center uppercase tracking-wider truncate" x-text="currentMonthYear"></h2>
                                <button @click="changeMonth(1)" aria-label="Bulan berikutnya" class="p-2.5 sm:p-2 flex-shrink-0 rounded-lg hover:bg-white dark:hover:bg-slate-800 text-gray-600 dark:text-gray-400 transition active:scale-90">
                                    <svg class="w-5 h-5 sm:w-5 sm:h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>

                            <div class="flex items-center gap-2 sm:gap-3">
                                <button @click="goToToday()" class="flex-shrink-0 px-4 py-2.5 sm:py-2 text-xs font-bold uppercase tracking-wider rounded-xl bg-slate-100 dark:bg-slate-900 text-[#002B6B] dark:text-blue-400 hover:bg-slate-200 dark:hover:bg-slate-800 transition active:scale-95 border border-gray-200/50 dark:border-0">
                                    Hari Ini
                                </button>
                                {{-- Semester Selector --}}
                                <div class="relative flex items-center flex-1 min-w-0 sm:flex-none">
                                    <select x-model="selectedSemesterId" @change="updateURL()" class="w-full min-w-0 truncate pl-3 sm:pl-4 pr-9 py-2.5 sm:py-2 text-xs font-bold tracking-wider rounded-xl border border-gray-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-gray-700 dark:text-slate-300 focus:border-[#002B6B] focus:ring-4 focus:ring-[#002B6B]/10 outline-none transition appearance-none cursor-pointer">
                                        <option value="">Semua Semester</option>
                                        @foreach($semesters as $sem)
                                            <option value="{{ $sem->id }}">{{ $sem->tahun_ajaran }} - {{ $sem->nama_semester }} @if($sem->is_active) (Aktif) @endif</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-3 pointer-events-none text-gray-400"><svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Calendar Matrix --}}
                    <div class="p-1.5 sm:p-6">
                        <div class="grid grid-cols-7 gap-0.5 sm:gap-1 mb-1 sm:mb-2">
                            @foreach(['Min' => 'Minggu', 'Sen' => 'Senin', 'Sel' => 'Selasa', 'Rab' => 'Rabu', 'Kam' => 'Kamis', 'Jum' => 'Jumat', 'Sab' => 'Sabtu'] as $short => $day)
                                <div class="text-center text-[10px] sm:text-xs font-bold uppercase tracking-wide sm:tracking-wider py-1 sm:py-2 {{ $short === 'Min' ? 'text-rose-400' : 'text-gray-400 dark:text-slate-500' }}">
                                    <span class="sm:hidden">{{ $short }}</span>
                                    <span class="hidden sm:inline">{{ $day }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-7 gap-0.5 sm:gap-1.5">
                            <template x-for="week in calendarWeeks" :key="JSON.stringify(week)">
                                <template x-for="day in week" :key="day.dateStr">
                                    <div @click="$dispatch('select-day', { day: day })"
                                         role="button" tabindex="0"
                                         @keydown.enter="$dispatch('select-day', { day: day })"
                                         :class="{
                                             'bg-slate-100/80 dark:bg-slate-900 border-[#002B6B] dark:border-blue-500/80 ring-1 ring-[#002B6B]/20': day.isToday,
                                             'bg-rose-50/40 dark:bg-rose-950/10 border-rose-100 dark:border-rose-950/30': day.isWeekend && !day.isToday && day.isCurrentMonth,
                                             'bg-white dark:bg-slate-800 border-gray-100 dark:border-slate-700/60': day.isCurrentMonth && !day.isToday && !day.isWeekend,
                                             'bg-slate-50/50 dark:bg-slate-900/40 border-gray-100 dark:border-slate-800/40 opacity-40': !day.isCurrentMonth,
                                         }"
                                         class="min-h-[52px] sm:min-h-[105px] p-1 sm:p-2.5 rounded-lg sm:rounded-2xl border transition-all duration-200 sm:hover:shadow-md hover:border-[#002B6B]/40 cursor-pointer flex flex-col items-center sm:items-stretch relative min-w-0 active:scale-95 sm:active:scale-100">

                                        <div class="flex items-start justify-center sm:justify-between gap-1 sm:mb-2 w-full">
                                            <span :class="{
                                                      'bg-[#002B6B] text-white font-extrabold': day.isToday,
                                                      'text-rose-600 dark:text-rose-500 font-bold': day.isWeekend && !day.isToday && day.isCurrentMonth,
                                                      'text-gray-800 dark:text-slate-200 font-bold': day.isCurrentMonth && !day.isToday && !day.isWeekend,
                                                      'text-gray-400 dark:text-slate-600 font-medium': !day.isCurrentMonth,
                                                  }"
                                                  class="text-xs sm:text-sm min-w-[22px] sm:min-w-[26px] h-[22px] sm:h-[26px] inline-flex items-center justify-center rounded-full"
                                                  x-text="day.date"></span>

                                            {{-- Jumlah agenda (hanya layar lebar) --}}
                                            <template x-if="uniqEvents(day.events).length > 0">
                                                <span class="hidden sm:inline text-[10px] font-extrabold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 px-1.5 py-0.5 rounded-md"
                                                      x-text="uniqEvents(day.events).length"></span>
                                            </template>
                                        </div>

                                        {{-- HP: titik warna per agenda --}}
                                        <div class="sm:hidden mt-1 flex items-center justify-center gap-0.5 flex-wrap">
                                            <template x-for="event in uniqEvents(day.events).slice(0, 3)" :key="event.id">
                                                <span class="w-1.5 h-1.5 rounded-full" :style="'background-color: ' + event.warna"></span>
                                            </template>
                                            <template x-if="uniqEvents(day.events).length > 3">
                                                <span class="text-[8px] font-bold leading-none text-gray-400">+</span>
                                            </template>
                                        </div>

                                        {{-- Layar lebar: preview judul agenda --}}
                                        <div class="hidden sm:block flex-1 space-y-1 min-w-0 overflow-hidden w-full">
                                            <template x-for="event in uniqEvents(day.events).slice(0, 1)" :key="event.id">
                                                <div :style="'background-color: ' + event.warna + '10; border-left: 3px solid ' + event.warna"
                                                     class="px-2 py-1 rounded-lg text-[11px] font-bold truncate flex flex-col gap-0.5 min-w-0">
                                                    <span class="truncate text-gray-800 dark:text-slate-200" x-text="event.judul"></span>
                                                    <template x-if="event.waktu_mulai && !event.is_all_day">
                                                        <span class="text-[9px] opacity-60 font-semibold tracking-wide" :style="'color: ' + event.warna" x-text="event.waktu_mulai.substring(0, 5)"></span>
                                                    </template>
                                                </div>
                                            </template>
                                            <template x-if="uniqEvents(day.events).length > 1">
                                                <div class="text-[10px] font-bold text-center py-0.5 bg-slate-50 dark:bg-slate-900 text-gray-500 rounded-md border border-dashed border-gray-200 dark:border-slate-700"
                                                     x-text="'+' + (uniqEvents(day.events).length - 1) + ' lagi'"></div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Riwayat Agenda --}}
                @if($historyEvents->count() > 0)
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                    <div class="px-4 sm:px-5 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-slate-700 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Riwayat Agenda</h3>
                        <span class="ml-auto px-2.5 py-1 rounded-full bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-bold whitespace-nowrap">
                            {{ $historyEvents->count() }} agenda
                        </span>
                    </div>
                    <div class="divide-y divide-gray-50 dark:divide-slate-700/50 max-h-64 overflow-y-auto">
                        @foreach($historyEvents as $event)
                        <div class="flex items-start gap-3 p-3 sm:p-4 opacity-70 hover:opacity-100 transition-all duration-150"
                             style="border-left: 4px solid {{ $event->warna }}">
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-800 dark:text-gray-200 text-sm truncate">{{ $event->judul }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                    {{ $event->tanggal_mulai->format('d M Y') }}
                                    @if($event->tanggal_selesai && !$event->tanggal_mulai->eq($event->tanggal_selesai))
                                        – {{ $event->tanggal_selesai->format('d M Y') }}
                                    @endif
                                </p>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold flex-shrink-0 bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400">Selesai</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 min-w-0 space-y-4 sm:space-y-6">
                {{-- Upcoming Events --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/80 overflow-hidden">
                    <div class="bg-slate-50/60 dark:bg-slate-900/40 px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100 dark:border-slate-700/60">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-900 flex items-center justify-center text-[#002B6B] dark:text-blue-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-base">Agenda Mendatang</h3>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4 space-y-3 max-h-[360px] lg:max-h-[420px] overflow-y-auto custom-scrollbar">
                        <template x-if="uniqEvents(upcomingEvents).length > 0">
                            <div class="space-y-2.5">
                                <template x-for="event in uniqEvents(upcomingEvents)" :key="event.id">
                                    <div @click="showEventDetail(event)" class="p-3 rounded-xl border border-gray-100 dark:border-slate-700/50 active:bg-slate-50 hover:shadow-sm cursor-pointer transition-all" :style="'border-left: 3px solid ' + event.warna">
                                        <div class="flex items-start justify-between gap-2 mb-1.5">
                                            <p class="font-bold text-xs text-gray-900 dark:text-white flex-1 min-w-0 truncate" x-text="event.judul"></p>
                                            <span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-wide flex-shrink-0" :style="'background-color: ' + event.warna + '15; color: ' + event.warna" x-text="event.jenis_kegiatan_label"></span>
                                        </div>
                                        <div class="flex flex-col gap-0.5 text-[11px] font-medium text-gray-500 dark:text-slate-400">
                                            <span x-text="fmtTanggal(event.tanggal_mulai)"></span>
                                            <span class="text-[10px] opacity-70" x-text="event.waktu_formatted"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="uniqEvents(upcomingEvents).length === 0">
                            <p class="text-center py-4 text-xs text-gray-400 font-medium">Belum ada agenda terdekat.</p>
                        </template>
                    </div>
                </div>

                {{-- Semester Statistics --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl sm:rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/80 overflow-hidden">
                    <div class="bg-slate-50/60 dark:bg-slate-900/40 px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100 dark:border-slate-700/60">
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm uppercase tracking-wider">Ringkasan Smt</h3>
                    </div>
                    <div class="p-3 sm:p-4 grid grid-cols-2 lg:grid-cols-1 gap-2">
                        <template x-for="(stat, idx) in [
                            { label: 'Total Agenda', key: 'total', bg: 'bg-slate-50 dark:bg-slate-900', text: 'text-gray-800 dark:text-white' },
                            { label: 'UTS & UAS', key: 'exams', bg: 'bg-rose-50/60 dark:bg-rose-950/20', text: 'text-rose-600 dark:text-rose-400' },
                            { label: 'Libur & Cuti', key: 'holidays', bg: 'bg-emerald-50/60 dark:bg-emerald-950/20', text: 'text-emerald-600 dark:text-emerald-400' },
                            { label: 'Batas Tugas', key: 'deadlines', bg: 'bg-amber-50/60 dark:bg-amber-950/20', text: 'text-amber-600 dark:text-amber-400' }
                        ]" :key="idx">
                            <div :class="stat.bg" class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-0.5 p-3 rounded-xl border border-black/5 dark:border-white/5">
                                <span class="text-[11px] sm:text-xs font-bold text-gray-500 dark:text-slate-400" x-text="stat.label"></span>
                                <span :class="stat.text" class="text-lg lg:text-sm font-extrabold" x-text="semesterStats[stat.key]"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 1: DETAIL TANGGAL (popup di tengah layar)              --}}
    {{-- ============================================================ --}}
    <template x-teleport="body">
        <div x-data="{
                dayOpen: false,
                day: null,
                openDay(d) { this.day = d; this.dayOpen = true; },
                closeDay() { this.dayOpen = false; },
                get dayEvents() { return this.day ? window.uniqEvents(this.day.events) : []; },
                get dayLabel() {
                    if (!this.day) return '';
                    const raw = String(this.day.dateStr).slice(0, 10);
                    const d = new Date(raw + 'T00:00:00');
                    return isNaN(d) ? raw : d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                }
             }"
             @select-day.window="openDay($event.detail.day)"
             @keydown.escape.window="closeDay()"
             x-effect="document.body.classList.toggle('overflow-hidden', dayOpen)">

            <div x-show="dayOpen" x-cloak
                 x-transition.opacity.duration.200ms
                 class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/50 backdrop-blur-sm touch-none overscroll-none"
                 @click.self="closeDay()">

                <div x-show="dayOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="bg-white dark:bg-slate-800 rounded-3xl w-full max-w-lg max-h-[85dvh] flex flex-col shadow-2xl border border-gray-100 dark:border-slate-700 overflow-hidden"
                     role="dialog" aria-modal="true" aria-labelledby="dayModalTitle">

                    {{-- Header --}}
                    <div class="px-4 sm:px-5 py-3 sm:py-5 border-b border-gray-100 dark:border-slate-700/60 flex items-start justify-between gap-3 flex-shrink-0">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-[#002B6B] flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <h3 id="dayModalTitle" class="font-extrabold text-gray-900 dark:text-white text-sm sm:text-base leading-snug" x-text="dayLabel"></h3>
                                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5" x-text="dayEvents.length > 0 ? dayEvents.length + ' agenda pada tanggal ini' : 'Tidak ada agenda'"></p>
                            </div>
                        </div>
                        <button @click="closeDay()" aria-label="Tutup" class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 transition active:scale-90 flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="p-4 sm:p-5 overflow-y-auto overscroll-contain space-y-3" style="padding-bottom: max(1rem, env(safe-area-inset-bottom));">
                        <template x-for="event in dayEvents" :key="event.id">
                            <div class="rounded-2xl border border-gray-100 dark:border-slate-700/60 p-4 space-y-3"
                                 :style="'border-left: 4px solid ' + (event.warna || '#002B6B') + '; background-color: ' + (event.warna || '#002B6B') + '08'">

                                <div class="flex flex-wrap items-start justify-between gap-2">
                                    <h4 class="font-bold text-sm text-gray-900 dark:text-white leading-snug min-w-0 flex-1" x-text="event.judul"></h4>
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wide flex-shrink-0"
                                          :style="'background-color: ' + (event.warna || '#002B6B') + '20; color: ' + (event.warna || '#002B6B')"
                                          x-text="event.jenis_kegiatan_label || 'Kegiatan'"></span>
                                </div>

                                <div class="space-y-1.5 text-xs text-gray-600 dark:text-slate-300">
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                        <span class="font-semibold">
                                            <span x-text="window.fmtTanggal(event.tanggal_mulai)"></span>
                                            <template x-if="event.tanggal_selesai && window.fmtTanggal(event.tanggal_selesai) !== window.fmtTanggal(event.tanggal_mulai)">
                                                <span x-text="' s/d ' + window.fmtTanggal(event.tanggal_selesai)"></span>
                                            </template>
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <template x-if="event.is_all_day">
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400">Sepanjang Hari</span>
                                        </template>
                                        <template x-if="!event.is_all_day">
                                            <span class="font-semibold" x-text="event.waktu_formatted || 'Waktu belum ditentukan'"></span>
                                        </template>
                                    </div>

                                    <template x-if="event.lokasi">
                                        <div class="flex items-start gap-2">
                                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-px" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                            <span class="font-semibold" x-text="event.lokasi"></span>
                                        </div>
                                    </template>
                                </div>

                                <template x-if="event.deskripsi">
                                    <div class="pt-3 border-t border-gray-100 dark:border-slate-700/60">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Keterangan</p>
                                        <p class="text-xs text-gray-600 dark:text-slate-300 leading-relaxed whitespace-pre-wrap break-words" x-text="event.deskripsi"></p>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="dayEvents.length === 0">
                            <div class="text-center py-8">
                                <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center mb-3 text-slate-400">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-700 dark:text-slate-200">Tidak ada agenda</p>
                                <p class="text-xs text-gray-400 mt-1">Belum ada kegiatan akademik pada tanggal ini.</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </template>

    {{-- ============================================================ --}}
    {{-- MODAL 2: DETAIL SATU AGENDA (popup di tengah layar)          --}}
    {{-- ============================================================ --}}
    <template x-teleport="body">
        <div x-show="selectedEvent" x-cloak
             x-transition.opacity.duration.200ms
             x-effect="document.body.classList.toggle('overflow-hidden', !!selectedEvent)"
             @keydown.escape.window="selectedEvent && closeEventModal()"
             @click.self="closeEventModal()"
             class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-950/50 backdrop-blur-sm touch-none overscroll-none">

            <div class="bg-white dark:bg-slate-800 rounded-3xl w-full max-w-md max-h-[85dvh] overflow-y-auto overscroll-contain shadow-2xl border border-gray-100 dark:border-slate-700"
                 role="dialog" aria-modal="true">

                <template x-if="selectedEvent">
                    <div>
                        <div class="px-4 sm:px-5 py-3 sm:py-5 border-b border-gray-100 dark:border-slate-700/60 flex items-center justify-between gap-3 sticky top-0 bg-white dark:bg-slate-800 z-10">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0" :style="'background-color: ' + (selectedEvent.warna || '#002B6B')">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
                                </div>
                                <h3 class="font-extrabold text-gray-900 dark:text-white text-sm leading-snug" x-text="selectedEvent.judul"></h3>
                            </div>
                            <button @click="closeEventModal()" aria-label="Tutup" class="w-9 h-9 rounded-xl flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 transition active:scale-90 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="p-4 sm:p-6 space-y-4 text-sm" style="padding-bottom: max(1rem, env(safe-area-inset-bottom));">
                            <div class="flex flex-wrap gap-2">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold"
                                      :style="'background-color: ' + (selectedEvent.warna || '#002B6B') + '15; color: ' + (selectedEvent.warna || '#002B6B')"
                                      x-text="selectedEvent.jenis_kegiatan_label || 'Kegiatan'"></span>
                                <span x-show="selectedEvent.is_all_day" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400">Sepanjang Hari</span>
                            </div>

                            <div class="bg-slate-50 dark:bg-slate-900/50 border border-gray-100 dark:border-none rounded-2xl p-4 text-xs space-y-1">
                                <div class="flex items-start gap-2 text-gray-800 dark:text-slate-200 font-bold">
                                    <svg class="w-4 h-4 text-[#002B6B] dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                    <span>
                                        <span x-text="window.fmtTanggal(selectedEvent.tanggal_mulai)"></span>
                                        <template x-if="selectedEvent.tanggal_selesai && window.fmtTanggal(selectedEvent.tanggal_selesai) !== window.fmtTanggal(selectedEvent.tanggal_mulai)">
                                            <span x-text="' s/d ' + window.fmtTanggal(selectedEvent.tanggal_selesai)"></span>
                                        </template>
                                    </span>
                                </div>
                                <template x-if="!selectedEvent.is_all_day && selectedEvent.waktu_formatted">
                                    <div class="text-[11px] font-semibold text-gray-400 pl-6" x-text="selectedEvent.waktu_formatted"></div>
                                </template>
                            </div>

                            <template x-if="selectedEvent.lokasi">
                                <div class="pt-3.5 border-t border-gray-100 dark:border-slate-700/60">
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Lokasi Ruangan</p>
                                    <p class="text-xs font-bold text-gray-800 dark:text-slate-200" x-text="selectedEvent.lokasi"></p>
                                </div>
                            </template>

                            <template x-if="selectedEvent.deskripsi">
                                <div class="pt-3.5 border-t border-gray-100 dark:border-slate-700/60">
                                    <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Keterangan Detail</p>
                                    <p class="text-xs text-gray-600 dark:text-slate-300 leading-relaxed font-medium whitespace-pre-wrap break-words" x-text="selectedEvent.deskripsi"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script>
    // Format tanggal ISO (mis. 2026-10-04T17:00:00.000000Z) ke "5 Oktober 2026" (WIB).
    window.fmtTanggal = function (v) {
        if (!v) return '';
        if (!/^\d{4}-\d{2}-\d{2}/.test(String(v))) return v;
        const d = new Date(v);
        if (isNaN(d)) return v;
        return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', timeZone: 'Asia/Jakarta' });
    };

    // Hapus agenda ganda (berdasarkan id) agar tidak dihitung/ditampilkan dua kali.
    window.uniqEvents = function (events) {
        return (events || []).filter((v, i, a) => a.findIndex(t => t.id === v.id) === i);
    };
</script>
@include('mahasiswa.kalender-akademik.calendar-script')
@endpush

@endsection