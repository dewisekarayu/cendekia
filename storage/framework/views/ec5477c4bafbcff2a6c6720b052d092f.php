<?php $__env->startSection('title', 'Jadwal'); ?>
<?php $__env->startSection('activeMenu', 'Jadwal'); ?>
<?php $__env->startSection('content'); ?>

<div class="space-y-6">
    
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-800 dark:text-white transition-colors duration-200">Jadwal Perkuliahan</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400 transition-colors duration-200">Lihat jadwal kelas dan acara akademik Anda</p>
        </div>
    </div>

    
    <div class="grid gap-6 lg:grid-cols-3">
        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm transition-colors duration-200">
                <div class="border-b border-gray-100 dark:border-slate-700 px-5 py-4 flex items-center justify-between">
                    <h3 class="font-bold text-slate-800 dark:text-white">Jadwal Minggu Ini</h3>
                </div>

                <div class="p-5">
                    
                    <div class="space-y-3">
                        <?php
                            $schedules = [
                                ['time' => '08:00 - 09:30', 'class' => 'Pemrograman Web', 'room' => 'Lab 1', 'students' => 32, 'color' => 'bg-blue-50 dark:bg-blue-950/40 border-l-4 border-blue-500 dark:border-blue-700'],
                                ['time' => '10:00 - 11:30', 'class' => 'Basis Data', 'room' => 'Lab 2', 'students' => 28, 'color' => 'bg-emerald-50 dark:bg-emerald-950/40 border-l-4 border-emerald-500 dark:border-emerald-700'],
                                ['time' => '13:00 - 14:30', 'class' => 'Algoritma & Struktur Data', 'room' => 'Ruang 305', 'students' => 35, 'color' => 'bg-purple-50 dark:bg-purple-950/40 border-l-4 border-purple-500 dark:border-purple-700'],
                            ];
                        ?>
                        <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="p-4 rounded-lg <?php echo e($item['color']); ?> flex items-start justify-between hover:shadow-md transition cursor-pointer">
                                <div class="flex-1">
                                    <p class="text-xs font-semibold text-gray-500 dark:text-slate-400 mb-1"><?php echo e($item['time']); ?></p>
                                    <h4 class="text-sm font-bold text-slate-800 dark:text-white"><?php echo e($item['class']); ?></h4>
                                    <div class="mt-2 flex gap-3 text-xs text-gray-600 dark:text-slate-300">
                                        <div class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m5 0h5M9 7h.01M9 11h.01M9 15h.01M12 7h.01M12 11h.01M12 15h.01M15 7h.01M15 11h.01M15 15h.01"/></svg>
                                            <?php echo e($item['room']); ?>

                                        </div>
                                        <div class="flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                                            <?php echo e($item['students']); ?> siswa
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="space-y-6">
            
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm transition-colors duration-200">
                <div class="border-b border-gray-100 dark:border-slate-700 px-5 py-4">
                    <h3 class="font-bold text-slate-800 dark:text-white">Acara Mendatang</h3>
                </div>

                <div class="space-y-3 p-5">
                    <?php
                        $events = [
                            ['date' => 'Hari Ini', 'title' => 'Ujian Tengah Semester', 'type' => 'Ujian'],
                            ['date' => 'Besok', 'title' => 'Rapat Dosen', 'type' => 'Rapat'],
                            ['date' => '15 Jan', 'title' => 'Pengumpulan Nilai', 'type' => 'Deadline'],
                        ];
                    ?>
                    <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex gap-3 pb-3 border-b border-gray-100 dark:border-slate-700 last:border-0">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg <?php echo e($event['type'] == 'Ujian' ? 'bg-red-50 dark:bg-red-950/40' : ($event['type'] == 'Rapat' ? 'bg-blue-50 dark:bg-blue-950/40' : 'bg-amber-50 dark:bg-amber-950/40')); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 <?php echo e($event['type'] == 'Ujian' ? 'text-red-500 dark:text-red-400' : ($event['type'] == 'Rapat' ? 'text-blue-500 dark:text-blue-400' : 'text-amber-500 dark:text-amber-400')); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 dark:text-slate-400 font-semibold"><?php echo e($event['date']); ?></p>
                                <p class="text-sm font-bold text-gray-800 dark:text-white mt-0.5"><?php echo e($event['title']); ?></p>
                                <span class="inline-block mt-1 text-[10px] font-semibold <?php echo e($event['type'] == 'Ujian' ? 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40' : ($event['type'] == 'Rapat' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/40' : 'text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/40')); ?> px-2 py-0.5 rounded">
                                    <?php echo e($event['type']); ?>

                                </span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 shadow-sm transition-colors duration-200">
                <div class="border-b border-gray-100 dark:border-slate-700 px-5 py-4">
                    <h3 class="font-bold text-slate-800 dark:text-white">Ringkasan</h3>
                </div>

                <div class="space-y-4 p-5">
                    <?php
                        $stats = [
                            ['label' => 'Total Jam', 'value' => '12.5', 'unit' => 'jam/minggu'],
                            ['label' => 'Kelas Aktif', 'value' => '3', 'unit' => 'kelas'],
                            ['label' => 'Total Mahasiswa', 'value' => '95', 'unit' => 'siswa'],
                        ];
                    ?>
                    <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-slate-700 last:border-0">
                            <span class="text-sm text-gray-600 dark:text-slate-300"><?php echo e($stat['label']); ?></span>
                            <div class="text-right">
                                <p class="text-lg font-bold text-slate-800 dark:text-white"><?php echo e($stat['value']); ?></p>
                                <p class="text-xs text-gray-550 dark:text-slate-500"><?php echo e($stat['unit']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\cendekia\resources\views/dosen/schedule.blade.php ENDPATH**/ ?>