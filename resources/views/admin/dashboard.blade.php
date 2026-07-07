@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')

    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <h2>Selamat Datang, {{ auth()->user()->name ?? 'Admin' }}</h2>
        <p>
            Platform Cendekia memantau {{ number_format($totalAktivitas ?? 0) }} aktivitas hari ini.
            Semuanya berjalan optimal dengan tingkat uptime sistem {{ $uptime ?? '99.9' }}%.
        </p>
    </div>

    <!-- Stat Cards -->
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

    <!-- Activity Chart -->
    <div class="chart-card">
        <div class="chart-card-header">
            <div>
                <h3>Aktivitas Pengguna</h3>
                <p>Grafik interaksi mingguan mahasiswa dan dosen</p>
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

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Data dari controller. Ganti dengan data asli, jangan gunakan angka acak di production.
    const dataBulanan = {!! json_encode($aktivitasBulanan ?? [
        ['label' => 'Jan', 'value' => 320],
        ['label' => 'Feb', 'value' => 450],
        ['label' => 'Mar', 'value' => 410],
        ['label' => 'Apr', 'value' => 520],
        ['label' => 'Mei', 'value' => 380],
        ['label' => 'Jun', 'value' => 610],
        ['label' => 'Jul', 'value' => 470],
        ['label' => 'Agu', 'value' => 500],
        ['label' => 'Sep', 'value' => 430],
        ['label' => 'Okt', 'value' => 560],
        ['label' => 'Nov', 'value' => 590],
        ['label' => 'Des', 'value' => 400],
    ]) !!};


    const dataMingguan = {!! json_encode($aktivitasMingguan ?? [
        ['label' => 'Sen', 'value' => 120],
        ['label' => 'Sel', 'value' => 150],
        ['label' => 'Rab', 'value' => 135],
        ['label' => 'Kam', 'value' => 170],
        ['label' => 'Jum', 'value' => 190],
        ['label' => 'Sab', 'value' => 90],
        ['label' => 'Min', 'value' => 60],
    ]) !!};

    const ctx = document.getElementById('aktivitasChart').getContext('2d');
    const navy = '#002B6B';
    const accent = '#CDDCFF';

    function buildDataset(rows) {
        const max = Math.max(...rows.map(r => r.value));
        return {
            labels: rows.map(r => r.label),
            datasets: [{
                data: rows.map(r => r.value),
                backgroundColor: rows.map(r => r.value === max ? navy : accent),
                borderRadius: 6,
                barThickness: 22,
            }]
        };
    }

    const chart = new Chart(ctx, {
        type: 'bar',
        data: buildDataset(dataBulanan),
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: '#EDF1F7' }, beginAtZero: true }
            }
        }
    });

    document.querySelectorAll('#chartRangeToggle button').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('#chartRangeToggle button').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const rows = this.dataset.range === 'mingguan' ? dataMingguan : dataBulanan;
            const rebuilt = buildDataset(rows);
            chart.data = rebuilt;
            chart.update();
        });
    });

});
</script>
@endpush