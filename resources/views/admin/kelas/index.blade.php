@extends('layouts.portal')

@section('title', 'Kelas Perkuliahan')
@section('activeMenu', 'Kelas Perkuliahan')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-800">Kelas Perkuliahan</h1>
        <a href="{{ route('admin.kelas.create') }}" class="bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-lg">
            + Buat Kelas Baru
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-400 text-xs border-b border-gray-100">
                    <th class="px-5 py-3 font-medium">Mata Kuliah</th>
                    <th class="px-5 py-3 font-medium">Dosen</th>
                    <th class="px-5 py-3 font-medium">Jadwal</th>
                    <th class="px-5 py-3 font-medium">Mahasiswa</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kelasList as $kelas)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-5 py-3">
                            <p class="font-medium text-gray-800">{{ $kelas->mataKuliah?->nama_mk ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $kelas->mataKuliah?->kode_mk ?? '-' }} • Kelas {{ $kelas->kode_kelas }}</p>
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $kelas->dosen?->name ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-500">
                            {{ $kelas->hari }}, {{ substr($kelas->jam_mulai, 0, 5) }} - {{ substr($kelas->jam_selesai, 0, 5) }}
                            <br><span class="text-xs text-gray-400">{{ $kelas->ruangan }}</span>
                        </td>
                        <td class="px-5 py-3 text-gray-500">{{ $kelas->mahasiswa->count() }}</td>
                        <td class="px-5 py-3">
                            @if ($kelas->is_active)
                                <span class="inline-block text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-1 rounded">Aktif</span>
                            @else
                                <span class="inline-block text-[10px] font-semibold text-gray-600 bg-gray-100 px-2 py-1 rounded">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.kelas.edit', $kelas->id) }}" class="text-blue-900 text-xs font-medium hover:underline">Edit</a>
                            <form action="{{ route('admin.kelas.destroy', $kelas->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus kelas ini? Semua data mahasiswa yang terdaftar akan ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 text-xs font-medium hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada kelas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection