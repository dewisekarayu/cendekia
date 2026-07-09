@extends('layouts.portal')
@section('title', 'Jadwal')
@section('activeMenu', 'Jadwal')
@section('content')

<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800">Jadwal Perkuliahan</h1>
            <p class="mt-1 text-sm text-gray-500">Lihat jadwal kelas dan acara akademik Anda</p>
        </div>
        <div class="flex gap-2">
            <button class="rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                Filter
            </button>
            <button class="rounded-lg bg-[#321270] px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#321270]/90 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Tambah Jadwal
            </button>
        </div>
    </div>

    {{-- CALENDAR VIEW --}}
    <div class="grid gap-6 lg:grid-cols-3">
        {{-- CALENDAR --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- WEEK VIEW --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800">Minggu Ini</h3>
                    <div class="flex gap-2">
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button class="p-2 hover:bg-gray-100 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <div class="p-5 min-w-[800px]">
                        <div class="grid grid-cols-7 gap-4">
                            @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day)
                                <div class="text-center">
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">{{ $day }}</p>
                                    <div class="text-lg font-bold text-slate-800 mb-2">{{ rand(1, 31) }}</div>
                                </div>
                            @endforeach
                        </div>

                        {{-- SCHEDULE ITEMS --}}
                        <div class="mt-8 space-y-3">
                            @php
                                $schedules = [
                                    ['time' => '08:00 - 09:30', 'class' => 'Pemrograman Web', 'room' => 'Lab 1', 'students' => 32, 'color' => 'bg-blue-50 border-l-4 border-blue-500'],
                                    ['time' => '10:00 - 11:30', 'class' => 'Basis Data', 'room' => 'Lab 2', 'students' => 28, 'color' => 'bg-emerald-50 border-l-4 border-emerald-500'],
                                    ['time' => '13:00 - 14:30', 'class' => 'Algoritma & Struktur Data', 'room' => 'Ruang 305', 'students' => 35, 'color' => 'bg-purple-50 border-l-4 border-purple-500'],
                                ];
                            @endphp
                            @foreach ($schedules as $item)
                                <div class="p-4 rounded-lg {{ $item['color'] }} flex items-start justify-between hover:shadow-md transition cursor-pointer">
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-gray-500 mb-1">{{ $item['time'] }}</p>
                                        <h4 class="text-sm font-bold text-slate-800">{{ $item['class'] }}</h4>
                                        <div class="mt-2 flex gap-3 text-xs text-gray-600">
                                            <div class="flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m5 0h5M9 7h.01M9 11h.01M9 15h.01M12 7h.01M12 11h.01M12 15h.01M15 7h.01M15 11h.01M15 15h.01"/></svg>
                                                {{ $item['room'] }}
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                                                {{ $item['students'] }} siswa
                                            </div>
                                        </div>
                                    </div>
                                    <button class="p-2 hover:bg-white/50 rounded-lg transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- MONTH VIEW --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h3 class="font-bold text-slate-800">Bulan Ini</h3>
                </div>

                <div class="p-5">
                    <div class="grid grid-cols-7 gap-2 text-center">
                        {{-- DAYS HEADER --}}
                        @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                            <div class="text-xs font-bold text-gray-500 uppercase py-2">{{ $day }}</div>
                        @endforeach

                        {{-- CALENDAR DAYS --}}
                        @for ($i = 1; $i <= 35; $i++)
                            <div class="aspect-square flex items-center justify-center rounded-lg {{ $i <= 7 ? 'bg-gray-50 text-gray-400 text-sm' : 'hover:bg-[#321270]/5 cursor-pointer text-sm font-medium text-gray-700' }} relative">
                                {{ $i <= 7 ? '' : $i - 7 }}
                                @if(rand(0, 1) && $i > 7)
                                    <div class="absolute bottom-1 left-1/2 transform -translate-x-1/2 h-1 w-1 bg-[#321270] rounded-full"></div>
                                @endif
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="space-y-6">
            {{-- UPCOMING EVENTS --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h3 class="font-bold text-slate-800">Acara Mendatang</h3>
                </div>

                <div class="space-y-3 p-5">
                    @php
                        $events = [
                            ['date' => 'Hari Ini', 'title' => 'Ujian Tengah Semester', 'type' => 'Ujian'],
                            ['date' => 'Besok', 'title' => 'Rapat Dosen', 'type' => 'Rapat'],
                            ['date' => '15 Jan', 'title' => 'Pengumpulan Nilai', 'type' => 'Deadline'],
                        ];
                    @endphp
                    @foreach ($events as $event)
                        <div class="flex gap-3 pb-3 border-b border-gray-100 last:border-0">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg {{ $event['type'] == 'Ujian' ? 'bg-red-50' : ($event['type'] == 'Rapat' ? 'bg-blue-50' : 'bg-amber-50') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 {{ $event['type'] == 'Ujian' ? 'text-red-500' : ($event['type'] == 'Rapat' ? 'text-blue-500' : 'text-amber-500') }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 font-semibold">{{ $event['date'] }}</p>
                                <p class="text-sm font-bold text-gray-800 mt-0.5">{{ $event['title'] }}</p>
                                <span class="inline-block mt-1 text-[10px] font-semibold {{ $event['type'] == 'Ujian' ? 'text-red-600 bg-red-50' : ($event['type'] == 'Rapat' ? 'text-blue-600 bg-blue-50' : 'text-amber-600 bg-amber-50') }} px-2 py-0.5 rounded">
                                    {{ $event['type'] }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- STATISTICS --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h3 class="font-bold text-slate-800">Statistik</h3>
                </div>

                <div class="space-y-4 p-5">
                    @php
                        $stats = [
                            ['label' => 'Total Jam', 'value' => '12.5', 'unit' => 'jam/minggu'],
                            ['label' => 'Kelas Aktif', 'value' => '3', 'unit' => 'kelas'],
                            ['label' => 'Total Mahasiswa', 'value' => '95', 'unit' => 'siswa'],
                        ];
                    @endphp
                    @foreach ($stats as $stat)
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 last:border-0">
                            <span class="text-sm text-gray-600">{{ $stat['label'] }}</span>
                            <div class="text-right">
                                <p class="text-lg font-bold text-slate-800">{{ $stat['value'] }}</p>
                                <p class="text-xs text-gray-500">{{ $stat['unit'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- QUICK ACTIONS --}}
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 bg-white shadow-sm">
                <div class="border-b border-gray-100 px-5 py-4">
                    <h3 class="font-bold text-slate-800">Aksi Cepat</h3>
                </div>

                <div class="space-y-2 p-5">
                    <button class="w-full rounded-lg bg-[#321270]/10 py-2.5 text-sm font-semibold text-[#321270] hover:bg-[#321270]/20 transition text-left px-4">
                        📝 Buat Pengumuman
                    </button>
                    <button class="w-full rounded-lg bg-[#321270]/10 py-2.5 text-sm font-semibold text-[#321270] hover:bg-[#321270]/20 transition text-left px-4">
                        📋 Buat Tugas
                    </button>
                    <button class="w-full rounded-lg bg-[#321270]/10 py-2.5 text-sm font-semibold text-[#321270] hover:bg-[#321270]/20 transition text-left px-4">
                        📊 Lihat Nilai
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
