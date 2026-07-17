<?php $__env->startSection('title', 'Dashboard Admin'); ?>

<?php $__env->startSection('content'); ?>

    
    <style>
        .welcome-banner {
            background: linear-gradient(135deg, #002B6B 0%, #0B192C 100%);
            padding: 24px 32px;
            border-radius: 16px;
            color: #ffffff;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0, 43, 107, 0.1);
        }
        /* Memperkecil judul selamat datang agar proporsional */
        .welcome-banner h2 {
            font-size: 22px !important;
            font-weight: 700;
            margin: 0 0 8px 0;
            letter-spacing: -0.5px;
        }
        .welcome-banner p {
            font-size: 13.5px;
            opacity: 0.85;
            margin: 0;
            line-height: 1.5;
        }

        /* Layout Grid untuk Stat Cards */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        /* Penyelarasan Tipografi di Komponen Grafik dan Konten */
        .chart-card-header h3, 
        .insight-card-header h3 {
            font-size: 16px !important;
            font-weight: 700;
            color: #0F172A;
            margin: 0 0 4px 0;
        }
        .chart-card-header p, 
        .insight-card-header p {
            font-size: 12px;
            color: #64748B;
            margin: 0;
        }
        
        /* Setelan teks ringkasan grafik */
        .chart-summary-item .summary-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748B;
        }
        .chart-summary-item strong {
            font-size: 18px;
            color: #0F172A;
        }
    </style>

    <div class="welcome-banner">
        <h2>Selamat Datang, <?php echo e(auth()->user()->name ?? 'Admin'); ?></h2>
        <p>
            Platform Cendekia memantau <?php echo e(number_format($totalAktivitas ?? 0)); ?> aktivitas hari ini.
            Semuanya berjalan optimal dengan tingkat uptime sistem <?php echo e($uptime ?? '99.9'); ?>%.
        </p>
    </div>

    <div class="stat-grid">
        <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['icon' => 'people-fill','color' => 'blue','label' => 'Total Mahasiswa','value' => number_format($totalMahasiswa ?? 0),'change' => $perubahanMahasiswa ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'people-fill','color' => 'blue','label' => 'Total Mahasiswa','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($totalMahasiswa ?? 0)),'change' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($perubahanMahasiswa ?? null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['icon' => 'person-badge','color' => 'green','label' => 'Total Dosen','value' => number_format($totalDosen ?? 0),'change' => $perubahanDosen ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'person-badge','color' => 'green','label' => 'Total Dosen','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($totalDosen ?? 0)),'change' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($perubahanDosen ?? null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['icon' => 'book-fill','color' => 'orange','label' => 'Mata Kuliah','value' => number_format($totalMataKuliah ?? 0),'change' => $perubahanMataKuliah ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'book-fill','color' => 'orange','label' => 'Mata Kuliah','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($totalMataKuliah ?? 0)),'change' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($perubahanMataKuliah ?? null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>

        <?php if (isset($component)) { $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.stat-card','data' => ['icon' => 'diagram-3','color' => 'red','label' => 'Program Studi','value' => number_format($totalProgramStudi ?? 0),'change' => $perubahanProgramStudi ?? null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('stat-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'diagram-3','color' => 'red','label' => 'Program Studi','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(number_format($totalProgramStudi ?? 0)),'change' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($perubahanProgramStudi ?? null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $attributes = $__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__attributesOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682)): ?>
<?php $component = $__componentOriginal527fae77f4db36afc8c8b7e9f5f81682; ?>
<?php unset($__componentOriginal527fae77f4db36afc8c8b7e9f5f81682); ?>
<?php endif; ?>
    </div>

    <div class="admin-dashboard-grid">
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <h3>Aktivitas Pengguna</h3>
                    <p>Grafik interaksi mahasiswa dan dosen berdasarkan data aktivitas LMS</p>
                </div>

                <div class="chart-summary">
                    <div class="chart-summary-item">
                        <span class="summary-label">Total</span>
                        <strong id="chartTotalValue">0</strong>
                    </div>
                    <div class="chart-summary-item">
                        <span class="summary-label">Tertinggi</span>
                        <strong id="chartPeakValue">0</strong>
                    </div>
                </div>

                <div class="chart-toggle" id="chartRangeToggle">
                    <button type="button" data-range="mingguan">Mingguan</button>
                    <button type="button" data-range="bulanan" class="active">Bulanan</button>
                </div>
            </div>

            <div class="chart-canvas-wrap">
                <canvas id="aktivitasChart"></canvas>
            </div>
        </div>

        <div class="insight-card">
            <div class="insight-card-header">
                <div>
                    <h3>Sebaran Mahasiswa</h3>
                    <p>Jumlah mahasiswa aktif per program studi</p>
                </div>
                <span><?php echo e(number_format($totalMahasiswa ?? 0)); ?></span>
            </div>

            <div class="insight-list">
                <?php $__empty_1 = true; $__currentLoopData = $mahasiswaPerProdi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prodi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $value = (int) ($prodi['value'] ?? 0);
                        $percentage = ($totalMahasiswa ?? 0) > 0 ? round(($value / $totalMahasiswa) * 100) : 0;
                    ?>
                    <div class="insight-row">
                        <div class="d-flex justify-content-between gap-3">
                            <span><?php echo e($prodi['label']); ?></span>
                            <strong><?php echo e(number_format($value)); ?></strong>
                        </div>
                        <div class="insight-progress">
                            <div style="width: <?php echo e($percentage); ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted mb-0">Belum ada data program studi.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const dataBulanan = <?php echo json_encode($aktivitasBulanan ?? []); ?>;
    const dataMingguan = <?php echo json_encode($aktivitasMingguan ?? []); ?>;

    const ctx = document.getElementById('aktivitasChart').getContext('2d');
    const palette = ['#002B6B', '#0EA5E9', '#10B981', '#F59E0B', '#EF4444', '#7C3AED', '#14B8A6'];
    const muted = '#E8EEF8';
    const totalValue = document.getElementById('chartTotalValue');
    const peakValue = document.getElementById('chartPeakValue');

    function formatNumber(value) {
        return new Intl.NumberFormat('id-ID').format(value ?? 0);
    }

    function makeGradient() {
        const gradient = ctx.createLinearGradient(0, 0, 0, 320);
        gradient.addColorStop(0, '#38BDF8');
        gradient.addColorStop(0.55, '#002B6B');
        gradient.addColorStop(1, '#0F172A');
        return gradient;
    }

    function updateSummary(rows) {
        const values = rows.map(row => Number(row.value || 0));
        totalValue.textContent = formatNumber(values.reduce((sum, value) => sum + value, 0));
        peakValue.textContent = formatNumber(Math.max(...values, 0));
    }

    function buildDataset(rows) {
        const values = rows.map(row => Number(row.value || 0));
        const max = Math.max(...values, 0);
        const gradient = makeGradient();

        return {
            labels: rows.map(r => r.label),
            datasets: [{
                label: 'Aktivitas',
                data: values,
                backgroundColor: values.map((value, index) => value === max && value > 0 ? gradient : palette[index % palette.length]),
                hoverBackgroundColor: values.map((value, index) => value === 0 ? muted : palette[index % palette.length]),
                borderColor: 'rgba(15, 23, 42, 0.06)',
                borderWidth: 1,
                borderRadius: { topLeft: 10, topRight: 10, bottomLeft: 4, bottomRight: 4 },
                borderSkipped: false,
                barPercentage: 0.72,
                categoryPercentage: 0.7,
                maxBarThickness: 34,
            }]
        };
    }

    if (dataBulanan.length > 0) {
        updateSummary(dataBulanan);
    }

    const chart = new Chart(ctx, {
        type: 'bar',
        data: buildDataset(dataBulanan),
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: { duration: 700, easing: 'easeOutQuart' },
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
                    titleColor: '#FFFFFF',
                    bodyColor: '#E2E8F0',
                    borderColor: 'rgba(255,255,255,0.12)',
                    borderWidth: 1,
                    displayColors: false,
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        title: items => 'Periode ' + items[0].label,
                        label: item => formatNumber(item.parsed.y) + ' aktivitas',
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748B', font: { size: 12, weight: 600 } },
                    border: { display: false },
                },
                y: {
                    beginAtZero: true,
                    grace: '12%',
                    grid: { color: 'rgba(0, 43, 107, 0.08)', drawTicks: false },
                    ticks: { color: '#94A3B8', padding: 10, callback: value => formatNumber(value) },
                    border: { display: false },
                },
            },
        }
    });

    document.querySelectorAll('#chartRangeToggle button').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('#chartRangeToggle button').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const rows = this.dataset.range === 'mingguan' ? dataMingguan : dataBulanan;
            chart.data = buildDataset(rows);
            updateSummary(rows);
            chart.update();
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\cendekia\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>