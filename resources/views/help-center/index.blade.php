{{--
    Pusat Bantuan & FAQ — LMS Cendekia (NO CATEGORIES)
    Versi Premium & Modern Tanpa Kategori Sidebar - Fixed Background
--}}
@extends('layouts.portal')
@section('title', 'Pusat Bantuan')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-0 my-6 sm:my-10 animate-fade-in">

    {{-- ===== HERO SECTION ===== --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#002B6B] via-indigo-700 to-purple-600 px-5 sm:px-7 py-12 sm:py-16 mb-10 shadow-xl shadow-blue-950/10 border border-slate-100 dark:border-slate-800">
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-white/10 to-transparent rounded-full -mr-20 -mt-20 blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-10 left-10 w-72 h-72 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>
        
        <div class="relative z-10">
            <div class="flex items-center justify-between gap-4 mb-6">
                <div>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/10 border border-white/10 px-3.5 py-1.5 text-xs font-medium text-blue-100 backdrop-blur-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pusat Bantuan
                    </span>
                </div>
                <div class="text-right hidden sm:block bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl px-3 py-1.5">
                    <p class="text-[10px] uppercase tracking-wider font-semibold text-blue-200 mb-0.5">Status Admin</p>
                    <div class="flex items-center gap-2 justify-end">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $adminOnline ? 'bg-emerald-400' : 'bg-slate-400' }} opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 {{ $adminOnline ? 'bg-emerald-500' : 'bg-slate-500' }}"></span>
                        </span>
                        <span class="text-xs font-bold text-white">{{ $adminOnline ? 'Sedang Online' : 'Offline' }}</span>
                    </div>
                </div>
            </div>

            <h1 class="text-3xl sm:text-4xl font-display font-extrabold text-white mb-4 tracking-tight leading-tight">
                Ada yang bisa kami bantu?
            </h1>
            <p class="text-sm sm:text-base text-blue-100/90 max-w-2xl leading-relaxed mb-8">
                Temukan solusi instan dan panduan lengkap seputar akun, kelas, tugas, absensi, hingga penilaian LMS Cendekia.
            </p>

            {{-- Search bar --}}
            <div class="max-w-2xl">
                <div class="relative group shadow-xl shadow-black/10 rounded-2xl transition-all duration-300 focus-within:ring-4 focus-within:ring-white/20">
                    <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none group-focus-within:text-[#002B6B] transition-colors duration-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    <input 
                        id="faqSearch" 
                        type="text" 
                        autocomplete="off" 
                        placeholder="Cari solusi: login, upload tugas, reset password..."
                        class="w-full h-14 pl-12 pr-4 rounded-2xl bg-white text-slate-800 placeholder:text-slate-400 outline-none text-sm font-medium transition-all duration-300 shadow-sm"
                    >
                </div>
                <p id="searchMeta" class="mt-3 ml-2 text-xs font-medium text-blue-200/70 hidden"></p>
            </div>
        </div>
    </div>

    {{-- ===== FAQ LIST ===== --}}
    <div class="space-y-4 mb-12">
        @php
            $allFaqs = collect();
            foreach ($faqs as $categoryKey => $categoryFaqs) {
                foreach ($categoryFaqs as $faq) {
                    $faq['category'] = $categoryKey;
                    $allFaqs->push($faq);
                }
            }
        @endphp

        @forelse ($allFaqs as $faq)
            <div class="faq-item group transition-all duration-300" data-question="{{ Str::lower($faq['question']) }}" data-answer="{{ Str::lower($faq['answer']) }}">
                <button type="button" class="faq-toggle w-full flex items-center gap-4 text-left px-6 py-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 hover:border-blue-500/30 dark:hover:border-blue-500/30 hover:bg-slate-50/60 dark:hover:bg-slate-800/40 hover:-translate-y-0.5 transition-all duration-300 shadow-sm hover:shadow-md">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 group-hover:bg-[#002B6B] group-hover:text-white dark:group-hover:bg-blue-600 transition-all duration-300 shrink-0">
                        <svg class="w-3.5 h-3.5 transition-transform duration-300 transform" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <span class="flex-1 text-sm sm:text-base font-semibold text-slate-800 dark:text-slate-200 leading-snug tracking-tight group-hover:text-[#002B6B] dark:group-hover:text-blue-400 transition-colors duration-200">{{ $faq['question'] }}</span>
                    <svg class="faq-chevron w-5 h-5 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 shrink-0 transition-transform duration-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                    </svg>
                </button>
                
                <div class="faq-answer overflow-hidden px-6 bg-slate-50/30 dark:bg-slate-900/20 rounded-b-2xl border-x border-b border-transparent transition-all duration-300">
                    <div class="pb-5 pt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal border-t border-slate-100 dark:border-slate-800/50 mt-1 pt-4">
                        {{ $faq['answer'] }}
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-16 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/60 rounded-3xl shadow-sm">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-50 dark:bg-slate-800/50 flex items-center justify-center mb-4 border border-slate-100 dark:border-slate-800">
                    <svg class="w-8 h-8 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/><path d="m20 20-3.2-3.2"/>
                    </svg>
                </div>
                <p class="font-semibold text-slate-700 dark:text-slate-200 mb-1">Tidak ada FAQ tersedia</p>
                <p class="text-xs text-slate-400 dark:text-slate-500 max-w-xs mx-auto">FAQ akan segera ditambahkan oleh tim kurikulum. Hubungi support untuk bantuan mendesak.</p>
            </div>
        @endforelse

        {{-- No search results --}}
        <div id="noResults" class="hidden text-center py-16 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/60 rounded-3xl shadow-sm">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center mb-4 border border-amber-100 dark:border-amber-500/20">
                <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><path d="m20 20-3.2-3.2"/>
                </svg>
            </div>
            <p class="font-semibold text-slate-800 dark:text-slate-200 mb-1">Solusi tidak ditemukan</p>
            <p class="text-xs text-slate-400 dark:text-slate-500 mb-5 max-w-xs mx-auto">Coba cari dengan kata kunci lain atau gunakan saluran pintas support di bawah ini.</p>
            <button onclick="document.getElementById('faqSearch').value = ''; document.getElementById('faqSearch').focus(); document.getElementById('faqSearch').dispatchEvent(new Event('input'));" class="inline-flex items-center gap-1.5 text-xs font-bold text-[#002B6B] dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 bg-slate-50 dark:bg-slate-800 px-3 py-2 rounded-xl border border-slate-200/60 dark:border-slate-700 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Bersihkan Pencarian
            </button>
        </div>
    </div>

    {{-- ===== TIPS BOX ===== --}}
    <div class="rounded-3xl border border-blue-500/10 dark:border-blue-500/20 bg-gradient-to-r from-blue-500/[0.04] to-indigo-500/[0.04] dark:from-blue-500/[0.07] dark:to-transparent p-6 sm:p-8 mb-12">
        <div class="flex flex-col sm:flex-row gap-5">
            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#002B6B] to-blue-700 text-white shadow-lg shadow-blue-700/20">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5h.01"/>
                </svg>
            </div>
            <div>
                <p class="font-display font-bold text-slate-900 dark:text-white text-base mb-3 flex items-center gap-2">Tips Penggunaan LMS Cendekia</p>
                <ul class="grid sm:grid-cols-2 gap-x-6 gap-y-3 text-xs sm:text-sm text-slate-600 dark:text-slate-400 font-medium">
                    <li class="flex items-start gap-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mt-2 shrink-0"></span>
                        <span>Selalu refresh halaman setelah mengubah data penting.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mt-2 shrink-0"></span>
                        <span>Gunakan browser terbaru (Chrome/Edge) untuk performa optimal.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mt-2 shrink-0"></span>
                        <span>Simpan tugas dalam format PDF sebelum mengunggah.</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mt-2 shrink-0"></span>
                        <span>Aktifkan notifikasi email agar tidak tertinggal kelas.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ===== SUPPORT SECTION ===== --}}
    <div class="mb-6">
        <div class="mb-6 text-center sm:text-left">
            <h2 class="text-xl sm:text-2xl font-display font-bold text-slate-900 dark:text-white tracking-tight mb-1">💬 Hubungi Kami</h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Pilih cara yang paling sesuai untuk menghubungi tim support</p>
        </div>

        <div class="grid sm:grid-cols-2 gap-6">
            {{-- Card 1: Buat Tiket --}}
            <div class="group/card rounded-2xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 hover:border-[#002B6B]/30 dark:hover:border-blue-500/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#002B6B]/10 text-[#002B6B] dark:bg-blue-500/10 dark:text-blue-400 mb-5 group-hover/card:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-slate-900 dark:text-white text-base mb-2">Buat Tiket Support</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">Buat antrean tiket untuk masalah detail. Admin akan merespons dalam 1-2 jam kerja.</p>
                </div>
                <button type="button" id="openTicketModal" class="w-full text-xs font-bold px-4 py-3 rounded-xl bg-[#002B6B] dark:bg-blue-600 text-white hover:bg-blue-800 dark:hover:bg-blue-700 shadow-md shadow-blue-700/15 transition-all duration-200 hover:shadow-lg">
                    Buat Tiket
                </button>
            </div>

            {{-- Card 2: Email Support --}}
            <div class="group/card rounded-2xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 hover:border-amber-500/30 dark:hover:border-amber-500/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">
                <div>
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400 mb-5 group-hover/card:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h4 class="font-bold text-slate-900 dark:text-white text-base mb-2">Email Support</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">Kirim email ke support kami. Ideal untuk komunikasi formal dan dokumentasi.</p>
                </div>
                <a href="mailto:support@cendekia.ac.id" class="w-full inline-flex items-center justify-center text-xs font-bold px-4 py-3 rounded-xl border border-amber-200 dark:border-amber-500/30 text-amber-700 dark:text-amber-400 hover:bg-amber-50 dark:hover:bg-amber-500/10 transition-all duration-200">
                    ✉️ Kirim Email
                </a>
            </div>
        </div>
    </div>

</div>
{{-- Modal diletakkan di luar wrapper .animate-fade-in supaya position:fixed-nya
     nempel ke viewport, bukan ke wrapper (animasi transform di wrapper bikin
     'fixed' di dalamnya jadi ngikut wrapper, bukan layar). --}}
{{-- ===== TICKET MODAL ===== --}}
<div id="ticketModal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 transition-all duration-300">
    <div id="ticketOverlay" class="absolute inset-0 bg-slate-950/40 backdrop-blur-sm transition-opacity duration-300"></div>
    <div class="relative bg-white dark:bg-slate-900 rounded-3xl w-full max-w-md p-6 sm:p-7 shadow-2xl border border-slate-100 dark:border-slate-800 transform scale-95 opacity-0 transition-all duration-300" id="modalContainer">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-display font-bold text-lg text-slate-900 dark:text-white flex items-center gap-2">
                Buat Tiket Bantuan
            </h3>
            <button type="button" id="closeTicketModal" class="w-8 h-8 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="ticketForm" class="space-y-4">
            <input type="text" name="name" required placeholder="Nama lengkap Anda" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-transparent text-slate-800 dark:text-white placeholder:text-slate-400 text-sm focus:ring-2 focus:ring-[#002B6B] dark:focus:ring-blue-600 focus:border-transparent outline-none transition-all">
            <input type="email" name="email" required placeholder="Alamat email institusi" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-transparent text-slate-800 dark:text-white placeholder:text-slate-400 text-sm focus:ring-2 focus:ring-[#002B6B] dark:focus:ring-blue-600 focus:border-transparent outline-none transition-all">
            <input type="text" name="subject" required placeholder="Subjek / Inti kendala" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-transparent text-slate-800 dark:text-white placeholder:text-slate-400 text-sm focus:ring-2 focus:ring-[#002B6B] dark:focus:ring-blue-600 focus:border-transparent outline-none transition-all">
            <div class="relative">
                <select name="category" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-300 text-sm focus:ring-2 focus:ring-[#002B6B] dark:focus:ring-blue-600 focus:border-transparent outline-none appearance-none transition-all">
                    <option value="" class="text-slate-400">Pilih rumpun kategori</option>
                    <option value="akun">Login & Otentikasi Akun</option>
                    <option value="kelas">Mata Kuliah & Silabus Kelas</option>
                    <option value="tugas">Pengunggahan Tugas</option>
                    <option value="absensi">Presensi & QR Code</option>
                    <option value="nilai">Transkrip Nilai & Gradebook</option>
                    <option value="teknis">Bug Sistem & Teknis</option>
                    <option value="lainnya">Lainnya</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                </div>
            </div>
            <textarea name="message" required rows="4" placeholder="Deskripsikan secara detail kendala yang dialami..." class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-transparent text-slate-800 dark:text-white placeholder:text-slate-400 text-sm focus:ring-2 focus:ring-[#002B6B] dark:focus:ring-blue-600 focus:border-transparent outline-none resize-none transition-all"></textarea>
            
            <div id="ticketStatus" class="text-xs font-semibold p-3 rounded-xl hidden transition-all duration-200"></div>
            
            <button type="submit" class="w-full bg-[#002B6B] dark:bg-blue-600 hover:bg-blue-800 dark:hover:bg-blue-700 text-white text-sm font-bold rounded-xl py-3 shadow-md shadow-blue-700/10 transition-colors">Kirim Tiket Resmi</button>
        </form>
    </div>
</div>

<script>
(function () {
    // ===== ACCORDION FUNCTIONALITY =====
    const items = Array.from(document.querySelectorAll('.faq-item'));
    items.forEach(item => {
        const btn = item.querySelector('.faq-toggle');
        const answer = item.querySelector('.faq-answer');
        const iconPath = btn.querySelector('svg:first-child path');
        
        btn.addEventListener('click', () => {
            const isOpen = item.classList.contains('open');
            
            items.forEach(other => {
                other.classList.remove('open');
                other.querySelector('.faq-answer').style.maxHeight = null;
                other.querySelector('.faq-answer').style.borderColor = 'transparent';
                other.querySelector('.faq-toggle').classList.remove('bg-slate-50', 'dark:bg-slate-800/60', 'border-blue-500/20');
                other.querySelector('svg:first-child path').setAttribute('d', 'M12 4v16m8-8H4');
                other.querySelector('.faq-chevron').style.transform = null;
            });
            
            if (!isOpen) {
                item.classList.add('open');
                answer.style.maxHeight = answer.scrollHeight + 30 + 'px';
                answer.style.borderColor = 'rgba(226, 232, 240, 0.4)'; 
                btn.classList.add('bg-slate-50', 'dark:bg-slate-800/60', 'border-blue-500/20');
                iconPath.setAttribute('d', 'M20 12H4');
                item.querySelector('.faq-chevron').style.transform = 'rotate(180deg)';
            }
        });
    });

    // ===== DIGIT FILTER SEARCH =====
    const searchInput = document.getElementById('faqSearch');
    const searchMeta = document.getElementById('searchMeta');
    const faqItems = Array.from(document.querySelectorAll('.faq-item'));
    const noResults = document.getElementById('noResults');

    searchInput.addEventListener('input', () => {
        const q = searchInput.value.trim().toLowerCase();
        let totalMatches = 0;

        faqItems.forEach(item => {
            const match = !q || item.dataset.question.includes(q) || item.dataset.answer.includes(q);
            item.style.display = match ? '' : 'none';
            if (match) totalMatches++;
        });

        if (q) {
            searchMeta.textContent = '✨ ' + totalMatches + ' solusi relevan ditemukan untuk "' + searchInput.value.trim() + '"';
            searchMeta.classList.remove('hidden');
        } else {
            searchMeta.classList.add('hidden');
        }
        noResults.classList.toggle('hidden', totalMatches > 0 || !q);
    });

    // ===== MODAL SMARTER ANIMATION =====
    const modal = document.getElementById('ticketModal');
    const container = document.getElementById('modalContainer');
    const openBtn = document.getElementById('openTicketModal');
    const closeBtn = document.getElementById('closeTicketModal');
    const overlay = document.getElementById('ticketOverlay');
    
    const show = () => { 
        modal.classList.remove('hidden'); 
        modal.classList.add('flex'); 
        setTimeout(() => {
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
        }, 20);
    };
    
    const hide = () => { 
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden'); 
            modal.classList.remove('flex'); 
        }, 200);
    };
    
    openBtn.addEventListener('click', show);
    closeBtn.addEventListener('click', hide);
    overlay.addEventListener('click', hide);

    // ===== FORM ASYNC SUBMIT =====
    const form = document.getElementById('ticketForm');
    const status = document.getElementById('ticketStatus');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        status.className = "text-xs font-semibold p-3 rounded-xl text-blue-700 bg-blue-50 dark:bg-blue-500/10 dark:text-blue-400";
        status.textContent = '⏳ Sedang mengirimkan berkas tiket Anda...';

        const endpoint = "{{ Route::has('help-center.store-ticket') ? route('help-center.store-ticket') : '' }}";
        if (!endpoint) {
            status.className = "text-xs font-semibold p-3 rounded-xl text-red-700 bg-red-50 dark:bg-red-500/10 dark:text-red-400";
            status.textContent = '⚠️ Fitur form dinonaktifkan sementara. Silakan hubungi email resmi: support@cendekia.ac.id';
            return;
        }

        try {
            const res = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: new FormData(form),
            });
            const data = await res.json();
            if (res.ok) {
                status.className = "text-xs font-semibold p-3 rounded-xl text-emerald-700 bg-emerald-50 dark:bg-emerald-500/10 dark:text-emerald-400";
                status.textContent = data.message || 'Sukses! Tiket Anda telah masuk sistem support Cendekia. ✓';
                form.reset();
                setTimeout(hide, 2000);
            } else {
                status.className = "text-xs font-semibold p-3 rounded-xl text-red-700 bg-red-50 dark:bg-red-500/10 dark:text-red-400";
                status.textContent = '❌ Validasi gagal. Pastikan seluruh input diisi dengan benar.';
            }
        } catch (err) {
            status.className = "text-xs font-semibold p-3 rounded-xl text-red-700 bg-red-50 dark:bg-red-500/10 dark:text-red-400";
            status.textContent = '❌ Terjadi gangguan jaringan internal. Silakan coba beberapa saat lagi.';
        }
    });
})();
</script>

<style>
    /* Accordion animations */
    .faq-answer {
        max-height: 0;
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease, border-color 0.2s ease;
    }
    html {
        scroll-behavior: smooth;
    }
    :focus-visible {
        outline: 3px solid rgba(0, 43, 107, 0.3);
        outline-offset: 2px;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
    @media (prefers-reduced-motion: reduce) {
        .faq-answer, .animate-fade-in, html {
            transition: none !important;
            animation: none !important;
        }
    }
</style>
@endsection