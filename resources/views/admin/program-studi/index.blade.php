@extends('layouts.portal')

@section('title', 'Program Studi')
@section('activeMenu', 'Program Studi')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-800">Program Studi</h1>
        <a href="{{ route('admin.program-studi.create') }}" class="bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-lg">
            + Tambah Program Studi
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
                    <th class="px-5 py-3 font-medium">Nama Program Studi</th>
                    <th class="px-5 py-3 font-medium">Jenjang</th>
                    <th class="px-5 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($prodiList as $prodi)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-5 py-3 text-gray-700">{{ $prodi->kode_prodi }}</td>
                        <td class="px-5 py-3 text-gray-700">{{ $prodi->nama_prodi }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $prodi->jenjang }}</td>
                        <td class="px-5 py-3 text-right space-x-2">
                            <a href="{{ route('admin.program-studi.edit', $prodi->id) }}" class="text-blue-900 text-xs font-medium hover:underline">Edit</a>
                            <form action="{{ route('admin.program-studi.destroy', $prodi->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus program studi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 text-xs font-medium hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-gray-400">Belum ada program studi.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection