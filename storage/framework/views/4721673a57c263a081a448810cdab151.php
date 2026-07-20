<?php $__env->startSection('title', 'Dashboard Dosen'); ?>
<?php $__env->startSection('activeMenu', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<?php
    $firstName = explode(' ', auth()->user()->name)[0];
    $hour = now()->hour;
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
    $topColors = ['bg-blue-500', 'bg-violet-500', 'bg-emerald-500', 'bg-amber-500'];
?>

<div class="space-y-6">
    
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#321270] via-[#461a9c] to-[#5a2cc9] px-6 py-8 sm:px-8 shadow-md">
        <div class="pointer-events-none absolute -right-10 -top-10 h-48 w-48 rounded-full bg-white/5"></div>
        <div class="pointer-events-none absolute right-20 -bottom-10 h-32 w-32 rounded-full bg-white/5"></div>
        
        <div class="relative z-10 gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[11px] font-bold uppercase tracking-widest text-purple-200/90"><?php echo e($greeting); ?>, Dosen</p>
                <h1 class="mt-1 text-2xl font-extrabold text-white tracking-tight sm:text-3xl"><?php echo e($firstName); ?> 👋</h1>
                <p class="mt-2 max-w-md text-sm leading-relaxed text-purple-100/80">
                    Kamu mengampu <span class="font-bold text-white"><?php echo e($kelasList->count()); ?> kelas</span> aktif semester ini
                    <?php if($tugasPerluDinilai > 0): ?>
                        dengan <span class="font-bold text-amber-300"><?php echo e($tugasPerluDinilai); ?> berkas tugas</span> menunggu penilaian Anda.
                    <?php else: ?>
                        . Luar biasa! Semua tugas mahasiswa telah selesai dinilai. 🎉
                    <?php endif; ?>
                </p>
            </div>
            
            <div class="grid grid-cols-3 gap-3 w-full sm:w-80 shrink-0">
                <div class="rounded-xl border border-white/10 bg-white/10 p-3 text-center backdrop-blur-sm">
                    <p class="text-xl font-extrabold text-white"><?php echo e($kelasList->count()); ?></p>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-purple-200/70 mt-0.5">Kelas</p>
                </div>
                <div class="rounded-xl border border-white/10 bg-white/10 p-3 text-center backdrop-blur-sm">
                    <p class="text-xl font-extrabold <?php echo e($tugasPerluDinilai > 0 ? 'text-amber-300' : 'text-white'); ?>"><?php echo e($tugasPerluDinilai); ?></p>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-purple-200/70 mt-0.5">Dinilai</p>
                </div>
                <div class="rounded-xl border border-white/10 bg-white/10 p-3 text-center backdrop-blur-sm">
                    <p class="text-xl font-extrabold text-white"><?php echo e($totalMahasiswa); ?></p>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-purple-200/70 mt-0.5">Siswa</p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <?php $__currentLoopData = [
            ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'label' => 'Total Kelas Diampu', 'value' => $kelasList->count(), 'color' => 'bg-[#321270]/10 text-[#321270] dark:bg-purple-950/40 dark:text-purple-300'],
            ['icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Tugas Perlu Dinilai', 'value' => $tugasPerluDinilai, 'color' => $tugasPerluDinilai > 0 ? 'bg-amber-50 text-amber-600 border border-amber-200/40 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-900/30' : 'bg-slate-50 text-slate-400 dark:bg-slate-900/50 dark:text-slate-500'],
            ['icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z', 'label' => 'Total Bimbingan Mahasiswa', 'value' => $totalMahasiswa, 'color' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-300'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex items-center gap-4 rounded-2xl border border-slate-200/60 dark:border-slate-700 bg-white dark:bg-slate-800 p-5 shadow-sm transition-colors duration-200">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl <?php echo e($stat['color']); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($stat['icon']); ?>"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 dark:text-slate-500"><?php echo e($stat['label']); ?></p>
                    <p class="text-2xl font-black text-slate-800 dark:text-white mt-0.5"><?php echo e(number_format($stat['value'], 0, ',', '.')); ?></p>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div>
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800 dark:text-white tracking-tight transition-colors duration-200">Kelas Mengajar Saya</h2>
            <a href="<?php echo e(route('dosen.kelas-saya')); ?>" class="inline-flex items-center gap-1 text-xs font-bold text-[#321270] dark:text-purple-400 hover:text-[#4a1fa8] dark:hover:text-purple-300 transition group">
                Lihat Semua Kelas 
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 transform group-hover:translate-x-0.5 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <?php if($kelasList->isEmpty()): ?>
            <div class="flex min-h-[160px] flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 text-center shadow-sm transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-8 w-8 text-slate-300 dark:text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <p class="text-sm font-semibold text-slate-400 dark:text-slate-500">Belum ada kelas perkuliahan aktif yang diampu.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <?php $__currentLoopData = $kelasList->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200/70 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md">
                        <div class="h-1.5 <?php echo e($topColors[$i % count($topColors)]); ?>"></div>
                        <div class="flex flex-1 flex-col p-5">
                            <div class="mb-3 flex items-center justify-between gap-2">
                                <span class="rounded-lg bg-[#321270]/10 dark:bg-purple-950/40 px-2.5 py-0.5 text-[10px] font-bold text-[#321270] dark:text-purple-300">
                                    <?php echo e($kelas->mataKuliah?->programStudi?->kode_prodi ?? 'MK'); ?>

                                </span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 dark:bg-emerald-950/20 px-2 py-0.5 rounded-md">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            </div>

                            <h3 class="text-sm font-bold text-slate-800 dark:text-white leading-snug line-clamp-2 group-hover:text-[#321270] dark:group-hover:text-purple-400 transition-colors duration-150">
                                <?php echo e($kelas->mataKuliah?->nama_mk ?? '-'); ?>

                            </h3>
                            <p class="mt-1 text-[11px] font-medium text-slate-400 dark:text-slate-500"><?php echo e($kelas->kode_kelas); ?> &middot; <?php echo e($kelas->mataKuliah?->sks ?? 0); ?> SKS</p>

                            <div class="mt-4 space-y-2 border-t border-slate-50 dark:border-slate-700 pt-3 text-xs text-slate-500 dark:text-slate-400">
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="font-medium text-slate-600 dark:text-slate-300"><?php echo e($kelas->hari); ?>, <?php echo e(substr($kelas->jam_mulai, 0, 5)); ?> WIB</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z"/>
                                    </svg>
                                    <span class="font-medium text-slate-600 dark:text-slate-300"><?php echo e($kelas->mahasiswa->count()); ?> Mahasiswa Terdaftar</span>
                                </div>
                            </div>

                            <div class="mt-5 pt-2">
                                <a href="<?php echo e(route('dosen.kelas-detail', $kelas->id)); ?>"
                                   class="block w-full rounded-xl bg-[#321270] dark:bg-[#6c2bd9] py-2 text-center text-xs font-bold text-white hover:bg-[#250d54] dark:hover:bg-[#5b21b6] transition duration-150 shadow-sm">
                                    Kelola Kelas
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="overflow-hidden rounded-2xl border border-slate-200/70 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm transition-colors duration-200">
        <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700 px-5 py-4 bg-slate-50/50 dark:bg-slate-900/30">
            <h2 class="text-sm font-bold text-slate-800 dark:text-white tracking-tight">Pengumpulan Tugas Terbaru</h2>
            <?php if($submissions->isNotEmpty()): ?>
                <a href="<?php echo e(route('dosen.kelas-tugas.rekap', ['id' => $submissions->first()->tugas?->kelas_perkuliahan_id])); ?>" class="inline-flex items-center gap-1 text-xs font-bold text-[#321270] dark:text-purple-400 hover:underline">Buka Lembar Nilai &rarr;</a>
            <?php endif; ?>
        </div>

        <?php if($submissions->isEmpty()): ?>
            <div class="py-12 text-center text-sm font-medium text-slate-400 dark:text-slate-500">Belum ada tugas yang dikumpulkan oleh mahasiswa.</div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[600px] align-middle">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-700 bg-slate-50/40 dark:bg-slate-900/20 text-[10px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            <th class="px-5 py-3 text-left">Nama Mahasiswa</th>
                            <th class="px-5 py-3 text-left">Judul Tugas / Kuliah</th>
                            <th class="px-5 py-3 text-left">Waktu Pengiriman</th>
                            <th class="px-5 py-3 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                        <?php $__currentLoopData = $submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-purple-50/10 dark:hover:bg-purple-900/10 transition duration-150">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-[#321270] dark:bg-purple-950 text-xs font-bold text-white dark:text-purple-300 shadow-sm">
                                            <?php echo e(strtoupper(substr($item->mahasiswa?->name ?? '?', 0, 1))); ?>

                                        </div>
                                        <span class="font-semibold text-slate-700 dark:text-slate-200 max-w-[140px] truncate block"><?php echo e($item->mahasiswa?->name ?? '-'); ?></span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5">
                                    <p class="font-bold text-[#321270] dark:text-purple-300 max-w-[200px] truncate"><?php echo e($item->tugas?->judul ?? '-'); ?></p>
                                    <p class="text-[11px] font-medium text-slate-400 dark:text-slate-500 max-w-[200px] truncate mt-0.5"><?php echo e($item->tugas?->kelasPerkuliahan?->mataKuliah?->nama_mk ?? '-'); ?></p>
                                </td>
                                <td class="px-5 py-3.5 text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    <span class="inline-flex items-center gap-1">
                                        <i class="bi bi-clock"></i>
                                        <?php echo e($item->waktu_kumpul?->diffForHumans() ?? '-'); ?>

                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <a href="<?php echo e(route('dosen.kelas-tugas.rekap', ['id' => $item->tugas?->kelas_perkuliahan_id])); ?>"
                                        class="inline-flex items-center rounded-xl bg-[#321270]/10 dark:bg-purple-950/40 px-4 py-1.5 text-xs font-bold text-[#321270] dark:text-purple-300 hover:bg-[#321270] dark:hover:bg-[#6c2bd9] hover:text-white dark:hover:text-white transition duration-150">
                                        Buka Evaluasi
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\cendekia\resources\views/dosen/dashboard.blade.php ENDPATH**/ ?>