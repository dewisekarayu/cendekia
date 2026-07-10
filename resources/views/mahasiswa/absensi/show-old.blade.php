@extends('layouts.portal')

@section('title', 'Riwayat Presensi - ' . $kelas->mataKuliah->nama_mk)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Riwayat Presensi
            </h1>
            <p class="mt-1 text-gray-500">{{ $kelas->mataKuliah->nama_mk }} <span class="font-semibold">({{ $kelas->kode_kelas }})</span></p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('mahasiswa.absensi.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <a href="{{ route('mahasiswa.absensi.kelas', $kelas->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-lg font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Presensi
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Main -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Statistik -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['totalPertemuan'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total Pertemuan</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $stats['hadir'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Hadir</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['izin'] + $stats['sakit'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Izin/Sakit</p>
                </div>
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
                    <p class="text-2xl font-bold text-red-600">{{ $stats['alpha'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Alpha</p>
                </div>
            </div>

            <!-- Ringkasan Persentase -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-semibold text-gray-900 mb-4">Ringkasan Kehadiran</h3>
                @if($stats['totalPertemuan'] > 0)
                    @php
                        $bars = [
                            ['label' => 'Hadir', 'value' => $stats['hadir'], 'color' => 'bg-green-500'],
                            ['label' => 'Izin', 'value' => $stats['izin'], 'color' => 'bg-blue-500'],
                            ['label' => 'Sakit', 'value' => $stats['sakit'], 'color' => 'bg-yellow-500'],
                            ['label' => 'Alpha', 'value' => $stats['alpha'], 'color' => 'bg-red-500'],
                        ];
                    @endphp
                    <div class="space-y-4">
                        @foreach($bars as $bar)
                            @php $percent = round($bar['value'] / $stats['totalPertemuan'] * 100); @endphp
                            <div>
                                <div class="flex justify-between mb-1.5 text-sm">
                                    <span class="font-semibold text-gray-700">{{ $bar['label'] }}</span>
                                    <span class="text-gray-500">{{ $bar['value'] }} dari {{ $stats['totalPertemuan'] }} ({{ $percent }}%)</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                                    <div class="{{ $bar['color'] }} h-full rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Belum ada data presensi.</p>
                @endif
            </div>

            <!-- Detail Presensi -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h2 class="font-semibold text-gray-900">Detail Presensi</h2>
                </div>

                @if($absensiList->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-3 text-left">Pertemuan</th>
                                    <th class="px-6 py-3 text-left">Tanggal</th>
                                    <th class="px-6 py-3 text-left">Waktu</th>
                                    <th class="px-6 py-3 text-left">Status</th>
                                    <th class="px-6 py-3 text-left">Waktu Presensi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($absensiList as $absensi)
                                    @php $attendance = $absensi->absensiMahasiswa->first(); @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-semibold text-gray-900">Pertemuan {{ $absensi->pertemuan_ke }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ $absensi->tanggal->format('d M Y') }}</td>
                                        <td class="px-6 py-4 text-gray-500">
                                            @if($absensi->jam_mulai && $absensi->jam_selesai)
                                                {{ $absensi->jam_mulai }} - {{ $absensi->jam_selesai }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($attendance)
                                                <span @class([
                                                    'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold',
                                                    'bg-green-100 text-green-800' => $attendance->status === 'hadir',
                                                    'bg-blue-100 text-blue-800' => $attendance->status === 'izin',
                                                    'bg-yellow-100 text-yellow-800' => $attendance->status === 'sakit',
                                                    'bg-red-100 text-red-800' => $attendance->status === 'alpha',
                                                ])>
                                                    {{ $attendance->getStatusLabel() }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Alpha</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">
                                            {{ $attendance?->waktu_absensi?->format('d M Y H:i') ?? '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-center py-4 border-t border-gray-100">
                        {{ $absensiList->links() }}
                    </div>
                @else
                    <div class="text-center py-16 text-gray-500">
                        Belum ada data presensi untuk kelas ini.
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-900 mb-4">Informasi Kelas</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Mata Kuliah</p>
                        <p class="font-semibold text-gray-900">{{ $kelas->mataKuliah->nama_mk }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Kode Kelas</p>
                        <p class="font-semibold text-gray-900">{{ $kelas->kode_kelas }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Pengajar</p>
                        <p class="font-semibold text-gray-900">{{ $kelas->dosen->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Ruangan</p>
                        <p class="font-semibold text-gray-900">{{ $kelas->ruangan ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                <h3 class="font-semibold text-gray-900 mb-4">Keterangan Status</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                        <span class="text-gray-700">Hadir — Anda hadir di kelas</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                        <span class="text-gray-700">Izin — dengan alasan tertentu</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                        <span class="text-gray-700">Sakit</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                        <span class="text-gray-700">Alpha — tanpa keterangan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
