<?php $__env->startSection('title', 'Kalender Akademik'); ?>
<?php $__env->startSection('activeMenu', 'Kalender Akademik'); ?>
<?php $__env->startSection('content'); ?>



<div class="space-y-6 max-w-7xl mx-auto p-3 sm:p-4"
     x-data="kalender()"
     x-init="init()">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="min-w-0">
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-2">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="hover:text-gray-900 dark:hover:text-white transition-colors">Dashboard</a>
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                <span class="text-gray-900 dark:text-white font-medium truncate">Kalender Akademik</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-md">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <span>Kalender Akademik</span>
            </h1>
            <?php
                $activeSem = $semesters->firstWhere('id', $selectedSemesterId);
            ?>
            <?php if($activeSem): ?>
            <p class="mt-2 flex items-center gap-2 flex-wrap">
                <span class="inline-block px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-xs font-semibold">
                    <?php echo e($activeSem->tahun_ajaran); ?> – <?php echo e($activeSem->nama_semester); ?>

                </span>
                <?php if($activeSem->is_active): ?>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> Aktif
                    </span>
                <?php endif; ?>
            </p>
            <?php endif; ?>
        </div>
        <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap sm:flex-nowrap">
            <a href="<?php echo e(route('admin.kalender-akademik.create')); ?>"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white rounded-xl font-medium text-sm transition-all duration-200 shadow-md hover:shadow-lg flex-1 sm:flex-initial justify-center sm:justify-start">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Agenda</span>
            </a>
            <a href="<?php echo e(route('admin.dashboard')); ?>"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-slate-200 rounded-xl font-medium text-sm transition-colors self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Dashboard
            </a>
        </div>
    </div>

    
    <?php if(session('success')): ?>
    <div class="bg-green-50 border border-green-200 dark:bg-green-900/20 dark:border-green-800 rounded-xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <p class="text-green-800 dark:text-green-300 text-sm font-medium"><?php echo e(session('success')); ?></p>
    </div>
    <?php endif; ?>

    
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        
        <div class="lg:col-span-3 space-y-5">

            
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-gray-100 dark:border-slate-700">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                        
                        <div class="flex items-center gap-2">
                            <button @click="prevMonth()"
                                    class="p-2 rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors"
                                    aria-label="Bulan sebelumnya">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            </button>

                            <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white min-w-[160px] text-center" x-text="currentMonthYear"></h2>

                            <button @click="nextMonth()"
                                    class="p-2 rounded-xl bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-slate-300 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors"
                                    aria-label="Bulan berikutnya">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </button>

                            <button @click="goToToday()"
                                    class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors text-sm font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Hari Ini
                            </button>
                        </div>

                        
                        <div class="flex items-center gap-2 flex-wrap">
                            <select x-model="selectedSemester"
                                    @change="navigateWithFilter()"
                                    class="px-3 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 text-sm font-medium text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition appearance-none cursor-pointer"
                                    style="padding-right:2.25rem; background-image:url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 viewBox=%220 0 16 16%22><path fill=%22%236b7280%22 d=%22M4 6l4 4 4-4H4z%22/></svg>'); background-position:right 0.6rem center; background-repeat:no-repeat;">
                                <option value="">Semua Semester</option>
                                <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sem->id); ?>" <?php echo e($selectedSemesterId == $sem->id ? 'selected' : ''); ?>>
                                        <?php echo e($sem->tahun_ajaran); ?> – <?php echo e($sem->nama_semester); ?> <?php if($sem->is_active): ?>(Aktif)<?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="text-xs text-gray-500 dark:text-gray-400 self-center mr-1 font-medium">Filter:</span>
                        <?php
                            $kategoriList = [
                                'uts'              => ['label' => 'UTS',      'color' => '#DC2626'],
                                'uas'              => ['label' => 'UAS',      'color' => '#DC2626'],
                                'libur_nasional'   => ['label' => 'Libur Nas.','color' => '#16A34A'],
                                'libur_akademik'   => ['label' => 'Libur Akd.','color' => '#16A34A'],
                                'deadline_tugas'   => ['label' => 'Deadline', 'color' => '#EA580C'],
                                'deadline_skripsi' => ['label' => 'Skripsi',  'color' => '#EA580C'],
                                'praktikum'        => ['label' => 'Praktikum','color' => '#65A30D'],
                                'wisuda'           => ['label' => 'Wisuda',   'color' => '#9333EA'],
                                'pengumuman_nilai' => ['label' => 'Nilai',    'color' => '#0891B2'],
                                'pembayaran_ukt'   => ['label' => 'UKT',      'color' => '#F59E0B'],
                                'pengisian_krs'    => ['label' => 'KRS',      'color' => '#002B6B'],
                                'pengisian_khs'    => ['label' => 'KHS',      'color' => '#002B6B'],
                                'lainnya'          => ['label' => 'Lainnya',  'color' => '#64748B'],
                            ];
                        ?>
                        <?php $__currentLoopData = $kategoriList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button"
                                    @click="toggleCategory('<?php echo e($key); ?>')"
                                    :class="isCategoryVisible('<?php echo e($key); ?>')
                                        ? 'opacity-100 ring-2 ring-offset-1'
                                        : 'opacity-40'"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold transition-all duration-150 cursor-pointer"
                                    :style="isCategoryVisible('<?php echo e($key); ?>')
                                        ? 'background-color:<?php echo e($info['color']); ?>20; color:<?php echo e($info['color']); ?>; ring-color:<?php echo e($info['color']); ?>'
                                        : 'background-color:#f3f4f6; color:#6b7280'">
                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" style="background-color:<?php echo e($info['color']); ?>"></span>
                                <?php echo e($info['label']); ?>

                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <button type="button" @click="resetCategories()"
                                class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-600 transition-colors">
                            Semua
                        </button>
                    </div>
                </div>

                
                <div class="p-3 sm:p-4">
                    
                    <div class="grid grid-cols-7 mb-1">
                        <?php $__currentLoopData = ['Min','Sen','Sel','Rab','Kam','Jum','Sab']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="text-center py-2 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider"><?php echo e($d); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div class="grid grid-cols-7 gap-1">
                        <template x-for="(day, idx) in calendarDays" :key="idx">
                            <div class="relative rounded-xl cursor-pointer select-none transition-all duration-150"
                                 :class="{
                                     'bg-blue-50 dark:bg-blue-900/25 ring-1 ring-blue-300 dark:ring-blue-700': day.isToday && !day.isSelected,
                                     'bg-blue-600 ring-2 ring-blue-500 shadow-md': day.isSelected,
                                     'hover:bg-gray-100 dark:hover:bg-slate-700/70': !day.isToday && !day.isSelected,
                                     'bg-white dark:bg-slate-800': !day.isToday && !day.isSelected,
                                     'opacity-40': !day.isCurrentMonth,
                                 }"
                                 style="min-height:90px;"
                                 @click="selectDay(day)"
                                 :aria-label="'Tanggal ' + day.date"
                                 tabindex="0"
                                 @keydown.enter.prevent="selectDay(day)"
                                 @keydown.space.prevent="selectDay(day)">

                                
                                <div class="p-1.5 pb-0 flex justify-between items-start">
                                    <span class="text-xs font-bold leading-none"
                                          :class="day.isSelected ? 'text-white' : (day.isToday ? 'text-blue-600 dark:text-blue-400' : (day.isCurrentMonth ? 'text-gray-800 dark:text-gray-200' : 'text-gray-400 dark:text-gray-600'))"
                                          x-text="day.date"></span>
                                    <span x-show="day.filteredEvents.length > 0"
                                          class="text-[9px] font-bold leading-none"
                                          :class="day.isSelected ? 'text-blue-200' : 'text-gray-400 dark:text-gray-500'"
                                          x-text="day.filteredEvents.length"></span>
                                </div>

                                
                                <div class="px-1 pb-1.5 pt-1 space-y-0.5">
                                    <template x-for="event in day.filteredEvents.slice(0, 3)" :key="event.id">
                                        <div class="px-1.5 py-0.5 rounded-md text-[9px] sm:text-[10px] font-semibold truncate leading-tight"
                                             :style="'background-color:' + event.warna + '25; color:' + event.warna"
                                             @click.stop="openModal(event)"
                                             :title="event.judul">
                                            <span x-text="event.judul"></span>
                                        </div>
                                    </template>
                                    <div x-show="day.filteredEvents.length > 3"
                                         class="px-1.5 py-0.5 rounded-md text-[9px] font-semibold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-slate-700/50 text-center"
                                         x-text="'+' + (day.filteredEvents.length - 3) + ' agenda'"></div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Agenda Hari Ini</h3>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 text-xs font-bold"
                          x-text="todaysEvents.length + ' agenda'"></span>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-slate-700/50">
                    <template x-if="todaysEvents.length === 0">
                        <div class="p-8 text-center">
                            <svg class="w-10 h-10 mx-auto text-gray-200 dark:text-gray-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="text-gray-400 dark:text-gray-500 text-sm">Tidak ada agenda hari ini</p>
                        </div>
                    </template>
                    <template x-for="event in todaysEvents" :key="event.id">
                        <div class="flex items-start gap-3 p-4 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors cursor-pointer"
                             :style="'border-left: 3px solid ' + event.warna"
                             @click="openModal(event)">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0" :style="'background-color:' + event.warna + '20'">
                                <svg class="w-4 h-4" :style="'color:' + event.warna" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white text-sm truncate" x-text="event.judul"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5" x-text="event.waktu_formatted"></p>
                                <template x-if="event.lokasi">
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 flex items-center gap-1">
                                        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        <span x-text="event.lokasi" class="truncate"></span>
                                    </p>
                                </template>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold flex-shrink-0"
                                  :style="'background-color:' + event.warna + '20; color:' + event.warna"
                                  x-text="event.jenis_kegiatan_label"></span>
                        </div>
                    </template>
                </div>
            </div>

            
            <?php if($historyEvents->count() > 0): ?>
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gray-100 dark:bg-slate-700 flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Riwayat Agenda</h3>
                    <span class="ml-auto px-2.5 py-1 rounded-full bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300 text-xs font-bold">
                        <?php echo e($historyEvents->count()); ?> agenda
                    </span>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-slate-700/50">
                    <?php $__currentLoopData = $historyEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start gap-3 p-4 opacity-70 hover:opacity-100 transition-opacity"
                         style="border-left: 3px solid <?php echo e($event->warna); ?>">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 dark:text-gray-200 text-sm truncate"><?php echo e($event->judul); ?></p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">
                                <?php echo e($event->tanggal_mulai->format('d M Y')); ?>

                                <?php if($event->tanggal_selesai && !$event->tanggal_mulai->eq($event->tanggal_selesai)): ?>
                                    – <?php echo e($event->tanggal_selesai->format('d M Y')); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold flex-shrink-0 bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400">
                            Selesai
                        </span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php endif; ?>

        </div>

        
        <div class="lg:col-span-1 space-y-5">

            
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden"
                 x-show="selectedDay !== null" x-cloak>
                <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700 bg-blue-50 dark:bg-blue-900/20 flex items-center justify-between">
                    <h3 class="font-bold text-blue-900 dark:text-blue-200 text-sm" x-text="selectedDayLabel"></h3>
                    <button @click="selectedDay = null"
                            class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-200 transition-colors p-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-slate-700/50 max-h-80 overflow-y-auto">
                    <template x-if="selectedDay && selectedDay.filteredEvents.length === 0">
                        <div class="p-5 text-center">
                            <p class="text-gray-400 dark:text-gray-500 text-xs">Tidak ada agenda pada tanggal ini</p>
                        </div>
                    </template>
                    <template x-for="event in (selectedDay?.filteredEvents ?? [])" :key="event.id">
                        <div class="p-3 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors cursor-pointer"
                             :style="'border-left: 3px solid ' + event.warna"
                             @click="openModal(event)">
                            <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate" x-text="event.judul"></p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5" x-text="event.waktu_formatted"></p>
                            <span class="inline-block mt-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold"
                                  :style="'background-color:' + event.warna + '20; color:' + event.warna"
                                  x-text="event.jenis_kegiatan_label"></span>
                        </div>
                    </template>
                </div>
            </div>

            
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden sticky top-24">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-3 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20">
                    <div class="w-9 h-9 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm">Agenda Mendatang</h3>
                    <span class="ml-auto text-xs text-gray-400 dark:text-gray-500" x-text="upcomingEvents.length + ' item'"></span>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-slate-700/50 max-h-[420px] overflow-y-auto">
                    <template x-if="upcomingEvents.length === 0">
                        <div class="p-6 text-center">
                            <p class="text-gray-400 dark:text-gray-500 text-sm">Tidak ada agenda mendatang</p>
                        </div>
                    </template>
                    <template x-for="event in upcomingEvents" :key="event.id">
                        <div class="p-3 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors cursor-pointer group"
                             :style="'border-left: 3px solid ' + event.warna"
                             @click="openModal(event)">
                            <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" x-text="event.judul"></p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5" x-text="event.tanggal_mulai_formatted"></p>
                            <span class="inline-block mt-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold"
                                  :style="'background-color:' + event.warna + '20; color:' + event.warna"
                                  x-text="event.jenis_kegiatan_label"></span>
                        </div>
                    </template>
                </div>
            </div>

            
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700">
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm">Ringkasan Semester</h3>
                </div>
                <div class="p-4 space-y-2">
                    <?php
                        $totalAgenda  = $events->count();
                        $totalExam    = $events->whereIn('jenis_kegiatan', ['uts','uas'])->count();
                        $totalLibur   = $events->whereIn('jenis_kegiatan', ['libur_nasional','libur_akademik','cuti_akademik'])->count();
                        $totalDeadline= $events->whereIn('jenis_kegiatan', ['deadline_tugas','deadline_skripsi'])->count();
                        $totalLain    = $totalAgenda - $totalExam - $totalLibur - $totalDeadline;
                    ?>
                    <div class="flex justify-between items-center p-2 rounded-lg bg-gray-50 dark:bg-slate-700/40">
                        <span class="text-xs text-gray-600 dark:text-gray-300">Total Bulan Ini</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white"><?php echo e($totalAgenda); ?></span>
                    </div>
                    <div class="flex justify-between items-center p-2 rounded-lg bg-red-50 dark:bg-red-900/20">
                        <span class="text-xs text-red-700 dark:text-red-300">UTS & UAS</span>
                        <span class="text-sm font-bold text-red-700 dark:text-red-300"><?php echo e($totalExam); ?></span>
                    </div>
                    <div class="flex justify-between items-center p-2 rounded-lg bg-green-50 dark:bg-green-900/20">
                        <span class="text-xs text-green-700 dark:text-green-300">Libur & Cuti</span>
                        <span class="text-sm font-bold text-green-700 dark:text-green-300"><?php echo e($totalLibur); ?></span>
                    </div>
                    <div class="flex justify-between items-center p-2 rounded-lg bg-orange-50 dark:bg-orange-900/20">
                        <span class="text-xs text-orange-700 dark:text-orange-300">Deadline</span>
                        <span class="text-sm font-bold text-orange-700 dark:text-orange-300"><?php echo e($totalDeadline); ?></span>
                    </div>
                    <div class="flex justify-between items-center p-2 rounded-lg bg-purple-50 dark:bg-purple-900/20">
                        <span class="text-xs text-purple-700 dark:text-purple-300">Lainnya</span>
                        <span class="text-sm font-bold text-purple-700 dark:text-purple-300"><?php echo e(max(0,$totalLain)); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-2xl border border-indigo-100 dark:border-indigo-800 p-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-200 dark:bg-indigo-800 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-indigo-700 dark:text-indigo-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-indigo-900 dark:text-indigo-300 text-xs mb-2">Tips Kalender</p>
                        <ul class="text-xs text-indigo-700 dark:text-indigo-300 space-y-1">
                            <li class="flex items-start gap-1.5"><span class="text-green-500 font-bold mt-0.5">✓</span>Klik tanggal untuk lihat semua agenda di hari itu</li>
                            <li class="flex items-start gap-1.5"><span class="text-green-500 font-bold mt-0.5">✓</span>Klik badge agenda untuk detail lengkap</li>
                            <li class="flex items-start gap-1.5"><span class="text-green-500 font-bold mt-0.5">✓</span>Filter kategori untuk fokus pada jenis tertentu</li>
                            <li class="flex items-start gap-1.5"><span class="text-green-500 font-bold mt-0.5">✓</span>Tombol "Hari Ini" kembali ke bulan sekarang</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div x-show="modalOpen"
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     role="dialog" aria-modal="true">
    
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" @click="closeModal()"></div>

    
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-hidden z-10"
         x-show="modalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95">

        
        <div class="flex items-center gap-3 p-4 border-b border-gray-100 dark:border-slate-700">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm"
                 :style="'background-color:' + (selectedEvent?.warna ?? '#002B6B')">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
            </div>
            <h3 class="font-bold text-gray-900 dark:text-white text-base flex-1 min-w-0 truncate" x-text="selectedEvent?.judul ?? ''"></h3>
            <button @click="closeModal()"
                    class="p-2 rounded-xl text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-600 dark:hover:text-gray-200 transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        
        <div class="p-4 space-y-4 overflow-y-auto max-h-[calc(90vh-140px)]">
            
            <div class="flex items-center gap-2 flex-wrap">
                <span class="px-3 py-1 rounded-full text-xs font-bold"
                      :style="'background-color:' + (selectedEvent?.warna ?? '#002B6B') + '20; color:' + (selectedEvent?.warna ?? '#002B6B')"
                      x-text="selectedEvent?.jenis_kegiatan_label ?? ''"></span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-slate-700 text-gray-600 dark:text-gray-300"
                      x-text="selectedEvent?.waktu_formatted ?? ''"></span>
            </div>

            
            <div class="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span x-text="selectedEvent?.tanggal_label ?? ''"></span>
            </div>

            
            <template x-if="selectedEvent?.lokasi">
                <div class="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span x-text="selectedEvent?.lokasi ?? ''"></span>
                </div>
            </template>

            
            <template x-if="selectedEvent?.deskripsi">
                <div class="pt-3 border-t border-gray-100 dark:border-slate-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1 font-medium uppercase tracking-wider">Deskripsi</p>
                    <p class="text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap leading-relaxed" x-text="selectedEvent?.deskripsi ?? ''"></p>
                </div>
            </template>

            
            <div class="pt-3 border-t border-gray-100 dark:border-slate-700 grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1 font-medium uppercase tracking-wider">Semester</p>
                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200" x-text="selectedEvent?.semester_label ?? '–'"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mb-1 font-medium uppercase tracking-wider">Status</p>
                    <span class="inline-flex px-2 py-1 rounded-full text-xs font-bold"
                          :style="'background-color:' + (selectedEvent?.status_badge?.bg ?? '#F1F3F9') + '; color:' + (selectedEvent?.status_badge?.color ?? '#64748B')"
                          x-text="selectedEvent?.status_badge?.label ?? '–'"></span>
                </div>
            </div>
        </div>

        
        <div class="p-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-800/50">
            <button @click="closeModal()"
                    class="w-full py-2.5 px-4 rounded-xl font-semibold text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 border border-gray-200 dark:border-slate-600 transition-colors shadow-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
<script>
/**
 * PERBAIKAN UTAMA:
 * Semua state digabung dalam satu Alpine component "kalender()" untuk menghindari
 * scope isolation bug antara parent x-data dan child x-data="calendarGrid()".
 *
 * Navigasi bulan menggunakan redirect ke URL baru sehingga server selalu
 * mengirim eventsByDate yang benar untuk bulan tersebut.
 *
 * Modal menggunakan state Alpine langsung, bukan __x internal API (deprecated di v3).
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('kalender', () => ({
        // ─── State navigasi bulan ───
        currentMonth: <?php echo e($selectedMonth); ?>,
        currentYear:  <?php echo e($selectedYear); ?>,

        // ─── Data dari server (sudah include accessor via $appends model) ───
        eventsByDate:   <?php echo json_encode($eventsByDate, 15, 512) ?>,
        todaysEvents:   <?php echo json_encode($todaysEvents, 15, 512) ?>,
        upcomingEvents: <?php echo json_encode($upcomingEvents, 15, 512) ?>,

        // ─── State filter kategori ───
        // Kosong = semua tampil; berisi key = hanya key itu yang tampil
        hiddenCategories: [],

        // ─── State kalender ───
        selectedDay:  null,
        selectedEvent: null,
        modalOpen:    false,

        // ─── State filter semester ───
        selectedSemester: '<?php echo e($selectedSemesterId ?? ""); ?>',

        // ─────────────────────────────────────────────
        // INIT
        // ─────────────────────────────────────────────
        init() {
            // Tambahkan tanggal_mulai_formatted ke upcomingEvents untuk sidebar
            this.upcomingEvents = this.upcomingEvents.map(e => ({
                ...e,
                tanggal_mulai_formatted: this.formatTanggal(e.tanggal_mulai),
            }));
        },

        // ─────────────────────────────────────────────
        // COMPUTED: Kalender grid (array of day objects)
        // ─────────────────────────────────────────────
        get calendarDays() {
            const year  = this.currentYear;
            const month = this.currentMonth;

            const firstDay      = new Date(year, month - 1, 1);
            const lastDay       = new Date(year, month, 0);
            const startWeekday  = firstDay.getDay(); // 0=Sun
            const daysInMonth   = lastDay.getDate();
            const prevLastDay   = new Date(year, month - 1, 0).getDate();

            const days = [];

            // Hari dari bulan sebelumnya
            for (let i = startWeekday - 1; i >= 0; i--) {
                const d = prevLastDay - i;
                const m = month === 1 ? 12 : month - 1;
                const y = month === 1 ? year - 1 : year;
                days.push(this.buildDay(d, m, y, false));
            }

            // Hari bulan ini
            for (let d = 1; d <= daysInMonth; d++) {
                days.push(this.buildDay(d, month, year, true));
            }

            // Hari dari bulan berikutnya (lengkapi baris terakhir)
            let nextDay = 1;
            while (days.length % 7 !== 0) {
                const m = month === 12 ? 1 : month + 1;
                const y = month === 12 ? year + 1 : year;
                days.push(this.buildDay(nextDay++, m, y, false));
            }

            return days;
        },

        buildDay(date, month, year, isCurrentMonth) {
            const pad     = n => String(n).padStart(2, '0');
            const dateStr = `${year}-${pad(month)}-${pad(date)}`;
            const todayStr = new Date().toISOString().split('T')[0];
            const rawEvents = this.eventsByDate[dateStr] ?? [];

            // Filter kategori
            const filteredEvents = rawEvents.filter(e =>
                this.hiddenCategories.length === 0 ||
                !this.hiddenCategories.includes(e.jenis_kegiatan)
            );

            return {
                date,
                month,
                year,
                dateStr,
                isCurrentMonth,
                isToday:    dateStr === todayStr,
                isSelected: this.selectedDay?.dateStr === dateStr,
                events:         rawEvents,
                filteredEvents: filteredEvents,
            };
        },

        // ─────────────────────────────────────────────
        // COMPUTED: Label judul bulan tahun
        // ─────────────────────────────────────────────
        get currentMonthYear() {
            const months = ['Januari','Februari','Maret','April','Mei','Juni',
                            'Juli','Agustus','September','Oktober','November','Desember'];
            return `${months[this.currentMonth - 1]} ${this.currentYear}`;
        },

        get selectedDayLabel() {
            if (!this.selectedDay) return '';
            const days   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            const d = new Date(this.selectedDay.year, this.selectedDay.month - 1, this.selectedDay.date);
            return `${days[d.getDay()]}, ${this.selectedDay.date} ${months[this.selectedDay.month - 1]} ${this.selectedDay.year}`;
        },

        // ─────────────────────────────────────────────
        // NAVIGASI BULAN
        // Redirect ke URL baru agar data server selalu fresh
        // ─────────────────────────────────────────────
        prevMonth() {
            let m = this.currentMonth - 1;
            let y = this.currentYear;
            if (m < 1) { m = 12; y--; }
            this.navigate(m, y);
        },

        nextMonth() {
            let m = this.currentMonth + 1;
            let y = this.currentYear;
            if (m > 12) { m = 1; y++; }
            this.navigate(m, y);
        },

        goToToday() {
            const today = new Date();
            this.navigate(today.getMonth() + 1, today.getFullYear());
        },

        navigate(month, year) {
            const url = new URL(window.location.href);
            url.searchParams.set('month', month);
            url.searchParams.set('year', year);
            if (this.selectedSemester) {
                url.searchParams.set('semester_id', this.selectedSemester);
            } else {
                url.searchParams.delete('semester_id');
            }
            window.location.href = url.toString();
        },

        navigateWithFilter() {
            this.navigate(this.currentMonth, this.currentYear);
        },

        // ─────────────────────────────────────────────
        // SELEKSI HARI
        // ─────────────────────────────────────────────
        selectDay(day) {
            if (this.selectedDay?.dateStr === day.dateStr) {
                this.selectedDay = null;
            } else {
                this.selectedDay = day;
            }
        },

        // ─────────────────────────────────────────────
        // FILTER KATEGORI
        // ─────────────────────────────────────────────
        toggleCategory(key) {
            const idx = this.hiddenCategories.indexOf(key);
            if (idx === -1) {
                this.hiddenCategories.push(key);
            } else {
                this.hiddenCategories.splice(idx, 1);
            }
            // Refresh selected day agar filteredEvents ikut update
            if (this.selectedDay) {
                this.selectedDay = this.buildDay(
                    this.selectedDay.date,
                    this.selectedDay.month,
                    this.selectedDay.year,
                    this.selectedDay.isCurrentMonth
                );
            }
        },

        isCategoryVisible(key) {
            return !this.hiddenCategories.includes(key);
        },

        resetCategories() {
            this.hiddenCategories = [];
            if (this.selectedDay) {
                this.selectedDay = this.buildDay(
                    this.selectedDay.date,
                    this.selectedDay.month,
                    this.selectedDay.year,
                    this.selectedDay.isCurrentMonth
                );
            }
        },

        // ─────────────────────────────────────────────
        // MODAL EVENT
        // Tidak menggunakan __x internal API (bug di Alpine v3)
        // ─────────────────────────────────────────────
        openModal(event) {
            // Bangun label tanggal
            const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            let tanggalLabel = '';
            if (event.tanggal_mulai) {
                const start = new Date(event.tanggal_mulai + 'T00:00:00');
                tanggalLabel = `${start.getDate()} ${months[start.getMonth()]} ${start.getFullYear()}`;
                if (event.tanggal_selesai && event.tanggal_selesai !== event.tanggal_mulai) {
                    const end = new Date(event.tanggal_selesai + 'T00:00:00');
                    tanggalLabel += ` – ${end.getDate()} ${months[end.getMonth()]} ${end.getFullYear()}`;
                }
            }

            // Semester label
            const semLabel = event.semester
                ? (event.semester.tahun_ajaran + ' – ' + event.semester.nama_semester)
                : '–';

            this.selectedEvent = {
                ...event,
                tanggal_label:  tanggalLabel,
                semester_label: semLabel,
            };
            this.modalOpen = true;
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.modalOpen = false;
            this.selectedEvent = null;
            document.body.style.overflow = '';
        },

        // ─────────────────────────────────────────────
        // HELPERS
        // ─────────────────────────────────────────────
        formatTanggal(dateStr) {
            if (!dateStr) return '';
            const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            const d = new Date(dateStr + 'T00:00:00');
            return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
        },
    }));
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\cendekia\resources\views/admin/kalender-akademik/index.blade.php ENDPATH**/ ?>