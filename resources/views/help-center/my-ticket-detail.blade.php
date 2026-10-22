@extends('layouts.portal')

@section('title', ($isEn ? 'Ticket #' : 'Tiket #') . $ticket->id . ' — ' . $ticket->subject)

@section('content')
@php
    $categoryLabels = $isEn
        ? ['akun' => 'Account', 'kelas' => 'Classes', 'tugas' => 'Assignments', 'absensi' => 'Attendance', 'nilai' => 'Grades', 'teknis' => 'Technical', 'lainnya' => 'Other']
        : ['akun' => 'Akun', 'kelas' => 'Kelas', 'tugas' => 'Tugas', 'absensi' => 'Absensi', 'nilai' => 'Nilai', 'teknis' => 'Teknis', 'lainnya' => 'Lainnya'];
@endphp
<div class="max-w-4xl mx-auto px-4 sm:px-0 my-4 sm:my-6 space-y-6 animate-fade-in">

    {{-- Breadcrumb & Back --}}
    <div class="flex items-center justify-between gap-3">
        <nav class="flex items-center gap-2 text-xs font-medium text-slate-400" aria-label="Breadcrumb">
            <a href="{{ route('help-center.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition text-decoration-none text-slate-500 dark:text-slate-400">
                {{ $isEn ? 'Help Center' : 'Pusat Bantuan' }}
            </a>
            <span>&bull;</span>
            <a href="{{ route('help-center.my-tickets') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition text-decoration-none text-slate-500 dark:text-slate-400">
                {{ $isEn ? 'My Tickets' : 'Tiket Saya' }}
            </a>
            <span>&bull;</span>
            <span class="text-slate-700 dark:text-slate-200 font-semibold">#{{ $ticket->id }}</span>
        </nav>

        <a href="{{ route('help-center.my-tickets') }}" 
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/60 transition shadow-2xs text-decoration-none">
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>{{ $isEn ? 'Back to Tickets' : 'Kembali' }}</span>
        </a>
    </div>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-semibold flex items-center gap-2.5 shadow-xs">
            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs font-semibold flex items-center gap-2.5 shadow-xs">
            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Ticket Header Card --}}
    <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 sm:p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
            <div class="space-y-1.5 flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-xs font-mono font-bold text-slate-500 dark:text-slate-400">
                        {{ $isEn ? 'Ticket' : 'Tiket' }} #{{ $ticket->id }}
                    </span>
                    <span class="text-slate-300 dark:text-slate-600">&bull;</span>
                    <span class="text-[11px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                        {{ $categoryLabels[strtolower($ticket->category)] ?? $ticket->category }}
                    </span>

                    {{-- Status Badge --}}
                    @if($ticket->status === 'responded')
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>{{ $isEn ? 'Answered by Admin' : 'Sudah Dibalas Admin' }}</span>
                        </span>
                    @elseif($ticket->status === 'closed')
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-700/60 dark:text-slate-300 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                            <span>{{ $isEn ? 'Closed / Resolved' : 'Selesai / Ditutup' }}</span>
                        </span>
                    @else
                        <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-300 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            <span>{{ $isEn ? 'Awaiting Response' : 'Menunggu Respon Admin' }}</span>
                        </span>
                    @endif
                </div>

                <h1 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white leading-snug m-0">
                    {{ $ticket->subject }}
                </h1>

                <p class="text-xs text-slate-400 dark:text-slate-500 m-0">
                    {{ $isEn ? 'Submitted on' : 'Dikirim pada' }} {{ $ticket->created_at->translatedFormat('d F Y, H:i') }} &bull; {{ $ticket->created_at->diffForHumans() }}
                </p>
            </div>
        </div>
    </div>

    {{-- Timeline Percakapan / Chat History --}}
    <div class="space-y-4">
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500 px-1 m-0">
            {{ $isEn ? 'Conversation History' : 'Riwayat Percakapan' }}
        </h2>

        {{-- 1. Pesan Awal dari Pengguna --}}
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm space-y-3">
            <div class="flex items-center justify-between gap-3 border-b border-gray-100 dark:border-slate-700/60 pb-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-[#002B6B] text-white flex items-center justify-center text-xs font-bold shadow-2xs">
                        {{ substr($ticket->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-900 dark:text-white m-0">{{ $ticket->name }} <span class="text-slate-400 font-normal">({{ $isEn ? 'You' : 'Anda' }})</span></p>
                        <p class="text-[11px] text-slate-400 m-0">{{ $ticket->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300">
                    {{ $isEn ? 'Original Question' : 'Pertanyaan Awal' }}
                </span>
            </div>

            <div class="text-xs sm:text-sm text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                {{ $ticket->message }}
            </div>

            @if($ticket->attachment_path)
                <div class="pt-2 border-t border-gray-50 dark:border-slate-700/40">
                    <a href="{{ asset('storage/' . $ticket->attachment_path) }}" target="_blank" 
                       class="inline-flex items-center gap-2 text-xs font-bold text-blue-600 dark:text-blue-400 hover:underline">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" /></svg>
                        <span>{{ $isEn ? 'View Attachment' : 'Lihat Lampiran Berkas' }}</span>
                    </a>
                </div>
            @endif
        </div>

        {{-- 2. Balasan Percakapan (Replies) --}}
        @foreach($ticket->replies as $reply)
            @php
                $isAdmin = $reply->user && $reply->user->hasRole('admin');
            @endphp
            <div class="rounded-2xl border {{ $isAdmin ? 'border-emerald-200 dark:border-emerald-800/80 bg-emerald-50/40 dark:bg-slate-800/90' : 'border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800' }} p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between gap-3 border-b {{ $isAdmin ? 'border-emerald-100 dark:border-emerald-900/50' : 'border-gray-100 dark:border-slate-700/60' }} pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full {{ $isAdmin ? 'bg-emerald-600 text-white' : 'bg-slate-700 text-white' }} flex items-center justify-center text-xs font-bold shadow-2xs">
                            @if($isAdmin)
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            @else
                                {{ substr($reply->user->name ?? 'U', 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <p class="text-xs font-bold {{ $isAdmin ? 'text-emerald-950 dark:text-emerald-300' : 'text-slate-900 dark:text-white' }} m-0">
                                {{ $reply->user->name ?? 'Tim Support' }}
                            </p>
                            <p class="text-[11px] text-slate-400 m-0">{{ $reply->created_at->diffForHumans() }} &bull; {{ $reply->created_at->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    @if($isAdmin)
                        <span class="inline-flex items-center gap-1 text-[11px] font-black px-2.5 py-0.5 rounded-full bg-emerald-600 text-white shadow-2xs">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                            <span>{{ $isEn ? 'Admin Response' : 'Jawaban Admin' }}</span>
                        </span>
                    @else
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                            {{ $isEn ? 'Follow-up Message' : 'Tanggapan Anda' }}
                        </span>
                    @endif
                </div>

                <div class="text-xs sm:text-sm {{ $isAdmin ? 'text-slate-800 dark:text-slate-100 font-medium' : 'text-slate-700 dark:text-slate-300' }} leading-relaxed whitespace-pre-line">
                    {{ $reply->message }}
                </div>
            </div>
        @endforeach

        {{-- Jika Belum Ada Balasan Admin --}}
        @if($ticket->replies->isEmpty())
            <div class="p-6 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/40 text-center">
                <svg class="w-8 h-8 text-amber-500 mx-auto mb-2 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-xs font-bold text-slate-700 dark:text-slate-300 m-0">
                    {{ $isEn ? 'Awaiting response from admin team' : 'Tiket sedang menunggu antrean tanggapan dari tim Admin Cendekia' }}
                </p>
                <p class="text-[11px] text-slate-400 mt-1 m-0">
                    {{ $isEn ? 'Admin will reply directly here and you will receive a notification.' : 'Admin akan membalas langsung di sini dan Anda akan menerima pemberitahuan di lonceng notifikasi.' }}
                </p>
            </div>
        @endif
    </div>

    {{-- Form Balas Lanjutan --}}
    @if($ticket->status !== 'closed')
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 sm:p-6 shadow-sm space-y-3">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white m-0 flex items-center gap-2">
                <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" /></svg>
                <span>{{ $isEn ? 'Send Follow-up Reply' : 'Kirim Tanggapan Lanjutan' }}</span>
            </h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 m-0">
                {{ $isEn ? 'Still have questions or need further clarification? Reply to the admin below.' : 'Masih ada kendala atau ingin menanyakan hal lain terkait tiket ini? Tulis balasan Anda untuk admin di bawah.' }}
            </p>

            <form action="{{ route('help-center.my-tickets.reply', $ticket->id) }}" method="POST" class="space-y-3 m-0">
                @csrf
                <textarea name="message" rows="3" required
                    class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900 px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 dark:text-white placeholder:text-slate-400 outline-none transition focus:border-blue-600 focus:bg-white dark:focus:bg-slate-900"
                    placeholder="{{ $isEn ? 'Write your follow-up reply here...' : 'Ketik tanggapan atau informasi tambahan Anda di sini...' }}"></textarea>

                <div class="flex justify-end">
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#002B6B] hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700 text-xs font-bold text-white transition shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                        <span>{{ $isEn ? 'Send Reply' : 'Kirim Balasan' }}</span>
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 p-4 text-center">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 m-0">
                {{ $isEn ? 'This ticket has been marked as resolved and closed by admin.' : 'Tiket bantuan ini telah diselesaikan dan ditutup oleh Admin.' }}
            </p>
        </div>
    @endif

</div>
@endsection
