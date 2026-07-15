@extends('layouts.portal')
@section('title', 'FAQ & Bantuan')
@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 my-8 sm:my-14 antialiased">

    {{-- ============ HERO ============ --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#002B6B] via-[#00388a] to-[#0044a8] px-6 sm:px-10 lg:px-12 py-12 sm:py-16 lg:py-20 mb-10 sm:mb-12 shadow-2xl shadow-blue-900/10 border border-white/5">
        {{-- decorative glow, kept subtle and non-overlapping with text --}}
        <div class="absolute top-0 right-0 w-80 h-80 sm:w-96 sm:h-96 bg-gradient-to-bl from-white/10 to-transparent rounded-full -mr-24 -mt-24 blur-3xl pointer-events-none"></div>
        <div class="absolute -left-16 -bottom-16 w-64 h-64 sm:w-72 sm:h-72 bg-blue-400/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 text-center sm:text-left">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-white/10 text-blue-100 mb-4 backdrop-blur-sm border border-white/10">
                🚀 Pusat Informasi LMS Cendekia
            </span>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight mb-4 leading-tight">
                FAQ &amp; Pusat Bantuan
            </h1>
            <p class="text-sm sm:text-base text-blue-100/90 max-w-2xl mx-auto sm:mx-0 font-medium leading-relaxed">
                Punya kendala atau pertanyaan seputar penggunaan sistem? Temukan solusi instan dan panduan lengkap di bawah ini.
            </p>
        </div>
    </div>

    {{-- ============ FAQ LIST ============ --}}
    <div class="mb-14 sm:mb-16">
        <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-3 px-1">
            Pertanyaan yang Sering Diajukan
        </h3>

        @php
            $allFaqs = collect();
            foreach ($faqs as $categoryKey => $categoryFaqs) {
                foreach ($categoryFaqs as $faq) {
                    $faq['category'] = $categoryKey;
                    $allFaqs->push($faq);
                }
            }
        @endphp

        <div class="space-y-3 sm:space-y-4">
            @forelse ($allFaqs as $faq)
                <div
                    class="faq-item group rounded-2xl border border-slate-100 bg-white transition-all duration-300 hover:shadow-lg hover:shadow-slate-100 hover:border-blue-500/20"
                    data-question="{{ Str::lower($faq['question']) }}"
                    data-answer="{{ Str::lower($faq['answer']) }}"
                >
                    <button
                        type="button"
                        class="faq-toggle w-full flex items-center justify-between gap-4 text-left px-5 sm:px-6 py-4 sm:py-5 outline-none rounded-2xl"
                        aria-expanded="false"
                    >
                        <span class="text-sm sm:text-base font-bold text-slate-800 group-hover:text-[#002B6B] transition-colors duration-200 pr-2">
                            {{ $faq['question'] }}
                        </span>
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-slate-50 text-slate-400 group-hover:bg-[#002B6B]/10 group-hover:text-[#002B6B] transition-all duration-300">
                            <svg class="faq-chevron w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </span>
                    </button>

                    <div class="faq-answer overflow-hidden px-5 sm:px-6">
                        <div class="pb-5 sm:pb-6 text-sm text-slate-600 leading-relaxed border-t border-slate-50 pt-4">
                            {{ $faq['answer'] }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 sm:py-20 bg-white border border-slate-100 rounded-3xl shadow-sm">
                    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <p class="font-bold text-slate-700 mb-1">Belum Ada FAQ</p>
                    <p class="text-xs text-slate-400">Konten FAQ sedang diperbarui oleh admin sistem.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- ============ SUPPORT SECTION ============ --}}
    <div class="mb-6">
        <div class="mb-6 sm:mb-8">
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">💬 Masih Punya Pertanyaan?</h2>
            <p class="text-sm text-slate-500 mt-1">Tim support kami siap membantu kendala teknis Anda.</p>
        </div>

        <div class="grid sm:grid-cols-3 gap-5 sm:gap-6">
            {{-- Card 1: Ajukan Pertanyaan --}}
            <div class="group/card rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 hover:border-[#002B6B]/20 hover:shadow-2xl hover:shadow-slate-200/50 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-[#002B6B]/5 text-[#002B6B] mb-6 group-hover/card:scale-110 group-hover/card:bg-[#002B6B] group-hover/card:text-white transition-all duration-300 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-slate-900 text-lg mb-2">Ajukan Pertanyaan</h4>
                    <p class="text-sm text-slate-500 leading-relaxed mb-8">Kirim kendala akun atau sistem yang lebih spesifik langsung ke tim support. Direspons dalam 1–2 jam kerja.</p>
                </div>
                <button type="button" id="openHelpModal" class="w-full text-xs font-bold tracking-wider uppercase px-5 py-4 rounded-xl bg-[#002B6B] text-white hover:bg-blue-800 shadow-lg shadow-blue-900/10 transition-all duration-200">
                    Buka Formulir Bantuan
                </button>
            </div>

            {{-- Card 2: Email Support --}}
            <div class="group/card rounded-3xl border border-slate-100 bg-white p-6 sm:p-8 hover:border-amber-500/20 hover:shadow-2xl hover:shadow-slate-200/50 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-600 mb-6 group-hover/card:scale-110 group-hover/card:bg-amber-500 group-hover/card:text-white transition-all duration-300 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-slate-900 text-lg mb-2">Email Layanan Pelanggan</h4>
                    <p class="text-sm text-slate-500 leading-relaxed mb-8">Kirim pesan formal melalui surel institusi untuk keperluan dokumentasi akademik resmi Anda.</p>
                </div>
                <a href="mailto:support@cendekia.ac.id" class="w-full inline-flex items-center justify-center text-xs font-bold tracking-wider uppercase px-5 py-4 rounded-xl border-2 border-slate-100 text-slate-700 hover:border-amber-500 hover:text-amber-600 hover:bg-amber-50/40 transition-all duration-200">
                    Kirim Email Sekarang
                </a>
            </div>

            {{-- Card 3: Live Chat (Premium UI) --}}
            <div class="group/card rounded-3xl border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-emerald-50/50 p-6 sm:p-8 hover:border-emerald-400/60 hover:shadow-2xl hover:shadow-emerald-200/50 hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between relative overflow-hidden">
                {{-- Decorative gradient glow --}}
                <div class="absolute -top-16 -right-16 w-40 h-40 bg-gradient-to-bl from-emerald-300/20 to-transparent rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-500 text-white shadow-lg shadow-emerald-400/30 group-hover/card:scale-110 transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold text-emerald-700 bg-white/80 backdrop-blur-sm border border-emerald-200/50">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-pulse absolute inline-flex h-full w-full rounded-full bg-emerald-500"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            Online
                        </span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-lg mb-2">Chat Live dengan Support</h4>
                    <p class="text-sm text-slate-600 leading-relaxed mb-8">Obrolan real-time dengan tim support untuk pertanyaan mendesak. Respons instan pada jam kerja 08:00 - 17:00.</p>
                </div>
                <button type="button" onclick="alert('💬 Fitur Live Chat sedang dipersiapkan. Gunakan opsi lain untuk menghubungi kami.')" class="group/btn relative w-full text-xs font-bold tracking-wider uppercase px-5 py-4 rounded-xl bg-gradient-to-r from-emerald-500 to-emerald-600 text-white hover:from-emerald-600 hover:to-emerald-700 shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 transition-all duration-200 flex items-center justify-center gap-2">
                    Mulai Chat
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 group-hover/btn:translate-x-1 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

</div>

{{-- ============ HELP REQUEST MODAL ============ --}}
<div id="helpModal" class="hidden fixed inset-0 z-50 items-center justify-center p-4">
    <div id="helpOverlay" class="absolute inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity duration-300"></div>

    <div
        id="modalContainer"
        class="relative bg-white rounded-[2rem] w-full max-w-lg max-h-[90vh] overflow-y-auto p-6 sm:p-8 shadow-2xl border border-slate-100 transform scale-95 opacity-0 transition-all duration-300"
        role="dialog"
        aria-modal="true"
        aria-labelledby="helpModalTitle"
    >
        <div class="flex items-start justify-between mb-6 gap-4">
            <div>
                <h3 id="helpModalTitle" class="font-black text-xl text-slate-900">Ajukan Pertanyaan ke Support</h3>
                <p class="text-xs text-slate-400 mt-0.5">Lengkapi form di bawah dan tim kami akan menghubungi Anda kembali.</p>
            </div>
            <button type="button" id="closeHelpModal" aria-label="Tutup" class="w-9 h-9 shrink-0 rounded-xl bg-slate-50 hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="helpForm" class="space-y-4">
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="helpName" class="block text-xs font-bold text-slate-700 uppercase mb-2 px-1">Nama Lengkap</label>
                    <input id="helpName" type="text" name="name" required placeholder="John Doe" class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 placeholder:text-slate-400 text-sm focus:ring-2 focus:ring-[#002B6B]/20 focus:border-[#002B6B] focus:bg-white outline-none transition-all">
                </div>
                <div>
                    <label for="helpEmail" class="block text-xs font-bold text-slate-700 uppercase mb-2 px-1">Email Institusi</label>
                    <input id="helpEmail" type="email" name="email" required placeholder="user@cendekia.ac.id" class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 placeholder:text-slate-400 text-sm focus:ring-2 focus:ring-[#002B6B]/20 focus:border-[#002B6B] focus:bg-white outline-none transition-all">
                </div>
            </div>

            <div>
                <label for="helpSubject" class="block text-xs font-bold text-slate-700 uppercase mb-2 px-1">Subjek Kendala</label>
                <input id="helpSubject" type="text" name="subject" required placeholder="Contoh: Tidak bisa submit tugas" class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 placeholder:text-slate-400 text-sm focus:ring-2 focus:ring-[#002B6B]/20 focus:border-[#002B6B] focus:bg-white outline-none transition-all">
            </div>

            <div>
                <label for="helpCategory" class="block text-xs font-bold text-slate-700 uppercase mb-2 px-1">Kategori Masalah</label>
                <div class="relative">
                    <select id="helpCategory" name="category" required class="w-full appearance-none px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 text-sm focus:ring-2 focus:ring-[#002B6B]/20 focus:border-[#002B6B] focus:bg-white outline-none transition-all">
                        <option value="">Pilih kategori...</option>
                        <option value="akun">Login & Akun</option>
                        <option value="kelas">Kelas</option>
                        <option value="tugas">Tugas</option>
                        <option value="absensi">Absensi</option>
                        <option value="nilai">Nilai</option>
                        <option value="teknis">Teknis</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/></svg>
                    </div>
                </div>
            </div>

            <div>
                <label for="helpMessage" class="block text-xs font-bold text-slate-700 uppercase mb-2 px-1">Deskripsi Detail Masalah</label>
                <textarea id="helpMessage" name="message" required rows="4" placeholder="Ceritakan secara detail kronologi atau kendala sistem yang dihadapi..." class="w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50/50 text-slate-800 placeholder:text-slate-400 text-sm focus:ring-2 focus:ring-[#002B6B]/20 focus:border-[#002B6B] focus:bg-white outline-none resize-none transition-all"></textarea>
            </div>

            <div id="helpStatus" class="text-xs font-semibold p-4 rounded-xl hidden" role="status"></div>

            <button type="submit" class="w-full bg-[#002B6B] hover:bg-blue-800 text-white text-sm font-bold rounded-xl py-4 shadow-xl shadow-blue-950/10 transition-colors duration-200 mt-2">
                Kirim Pertanyaan
            </button>
        </form>
    </div>
</div>

<script>
(function () {
    // ---------- Accordion ----------
    const items = Array.from(document.querySelectorAll('.faq-item'));

    function closeAll() {
        items.forEach(item => {
            item.classList.remove('open');
            item.querySelector('.faq-answer').style.maxHeight = null;
            item.querySelector('.faq-chevron').style.transform = null;
            item.querySelector('.faq-toggle').setAttribute('aria-expanded', 'false');
        });
    }

    items.forEach(item => {
        const btn = item.querySelector('.faq-toggle');
        const answer = item.querySelector('.faq-answer');
        const chevron = item.querySelector('.faq-chevron');

        btn.addEventListener('click', () => {
            const isOpen = item.classList.contains('open');
            closeAll();
            if (!isOpen) {
                item.classList.add('open');
                answer.style.maxHeight = answer.scrollHeight + 'px';
                chevron.style.transform = 'rotate(180deg)';
                btn.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // keep open item's height correct if content reflows (e.g. orientation change)
    window.addEventListener('resize', () => {
        const openItem = items.find(i => i.classList.contains('open'));
        if (openItem) {
            openItem.querySelector('.faq-answer').style.maxHeight =
                openItem.querySelector('.faq-answer > div').scrollHeight + 'px';
        }
    });

    // ---------- Modal ----------
    const modal = document.getElementById('helpModal');
    const openBtn = document.getElementById('openHelpModal');
    const closeBtn = document.getElementById('closeHelpModal');
    const overlay = document.getElementById('helpOverlay');
    const container = document.getElementById('modalContainer');

    function showModal() {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
        requestAnimationFrame(() => {
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
        });
    }

    function hideModal() {
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        document.body.classList.remove('overflow-hidden');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200);
    }

    openBtn.addEventListener('click', showModal);
    closeBtn.addEventListener('click', hideModal);
    overlay.addEventListener('click', hideModal);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) hideModal();
    });

    // ---------- Form submit (AJAX) ----------
    const form = document.getElementById('helpForm');
    const status = document.getElementById('helpStatus');

    function setStatus(text, tone) {
        const tones = {
            info: 'text-xs font-semibold p-4 rounded-xl text-blue-700 bg-blue-50',
            success: 'text-xs font-semibold p-4 rounded-xl text-emerald-700 bg-emerald-50',
            error: 'text-xs font-semibold p-4 rounded-xl text-red-700 bg-red-50',
        };
        status.className = tones[tone] || tones.info;
        status.textContent = text;
        status.classList.remove('hidden');
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        setStatus('⏳ Sedang mengirimkan pertanyaan Anda...', 'info');

        try {
            const res = await fetch("{{ route('help-center.store-ticket') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: new FormData(form),
            });
            const data = await res.json();

            if (res.ok) {
                setStatus('✓ Sukses! Pertanyaan Anda berhasil terkirim. Silakan cek email secara berkala.', 'success');
                form.reset();
                setTimeout(hideModal, 2200);
            } else {
                setStatus('❌ Validasi gagal. Pastikan semua data sudah diisi dengan benar.', 'error');
            }
        } catch (err) {
            setStatus('❌ Terjadi kesalahan jaringan. Silakan coba lagi.', 'error');
        }
    });
})();
</script>

<style>
    .faq-answer {
        max-height: 0;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    html { scroll-behavior: smooth; }

    :focus-visible {
        outline: 2px solid #002B6B;
        outline-offset: 2px;
    }

    @media (prefers-reduced-motion: reduce) {
        .faq-answer, .faq-chevron, #modalContainer, html {
            transition: none !important;
        }
    }
</style>

@endsection