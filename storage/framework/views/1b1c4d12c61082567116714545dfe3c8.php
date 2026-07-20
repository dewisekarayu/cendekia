<?php $__env->startSection('title', 'My Classes'); ?>
<?php $__env->startSection('activeMenu', 'Kelas Saya'); ?>

<?php $__env->startSection('content'); ?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-[#321270] to-[#4c19a0] dark:from-indigo-950 dark:to-purple-900 rounded-xl px-8 py-6 relative overflow-hidden mb-8 shadow-sm">
    <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/5"></div>
    <div class="mb-6 relative z-10">
        <h1 class="text-xl font-bold text-white">My Classes</h1>
        <p class="text-sm text-white/80 mt-1">Manage and access all your teaching classes this semester.</p>
    </div>
</div>

<!-- Filter Row -->
<div class="flex flex-col sm:flex-row gap-3 mb-6">
    <select class="text-sm border-gray-200 dark:border-slate-700 rounded-lg focus:border-[#321270] focus:ring-[#321270] bg-white dark:bg-slate-800 text-gray-600 dark:text-gray-300 py-2.5">
        <option class="dark:bg-slate-800">Semester Ganjil 2025/2026</option>
    </select>
    <form id="kelas-search-form" class="relative flex-1 max-w-sm">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input id="kelas-search-input" type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nama kelas..." autocomplete="off" class="w-full pl-9 text-sm border-gray-200 dark:border-slate-700 rounded-lg focus:border-[#321270] dark:focus:border-purple-500 bg-white dark:bg-slate-800 text-gray-800 dark:text-gray-100 focus:ring-[#321270] dark:focus:ring-purple-500 py-2.5">
    </form>
</div>

<!-- Class Grid -->
<?php if($kelasList->isEmpty()): ?>
<div class="bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 p-10 text-center shadow-sm transition-colors duration-200">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto text-gray-300 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
    </svg>
    <p class="text-gray-500 dark:text-slate-400 text-sm">
        <?php if($search !== ''): ?>
            Tidak ada kelas yang cocok dengan pencarian "<?php echo e($search); ?>".
        <?php else: ?>
            Belum ada kelas yang diampu. Data kelas akan muncul di sini setelah ditambahkan oleh Admin.
        <?php endif; ?>
    </p>
</div>
<?php else: ?>
<div id="kelas-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php
    $topColors = ['bg-blue-900', 'bg-amber-400', 'bg-emerald-500', 'bg-indigo-500'];
    $tagColors = [
        'text-blue-900 bg-blue-50 dark:text-blue-300 dark:bg-blue-950/40 dark:border dark:border-blue-900/30',
        'text-amber-700 bg-amber-50 dark:text-amber-300 dark:bg-amber-950/40 dark:border dark:border-amber-900/30',
        'text-emerald-700 bg-emerald-50 dark:text-emerald-300 dark:bg-emerald-950/40 dark:border dark:border-emerald-900/30',
        'text-indigo-700 bg-indigo-50 dark:text-indigo-300 dark:bg-indigo-950/40 dark:border dark:border-indigo-900/30'
    ];
    ?>

    <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="kelas-card bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 shadow-sm overflow-hidden transition-colors duration-200" data-search="<?php echo e(strtolower($kelas->mataKuliah->nama_mk ?? '')); ?> <?php echo e(strtolower($kelas->mataKuliah->kode_mk ?? '')); ?> <?php echo e(strtolower($kelas->kode_kelas ?? '')); ?> <?php echo e(strtolower($kelas->mataKuliah->programStudi->nama_prodi ?? '')); ?>">
        <div class="h-1.5 <?php echo e($topColors[$i % count($topColors)]); ?>"></div>
        <div class="p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="inline-block text-[10px] font-semibold tracking-wide px-2 py-0.5 rounded <?php echo e($tagColors[$i % count($tagColors)]); ?>">
                    <?php echo e($kelas->mataKuliah->programStudi->nama_prodi ?? 'Umum'); ?>

                </span>
                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-600 dark:text-emerald-400">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"></span>
                    <?php echo e($kelas->is_active ? 'Active' : 'Inactive'); ?>

                </span>
            </div>

            <h3 class="font-bold text-gray-800 dark:text-white leading-snug"><?php echo e($kelas->mataKuliah->nama_mk ?? '-'); ?></h3>
            <p class="text-xs text-gray-400 dark:text-slate-500 mt-0.5"><?php echo e($kelas->mataKuliah->kode_mk ?? '-'); ?> • <?php echo e($kelas->mataKuliah->sks ?? 0); ?> SKS</p>

            <div class="mt-3 space-y-1.5 text-xs text-gray-500 dark:text-slate-300">
                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <?php echo e($kelas->hari); ?>, <?php echo e($kelas->jam_mulai); ?> - <?php echo e($kelas->jam_selesai); ?>

                </div>
                <div class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <?php echo e($kelas->ruangan); ?>

                </div>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <div class="flex items-center gap-1.5 text-xs text-gray-500 dark:text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z" />
                    </svg>
                    <?php echo e($kelas->mahasiswa->count()); ?> students enrolled
                </div>
            </div>

            <a href="<?php echo e(route('dosen.kelas-detail', $kelas->id)); ?>" class="mt-4 block text-center bg-[#321270] dark:bg-[#6c2bd9] hover:bg-[#250d54] dark:hover:bg-[#5b21b6] text-white text-sm font-bold py-2 rounded-lg transition duration-150">
                Manage Class
            </a>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<div id="kelas-empty-state" class="hidden bg-white dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-700 p-10 text-center shadow-sm">
    <p class="text-gray-500 dark:text-slate-400 text-sm">Tidak ada kelas yang cocok dengan pencarian.</p>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('kelas-search-form');
    const input = document.getElementById('kelas-search-input');
    const grid = document.getElementById('kelas-grid');
    const emptyState = document.getElementById('kelas-empty-state');
    const cards = Array.from(document.querySelectorAll('.kelas-card'));

    if (!input || !cards.length) return;

    if (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
        });
    }

    const filterCards = () => {
        const query = input.value.toLowerCase().trim();
        let visibleCount = 0;

        cards.forEach((card) => {
            const searchText = (card.dataset.search || '').toLowerCase();
            const isMatch = !query || searchText.includes(query);
            card.classList.toggle('hidden', !isMatch);

            if (isMatch) {
                visibleCount++;
            }
        });

        if (grid) {
            grid.classList.toggle('hidden', visibleCount === 0);
        }

        if (emptyState) {
            emptyState.classList.toggle('hidden', visibleCount !== 0);
            const message = emptyState.querySelector('p');
            if (message) {
                message.textContent = query
                    ? `Tidak ada kelas yang cocok dengan pencarian "${query}".`
                    : 'Tidak ada kelas yang cocok dengan pencarian.';
            }
        }
    };

    input.addEventListener('input', filterCards);
    filterCards();
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\cendekia\resources\views/dosen/kelas-saya.blade.php ENDPATH**/ ?>