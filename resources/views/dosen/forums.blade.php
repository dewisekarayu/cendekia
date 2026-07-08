@extends('layouts.portal')
@section('title', 'Forum Diskusi')
@section('content')
@php
    $user    = auth()->user();
    $palette = ['#075E54','#128C7E','#25D366','#34B7F1','#6B21A8','#BE123C','#B45309'];

    function dosenForumAvatarColor(string $name, array $pal): string {
        return $pal[abs(crc32($name)) % count($pal)];
    }
@endphp

{{-- Full-height flex container --}}
<div class="flex h-[calc(100vh-6rem)] min-h-[540px] overflow-hidden rounded-2xl border border-slate-200 shadow-md bg-white"
     x-data="{ mobile: {{ $activeForum ? 'true' : 'false' }} }">

{{-- ============================================
     LEFT — Thread list
============================================ --}}
<aside class="w-[300px] lg:w-[340px] flex-none flex flex-col border-r border-gray-200 bg-white overflow-hidden"
       :class="mobile ? 'hidden sm:flex' : 'flex'">

    {{-- Topbar --}}
    <div class="flex items-center justify-between px-4 py-3 bg-[#321270]">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-white text-sm font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <p class="text-[13px] font-bold text-white leading-tight">{{ explode(' ', $user->name)[0] }}</p>
                <p class="text-[10px] text-purple-200/80">Forum Diskusi Kelas</p>
            </div>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white/70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
    </div>

    {{-- Search --}}
    <div class="px-3 py-2 bg-gray-50 border-b border-gray-200">
        <div class="flex items-center gap-2 rounded-full bg-white border border-gray-200 px-3 py-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input id="forumSearch" type="search" placeholder="Cari kelas..."
                   class="flex-1 text-[12px] text-gray-700 placeholder-gray-400 bg-transparent focus:outline-none">
        </div>
    </div>

    {{-- Thread list --}}
    <div class="flex-1 overflow-y-auto divide-y divide-gray-100" id="forumList">
        @forelse ($forumList as $forum)
            @php
                $isActive  = $activeForum && $activeForum->id === $forum->id;
                $last      = $forum->komentar->last();
                $matkul    = $forum->kelasPerkuliahan?->mataKuliah?->nama_mk ?? 'Kelas';
                $kode      = strtoupper(substr($forum->kelasPerkuliahan?->mataKuliah?->kode_mk ?? 'MK', 0, 2));
                $avatarClr = dosenForumAvatarColor($matkul, $palette);
                $isUnread  = !$isActive && $last && $last->user_id !== $user->id;
                $lastTime  = $last ? ($last->created_at->isToday()
                                ? $last->created_at->format('H:i')
                                : $last->created_at->format('d/m/y')) : '';
            @endphp
            <a href="{{ route('dosen.forums', ['forum' => $forum->id]) }}"
               data-search="{{ strtolower($matkul) }}"
               @click="mobile = true"
               class="forum-thread flex items-center gap-3 px-3 py-3 transition-colors
                      {{ $isActive ? 'bg-purple-50' : 'hover:bg-gray-50' }}">

                <div class="relative shrink-0">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-semibold text-[13px] shadow-sm"
                         style="background: {{ $avatarClr }}">{{ $kode }}</div>
                    @if ($isActive)
                        <span class="absolute -right-0.5 -bottom-0.5 w-3.5 h-3.5 rounded-full bg-[#25D366] border-2 border-white"></span>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-baseline mb-0.5">
                        <p class="text-[13.5px] font-semibold text-gray-900 truncate">{{ $matkul }}</p>
                        <span class="text-[11px] shrink-0 ml-2 {{ $isUnread ? 'text-[#25D366] font-bold' : 'text-gray-400' }}">
                            {{ $lastTime }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <p class="text-[12px] text-gray-500 truncate flex-1">
                            @if ($last)
                                @if ($last->user_id === $user->id)
                                    <span class="text-gray-400 inline-flex items-center gap-0.5 mr-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-sky-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75l6 6 9-13.5" opacity=".5"/>
                                        </svg>
                                    </span>
                                @else
                                    <span class="font-semibold text-gray-600 mr-0.5">{{ explode(' ', $last->user?->name ?? '')[0] }}:</span>
                                @endif
                                {{ Str::limit($last->isi, 32) }}
                            @else
                                <span class="italic text-gray-400">Belum ada pesan</span>
                            @endif
                        </p>
                        @if ($isUnread)
                            <span class="ml-2 shrink-0 w-5 h-5 rounded-full bg-[#25D366] text-white text-[10px] font-bold flex items-center justify-center">!</span>
                        @endif
                    </div>
                    {{-- Jumlah mahasiswa di kelas --}}
                    <p class="text-[11px] text-gray-400 mt-0.5">
                        {{ $forum->kelasPerkuliahan?->mahasiswa?->count() ?? 0 }} mahasiswa
                    </p>
                </div>
            </a>
        @empty
            <div class="flex flex-col items-center justify-center py-16 px-5 text-center text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <p class="text-sm font-semibold">Belum ada kelas aktif</p>
                <p class="text-xs mt-1">Forum akan muncul otomatis setelah ada kelas yang diampu.</p>
            </div>
        @endforelse
    </div>
</aside>

{{-- ============================================
     RIGHT — Chat window
============================================ --}}
<section class="flex-1 min-w-0 flex flex-col overflow-hidden"
         :class="mobile ? 'flex' : 'hidden sm:flex'">

@if ($activeForum)
@php
    $mahasiswaCount = $activeForum->kelasPerkuliahan?->mahasiswa?->count() ?? 0;
    $mn      = $activeForum->kelasPerkuliahan?->mataKuliah?->nama_mk ?? 'Kelas';
    $mk      = strtoupper(substr($activeForum->kelasPerkuliahan?->mataKuliah?->kode_mk ?? 'MK', 0, 2));
    $hdrClr  = dosenForumAvatarColor($mn, $palette);
    $msgs    = $activeForum->komentar;
    $total   = $msgs->count();
    $byDay   = $msgs->groupBy(fn($m) => $m->created_at->format('Y-m-d'));
@endphp

    {{-- Header --}}
    <div class="shrink-0 flex items-center gap-3 px-4 py-2.5 bg-[#321270] shadow-md z-10">
        <button @click="mobile = false"
                class="sm:hidden shrink-0 w-8 h-8 flex items-center justify-center text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
             style="background: {{ $hdrClr }}">{{ $mk }}</div>
        <div class="flex-1 min-w-0">
            <p class="text-[14px] font-bold text-white leading-tight truncate">{{ $mn }}</p>
            <p class="text-[11px] text-purple-200/80 truncate">{{ $mahasiswaCount }} mahasiswa · {{ $total }} pesan</p>
        </div>
        <a href="{{ route('dosen.kelas-detail', $activeForum->kelas_perkuliahan_id) }}"
           class="shrink-0 flex items-center justify-center w-9 h-9 rounded-full hover:bg-white/10 text-white/80 hover:text-white transition"
           title="Detail Kelas">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </a>
    </div>

    {{-- Chat area --}}
    <div id="chatMessages"
         class="flex-1 overflow-y-auto px-3 py-3"
         style="background-color:#e5ddd5;
                background-image:url(\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGQAAABkCAYAAABw4pVUAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QMeDBIRVFTlBQAAAB1pVFh0Q29tbWVudAAAAAAAQ3JlYXRlZCB3aXRoIEdJTVBkLmUHAAAAHElEQVRo3u3BMQEAAADCoPVP7WsIoAAAAAAAAAAAeQMBxAABHa8drwAAAABJRU5ErkJggg==\");">

        {{-- Info chip --}}
        <div class="flex justify-center mb-3">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/90 border border-gray-200 shadow-sm px-4 py-1.5 text-[11px] text-gray-600 backdrop-blur-sm">
                👨‍🏫 Anda sedang mengajar di forum
                <strong class="text-[#321270]">{{ $mn }}</strong>
            </span>
        </div>

        @forelse ($byDay as $tanggal => $dayMsgs)

            {{-- Date chip --}}
            <div class="flex justify-center my-3">
                <span class="rounded-full bg-[#e1f0ea] border border-[#c8e6c9] px-4 py-1 text-[11px] font-semibold text-[#075E54] shadow-sm">
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

                    $sName   = $msg->user?->name ?? 'Anonim';
                    $sFirst  = explode(' ', $sName)[0];
                    $sClr    = dosenForumAvatarColor($sName, $palette);
                    $timeStr = $msg->created_at->format('H:i');

                    if ($mine) {
                        $bg      = '#DCF8C6';
                        $txtCls  = 'text-gray-900';
                        $tmCls   = 'text-gray-500';
                        $nameCls = '';
                    } elseif ($isDosen) {
                        $bg      = '#EDE9FE';
                        $txtCls  = 'text-slate-800';
                        $tmCls   = 'text-gray-400';
                        $nameCls = 'text-violet-700';
                    } else {
                        $bg      = '#FFFFFF';
                        $txtCls  = 'text-gray-900';
                        $tmCls   = 'text-gray-400';
                        $nameCls = 'text-[#075E54]';
                    }
                @endphp

                <div class="flex items-end gap-1.5 mb-0.5 {{ $mine ? 'justify-end' : 'justify-start' }} {{ $grouped ? '' : 'mt-2' }}">

                    @unless ($mine)
                        <div class="w-8 shrink-0 self-end mb-0.5">
                            @unless ($grouped)
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm"
                                     style="background:{{ $sClr }}">
                                    {{ strtoupper(substr($sFirst, 0, 1)) }}
                                </div>
                            @endunless
                        </div>
                    @endunless

                    <div class="relative flex flex-col {{ $mine ? 'items-end' : 'items-start' }} max-w-[70%] sm:max-w-[60%]">

                        @unless ($mine || $grouped)
                            <p class="text-[11px] font-bold mb-0.5 px-2 {{ $nameCls }}">
                                @if ($isDosen)👨‍🏫 {{ $sName }}
                                @else{{ $sFirst }}
                                @endif
                            </p>
                        @endunless

                        <div class="relative">

                            @unless ($grouped)
                                @if ($mine)
                                    <svg class="absolute -right-[7px] bottom-[6px] w-3 h-4 drop-shadow-sm"
                                         viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 16 Q10 14 11 4 Q11 0 8 0 L8 14 Q8 15 0 16Z" fill="#DCF8C6"/>
                                    </svg>
                                @else
                                    <svg class="absolute -left-[7px] bottom-[6px] w-3 h-4 drop-shadow-sm"
                                         viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 16 Q2 14 1 4 Q1 0 4 0 L4 14 Q4 15 12 16Z" fill="{{ $bg }}"/>
                                    </svg>
                                @endif
                            @endunless

                            <div class="rounded-xl shadow-sm px-3 py-2 min-w-[80px]
                                        {{ $mine
                                            ? ($grouped ? 'rounded-tr-xl' : 'rounded-tr-sm')
                                            : ($grouped ? 'rounded-tl-xl' : 'rounded-tl-sm') }}"
                                 style="background: {{ $bg }}">
                                <p class="text-[13.5px] leading-[1.5] {{ $txtCls }} whitespace-pre-wrap break-words">{{ $msg->isi }}</p>
                                <div class="flex items-center justify-end gap-1 mt-1 -mb-0.5">
                                    <span class="text-[10px] {{ $tmCls }} whitespace-nowrap">{{ $timeStr }}</span>
                                    @if ($mine)
                                        <svg width="16" height="11" viewBox="0 0 16 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M1 5.5L4.5 9L10 2" stroke="#53BDEB" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M6 5.5L9.5 9L15 2" stroke="#53BDEB" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach

        @empty
            <div class="flex flex-col items-center justify-center h-full py-16 text-center">
                <div class="w-20 h-20 rounded-full bg-white/70 border border-gray-200 flex items-center justify-center mb-4 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <p class="text-sm font-bold text-gray-500">Belum ada pesan</p>
                <p class="text-xs text-gray-400 mt-1">Mulai percakapan dengan mahasiswa Anda 🎓</p>
            </div>
        @endforelse

        <div id="chatBottom"></div>
    </div>

    {{-- Input bar --}}
    <div class="shrink-0 flex items-end gap-2 px-2 py-2 bg-[#F0F2F5] border-t border-gray-200">

        <button type="button" class="shrink-0 mb-1 w-10 h-10 flex items-center justify-center rounded-full text-gray-500 hover:bg-gray-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </button>

        <form id="chatForm" method="POST"
              action="{{ route('dosen.forum.pesan', $activeForum->id) }}"
              class="flex-1 flex items-end gap-2">
            @csrf
            <textarea id="chatInput" name="isi" rows="1" maxlength="2000"
                      placeholder="Tulis pesan kepada mahasiswa..."
                      class="flex-1 resize-none rounded-2xl bg-white border-0 px-4 py-2.5
                             text-[13.5px] text-gray-900 leading-relaxed placeholder-gray-400
                             focus:outline-none focus:ring-2 focus:ring-[#321270]/30
                             shadow-sm max-h-32 overflow-y-hidden"></textarea>

            <button type="submit" id="sendBtn"
                    class="shrink-0 mb-0.5 w-11 h-11 flex items-center justify-center rounded-full
                           bg-[#321270] text-white shadow-md
                           hover:bg-[#4c1d95] active:scale-90 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z"/>
                </svg>
            </button>
        </form>
    </div>

@else
    <div class="flex-1 flex flex-col items-center justify-center p-10 text-center"
         style="background:#e5ddd5">
        <div class="w-24 h-24 rounded-full bg-white/80 border border-gray-200 flex items-center justify-center mb-5 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-11 h-11 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
        </div>
        <h3 class="text-base font-bold text-gray-600">Pilih percakapan</h3>
        <p class="mt-2 max-w-xs text-sm text-gray-400 leading-relaxed">
            Pilih kelas dari panel kiri untuk mulai berdiskusi dengan mahasiswa.
        </p>
    </div>
@endif

</section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    // 1. Auto-scroll ke bawah
    const box = document.getElementById('chatMessages');
    if (box) box.scrollTop = box.scrollHeight;

    // 2. Auto-resize textarea
    const ta = document.getElementById('chatInput');
    if (ta) {
        const resize = () => {
            ta.style.height = 'auto';
            ta.style.height = Math.min(ta.scrollHeight, 128) + 'px';
        };
        ta.addEventListener('input', resize);

        // Enter = kirim, Shift+Enter = baris baru
        ta.addEventListener('keydown', e => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (ta.value.trim()) document.getElementById('chatForm').submit();
            }
        });
        setTimeout(() => ta.focus(), 80);
    }

    // 3. Search kelas
    const s = document.getElementById('forumSearch');
    if (s) {
        s.addEventListener('input', () => {
            const q = s.value.toLowerCase();
            document.querySelectorAll('.forum-thread').forEach(el => {
                el.style.display = (!q || (el.dataset.search || '').includes(q)) ? '' : 'none';
            });
        });
    }
});
</script>
@endpush
