<?php $__env->startSection('title', 'Tambah Agenda Kalender Akademik'); ?>
<?php $__env->startSection('activeMenu', 'Kalender Akademik'); ?>
<?php $__env->startSection('content'); ?>

<div class="space-y-6 max-w-3xl mx-auto p-3 sm:p-4">
    
    <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-slate-400 mb-4">
        <a href="<?php echo e(route('admin.kalender-akademik.index')); ?>" class="hover:text-gray-900 dark:hover:text-white transition-colors">Kalender Akademik</a>
        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
        <span class="text-gray-900 dark:text-white font-medium">Tambah Agenda</span>
    </div>

    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0 shadow-md">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        </div>
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Tambah Agenda Baru</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Buat agenda kalender akademik baru yang akan tampil untuk semua mahasiswa</p>
        </div>
    </div>

    
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
        <form action="<?php echo e(route('admin.kalender-akademik.store')); ?>" method="POST" class="divide-y divide-gray-100 dark:divide-slate-700">
            <?php echo csrf_field(); ?>

            
            <div class="p-6 space-y-5">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-bold">1</span>
                    Informasi Dasar
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <div>
                        <label for="semester_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select name="semester_id" id="semester_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 text-sm font-medium focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition appearance-none cursor-pointer"
                                style="background-image:url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 viewBox=%220 0 16 16%22><path fill=%22%236b7280%22 d=%22M4 6l4 4 4-4H4z%22/></svg>'); background-position:right 0.6rem center; background-repeat:no-repeat; padding-right:2.25rem;" required>
                            <option value="">Pilih Semester</option>
                            <?php $__currentLoopData = $semesters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sem->id); ?>" <?php echo e(old('semester_id') == $sem->id ? 'selected' : ''); ?>>
                                    <?php echo e($sem->tahun_ajaran); ?> – <?php echo e($sem->nama_semester); ?>

                                    <?php if($sem->is_active): ?> (Aktif) <?php endif; ?>
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['semester_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 dark:text-red-400 text-xs mt-1.5 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg> <?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div>
                        <label for="judul" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Judul Agenda <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" id="judul" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition"
                               value="<?php echo e(old('judul')); ?>" placeholder="Contoh: UTS Semester Ganjil" required>
                        <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 dark:text-red-400 text-xs mt-1.5 flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg> <?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div>
                    <label for="deskripsi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition resize-none"
                            placeholder="Jelaskan detail agenda ini..."><?php echo e(old('deskripsi')); ?></textarea>
                    <?php $__errorArgs = ['deskripsi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 dark:text-red-400 text-xs mt-1.5"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="p-6 space-y-5">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-bold">2</span>
                    Tanggal & Waktu
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition"
                               value="<?php echo e(old('tanggal_mulai')); ?>" required>
                        <?php $__errorArgs = ['tanggal_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 dark:text-red-400 text-xs mt-1.5"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition"
                               value="<?php echo e(old('tanggal_selesai')); ?>">
                        <?php $__errorArgs = ['tanggal_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 dark:text-red-400 text-xs mt-1.5"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="flex items-end">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_all_day" id="is_all_day" value="1" <?php echo e(old('is_all_day', true) ? 'checked' : ''); ?> 
                                   onchange="toggleWaktuInput(this)" class="w-4 h-4 rounded border-gray-300">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Sepanjang Hari</span>
                        </label>
                    </div>
                </div>

                
                <div id="waktuContainer" class="hidden grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="waktu_mulai" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" id="waktu_mulai" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition"
                               value="<?php echo e(old('waktu_mulai')); ?>">
                        <?php $__errorArgs = ['waktu_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 dark:text-red-400 text-xs mt-1.5"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label for="waktu_selesai" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Waktu Selesai</label>
                        <input type="time" name="waktu_selesai" id="waktu_selesai" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition"
                               value="<?php echo e(old('waktu_selesai')); ?>">
                        <?php $__errorArgs = ['waktu_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 dark:text-red-400 text-xs mt-1.5"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            
            <div class="p-6 space-y-5">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-bold">3</span>
                    Kategori & Lokasi
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <div>
                        <label for="jenis_kegiatan" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Jenis Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis_kegiatan" id="jenis_kegiatan" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 text-sm font-medium focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition appearance-none cursor-pointer"
                                style="background-image:url('data:image/svg+xml;utf8,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2216%22 height=%2216%22 viewBox=%220 0 16 16%22><path fill=%22%236b7280%22 d=%22M4 6l4 4 4-4H4z%22/></svg>'); background-position:right 0.6rem center; background-repeat:no-repeat; padding-right:2.25rem;" required>
                            <option value="">Pilih Jenis</option>
                            <?php $__currentLoopData = $jenisKegiatanOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e(old('jenis_kegiatan') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['jenis_kegiatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 dark:text-red-400 text-xs mt-1.5"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div>
                        <label for="lokasi" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition"
                               value="<?php echo e(old('lokasi')); ?>" placeholder="Ruang 301, Gedung A, atau Online">
                        <?php $__errorArgs = ['lokasi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-red-600 dark:text-red-400 text-xs mt-1.5"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                
                <div class="pt-3 border-t border-gray-100 dark:border-slate-700">
                    <label for="catatan" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Catatan Tambahan</label>
                    <textarea name="catatan" id="catatan" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-slate-600 bg-white dark:bg-slate-900 text-gray-800 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition resize-none"
                            placeholder="Informasi tambahan untuk keperluan admin..."><?php echo e(old('catatan')); ?></textarea>
                    <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 dark:text-red-400 text-xs mt-1.5"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div class="p-6 space-y-5">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-sm font-bold">4</span>
                    Warna & Status
                </h3>

                
                <div class="space-y-3">
                    <label for="warna" class="block text-sm font-semibold text-gray-900 dark:text-white">
                        Warna Agenda <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="warna" id="warna" class="w-14 h-14 rounded-xl cursor-pointer border-2 border-gray-200 dark:border-slate-600 transition-colors"
                               value="<?php echo e(old('warna', '#002B6B')); ?>" required>
                        <span id="warnaLabel" class="text-sm font-medium text-gray-600 dark:text-gray-400">Warna Dipilih</span>
                    </div>

                    
                    <div class="pt-2">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 font-medium">Preset Cepat:</p>
                        <div class="flex flex-wrap gap-2">
                            <?php $__currentLoopData = $warnaPresets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hex => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" class="group px-3 py-2 rounded-lg text-xs font-semibold transition-all hover:scale-105 shadow-sm"
                                        style="background-color: <?php echo e($hex); ?>; color: <?php echo e(in_array($hex, ['#F59E0B', '#EA580C', '#65A30D', '#16A34A']) ? '#000' : '#fff'); ?>;"
                                        onclick="setColor('<?php echo e($hex); ?>'); return false;"
                                        title="<?php echo e($label); ?>">
                                    <?php echo e(substr($label, 0, 6)); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <?php $__errorArgs = ['warna'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-600 dark:text-red-400 text-xs mt-1.5"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="pt-3 border-t border-gray-100 dark:border-slate-700">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_published" id="is_published" value="1" <?php echo e(old('is_published', true) ? 'checked' : ''); ?> 
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-checked:bg-green-500 rounded-full transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full peer-checked:translate-x-5 transition-transform"></div>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Publikasikan Agenda</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Agenda akan langsung terlihat di kalender mahasiswa</p>
                        </div>
                    </label>
                </div>
            </div>

            
            <div class="p-6 bg-gray-50 dark:bg-slate-700/30 flex items-center justify-between gap-3">
                <a href="<?php echo e(route('admin.kalender-akademik.index')); ?>"
                   class="px-4 py-2.5 rounded-xl font-medium text-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 rounded-xl font-semibold text-sm text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Agenda
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

<?php echo $__env->make('layouts.portal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\cendekia\resources\views/admin/kalender-akademik/create.blade.php ENDPATH**/ ?>