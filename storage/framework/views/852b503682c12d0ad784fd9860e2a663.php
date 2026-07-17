
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th>KODE PRODI</th>
                <th>NAMA PROGRAM STUDI</th>
                <th>JENJANG PENDIDIKAN</th>
                <th>AKREDITASI</th>
                <th>STATUS</th>
                <th class="text-center">AKSI</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $prodiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td style="font-weight: 700; color: #002B6B;"><?php echo e($prodi->kode_prodi); ?></td>
                    <td style="font-weight: 600;"><?php echo e($prodi->nama_prodi); ?></td>
                    <td><?php echo e($prodi->jenjang); ?> - <?php echo e($prodi->jenjang === 'S1' ? 'Sarjana' : 'Program ' . $prodi->jenjang); ?></td>
                    <td>
                        <span class="badge bg-light text-dark border px-2.5 py-1.5 fw-semibold"><?php echo e($prodi->akreditasi ?? 'Baik'); ?></span>
                    </td>
                    <td>
                        <?php if($prodi->status == 1): ?>
                            <span class="badge bg-success bg-opacity-10 text-success border-0 px-2 py-1">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-danger bg-opacity-10 text-danger border-0 px-2 py-1">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="action-buttons justify-content-center">
                            <a href="<?php echo e(route('admin.program-studi.edit', $prodi->id)); ?>" class="action-btn action-btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="<?php echo e(route('admin.program-studi.destroy', $prodi->id)); ?>" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus prodi ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="action-btn action-btn-delete" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">Belum ada data program studi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div style="padding: 1.25rem 1.5rem; border-top: 1px solid rgba(0, 43, 107, 0.08);">
    <?php echo e($prodiList->links()); ?>

</div><?php /**PATH C:\laragon\www\cendekia\resources\views/admin/program-studi/table.blade.php ENDPATH**/ ?>