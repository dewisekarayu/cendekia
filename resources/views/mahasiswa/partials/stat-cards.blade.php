{{--
    Shared summary cards — used on Dashboard, Profil, and Setting pages
    so the header identity stays consistent everywhere.

    Expected variables (pass with sensible fallbacks from the controller):
    - $kelasAktif      (int)   jumlah kelas aktif
    - $rataRataNilai   (float) rata-rata nilai mahasiswa
    - $mkDinilai       (int)   jumlah mata kuliah yang sudah dinilai
    - $pengumumanCount (int)   jumlah pengumuman belum dibaca / total
--}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    <div class="group rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5 bg-[#002B6B]"></div>
        <p class="text-[28px] font-black text-[#002B6B] dark:text-blue-400 leading-none">{{ $kelasAktif ?? 0 }}</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 dark:text-slate-400 uppercase tracking-wide">Kelas Aktif</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-[#002B6B]/30 dark:bg-blue-500/30"></div>
    </div>
    <div class="group rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5 bg-emerald-500"></div>
        <p class="text-[28px] font-black text-emerald-600 dark:text-emerald-400 leading-none">{{ $rataRataNilai ?? 0 }}</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 dark:text-slate-400 uppercase tracking-wide">Rata-rata Nilai</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-emerald-400/40"></div>
    </div>
    <div class="group rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5 bg-violet-500"></div>
        <p class="text-[28px] font-black text-violet-600 dark:text-violet-400 leading-none">{{ $mkDinilai ?? 0 }}</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 dark:text-slate-400 uppercase tracking-wide">MK Dinilai</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-violet-400/40"></div>
    </div>
    <div class="group rounded-2xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 rounded-bl-full opacity-5 bg-amber-500"></div>
        <p class="text-[28px] font-black text-amber-500 leading-none">{{ $pengumumanCount ?? 0 }}</p>
        <p class="mt-1.5 text-[11px] font-semibold text-gray-400 dark:text-slate-400 uppercase tracking-wide">Pengumuman</p>
        <div class="mt-2 w-6 h-0.5 rounded-full bg-amber-400/40"></div>
    </div>
</div>