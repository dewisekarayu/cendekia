@extends('layouts.portal')

@section('title', 'Schedule')
@section('activeMenu', 'Schedule')

@section('content')

    <div class="bg-blue-900 rounded-xl px-5 sm:px-8 py-6 relative overflow-hidden mb-6 sm:mb-8">
        <div class="mb-2 sm:mb-6">
            <h1 class="text-lg sm:text-xl font-bold text-white">Teaching Schedule</h1>
            <p class="text-sm text-white/80 mt-1">Jadwal dari semua kelas yang kamu ikuti minggu ini.</p>
        </div>
    </div>

    @if ($kelasList->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 p-10 text-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="text-gray-500 text-sm">Belum ada jadwal. Gabung ke kelas dulu untuk melihat jadwalmu.</p>
        </div>
    @else
        @php
            $startHour = 7;
            $endHour = 20;
            $hourHeight = 80; // pixels per hour
            $headerHeight = 48; // px spacer for header row
            $totalHeight = ($endHour - $startHour) * $hourHeight; // total px height (hours area)
            $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            $eventsByDay = [];
            foreach ($kelasList as $k) {
                $eventsByDay[$k->hari ?? 'Senin'][] = $k;
            }
            // urutkan tiap hari berdasarkan jam mulai supaya rapi di tampilan mobile
            foreach ($eventsByDay as $day => $items) {
                usort($items, fn($a, $b) => strcmp($a->jam_mulai ?? '07:00', $b->jam_mulai ?? '07:00'));
                $eventsByDay[$day] = $items;
            }
        @endphp

        {{-- ================= MOBILE / TABLET VIEW (list per hari) ================= --}}
        <div class="md:hidden space-y-4">
            @foreach ($days as $day)
                @php $events = $eventsByDay[$day] ?? []; @endphp
                @if (!empty($events))
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="bg-blue-50 text-blue-700 text-xs font-semibold px-4 py-2.5 border-b border-blue-100">
                            {{ $day }}
                        </div>
                        <div class="divide-y divide-gray-50">
                            @foreach ($events as $ev)
                                <div class="flex gap-3 px-4 py-3">
                                    <div class="w-16 shrink-0 text-[11px] text-gray-500 leading-tight pt-0.5">
                                        {{ $ev->jam_mulai }}<br>{{ $ev->jam_selesai }}
                                    </div>
                                    <div class="flex-1 min-w-0 border-l-4 border-blue-500 bg-blue-50/60 rounded-md px-3 py-2">
                                        <div class="text-sm font-semibold text-gray-800 truncate">
                                            {{ $ev->mataKuliah->nama_mk ?? '-' }}
                                        </div>
                                        <div class="text-[11px] text-gray-600 mt-0.5">
                                            {{ $ev->ruangan ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach

            @if (collect($eventsByDay)->flatten()->isEmpty())
                <div class="bg-white rounded-xl border border-gray-100 p-8 text-center shadow-sm">
                    <p class="text-gray-500 text-sm">Tidak ada jadwal minggu ini.</p>
                </div>
            @endif
        </div>

        {{-- ================= DESKTOP VIEW (grid mingguan) ================= --}}
        <div class="hidden md:block bg-white rounded-xl border border-gray-100 shadow-sm p-4 overflow-x-auto">
            <div class="flex min-w-[820px]">
                <!-- Time column -->
                <div class="flex-none w-14 pr-2">
                    <div style="height:{{ $headerHeight }}px"></div>
                    <div class="relative" style="height:{{ $totalHeight }}px">
                        @for ($h = $startHour; $h < $endHour; $h++)
                            <div style="height:{{ $hourHeight }}px" class="text-[11px] text-gray-400 -mt-2 text-right pr-1">
                                {{ sprintf('%02d:00', $h) }}
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Days grid -->
                <div class="flex-1">
                    <div class="grid grid-cols-6 gap-3">
                        @foreach ($days as $day)

                            <div class="border-l border-gray-100 bg-white min-w-[110px]">
                                <div class="sticky top-0 bg-blue-50 text-xs text-blue-700 text-center border-b border-blue-100 font-semibold" style="height:{{$headerHeight}}px; line-height:{{$headerHeight}}px;">
                                    {{ $day }}
                                </div>

                                <div class="relative" style="height:{{$totalHeight}}px">
                                    {{-- background hour rows for grid lines --}}
                                    @for ($h = $startHour; $h < $endHour; $h++)
                                        <div style="height:{{$hourHeight}}px" class="border-b border-gray-100"></div>
                                    @endfor

                                    @php $events = $eventsByDay[$day] ?? []; @endphp
                                    @foreach ($events as $idx => $ev)
                                        @php
                                            [$sh, $sm] = explode(':', $ev->jam_mulai ?? '07:00');
                                            [$eh, $em] = explode(':', $ev->jam_selesai ?? '08:00');
                                            $startMin = intval($sh) * 60 + intval($sm);
                                            $endMin = intval($eh) * 60 + intval($em);
                                            $offset = max(0, $startMin - ($startHour * 60));
                                            $duration = max(15, $endMin - $startMin);
                                            $topPx = $offset * ($hourHeight / 60);
                                            $heightPx = max(24, $duration * ($hourHeight / 60));
                                        @endphp

                                        <div class="absolute left-2 right-2 rounded-md shadow-sm overflow-hidden" style="top:{{ $topPx }}px; height:{{ $heightPx }}px; background-color: rgba(59,130,246,0.08); border-left:4px solid rgba(59,130,246,1);">
                                            <div class="p-2 text-xs text-gray-800">
                                                <div class="font-semibold truncate">{{ $ev->mataKuliah->nama_mk ?? '-' }}</div>
                                                <div class="text-[11px] text-gray-600 truncate">{{ $ev->ruangan ?? '' }}</div>
                                                <div class="text-[11px] text-gray-500 mt-1">{{ $ev->jam_mulai }} - {{ $ev->jam_selesai }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection