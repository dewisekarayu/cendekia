@extends('layouts.portal')

@section('title', 'Kelola Pengguna')
@section('activeMenu', 'Dosen & Mahasiswa')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-bold text-gray-800">Kelola Pengguna</h1>
        <a href="{{ route('admin.user.create', ['role' => $role]) }}" class="bg-blue-900 text-white text-sm font-medium px-4 py-2 rounded-lg">
            + Tambah {{ ucfirst($role) }}
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tab Role -->
    <div class="flex items-center gap-2 mb-4">
        @foreach (['admin', 'dosen', 'mahasiswa'] as $r)
            <a href="{{ route('admin.user.index', ['role' => $r]) }}"
                class="px-4 py-2 rounded-lg text-sm font-medium transition
                {{ $role === $r ? 'bg-blue-900 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                {{ ucfirst($r) }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-400 text-xs border-b border-gray-100">
                    <th class="px-5 py-3 font-medium">NIP/NIM</th>
                    <th class="px-5 py-3 font-medium">Nama</th>
                    <th class="px-5 py-3 font-medium">Email</th>
                    @if ($role === 'mahasiswa')
                        <th class="px-5 py-3 font-medium">Program Studi</th>
                    @endif
                    <th class="px-5 py-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($userList as $user)
                    <tr class="border-b border-gray-50 last:border-0">
                        <td class="px-5 py-3 text-gray-700">{{ $user->nip_nim }}</td>
                        <td class="px-5 py-3 text-gray-700">{{ $user->name }}</td>
                        <td class="px-5 py-3 text-gray-500">{{ $user->email }}</td>
                        @if ($role === 'mahasiswa')
                            <td class="px-5 py-3 text-gray-500">{{ $user->programStudi?->nama_prodi ?? '-' }}</td>
                        @endif
                        <td class="px-5 py-3 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.user.edit', $user->id) }}" class="text-blue-900 text-xs font-medium hover:underline">Edit</a>
                            <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus akun ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 text-xs font-medium hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-gray-400">Belum ada akun {{ $role }}.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $userList->links() }}
    </div>

@endsection