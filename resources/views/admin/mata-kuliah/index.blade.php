@extends('layouts.portal')

@section('title', 'Mata Kuliah')
@section('activeMenu', 'Mata Kuliah')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-800">Mata Kuliah</h1>
        <a href="{{ route('admin.mata-kuliah.create') }}" class="bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-lg">
            + Tambah Mata Kuliah
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
                    <th class="px-5 py-3 font-medium">Kode</th>
                    <th class="px-5 py-3 font-medium">Nama Mata Kuliah</th>
                    <th class="px-5 py-3 font-medium">Program Studi</th>
                    <th class="px-5 py-3 font-medium">SKS</th>
                    <th class="px-5 py-3 font-medium">Semester</th>
                    <th class="px-5 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($mataKuliahList as $mk)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-5 py-3 text-gray-700">{{ $mk->kode_mk }}</td>
                        <td class="px-5 py-3 text-gray-700">{{ $mk->nama_mk }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $mk->programStudi?->nama_prodi ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $mk->sks }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $mk->semester_ke }}</td>
                        <td class="px-5 py-3 text-right space-x-2">
                            <a href="{{ route('admin.mata-kuliah.edit', $mk->id) }}" class="text-blue-900 text-xs font-medium hover:underline">Edit</a>
                            <form action="{{ route('admin.mata-kuliah.destroy', $mk->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus mata kuliah ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 text-xs font-medium hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-gray-400">Belum ada mata kuliah.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection

