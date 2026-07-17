<?php $__env->startSection('title', 'Jadwal Kuliah'); ?>
<?php $__env->startSection('content'); ?>

<?php
    $days = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    $hariIni = now()->locale('id')->dayName;
    
    // Fallback if day is not in array
    if (!in_array($hariIni, $days)) {
        $hariIni = 'Senin';
    }
    
    $eventsByDay = [];
    foreach ($kelasList as $k) {
        $eventsByDay[$k->hari ?? 'Senin'][] = $k;
    }
    foreach ($eventsByDay as $day => $items) {
        usort($items, fn($a,$b) => strcmp($a->jam_mulai ?? '07:00', $b->jam_mulai ?? '07:00'));
        $eventsByDay[$day] = $items;
    }
    
    $colors = ['border-blue-400 bg-blue-50/80', 'border-violet-400 bg-violet-50/80', 'border-emerald-400 bg-emerald-50/80', 'border-amber-400 bg-amber-50/80', 'border-rose-400 bg-rose-50/80'];
    $colorIdx = 0;
    $kelasColorMap = [];
    foreach ($kelasList as $k) {
        if (!isset($kelasColorMap[$k->id])) {
            $kelasColorMap[$k->id] = $colors[$colorIdx % count($colors)];
            $colorIdx++;
        }
    }
    $startHour = 7; $endHour = 20; $hourHeight = 80;
    $totalHeight = ($endHour - $startHour) * $hourHeight;
    $headerHeight = 44;
?>

<div x-data="{ viewMode: 'weekly', activeDay: '<?php echo e($hariIni); ?>' }" class="space-y-6">

    
    <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-[#002B6B] to-[#0044a8] px-6 py-6 sm:px-8 shadow-lg shadow-blue-950/15 relative">
        <div class="pointer-events-none absolute -right-8 -top-8 h-40 w-40 rounded-full bg-white/5"></div>
        <div class="pointer-events-none absolute right-20 bottom-0 h-20 w-20 rounded-full bg-white/5"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-blue-200/80">Semester Aktif</p>
                <h1 class="mt-1 text-xl font-extrabold text-white sm:text-2xl">Jadwal Kuliah</h1>
                <p class="mt-1 text-sm text-blue-100/80">Seluruh jadwal kelas yang kamu ikuti minggu ini.</p>
            </div>
            <div class="shrink-0 rounded-xl border border-white/20 bg-white/10 px-4 py-2 text-center">
                <p class="text-xs text-blue-200/80 font-medium">Hari ini</p>
                <p class="text-sm font-extrabold text-white"><?php echo e(now()->locale('id')->dayName); ?>, <?php echo e(now()->format('d M')); ?></p>
            </div>
        </div>
    </div>

    <?php if($kelasList->isEmpty()): ?>
        <div class="flex min-h-[280px] flex-col items-center justify-center rounded-2xl border-2 border-dashed border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-10 text-center shadow-sm">
            <div class="mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-50 dark:bg-slate-800 border border-gray-100 dark:border-slate-800 text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="font-bold text-gray-700 dark:text-slate-200">Belum Ada Jadwal</h3>
            <p class="mt-1 text-xs text-gray-400 dark:text-slate-500">Gabung ke kelas dulu untuk melihat jadwalmu.</p>
            <a href="<?php echo e(route('mahasiswa.jelajahi-kelas')); ?>" class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-[#002B6B] px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-800 transition">
                Jelajahi Kelas
            </a>
        </div>
    <?php else: ?>
        <!-- VIEW SELECTOR & DAY SELECTOR TABS -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 p-3 sm:p-4 rounded-2xl shadow-sm">
            <div class="flex items-center gap-1 bg-gray-100 dark:bg-slate-800 p-1 rounded-xl w-fit">
                <button @click="viewMode = 'weekly'" :class="viewMode === 'weekly' ? 'bg-[#002B6B] text-white dark:bg-blue-600' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'" class="px-4 py-2 text-xs font-bold rounded-lg transition">
                    Weekly View
                </button>
                <button @click="viewMode = 'daily'" :class="viewMode === 'daily' ? 'bg-[#002B6B] text-white dark:bg-blue-600' : 'text-gray-600 dark:text-slate-400 hover:text-gray-900 dark:hover:text-white'" class="px-4 py-2 text-xs font-bold rounded-lg transition">
                    Daily View
                </button>
            </div>
            
            <div x-show="viewMode === 'daily'" x-transition class="flex flex-wrap gap-1">
                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button @click="activeDay = '<?php echo e($day); ?>'" :class="activeDay === '<?php echo e($day); ?>' ? 'bg-[#002B6B]/10 text-[#002B6B] border-[#002B6B] dark:bg-blue-600/10 dark:text-blue-400 dark:border-blue-500/50 font-extrabold' : 'bg-transparent text-gray-500 border-transparent dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'" class="px-3 py-1.5 text-xs font-bold border rounded-lg transition">
                        <?php echo e($day); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- WEEKLY VIEW -->
        <div x-show="viewMode === 'weekly'" x-transition class="space-y-6">
            
            <div class="md:hidden space-y-4">
                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $events = $eventsByDay[$day] ?? []; ?>
                    <?php if(!empty($events)): ?>
                        <div class="overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                            <div @click="viewMode = 'daily'; activeDay = '<?php echo e($day); ?>'" class="flex items-center justify-between px-4 py-2.5 <?php echo e($day === $hariIni ? 'bg-[#002B6B]' : 'bg-gray-50 dark:bg-slate-800/40'); ?> border-b border-gray-100 dark:border-slate-800 cursor-pointer hover:opacity-90 transition">
                                <span class="text-xs font-bold <?php echo e($day === $hariIni ? 'text-white' : 'text-gray-700 dark:text-slate-200'); ?>"><?php echo e($day); ?></span>
                                <div class="flex items-center gap-1.5">
                                    <?php if($day === $hariIni): ?>
                                        <span class="rounded-full bg-white/20 px-2 py-0.5 text-[10px] font-bold text-white">Hari Ini</span>
                                    <?php endif; ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 <?php echo e($day === $hariIni ? 'text-white/80' : 'text-gray-400'); ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                            <div class="divide-y divide-gray-50 dark:divide-slate-800/50">
                                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center gap-3 px-4 py-3">
                                        <div class="shrink-0 text-center w-14">
                                            <p class="text-xs font-bold text-gray-700 dark:text-slate-200"><?php echo e(substr($ev->jam_mulai,0,5)); ?></p>
                                            <p class="text-[10px] text-gray-400"><?php echo e(substr($ev->jam_selesai,0,5)); ?></p>
                                        </div>
                                        <div class="flex-1 min-w-0 rounded-xl border-l-4 <?php echo e($kelasColorMap[$ev->id] ?? $colors[0]); ?> px-3 py-2">
                                            <p class="text-sm font-semibold text-gray-800 dark:text-slate-200 truncate"><?php echo e($ev->mataKuliah?->nama_mk ?? '-'); ?></p>
                                            <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-0.5"><?php echo e($ev->ruangan ?? '-'); ?> · <?php echo e($ev->mataKuliah?->sks ?? 0); ?> SKS</p>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="hidden md:block overflow-hidden rounded-2xl border border-slate-200/80 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-slate-800">
                                <th class="w-16 px-3 py-2 text-center text-[10px] font-semibold text-gray-400 dark:text-slate-500"></th>
                                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php $isToday = $day === $hariIni; ?>
                                    <th @click="viewMode = 'daily'; activeDay = '<?php echo e($day); ?>'" class="w-32 px-3 py-3 text-center border-l border-gray-100 dark:border-slate-800 <?php echo e($isToday ? 'bg-[#002B6B]' : 'bg-gray-50/80 dark:bg-slate-800/40'); ?> cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition duration-150">
                                        <span class="text-xs font-bold <?php echo e($isToday ? 'text-white' : 'text-gray-600 dark:text-slate-300'); ?>"><?php echo e($day); ?></span>
                                    </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for($h = $startHour; $h < $endHour; $h++): ?>
                                <tr class="border-b border-gray-50 dark:border-slate-850">
                                    <td class="w-16 px-3 py-2 text-center border-r border-gray-100 dark:border-slate-800">
                                        <span class="text-[10px] font-medium text-gray-400 dark:text-slate-500"><?php echo e(sprintf('%02d:00', $h)); ?></span>
                                    </td>
                                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <td class="w-32 border-l border-gray-100 dark:border-slate-800 p-0.5 align-top relative" style="height: 80px;">
                                            <?php
                                                $eventsAtThisHour = [];
                                                foreach ($eventsByDay[$day] ?? [] as $ev) {
                                                    [$sh, $sm] = array_pad(explode(':', $ev->jam_mulai ?? '07:00'), 2, '00');
                                                    if ((int)$sh === $h) {
                                                        $eventsAtThisHour[] = $ev;
                                                    }
                                                }
                                            ?>
                                            
                                            <?php $__currentLoopData = $eventsAtThisHour; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    [$sh, $sm] = array_pad(explode(':', $ev->jam_mulai ?? '07:00'), 2, '00');
                                                    [$eh, $em] = array_pad(explode(':', $ev->jam_selesai ?? '08:00'), 2, '00');
                                                    $startMin = (int)$sh * 60 + (int)$sm;
                                                    $endMin   = (int)$eh * 60 + (int)$em;
                                                    $durationMin = $endMin - $startMin;
                                                    $durationHours = $durationMin / 60;
                                                    $rowsSpanned = ceil($durationHours);
                                                    $heightPx = $rowsSpanned * 80;
                                                    $evColor  = $kelasColorMap[$ev->id] ?? $colors[0];
                                                ?>
                                                <div @click="viewMode = 'daily'; activeDay = '<?php echo e($day); ?>'" class="absolute left-0.5 right-0.5 rounded-lg border-l-4 overflow-hidden <?php echo e($evColor); ?> shadow-sm p-1.5 text-left text-[10px] cursor-pointer hover:scale-98 hover:shadow-md transition" 
                                                     style="height: <?php echo e($heightPx); ?>px; top: 2px; z-index: 10;">
                                                    <p class="font-bold text-gray-800 dark:text-slate-200 leading-tight line-clamp-2"><?php echo e($ev->mataKuliah?->nama_mk ?? '-'); ?></p>
                                                    <?php if($heightPx > 45): ?>
                                                        <p class="text-gray-600 dark:text-slate-350 line-clamp-1 mt-1 font-semibold"><?php echo e($ev->ruangan ?? '-'); ?></p>
                                                        <p class="text-gray-500 dark:text-slate-400 mt-1"><?php echo e(substr($ev->jam_mulai,0,5)); ?> - <?php echo e(substr($ev->jam_selesai,0,5)); ?></p>
                                                    <?php else: ?>
                                                        <p class="text-gray-600 dark:text-slate-350 line-clamp-1 mt-0.5"><?php echo e(substr($ev->jam_mulai,0,5)); ?> - <?php echo e(substr($ev->jam_selesai,0,5)); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- DAILY VIEW -->
        <div x-show="viewMode === 'daily'" x-transition class="space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#002B6B] dark:bg-blue-500"></span>
                    <span>Jadwal Hari <span x-text="activeDay"></span></span>
                </h2>
                <span x-show="activeDay === '<?php echo e($hariIni); ?>'" class="rounded-full bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 px-3 py-1 text-xs font-bold">Hari Ini</span>
            </div>

            <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $events = $eventsByDay[$day] ?? []; ?>
                <div x-show="activeDay === '<?php echo e($day); ?>'" class="space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ev): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex flex-col sm:flex-row gap-4 p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm hover:shadow-md transition">
                            <div class="flex items-center gap-2 sm:flex-col sm:items-start sm:justify-center sm:w-28 shrink-0">
                                <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-slate-800 text-xs font-bold text-gray-800 dark:text-slate-200"><?php echo e(substr($ev->jam_mulai,0,5)); ?></span>
                                <span class="text-xs text-gray-400 font-medium">sampai</span>
                                <span class="px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-slate-800 text-xs font-bold text-gray-800 dark:text-slate-200"><?php echo e(substr($ev->jam_selesai,0,5)); ?></span>
                            </div>
                            
                            <div class="flex-1 min-w-0 rounded-2xl border-l-4 <?php echo e($kelasColorMap[$ev->id] ?? $colors[0]); ?> px-4 py-3 bg-slate-50/50 dark:bg-slate-800/40">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h4 class="text-base font-bold text-slate-800 dark:text-white leading-tight"><?php echo e($ev->mataKuliah?->nama_mk ?? '-'); ?></h4>
                                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 font-semibold flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m5 0h5M9 7h.01M9 11h.01M9 15h.01M12 7h.01M12 11h.01M12 15h.01M15 7h.01M15 11h.01M15 15h.01"/>
                                            </svg>
                                            <?php echo e($ev->ruangan ?? '-'); ?>

                                        </p>
                                    </div>
                                    <span class="rounded-full bg-blue-100 text-blue-800 dark:bg-blue-950/40 dark:text-blue-400 px-3 py-1 text-xs font-extrabold shrink-0"><?php echo e($ev->mataKuliah?->sks ?? 0); ?> SKS</span>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-200/60 dark:border-slate-850 flex items-center justify-between text-xs text-gray-500 dark:text-slate-400">
                                    <span class="font-medium">Dosen Pengampu:</span>
                                    <span class="font-bold text-slate-800 dark:text-white"><?php echo e($ev->dosen?->name ?? 'Belum Ditentukan'); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="flex flex-col items-center justify-center p-12 rounded-2xl border-2 border-dashed border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-center shadow-sm">
                            <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-50 dark:bg-slate-850 text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 class="font-bold text-gray-700 dark:text-slate-200">Tidak Ada Kelas</h4>
                            <p class="text-xs text-gray-450 dark:text-slate-500 mt-1">Kamu bebas hari ini. Tetap produktif!</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\cendekia\resources\views/mahasiswa/schedule.blade.php ENDPATH**/ ?>