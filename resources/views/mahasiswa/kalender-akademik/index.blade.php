@extends('layouts.portal')
@section('title', 'Kalender Akademik')
@section('activeMenu', 'Jadwal')
@section('content')

<div x-data="calendar()" class="min-h-screen bg-slate-50 dark:bg-slate-900 py-6 px-4 sm:px-6 lg:px-8 mb-12">
    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Breadcrumb & Header --}}
        <div class="space-y-4">
            <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-slate-500">
                <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-[#002B6B] dark:hover:text-blue-400 transition-colors">Dashboard</a>
                <svg class="w-3 h-3 flex-shrink-0 text-gray-300 dark:text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-600 dark:text-slate-300">Kalender Akademik</span>
            </nav>

            <div class="relative bg-gradient-to-r from-[#002B6B] via-[#053d8f] to-[#001f52] rounded-3xl p-6 text-white shadow-xl overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center flex-shrink-0 shadow-inner">
                            <svg class="w-6 h-6 text-blue-200" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-100 to-slate-300">Kalender Akademik</h1>
                            <p class="text-xs text-blue-100/80 mt-0.5">Pantau seluruh agenda, batas waktu tugas, serta jadwal ujian semester Anda di sini. Klik tanggal untuk melihat detail agenda.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">

            {{-- Calendar Main Block --}}
            <div class="lg:col-span-3 space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/80 overflow-hidden">

                    {{-- Controls Bar --}}
                    <div class="p-5 sm:p-6 border-b border-gray-100 dark:border-slate-700/60 space-y-4">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            {{-- Month Nav --}}
                            <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-900 p-1 rounded-xl w-fit border border-gray-100 dark:border-slate-800">
                                <button @click="changeMonth(-1)" class="p-2 rounded-lg hover:bg-white dark:hover:bg-slate-800 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition shadow-sm hover:shadow-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <h2 class="text-sm font-extrabold text-gray-800 dark:text-white px-3 min-w-[140px] text-center uppercase tracking-wider" x-text="currentMonthYear"></h2>
                                <button @click="changeMonth(1)" class="p-2 rounded-lg hover:bg-white dark:hover:bg-slate-800 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition shadow-sm hover:shadow-none">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>

                            <div class="flex items-center gap-3">
                                <button @click="goToToday()" class="px-4 py-2 text-xs font-bold uppercase tracking-wider rounded-xl bg-slate-100 dark:bg-slate-900 text-[#002B6B] dark:text-blue-400 hover:bg-slate-200 dark:hover:bg-slate-800 transition active:scale-95 shadow-sm border border-gray-200/50 dark:border-0">
                                    Hari Ini
                                </button>
                                {{-- Semester Selector --}}
                                <div class="relative flex items-center">
                                    <select x-model="selectedSemesterId" @change="updateURL()" class="pl-4 pr-10 py-2 text-xs font-bold uppercase tracking-wider rounded-xl border border-gray-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-gray-700 dark:text-slate-300 focus:border-[#002B6B] focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-[#002B6B]/10 outline-none transition appearance-none cursor-pointer">
                                        <option value="">Semua Semester</option>
                                        @foreach($semesters as $sem)
                                            <option value="{{ $sem->id }}">{{ $sem->tahun_ajaran }} - {{ $sem->nama_semester }} @if($sem->is_active) (Aktif) @endif</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute right-3 pointer-events-none text-gray-400"><svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg></div>
                                </div>
                            </div>
                        </div>

                        {{-- Category Filter --}}
                        <div class="overflow-x-auto scrollbar-none -mx-5 px-5 sm:mx-0 sm:px-0">
                            <div class="flex gap-2 pb-1 flex-nowrap">
                                @foreach([
                                    'uts' => 'UTS', 'uas' => 'UAS', 'libur_nasional' => 'Libur', 'libur_akademik' => 'Akademik',
                                    'deadline_tugas' => 'Tugas', 'deadline_skripsi' => 'Skripsi', 'pengumuman_nilai' => 'Nilai',
                                    'praktikum' => 'Praktikum', 'wisuda' => 'Wisuda', 'seminar' => 'Seminar', 'workshop' => 'Workshop',
                                    'presentasi_proyek' => 'Presentasi', 'sidang' => 'Sidang', 'orientasi_mahasiswa_baru' => 'Orientasi',
                                    'pembayaran_ukt' => 'UKT', 'pengisian_krs' => 'KRS', 'pengisian_khs' => 'KHS', 'cuti_akademik' => 'Cuti',
                                    'pengumuman_akademik' => 'Pengumuman', 'lainnya' => 'Lainnya'
                                ] as $cat => $label)
                                    <label class="flex items-center gap-2 cursor-pointer px-3 py-1.5 rounded-xl border transition-all text-xs font-bold whitespace-nowrap flex-shrink-0 select-none active:scale-95 shadow-sm"
                                           :class="visibleCategories.has('{{ $cat }}') ? 'bg-[#002B6B] border-[#002B6B] text-white shadow-[#002B6B]/10' : 'bg-white dark:bg-slate-900 border-gray-200 dark:border-slate-700 text-gray-600 dark:text-slate-400 hover:bg-gray-50 dark:hover:bg-slate-800'">
                                        <input type="checkbox" @click="toggleCategory('{{ $cat }}')" :checked="visibleCategories.has('{{ $cat }}')" class="sr-only">
                                        <span class="w-1.5 h-1.5 rounded-full" :class="visibleCategories.has('{{ $cat }}') ? 'bg-white' : 'bg-gray-400 dark:bg-slate-600'"></span>
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Calendar Matrix --}}
                    <div class="p-2 sm:p-6">
                        <div class="grid grid-cols-7 gap-0.5 sm:gap-1 mb-2">
                            @foreach(['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                                <div class="text-center text-[9px] sm:text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-slate-500 py-1 sm:py-2 truncate">{{ $day }}</div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-7 gap-0.5 sm:gap-1.5">
                            <template x-for="week in calendarWeeks" :key="JSON.stringify(week)">
                                <template x-for="day in week" :key="day.dateStr">
                                    {{-- Klik sel tanggal -> buka modal detail tanggal --}}
                                    <div @click="$dispatch('select-day', { day: day })"
                                         role="button" tabindex="0"
                                         @keydown.enter="$dispatch('select-day', { day: day })"
                                         :class="{
                                             'bg-slate-100/80 dark:bg-slate-900 border-[#002B6B] dark:border-blue-500/80 shadow-inner ring-1 ring-[#002B6B]/20': day.isToday,
                                             'bg-rose-50/40 dark:bg-rose-950/10 border-rose-100 dark:border-rose-950/30': day.isWeekend && !day.isToday && day.isCurrentMonth,
                                             'bg-white dark:bg-slate-800 border-gray-100 dark:border-slate-700/60': day.isCurrentMonth && !day.isToday && !day.isWeekend,
                                             'bg-slate-50/50 dark:bg-slate-900/40 border-gray-100 dark:border-slate-800/40 opacity-40': !day.isCurrentMonth,
                                         }"
                                         class="min-h-[64px] sm:min-h-[105px] p-1 sm:p-2.5 rounded-lg sm:rounded-2xl border transition-all duration-200 hover:shadow-md hover:border-[#002B6B]/40 dark:hover:border-slate-600 cursor-pointer flex flex-col relative group min-w-0">

                                        <div class="flex items-start justify-between gap-1 mb-1 sm:mb-2 relative z-10">
                                            <span :class="{
                                                      'text-[#002B6B] dark:text-blue-400 font-extrabold bg-slate-200/60 dark:bg-slate-800 px-1 sm:px-1.5 py-0.5 rounded-md sm:rounded-lg': day.isToday,
                                                      'text-rose-600 dark:text-rose-500 font-bold': day.isWeekend && !day.isToday && day.isCurrentMonth,
                                                      'text-gray-800 dark:text-slate-200 font-bold': day.isCurrentMonth && !day.isToday && !day.isWeekend,
                                                      'text-gray-400 dark:text-slate-600 font-medium': !day.isCurrentMonth,
                                                  }"
                                                  class="text-[11px] sm:text-sm" x-text="day.date"></span>

                                            <template x-if="day.events.filter((v,i,a)=>a.findIndex(t=>(t.id===v.id))===i).length > 0">
                                                <span class="text-[8px] sm:text-[10px] font-extrabold bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 px-1 sm:px-1.5 py-0.5 rounded-md"
                                                      x-text="day.events.filter((v,i,a)=>a.findIndex(t=>(t.id===v.id))===i).length"></span>
                                            </template>
                                        </div>

                                        {{-- Preview event --}}
                                        <div class="flex-1 space-y-0.5 sm:space-y-1 min-w-0 overflow-hidden">
                                            <template x-for="(event, idx) in day.events.filter((v,i,a)=>a.findIndex(t=>(t.id===v.id))===i).slice(0, 1)" :key="event.id">
                                                <div :style="'background-color: ' + event.warna + '10; border-left: 3px solid ' + event.warna"
                                                     class="px-1 sm:px-2 py-0.5 sm:py-1 rounded sm:rounded-lg text-[8px] sm:text-[11px] font-bold truncate shadow-xs flex flex-col gap-0.5 min-w-0">
                                                    <span class="inline-flex items-center gap-1 w-full text-gray-800 dark:text-slate-200 truncate min-w-0">
                                                        <span class="w-1 h-1 rounded-full flex-shrink-0 hidden sm:inline-block" :style="'background-color: ' + event.warna"></span>
                                                        <span class="truncate" x-text="event.judul"></span>
                                                    </span>
                                                    <template x-if="event.waktu_mulai && !event.is_all_day">
                                                        <span class="hidden sm:inline text-[9px] opacity-60 font-semibold tracking-wide" :style="'color: ' + event.warna" x-text="event.waktu_mulai.substring(0, 5)"></span>
                                                    </template>
                                                </div>
                                            </template>

                                            <template x-if="day.events.filter((v,i,a)=>a.findIndex(t=>(t.id===v.id))===i).length > 1">
                                                <div class="text-[8px] sm:text-[10px] font-bold text-center py-0.5 bg-slate-50 dark:bg-slate-900 text-gray-500 rounded sm:rounded-md border border-dashed border-gray-200 dark:border-slate-700"
                                                     x-text="'+' + (day.events.filter((v,i,a)=>a.findIndex(t=>(t.id===v.id))===i).length - 1) + ' lagi'"></div>
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
                    <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-slate-700 flex items-center justify-center">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Riwayat Agenda</h3>
                        <span class="ml-auto px-2.5 py-1 rounded-full bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-bold">
                            {{ $historyEvents->count() }} agenda
                        </span>
                    </div>
                    <div class="divide-y divide-gray-50 dark:divide-slate-700/50 max-h-64 overflow-y-auto">
                        @foreach($historyEvents as $event)
                        <div class="flex items-start gap-3 p-4 opacity-70 hover:opacity-100 transition-all duration-150"
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
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold flex-shrink-0 bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400">
                                Selesai
                            </span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Upcoming Events --}}
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/80 overflow-hidden">
                    <div class="bg-slate-50/60 dark:bg-slate-900/40 px-5 py-4 border-b border-gray-100 dark:border-slate-700/60">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-900 flex items-center justify-center text-[#002B6B] dark:text-blue-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-base">Agenda Mendatang</h3>
                        </div>
                    </div>
                    <div class="p-4 space-y-3 max-h-[420px] overflow-y-auto custom-scrollbar">
                        <template x-if="upcomingEvents.filter((v,i,a)=>a.findIndex(t=>(t.id===v.id))===i).length > 0">
                            <div class="space-y-2.5">
                                <template x-for="event in upcomingEvents.filter((v,i,a)=>a.findIndex(t=>(t.id===v.id))===i)" :key="event.id">
                                    <div @click="showEventDetail(event)" class="p-3 rounded-xl border border-gray-100 dark:border-slate-700/50 hover:shadow-sm cursor-pointer transition-all bg-slate-50/20 dark:bg-slate-900/5" :style="'border-left: 3px solid ' + event.warna">
                                        <div class="flex items-start justify-between gap-2 mb-1.5">
                                            <p class="font-bold text-xs text-gray-900 dark:text-white truncate flex-1" x-text="event.judul"></p>
                                            <span class="px-2 py-0.5 rounded-md text-[9px] font-extrabold uppercase tracking-wide flex-shrink-0" :style="'background-color: ' + event.warna + '15; color: ' + event.warna" x-text="event.jenis_kegiatan_label"></span>
                                        </div>
                                        <div class="flex flex-col gap-0.5 text-[11px] font-medium text-gray-500 dark:text-slate-400">
                                            <span class="flex items-center gap-1"><svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg> <span x-text="fmtTanggal(event.tanggal_mulai)"></span></span>
                                            <span class="pl-4 text-[10px] opacity-70" x-text="event.waktu_formatted"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="upcomingEvents.filter((v,i,a)=>a.findIndex(t=>(t.id===v.id))===i).length === 0">
                            <p class="text-center py-4 text-xs text-gray-400 font-medium">Belum ada agenda terdekat.</p>
                        </template>
                    </div>
                </div>

                {{-- Semester Statistics --}}
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/80 overflow-hidden">
                    <div class="bg-slate-50/60 dark:bg-slate-900/40 px-5 py-4 border-b border-gray-100 dark:border-slate-700/60">
                        <h3 class="font-bold text-gray-900 dark:text-white text-sm uppercase tracking-wider">Ringkasan Smt</h3>
                    </div>
                    <div class="p-4 space-y-2">
                        <template x-for="(stat, idx) in [
                            { label: 'Total Agenda', key: 'total', bg: 'bg-slate-50 dark:bg-slate-900', text: 'text-gray-800 dark:text-white' },
                            { label: 'UTS & UAS', key: 'exams', bg: 'bg-rose-50/60 dark:bg-rose-950/20', text: 'text-rose-600 dark:text-rose-400' },
                            { label: 'Libur & Cuti', key: 'holidays', bg: 'bg-emerald-50/60 dark:bg-emerald-950/20', text: 'text-emerald-600 dark:text-emerald-400' },
                            { label: 'Batas Tugas', key: 'deadlines', bg: 'bg-amber-50/60 dark:bg-amber-950/20', text: 'text-amber-600 dark:text-amber-400' }
                        ]" :key="idx">
                            <div :class="stat.bg" class="flex items-center justify-between p-3 rounded-xl transition border border-black/5 dark:border-white/5">
                                <span class="text-xs font-bold text-gray-500 dark:text-slate-400" x-text="stat.label"></span>
                                <span :class="stat.text" class="text-sm font-extrabold" x-text="semesterStats[stat.key]"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MODAL 1: DETAIL TANGGAL (muncul saat sel tanggal diklik)     --}}
    {{-- ============================================================ --}}
    <div x-data="{
            dayOpen: false,
            day: null,
            openDay(d) { this.day = d; this.dayOpen = true; },
            closeDay() { this.dayOpen = false; },
            get dayEvents() {
                if (!this.day) return [];
                return this.day.events.filter((v, i, a) => a.findIndex(t => t.id === v.id) === i);
            },
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
             class="fixed inset-0 z-40 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-md"
             @click.self="closeDay()">

            <div x-show="dayOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 class="bg-white dark:bg-slate-800 rounded-3xl max-w-lg w-full max-h-[85vh] flex flex-col shadow-2xl border border-gray-100 dark:border-slate-700 overflow-hidden"
                 role="dialog" aria-modal="true" aria-labelledby="dayModalTitle">

                {{-- Header --}}
                <div class="p-5 border-b border-gray-100 dark:border-slate-700/60 flex items-start justify-between gap-3 bg-slate-50/50 dark:bg-slate-900/20">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-xl bg-[#002B6B] flex items-center justify-center flex-shrink-0 shadow-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <h3 id="dayModalTitle" class="font-extrabold text-gray-900 dark:text-white text-sm sm:text-base truncate" x-text="dayLabel"></h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5" x-text="dayEvents.length > 0 ? dayEvents.length + ' agenda pada tanggal ini' : 'Tidak ada agenda'"></p>
                        </div>
                    </div>
                    <button @click="closeDay()" aria-label="Tutup" class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 transition active:scale-90 flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Body --}}
                <div class="p-5 overflow-y-auto space-y-3">
                    <template x-for="event in dayEvents" :key="event.id">
                        <div class="rounded-2xl border border-gray-100 dark:border-slate-700/60 p-4 space-y-3"
                             :style="'border-left: 4px solid ' + (event.warna || '#002B6B') + '; background-color: ' + (event.warna || '#002B6B') + '08'">

                            <div class="flex items-start justify-between gap-2">
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white leading-snug" x-text="event.judul"></h4>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wide flex-shrink-0"
                                      :style="'background-color: ' + (event.warna || '#002B6B') + '20; color: ' + (event.warna || '#002B6B')"
                                      x-text="event.jenis_kegiatan_label || 'Kegiatan'"></span>
                            </div>

                            <div class="space-y-1.5 text-xs text-gray-600 dark:text-slate-300">
                                {{-- Tanggal --}}
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                    <span class="font-semibold">
                                        <span x-text="fmtTanggal(event.tanggal_mulai)"></span>
                                        <template x-if="event.tanggal_selesai && fmtTanggal(event.tanggal_selesai) !== fmtTanggal(event.tanggal_mulai)">
                                            <span x-text="' s/d ' + fmtTanggal(event.tanggal_selesai)"></span>
                                        </template>
                                    </span>
                                </div>

                                {{-- Waktu --}}
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <template x-if="event.is_all_day">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400">Sepanjang Hari</span>
                                    </template>
                                    <template x-if="!event.is_all_day">
                                        <span class="font-semibold" x-text="event.waktu_formatted || 'Waktu belum ditentukan'"></span>
                                    </template>
                                </div>

                                {{-- Lokasi --}}
                                <template x-if="event.lokasi">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                        <span class="font-semibold" x-text="event.lokasi"></span>
                                    </div>
                                </template>
                            </div>

                            {{-- Keterangan --}}
                            <template x-if="event.deskripsi">
                                <div class="pt-3 border-t border-gray-100 dark:border-slate-700/60">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Keterangan</p>
                                    <p class="text-xs text-gray-600 dark:text-slate-300 leading-relaxed whitespace-pre-wrap" x-text="event.deskripsi"></p>
                                </div>
                            </template>
                        </div>
                    </template>

                    {{-- Kosong --}}
                    <template x-if="dayEvents.length === 0">
                        <div class="text-center py-10">
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

    {{-- ============================================================ --}}
    {{-- MODAL 2: DETAIL SATU AGENDA (dari Agenda Mendatang)          --}}
    {{-- ============================================================ --}}
    <div x-show="selectedEvent" x-cloak
         @keydown.escape.window="selectedEvent && closeEventModal()"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/40 backdrop-blur-md">
        <div @click.outside="closeEventModal()"
             class="bg-white dark:bg-slate-800 rounded-3xl max-w-md w-full shadow-2xl border border-gray-100 dark:border-slate-700 overflow-hidden transform transition-all">

            <template x-if="selectedEvent">
                <div>
                    <div class="p-5 border-b border-gray-100 dark:border-slate-700/60 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/20">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                                 :style="'background-color: ' + (selectedEvent.warna || '#002B6B')">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/></svg>
                            </div>
                            <h3 class="font-extrabold text-gray-900 dark:text-white text-sm truncate" x-text="selectedEvent.judul"></h3>
                        </div>
                        <button @click="closeEventModal()" aria-label="Tutup" class="w-8 h-8 rounded-xl flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 transition active:scale-90">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4 text-sm">
                        <div class="flex flex-wrap gap-2">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold"
                                  :style="'background-color: ' + (selectedEvent.warna || '#002B6B') + '15; color: ' + (selectedEvent.warna || '#002B6B')"
                                  x-text="selectedEvent.jenis_kegiatan_label || 'Kegiatan'"></span>
                            <span x-show="selectedEvent.is_all_day"
                                  class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400">
                                Sepanjang Hari
                            </span>
                        </div>

                        <div class="bg-slate-50 dark:bg-slate-900/50 border border-gray-100 dark:border-none rounded-2xl p-4 text-xs space-y-1">
                            <div class="flex items-center gap-2 text-gray-800 dark:text-slate-200 font-bold">
                                <svg class="w-4 h-4 text-[#002B6B] dark:text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                <span>
                                    <span x-text="fmtTanggal(selectedEvent.tanggal_mulai)"></span>
                                    <template x-if="selectedEvent.tanggal_selesai && fmtTanggal(selectedEvent.tanggal_selesai) !== fmtTanggal(selectedEvent.tanggal_mulai)">
                                        <span x-text="' s/d ' + fmtTanggal(selectedEvent.tanggal_selesai)"></span>
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
                                <p class="text-xs font-bold text-gray-800 dark:text-slate-200 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                    <span x-text="selectedEvent.lokasi"></span>
                                </p>
                            </div>
                        </template>

                        <template x-if="selectedEvent.deskripsi">
                            <div class="pt-3.5 border-t border-gray-100 dark:border-slate-700/60">
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Keterangan Detail</p>
                                <p class="text-xs text-gray-600 dark:text-slate-300 leading-relaxed font-medium whitespace-pre-wrap" x-text="selectedEvent.deskripsi"></p>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Format tanggal ISO (mis. 2026-10-04T17:00:00.000000Z) ke "5 Oktober 2026" (WIB).
    // Jika bukan format ISO, nilai dikembalikan apa adanya.
    window.fmtTanggal = function (v) {
        if (!v) return '';
        if (!/^\d{4}-\d{2}-\d{2}/.test(String(v))) return v;
        const d = new Date(v);
        if (isNaN(d)) return v;
        return d.toLocaleDateString('id-ID', {
            day: 'numeric', month: 'long', year: 'numeric', timeZone: 'Asia/Jakarta'
        });
    };
</script>
@include('mahasiswa.kalender-akademik.calendar-script')
@endpush

@endsection