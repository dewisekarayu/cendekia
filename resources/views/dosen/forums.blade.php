@extends('layouts.portal')
@section('title', 'Forum Diskusi')
@section('content')
@php
    $user     = auth()->user();
    $palette  = ['#321270','#0C4A6E','#1E3A8A','#164E63','#1C3144','#0F172A','#1F2937'];

    function forumAvatarColor(string $name, array $pal): string {
        return $pal[abs(crc32($name)) % count($pal)];
    }
@endphp

<style>
    :root{
        --forum-primary: #321270;
        --forum-primary-dark: #321270;
        --forum-light: #CDDCFF;
        --forum-bg-1: #F4F7FF;
        --forum-bg-2: #E9EFFF;
    }

    .forum-chat-wrap{
        display: flex !important;
        flex-direction: column !important;
        height: calc(100vh - 120px);
        min-height: 520px;
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 8px 30px rgba(0,43,107,0.10);
        border: 1px solid #E3E9F7;
    }

    /* ===== Header ===== */
    .forum-header{
        flex-shrink: 0;
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 14px;
        padding: 16px 20px;
        background: linear-gradient(120deg, var(--forum-primary) 0%, var(--forum-primary-dark) 100%);
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .forum-header-left{
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 12px;
        flex: 1 1 auto;
        min-width: 0;
    }
    .forum-back-btn{
        display: none;
        width: 36px; height: 36px; border-radius: 50%;
        align-items: center; justify-content: center;
        color: #fff; background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.25);
        flex-shrink: 0;
    }
    @media (max-width: 575.98px){
        .forum-back-btn{ display: flex !important; }
    }

    .forum-header-avatar{
        width: 46px; height: 46px; border-radius: 50%;
        display: flex !important; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: 13px;
        border: 2px solid rgba(255,255,255,.35);
        flex-shrink: 0;
    }

    .forum-header-text{ flex: 1 1 auto; min-width: 0; }
    .forum-header-title{
        color: #fff; font-weight: 700; font-size: 15px; margin: 0;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .forum-header-sub{
        color: var(--forum-light); font-size: 12px; margin: 2px 0 0 0;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .forum-header-btn{
        width: 38px; height: 38px; border-radius: 50%;
        display: flex !important; align-items: center; justify-content: center;
        color: #fff; background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.25);
        transition: .2s; flex-shrink: 0; text-decoration: none;
    }
    .forum-header-btn:hover{ background: rgba(255,255,255,.2); color: #fff; }

    /* ===== Body ===== */
    .forum-body{
        flex: 1 1 auto;
        overflow-y: auto;
        padding: 24px 20px;
        background: linear-gradient(180deg, var(--forum-bg-1) 0%, var(--forum-bg-2) 100%);
    }

    /* PERBAIKAN: Menambah ruang napas atas-bawah pada pembatas tanggal */
    .forum-center-row{
        display: flex !important;
        justify-content: center !important;
        width: 100%;
        margin-top: 24px;
        margin-bottom: 24px;
    }
    .forum-center-row:first-child {
        margin-top: 8px;
    }

    .forum-chip{
        display: inline-flex !important; align-items: center; gap: 8px;
        background: #fff; border: 1.5px solid var(--forum-primary);
        color: var(--forum-primary); font-weight: 700; font-size: 11.5px;
        padding: 7px 16px; border-radius: 999px;
        box-shadow: 0 2px 6px rgba(0,43,107,.08);
    }

    .forum-date-chip{
        background: #fff; border: 1.5px solid var(--forum-primary);
        color: var(--forum-primary); font-weight: 700; font-size: 11px;
        padding: 6px 14px; border-radius: 999px;
    }

    /* PERBAIKAN: Penegasan layout arah baris chat dan penambahan margin-top ke 20px */
    .forum-msg-row{
        display: flex !important;
        flex-direction: row !important;
        align-items: flex-end !important;
        gap: 10px;
        width: 100%;
        margin-top: 20px;
    }
    .forum-msg-row.mine{ 
        justify-content: flex-end !important; 
    }
    .forum-msg-row.grouped{ margin-top: 6px; }

    .forum-avatar-slot{ width: 34px; flex-shrink: 0; }
    .forum-avatar-sm{
        width: 34px; height: 34px; border-radius: 50%;
        display: flex !important; align-items: center; justify-content: center;
        color: #fff; font-size: 12px; font-weight: 700;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
    }

    /* PERBAIKAN: Memaksa bubble mine terdorong ke kanan penuh dengan margin-left: auto */
    .forum-bubble-col{
        display: flex !important;
        flex-direction: column !important;
        max-width: 70%;
    }
    .forum-bubble-col.mine{ 
        align-items: flex-end !important; 
        margin-left: auto; 
    }
    .forum-bubble-col.theirs{ 
        align-items: flex-start !important; 
        margin-right: auto;
    }

    .forum-sender-name{
        font-size: 12px; font-weight: 700; margin: 0 0 6px 4px;
        color: #0284C7;
    }
    .forum-sender-name.dosen{ color: #4338CA; }

    /* PERBAIKAN: Menambah padding bubble agar teks di dalamnya lebih pas */
    .forum-bubble{
        padding: 12px 18px;
        border-radius: 18px;
        min-width: 90px;
        box-shadow: 0 2px 6px rgba(15,23,42,.06);
        border: 1px solid rgba(0,43,107,.12);
    }
    .forum-bubble.mine{ background: var(--forum-light); border-bottom-right-radius: 4px; }
    .forum-bubble.theirs{ background: #fff; border-bottom-left-radius: 4px; }
    .forum-bubble.theirs.dosen{ background: #E9E7FF; }
    
    .forum-bubble.grouped.mine{ border-bottom-right-radius: 18px; border-top-right-radius: 4px; }
    .forum-bubble.grouped.theirs{ border-bottom-left-radius: 18px; border-top-left-radius: 4px; }

    .forum-bubble-text{
        font-size: 14px; line-height: 1.5; color: #1F2937;
        white-space: pre-wrap; word-break: break-word; margin: 0; font-weight: 500;
    }

    .forum-bubble-meta{
        display: flex !important; align-items: center; justify-content: flex-end;
        gap: 6px; margin-top: 6px;
    }
    .forum-bubble-time{ font-size: 11px; color: #6B7280; white-space: nowrap; }

    .forum-empty{
        display: flex !important;
        flex-direction: column !important;
        align-items: center; justify-content: center;
        height: 100%; text-align: center; padding: 48px 16px;
    }
    .forum-empty-icon{
        width: 80px; height: 80px; border-radius: 50%;
        background: #fff; border: 1px solid #E3E9F7;
        display: flex !important; align-items: center; justify-content: center;
        margin-bottom: 16px; color: #B9C4DA;
    }
    .forum-empty-title{ font-weight: 700; color: #374151; margin: 0 0 4px 0; }
    .forum-empty-sub{ font-size: 13px; color: #9CA3AF; margin: 0; }

    /* ===== Input bar ===== */
    .forum-input-bar{
        flex-shrink: 0;
        display: flex !important;
        flex-direction: row !important;
        align-items: flex-end !important;
        gap: 12px;
        padding: 16px 20px;
        background: #fff;
        border-top: 1px solid #E3E9F7;
    }

    .forum-icon-btn{
        width: 44px; height: 44px; border-radius: 50%;
        display: flex !important; align-items: center; justify-content: center;
        color: var(--forum-primary); background: #fff;
        border: 1.5px solid var(--forum-light);
        transition: .2s; flex-shrink: 0; cursor: pointer;
    }
    .forum-icon-btn:hover{ background: var(--forum-bg-2); border-color: var(--forum-primary); }

    .forum-form{
        display: flex !important;
        flex-direction: row !important;
        align-items: flex-end !important;
        gap: 10px;
        flex: 1 1 auto;
        min-width: 0;
    }

    .forum-textarea{
        flex: 1 1 auto;
        min-width: 0;
        resize: none;
        border-radius: 22px;
        border: 1.5px solid var(--forum-primary);
        padding: 11px 18px;
        font-size: 14px; font-weight: 500;
        max-height: 128px; overflow-y: auto;
        font-family: inherit;
    }
    .forum-textarea:focus{
        outline: none;
        box-shadow: 0 0 0 3px rgba(0,43,107,.15);
        border-color: var(--forum-primary);
        background: var(--forum-bg-1);
    }

    .forum-send-btn{
        width: 46px; height: 46px; border-radius: 50%; flex-shrink: 0;
        display: flex !important; align-items: center; justify-content: center;
        background: linear-gradient(135deg, var(--forum-primary) 0%, var(--forum-primary-dark) 100%);
        color: #fff; border: none; box-shadow: 0 3px 10px rgba(0,43,107,.3);
        transition: .15s; cursor: pointer;
    }
    .forum-send-btn:hover{ filter: brightness(1.1); }
    .forum-send-btn:active{ transform: scale(.94); }

    /* ===== Empty state ===== */
    .forum-placeholder{
        display: flex !important;
        flex-direction: column !important;
        align-items: center; justify-content: center;
        flex: 1 1 auto;
        text-align: center;
        padding: 48px 20px;
        background: linear-gradient(180deg, var(--forum-bg-1) 0%, var(--forum-bg-2) 100%);
    }
    .forum-placeholder-icon{
        width: 96px; height: 96px; border-radius: 50%;
        background: #fff; border: 2px solid var(--forum-primary);
        display: flex !important; align-items: center; justify-content: center;
        margin-bottom: 16px;
    }
    .forum-placeholder-title{ font-weight: 700; color: var(--forum-primary); margin: 0; font-size: 18px; }
    .forum-placeholder-sub{ color: #9CA3AF; margin-top: 8px; max-width: 320px; font-size: 14px; }

    /* ===== Dark Mode Override ===== */
    html.dark .forum-chat-wrap {
        background: #1e293b !important;
        border: 1px solid #334155 !important;
        box-shadow: 0 8px 30px rgba(0,0,0,0.3) !important;
    }
    html.dark .forum-body {
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%) !important;
    }
    html.dark .forum-bubble.mine {
        background: #312e81 !important;
        border: 1px solid #4338ca !important;
    }
    html.dark .forum-bubble.theirs {
        background: #334155 !important;
        border: 1px solid #475569 !important;
    }
    html.dark .forum-bubble.theirs.dosen {
        background: #3c3a6e !important;
        border: 1px solid #5850ec !important;
    }
    html.dark .forum-bubble-text {
        color: #f8fafc !important;
    }
    html.dark .forum-bubble-time {
        color: #94a3b8 !important;
    }
    html.dark .forum-date-chip {
        background: #1e293b !important;
        border: 1.5px solid #6366f1 !important;
        color: #818cf8 !important;
    }
    html.dark .forum-chip {
        background: #1e293b !important;
        border: 1.5px solid #6366f1 !important;
        color: #818cf8 !important;
    }
    html.dark .forum-empty-icon {
        background: #1e293b !important;
        border: 1px solid #334155 !important;
        color: #475569 !important;
    }
    html.dark .forum-empty-title {
        color: #f8fafc !important;
    }
    html.dark .forum-input-bar {
        background: #1e293b !important;
        border-top: 1px solid #334155 !important;
    }
    html.dark .forum-textarea {
        background: #0f172a !important;
        border: 1.5px solid #475569 !important;
        color: #f8fafc !important;
    }
    html.dark .forum-textarea:focus {
        border-color: #818cf8 !important;
        background: #0f172a !important;
    }
    html.dark .forum-icon-btn {
        background: #1e293b !important;
        border: 1.5px solid #334155 !important;
        color: #a78bfa !important;
    }
    html.dark .forum-icon-btn:hover {
        background: #334155 !important;
        border-color: #8b5cf6 !important;
    }
    html.dark .forum-sender-name {
        color: #38bdf8 !important;
    }
    html.dark .forum-sender-name.dosen {
        color: #a78bfa !important;
    }
    html.dark .forum-placeholder {
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%) !important;
    }
    html.dark .forum-placeholder-icon {
        background: #1e293b !important;
        border: 2px solid #6366f1 !important;
    }
    html.dark .forum-placeholder-icon svg {
        stroke: #818cf8 !important;
    }
    html.dark .forum-placeholder-title {
        color: #818cf8 !important;
    }
</style>

{{-- HEADER --}}
<div class="mb-5 overflow-hidden rounded-2xl bg-gradient-to-br from-[#321270] to-[#4a1fa8] dark:from-indigo-950 dark:to-purple-900 px-6 py-6 sm:px-8 shadow-lg relative">
    <div class="pointer-events-none absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/5"></div>
    <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
        <div class="min-w-0">
            <div class="mb-2 flex flex-wrap items-center gap-2">
                <span class="rounded-lg border border-white/20 bg-white/15 px-2.5 py-1 text-xs font-bold text-white">
                    {{ $kelas->mataKuliah?->kode_mk ?? '-' }}
                </span>
                <span class="text-xs text-purple-200/80">{{ $kelas->semester?->nama_semester ?? 'Semester Aktif' }}</span>
            </div>
            <h1 class="text-xl font-extrabold text-white sm:text-2xl">{{ $kelas->mataKuliah?->nama_mk ?? 'Detail Kelas' }}</h1>
            <p class="mt-1 text-sm text-purple-100/80">
                {{ $kelas->hari }}, {{ substr($kelas->jam_mulai,0,5) }}–{{ substr($kelas->jam_selesai,0,5) }} · {{ $kelas->ruangan ?? '-' }}
            </p>
        </div>
        <div class="shrink-0 flex flex-wrap gap-2">
            <span class="inline-flex items-center gap-1.5 rounded-xl border border-white/20 bg-white/10 px-3 py-1.5 text-xs font-semibold text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                {{ $kelas->mahasiswa->count() }} mahasiswa
            </span>
        </div>
    </div>
</div>

{{-- TABS --}}
@php
    $tabLinks = [
        'Beranda'      => ['url' => route('dosen.kelas-detail', $kelas->id), 'active' => request()->routeIs('dosen.kelas-detail')],
        'Absensi'      => ['url' => route('dosen.absensi.index', $kelas->id),  'active' => request()->routeIs('dosen.absensi.*')],
        'Materi'       => ['url' => route('dosen.kelas-materi', $kelas->id), 'active' => request()->routeIs('dosen.kelas-materi')],
        'Tugas'        => ['url' => route('dosen.kelas-tugas', $kelas->id),  'active' => request()->routeIs('dosen.kelas-tugas')],
        'Forum'        => ['url' => route('dosen.kelas-forum', $kelas->id),  'active' => request()->routeIs('dosen.kelas-forum')],
        'Penilaian'    => ['url' => route('dosen.gradebook', ['kelas_id' => $kelas->id]), 'active' => request()->routeIs('dosen.gradebook')],
    ];
@endphp
<div class="mb-5 flex items-center gap-1 overflow-x-auto rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-1.5 shadow-sm transition-colors duration-200">
    @foreach ($tabLinks as $label => $tab)
        <a href="{{ $tab['url'] }}"
            class="whitespace-nowrap rounded-xl px-4 py-2 text-xs font-bold transition-all duration-200 ease-out
                {{ $tab['active']
                    ? 'bg-[#321270] dark:bg-purple-650 text-white shadow-sm shadow-purple-900/20 scale-[1.02]'
                    : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-800 dark:hover:text-white hover:scale-[1.02]' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

<div class="forum-chat-wrap">

@if ($activeForum)
@php
    $dn       = $activeForum->kelasPerkuliahan?->dosen?->name ?? 'Dosen';
    $mn       = $activeForum->judul;
    $mk       = strtoupper(substr($activeForum->kelasPerkuliahan?->mataKuliah?->kode_mk ?? 'MK', 0, 2));
    $hdrClr   = forumAvatarColor($activeForum->kelasPerkuliahan?->mataKuliah?->nama_mk ?? 'Kelas', $palette);
    $msgs     = $activeForum->komentar;
    $total    = $msgs->count();
    $byDay    = $msgs->groupBy(fn($m) => $m->created_at->format('Y-m-d'));
@endphp

    {{-- Header --}}
    <div class="forum-header">
        <div class="forum-header-left">
            <button @click="mobile = false" class="forum-back-btn" type="button">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div class="forum-header-avatar" style="background: linear-gradient(135deg, {{ $hdrClr }} 0%, #001A40 100%)">{{ $mk }}</div>
            <div class="forum-header-text">
                <p class="forum-header-title">{{ $mn }}</p>
                <p class="forum-header-sub">{{ $dn }} &bull; {{ $total }} pesan</p>
            </div>
        </div>
        <a href="{{ route('dosen.kelas-detail', $activeForum->kelas_perkuliahan_id) }}" class="forum-header-btn">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </a>
    </div>

    {{-- Chat area --}}
    <div id="chatMessages" class="forum-body">

        <div class="forum-center-row">
            <span class="forum-chip">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                Private Chat &bull; <strong>{{ $mn }}</strong>
            </span>
        </div>

        @forelse ($byDay as $tanggal => $dayMsgs)

            <div class="forum-center-row">
                <span class="forum-date-chip">
                    @php
                        $d = \Carbon\Carbon::parse($tanggal);
                        echo $d->isToday() ? 'Hari Ini' : ($d->isYesterday() ? 'Kemarin' : $d->translatedFormat('l, d F Y'));
                    @endphp
                </span>
            </div>

            @foreach ($dayMsgs->values() as $i => $msg)
                @php
                    $mine    = $msg->user_id === $user->id;
                    $isDosen = $msg->user?->hasRole('dosen');
                    $prev    = $dayMsgs->values()[$i - 1] ?? null;
                    $grouped = $prev && $prev->user_id === $msg->user_id
                               && $msg->created_at->diffInSeconds($prev->created_at) < 180;

                    $sName  = $msg->user?->name ?? 'Anonim';
                    $sFirst = explode(' ', $sName)[0];
                    $sClr   = forumAvatarColor($sName, $palette);
                    $timeStr = $msg->created_at->format('H:i');
                @endphp

                <div class="forum-msg-row {{ $mine ? 'mine' : '' }} {{ $grouped ? 'grouped' : '' }}">

                    @unless ($mine)
                        <div class="forum-avatar-slot">
                            @unless ($grouped)
                                <div class="forum-avatar-sm" style="background:{{ $sClr }}">
                                    {{ strtoupper(substr($sFirst, 0, 1)) }}
                                </div>
                            @endunless
                        </div>
                    @endunless

                    <div class="forum-bubble-col {{ $mine ? 'mine' : 'theirs' }}">

                        @unless ($mine || $grouped)
                            <p class="forum-sender-name {{ $isDosen ? 'dosen' : '' }}">
                                @if ($isDosen)&#128104;&#8205;&#127979; {{ $sName }}
                                @else{{ $sFirst }}
                                @endif
                            </p>
                        @endunless

                        <div class="forum-bubble {{ $mine ? 'mine' : 'theirs' }} {{ $isDosen ? 'dosen' : '' }} {{ $grouped ? 'grouped' : '' }}">
                            <p class="forum-bubble-text">{{ $msg->isi }}</p>
                            <div class="forum-bubble-meta">
                                <span class="forum-bubble-time">{{ $timeStr }}</span>
                                @if ($mine)
                                    <svg width="15" height="10" viewBox="0 0 16 11" fill="none">
                                        <path d="M1 5.5L4.5 9L10 2" stroke="#002B6B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M6 5.5L9.5 9L15 2" stroke="#002B6B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

        @empty
            <div class="forum-empty">
                <div class="forum-empty-icon">
                    <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <p class="forum-empty-title">Belum ada pesan</p>
                <p class="forum-empty-sub">Jadilah yang pertama memulai diskusi! &#126980;</p>
            </div>
        @endforelse

        <div id="chatBottom"></div>
    </div>

    {{-- Input bar --}}
    <div class="forum-input-bar">
        <button type="button" class="forum-icon-btn">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </button>

        <form id="chatForm" method="POST"
              action="{{ route('dosen.kelas-forum.pesan', [$activeForum->kelas_perkuliahan_id, $activeForum->id]) }}"
              class="forum-form">
            @csrf
            <textarea id="chatInput" name="isi" rows="1" maxlength="2000"
                      placeholder="Ketik pesan..."
                      class="forum-textarea"></textarea>

            <button type="submit" id="sendBtn" class="forum-send-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z"/>
                </svg>
            </button>
        </form>
    </div>

@else
    {{-- No forum selected --}}
    <div class="forum-placeholder">
        <div class="forum-placeholder-icon">
            <svg width="44" height="44" fill="none" viewBox="0 0 24 24" stroke="#002B6B" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <h3 class="forum-placeholder-title">Pilih Percakapan</h3>
        <p class="forum-placeholder-sub">Pilih forum dari panel kiri untuk mulai berdiskusi.</p>
    </div>
@endif

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const box = document.getElementById('chatMessages');
        if (box) box.scrollTop = box.scrollHeight;

        const ta = document.getElementById('chatInput');
        if (ta) {
            const resize = () => {
                ta.style.height = 'auto';
                ta.style.height = Math.min(ta.scrollHeight, 128) + 'px';
            };
            ta.addEventListener('input', resize);

            ta.addEventListener('keydown', e => {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (ta.value.trim()) document.getElementById('chatForm').submit();
                }
            });
            setTimeout(() => ta.focus(), 80);
        }
    });
</script>
@endpush