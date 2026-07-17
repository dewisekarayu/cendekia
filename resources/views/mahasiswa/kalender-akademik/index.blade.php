@extends('layouts.portal')
@section('title', 'Kalender Akademik')
@section('activeMenu', 'Jadwal')
@section('content')

<div x-data="calendar()" class="min-h-screen bg-gray-50 dark:bg-slate-900 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-slate-400 mb-3">
                <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-900 dark:text-white font-medium">Kalender Akademik</span>
            </div>
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2 flex items-center gap-2 sm:gap-3">
                        <div class="w-10 sm:w-12 h-10 sm:h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 sm:w-6 h-5 sm:h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                        </div>
                        <span class="truncate">Kalender Akademik</span>
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-slate-400">Lihat semua agenda akademik Anda di semester ini</p>
                </div>
                <a href="{{ route('mahasiswa.dashboard') }}" class="flex items-center justify-center gap-1 sm:gap-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-200 rounded-lg sm:rounded-xl font-medium transition text-sm sm:text-base flex-shrink-0">
                    <svg class="w-4 sm:w-5 h-4 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
            </div>
        </div>

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Calendar Card --}}
            <div class="lg:col-span-3 space-y-6">
                {{-- Kalender Controls --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-gray-200 dark:border-slate-700">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            {{-- Navigation --}}
                            <div class="flex items-center gap-2">
                                <button @click="changeMonth(-1)" class="p-2 rounded-lg bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 transition">
                                    <svg class="w-5 h-5 text-gray-700 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white min-w-[200px] text-center" x-text="currentMonthYear"></h2>
                                <button @click="changeMonth(1)" class="p-2 rounded-lg bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 transition">
                                    <svg class="w-5 h-5 text-gray-700 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                                <button @click="goToToday()" class="px-3 py-1.5 text-sm font-medium rounded-lg bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition">
                                    Hari Ini
                                </button>
                            </div>

                            {{-- Filters --}}
                            <select x-model="selectedSemesterId" @change="updateURL()" class="px-3 py-1.5 text-sm rounded-lg border border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
                                <option value="">Semua Semester</option>
                                @foreach($semesters as $sem)
                                    <option value="{{ $sem->id }}">{{ $sem->tahun_ajaran }} - {{ $sem->nama_semester }} @if($sem->is_active) (Aktif) @endif</option>
                                @endforeach
                            </select>
                        </div>

        {{-- Category Filter --}}
                        <div class="mt-3 sm:mt-4 overflow-x-auto -mx-5 sm:mx-0 px-5 sm:px-0">
                            <div class="flex gap-1.5 sm:gap-2 pb-2 flex-nowrap">
                                @foreach([
                                    'uts' => 'UTS', 
                                    'uas' => 'UAS', 
                                    'libur_nasional' => 'Libur', 
                                    'libur_akademik' => 'Akademik', 
                                    'deadline_tugas' => 'Tugas', 
                                    'deadline_skripsi' => 'Skripsi',
                                    'pengumuman_nilai' => 'Nilai', 
                                    'praktikum' => 'Praktikum', 
                                    'wisuda' => 'Wisuda',
                                    'seminar' => 'Seminar',
                                    'workshop' => 'Workshop',
                                    'presentasi_proyek' => 'Presentasi',
                                    'sidang' => 'Sidang',
                                    'orientasi_mahasiswa_baru' => 'Orientasi',
                                    'pembayaran_ukt' => 'UKT',
                                    'pengisian_krs' => 'KRS',
                                    'pengisian_khs' => 'KHS',
                                    'cuti_akademik' => 'Cuti',
                                    'pengumuman_akademik' => 'Pengumuman',
                                    'lainnya' => 'Lainnya'
                                ] as $cat => $label)
                                    <label class="flex items-center gap-1 cursor-pointer px-2 sm:px-3 py-1 sm:py-1.5 rounded-lg transition text-xs sm:text-sm whitespace-nowrap flex-shrink-0" :class="visibleCategories.has('{{ $cat }}') ? 'bg-blue-100 dark:bg-blue-900/30' : 'bg-gray-50 dark:bg-slate-700/50 hover:bg-gray-100 dark:hover:bg-slate-700'">
                                        <input type="checkbox" 
                                            @click="toggleCategory('{{ $cat }}')"
                                            :checked="visibleCategories.has('{{ $cat }}')"
                                            class="w-3 h-3 sm:w-4 sm:h-4 rounded accent-blue-600 cursor-pointer">
                                        <span class="font-medium" :class="visibleCategories.has('{{ $cat }}') ? 'text-blue-700 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300'">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Calendar Grid --}}
                    <div class="p-3 sm:p-5 overflow-x-auto">
                        <div class="grid grid-cols-7 gap-0.5 sm:gap-1 mb-2 sm:mb-3 min-w-max">
                            @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                                <div class="text-center text-xs sm:text-sm font-bold text-gray-600 dark:text-gray-400 py-1.5 sm:py-2">{{ $day }}</div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-7 gap-0.5 sm:gap-1 min-w-max">
                            <template x-for="week in calendarWeeks" :key="JSON.stringify(week)">
                                <template x-for="day in week" :key="day.dateStr">
                                    <div 
                                        @click="$dispatch('select-day', {day: day})"
                                        :class="{
                                            'bg-blue-50 dark:bg-blue-900/30 border-2 border-blue-400 dark:border-blue-600 shadow-md': day.isToday,
                                            'bg-rose-50 dark:bg-rose-900/20': day.isWeekend && !day.isToday && day.isCurrentMonth,
                                            'bg-white dark:bg-slate-800': day.isCurrentMonth && !day.isToday && !day.isWeekend,
                                            'bg-gray-50 dark:bg-slate-900': !day.isCurrentMonth,
                                        }"
                                        class="min-h-[80px] sm:min-h-[100px] p-1 sm:p-2 rounded-lg border border-gray-200 dark:border-slate-700 cursor-pointer hover:shadow-md transition flex flex-col relative group">
                                        {{-- Today indicator ring --}}
                                        <template x-if="day.isToday">
                                            <div class="absolute inset-0 rounded-lg ring-2 ring-blue-400 dark:ring-blue-500 opacity-50"></div>
                                        </template>

                                        <div class="flex items-start justify-between gap-1 mb-0.5 sm:mb-1 relative z-10">
                                            <span 
                                                :class="{
                                                    'text-blue-600 dark:text-blue-400 font-bold text-sm sm:text-base': day.isToday,
                                                    'text-rose-600 dark:text-rose-400 font-semibold': day.isWeekend && !day.isToday && day.isCurrentMonth,
                                                    'text-gray-900 dark:text-white font-semibold': day.isCurrentMonth && !day.isToday && !day.isWeekend,
                                                    'text-gray-400 dark:text-gray-600': !day.isCurrentMonth,
                                                }"
                                                class="text-xs sm:text-sm"
                                                x-text="day.date"></span>
                                            <template x-if="day.eventCount > 0">
                                                <span class="text-xs font-bold bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 px-1.5 sm:px-2 py-0.5 rounded-full flex-shrink-0" x-text="day.eventCount"></span>
                                            </template>
                                        </div>
                                        <div class="flex-1 space-y-0.5 sm:space-y-1 min-w-0 overflow-hidden">
                                            <template x-for="(event, idx) in day.events.slice(0, 2)" :key="idx">
                                                <div 
                                                    @click.stop="showEventDetail(event)"
                                                    :style="'background-color: ' + event.warna + '15; border-left: 3px solid ' + event.warna"
                                                    class="px-1 sm:px-1.5 py-0.5 rounded text-xs font-medium truncate cursor-pointer hover:shadow-sm transition group relative">
                                                    <span class="inline-flex items-center gap-0.5 w-full">
                                                        <span class="w-1 h-1 sm:w-1.5 sm:h-1.5 rounded-full flex-shrink-0" :style="'background-color: ' + event.warna"></span>
                                                        <span class="truncate text-xs" x-text="event.judul"></span>
                                                    </span>
                                                    <template x-if="event.waktu_mulai && !event.is_all_day">
                                                        <div class="text-xs opacity-75 truncate" :style="'color: ' + event.warna" x-text="event.waktu_mulai.substring(0, 5)"></div>
                                                    </template>
                                                </div>
                                            </template>
                                            <template x-if="day.eventCount > 2">
                                                <div class="text-xs font-bold px-1 sm:px-1.5 py-0.5 rounded cursor-pointer hover:shadow-sm transition" :style="'background-color: ' + (day.events[0]?.warna || '#002B6B') + '30; color: ' + (day.events[0]?.warna || '#002B6B')" x-text="'+' + (day.eventCount - 2) + ' lagi'"></div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Today's Events --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-200 dark:border-slate-700 flex items-center justify-between">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-8 sm:w-10 h-8 sm:h-10 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 sm:w-5 h-4 sm:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-base sm:text-lg">Agenda Hari Ini</h3>
                        </div>
                        <span class="px-2 sm:px-3 py-0.5 sm:py-1 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 text-xs font-bold" x-text="todaysEvents.length"></span>
                    </div>
                    <div class="p-3 sm:p-5">
                        <template x-if="todaysEvents.length > 0">
                            <div class="space-y-2 sm:space-y-3">
                                <template x-for="event in todaysEvents" :key="event.id">
                                    <div class="p-3 sm:p-4 rounded-xl border border-gray-200 dark:border-slate-700 hover:shadow-md transition cursor-pointer group text-sm sm:text-base" :style="'border-left: 4px solid ' + event.warna" @click="showEventDetail(event)">
                                        <div class="flex items-start justify-between gap-2 mb-1 sm:mb-2">
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full flex-shrink-0" :style="'background-color: ' + event.warna"></span>
                                                    <p class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition truncate text-sm sm:text-base" x-text="event.judul"></p>
                                                </div>
                                                <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400 flex-wrap">
                                                    <span class="flex items-center gap-1" x-text="event.waktu_formatted"></span>
                                                    <template x-if="event.lokasi">
                                                        <span class="flex items-center gap-1 truncate">
                                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                                            <span class="truncate" x-text="event.lokasi"></span>
                                                        </span>
                                                    </template>
                                                </div>
                                            </div>
                                            <span class="px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-bold flex-shrink-0" :style="'background-color: ' + event.warna + '20; color: ' + event.warna" x-text="event.jenis_kegiatan_label"></span>
                                        </div>
                                        <template x-if="event.deskripsi">
                                            <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 mt-1 sm:mt-2" x-text="event.deskripsi"></p>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="todaysEvents.length === 0">
                            <div class="text-center py-6 sm:py-8">
                                <svg class="w-12 sm:w-16 h-12 sm:h-16 mx-auto text-gray-300 dark:text-gray-600 mb-2 sm:mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-gray-600 dark:text-gray-400 font-medium text-sm">Tidak ada agenda hari ini</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">Nikmati hari yang tenang dan bebas agenda</p>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            {{-- Sidebar --}}
            <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                {{-- Upcoming Events --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden sticky top-24">
                    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-200 dark:border-slate-700">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-8 sm:w-10 h-8 sm:h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 sm:w-5 h-4 sm:h-5 text-emerald-600 dark:text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-base sm:text-lg">Agenda Mendatang</h3>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4 space-y-2 sm:space-y-3 max-h-[500px] overflow-y-auto">
                        <template x-if="upcomingEvents.length > 0">
                            <div class="space-y-2 sm:space-y-3">
                                <template x-for="event in upcomingEvents" :key="event.id">
                                    <div @click="showEventDetail(event)" class="p-2 sm:p-3 rounded-xl border border-gray-200 dark:border-slate-700 hover:shadow-md cursor-pointer transition group text-sm" :style="'border-left: 4px solid ' + event.warna">
                                        <div class="flex items-start justify-between gap-2 mb-1">
                                            <p class="font-semibold text-xs sm:text-sm text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition truncate flex-1" x-text="event.judul"></p>
                                            <span class="px-1.5 sm:px-2 py-0.5 rounded-full text-xs font-bold flex-shrink-0" :style="'background-color: ' + event.warna + '20; color: ' + event.warna" x-text="event.jenis_kegiatan_label"></span>
                                        </div>
                                        <div class="flex items-center gap-1 text-xs text-gray-600 dark:text-gray-400">
                                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            <span x-text="event.tanggal_mulai"></span>
                                        </div>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5 sm:mt-1" x-text="event.waktu_formatted"></p>
                                        <template x-if="event.lokasi">
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5 sm:mt-1 flex items-center gap-1">
                                                <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                                <span x-text="event.lokasi" class="truncate"></span>
                                            </p>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <template x-if="upcomingEvents.length === 0">
                            <div class="text-center py-4 sm:py-6">
                                <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">Tidak ada agenda mendatang</p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Semester Summary --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-700/50 dark:to-slate-800/50 px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-200 dark:border-slate-700">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <svg class="w-5 sm:w-6 h-5 sm:h-6 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <h3 class="font-bold text-gray-900 dark:text-white text-base sm:text-lg">Ringkasan Semester</h3>
                        </div>
                    </div>
                    <div class="p-3 sm:p-4 space-y-2 sm:space-y-3">
                        <div class="flex items-center justify-between p-2 sm:p-3 rounded-lg bg-gray-50 dark:bg-slate-700/50 hover:bg-gray-100 dark:hover:bg-slate-700 transition text-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 sm:w-5 h-4 sm:h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Total Agenda</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900 dark:text-white" x-text="semesterStats.total"></span>
                        </div>
                        <div class="flex items-center justify-between p-2 sm:p-3 rounded-lg bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 transition text-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 sm:w-5 h-4 sm:h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">UTS & UAS</span>
                            </div>
                            <span class="text-base sm:text-lg font-bold text-red-600 dark:text-red-400" x-text="semesterStats.exams"></span>
                        </div>
                        <div class="flex items-center justify-between p-2 sm:p-3 rounded-lg bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 transition text-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 sm:w-5 h-4 sm:h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Libur & Cuti</span>
                            </div>
                            <span class="text-base sm:text-lg font-bold text-green-600 dark:text-green-400" x-text="semesterStats.holidays"></span>
                        </div>
                        <div class="flex items-center justify-between p-2 sm:p-3 rounded-lg bg-orange-50 dark:bg-orange-900/20 hover:bg-orange-100 dark:hover:bg-orange-900/30 transition text-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 sm:w-5 h-4 sm:h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Deadline Tugas</span>
                            </div>
                            <span class="text-base sm:text-lg font-bold text-orange-600 dark:text-orange-400" x-text="semesterStats.deadlines"></span>
                        </div>
                        <div class="flex items-center justify-between p-2 sm:p-3 rounded-lg bg-purple-50 dark:bg-purple-900/20 hover:bg-purple-100 dark:hover:bg-purple-900/30 transition text-sm">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 sm:w-5 h-4 sm:h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17s4.5 10.747 10 10.747c5.5 0 10-4.998 10-10.747S17.5 6.253 12 6.253z"/></svg>
                                <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Praktikum & Acara</span>
                            </div>
                            <span class="text-base sm:text-lg font-bold text-purple-600 dark:text-purple-400" x-text="semesterStats.others"></span>
                        </div>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="bg-gradient-to-br from-indigo-50 via-purple-50 to-pink-50 dark:from-indigo-900/20 dark:via-purple-900/20 dark:to-pink-900/20 rounded-2xl border border-indigo-200 dark:border-indigo-800 p-3 sm:p-5">
                    <div class="flex items-start gap-2 sm:gap-3">
                        <div class="w-8 sm:w-10 h-8 sm:h-10 rounded-full bg-indigo-200 dark:bg-indigo-800 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 sm:w-5 h-4 sm:h-5 text-indigo-700 dark:text-indigo-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-indigo-900 dark:text-indigo-300 mb-1 sm:mb-2 text-sm sm:text-base">Tips Kalender</h4>
                            <ul class="text-xs text-indigo-800 dark:text-indigo-300 space-y-1">
                                <li class="flex gap-2"><span class="text-green-600 font-bold flex-shrink-0">✓</span> <span>Klik event untuk detail lengkap</span></li>
                                <li class="flex gap-2"><span class="text-green-600 font-bold flex-shrink-0">✓</span> <span>Filter kategori sesuai kebutuhan</span></li>
                                <li class="flex gap-2"><span class="text-green-600 font-bold flex-shrink-0">✓</span> <span>Hari ini ditandai dengan garis biru</span></li>
                                <li class="flex gap-2"><span class="text-green-600 font-bold flex-shrink-0">✓</span> <span>Event merah = ujian penting</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Event Detail Modal --}}
<div id="eventModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-3 sm:p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white dark:bg-slate-800 rounded-2xl max-w-md w-full shadow-2xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 flex items-center justify-between p-3 sm:p-4 border-b border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800">
            <div class="flex items-center gap-2 sm:gap-3 min-w-0">
                <div class="w-8 sm:w-10 h-8 sm:h-10 rounded-xl flex items-center justify-center flex-shrink-0" id="modalColorBox">
                    <svg class="w-4 sm:w-5 h-4 sm:h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
                <h3 class="font-bold text-gray-900 dark:text-white text-sm sm:text-base truncate" id="modalTitle"></h3>
            </div>
            <button @click="closeEventModal()" class="p-1.5 sm:p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-700 transition flex-shrink-0">
                <svg class="w-4 sm:w-5 h-4 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-3 sm:p-5 space-y-3 sm:space-y-4 text-sm sm:text-base">
            <div class="flex flex-wrap gap-1.5 sm:gap-2" id="modalBadges"></div>
            <div id="modalDateRange" class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-2 sm:p-3 text-xs sm:text-sm space-y-0.5 sm:space-y-1"></div>
            <div id="modalTime" class="hidden"></div>
            <div id="modalLocation" class="hidden"></div>
            <div id="modalDescription" class="hidden"></div>
            <div id="modalSemester" class="hidden"></div>
        </div>
    </div>
</div>

@push('scripts')
    @include('mahasiswa.kalender-akademik.calendar-script')

    <script>
    // Debug: Log data passed to Alpine
    console.log('=== Calendar Data Debug ===');
    console.log('Total Events:', @json($events)->length || 0);
    console.log('Events by Date:', Object.keys(@json($eventsByDate) || {}).length || 0);
    console.log('Selected Semester:', "{{ $selectedSemesterId ?? '' }}");
    console.log('Current Month/Year:', "{{ $selectedMonth }}/{{ $selectedYear }}");
    console.log('Event Categories:', @json(array_unique(array_column($events->toArray(), 'jenis_kegiatan'))) || []);

    document.addEventListener('alpine:init', () => {
        Alpine.listen('select-day', ({detail}) => {
            console.log('Day selected:', detail.day);
        });
    });

    // Event modal handler
    document.addEventListener('alpine:initialized', () => {
        const modal = document.getElementById('eventModal');
        const calendar = document.querySelector('[x-data="calendar"]');
        
        if (!calendar) return;
        
        const calendarData = Alpine.raw(calendar).__x.getUnobservedData();
        
        // Override showEventDetail di calendar untuk update modal
        const originalShow = calendarData.showEventDetail;
        calendarData.showEventDetail = function(event) {
            console.log('Opening event detail:', event.judul);
            
            // Set color
            const colorBox = document.getElementById('modalColorBox');
            if (colorBox && event.warna) {
                colorBox.style.backgroundColor = event.warna;
            }

            // Set title
            document.getElementById('modalTitle').textContent = event.judul;

            // Set badges - Category, Time Format, All Day indicator
            const badges = document.getElementById('modalBadges');
            let badgesHTML = `<span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: ${event.warna}20; color: ${event.warna}">${event.jenis_kegiatan_label}</span>`;
            
            if (event.is_all_day) {
                badgesHTML += `<span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">Sepanjang Hari</span>`;
            } else if (event.waktu_formatted) {
                badgesHTML += `<span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">${event.waktu_formatted}</span>`;
            }
            badges.innerHTML = badgesHTML;

            // Set date range
            const dateRange = document.getElementById('modalDateRange');
            let dateHTML = `<div class="flex items-center gap-2 text-gray-900 dark:text-white font-semibold">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>${event.tanggal_mulai}`;
            
            if (event.tanggal_selesai && event.tanggal_selesai !== event.tanggal_mulai) {
                dateHTML += ` hingga ${event.tanggal_selesai}`;
            }
            dateHTML += `</span></div>`;
            
            if (!event.is_all_day && event.waktu_mulai) {
                dateHTML += `<div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>${event.waktu_formatted}</span>
                </div>`;
            }
            dateRange.innerHTML = dateHTML;

            // Set description
            const descEl = document.getElementById('modalDescription');
            if (event.deskripsi) {
                descEl.classList.remove('hidden');
                descEl.innerHTML = `
                    <div class="pt-3 border-t border-gray-200 dark:border-slate-700">
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wide">Deskripsi</p>
                        <p class="text-sm text-gray-900 dark:text-white leading-relaxed whitespace-pre-wrap">${event.deskripsi}</p>
                    </div>
                `;
            } else {
                descEl.classList.add('hidden');
            }

            // Set location
            const locEl = document.getElementById('modalLocation');
            if (event.lokasi) {
                locEl.classList.remove('hidden');
                locEl.innerHTML = `
                    <div class="pt-3 border-t border-gray-200 dark:border-slate-700">
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wide">Lokasi</p>
                        <p class="text-sm text-gray-900 dark:text-white flex items-start gap-2">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <span>${event.lokasi}</span>
                        </p>
                    </div>
                `;
            } else {
                locEl.classList.add('hidden');
            }

            // Set semester info
            const semEl = document.getElementById('modalSemester');
            if (event.semester) {
                semEl.classList.remove('hidden');
                semEl.innerHTML = `
                    <div class="pt-3 border-t border-gray-200 dark:border-slate-700">
                        <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2 uppercase tracking-wide">Semester</p>
                        <p class="text-sm text-gray-900 dark:text-white">${event.semester.tahun_ajaran} - ${event.semester.nama_semester}</p>
                    </div>
                `;
            } else {
                semEl.classList.add('hidden');
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };

        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                calendarData.closeEventModal();
            }
        });
    });
    </script>
@endpush

@endsection
