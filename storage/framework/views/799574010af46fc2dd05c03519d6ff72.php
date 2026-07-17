
<div class="px-4 pt-3 pb-2 text-muted" style="background-color: #f8fafc;">
    <small class="fw-medium">Menampilkan <?php echo e($mahasiswa->firstItem() ?? 0); ?>-<?php echo e($mahasiswa->lastItem() ?? 0); ?> dari <?php echo e($mahasiswa->total()); ?> data mahasiswa</small>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead>
            <tr>
                <th style="font-weight: 700; color: #475569; font-size: 0.75rem; padding: 1.25rem 1.5rem;">NIM</th>
                <th style="font-weight: 700; color: #475569; font-size: 0.75rem; padding: 1.25rem 1.5rem;">NAMA LENGKAP</th>
                <th style="font-weight: 700; color: #475569; font-size: 0.75rem; padding: 1.25rem 1.5rem;">PROGRAM STUDI</th>
                <th style="font-weight: 700; color: #475569; font-size: 0.75rem; padding: 1.25rem 1.5rem; text-align: center;">STATUS</th>
                <th style="font-weight: 700; color: #475569; font-size: 0.75rem; padding: 1.25rem 1.5rem; text-align: center;">AKSI</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $mahasiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $statusValue = $item->status ?? 'aktif';
                    $statusMap = [
                        'aktif'     => ['label' => 'AKTIF', 'bg' => '#d1fae5', 'color' => '#065f46'],
                        'cuti'      => ['label' => 'CUTI', 'bg' => '#fef3c7', 'color' => '#92400e'],
                        'non_aktif' => ['label' => 'NON-AKTIF', 'bg' => '#fee2e2', 'color' => '#991b1b'],
                    ];
                    $statusInfo = $statusMap[$statusValue] ?? $statusMap['aktif'];
                ?>
                <tr>
                    <td style="padding: 1.25rem 1.5rem; font-weight: 600; color: #002B6B;"><?php echo e($item->nip_nim); ?></td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?php echo e($item->foto ? asset('storage/' . $item->foto) : 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . urlencode($item->name)); ?>"
                                 style="width: 36px; height: 36px; border-radius: 50%; border: 1.5px solid #cbd5e1; padding: 1px; object-fit: cover;" alt="<?php echo e($item->name); ?>">
                            <div>
                                <div style="font-weight: 600; color: #334155;"><?php echo e($item->name); ?></div>
                                <small style="color: #64748b; font-size: 0.8rem;"><?php echo e($item->email); ?></small>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; color: #475569;"><?php echo e($item->programStudi?->nama_prodi ?? 'Belum memilih prodi'); ?></td>
                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                        <span class="badge badge-status" style="background-color: <?php echo e($statusInfo['bg']); ?>; color: <?php echo e($statusInfo['color']); ?>; border-radius: 6px; padding: 0.4rem 0.75rem; font-weight: 600;"><?php echo e($statusInfo['label']); ?></span>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                        <div class="d-flex justify-content-center gap-2">
                            <a href="<?php echo e(route('admin.mahasiswa.edit', $item->id)); ?>" class="action-btn action-btn-edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="<?php echo e(route('admin.mahasiswa.destroy', $item->id)); ?>" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini?')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="action-btn action-btn-delete" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-5">Belum ada data mahasiswa.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div style="padding: 1.5rem; border-top: 1px solid rgba(0, 43, 107, 0.08);">
    <?php echo e($mahasiswa->links()); ?>

</div><?php /**PATH C:\laragon\www\cendekia\resources\views/admin/mahasiswa/table.blade.php ENDPATH**/ ?>