@extends('layouts.portal')

@section('title', 'Schedule')
@section('activeMenu', 'Schedule')

@section('content')

    <div class="bg-blue-900 rounded-xl px-8 py-6 text-white mb-8">
        <h1 class="text-2xl font-bold">Jadwal Kuliah</h1>
        <p class="text-blue-200 text-sm mt-1">Jadwal dari semua kelas yang kamu ikuti minggu ini.</p>
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
            $headerHeight = 48; // px spacer for header (matches time column spacer)
            $totalMinutes = ($endHour - $startHour) * 60; // total minutes in view
            $totalHeight = ($endHour - $startHour) * $hourHeight; // total px height (hours area)
            $containerHeight = $headerHeight + $totalHeight; // include header
            $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            $eventsByDay = [];
            foreach ($kelasList as $k) {
                $eventsByDay[$k->hari ?? 'Senin'][] = $k;
            }
        @endphp

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 overflow-auto">
            <div class="flex">
                <!-- Days grid -->
                <div class="flex-1">
                    <div class="grid grid-cols-6 gap-3 min-w-full">
                        @foreach ($days as $day)

                            <div class="border-l border-gray-100 bg-white">
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
                                                <div class="font-semibold">{{ $ev->mataKuliah->nama_mk ?? '-' }}</div>
                                                <div class="text-[11px] text-gray-600">{{ $ev->ruangan ?? '' }}</div>
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