@extends('layouts.admin')

@section('title', 'Ticket #' . $ticket->id . ' - Admin')

@push('styles')
<style>
    /* Styling khusus detail tiket */
    .ticket-container {
        font-family: inherit;
    }

    /* Light mode styling */
    .ticket-card-surface {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }
    .ticket-header-surface {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .ticket-body-bubble {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #1e293b;
    }
    .ticket-reply-admin-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #1e293b;
    }
    .ticket-reply-user-box {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        color: #1e293b;
    }

    /* Uniform Dark mode styling - Seragam Senada dengan Tema Admin */
    html.dark .ticket-card-surface {
        background-color: #102820 !important;
        border: 1px solid #25463a !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25) !important;
    }
    html.dark .ticket-header-surface {
        background-color: #0c201a !important;
        border-bottom: 1px solid #25463a !important;
    }
    html.dark .ticket-body-bubble {
        background-color: #0c201a !important;
        border: 1px solid #1c382e !important;
        color: #e5f5ed !important;
    }
    /* Kotak Balasan Admin dan User dibuat seragam 1 tema warna */
    html.dark .ticket-reply-admin-box {
        background-color: #14352b !important;
        border: 1px solid #2a5545 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;
        color: #e5f5ed !important;
    }
    html.dark .ticket-reply-user-box {
        background-color: #102820 !important;
        border: 1px solid #25463a !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2) !important;
        color: #e5f5ed !important;
    }
    html.dark .ticket-input-surface {
        background-color: #0c201a !important;
        border: 1px solid #25463a !important;
        color: #e5f5ed !important;
    }
    html.dark .ticket-input-surface:focus {
        border-color: #34d399 !important;
        background-color: #0c201a !important;
        box-shadow: 0 0 0 2px rgba(52, 211, 153, 0.2) !important;
    }
    html.dark .ticket-label-muted {
        color: #8da49a !important;
    }
    html.dark .ticket-meta-divider {
        border-color: #25463a !important;
    }
    html.dark .ticket-badge-category {
        background-color: #0c201a !important;
        border: 1px solid #25463a !important;
        color: #6ee7b7 !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-6 max-w-6xl mx-auto px-4 sm:px-0">
    
    {{-- ===== HEADER ===== --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.help-center.tickets') }}" class="w-8 h-8 rounded-lg hover:bg-gray-50 dark:hover:bg-teal-900/40 flex items-center justify-center text-gray-500 dark:text-teal-200 border border-gray-200 dark:border-teal-500/30 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                    Tiket #{{ $ticket->id }}
                </h1>
                <p class="text-xs text-gray-500 dark:text-slate-300">
                    {{ $ticket->subject }}
                </p>
            </div>
        </div>
    </div>

    {{-- ===== FEEDBACK MESSAGES ===== --}}
    @if(session('success'))
        <div class="rounded-lg border border-emerald-200 dark:border-emerald-800/50 bg-emerald-50 dark:bg-emerald-950/60 p-4 text-xs font-semibold text-emerald-800 dark:text-emerald-300">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-lg border border-red-200 dark:border-red-800/50 bg-red-50 dark:bg-red-950/60 p-4 text-xs font-semibold text-red-800 dark:text-red-300">
            {{ session('error') }}
        </div>
    @endif

    {{-- ===== CONTENT GRID ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- ===== CHAT / REPLY AREA ===== --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- ORIGINAL MESSAGE --}}
            <div class="ticket-card-surface rounded-xl overflow-hidden">
                <div class="ticket-header-surface p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 dark:bg-emerald-500/20 flex items-center justify-center shrink-0">
                            <span class="font-bold text-xs text-emerald-600 dark:text-emerald-400">
                                {{ substr($ticket->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 dark:text-white text-sm leading-tight">{{ $ticket->name }}</p>
                            <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-0.5">{{ $ticket->email }}</p>
                        </div>
                    </div>
                    <span class="text-[10px] text-gray-400 dark:text-slate-400 font-medium">{{ $ticket->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="p-6 space-y-4">
                    <div class="ticket-body-bubble p-4 rounded-xl text-sm leading-relaxed whitespace-pre-wrap font-normal">
                        {{ $ticket->message }}
                    </div>

                    @if($ticket->attachment_path)
                        <div class="pt-4 border-t border-gray-100 dark:ticket-meta-divider flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <span class="text-xs text-gray-500 dark:text-slate-400 font-medium">Lampiran File</span>
                            </div>
                            <a href="{{ asset('storage/' . $ticket->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 hover:underline">
                                Unduh Lampiran
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- CHAT HISTORY / REPLIES --}}
            @if($ticket->replies->count() > 0)
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-emerald-300/70">Riwayat Tanggapan ({{ $ticket->replies->count() }})</h3>
                    
                    @foreach($ticket->replies as $reply)
                        @php
                            $isAdminReply = $reply->user && ($reply->user->hasRole('admin') || $reply->user->hasRole('super-admin') || $reply->user->hasRole('dosen'));
                        @endphp
                        <div class="flex flex-col {{ $isAdminReply ? 'items-end' : 'items-start' }}">
                            <div class="max-w-[85%] rounded-xl p-4 shadow-sm {{ $isAdminReply ? 'ticket-reply-admin-box' : 'ticket-reply-user-box' }}">
                                <div class="ticket-meta-divider flex items-center justify-between gap-4 mb-2 pb-2 border-b border-gray-100">
                                    <span class="text-xs font-semibold flex items-center gap-1.5 {{ $isAdminReply ? 'text-emerald-700 dark:text-emerald-300' : 'text-gray-700 dark:text-emerald-200' }}">
                                        @if($isAdminReply)
                                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                            {{ $reply->user->name }} (Support)
                                        @else
                                            <span class="w-2 h-2 rounded-full bg-emerald-600/60 dark:bg-emerald-400/60"></span>
                                            {{ $reply->user->name ?? $ticket->name }}
                                        @endif
                                    </span>
                                    <span class="text-[10px] text-gray-400 dark:text-emerald-300/70 font-medium">{{ $reply->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <div class="text-sm leading-relaxed whitespace-pre-wrap font-normal">
                                    {{ $reply->message }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- RESPONSE FORM --}}
            @if($ticket->status !== 'closed')
                <div class="ticket-card-surface rounded-xl p-4 sm:p-6">
                    <h3 class="text-sm font-bold text-gray-800 dark:text-white mb-4">Tulis Tanggapan Resmi</h3>
                    
                    <form method="POST" action="{{ route('admin.help-center.reply', $ticket->id) }}" class="space-y-4">
                        @csrf
                        <div>
                            <textarea 
                                name="message" 
                                rows="6" 
                                required
                                placeholder="Jelaskan solusi atau jawaban Anda secara lengkap..." 
                                class="ticket-input-surface w-full px-4 py-3 rounded-lg text-sm placeholder:text-gray-400 dark:placeholder:text-slate-500 outline-none resize-none transition-all"
                            ></textarea>
                            <p class="text-[11px] text-gray-400 dark:text-slate-400 mt-2">
                                <i class="bi bi-info-circle me-1" aria-hidden="true"></i>Mengirim tanggapan ini akan mengubah status tiket menjadi <strong>Responded</strong> dan otomatis mengirimkan email salinan ke pengguna.
                            </p>
                        </div>
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="submit" class="h-10 px-6 rounded-lg bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-xs font-semibold transition shadow-sm">
                                Kirim Balasan
                            </button>
                        </div>
                    </form>
                </div>
            @endif

        </div>

        {{-- ===== SIDEBAR INFO ===== --}}
        <div class="space-y-4">
            
            {{-- STATUS CARD --}}
            <div class="ticket-card-surface rounded-xl p-5 space-y-4">
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400 mb-2">Status Tiket</h4>
                    @php
                        $statusColors = [
                            'open' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/30',
                            'responded' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/30',
                            'closed' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30',
                        ];
                        $statusLabels = [
                            'open' => 'Terbuka (Open)',
                            'responded' => 'Dijawab (Responded)',
                            'closed' => 'Selesai (Closed)',
                        ];
                    @endphp
                    <div class="w-full text-center py-2 px-4 rounded-lg font-bold text-xs uppercase tracking-wide {{ $statusColors[$ticket->status] ?? 'bg-slate-500/10 border border-slate-500/30 text-slate-400' }}">
                        {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                    </div>
                </div>

                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400 mb-2">Rumpun Kategori</h4>
                    <div class="ticket-badge-category w-full text-center py-2 px-4 bg-gray-50 border border-gray-100 rounded-lg font-bold text-xs">
                        {{ $categories[$ticket->category] ?? ucfirst($ticket->category) }}
                    </div>
                </div>

                {{-- Action to close --}}
                @if($ticket->status !== 'closed')
                    <div class="pt-2 border-t border-gray-100 dark:ticket-meta-divider">
                        <form method="POST" action="{{ route('admin.help-center.close', $ticket->id) }}" data-confirm="Yakin ingin menutup tiket ini? Setelah ditutup, tiket tidak dapat dibalas lagi.">
                            @csrf
                            <button type="submit" class="w-full h-10 rounded-lg bg-red-600 hover:bg-red-700 text-white text-xs font-semibold transition shadow-sm">
                                Tutup Tiket
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- METADATA CARD --}}
            <div class="ticket-card-surface rounded-xl p-5 space-y-4">
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400 mb-1">Dibuat Pada</h4>
                    <p class="text-xs font-semibold text-gray-700 dark:text-slate-200">{{ $ticket->created_at->format('d F Y H:i') }}</p>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-400 mb-1">Pembaruan Terakhir</h4>
                    <p class="text-xs font-semibold text-gray-700 dark:text-slate-200">{{ $ticket->updated_at->format('d F Y H:i') }}</p>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
