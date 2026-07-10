@extends('layouts.portal')

@section('title', 'Presensi - ' . $kelas->mataKuliah->nama_mk)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Presensi Kelas
            </h1>
            <p class="mt-1 text-gray-500">{{ $kelas->mataKuliah->nama_mk }} <span class="font-semibold">({{ $kelas->kode_kelas }})</span></p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('dosen.kelas-detail', $kelas->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <a href="{{ route('dosen.absensi.create', $kelas->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white rounded-lg font-semibold transition shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Sesi Baru
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
            <p class="font-semibold text-green-900">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('warning'))
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.487 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
            <p class="font-semibold text-yellow-900">{{ session('warning') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
            <p class="font-semibold text-red-900">{{ session('error') }}</p>
        </div>
    @endif

    <!-- Statistik -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $statistics['total_sesi'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Sesi</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-yellow-600">{{ $statistics['sesi_draft'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Draft</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $statistics['sesi_buka'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Dibuka</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-red-600">{{ $statistics['sesi_tutup'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Ditutup</p>
        </div>
    </div>

    <!-- Daftar Sesi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-semibold text-gray-900">Daftar Sesi Presensi</h2>
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
                            <th class="px-6 py-3 text-left">Hadir</th>
                            <th class="px-6 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($absensiList as $absensi)
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
                                    <span @class([
                                        'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold',
                                        'bg-yellow-100 text-yellow-800' => $absensi->isDraft(),
                                        'bg-green-100 text-green-800' => $absensi->isBuka(),
                                        'bg-red-100 text-red-800' => $absensi->isTutup(),
                                    ])>
                                        {{ $absensi->getStatusLabel() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $absensi->hadir_count }}/{{ $kelas->mahasiswa->count() }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('dosen.absensi.show', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}"
                                            class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">
                                            Detail
                                        </a>
                                        @if($absensi->isDraft())
                                            <form action="{{ route('dosen.absensi.buka', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg text-xs font-semibold transition">
                                                    Buka
                                                </button>
                                            </form>
                                        @elseif($absensi->isBuka())
                                            <form action="{{ route('dosen.absensi.tutup', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-3 py-1.5 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 rounded-lg text-xs font-semibold transition">
                                                    Tutup
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('dosen.absensi.destroy', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus sesi presensi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg text-xs font-semibold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
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
            <div class="text-center py-16">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m9-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-500 mb-4">Belum ada sesi presensi untuk kelas ini.</p>
                <a href="{{ route('dosen.absensi.create', $kelas->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold transition">
                    Buat Sesi Presensi Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
