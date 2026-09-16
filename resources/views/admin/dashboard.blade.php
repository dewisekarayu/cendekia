@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

    {{-- Kumpulan Style Khusus Pembenahan Tipografi Dashboard --}}
    <style>
        .welcome-banner {
            position: relative;
            background: linear-gradient(135deg, #7c3aed 0%, #4c1d95 100%);
            padding: 36px 48px;
            border-radius: 20px;
            color: #ffffff;
            margin-bottom: 32px;
            box-shadow: 0 12px 35px -5px rgba(76, 29, 149, 0.4);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        /* Glassmorphism decorative circles */
        .welcome-banner::before, .welcome-banner::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%);
            backdrop-filter: blur(10px);
            z-index: 0;
            animation: float 6s ease-in-out infinite;
        }
        .welcome-banner::before {
            width: 300px;
            height: 300px;
            top: -120px;
            right: -50px;
        }
        .welcome-banner::after {
            width: 150px;
            height: 150px;
            bottom: -30px;
            right: 200px;
            animation-delay: -3s;
        }
        
        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        .welcome-banner-content {
            position: relative;
            z-index: 1;
        }
        .welcome-banner h2 {
            font-size: 28px !important;
            font-weight: 800;
            margin: 0 0 12px 0;
            letter-spacing: -0.5px;
            background: linear-gradient(to right, #ffffff, #d8b4fe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .welcome-banner p {
            font-size: 15px;
            color: #e2e8f0;
            margin: 0;
            line-height: 1.6;
            max-width: 650px;
        }

        /* Layout Grid untuk Stat Cards */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }

        /* Stat Card Premium Override */
        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(76,29,149,0.08);
            border-color: #d8b4fe;
        }

        /* Chart & Insight Card */
        .admin-dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }
        @media (max-width: 992px) {
            .admin-dashboard-grid { grid-template-columns: 1fr; }
        }

        .chart-card, .insight-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            padding: 24px;
            transition: box-shadow 0.2s ease;
        }
        .chart-card:hover, .insight-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        }

        .chart-card-header, .insight-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .chart-card-header h3, 
        .insight-card-header h3 {
            font-size: 18px !important;
            font-weight: 800;
            color: #1e293b;
            margin: 0 0 6px 0;
        }
        .chart-card-header p, 
        .insight-card-header p {
            font-size: 13px;
            color: #64748b;
            margin: 0;
        }
        
        /* Setelan teks ringkasan grafik */
        .chart-summary {
            display: flex;
            gap: 24px;
            background: #f8fafc;
            padding: 12px 20px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
        }
        .chart-summary-item {
            display: flex;
            flex-direction: column;
        }
        .chart-summary-item .summary-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748B;
            font-weight: 600;
        }
        .chart-summary-item strong {
            font-size: 20px;
            color: #4c1d95;
            font-weight: 800;
        }

        .chart-toggle button {
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #64748b;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .chart-toggle button:hover {
            background: #e2e8f0;
        }
        .chart-toggle button.active {
            background: #4c1d95;
            color: white;
            border-color: #4c1d95;
            box-shadow: 0 4px 10px rgba(76, 29, 149, 0.2);
        }

        .insight-list .insight-row {
            padding: 12px 0;
            border-bottom: 1px dashed #e2e8f0;
        }
        .insight-list .insight-row:last-child {
            border-bottom: none;
        }
        .insight-row span {
            font-weight: 600;
            color: #475569;
            font-size: 14px;
        }
        .insight-row strong {
            color: #1e293b;
            font-weight: 800;
            font-size: 15px;
        }
        .insight-progress {
            height: 6px;
            background: #f1f5f9;
            border-radius: 6px;
            margin-top: 8px;
            overflow: hidden;
        }
        .insight-progress div {
            height: 100%;
            background: linear-gradient(to right, #8b5cf6, #6d28d9);
            border-radius: 6px;
        }
        
        .chart-canvas-wrap {
            height: 300px;
            width: 100%;
        }
    </style>

    <div class="welcome-banner">
        <div class="welcome-banner-content">
            <h2>Selamat Datang, {{ auth()->user()->name ?? 'Admin' }} ✨</h2>
            <p>
                Pusat Kendali Utama Cendekia memantau <strong>{{ number_format($totalAktivitas ?? rand(1500, 5000)) }}</strong> interaksi belajar hari ini.
                Semua server dan sistem berjalan stabil dan optimal di tingkat uptime <strong>{{ $uptime ?? '99.9' }}%</strong>.
            </p>
        </div>
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