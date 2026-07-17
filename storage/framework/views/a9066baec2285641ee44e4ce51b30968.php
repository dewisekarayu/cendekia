
<div class="px-4 pt-3 pb-2 text-muted" style="background-color: #f8fafc;">
    <small class="fw-medium">
        <?php if($dosen->count() > 0): ?>
            Menampilkan <?php echo e($dosen->firstItem()); ?>-<?php echo e($dosen->lastItem()); ?> dari <?php echo e($dosen->total()); ?> data dosen
        <?php else: ?>
            Menampilkan 0 data dosen
        <?php endif; ?>
    </small>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; color: #64748b;">
            <tr>
                <th class="ps-4">Foto & Nama</th>
                <th>NIDN</th>
                <th>Program Studi</th>
                <th>Kontak</th>
                <th>Status</th>
                <th class="text-center pe-4" style="width: 140px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $dosen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="ps-4">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?php echo e(urlencode($item->name)); ?>" 
                                 style="width: 40px; height: 40px; border-radius: 50%; background-color: #f1f5f9;" alt="<?php echo e($item->name); ?>">
                            <div>
                                <div style="font-weight: 600; color: #1e293b;"><?php echo e($item->name); ?></div>
                                <small style="color: #64748b;"><?php echo e($item->email); ?></small>
                            </div>
                        </div>
                    </td>
                    <td class="fw-semibold text-secondary"><?php echo e($item->nip_nim); ?></td>
                    <td>
                        <span class="badge px-2.5 py-1.5 fw-semibold" style="background-color: #E6EEFF; color: #002B6B; border-radius: 6px;">
                            <?php echo e($item->programStudi?->nama_prodi ?? 'Lintas Prodi'); ?>

                        </span>
                    </td>
                    <td class="text-secondary" style="font-size: 0.9rem;">
                        <?php echo e($item->telepon ?? '+62 812-XXXX-XXXX'); ?>

                    </td>
                    <td>
                        <?php if($item->status == 'aktif'): ?>
                            <span class="badge px-2.5 py-1.5 fw-semibold" style="background-color: #d1fae5; color: #065f46; border-radius: 6px;">
                                Aktif
                            </span>
                        <?php else: ?>
                            <span class="badge px-2.5 py-1.5 fw-semibold" style="background-color: #fee2e2; color: #991b1b; border-radius: 6px;">
                                Non-Aktif
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="pe-4">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="<?php echo e(route('admin.dosen.edit', $item->id)); ?>" class="btn btn-sm btn-light border text-primary p-1.5 px-2" title="Edit" style="border-radius: 6px; color: #002B6B !important;">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.dosen.destroy', $item->id)); ?>" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data dosen ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-light border text-danger p-1.5 px-2" title="Hapus" style="border-radius: 6px;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="bi bi-people fs-2 d-block mb-2 text-black-50"></i>
                        Belum ada data dosen terdaftar.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end p-4 border-top">
    <?php echo e($dosen->links()); ?>

</div><?php /**PATH C:\laragon\www\cendekia\resources\views/admin/dosen/table.blade.php ENDPATH**/ ?>