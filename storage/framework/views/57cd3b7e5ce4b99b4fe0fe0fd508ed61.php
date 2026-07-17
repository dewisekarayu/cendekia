<?php $__env->startSection('title', 'Tambah Agenda Kalender Akademik'); ?>
<?php $__env->startSection('activeMenu', 'Kalender Akademik'); ?>
<?php $__env->startSection('content'); ?>

<div class="space-y-8 max-w-4xl mx-auto p-4 sm:p-6 mb-12">
    
    <nav class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-400 dark:text-slate-500">
        <a href="<?php echo e(route('admin.kalender-akademik.index')); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Kalender Academic</a>
        <svg class="w-3 h-3 flex-shrink-0 text-gray-300 dark:text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        <span class="text-gray-600 dark:text-slate-300">Tambah Agenda</span>
    </nav>

    
    <div class="relative bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 rounded-3xl p-6 md:p-8 text-white shadow-xl overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 relative z-10">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center flex-shrink-0 shadow-inner">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-100 to-slate-300">Tambah Agenda Baru</h1>
                    <p class="text-sm text-slate-400 mt-1 max-w-xl">Konfigurasikan detail agenda akademik. Data yang Anda publikasikan akan langsung tersinkronisasi ke dashboard mahasiswa.</p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-gray-100 dark:border-slate-700/80 overflow-hidden transition-all duration-300 hover:shadow-md">
        <form action="<?php echo e(route('admin.kalender-akademik.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>

            <div class="p-6 md:p-8 space-y-8 divide-y divide-gray-100 dark:divide-slate-700/60">
                
                
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 text-sm font-bold shadow-sm">1</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Informasi Dasar</h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400">Tentukan penempatan semester dan nama utama dari agenda kegiatan</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <div class="md:col-span-1">
                            <label for="semester_id" class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Semester <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <select name="semester_id" id="semester_id" class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-gray-800 dark:text-slate-100 text-sm font-medium focus:border-blue-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-blue-500/10 outline-none transition appearance-none cursor-pointer"  required>
                                    <option value="">Pilih Semester</option>
                                    <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($sem->id); ?>" <?php echo e(old('semester_id') == $sem->id ? 'selected' : ''); ?>>
                                            <?php echo e($sem->tahun_ajaran); ?> – <?php echo e($sem->nama_semester); ?> <?php if($sem->is_active): ?> (Aktif) <?php endif; ?>
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="absolute right-3.5 pointer-events-none flex items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </div>
                            </div>
                            <?php $__errorArgs = ['semester_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><span class="w-1 h-1 rounded-full bg-red-500"></span> <?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="md:col-span-2">
                            <label for="judul" class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Judul Agenda <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="judul" id="judul" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-gray-800 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 text-sm font-medium focus:border-blue-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-blue-500/10 outline-none transition"
                                   value="<?php echo e(old('judul')); ?>" placeholder="Contoh: Ujian Tengah Semester (UTS)" required>
                            <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><span class="w-1 h-1 rounded-full bg-red-500"></span> <?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    
                    <div>
                        <label for="deskripsi" class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-2">Deskripsi Detail</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-gray-800 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 text-sm focus:border-blue-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-blue-500/10 outline-none transition resize-none"
                                placeholder="Tuliskan petunjuk, syarat, atau deskripsi tambahan terkait agenda ini..."><?php echo e(old('deskripsi')); ?></textarea>
                        <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1.5"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="pt-8 space-y-6">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 text-sm font-bold shadow-sm">2</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Tanggal & Waktu Pelaksanaan</h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400">Atur batasan waktu pengerjaan atau durasi jalannya agenda</p>
                        </div>
                    </div>

                    <div class="bg-slate-50 dark:bg-slate-900/60 rounded-2xl p-5 border border-gray-100 dark:border-slate-700/40 space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            
                            <div>
                                <label for="tanggal_mulai" class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition font-medium"
                                       value="<?php echo e(old('tanggal_mulai')); ?>" required>
                                <?php $__errorArgs = ['tanggal_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1.5"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div>
                                <label for="tanggal_selesai" class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition font-medium"
                                       value="<?php echo e(old('tanggal_selesai')); ?>">
                                <?php $__errorArgs = ['tanggal_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1.5"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="flex items-center pt-4 sm:pt-0 sm:h-full">
                                <label class="flex items-center gap-3 cursor-pointer group select-none">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="is_all_day" id="is_all_day" value="1" <?php echo e(old('is_all_day', true) ? 'checked' : ''); ?> 
                                               onchange="toggleWaktuInput(this)" class="w-5 h-5 rounded-md border-gray-300 dark:border-slate-600 text-blue-600 focus:ring-blue-500/20 dark:bg-slate-800 transition cursor-pointer">
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Sepanjang Hari</span>
                                </label>
                            </div>
                        </div>

                        
                        <div id="waktuContainer" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-5 pt-3 border-t border-dashed border-gray-200 dark:border-slate-700">
                            <div>
                                <label for="waktu_mulai" class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Jam Mulai</label>
                                <input type="time" name="waktu_mulai" id="waktu_mulai" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition"
                                       value="<?php echo e(old('waktu_mulai')); ?>">
                                <?php $__errorArgs = ['waktu_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1.5"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="waktu_selesai" class="block text-xs font-bold text-gray-600 dark:text-slate-400 uppercase tracking-wider mb-2">Jam Selesai</label>
                                <input type="time" name="waktu_selesai" id="waktu_selesai" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 outline-none transition"
                                       value="<?php echo e(old('waktu_selesai')); ?>">
                                <?php $__errorArgs = ['waktu_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="text-red-500 text-xs mt-1.5"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="pt-8 space-y-6">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 text-sm font-bold shadow-sm">3</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Kategori & Lokasi Kegiatan</h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400">Kelompokkan tipe agenda beserta ruangan tempat dilangsungkan</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label for="jenis_kegiatan" class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                Jenis Kegiatan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <select name="jenis_kegiatan" id="jenis_kegiatan" class="w-full pl-4 pr-10 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-gray-800 dark:text-gray-100 text-sm font-medium focus:border-blue-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-blue-500/10 outline-none transition appearance-none cursor-pointer" required>
                                    <option value="">Pilih Jenis</option>
                                    <?php $__currentLoopData = $jenisKegiatanOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('jenis_kegiatan') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="absolute right-3.5 pointer-events-none flex items-center justify-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                </div>
                            </div>
                            <?php $__errorArgs = ['jenis_kegiatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1.5"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div>
                            <label for="lokasi" class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-2">Lokasi / Ruangan</label>
                            <input type="text" name="lokasi" id="lokasi" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-slate-500 text-sm font-medium focus:border-blue-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-blue-500/10 outline-none transition"
                                   value="<?php echo e(old('lokasi')); ?>" placeholder="Misal: Gedung B Ruang 302 / Zoom Meeting">
                            <?php $__errorArgs = ['lokasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1.5"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    
                    <div>
                        <label for="catatan" class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider mb-2">Catatan Internal Admin</label>
                        <textarea name="catatan" id="catatan" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-slate-500 text-sm focus:border-blue-500 focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-blue-500/10 outline-none transition resize-none"
                                placeholder="Tuliskan catatan rahasia atau log perubahan antar administrator jika diperlukan..."><?php echo e(old('catatan')); ?></textarea>
                        <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-500 text-xs mt-1.5"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="pt-8 space-y-6">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 text-sm font-bold shadow-sm">4</span>
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white">Warna Visual & Status Publikasi</h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400">Atur skema pewarnaan pada kalender visual sistem</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        <div class="md:col-span-1 space-y-3">
                            <label for="warna" class="block text-xs font-bold text-gray-700 dark:text-slate-300 uppercase tracking-wider">
                                Warna Identitas <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center gap-3 bg-slate-50 dark:bg-slate-900 p-2.5 rounded-2xl border border-gray-100 dark:border-slate-700/60">
                                <input type="color" name="warna" id="warna" class="w-12 h-12 rounded-xl cursor-pointer border-0 bg-transparent transition-transform transform hover:scale-105"
                                       value="<?php echo e(old('warna', '#002B6B')); ?>" required>
                                <div class="flex flex-col">
                                    <span id="warnaLabel" class="text-sm font-bold tracking-wider text-gray-800 dark:text-slate-200">#002B6B</span>
                                    <span class="text-[10px] text-gray-400 font-semibold uppercase">Hex Code</span>
                                </div>
                            </div>
                        </div>

                        
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Pilihan Warna Cepat</label>
                            <div class="flex flex-wrap gap-2.5">
                                <?php $__currentLoopData = $warnaPresets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hex => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button type="button" class="group px-3 py-2 rounded-xl text-xs font-bold transition-all hover:scale-105 active:scale-95 shadow-sm border border-black/5 flex items-center gap-1.5"
                                            style="background-color: <?php echo e($hex); ?>; color: <?php echo e(in_array($hex, ['#F59E0B', '#EA580C', '#65A30D', '#16A34A']) ? '#1e293b' : '#ffffff'); ?>;"
                                            onclick="setColor('<?php echo e($hex); ?>'); return false;"
                                            title="<?php echo e($label); ?>">
                                        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-60"></span>
                                        <?php echo e(str_replace('Warna ', '', $label)); ?>

                                    </button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php $__errorArgs = ['warna'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-red-500 text-xs mt-1.5"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    
                    <div class="pt-5 border-t border-gray-100 dark:border-slate-700/60">
                        <label class="flex items-start gap-4 cursor-pointer group select-none">
                            <div class="relative pt-0.5">
                                <input type="checkbox" name="is_published" id="is_published" value="1" <?php echo e(old('is_published', true) ? 'checked' : ''); ?> class="sr-only peer">
                                <div class="w-12 h-6.5 bg-gray-200 dark:bg-slate-700 peer-checked:bg-gradient-to-r peer-checked:from-emerald-500 peer-checked:to-teal-500 rounded-full transition-all duration-300 shadow-inner"></div>
                                <div class="absolute top-0.5 left-0.5 w-5.5 h-5.5 bg-white rounded-full shadow-md peer-checked:translate-x-5.5 transition-transform duration-300 flex items-center justify-center">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300 peer-checked:bg-emerald-500 transition-colors"></span>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Publikasikan Agenda Secara Live</p>
                                <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Aktifkan untuk langsung menampilkan informasi agenda ini di halaman kalender portal mahasiswa & dosen.</p>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            
            <div class="px-6 py-5 md:px-8 bg-gray-50 dark:bg-slate-900/40 border-t border-gray-100 dark:border-slate-700/60 flex items-center justify-end gap-3.5">
                <a href="<?php echo e(route('admin.kalender-akademik.index')); ?>"
                   class="px-5 py-2.5 rounded-xl font-bold text-sm text-gray-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 hover:text-gray-900 dark:hover:text-white transition-all active:scale-98">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl font-bold text-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 active:scale-98 transition-all shadow-md hover:shadow-lg shadow-blue-500/10 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Simpan Agenda
                </button>
            </div>
        </form>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function setColor(hex) {
        document.getElementById('warna').value = hex;
        updateWarnaLabel();
    }

    function updateWarnaLabel() {
        const color = document.getElementById('warna').value;
        const label = document.getElementById('warnaLabel');
        label.textContent = color.toUpperCase();
        label.style.color = color;
    }

    function toggleWaktuInput(checkbox) {
        const container = document.getElementById('waktuContainer');
        const mulai = document.getElementById('waktu_mulai');
        const selesai = document.getElementById('waktu_selesai');
        
        if (checkbox.checked) {
            container.classList.add('hidden');
            mulai.required = false;
            selesai.required = false;
        } else {
            container.classList.remove('hidden');
            mulai.required = true;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        updateWarnaLabel();
        toggleWaktuInput(document.getElementById('is_all_day'));
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\cendekia\resources\views/admin/kalender-akademik/create.blade.php ENDPATH**/ ?>