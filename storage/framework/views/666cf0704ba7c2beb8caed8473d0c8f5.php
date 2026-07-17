<?php $__env->startSection('title', 'Mata Kuliah'); ?>

<?php $__env->startSection('content'); ?>
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
        <div>
            <h1 class="page-title mb-1">Mata Kuliah</h1>
            <p class="text-muted mb-2" style="font-size: 0.9rem;">Kelola seluruh data mata kuliah dan relasinya.</p>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Master Data</li>
                    <li class="breadcrumb-item active">Mata Kuliah</li>
                </ol>
            </nav>
        </div>

        <a href="<?php echo e(route('admin.mata-kuliah.create')); ?>" class="btn btn-primary d-flex align-items-center gap-2" style="white-space:nowrap;">
            <i class="bi bi-plus-circle"></i> Tambah Mata Kuliah
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 8px;">
            <i class="bi bi-check-circle-fill me-2"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="table-card p-4">
        <div class="table-card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <h5 class="m-0">Daftar Mata Kuliah</h5>

            
            <div style="position: relative; min-width: 280px;">
                <input type="text" id="liveSearchMK" class="form-control form-control-sm" placeholder="Cari Kode atau Nama MK..." style="border-radius: 6px; padding-right: 2.5rem;" autocomplete="off">
                <div id="searchSpinner" class="spinner-border spinner-border-sm text-secondary d-none" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%);" role="status"></div>
                <i id="searchIcon" class="bi bi-search" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #9ca3af;"></i>
            </div>
        </div>

        
        <div id="tableContainer">
            <?php echo $__env->make('admin.mata-kuliah.table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('liveSearchMK');
            const tableContainer = document.getElementById('tableContainer');
            const searchIcon = document.getElementById('searchIcon');
            const spinner = document.getElementById('searchSpinner');
            let typingTimer;
            const doneTypingInterval = 350; // Jeda 350ms setelah ketikan terakhir

            searchInput.addEventListener('input', function () {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(performSearch, doneTypingInterval);
            });

            function performSearch() {
                if (searchIcon) searchIcon.classList.add('d-none');
                if (spinner) spinner.classList.remove('d-none');

                const keyword = searchInput.value;
                const url = new URL(window.location.href);
                url.searchParams.set('search', keyword);
                url.searchParams.set('ajax', '1'); // Penanda request AJAX untuk Backend

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        tableContainer.innerHTML = html;
                        if (spinner) spinner.classList.add('d-none');
                        if (searchIcon) searchIcon.classList.remove('d-none');

                        // Update URL di browser address bar tanpa reload halaman
                        const browserUrl = new URL(window.location.href);
                        if(keyword) {
                            browserUrl.searchParams.set('search', keyword);
                        } else {
                            browserUrl.searchParams.delete('search');
                        }
                        window.history.pushState({}, '', browserUrl);
                    })
                    .catch(error => {
                        console.error('Gagal memuat data:', error);
                        if (spinner) spinner.classList.add('d-none');
                        if (searchIcon) searchIcon.classList.remove('d-none');
                    });
            }

            // Perbaikan Bug: Intercept klik pagination Bootstrap bawaan agar tetap mengalir menggunakan AJAX
            document.addEventListener('click', function (e) {
                const paginationLink = e.target.closest('#tableContainer .pagination a');
                if (paginationLink) {
                    e.preventDefault();
                    if (spinner) spinner.classList.remove('d-none');
                    if (searchIcon) searchIcon.classList.add('d-none');

                    const targetUrl = new URL(paginationLink.getAttribute('href'));
                    targetUrl.searchParams.set('ajax', '1');

                    fetch(targetUrl)
                        .then(response => response.text())
                        .then(html => {
                            tableContainer.innerHTML = html;
                            if (spinner) spinner.classList.add('d-none');
                            if (searchIcon) searchIcon.classList.remove('d-none');

                            targetUrl.searchParams.delete('ajax');
                            window.history.pushState({}, '', targetUrl);
                        })
                        .catch(error => {
                            console.error('Gagal memuat halaman:', error);
                            if (spinner) spinner.classList.add('d-none');
                            if (searchIcon) searchIcon.classList.remove('d-none');
                        });
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\cendekia\resources\views/admin/mata-kuliah/index.blade.php ENDPATH**/ ?>