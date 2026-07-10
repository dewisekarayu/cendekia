@extends('layouts.portal')

@section('title', 'Edit Kehadiran Manual - Pertemuan ' . $absensi->pertemuan_ke)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Kehadiran Manual
            </h1>
            <p class="mt-1 text-gray-500">
                {{ $kelas->mataKuliah->nama_mk }} <span class="font-semibold">({{ $kelas->kode_kelas }})</span> • Pertemuan {{ $absensi->pertemuan_ke }}
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('dosen.absensi.show', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Session Info -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-4">
        <div class="grid grid-cols-4 gap-4">
            <div class="text-center">
                <p class="text-gray-600 text-sm font-medium mb-2">Tanggal</p>
                <p class="text-lg font-semibold text-gray-900">{{ $absensi->tanggal->format('d M Y') }}</p>
            </div>
            <div class="text-center">
                <p class="text-gray-600 text-sm font-medium mb-2">Waktu</p>
                <p class="text-lg font-semibold text-gray-900">{{ $absensi->jam_mulai }} - {{ $absensi->jam_selesai }}</p>
            </div>
            <div class="text-center">
                <p class="text-gray-600 text-sm font-medium mb-2">Status</p>
                @php
                    $statusClass = match($absensi->session_status) {
                        'draft' => 'bg-yellow-100 text-yellow-800',
                        'buka' => 'bg-green-100 text-green-800',
                        'tutup' => 'bg-red-100 text-red-800',
                        default => 'bg-gray-100 text-gray-800',
                    };
                    $statusLabel = match($absensi->session_status) {
                        'draft' => 'Draft',
                        'buka' => 'Dibuka',
                        'tutup' => 'Ditutup',
                        default => 'Unknown',
                    };
                @endphp
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusClass }}">{{ $statusLabel }}</span>
            </div>
            <div class="text-center">
                <p class="text-gray-600 text-sm font-medium mb-2">Total Mahasiswa</p>
                <p class="text-lg font-semibold text-blue-600">{{ $kelas->mahasiswa->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    <div class="bg-gradient-to-br from-yellow-50 to-amber-50 rounded-xl border border-yellow-200 p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
            </svg>
            <div>
                <h3 class="font-semibold text-yellow-900 mb-1">Panduan Edit Kehadiran</h3>
                <ul class="text-sm text-yellow-800 space-y-1">
                    <li>• Pilih status kehadiran untuk setiap mahasiswa menggunakan dropdown</li>
                    <li>• Tambahkan keterangan jika diperlukan (opsional)</li>
                    <li>• Status default untuk mahasiswa yang belum presensi adalah <strong>Alpha</strong></li>
                    <li>• Perubahan akan langsung disimpan ke database</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Attendance Form -->
    <form action="{{ route('dosen.absensi.updateAttendance', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-blue-50 px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Mahasiswa</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Nama Mahasiswa</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">NIM</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status Kehadiran</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Keterangan (Opsional)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php
                            $statusColors = [
                                'hadir' => 'bg-green-50 text-green-700 border-green-200',
                                'izin' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'sakit' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                'alpha' => 'bg-red-50 text-red-700 border-red-200',
                            ];
                        @endphp

                        @foreach($kelas->mahasiswa as $index => $mahasiswa)
                            @php
                                $attendance = $absensi->absensiMahasiswa->where('mahasiswa_id', $mahasiswa->id)->first();
                                $currentStatus = $attendance->status ?? 'alpha';
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900 font-semibold">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900">{{ $mahasiswa->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $mahasiswa->nim ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="relative w-48">
                                        <select name="attendance[{{ $mahasiswa->id }}]" 
                                                class="w-full px-4 py-2.5 rounded-lg border {{ $statusColors[$currentStatus] }} appearance-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition cursor-pointer">
                                            <option value="hadir" class="bg-green-50 text-green-700" {{ $currentStatus === 'hadir' ? 'selected' : '' }}>Hadir</option>
                                            <option value="izin" class="bg-blue-50 text-blue-700" {{ $currentStatus === 'izin' ? 'selected' : '' }}>Izin</option>
                                            <option value="sakit" class="bg-yellow-50 text-yellow-700" {{ $currentStatus === 'sakit' ? 'selected' : '' }}>Sakit</option>
                                            <option value="alpha" class="bg-red-50 text-red-700" {{ $currentStatus === 'alpha' ? 'selected' : '' }}>Alpha</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <input type="text" 
                                           name="keterangan[{{ $mahasiswa->id }}]" 
                                           value="{{ $attendance->keterangan ?? '' }}"
                                           placeholder="Masukkan keterangan..."
                                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                           maxlength="255">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Legend -->
        <div class="mt-4 grid grid-cols-4 gap-4">
            <div class="flex items-center gap-2 p-3 rounded-lg bg-green-50 border border-green-200">
                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                <span class="text-sm font-medium text-green-700">Hadir</span>
            </div>
            <div class="flex items-center gap-2 p-3 rounded-lg bg-blue-50 border border-blue-200">
                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                <span class="text-sm font-medium text-blue-700">Izin</span>
            </div>
            <div class="flex items-center gap-2 p-3 rounded-lg bg-yellow-50 border border-yellow-200">
                <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                <span class="text-sm font-medium text-yellow-700">Sakit</span>
            </div>
            <div class="flex items-center gap-2 p-3 rounded-lg bg-red-50 border border-red-200">
                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                <span class="text-sm font-medium text-red-700">Alpha</span>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <button type="reset" class="flex-1 px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold transition">
                Reset Semua
            </button>
            <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg font-semibold transition shadow-lg hover:shadow-xl">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection