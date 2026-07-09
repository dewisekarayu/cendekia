@extends('layouts.portal')
@section('title', 'Jadwal Kuliah')
@section('content')

<div class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-br from-[#002B6B] to-[#0044a8] px-6 py-6 sm:px-8 shadow-lg shadow-blue-950/15 relative">
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
    <div class="flex min-h-[280px] flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-white p-10 text-center shadow-sm">
        <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 border border-gray-100 text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <h3 class="font-bold text-gray-700">Belum Ada Jadwal</h3>
        <p class="mt-1 text-xs text-gray-400">Gabung ke kelas dulu untuk melihat jadwalmu.</p>
        <a href="{{ route('mahasiswa.jelajahi-kelas') }}" class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-[#002B6B] px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800 transition">
            Jelajahi Kelas
        </a>
    </div>
@else
    @php
        $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $hariIni = now()->locale('id')->dayName;
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

    {{-- MOBILE: List view --}}
    <div class="md:hidden space-y-4">
        @foreach ($days as $day)
            @php $events = $eventsByDay[$day] ?? []; @endphp
            @if (!empty($events))
                <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                    <div class="flex items-center justify-between px-4 py-2.5 {{ $day === $hariIni ? 'bg-[#002B6B]' : 'bg-gray-50' }} border-b border-gray-100">
                        <span class="text-xs font-bold {{ $day === $hariIni ? 'text-white' : 'text-gray-700' }}">{{ $day }}</span>
                        @if ($day === $hariIni)
                            <span class="rounded-full bg-white/20 px-2 py-0.5 text-[10px] font-bold text-white">Hari Ini</span>
                        @endif
                    </div>
                    <div class="divide-y divide-gray-50">
                        @foreach ($events as $ev)
                            <div class="flex items-center gap-3 px-4 py-3">
                                <div class="shrink-0 text-center w-14">
                                    <p class="text-xs font-bold text-gray-700">{{ substr($ev->jam_mulai,0,5) }}</p>
                                    <p class="text-[10px] text-gray-400">{{ substr($ev->jam_selesai,0,5) }}</p>
                                </div>
                                <div class="flex-1 min-w-0 rounded-xl border-l-4 {{ $kelasColorMap[$ev->id] ?? $colors[0] }} px-3 py-2">
                                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $ev->mataKuliah?->nama_mk ?? '-' }}</p>
                                    <p class="text-[11px] text-gray-500 mt-0.5">{{ $ev->ruangan ?? '-' }} · {{ $ev->mataKuliah?->sks ?? 0 }} SKS</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- DESKTOP: Grid timetable --}}
    <div class="hidden md:block overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="w-16 px-3 py-2 text-center text-[10px] font-semibold text-gray-400"></th>
                        @foreach ($days as $day)
                            @php $isToday = $day === $hariIni; @endphp
                            <th class="w-32 px-3 py-3 text-center border-l border-gray-100 {{ $isToday ? 'bg-[#002B6B]' : 'bg-gray-50/80' }}">
                                <span class="text-xs font-bold {{ $isToday ? 'text-white' : 'text-gray-600' }}">{{ $day }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @for ($h = $startHour; $h < $endHour; $h++)
                        <tr class="border-b border-gray-50">
                            <td class="w-16 px-3 py-2 text-center border-r border-gray-100">
                                <span class="text-[10px] font-medium text-gray-400">{{ sprintf('%02d:00', $h) }}</span>
                            </td>
                            @foreach ($days as $day)
                                <td class="w-32 border-l border-gray-100 p-0.5 align-top relative" style="height: 80px;">
                                    @php
                                        // Only render events that START at this hour
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
                                        <div class="absolute left-0.5 right-0.5 rounded-lg border-l-4 overflow-hidden {{ $evColor }} shadow-sm p-1.5 text-left text-[10px]" 
                                             style="height: {{ $heightPx }}px; top: 2px;">
                                            <p class="font-bold text-gray-800 leading-tight line-clamp-2">{{ $ev->mataKuliah?->nama_mk ?? '-' }}</p>
                                            @if ($heightPx > 45)
                                                <p class="text-gray-600 line-clamp-1 mt-1 font-semibold">{{ $ev->ruangan ?? '-' }}</p>
                                                <p class="text-gray-500 mt-1">{{ substr($ev->jam_mulai,0,5) }} - {{ substr($ev->jam_selesai,0,5) }}</p>
                                            @else
                                                <p class="text-gray-600 line-clamp-1 mt-0.5">{{ substr($ev->jam_mulai,0,5) }} - {{ substr($ev->jam_selesai,0,5) }}</p>
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
@endif

@endsection
