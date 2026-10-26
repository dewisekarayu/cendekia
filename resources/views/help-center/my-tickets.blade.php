@extends('layouts.portal')

@section('title', $isEn ? 'My Support Tickets' : 'Tiket Bantuan Saya')

@section('content')
@php
    $categoryLabels = $isEn
        ? ['akun' => 'Account', 'kelas' => 'Classes', 'tugas' => 'Assignments', 'absensi' => 'Attendance', 'nilai' => 'Grades', 'teknis' => 'Technical', 'lainnya' => 'Other']
        : ['akun' => 'Akun', 'kelas' => 'Kelas', 'tugas' => 'Tugas', 'absensi' => 'Absensi', 'nilai' => 'Nilai', 'teknis' => 'Teknis', 'lainnya' => 'Lainnya'];
@endphp
<div class="help-center-page max-w-5xl mx-auto px-4 sm:px-0 my-4 sm:my-6 space-y-6 animate-fade-in">

    {{-- Breadcrumb & Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs font-medium text-slate-400 mb-1.5" aria-label="Breadcrumb">
                <a href="{{ route('help-center.index') }}" class="hover:text-slate-800 dark:hover:text-white transition">
                    {{ $isEn ? 'Help Center' : 'Pusat Bantuan' }}
                </a>
                <span>&bull;</span>
                <span class="text-slate-700 dark:text-slate-200 font-semibold">{{ $isEn ? 'My Tickets' : 'Tiket Saya' }}</span>
            </nav>
            <h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white tracking-tight flex items-center gap-2.5 m-0">
                <span>{{ $isEn ? 'My Support Tickets' : 'Tiket Bantuan Saya' }}</span>
                @if(($counts['responded'] ?? 0) > 0)
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-500 text-white text-xs font-bold shadow-xs">
                        {{ $counts['responded'] }} {{ $isEn ? 'Answered' : 'Dibalas' }}
                    </span>
                @endif
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1 m-0">
                {{ $isEn ? 'Track and view replies from administrators for your help tickets.' : 'Pantau status kendala dan lihat jawaban balasan dari tim administrator Cendekia.' }}
            </p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <a href="{{ route('help-center.index') }}" 
               class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/60 transition shadow-2xs text-decoration-none">
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>{{ $isEn ? 'Back to FAQ' : 'Kembali ke FAQ' }}</span>
            </a>
            <a href="{{ route('help-center.index') }}#form-tiket" 
               class="help-center-primary inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-white transition shadow-sm text-decoration-none">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                <span>{{ $isEn ? 'New Ticket' : 'Buat Tiket Baru' }}</span>
            </a>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
        @php
            $currentStatus = request('status', '');
            $tabs = [
                '' => ['label' => $isEn ? 'All Tickets' : 'Semua Tiket', 'count' => $counts['all']],
                'responded' => ['label' => $isEn ? 'Answered' : 'Dibalas Admin', 'count' => $counts['responded']],
                'open' => ['label' => $isEn ? 'Awaiting Response' : 'Menunggu Respon', 'count' => $counts['open']],
                'closed' => ['label' => $isEn ? 'Closed' : 'Ditutup', 'count' => $counts['closed']],
            ];
        @endphp

        @foreach($tabs as $sKey => $tab)
            @php $isActive = $currentStatus === $sKey; @endphp
            <a href="{{ route('help-center.my-tickets', array_filter(['status' => $sKey])) }}"
               class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 shrink-0 text-decoration-none {{ $isActive ? 'help-center-primary text-white shadow-xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200/80 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700/60' }}">
                <span>{{ $tab['label'] }}</span>
                @if($tab['count'] > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] font-black {{ $isActive ? 'bg-white text-slate-700' : ($sKey === 'responded' ? 'bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300') }}">
                        {{ $tab['count'] }}
                    </span>
                @endif
            </a>
        @endforeach
    </div>

    {{-- Daftar Tiket --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-sm overflow-hidden divide-y divide-gray-100 dark:divide-slate-700/60">
        @forelse($tickets as $ticket)
            @php
                $repliesCount = $ticket->replies->count();
                $hasAdminReply = $ticket->replies->where('user_id', '!=', auth()->id())->isNotEmpty();
                $lastReply = $ticket->replies->last();
            @endphp
            <div class="p-4 sm:p-5 hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-3.5 min-w-0">
                    {{-- Status Icon --}}
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-xs 
                        {{ $ticket->status === 'responded' ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400' : ($ticket->status === 'closed' ? 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400' : 'bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400') }}">
                        @if($ticket->status === 'responded')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                            </svg>
                        @elseif($ticket->status === 'closed')
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        @else
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="text-xs font-mono font-bold text-slate-500 dark:text-slate-400">
                                #{{ $ticket->id }}
                            </span>
                            <span class="text-slate-300 dark:text-slate-600">&bull;</span>
                            <span class="text-[11px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                                {{ $categoryLabels[strtolower($ticket->category)] ?? $ticket->category }}
                            </span>

                            {{-- Status Badge --}}
                            @if($ticket->status === 'responded')
                                <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>{{ $isEn ? 'Admin Replied' : 'Dibalas Admin' }}</span>
                                </span>
                            @elseif($ticket->status === 'closed')
                                <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700/60 dark:text-slate-300">
                                    {{ $isEn ? 'Closed' : 'Ditutup' }}
                                </span>
                            @else
                                <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    <span>{{ $isEn ? 'Awaiting Response' : 'Menunggu Respon' }}</span>
                                </span>
                            @endif

                            <span class="text-xs text-slate-400 dark:text-slate-500 ml-auto sm:ml-0 font-medium">
                                {{ $ticket->updated_at->diffForHumans() }}
                            </span>
                        </div>

                        <h3 class="text-sm font-bold text-slate-900 dark:text-white leading-snug truncate m-0">
                            {{ $ticket->subject }}
                        </h3>

                        {{-- Snippet Pesan Terakhir --}}
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-1 m-0">
                            @if($lastReply)
                                <span class="font-semibold {{ $lastReply->user_id == auth()->id() ? 'text-slate-700 dark:text-slate-300' : 'text-blue-600 dark:text-blue-400' }}">
                                    {{ $lastReply->user_id == auth()->id() ? ($isEn ? 'You: ' : 'Anda: ') : 'Admin: ' }}
                                </span>
                                <span>{{ $lastReply->message }}</span>
                            @else
                                <span>{{ $ticket->message }}</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Action Button --}}
                <div class="flex items-center gap-3 shrink-0 self-end sm:self-center">
                    @if($repliesCount > 0)
                        <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>
                            <span>{{ $repliesCount }} {{ $isEn ? ($repliesCount === 1 ? 'reply' : 'replies') : 'balasan' }}</span>
                        </span>
                    @endif

                    <a href="{{ route('help-center.my-tickets.show', $ticket->id) }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-700 hover:text-white dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs font-bold transition text-decoration-none">
                        <span>{{ $isEn ? 'View Replies' : 'Lihat Jawaban' }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="w-14 h-14 mx-auto rounded-2xl bg-slate-50 dark:bg-slate-700/60 flex items-center justify-center text-slate-400 mb-3">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
                <h3 class="text-base font-bold text-slate-800 dark:text-white m-0">
                    {{ $isEn ? 'No tickets found' : 'Belum Ada Tiket Bantuan' }}
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 max-w-sm mx-auto m-0">
                    {{ $isEn ? 'If you encounter any issues or have questions, feel free to submit a support ticket.' : 'Jika Anda mengalami kendala atau membutuhkan bantuan, silakan kirimkan tiket bantuan.' }}
                </p>
                <div class="mt-4">
                    <a href="{{ route('help-center.index') }}#form-tiket" 
                       class="help-center-primary inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-white transition text-decoration-none">
                        <span>{{ $isEn ? 'Create Ticket Now' : 'Buat Tiket Sekarang' }}</span>
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($tickets->hasPages())
        <div class="mt-4">
            {{ $tickets->links() }}
        </div>
    @endif

</div>
@endsection
