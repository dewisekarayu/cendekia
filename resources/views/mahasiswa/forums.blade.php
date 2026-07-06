@extends('layouts.portal')

@section('title', 'Forums')
@section('activeMenu', 'Forums')

@section('content')

    <div class="bg-blue-900 rounded-xl px-8 py-6 text-white mb-8">
        <h1 class="text-2xl font-bold">Forum Diskusi</h1>
        <p class="text-blue-200 text-sm mt-1">Diskusi dari semua kelas yang kamu ikuti.</p>
    </div>

    @if ($forumList->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 p-10 text-center shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <p class="text-gray-500 text-sm">Belum ada diskusi. Gabung ke kelas dulu untuk melihat forum diskusinya.</p>
        </div>
    @else
        <div class="space-y-3">
            @foreach ($forumList as $forum)
                <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
                    <span class="text-xs text-blue-900 font-medium">{{ $forum->kelasPerkuliahan->mataKuliah->nama_mk ?? '-' }}</span>
                    <h3 class="font-semibold text-gray-800 mt-1">{{ $forum->judul }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($forum->isi, 100) }}</p>
                </div>
            @endforeach
        </div>
    @endif

@endsection