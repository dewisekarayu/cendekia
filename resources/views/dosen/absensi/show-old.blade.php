@extends('layouts.portal')

@section('title', 'Detail Presensi - Pertemuan ' . $absensi->pertemuan_ke)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-2">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Pertemuan {{ $absensi->pertemuan_ke }}
            </h1>
            <p class="mt-1 text-gray-500">{{ $kelas->mataKuliah->nama_mk }} <span class="font-semibold">({{ $kelas->kode_kelas }})</span> &middot; {{ $absensi->tanggal->format('d M Y') }}</p>
        </div>
        <a href="{{ route('dosen.absensi.index', $kelas->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-green-900 font-semibold">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-yellow-900 font-semibold">{{ session('warning') }}</div>
    @endif
    @if(session('info'))
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-blue-900 font-semibold">{{ session('info') }}</div>
    @endif

    <!-- Kartu Kontrol Sesi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <span @class([
                    'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold',
                    'bg-yellow-100 text-yellow-800' => $absensi->isDraft(),
                    'bg-green-100 text-green-800' => $absensi->isBuka(),
                    'bg-red-100 text-red-800' => $absensi->isTutup(),
                ])>
                    {{ $absensi->getStatusLabel() }}
                </span>
                <div class="text-sm text-gray-500">
                    @if($absensi->jam_mulai && $absensi->jam_selesai)
                        {{ $absensi->jam_mulai }} - {{ $absensi->jam_selesai }}
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if($absensi->isDraft())
                    <form action="{{ route('dosen.absensi.buka', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold text-sm transition">
                            Buka Sesi Presensi
                        </button>
                    </form>
                @elseif($absensi->isBuka())
                    <form action="{{ route('dosen.absensi.tutup', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-semibold text-sm transition">
                            Tutup Sesi Presensi
                        </button>
                    </form>
                @endif
                <a href="{{ route('dosen.absensi.attendance', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-semibold text-sm transition">
                    Edit Kehadiran Manual
                </a>
                <form action="{{ route('dosen.absensi.destroy', ['kelasId' => $kelas->id, 'absensiId' => $absensi->id]) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus sesi presensi ini beserta seluruh datanya?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg font-semibold text-sm transition">
                        Hapus Sesi
                    </button>
                </form>
            </div>
        </div>

        @if($absensi->rangkuman || $absensi->berita_acara || $absensi->catatan)
            <div class="mt-6 pt-6 border-t border-gray-100 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                @if($absensi->rangkuman)
                    <div>
                        <p class="text-gray-500 font-semibold mb-1">Ringkasan Materi</p>
                        <p class="text-gray-700">{{ $absensi->rangkuman }}</p>
                    </div>
                @endif
                @if($absensi->berita_acara)
                    <div>
                        <p class="text-gray-500 font-semibold mb-1">Berita Acara</p>
                        <p class="text-gray-700">{{ $absensi->berita_acara }}</p>
                    </div>
                @endif
                @if($absensi->catatan)
                    <div>
                        <p class="text-gray-500 font-semibold mb-1">Catatan</p>
                        <p class="text-gray-700">{{ $absensi->catatan }}</p>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Mahasiswa</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-green-600">{{ $stats['hadir'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Hadir</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-blue-600">{{ $stats['izin'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Izin</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-yellow-600">{{ $stats['sakit'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Sakit</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 text-center">
            <p class="text-2xl font-bold text-red-600">{{ $stats['alpha'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Alpha / Belum Absen</p>
        </div>
    </div>

    <!-- Daftar Mahasiswa -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h2 class="font-semibold text-gray-900">Daftar Kehadiran Mahasiswa</h2>
        </div>

        @if($kelas->mahasiswa->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3 text-left w-10">No</th>
                            <th class="px-6 py-3 text-left">Nama Mahasiswa</th>
                            <th class="px-6 py-3 text-left">NIM</th>
                            <th class="px-6 py-3 text-left">Status</th>
                            <th class="px-6 py-3 text-left">Waktu Presensi</th>
                            <th class="px-6 py-3 text-left">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($kelas->mahasiswa as $i => $mahasiswa)
                            @php $kehadiran = $hadirMap->get($mahasiswa->id); @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-500">{{ $i + 1 }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $mahasiswa->name }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $mahasiswa->nip_nim }}</td>
                                <td class="px-6 py-4">
                                    @if($kehadiran)
                                        <span @class([
                                            'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold',
                                            'bg-green-100 text-green-800' => $kehadiran->status === 'hadir',
                                            'bg-blue-100 text-blue-800' => $kehadiran->status === 'izin',
                                            'bg-yellow-100 text-yellow-800' => $kehadiran->status === 'sakit',
                                            'bg-red-100 text-red-800' => $kehadiran->status === 'alpha',
                                        ])>
                                            {{ $kehadiran->getStatusLabel() }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                                            Belum Presensi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $kehadiran?->waktu_absensi?->format('d M Y H:i') ?? '—' }}
                                </td>
                                <td class="px-6 py-4 text-gray-500">
                                    {{ $kehadiran?->keterangan ?? '—' }}
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
</div>
@endsection
