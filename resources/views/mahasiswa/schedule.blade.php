@extends('layouts.portal')
@section('title', 'Jadwal Kuliah')
@section('content')

@php
    $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $hariIni = now()->locale('id')->dayName;
    
    // Fallback if day is not in array
    if (!in_array($hariIni, $days)) {
        $hariIni = 'Senin';
    }
    
    $eventsByDay = [];
    foreach ($kelasList as $k) {
        $eventsByDay[$k->hari ?? 'Senin'][] = $k;
    }
    foreach ($eventsByDay as $day => $items) {
        usort($items, fn($a,$b) => strcmp($a->jam_mulai ?? '07:00', $b->jam_mulai ?? '07:00'));
        $eventsByDay[$day] = $items;
    }
    
    $colors = ['border-blue-400 bg-blue-50/80', 'border-violet-400 bg-violet-50/80', 'border-emerald-400 bg-emerald-50/80', 'border-amber-400 bg-amber-50/80', 'border-rose-400 bg-rose-50/80'];
    $colorIdx = 0;
    $kelasColorMap = [];
    foreach ($kelasList as $k) {
        if (!isset($kelasColorMap[$k->id])) {
            $kelasColorMap[$k->id] = $colors[$colorIdx % count($colors)];
            $colorIdx++;
        }
    }
    $startHour = 7; $endHour = 20; $hourHeight = 80;
    $totalHeight = ($endHour - $startHour) * $hourHeight;
    $headerHeight = 44;
@endphp

@php
    $kelasListJson = $kelasList->map(fn($k) => [
        'id' => $k->id,
        'nama_mk' => $k->mataKuliah?->nama_mk ?? '-',
        'kode_mk' => $k->mataKuliah?->kode_mk ?? '-',
        'hari' => $k->hari ?? 'Senin',
        'jam_mulai' => substr($k->jam_mulai ?? '07:00', 0, 5),
        'jam_selesai' => substr($k->jam_selesai ?? '08:00', 0, 5),
        'ruangan' => $k->ruangan ?? '-',
        'sks' => $k->mataKuliah?->sks ?? 0,
        'dosen' => $k->dosen?->name ?? 'Belum Ditentukan'
    ]);
@endphp

<div x-data="{ 
    viewMode: 'weekly', 
    activeDay: '{{ $hariIni }}',
    currentYear: new Date().getFullYear(),
    currentMonth: new Date().getMonth(),
    months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
    events: @json($kelasListJson),
    selectedDayObj: null,

    getDays() {
        const days = [];
        const firstDayIndex = new Date(this.currentYear, this.currentMonth, 1).getDay();
        const lastDay = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        const prevLastDay = new Date(this.currentYear, this.currentMonth, 0).getDate();
        
        // Pad previous month
        for (let i = firstDayIndex; i > 0; i--) {
            const d = prevLastDay - i + 1;
            const m = this.currentMonth === 0 ? 11 : this.currentMonth - 1;
            const y = this.currentMonth === 0 ? this.currentYear - 1 : this.currentYear;
            const date = new Date(y, m, d);
            days.push({
                day: d,
                isCurrentMonth: false,
                dayOfWeek: date.getDay(),
                dateStr: `${y}-${String(m + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`
            });
        }
        
        // Current month
        for (let i = 1; i <= lastDay; i++) {
            const date = new Date(this.currentYear, this.currentMonth, i);
            days.push({
                day: i,
                isCurrentMonth: true,
                dayOfWeek: date.getDay(),
                dateStr: `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`
            });
        }
        
        // Pad next month
        const totalCells = days.length > 35 ? 42 : 35;
        const nextDaysCount = totalCells - days.length;
        for (let i = 1; i <= nextDaysCount; i++) {
            const m = this.currentMonth === 11 ? 0 : this.currentMonth + 1;
            const y = this.currentMonth === 11 ? this.currentYear + 1 : this.currentYear;
            const date = new Date(y, m, i);
            days.push({
                day: i,
                isCurrentMonth: false,
                dayOfWeek: date.getDay(),
                dateStr: `${y}-${String(m + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`
            });
        }
        
        return days;
    },

    getEventsForDayOfWeek(dayOfWeek) {
        const dayMapping = {
            1: 'Senin',
            2: 'Selasa',
            3: 'Rabu',
            4: 'Kamis',
            5: 'Jumat',
            6: 'Sabtu',
            0: 'Minggu'
        };
        const indonesianDayName = dayMapping[dayOfWeek];
        return this.events.filter(e => e.hari === indonesianDayName);
    },

    prevMonth() {
        if (this.currentMonth === 0) {
            this.currentMonth = 11;
            this.currentYear--;
        } else {
            this.currentMonth--;
        }
        this.selectedDayObj = null;
    },

    nextMonth() {
        if (this.currentMonth === 11) {
            this.currentMonth = 0;
            this.currentYear++;
        } else {
            this.currentMonth++;
        }
        this.selectedDayObj = null;
    },

    selectDay(dayObj) {
        this.selectedDayObj = dayObj;
    }
}" class="space-y-6">

    {{-- HEADER CARD --}}
    <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-[#002B6B] to-[#0044a8] px-6 py-6 sm:px-8 shadow-lg shadow-blue-950/15 relative">
        <div class="pointer-events-none absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/5"></div>
        <div class="pointer-events-none absolute right-20 bottom-0 h-20 w-20 rounded-full bg-white/5"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-blue-200/80">Semester Aktif</p>
                <h1 class="mt-1 text-xl font-extrabold text-white sm:text-2xl">Jadwal Kuliah</h1>
                <p class="mt-1 text-sm text-blue-100/80">Seluruh jadwal kelas yang kamu ikuti minggu ini.</p>
            </div>
            <div class="shrink-0 rounded-xl border border-white/20 bg-white/10 px-4 py-2 text-center">
                <p class="text-xs text-blue-200/80 font-medium">Hari ini</p>
                <p class="text-sm font-extrabold text-white">{{ now()->locale('id')->dayName }}, {{ now()->format('d M') }}</p>
            </div>
        </div>
    </div>

    @if ($kelasList->isEmpty())
        <div class="flex min-h-[280px] flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-10 text-center shadow-sm">
            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-800 text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="font-bold text-gray-700 dark:text-slate-200">Belum Ada Jadwal</h3>
            <p class="mt-1 text-xs text-gray-400 dark:text-slate-500">Gabung ke kelas dulu untuk melihat jadwalmu.</p>
            <a href="{{ route('mahasiswa.jelajahi-kelas') }}" class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-[#002B6B] px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800 transition">
                Jelajahi Kelas
            </a>
        </div>
    @else
        <!-- VIEW SELECTOR & DAY SELECTOR TABS -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 sm:p-4 rounded-2xl shadow-sm">
            <div class="flex items-center gap-1 bg-gray-100 dark:bg-slate-800 p-1 rounded-xl w-fit">
                <button @click="viewMode = 'weekly'" :class="viewMode === 'weekly' ? 'bg-[#002B6B] text-white dark:bg-blue-600' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'" class="px-4 py-2 text-xs font-bold rounded-lg transition">
                    Weekly View
                </button>
                <button @click="viewMode = 'daily'" :class="viewMode === 'daily' ? 'bg-[#002B6B] text-white dark:bg-blue-600' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'" class="px-4 py-2 text-xs font-bold rounded-lg transition">
                    Daily View
                </button>
                <button @click="viewMode = 'monthly'" :class="viewMode === 'monthly' ? 'bg-[#002B6B] text-white dark:bg-blue-600' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'" class="px-4 py-2 text-xs font-bold rounded-lg transition">
                    Monthly/Yearly View
                </button>
            </div>
            
            <div x-show="viewMode === 'daily'" x-transition class="flex flex-wrap gap-1">
                @foreach ($days as $day)
                    <button @click="activeDay = '{{ $day }}'" :class="activeDay === '{{ $day }}' ? 'bg-[#002B6B]/10 text-[#002B6B] border-[#002B6B] dark:bg-blue-600/10 dark:text-blue-400 dark:border-blue-500/50 font-extrabold' : 'bg-transparent text-gray-500 border-transparent dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'" class="px-3 py-1.5 text-xs font-bold border rounded-lg transition">
                        {{ $day }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- WEEKLY VIEW -->
        <div x-show="viewMode === 'weekly'" x-transition class="space-y-6">
            {{-- MOBILE: List view --}}
            <div class="md:hidden space-y-4">
                @foreach ($days as $day)
                    @php $events = $eventsByDay[$day] ?? []; @endphp
                    @if (!empty($events))
                        <div class="overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <div @click="viewMode = 'daily'; activeDay = '{{ $day }}'" class="flex items-center justify-between px-4 py-2.5 {{ $day === $hariIni ? 'bg-[#002B6B]' : 'bg-gray-50 dark:bg-slate-800/40' }} border-b border-gray-100 dark:border-slate-800 cursor-pointer hover:opacity-90 transition">
                                <span class="text-xs font-bold {{ $day === $hariIni ? 'text-white' : 'text-gray-700 dark:text-slate-200' }}">{{ $day }}</span>
                                <div class="flex items-center gap-1.5">
                                    @if ($day === $hariIni)
                                        <span class="rounded-full bg-white/20 px-2 py-0.5 text-[10px] font-bold text-white">Hari Ini</span>
                                    @endif
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 {{ $day === $hariIni ? 'text-white/80' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                            <div class="divide-y divide-gray-50 dark:divide-slate-800/50">
                                @foreach ($events as $ev)
                                    <div class="flex items-center gap-3 px-4 py-3">
                                        <div class="shrink-0 text-center w-14">
                                            <p class="text-xs font-bold text-gray-700 dark:text-slate-200">{{ substr($ev->jam_mulai,0,5) }}</p>
                                            <p class="text-[10px] text-gray-400">{{ substr($ev->jam_selesai,0,5) }}</p>
                                        </div>
                                        <div class="flex-1 min-w-0 rounded-xl border-l-4 {{ $kelasColorMap[$ev->id] ?? $colors[0] }} px-3 py-2">
                                            <p class="text-sm font-semibold text-gray-800 dark:text-slate-200 truncate">{{ $ev->mataKuliah?->nama_mk ?? '-' }}</p>
                                            <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-0.5">{{ $ev->ruangan ?? '-' }} · {{ $ev->mataKuliah?->sks ?? 0 }} SKS</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- DESKTOP: Grid timetable --}}
            <div class="hidden md:block overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-slate-800">
                                <th class="w-16 px-3 py-2 text-center text-[10px] font-semibold text-gray-400 dark:text-slate-500"></th>
                                @foreach ($days as $day)
                                    @php $isToday = $day === $hariIni; @endphp
                                    <th @click="viewMode = 'daily'; activeDay = '{{ $day }}'" class="w-32 px-3 py-3 text-center border-l border-gray-100 dark:border-slate-800 {{ $isToday ? 'bg-[#002B6B]' : 'bg-gray-50/80 dark:bg-slate-800/40' }} cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition duration-150">
                                        <span class="text-xs font-bold {{ $isToday ? 'text-white' : 'text-gray-600 dark:text-slate-300' }}">{{ $day }}</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @for ($h = $startHour; $h < $endHour; $h++)
                                <tr class="border-b border-gray-50 dark:border-slate-850">
                                    <td class="w-16 px-3 py-2 text-center border-r border-gray-100 dark:border-slate-800">
                                        <span class="text-[10px] font-medium text-gray-400 dark:text-slate-500">{{ sprintf('%02d:00', $h) }}</span>
                                    </td>
                                    @foreach ($days as $day)
                                        <td class="w-32 border-l border-gray-100 dark:border-slate-800 p-0.5 align-top relative" style="height: 80px;">
                                            @php
                                                $eventsAtThisHour = [];
                                                foreach ($eventsByDay[$day] ?? [] as $ev) {
                                                    [$sh, $sm] = array_pad(explode(':', $ev->jam_mulai ?? '07:00'), 2, '00');
                                                    if ((int)$sh === $h) {
                                                        $eventsAtThisHour[] = $ev;
                                                    }
                                                }
                                            @endphp
                                            
                                            @foreach ($eventsAtThisHour as $ev)
                                                @php
                                                    [$sh, $sm] = array_pad(explode(':', $ev->jam_mulai ?? '07:00'), 2, '00');
                                                    [$eh, $em] = array_pad(explode(':', $ev->jam_selesai ?? '08:00'), 2, '00');
                                                    $startMin = (int)$sh * 60 + (int)$sm;
                                                    $endMin   = (int)$eh * 60 + (int)$em;
                                                    $durationMin = $endMin - $startMin;
                                                    $durationHours = $durationMin / 60;
                                                    $rowsSpanned = ceil($durationHours);
                                                    $heightPx = $rowsSpanned * 80;
                                                    $evColor  = $kelasColorMap[$ev->id] ?? $colors[0];
                                                @endphp
                                                <div @click="viewMode = 'daily'; activeDay = '{{ $day }}'" class="absolute left-0.5 right-0.5 rounded-lg border-l-4 overflow-hidden {{ $evColor }} shadow-sm p-1.5 text-left text-[10px] cursor-pointer hover:scale-98 hover:shadow-md transition" 
                                                     style="height: {{ $heightPx }}px; top: 2px; z-index: 10;">
                                                    <p class="font-bold text-gray-800 dark:text-slate-200 leading-tight line-clamp-2">{{ $ev->mataKuliah?->nama_mk ?? '-' }}</p>
                                                    @if ($heightPx > 45)
                                                        <p class="text-gray-600 dark:text-slate-350 line-clamp-1 mt-1 font-semibold">{{ $ev->ruangan ?? '-' }}</p>
                                                        <p class="text-gray-500 dark:text-slate-400 mt-1">{{ substr($ev->jam_mulai,0,5) }} - {{ substr($ev->jam_selesai,0,5) }}</p>
                                                    @else
                                                        <p class="text-gray-600 dark:text-slate-350 line-clamp-1 mt-0.5">{{ substr($ev->jam_mulai,0,5) }} - {{ substr($ev->jam_selesai,0,5) }}</p>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </td>
                                    @endforeach
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- DAILY VIEW -->
        <div x-show="viewMode === 'daily'" x-transition class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#002B6B] dark:bg-blue-500"></span>
                    <span>Jadwal Hari <span x-text="activeDay"></span></span>
                </h2>
                <span x-show="activeDay === '{{ $hariIni }}'" class="rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 px-3 py-1 text-xs font-bold">Hari Ini</span>
            </div>

            @foreach ($days as $day)
                @php $events = $eventsByDay[$day] ?? []; @endphp
                <div x-show="activeDay === '{{ $day }}'" class="space-y-4">
                    @forelse ($events as $ev)
                        <div class="flex flex-col sm:flex-row gap-4 p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition">
                            <div class="flex items-center gap-2 sm:flex-col sm:items-start sm:justify-center sm:w-28 shrink-0">
                                <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-slate-800 text-xs font-bold text-gray-800 dark:text-slate-200">{{ substr($ev->jam_mulai,0,5) }}</span>
                                <span class="text-xs text-gray-400 font-medium">sampai</span>
                                <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-slate-800 text-xs font-bold text-gray-800 dark:text-slate-200">{{ substr($ev->jam_selesai,0,5) }}</span>
                            </div>
                            
                            <div class="flex-1 min-w-0 rounded-2xl border-l-4 {{ $kelasColorMap[$ev->id] ?? $colors[0] }} px-4 py-3 bg-slate-50/50 dark:bg-slate-800/40">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h4 class="text-base font-bold text-slate-800 dark:text-white leading-tight">{{ $ev->mataKuliah?->nama_mk ?? '-' }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 font-semibold flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m5 0h5M9 7h.01M9 11h.01M9 15h.01M12 7h.01M12 11h.01M12 15h.01M15 7h.01M15 11h.01M15 15h.01"/>
                                            </svg>
                                            {{ $ev->ruangan ?? '-' }}
                                        </p>
                                    </div>
                                    <span class="rounded-full bg-blue-100 text-blue-800 dark:bg-blue-950/40 dark:text-blue-400 px-3 py-1 text-xs font-extrabold shrink-0">{{ $ev->mataKuliah?->sks ?? 0 }} SKS</span>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-200/60 dark:border-slate-850 flex items-center justify-between text-xs text-gray-500 dark:text-slate-400">
                                    <span class="font-medium">Dosen Pengampu:</span>
                                    <span class="font-bold text-slate-800 dark:text-white">{{ $ev->dosen?->name ?? 'Belum Ditentukan' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center p-12 rounded-2xl border-2 border-dashed border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-center shadow-sm">
                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-50 dark:bg-slate-850 text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-700 dark:text-slate-200">Tidak Ada Kelas</h4>
                            <p class="text-xs text-gray-450 dark:text-slate-500 mt-1">Kamu bebas hari ini. Tetap produktif!</p>
                        </div>
                    @endforelse
                </div>
            @endforeach
        </div>

        <!-- MONTHLY VIEW -->
        <div x-show="viewMode === 'monthly'" x-transition class="space-y-6">
            {{-- Navigation and Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-4 rounded-2xl shadow-sm">
                <div class="flex items-center gap-3">
                    <span class="text-lg font-bold text-gray-800 dark:text-white" x-text="months[currentMonth] + ' ' + currentYear"></span>
                </div>
                <div class="flex items-center gap-2">
                    <button @click="prevMonth()" class="w-8 h-8 rounded-xl border border-gray-200 dark:border-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 flex items-center justify-center font-bold transition">
                        &larr;
                    </button>
                    <button @click="currentYear = new Date().getFullYear(); currentMonth = new Date().getMonth(); selectedDayObj = null;" class="px-3 h-8 text-xs font-bold rounded-xl border border-gray-200 dark:border-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                        Bulan Ini
                    </button>
                    <button @click="nextMonth()" class="w-8 h-8 rounded-xl border border-gray-200 dark:border-slate-800 text-gray-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-slate-800 flex items-center justify-center font-bold transition">
                        &rarr;
                    </button>
                </div>
            </div>

            {{-- Grid --}}
            <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="grid grid-cols-7 border-b border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-850">
                    @foreach(['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $wd)
                        <div class="py-3 text-center text-xs font-bold text-gray-500 dark:text-slate-400">{{ $wd }}</div>
                    @endforeach
                </div>
                <div class="grid grid-cols-7 divide-x divide-y divide-gray-100 dark:divide-slate-800/60 border-l border-t border-gray-100 dark:border-slate-800">
                    <template x-for="dayObj in getDays()" :key="dayObj.dateStr">
                        <div 
                            @click="selectDay(dayObj)" 
                            :class="[
                                !dayObj.isCurrentMonth ? 'bg-gray-50/40 dark:bg-slate-950/20 text-gray-300 dark:text-slate-700' : 'text-gray-800 dark:text-slate-200 bg-white dark:bg-slate-900',
                                selectedDayObj && selectedDayObj.dateStr === dayObj.dateStr ? 'ring-2 ring-inset ring-blue-500 bg-blue-50/30 dark:bg-blue-950/20' : ''
                            ]" 
                            class="min-h-[90px] p-2 flex flex-col justify-between cursor-pointer hover:bg-gray-50/60 dark:hover:bg-slate-800/40 transition relative group"
                        >
                            {{-- Day number and markers --}}
                            <div class="flex items-center justify-between">
                                <span :class="dayObj.isCurrentMonth ? 'font-bold' : 'font-medium'" class="text-xs" x-text="dayObj.day"></span>
                                
                                {{-- Small count indicator for classes --}}
                                <template x-if="getEventsForDayOfWeek(dayObj.dayOfWeek).length > 0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#002B6B] dark:bg-blue-500"></span>
                                </template>
                            </div>

                            {{-- Shorthand display of classes inside the cell (desktop only) --}}
                            <div class="hidden sm:block mt-1 space-y-1 overflow-y-hidden max-h-[52px]">
                                <template x-for="ev in getEventsForDayOfWeek(dayObj.dayOfWeek).slice(0, 2)" :key="ev.id">
                                    <div class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-blue-50 dark:bg-blue-950/40 border-l-2 border-blue-500 text-blue-800 dark:text-blue-400 truncate" :title="ev.nama_mk">
                                        <span x-text="ev.jam_mulai + ' '"></span>
                                        <span x-text="ev.nama_mk"></span>
                                    </div>
                                </template>
                                <template x-if="getEventsForDayOfWeek(dayObj.dayOfWeek).length > 2">
                                    <div class="text-[8px] text-gray-400 dark:text-slate-500 text-center font-bold">
                                        +<span x-text="getEventsForDayOfWeek(dayObj.dayOfWeek).length - 2"></span> Kelas Lain
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Detail Drawer / List for Selected Day --}}
            <div x-show="selectedDayObj" x-transition class="space-y-4 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-5 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#002B6B] dark:bg-blue-500"></span>
                        <span>Jadwal Tanggal <span x-text="selectedDayObj ? (selectedDayObj.day + ' ' + months[currentMonth] + ' ' + currentYear) : ''"></span></span>
                    </h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <template x-for="ev in (selectedDayObj ? getEventsForDayOfWeek(selectedDayObj.dayOfWeek) : [])" :key="ev.id">
                        <div class="flex gap-4 p-4 rounded-xl border border-gray-100 dark:border-slate-800 bg-gray-50/30 dark:bg-slate-950/20">
                            <div class="flex flex-col justify-center items-center w-20 shrink-0 border-r border-gray-100 dark:border-slate-800 pr-3">
                                <span class="text-xs font-bold text-gray-800 dark:text-slate-200" x-text="ev.jam_mulai"></span>
                                <span class="text-[10px] text-gray-400 dark:text-slate-550 my-0.5">s/d</span>
                                <span class="text-xs font-bold text-gray-800 dark:text-slate-200" x-text="ev.jam_selesai"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white truncate" x-text="ev.nama_mk"></h4>
                                <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-1 font-semibold flex items-center gap-1">
                                    <span>SKS: </span><strong x-text="ev.sks"></strong> · 
                                    <span>Ruangan: </span><strong x-text="ev.ruangan"></strong>
                                </p>
                                <p class="text-[11px] text-gray-400 dark:text-slate-500 mt-1">
                                    Dosen: <span class="font-bold text-slate-750 dark:text-slate-300" x-text="ev.dosen"></span>
                                </p>
                            </div>
                        </div>
                    </template>
                </div>
                
                <template x-if="selectedDayObj && getEventsForDayOfWeek(selectedDayObj.dayOfWeek).length === 0">
                    <div class="text-center py-6 text-gray-400 dark:text-slate-500 text-xs font-medium">
                        Tidak ada kelas kuliah di hari ini. Kamu bebas belajar mandiri!
                    </div>
                </template>
            </div>
        </div>

    @endif
</div>

@endsection
