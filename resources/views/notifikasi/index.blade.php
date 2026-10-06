@extends('layouts.portal')

@section('title', 'Semua Notifikasi')
@section('activeMenu', 'Notifikasi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Semua Notifikasi</h1>
            <p class="text-sm font-medium text-slate-400 dark:text-slate-500 mt-0.5">Pantau semua pembaruan dan informasi penting.</p>
        </div>
        <form action="{{ route('notifikasi.baca-semua') }}" method="POST">
            @csrf
            <button type="submit" class="px-4 py-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700/80 transition shadow-sm">
                Tandai Semua Dibaca
            </button>
        </form>
    </div>

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/60 dark:border-slate-700 shadow-sm overflow-hidden">
        @forelse($notifikasis as $notif)
            @php $isUnread = is_null($notif->dibaca_pada); @endphp
            <a href="{{ route('notifikasi.baca', $notif->id) }}" class="flex items-start gap-4 p-5 border-b border-gray-100 dark:border-slate-700/50 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition {{ $isUnread ? 'bg-blue-50/30 dark:bg-slate-700/20' : '' }}">
                <div class="flex-shrink-0 mt-1">
                    @if($isUnread)
                        <div class="w-3 h-3 bg-blue-500 rounded-full shadow-sm ring-4 ring-blue-100 dark:ring-slate-700"></div>
                    @else
                        <div class="w-3 h-3 bg-gray-300 dark:bg-slate-600 rounded-full"></div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold {{ $isUnread ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-300' }} mb-1">
                        {{ $notif->judul }}
                    </p>
                    <p class="text-sm {{ $isUnread ? 'text-gray-600 dark:text-gray-400' : 'text-gray-500 dark:text-gray-500/80' }} leading-relaxed">
                        {{ $notif->pesan }}
                    </p>
                    <div class="mt-3 flex items-center gap-3 text-xs font-medium {{ $isUnread ? 'text-gray-500 dark:text-gray-400' : 'text-gray-400 dark:text-gray-500' }}">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                        <span>&bull;</span>
                        <span>{{ $notif->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-slate-700 flex items-center justify-center text-gray-500 dark:text-gray-400 group-hover:bg-blue-100 group-hover:text-blue-600 transition">
                        <svg class="w-4 h-4 transform -rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </div>
                </div>
            </a>
        @empty
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 dark:bg-slate-800 border-2 border-dashed border-gray-200 dark:border-slate-700 text-gray-400 mb-4">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                </div>
                <h3 class="text-base font-bold text-gray-800 dark:text-white mb-1">Belum Ada Notifikasi</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Anda akan melihat notifikasi di sini ketika ada aktivitas baru.</p>
            </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $notifikasis->links() }}
    </div>
</div>
@endsection
