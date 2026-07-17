<?php $__env->startSection('title', 'Kalender Akademik'); ?>
<?php $__env->startSection('activeMenu', 'Kalender Akademik'); ?>
<?php $__env->startSection('content'); ?>

<style>[x-cloak]{ display:none !important; }</style>

<div class="space-y-6 max-w-7xl mx-auto p-3 sm:p-4" x-data="kalenderData()">

    
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
        <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap sm:flex-nowrap w-full sm:w-auto">
            <a href="<?php echo e(route('admin.kalender-akademik.create')); ?>"
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#002B6B] hover:bg-[#003a88] text-white rounded-xl font-medium text-sm transition-all duration-200 shadow-md hover:shadow-lg flex-1 sm:flex-initial justify-center">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Tambah Agenda</span>
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

                        
                        <div class="flex items-center justify-between sm:justify-start gap-2">
                            <div class="flex items-center gap-1">
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
                            </div>

                            <button @click="goToToday()"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-colors text-sm font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span class="hidden sm:inline">Hari Ini</span>
                            </button>
                        </div>

                        
                        <div class="w-full sm:w-auto relative flex items-center">
                            <select x-model="selectedSemester"
                                @change="navigateWithFilter()"
                                class="w-full sm:min-w-[260px] pl-3 pr-7 py-2 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 text-sm font-medium text-gray-800 dark:text-gray-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition appearance-none cursor-pointer">
                                <option value="">Semua Semester</option>
                                <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sem->id); ?>">
                                        <?php echo e($sem->tahun_ajaran); ?> – <?php echo e($sem->nama_semester); ?> <?php if($sem->is_active): ?>(Aktif)<?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    
                    <div class="mt-4 flex flex-col gap-2 bg-gray-50 dark:bg-slate-900/50 p-3.5 rounded-xl border border-gray-100 dark:border-slate-700">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-gray-500 dark:text-slate-400 tracking-wide uppercase flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                Filter Jenis Agenda:
                            </span>
                            <button type="button" @click="resetCategories()"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/30 transition-colors cursor-pointer">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Tampilkan Semua
                            </button>
                        </div>
                        
                        <div class="flex flex-wrap gap-2 pt-1">
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
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold transition-all duration-200 cursor-pointer border shadow-sm select-none"
                                        :style="isCategoryVisible('<?php echo e($key); ?>')
                                            ? 'background-color: <?php echo e($info['color']); ?>10; color: <?php echo e($info['color']); ?>; border-color: <?php echo e($info['color']); ?>30;'
                                            : 'background-color: #f3f4f6; color: #9ca3af; border-color: #e5e7eb; opacity: 0.6; text-decoration: line-through;'">
                                    <span class="w-1.5 h-1.5 rounded-full flex-shrink-0" 
                                        :style="isCategoryVisible('<?php echo e($key); ?>') ? 'background-color: <?php echo e($info['color']); ?>' : 'background-color: #9ca3af'"></span>
                                    <?php echo e($info['label']); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                
                <div class="p-3 sm:p-4">
                    
                    <div class="grid grid-cols-7 mb-1 bg-gray-50 dark:bg-slate-900/40 rounded-xl">
                        <?php $__currentLoopData = ['Min','Sen','Sel','Rab','Kam','Jum','Sab']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="text-center py-2 text-[11px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-wider"><?php echo e($d); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div class="grid grid-cols-7 gap-1">
                        <template x-for="(day, idx) in calendarDays" :key="idx">
                            <div class="relative rounded-xl cursor-pointer select-none transition-all duration-150 border flex flex-col justify-between"
                                :class="{
                                    'bg-blue-50/70 dark:bg-blue-950/30 border-blue-200 dark:border-blue-900 ring-1 ring-blue-300 dark:ring-blue-800': day.isToday && !day.isSelected,
                                    'bg-blue-600 border-blue-600 shadow-md ring-2 ring-blue-400 text-white': day.isSelected,
                                    'hover:bg-gray-50 dark:hover:bg-slate-700/50 bg-white dark:bg-slate-800 border-gray-100 dark:border-slate-700': !day.isToday && !day.isSelected,
                                    'opacity-35': !day.isCurrentMonth,
                                }"
                                style="min-height:95px;"
                                @click="selectDay(day)"
                                @keydown.enter.prevent="selectDay(day)"
                                @keydown.space.prevent="selectDay(day)"
                                tabindex="0">

                                
                                <div class="p-2 pb-0 flex justify-between items-start">
                                    <span class="text-xs font-bold tracking-tight"
                                          :class="day.isSelected ? 'text-white' : (day.isToday ? 'text-blue-600 dark:text-blue-400' : (day.isCurrentMonth ? 'text-gray-800 dark:text-gray-200' : 'text-gray-400 dark:text-gray-600'))"
                                          x-text="day.date"></span>
                                    <span x-show="day.filteredEvents.length > 0"
                                          class="text-[9px] px-1 py-0.2 rounded font-black"
                                          :class="day.isSelected ? 'bg-blue-700 text-blue-100' : 'bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-gray-400'"
                                          x-text="day.filteredEvents.length"></span>
                                </div>

                                
                                <div class="px-1.5 pb-1.5 pt-1 space-y-1 overflow-hidden mt-auto">
                                    <template x-for="event in day.filteredEvents.slice(0, 2)" :key="event.id">
                                        <div class="px-1.5 py-0.5 rounded-md text-[9px] sm:text-[10px] font-medium truncate border"
                                             :style="day.isSelected 
                                                ? 'background-color: rgba(255,255,255,0.15); color: #fff; border-color: transparent;' 
                                                : 'background-color:' + event.warna + '12; color:' + event.warna + '; border-color:' + event.warna + '30;'"
                                             @click.stop="openModal(event)"
                                             :title="event.judul">
                                            <span x-text="event.judul"></span>
                                        </div>
                                    </template>
                                    <div x-show="day.filteredEvents.length > 2"
                                         class="px-1 py-0.5 rounded-md text-[9px] font-bold text-center"
                                         :class="day.isSelected ? 'bg-blue-700/50 text-blue-200' : 'bg-gray-50 dark:bg-slate-900/60 text-gray-400 dark:text-gray-500'"
                                         x-text="'+' + (day.filteredEvents.length - 2) + ' agenda'"></div>
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
                          x-text="calendarDays.find(d => d.isToday)?.filteredEvents.length || 0" x-text-suffix=" agenda"> Agenda</span>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-slate-700/50">
                    <div x-show="!calendarDays.find(d => d.isToday) || calendarDays.find(d => d.isToday)?.filteredEvents.length === 0" class="p-8 text-center" x-cloak>
                        <svg class="w-10 h-10 mx-auto text-gray-200 dark:text-gray-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-gray-400 dark:text-gray-500 text-sm">Tidak ada agenda aktif untuk hari ini.</p>
                    </div>
                    
                    <template x-for="event in (calendarDays.find(d => d.isToday)?.filteredEvents || [])" :key="event.id">
                        <div class="flex items-start gap-3 p-4 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors cursor-pointer"
                             :style="'border-left: 4px solid ' + event.warna"
                             @click="openModal(event)">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0" :style="'background-color:' + event.warna + '15'">
                                <svg class="w-4 h-4" :style="'color:' + event.warna" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white text-sm truncate" x-text="event.judul"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span x-text="formatTanggal(event.tanggal_mulai)"></span>
                                </p>
                            </div>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold flex-shrink-0"
                                  :style="'background-color:' + event.warna + '15; color:' + event.warna"
                                  x-text="event.jenis_kegiatan.toUpperCase()"></span>
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
                <div class="divide-y divide-gray-50 dark:divide-slate-700/50 max-h-64 overflow-y-auto">
                    <?php $__currentLoopData = $historyEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-start gap-3 p-4 opacity-70 hover:opacity-100 transition-all duration-150"
                         style="border-left: 4px solid <?php echo e($event->warna); ?>">
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
                            class="text-blue-500 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-200 transition-colors p-1 rounded-lg">
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
                             :style="'border-left: 4px solid ' + event.warna"
                             @click="openModal(event)">
                            <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate" x-text="event.judul"></p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5" x-text="formatTanggal(event.tanggal_mulai)"></p>
                            <span class="inline-block mt-1 px-1.5 py-0.5 rounded-full text-[9px] font-bold"
                                  :style="'background-color:' + event.warna + '15; color:' + event.warna"
                                  x-text="event.jenis_kegiatan.toUpperCase()"></span>
                        </div>
                    </template>
                </div>
            </div>

            
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 dark:border-slate-700 flex items-center gap-3 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/10 dark:to-teal-900/10">
                    <div class="w-9 h-9 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm">Agenda Mendatang</h3>
                    <span class="ml-auto text-xs px-2 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-300 font-bold" x-text="upcomingEvents.length"></span>
                </div>
                <div class="divide-y divide-gray-50 dark:divide-slate-700/50 max-h-[380px] overflow-y-auto">
                    <template x-if="upcomingEvents.length === 0">
                        <div class="p-6 text-center">
                            <p class="text-gray-400 dark:text-gray-500 text-sm">Tidak ada agenda mendatang</p>
                        </div>
                    </template>
                    <template x-for="event in upcomingEvents" :key="event.id">
                        <div class="p-3.5 hover:bg-gray-50 dark:hover:bg-slate-700/30 transition-colors cursor-pointer group"
                             :style="'border-left: 4px solid ' + event.warna"
                             @click="openModal(event)">
                            <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" x-text="event.judul"></p>
                            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 flex items-center gap-1">
                                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span x-text="event.tanggal_mulai_formatted"></span>
                            </p>
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
                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-gray-50 dark:bg-slate-900/50">
                        <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Total Agenda</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white"><?php echo e($totalAgenda); ?></span>
                    </div>
                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-red-50 dark:bg-red-950/20">
                        <span class="text-xs font-medium text-red-700 dark:text-red-400">UTS & UAS</span>
                        <span class="text-sm font-bold text-red-700 dark:text-red-400"><?php echo e($totalExam); ?></span>
                    </div>
                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-green-50 dark:bg-green-950/20">
                        <span class="text-xs font-medium text-green-700 dark:text-green-400">Libur & Cuti</span>
                        <span class="text-sm font-bold text-green-700 dark:text-green-400"><?php echo e($totalLibur); ?></span>
                    </div>
                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-orange-50 dark:bg-orange-950/20">
                        <span class="text-xs font-medium text-orange-700 dark:text-orange-400">Deadline</span>
                        <span class="text-sm font-bold text-orange-700 dark:text-orange-400"><?php echo e($totalDeadline); ?></span>
                    </div>
                    <div class="flex justify-between items-center p-2.5 rounded-xl bg-purple-50 dark:bg-purple-950/20">
                        <span class="text-xs font-medium text-purple-700 dark:text-purple-400">Lainnya</span>
                        <span class="text-sm font-bold text-purple-700 dark:text-purple-400"><?php echo e(max(0,$totalLain)); ?></span>
                    </div>
                </div>
            </div>

            
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-950/30 dark:to-blue-950/30 rounded-2xl border border-indigo-100/50 dark:border-indigo-900/50 p-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-indigo-700 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-indigo-900 dark:text-indigo-300 text-xs mb-1.5">Tips Navigasi</p>
                        <ul class="text-[11px] text-indigo-700 dark:text-indigo-400 space-y-1 list-disc list-inside">
                            <li>Klik kotak tanggal untuk filter sidebar.</li>
                            <li>Klik badge kegiatan untuk memunculkan detail info modal.</li>
                            <li>Gunakan filter di atas untuk menyembunyikan jenis agenda.</li>
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
    
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closeModal()"></div>

    
    <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-hidden z-10 border border-gray-100 dark:border-slate-700 transform transition-all"
         x-show="modalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95">

        
        <div class="flex items-center gap-3 p-5 border-b border-gray-100 dark:border-slate-700" :style="'border-top: 4px solid ' + (selectedEvent?.warna ?? '#3b82f6')">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm"
                 :style="'background-color:' + (selectedEvent?.warna ?? '#3b82f6') + '20'">
                <svg class="w-5 h-5" :style="'color:' + (selectedEvent?.warna ?? '#3b82f6')" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-bold text-gray-900 dark:text-white text-base truncate" x-text="selectedEvent?.judul ?? ''"></h3>
                <span class="text-[10px] uppercase tracking-wider font-extrabold" :style="'color:' + selectedEvent?.warna" x-text="selectedEvent?.jenis_kegiatan"></span>
            </div>
            <button @click="closeModal()"
                    class="p-2 rounded-xl text-gray-400 hover:bg-gray-100 dark:hover:bg-slate-700 hover:text-gray-600 dark:hover:text-gray-200 transition-colors flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        
        <div class="p-5 space-y-4 overflow-y-auto max-h-[calc(90vh-140px)] text-sm">
            
            <div class="flex items-start gap-3 text-gray-700 dark:text-gray-300">
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <div>
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">Waktu Pelaksanaan</p>
                    <p class="mt-0.5 font-medium text-gray-900 dark:text-white" x-text="selectedEvent?.tanggal_label ?? ''"></p>
                </div>
            </div>

            
            <div class="flex items-start gap-3 text-gray-700 dark:text-gray-300" x-show="selectedEvent?.lokasi">
                <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                <div>
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">Lokasi / Tempat</p>
                    <p class="mt-0.5 text-gray-900 dark:text-white" x-text="selectedEvent?.lokasi ?? ''"></p>
                </div>
            </div>

            
            <div class="pt-3 border-t border-gray-100 dark:border-slate-700" x-show="selectedEvent?.deskripsi">
                <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase mb-1.5">Deskripsi Detail</p>
                <p class="text-gray-600 dark:text-slate-300 whitespace-pre-wrap leading-relaxed bg-gray-50 dark:bg-slate-900/50 p-3 rounded-xl border border-gray-100 dark:border-slate-700" x-text="selectedEvent?.deskripsi ?? ''"></p>
            </div>

            
            <div class="pt-3 border-t border-gray-100 dark:border-slate-700 grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">Semester</p>
                    <p class="text-xs font-semibold text-gray-800 dark:text-gray-200 mt-0.5" x-text="selectedEvent?.semester_label ?? '–'"></p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">Status Sistem</p>
                    <span class="inline-flex mt-0.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-green-50 dark:bg-green-950/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-900">
                        Terjadwal
                    </span>
                </div>
            </div>
        </div>

        
        <div class="p-4 border-t border-gray-100 dark:border-slate-700 bg-gray-50 dark:bg-slate-900/30 flex justify-end gap-2">
            <template x-if="selectedEvent?.id">
                <a :href="'/admin/kalender-akademik/' + selectedEvent.id + '/edit'" 
                   class="px-4 py-2 bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/30 dark:hover:bg-amber-900/50 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-900 rounded-xl font-semibold text-xs transition-colors inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M18.364 4.982a2.322 2.322 0 013.284 3.284L12 17.414l-4 1 1-4 9.364-9.364z"/></svg>
                    Edit Data
                </a>
            </template>
            <button @click="closeModal()"
                    class="py-2 px-4 rounded-xl font-semibold text-xs text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-700 hover:bg-gray-100 dark:hover:bg-slate-600 border border-gray-200 dark:border-slate-600 transition-colors shadow-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function kalenderData() {
    return {
        currentMonth: <?php echo e($selectedMonth); ?>,
        currentYear:  <?php echo e($selectedYear); ?>,
        eventsByDate:   <?php echo json_encode($eventsByDate, 15, 512) ?>,
        todaysEvents:   <?php echo json_encode($todaysEvents, 15, 512) ?>,
        upcomingEvents: <?php echo json_encode($upcomingEvents, 15, 512) ?>,
        hiddenCategories: [],
        selectedDay: null,
        selectedEvent: null,
        modalOpen: false,
        selectedSemester: '<?php echo e($selectedSemesterId ?? ""); ?>',

        init() {
            // Menghilangkan duplikasi berdasarkan judul dan tanggal untuk Agenda Mendatang (Sidebar)
            const seenUpcoming = new Set();
            this.upcomingEvents = this.upcomingEvents.filter(e => {
                const duplicateKey = `${e.judul}-${e.tanggal_mulai}`;
                if (seenUpcoming.has(duplicateKey)) {
                    return false;
                }
                seenUpcoming.add(duplicateKey);
                return true;
            }).map(e => ({
                ...e,
                tanggal_mulai_formatted: this.formatTanggal(e.tanggal_mulai),
            }));
        },

        get calendarDays() {
            const year = this.currentYear, month = this.currentMonth;
            const firstDay = new Date(year, month - 1, 1);
            const lastDay = new Date(year, month, 0);
            const startWeekday = firstDay.getDay();
            const daysInMonth = lastDay.getDate();
            const prevLastDay = new Date(year, month - 1, 0).getDate();
            const days = [];
            for (let i = startWeekday - 1; i >= 0; i--) {
                const d = prevLastDay - i;
                const m = month === 1 ? 12 : month - 1;
                const y = month === 1 ? year - 1 : year;
                days.push(this.buildDay(d, m, y, false));
            }
            for (let d = 1; d <= daysInMonth; d++) days.push(this.buildDay(d, month, year, true));
            let nextDay = 1;
            while (days.length % 7 !== 0) {
                const m = month === 12 ? 1 : month + 1;
                const y = month === 12 ? year + 1 : year;
                days.push(this.buildDay(nextDay++, m, y, false));
            }
            return days;
        },

        buildDay(date, month, year, isCurrentMonth) {
            const pad = n => String(n).padStart(2, '0');
            const dateStr = `${year}-${pad(month)}-${pad(date)}`;
            const todayStr = new Date().toISOString().split('T')[0];
            const rawEvents = this.eventsByDate[dateStr] ?? [];
            
            // 1. Filter kategori yang disembunyikan
            let filtered = rawEvents.filter(e =>
                this.hiddenCategories.length === 0 || !this.hiddenCategories.includes(e.jenis_kegiatan)
            );

            // 2. PROTEKSI DUPLIKAT: Menyaring agar agenda dengan judul yang sama di hari yang sama hanya muncul 1 kali
            const seenTitles = new Set();
            filtered = filtered.filter(e => {
                if (seenTitles.has(e.judul)) {
                    return false;
                }
                seenTitles.add(e.judul);
                return true;
            });

            return {
                date, month, year, dateStr, isCurrentMonth,
                isToday: dateStr === todayStr,
                isSelected: this.selectedDay?.dateStr === dateStr,
                events: rawEvents,
                filteredEvents: filtered, // Hasil akhir yang bersih dari duplikasi
            };
        },

        get currentMonthYear() {
            const months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
            return `${months[this.currentMonth - 1]} ${this.currentYear}`;
        },

        get selectedDayLabel() {
            if (!this.selectedDay) return '';
            const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
            const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            const d = new Date(this.selectedDay.year, this.selectedDay.month - 1, this.selectedDay.date);
            return `${days[d.getDay()]}, ${this.selectedDay.date} ${months[this.selectedDay.month - 1]} ${this.selectedDay.year}`;
        },

        prevMonth() { let m = this.currentMonth - 1, y = this.currentYear; if (m < 1) { m = 12; y--; } this.navigate(m, y); },
        nextMonth() { let m = this.currentMonth + 1, y = this.currentYear; if (m > 12) { m = 1; y++; } this.navigate(m, y); },
        goToToday() { const t = new Date(); this.navigate(t.getMonth() + 1, t.getFullYear()); },

        navigate(month, year) {
            const url = new URL(window.location.href);
            url.searchParams.set('month', month);
            url.searchParams.set('year', year);
            if (this.selectedSemester) url.searchParams.set('semester_id', this.selectedSemester);
            else url.searchParams.delete('semester_id');
            window.location.href = url.toString();
        },
        navigateWithFilter() { this.navigate(this.currentMonth, this.currentYear); },

        selectDay(day) {
            this.selectedDay = (this.selectedDay?.dateStr === day.dateStr) ? null : day;
        },

        toggleCategory(key) {
            const idx = this.hiddenCategories.indexOf(key);
            if (idx === -1) this.hiddenCategories.push(key); else this.hiddenCategories.splice(idx, 1);
            if (this.selectedDay) this.selectedDay = this.buildDay(this.selectedDay.date, this.selectedDay.month, this.selectedDay.year, this.selectedDay.isCurrentMonth);
        },
        isCategoryVisible(key) { return !this.hiddenCategories.includes(key); },
        resetCategories() {
            this.hiddenCategories = [];
            if (this.selectedDay) this.selectedDay = this.buildDay(this.selectedDay.date, this.selectedDay.month, this.selectedDay.year, this.selectedDay.isCurrentMonth);
        },

        openModal(event) {
            const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            let tanggalLabel = '';
            if (event.tanggal_mulai) {
                const start = new Date(event.tanggal_mulai.split('T')[0] + 'T00:00:00');
                tanggalLabel = `${start.getDate()} ${months[start.getMonth()]} ${start.getFullYear()}`;
                if (event.tanggal_selesai && event.tanggal_selesai !== event.tanggal_mulai) {
                    const end = new Date(event.tanggal_selesai.split('T')[0] + 'T00:00:00');
                    tanggalLabel += ` – ${end.getDate()} ${months[end.getMonth()]} ${end.getFullYear()}`;
                }
            }
            const semLabel = event.semester ? (event.semester.tahun_ajaran + ' – ' + event.semester.nama_semester) : '–';
            this.selectedEvent = { ...event, tanggal_label: tanggalLabel, semester_label: semLabel };
            this.modalOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeModal() { this.modalOpen = false; this.selectedEvent = null; document.body.style.overflow = ''; },

        formatTanggal(dateStr) {
            if (!dateStr) return '';
            const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
            const datePart = dateStr.split('T')[0];
            const d = new Date(datePart + 'T00:00:00');
            return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
        },
    };
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\cendekia\resources\views/admin/kalender-akademik/index.blade.php ENDPATH**/ ?>