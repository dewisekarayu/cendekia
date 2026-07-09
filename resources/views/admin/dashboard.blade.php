@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

    <div class="welcome-banner">
        <h2>Selamat Datang, {{ auth()->user()->name ?? 'Admin' }}</h2>
        <p>
            Platform Cendekia memantau {{ number_format($totalAktivitas ?? 0) }} aktivitas hari ini.
            Semuanya berjalan optimal dengan tingkat uptime sistem {{ $uptime ?? '99.9' }}%.
        </p>
    </div>

    <div class="stat-grid">
        <x-stat-card
            icon="people-fill"
            color="blue"
            label="Total Mahasiswa"
            :value="number_format($totalMahasiswa ?? 0)"
            :change="$perubahanMahasiswa ?? null"
        />

        <x-stat-card
            icon="person-badge"
            color="green"
            label="Total Dosen"
            :value="number_format($totalDosen ?? 0)"
            :change="$perubahanDosen ?? null"
        />

        <x-stat-card
            icon="book-fill"
            color="orange"
            label="Mata Kuliah"
            :value="number_format($totalMataKuliah ?? 0)"
            :change="$perubahanMataKuliah ?? null"
        />

        <x-stat-card
            icon="diagram-3"
            color="red"
            label="Program Studi"
            :value="number_format($totalProgramStudi ?? 0)"
            :change="$perubahanProgramStudi ?? null"
        />
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
                <span>{{ number_format($totalMahasiswa ?? 0) }}</span>
            </div>

            <div class="insight-list">
                @forelse ($mahasiswaPerProdi as $prodi)
                    @php
                        $value = (int) ($prodi['value'] ?? 0);
                        $percentage = ($totalMahasiswa ?? 0) > 0 ? round(($value / $totalMahasiswa) * 100) : 0;
                    @endphp
                    <div class="insight-row">
                        <div class="d-flex justify-content-between gap-3">
                            <span>{{ $prodi['label'] }}</span>
                            <strong>{{ number_format($value) }}</strong>
                        </div>
                        <div class="insight-progress">
                            <div style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">Belum ada data program studi.</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const dataBulanan = {!! json_encode($aktivitasBulanan ?? []) !!};
    const dataMingguan = {!! json_encode($aktivitasMingguan ?? []) !!};

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
@endpush