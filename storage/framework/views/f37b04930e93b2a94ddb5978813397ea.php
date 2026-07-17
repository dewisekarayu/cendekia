<?php if (isset($component)) { $__componentOriginale0f1cdd055772eb1d4a99981c240763e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale0f1cdd055772eb1d4a99981c240763e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="container-fluid">
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>" style="color: #002B6B; font-weight: 600; text-decoration: none;">Master Data</a></li>
                        <li class="breadcrumb-item active" aria-current="page" style="color: #6b7280;">Manajemen Mahasiswa</li>
                    </ol>
                </nav>
                <h1 class="page-title mb-0" style="font-size: 1.75rem; font-weight: 700; color: #002B6B;">Manajemen Mahasiswa</h1>
                <p class="mb-0 mt-1 text-muted" style="font-size: 0.9rem;">Kelola data mahasiswa aktif dan status akademiknya.</p>
            </div>
            <a href="<?php echo e(route('admin.mahasiswa.create')); ?>" class="btn btn-primary px-4 py-2" style="border-radius: 0.5rem; background-color: #002B6B; border: none; font-weight: 600; box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);">
                <i class="bi bi-plus-circle me-2"></i> Tambah Mahasiswa
            </a>
        </div>

        <?php if(session('success')): ?>
            <div class="alert alert-success" style="border-radius: 0.5rem;"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <?php if (isset($component)) { $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['icon' => 'people-fill','color' => 'blue','title' => 'Total Mahasiswa','value' => number_format($totalMahasiswa, 0, ',', '.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'people-fill','color' => 'blue','title' => 'Total Mahasiswa','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($totalMahasiswa, 0, ',', '.'))]); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $attributes = $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $component = $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
            </div>
            <div class="col-lg-3 col-md-6">
                <?php if (isset($component)) { $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['icon' => 'check-circle-fill','color' => 'green','title' => 'Mahasiswa Aktif','value' => number_format($totalAktif, 0, ',', '.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'check-circle-fill','color' => 'green','title' => 'Mahasiswa Aktif','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($totalAktif, 0, ',', '.'))]); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $attributes = $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $component = $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
            </div>
            <div class="col-lg-3 col-md-6">
                <?php if (isset($component)) { $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['icon' => 'pencil-square','color' => 'blue','title' => 'Cuti Akademik','value' => number_format($totalCuti, 0, ',', '.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'pencil-square','color' => 'blue','title' => 'Cuti Akademik','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($totalCuti, 0, ',', '.'))]); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $attributes = $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $component = $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
            </div>
            <div class="col-lg-3 col-md-6">
                <?php if (isset($component)) { $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.stat-card','data' => ['icon' => 'x-circle-fill','color' => 'red','title' => 'Non-Aktif','value' => number_format($totalNonAktif, 0, ',', '.')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'x-circle-fill','color' => 'red','title' => 'Non-Aktif','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($totalNonAktif, 0, ',', '.'))]); ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $attributes = $__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__attributesOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6)): ?>
<?php $component = $__componentOriginal3c3cb599308b2d9971dae437d0b6bab6; ?>
<?php unset($__componentOriginal3c3cb599308b2d9971dae437d0b6bab6); ?>
<?php endif; ?>
            </div>
        </div>

        <div class="table-card" style="background: white; border-radius: 1rem; border: none; box-shadow: 0 4px 20px rgba(0, 43, 107, 0.05); overflow: hidden;">
            <form id="filterForm" method="GET" action="<?php echo e(route('admin.mahasiswa.index')); ?>" style="padding: 1.5rem; border-bottom: 1px solid rgba(0, 43, 107, 0.08);">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div class="d-flex flex-wrap gap-2 flex-grow-1">
                        <input type="text" id="searchInput" name="search" value="<?php echo e(request('search')); ?>" class="form-control py-2" placeholder="Cari nama / NIM..." style="width: auto; min-width: 200px; border-color: #cbd5e1; border-radius: 0.5rem; font-size: 0.9rem;" autocomplete="off">

                        <select id="prodiSelect" name="program_studi_id" class="form-select py-2" style="width: auto; min-width: 180px; border-color: #cbd5e1; border-radius: 0.5rem; color: #334155; font-size: 0.9rem;">
                            <option value="">Semua Prodi</option>
                            <?php $__currentLoopData = $programStudiList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($prodi->id); ?>" <?php echo e((string) request('program_studi_id') === (string) $prodi->id ? 'selected' : ''); ?>><?php echo e($prodi->nama_prodi); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>

                        <select id="statusSelect" name="status" class="form-select py-2" style="width: auto; min-width: 150px; border-color: #cbd5e1; border-radius: 0.5rem; color: #334155; font-size: 0.9rem;">
                            <option value="">Semua Status</option>
                            <option value="aktif" <?php echo e(request('status') === 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                            <option value="cuti" <?php echo e(request('status') === 'cuti' ? 'selected' : ''); ?>>Cuti</option>
                            <option value="non_aktif" <?php echo e(request('status') === 'non_aktif' ? 'selected' : ''); ?>>Non-Aktif</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('admin.mahasiswa.index')); ?>" class="btn btn-light d-flex align-items-center gap-2 px-3 py-2" style="border: 1px solid #cbd5e1; border-radius: 0.5rem; font-weight: 600; color: #334155; background-color: white; font-size: 0.9rem;">
                            <i class="bi bi-arrow-clockwise"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <div id="tableContainer">
                <?php echo $__env->make('admin.mahasiswa.table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const prodiSelect = document.getElementById('prodiSelect');
            const statusSelect = document.getElementById('statusSelect');
            const tableContainer = document.getElementById('tableContainer');
            
            let delayTimer;

            function fetchMahasiswa(page = 1) {
                const search = searchInput.value;
                const prodi = prodiSelect.value;
                const status = statusSelect.value;

                // Bangun parameter URL untuk request AJAX
                let url = `<?php echo e(route('admin.mahasiswa.index')); ?>?ajax=1&page=${page}&search=${encodeURIComponent(search)}&program_studi_id=${prodi}&status=${status}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                    })
                    .catch(error => console.error('Gagal memuat data mahasiswa:', error));
            }

            // Real-time Search Keyboard (dengan jeda/debounce 400ms agar hemat resource server)
            searchInput.addEventListener('input', function () {
                clearTimeout(delayTimer);
                delayTimer = setTimeout(function() {
                    fetchMahasiswa();
                }, 400);
            });

            // Ganti Filter Dropdown langsung panggil fungsi pencarian
            prodiSelect.addEventListener('change', () => fetchMahasiswa());
            statusSelect.addEventListener('change', () => fetchMahasiswa());

            // Intercept link pagination bawaan agar berjalan via AJAX
            document.addEventListener('click', function (e) {
                const paginationLink = e.target.closest('#tableContainer .pagination a');
                if (paginationLink) {
                    e.preventDefault();
                    const urlParams = new URLSearchParams(paginationLink.getAttribute('href').split('?')[1]);
                    const page = urlParams.get('page') || 1;
                    fetchMahasiswa(page);
                }
            });
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $attributes = $__attributesOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__attributesOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale0f1cdd055772eb1d4a99981c240763e)): ?>
<?php $component = $__componentOriginale0f1cdd055772eb1d4a99981c240763e; ?>
<?php unset($__componentOriginale0f1cdd055772eb1d4a99981c240763e); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\cendekia\resources\views/admin/mahasiswa/index.blade.php ENDPATH**/ ?>