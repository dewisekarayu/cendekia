@extends('layouts.portal')

@section('title', $kelas->mataKuliah?->nama_mk ?? 'Detail Kelas')
@section('activeMenu', 'My Courses')

@section('content')

    @php
        $hariIni = now()->translatedFormat('l');
        $sekarang = now()->format('H:i:s');
        $status = 'Tidak Ada Jadwal Hari Ini';
        $statusColor = 'bg-gray-100 text-gray-500';

        if ($kelas->hari === $hariIni) {
            if ($sekarang < $kelas->jam_mulai) {
                $status = 'Belum Mulai';
                $statusColor = 'bg-amber-50 text-amber-600';
            } elseif ($sekarang >= $kelas->jam_mulai && $sekarang <= $kelas->jam_selesai) {
                $status = 'Sedang Berlangsung';
                $statusColor = 'bg-emerald-50 text-emerald-600';
            } else {
                $status = 'Selesai';
                $statusColor = 'bg-gray-100 text-gray-500';
            }
        }
    @endphp

    <!-- Info Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-900 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ $kelas->dosen?->name ?? '-' }}</p>
                <p class="text-[11px] text-gray-400">Dosen</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ $kelas->mataKuliah?->nama_mk ?? '-' }}</p>
                <p class="text-[11px] text-gray-400">Mata Kuliah</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ substr($kelas->jam_mulai, 0, 5) }} - {{ substr($kelas->jam_selesai, 0, 5) }}</p>
                <p class="text-[11px] text-gray-400">{{ $kelas->hari }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ $kelas->ruangan ?? '-' }}</p>
                <p class="text-[11px] text-gray-400">Ruang</p>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg font-bold text-gray-800">{{ $kelas->mataKuliah?->kode_mk ?? '-' }} - Kelas {{ $kelas->kode_kelas }}</h1>
        <span class="text-xs font-semibold px-3 py-1.5 rounded-full {{ $statusColor }}">{{ $status }}</span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" x-data="{ tab: 'semua' }">

        <!-- Main: Filter Tabs + List -->
        <div class="lg:col-span-2">

            <!-- Filter Tabs -->
            <div class="flex items-center gap-2 mb-4 overflow-x-auto">
                <button @click="tab = 'semua'" :class="tab === 'semua' ? 'bg-blue-900 text-white' : 'bg-white border border-gray-200 text-gray-600'" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition">
                    Semua
                </button>
                <button @click="tab = 'materi'" :class="tab === 'materi' ? 'bg-blue-900 text-white' : 'bg-white border border-gray-200 text-gray-600'" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition">
                    Materi
                </button>
                <button @click="tab = 'tugas'" :class="tab === 'tugas' ? 'bg-blue-900 text-white' : 'bg-white border border-gray-200 text-gray-600'" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition">
                    Tugas
                </button>
                <button @click="tab = 'absensi'" :class="tab === 'absensi' ? 'bg-blue-900 text-white' : 'bg-white border border-gray-200 text-gray-600'" class="px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition">
                    Absensi
                </button>
            </div>

            <!-- List Materi -->
            <div x-show="tab === 'semua' || tab === 'materi'" class="space-y-3 mb-4">
                @forelse ($materiList as $materi)
                    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-900 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="overflow-hidden flex-1">
                            <p class="text-sm font-semibold text-gray-800 truncate">Pertemuan {{ $materi->pertemuan_ke }}: {{ $materi->judul }}</p>
                            <p class="text-xs text-gray-400">{{ $materi->file_path ? strtoupper($materi->tipe_file) . ' tersedia' : 'Belum ada file' }}</p>
                        </div>
                    </div>
                @empty
                    @if (\Illuminate\Support\Facades\Request::segment(3) || true)
                    <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-6 text-center text-sm text-gray-400" x-show="tab === 'materi'">
                        Belum ada materi untuk kelas ini.
                    </div>
                    @endif
                @endforelse
            </div>

            <!-- List Tugas -->
            <div x-show="tab === 'semua' || tab === 'tugas'" class="space-y-3 mb-4">
                @forelse ($tugasList as $tugas)
                    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div class="overflow-hidden flex-1">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $tugas->judul }}</p>
                            <p class="text-xs text-gray-400">Deadline: {{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-6 text-center text-sm text-gray-400" x-show="tab === 'tugas'">
                        Belum ada tugas untuk kelas ini.
                    </div>
                @endforelse
            </div>

            <!-- List Absensi -->
            <div x-show="tab === 'semua' || tab === 'absensi'" class="space-y-3">
                @forelse ($rekapAbsen as $item)
                    @php
                        $statusMap = [
                            'hadir' => ['label' => 'Hadir', 'color' => 'bg-emerald-50 text-emerald-700', 'icon' => 'M5 13l4 4L19 7'],
                            'izin' => ['label' => 'Izin', 'color' => 'bg-blue-50 text-blue-700', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'sakit' => ['label' => 'Sakit', 'color' => 'bg-amber-50 text-amber-700', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'alpha' => ['label' => 'Tidak Hadir', 'color' => 'bg-red-50 text-red-700', 'icon' => 'M6 18L18 6M6 6l12 12'],
                        ];
                        $s = $statusMap[$item->status] ?? $statusMap['alpha'];
                    @endphp
                    <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg {{ $s['color'] }} flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $s['icon'] }}" />
                                </svg>
                            </div>
                            <div class="overflow-hidden flex-1">
                                <p class="text-sm font-semibold text-gray-800">Pertemuan {{ $item->absensi->pertemuan_ke }} — {{ $s['label'] }}</p>
                                <p class="text-xs text-gray-400">{{ $item->absensi->tanggal->format('d M Y') }}</p>
                            </div>
                        </div>
                        @if ($item->absensi->rangkuman)
                            <p class="text-xs text-gray-500 mt-2 pl-13">{{ Str::limit($item->absensi->rangkuman, 150) }}</p>
                        @endif
                    </div>
                @empty
                    <div class="bg-white rounded-xl border-2 border-dashed border-gray-200 p-6 text-center text-sm text-gray-400" x-show="tab === 'absensi'">
                        Belum ada data absensi untuk kelas ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Course Progress -->
        <div>
            <div class="bg-white rounded-xl border border-gray-100 p-5 shadow-sm sticky top-6">
                <h2 class="text-base font-bold text-gray-800 mb-4">Course Progress</h2>

                <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-gray-500">Total Completion</span>
                    <span class="font-bold text-blue-900">{{ $progress }}%</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden mb-5">
                    <div class="h-full bg-blue-900 rounded-full" style="width: {{ $progress }}%"></div>
                </div>

                <div class="flex items-center justify-between text-sm mb-1">
                    <span class="text-gray-500">Kehadiran</span>
                    <span class="font-bold text-emerald-600">{{ $totalHadir }}/{{ $totalPertemuan }}</span>
                </div>
                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden mb-5">
                    <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $totalPertemuan > 0 ? round(($totalHadir / $totalPertemuan) * 100) : 0 }}%"></div>
                </div>

                <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Tugas Selesai</span>
                        <span class="font-semibold text-gray-800">{{ $submitted }}/{{ $totalTugas }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Total Materi</span>
                        <span class="font-semibold text-gray-800">{{ $materiList->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection