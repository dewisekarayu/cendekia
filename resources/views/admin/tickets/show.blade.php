@extends('layouts.admin')

@section('title', 'Ticket #' . $ticket->id . ' - Admin')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto px-4 sm:px-0">
    
    {{-- ===== HEADER ===== --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.help-center.tickets') }}" class="w-8 h-8 rounded-lg hover:bg-gray-50 flex items-center justify-center text-gray-500 border border-gray-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-800">
                    Tiket #{{ $ticket->id }}
                </h1>
                <p class="text-xs text-gray-500">
                    {{ $ticket->subject }}
                </p>
            </div>
        </div>
    </div>

    {{-- ===== FEEDBACK MESSAGES ===== --}}
    @if(session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-xs font-semibold text-emerald-800">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-xs font-semibold text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- ===== CONTENT GRID ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- ===== CHAT / REPLY AREA ===== --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- ORIGINAL MESSAGE --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-900/10 flex items-center justify-center shrink-0">
                            <span class="font-bold text-xs text-blue-900">
                                {{ substr($ticket->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 text-sm leading-tight">{{ $ticket->name }}</p>
                            <p class="text-[11px] text-gray-500 mt-0.5">{{ $ticket->email }}</p>
                        </div>
                    </div>
                    <span class="text-[10px] text-gray-400 font-medium">{{ $ticket->created_at->format('d M Y H:i') }}</span>
                </div>
                <div class="p-6 space-y-4">
                    <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap font-normal">
                        {{ $ticket->message }}
                    </div>

                    @if($ticket->attachment_path)
                        <div class="pt-4 border-t border-gray-50 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.414a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                <span class="text-xs text-gray-500 font-medium">Lampiran File</span>
                            </div>
                            <a href="{{ asset('storage/' . $ticket->attachment_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-900 hover:underline">
                                Unduh Lampiran
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- CHAT HISTORY / REPLIES --}}
            @if($ticket->replies->count() > 0)
                <div class="space-y-4">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400">Riwayat Tanggapan ({{ $ticket->replies->count() }})</h3>
                    
                    @foreach($ticket->replies as $reply)
                        @php
                            $isAdminReply = $reply->user && ($reply->user->hasRole('admin') || $reply->user->hasRole('super-admin') || $reply->user->hasRole('dosen'));
                        @endphp
                        <div class="flex flex-col {{ $isAdminReply ? 'items-end' : 'items-start' }}">
                            <div class="max-w-[85%] rounded-xl p-4 shadow-sm border {{ $isAdminReply ? 'bg-blue-50/70 border-blue-100 text-gray-800' : 'bg-white border-gray-150 text-gray-850' }}">
                                <div class="flex items-center justify-between gap-4 mb-2 pb-2 border-b border-gray-50">
                                    <span class="text-xs font-semibold flex items-center gap-1.5 text-gray-700">
                                        @if($isAdminReply)
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-900"></span>
                                            {{ $reply->user->name }} (Support)
                                        @else
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-450"></span>
                                            {{ $reply->user->name ?? $ticket->name }}
                                        @endif
                                    </span>
                                    <span class="text-[10px] text-gray-400 font-medium">{{ $reply->created_at->format('d M Y H:i') }}</span>
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
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 sm:p-6">
                    <h3 class="text-sm font-bold text-gray-800 mb-4">Tulis Tanggapan Resmi</h3>
                    
                    <form method="POST" action="{{ route('admin.help-center.reply', $ticket->id) }}" class="space-y-4">
                        @csrf
                        <div>
                            <textarea 
                                name="message" 
                                rows="6" 
                                required
                                placeholder="Jelaskan solusi atau jawaban Anda secara lengkap..." 
                                class="w-full px-4 py-3 rounded-lg border border-gray-250 bg-transparent text-gray-800 placeholder:text-gray-400 text-sm focus:ring-2 focus:ring-blue-900 focus:border-transparent outline-none resize-none transition-all"
                            ></textarea>
                            <p class="text-[11px] text-gray-400 mt-2">
                                💡 Mengirim tanggapan ini akan mengubah status tiket menjadi **Responded** dan otomatis mengirimkan email salinan ke pengguna.
                            </p>
                        </div>
                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="submit" class="h-10 px-6 rounded-lg bg-blue-900 text-white text-xs font-semibold transition hover:bg-opacity-95 shadow-sm">
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
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 space-y-4">
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2">Status Tiket</h4>
                    @php
                        $statusColors = [
                            'open' => 'bg-orange-50 text-orange-700 border-orange-200',
                            'responded' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'closed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        ];
                        $statusLabels = [
                            'open' => 'Terbuka (Open)',
                            'responded' => 'Dijawab (Responded)',
                            'closed' => 'Selesai (Closed)',
                        ];
                    @endphp
                    <div class="w-full text-center py-2 px-4 rounded-lg border font-bold text-xs uppercase tracking-wide {{ $statusColors[$ticket->status] ?? 'bg-slate-50 border-slate-200 text-slate-700' }}">
                        {{ $statusLabels[$ticket->status] ?? $ticket->status }}
                    </div>
                </div>

                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-2">Rumpun Kategori</h4>
                    <div class="w-full text-center py-2 px-4 bg-gray-50 border border-gray-100 text-gray-700 rounded-lg font-bold text-xs">
                        {{ $categories[$ticket->category] ?? ucfirst($ticket->category) }}
                    </div>
                </div>

                {{-- Action to close --}}
                @if($ticket->status !== 'closed')
                    <div class="pt-2 border-t border-gray-50">
                        <form method="POST" action="{{ route('admin.help-center.close', $ticket->id) }}" onsubmit="return confirm('Yakin ingin menutup tiket ini? Setelah ditutup, tiket tidak dapat dibalas lagi.');">
                            @csrf
                            <button type="submit" class="w-full h-10 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition shadow-sm">
                                Tutup Tiket
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- METADATA CARD --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 space-y-4">
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Dibuat Pada</h4>
                    <p class="text-xs font-semibold text-gray-700">{{ $ticket->created_at->format('d F Y H:i') }}</p>
                </div>
                <div>
                    <h4 class="text-[10px] font-bold uppercase tracking-wider text-gray-400 mb-1">Pembaruan Terakhir</h4>
                    <p class="text-xs font-semibold text-gray-700">{{ $ticket->updated_at->format('d F Y H:i') }}</p>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
