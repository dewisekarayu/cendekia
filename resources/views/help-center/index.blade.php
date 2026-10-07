{{--
    Pusat Bantuan & FAQ — LMS Cendekia
    Layout 2 kolom: FAQ (kiri) + Hubungi Kami & Tips (kanan)
--}}
@extends('layouts.portal')
@section('title', 'Pusat Bantuan')

@section('content')
@php
    $allFaqs = collect();
    foreach ($faqs as $categoryKey => $categoryFaqs) {
        foreach ($categoryFaqs as $faq) {
            $allFaqs->push($faq);
        }
    }

    $inputClass = 'w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-sm text-slate-800 dark:text-white placeholder:text-slate-400 outline-none transition focus:border-transparent focus:ring-2 focus:ring-[#002B6B] dark:focus:ring-blue-600';
    $labelClass = 'mb-1.5 block text-xs font-semibold text-slate-600 dark:text-slate-300';
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-0 my-4 sm:my-6 space-y-6 animate-fade-in">

    {{-- ===== HERO ===== --}}
    <section class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#002B6B] via-indigo-700 to-purple-600 p-6 sm:p-8 shadow-xl shadow-blue-950/10">
        <div class="pointer-events-none absolute -right-20 -top-24 h-80 w-80 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -bottom-16 left-10 h-64 w-64 rounded-full bg-blue-400/10 blur-2xl"></div>

        <div class="relative z-10">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                <span class="inline-flex items-center gap-1.5 rounded-full border border-white/15 bg-white/10 px-3.5 py-1.5 text-xs font-semibold text-blue-50 backdrop-blur-md">
                    <svg class="h-3.5 w-3.5 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pusat Bantuan
                </span>

                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3.5 py-1.5 text-xs font-semibold text-white backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full opacity-75 {{ $adminOnline ? 'bg-emerald-400' : 'bg-slate-400' }}"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full {{ $adminOnline ? 'bg-emerald-500' : 'bg-slate-500' }}"></span>
                    </span>
                    Admin {{ $adminOnline ? 'sedang online' : 'offline' }}
                </span>
            </div>

            <h1 class="font-display text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl">Ada yang bisa kami bantu?</h1>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-blue-100/90 sm:text-base">
                Temukan panduan seputar akun, kelas, tugas, absensi, hingga penilaian di LMS Cendekia.
            </p>

            {{-- Search --}}
            <div class="mt-6 max-w-2xl">
                <div class="group relative rounded-2xl shadow-xl shadow-black/10 transition focus-within:ring-4 focus-within:ring-white/25">
                    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400 transition group-focus-within:text-[#002B6B]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <input id="faqSearch" type="text" autocomplete="off" placeholder="Cari: login, upload tugas, reset password..."
                        class="h-14 w-full rounded-2xl bg-white pl-12 pr-12 text-sm font-medium text-slate-800 outline-none placeholder:text-slate-400">
                    <button type="button" id="clearSearch" aria-label="Hapus pencarian"
                        class="absolute right-3 top-1/2 hidden h-8 w-8 -translate-y-1/2 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-600">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <p id="searchMeta" class="ml-1 mt-3 hidden text-xs font-medium text-blue-100/80"></p>
            </div>
        </div>
    </section>

    {{-- ===== KONTEN: FAQ + SIDEBAR ===== --}}
    <div class="grid gap-6 lg:grid-cols-3 lg:items-start">

        {{-- FAQ LIST --}}
        <section class="lg:col-span-2">
            <div class="mb-4 flex items-end justify-between">
                <div>
                    <h2 class="font-display text-lg font-bold tracking-tight text-slate-900 dark:text-white sm:text-xl">Pertanyaan Umum</h2>
                    <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">{{ $allFaqs->count() }} topik bantuan tersedia</p>
                </div>
            </div>

            <div class="space-y-3">
                @forelse ($allFaqs as $faq)
                    <div class="faq-item rounded-2xl border border-slate-200/80 bg-white shadow-sm transition-colors duration-200 hover:border-blue-500/30 dark:border-slate-800 dark:bg-slate-900"
                         data-question="{{ Str::lower($faq['question']) }}" data-answer="{{ Str::lower($faq['answer']) }}">
                        <button type="button" class="faq-toggle flex w-full items-center gap-4 rounded-2xl px-5 py-4 text-left" aria-expanded="false">
                            <span class="flex-1 text-sm font-semibold leading-snug text-slate-800 dark:text-slate-100 sm:text-[15px]">{{ $faq['question'] }}</span>
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                <svg class="faq-chevron h-4 w-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                            </span>
                        </button>

                        <div class="faq-answer">
                            <div>
                                <div class="mx-5 whitespace-pre-line border-t border-slate-100 pb-5 pt-4 text-sm leading-relaxed text-slate-600 dark:border-slate-800 dark:text-slate-400">{{ $faq['answer'] }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-3xl border border-slate-100 bg-white py-16 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-slate-100 bg-slate-50 dark:border-slate-700 dark:bg-slate-800">
                            <svg class="h-7 w-7 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m20 20-3.2-3.2"/></svg>
                        </div>
                        <p class="mb-1 font-semibold text-slate-700 dark:text-slate-200">Belum ada FAQ</p>
                        <p class="mx-auto max-w-xs text-xs text-slate-400">FAQ akan segera ditambahkan. Untuk bantuan mendesak, silakan buat tiket support.</p>
                    </div>
                @endforelse

                {{-- Hasil pencarian kosong --}}
                <div id="noResults" class="hidden rounded-3xl border border-slate-100 bg-white py-14 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl border border-amber-100 bg-amber-50 dark:border-amber-500/20 dark:bg-amber-500/10">
                        <svg class="h-7 w-7 text-amber-600 dark:text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m20 20-3.2-3.2"/></svg>
                    </div>
                    <p class="mb-1 font-semibold text-slate-800 dark:text-slate-200">Solusi tidak ditemukan</p>
                    <p class="mx-auto mb-5 max-w-xs text-xs text-slate-400">Coba kata kunci lain, atau buat tiket support agar admin bisa membantu langsung.</p>
                    <div class="flex flex-wrap items-center justify-center gap-2">
                        <button type="button" id="resetSearch" class="rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2 text-xs font-bold text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Bersihkan pencarian</button>
                        <button type="button" data-open-ticket class="rounded-xl bg-[#002B6B] px-3.5 py-2 text-xs font-bold text-white transition hover:bg-blue-800 dark:bg-blue-600 dark:hover:bg-blue-700">Buat tiket</button>
                    </div>
                </div>
            </div>
        </section>

        {{-- SIDEBAR --}}
        <aside class="space-y-6 lg:sticky lg:top-6">
            {{-- Hubungi Kami --}}
            <div class="rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-[#002B6B]/10 text-[#002B6B] dark:bg-blue-500/10 dark:text-blue-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Belum menemukan jawaban?</h3>
                <p class="mt-1.5 text-xs leading-relaxed text-slate-500 dark:text-slate-400">Buat tiket untuk masalah yang lebih detail. Admin akan merespons dalam 1-2 jam kerja.</p>
                <button type="button" data-open-ticket
                    class="mt-4 w-full rounded-xl bg-[#002B6B] px-4 py-3 text-xs font-bold text-white shadow-md shadow-blue-700/15 transition hover:bg-blue-800 hover:shadow-lg dark:bg-blue-600 dark:hover:bg-blue-700">
                    Buat Tiket Support
                </button>
                <p class="mt-3 text-center text-[11px] text-slate-400">atau email: <span class="font-semibold text-slate-500 dark:text-slate-300">support@cendekia.ac.id</span></p>
            </div>

            {{-- Tips --}}
            <div class="rounded-2xl border border-blue-500/10 bg-gradient-to-br from-blue-500/[0.05] to-indigo-500/[0.04] p-5 dark:border-blue-500/20 dark:from-blue-500/[0.08] dark:to-transparent">
                <p class="mb-3 text-sm font-bold text-slate-900 dark:text-white">Tips Penggunaan</p>
                <ul class="space-y-2.5 text-xs font-medium text-slate-600 dark:text-slate-400">
                    @foreach ([
                        'Refresh halaman setelah mengubah data penting.',
                        'Gunakan browser terbaru (Chrome/Edge) untuk performa optimal.',
                        'Simpan tugas dalam format PDF sebelum mengunggah.',
                        'Aktifkan notifikasi email agar tidak tertinggal kelas.',
                    ] as $tip)
                        <li class="flex items-start gap-2.5">
                            <svg class="mt-0.5 h-4 w-4 shrink-0 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="leading-relaxed">{{ $tip }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>
    </div>
</div>

{{-- Modal diletakkan di luar wrapper .animate-fade-in supaya position:fixed
     menempel ke viewport (animasi transform di wrapper membuat 'fixed' ikut wrapper). --}}
{{-- ===== TICKET MODAL ===== --}}
<div id="ticketModal" class="fixed inset-0 z-50 hidden items-center justify-center px-4 py-6" role="dialog" aria-modal="true" aria-labelledby="ticketTitle">
    <div id="ticketOverlay" class="absolute inset-0 bg-slate-950/50 backdrop-blur-sm"></div>

    <div id="modalContainer" class="relative max-h-full w-full max-w-md scale-95 transform overflow-y-auto rounded-3xl border border-slate-100 bg-white p-6 opacity-0 shadow-2xl transition-all duration-300 dark:border-slate-800 dark:bg-slate-900 sm:p-7">
        <div class="mb-5 flex items-start justify-between gap-3">
            <div>
                <h3 id="ticketTitle" class="font-display text-lg font-bold text-slate-900 dark:text-white">Buat Tiket Bantuan</h3>
                <p class="mt-0.5 text-xs text-slate-500 dark:text-slate-400">Jelaskan kendala Anda, admin akan menindaklanjuti.</p>
            </div>
            <button type="button" id="closeTicketModal" aria-label="Tutup"
                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-200">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="ticketForm" class="space-y-4">
            <div>
                <label for="t_name" class="{{ $labelClass }}">Nama lengkap</label>
                <input id="t_name" type="text" name="name" required value="{{ auth()->user()?->name }}" placeholder="Nama lengkap Anda" class="{{ $inputClass }}">
            </div>
            <div>
                <label for="t_email" class="{{ $labelClass }}">Email institusi</label>
                <input id="t_email" type="email" name="email" required value="{{ auth()->user()?->email }}" placeholder="nama@kampus.ac.id" class="{{ $inputClass }}">
            </div>
            <div>
                <label for="t_subject" class="{{ $labelClass }}">Subjek</label>
                <input id="t_subject" type="text" name="subject" required placeholder="Inti kendala" class="{{ $inputClass }}">
            </div>
            <div>
                <label for="t_category" class="{{ $labelClass }}">Kategori</label>
                <div class="relative">
                    <select id="t_category" name="category" required class="{{ $inputClass }} appearance-none pr-10">
                        <option value="">Pilih kategori</option>
                        <option value="akun">Login & Otentikasi Akun</option>
                        <option value="kelas">Mata Kuliah & Silabus Kelas</option>
                        <option value="tugas">Pengunggahan Tugas</option>
                        <option value="absensi">Presensi & QR Code</option>
                        <option value="nilai">Transkrip Nilai & Gradebook</option>
                        <option value="teknis">Bug Sistem & Teknis</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    <svg class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                </div>
            </div>
            <div>
                <label for="t_message" class="{{ $labelClass }}">Deskripsi</label>
                <textarea id="t_message" name="message" required rows="4" placeholder="Jelaskan kendala secara detail..." class="{{ $inputClass }} resize-none"></textarea>
            </div>

            <div id="ticketStatus" class="hidden rounded-xl p-3 text-xs font-semibold" role="status"></div>

            <button type="submit" id="ticketSubmit"
                class="w-full rounded-xl bg-[#002B6B] py-3 text-sm font-bold text-white shadow-md shadow-blue-700/10 transition hover:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-blue-600 dark:hover:bg-blue-700">
                Kirim Tiket
            </button>
        </form>
    </div>
</div>

<script>
(function () {
    // ===== ACCORDION =====
    const items = Array.from(document.querySelectorAll('.faq-item'));

    items.forEach(item => {
        const btn = item.querySelector('.faq-toggle');
        btn.addEventListener('click', () => {
            const wasOpen = item.classList.contains('open');

            items.forEach(other => {
                other.classList.remove('open');
                other.querySelector('.faq-toggle').setAttribute('aria-expanded', 'false');
            });

            if (!wasOpen) {
                item.classList.add('open');
                btn.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // ===== SEARCH =====
    const searchInput = document.getElementById('faqSearch');
    const searchMeta  = document.getElementById('searchMeta');
    const noResults   = document.getElementById('noResults');
    const clearBtn    = document.getElementById('clearSearch');

    function applyFilter() {
        const raw = searchInput.value.trim();
        const q = raw.toLowerCase();
        let total = 0;

        items.forEach(item => {
            const match = !q || item.dataset.question.includes(q) || item.dataset.answer.includes(q);
            item.style.display = match ? '' : 'none';
            if (match) total++;
        });

        clearBtn.classList.toggle('hidden', !q);
        clearBtn.classList.toggle('flex', !!q);

        if (q) {
            searchMeta.textContent = total + ' hasil untuk "' + raw + '"';
            searchMeta.classList.remove('hidden');
        } else {
            searchMeta.classList.add('hidden');
        }

        noResults.classList.toggle('hidden', !(q && total === 0));
    }

    function resetSearch() {
        searchInput.value = '';
        applyFilter();
        searchInput.focus();
    }

    searchInput.addEventListener('input', applyFilter);
    clearBtn.addEventListener('click', resetSearch);
    document.getElementById('resetSearch')?.addEventListener('click', resetSearch);

    // ===== MODAL =====
    const modal     = document.getElementById('ticketModal');
    const container = document.getElementById('modalContainer');
    const overlay   = document.getElementById('ticketOverlay');
    const closeBtn  = document.getElementById('closeTicketModal');
    const form      = document.getElementById('ticketForm');
    const status    = document.getElementById('ticketStatus');
    const submitBtn = document.getElementById('ticketSubmit');
    let hideTimer = null;

    function show() {
        clearTimeout(hideTimer);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        requestAnimationFrame(() => {
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
            const first = form.querySelector('input:not([value=""]) ~ *') ? null : form.querySelector('[name="name"]');
            (form.querySelector('[name="subject"]') || first)?.focus();
        });
    }

    function hide() {
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        hideTimer = setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }, 200);
    }

    document.querySelectorAll('[data-open-ticket]').forEach(btn => btn.addEventListener('click', show));
    closeBtn.addEventListener('click', hide);
    overlay.addEventListener('click', hide);
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) hide();
    });

    // ===== SUBMIT TIKET =====
    const STATUS_STYLE = {
        info:    'text-blue-700 bg-blue-50 dark:bg-blue-500/10 dark:text-blue-400',
        success: 'text-emerald-700 bg-emerald-50 dark:bg-emerald-500/10 dark:text-emerald-400',
        error:   'text-red-700 bg-red-50 dark:bg-red-500/10 dark:text-red-400',
    };

    function setStatus(type, message) {
        status.className = 'rounded-xl p-3 text-xs font-semibold ' + STATUS_STYLE[type];
        status.textContent = message;
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const endpoint = "{{ Route::has('help-center.store-ticket') ? route('help-center.store-ticket') : '' }}";
        if (!endpoint) {
            setStatus('error', 'Form tiket sedang dinonaktifkan. Silakan hubungi support@cendekia.ac.id');
            return;
        }

        submitBtn.disabled = true;
        setStatus('info', 'Mengirim tiket Anda...');

        try {
            const res = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: new FormData(form),
            });
            const data = await res.json().catch(() => ({}));

            if (res.ok) {
                setStatus('success', data.message || 'Tiket Anda berhasil dikirim ke tim support.');
                form.querySelector('[name="subject"]').value = '';
                form.querySelector('[name="category"]').value = '';
                form.querySelector('[name="message"]').value = '';
                setTimeout(() => { hide(); status.className = 'hidden'; }, 2000);
            } else {
                const firstError = data.errors ? Object.values(data.errors)[0]?.[0] : null;
                setStatus('error', firstError || data.message || 'Gagal mengirim. Pastikan semua kolom terisi dengan benar.');
            }
        } catch (err) {
            setStatus('error', 'Terjadi gangguan jaringan. Silakan coba lagi beberapa saat.');
        } finally {
            submitBtn.disabled = false;
        }
    });
})();
</script>

<style>
    /* Accordion: animasi tinggi via grid-template-rows (tanpa hitung scrollHeight) */
    .faq-answer {
        display: grid;
        grid-template-rows: 0fr;
        transition: grid-template-rows 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .faq-answer > div { overflow: hidden; }
    .faq-item.open .faq-answer { grid-template-rows: 1fr; }
    .faq-item.open .faq-chevron { transform: rotate(180deg); }
    .faq-item.open { border-color: rgba(59, 130, 246, 0.35); }

    html { scroll-behavior: smooth; }
    :focus-visible { outline: 3px solid rgba(0, 43, 107, 0.3); outline-offset: 2px; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }

    @media (prefers-reduced-motion: reduce) {
        .faq-answer, .animate-fade-in, html { transition: none !important; animation: none !important; }
    }
</style>
@endsection