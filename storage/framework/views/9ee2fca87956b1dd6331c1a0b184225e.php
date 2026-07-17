<?php $__env->startSection('title', 'My Courses'); ?>

<?php $__env->startSection('activeMenu', 'My Courses'); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="mb-6 rounded-2xl bg-[#002B6B] px-6 py-5 sm:px-8 sm:py-6 relative overflow-hidden shadow-lg shadow-blue-950/10">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-blue-200/70">Semester Aktif</p>
                <h1 class="mt-1 text-xl sm:text-2xl font-extrabold text-white leading-tight">My Courses</h1>
                <p class="mt-1 text-sm text-blue-100/70"><?php echo e($kelasList->count()); ?> kelas terdaftar semester ini</p>
            </div>
            <a href="<?php echo e(route('mahasiswa.jelajahi-kelas')); ?>"
               class="inline-flex items-center gap-2 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 px-4 py-2 text-sm font-semibold text-white transition shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Jelajahi Kelas
            </a>
        </div>
        
        <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full bg-white/5 pointer-events-none"></div>
        <div class="absolute right-16 -bottom-10 w-28 h-28 rounded-full bg-white/5 pointer-events-none"></div>
    </div>

    
    <div class="mb-6 flex items-center gap-3">
        <div class="relative flex-1 max-w-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input id="kelasSearch" type="text" placeholder="Cari mata kuliah..." autocomplete="off"
                   class="w-full rounded-xl border border-gray-200 bg-white py-2.5 pl-9 pr-4 text-sm text-gray-700 shadow-sm focus:border-[#002B6B] focus:outline-none focus:ring-2 focus:ring-[#002B6B]/10">
        </div>
        <span id="kelasCount" class="shrink-0 text-xs text-gray-400 font-medium"><?php echo e($kelasList->count()); ?> kelas</span>
    </div>

    <?php if($kelasList->isEmpty()): ?>
        <div class="flex min-h-[320px] flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 bg-white p-10 text-center shadow-sm">
            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 border border-gray-100 text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <h3 class="font-bold text-gray-700">Belum Ada Kelas Terdaftar</h3>
            <p class="mt-1 max-w-xs text-xs text-gray-400 leading-relaxed">Kamu belum terdaftar di kelas manapun. Jelajahi kelas yang tersedia dan bergabung sekarang.</p>
            <a href="<?php echo e(route('mahasiswa.jelajahi-kelas')); ?>"
               class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[#002B6B] px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-800 transition">
                Jelajahi Kelas Sekarang
            </a>
        </div>
    <?php else: ?>
        <?php
            $colors = [
                ['bg' => 'bg-blue-500',   'light' => 'bg-blue-50',   'text' => 'text-blue-700',   'bar' => 'bg-blue-500'],
                ['bg' => 'bg-violet-500', 'light' => 'bg-violet-50', 'text' => 'text-violet-700', 'bar' => 'bg-violet-500'],
                ['bg' => 'bg-emerald-500','light' => 'bg-emerald-50','text' => 'text-emerald-700','bar' => 'bg-emerald-500'],
                ['bg' => 'bg-amber-500',  'light' => 'bg-amber-50',  'text' => 'text-amber-700',  'bar' => 'bg-amber-500'],
                ['bg' => 'bg-rose-500',   'light' => 'bg-rose-50',   'text' => 'text-rose-700',   'bar' => 'bg-rose-500'],
                ['bg' => 'bg-cyan-500',   'light' => 'bg-cyan-50',   'text' => 'text-cyan-700',   'bar' => 'bg-cyan-500'],
            ];
        ?>

        <div id="kelasGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $c = $colors[$i % count($colors)]; ?>
                <a href="<?php echo e(route('mahasiswa.kelas-detail', $kelas->id)); ?>"
                   data-search="<?php echo e(strtolower($kelas->mataKuliah?->nama_mk ?? '')); ?> <?php echo e(strtolower($kelas->mataKuliah?->kode_mk ?? '')); ?> <?php echo e(strtolower($kelas->dosen?->name ?? '')); ?>"
                   class="kelas-card group flex flex-col rounded-2xl border border-slate-200/80 bg-white shadow-sm hover:shadow-lg hover:shadow-blue-950/8 hover:-translate-y-0.5 transition-all duration-200 overflow-hidden">

                    
                    <div class="h-1.5 w-full <?php echo e($c['bg']); ?>"></div>

                    <div class="flex flex-col flex-1 p-4">
                        
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-block rounded-md <?php echo e($c['light']); ?> <?php echo e($c['text']); ?> px-2 py-0.5 text-[10px] font-bold uppercase tracking-wide">
                                <?php echo e($kelas->mataKuliah?->kode_mk ?? 'MK'); ?>

                            </span>
                            <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-600">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 inline-block"></span>
                                Aktif
                            </span>
                        </div>

                        
                        <h3 class="text-sm font-bold text-slate-800 group-hover:text-[#002B6B] transition leading-snug line-clamp-2 min-h-[40px]">
                            <?php echo e($kelas->mataKuliah?->nama_mk ?? '-'); ?>

                        </h3>

                        
                        <div class="mt-2.5 space-y-1.5 text-[11px] text-gray-500">
                            <div class="flex items-center gap-1.5 truncate">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="truncate"><?php echo e($kelas->dosen?->name ?? '-'); ?></span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span><?php echo e($kelas->hari); ?>, <?php echo e(substr($kelas->jam_mulai, 0, 5)); ?></span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span><?php echo e($kelas->ruangan ?? '-'); ?></span>
                                <span class="ml-auto font-semibold text-slate-600"><?php echo e($kelas->mataKuliah?->sks ?? 0); ?> SKS</span>
                            </div>
                        </div>

                        
                        <div class="flex-1"></div>

                        
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <div class="flex items-center justify-between text-[10px] font-semibold text-gray-400 mb-1.5">
                                <span>Progres Kelas</span>
                                <span class="<?php echo e($c['text']); ?> font-bold">0%</span>
                            </div>
                            <div class="h-1.5 w-full rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full rounded-full <?php echo e($c['bar']); ?> transition-all" style="width: 0%"></div>
                            </div>
                        </div>

                        
                        <div class="mt-3">
                            <span class="flex w-full items-center justify-center gap-1.5 rounded-xl <?php echo e($c['bg']); ?> py-2 text-xs font-bold text-white shadow-sm group-hover:opacity-90 transition">
                                Masuk Kelas
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div id="kelasEmpty" class="hidden mt-6 rounded-xl border border-gray-100 bg-white p-10 text-center shadow-sm">
            <p class="text-sm text-gray-400">Tidak ada kelas yang cocok dengan pencarianmu.</p>
        </div>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('kelasSearch');
    const grid  = document.getElementById('kelasGrid');
    const empty = document.getElementById('kelasEmpty');
    const count = document.getElementById('kelasCount');
    if (!input || !grid) return;

    input.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        const cards = grid.querySelectorAll('.kelas-card');
        let visible = 0;
        cards.forEach(card => {
            const match = !q || (card.dataset.search || '').includes(q);
            card.classList.toggle('hidden', !match);
            if (match) visible++;
        });
        if (empty) empty.classList.toggle('hidden', visible > 0);
        if (count) count.textContent = visible + ' kelas';
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\cendekia\resources\views/mahasiswa/kelas-saya.blade.php ENDPATH**/ ?>