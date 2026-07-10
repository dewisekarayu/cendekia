@extends('layouts.portal')

@section('title', 'Edit Kehadiran Manual')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Kehadiran Manual
            </h1>
            <p class="mt-1 text-gray-500">Pertemuan {{ $absensi->pertemuan_ke }} &middot; {{ $absensi->tanggal->format('d M Y') }}</p>
        </div>
        <a href="{{ route('dosen.absensi.show', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <p class="font-semibold text-red-900 mb-2">Terjadi kesalahan:</p>
            <ul class="list-disc list-inside text-sm text-red-800 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('dosen.absensi.updateAttendance', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}"
        method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-900">Daftar Mahasiswa</h2>
            </div>

            @if($kelas->mahasiswa->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3 text-left w-10">No</th>
                                <th class="px-6 py-3 text-left">Nama Mahasiswa</th>
                                <th class="px-6 py-3 text-left">NIM</th>
                                <th class="px-6 py-3 text-left w-48">Status Kehadiran</th>
                                <th class="px-6 py-3 text-left">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($kelas->mahasiswa as $i => $mahasiswa)
                                @php
                                    $attendance = $absensi->absensiMahasiswa->firstWhere('mahasiswa_id', $mahasiswa->id);
                                    $currentStatus = $attendance->status ?? 'alpha';
                                    $currentKeterangan = $attendance->keterangan ?? '';
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-gray-500">{{ $i + 1 }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $mahasiswa->name }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $mahasiswa->nip_nim }}</td>
                                    <td class="px-6 py-4">
                                        <select name="attendance[{{ $mahasiswa->id }}]"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
                                            <option value="hadir" @selected($currentStatus === 'hadir')>Hadir</option>
                                            <option value="izin" @selected($currentStatus === 'izin')>Izin</option>
                                            <option value="sakit" @selected($currentStatus === 'sakit')>Sakit</option>
                                            <option value="alpha" @selected($currentStatus === 'alpha')>Alpha</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="text"
                                            name="keterangan[{{ $mahasiswa->id }}]"
                                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                            placeholder="Opsional..."
                                            value="{{ old('keterangan.' . $mahasiswa->id, $currentKeterangan) }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-16 text-gray-500">
                    Tidak ada mahasiswa terdaftar di kelas ini.
                </div>
            @endif
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('dosen.absensi.show', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}"
                class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold transition">
                Batal
            </a>
            <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-lg font-semibold transition shadow-sm">
                Simpan Perubahan
            </button>
        </div>
    </form>

    <!-- Legend -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-900 mb-4">Keterangan Status</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-green-500"></span>
                <div><strong class="text-gray-900">Hadir</strong><p class="text-gray-500 text-xs">Mahasiswa hadir di kelas</p></div>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                <div><strong class="text-gray-900">Izin</strong><p class="text-gray-500 text-xs">Izin dengan alasan</p></div>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                <div><strong class="text-gray-900">Sakit</strong><p class="text-gray-500 text-xs">Tidak hadir karena sakit</p></div>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-red-500"></span>
                <div><strong class="text-gray-900">Alpha</strong><p class="text-gray-500 text-xs">Tanpa keterangan</p></div>
            </div>
        </div>
    </div>
</div>
@endsection
