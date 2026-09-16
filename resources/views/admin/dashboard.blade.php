@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('activeMenu', 'Dashboard')

@section('content')

<div class="space-y-6">

    {{-- HERO WELCOME BANNER --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-[#002B6B] via-[#09357a] to-[#144896] dark:from-slate-900 dark:via-indigo-950 dark:to-blue-950 px-6 py-7 sm:px-8 shadow-md text-white">
        <div class="pointer-events-none absolute -right-12 -top-12 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
        <div class="pointer-events-none absolute -left-10 -bottom-10 h-36 w-36 rounded-full bg-blue-400/10 blur-xl"></div>
        
        <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0 flex-1">
                <div class="mb-2 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-lg border border-white/20 bg-white/15 px-3 py-1 text-xs font-bold tracking-wide text-white backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ now()->translatedFormat('l, d F Y') }}
                    </span>
                    <span class="rounded-lg border border-white/10 bg-black/20 px-2.5 py-1 text-xs font-medium text-blue-100 backdrop-blur-sm">
                        Live Administration
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-lg border border-emerald-400/30 bg-emerald-500/20 px-2.5 py-1 text-xs font-bold text-emerald-200">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Uptime {{ $uptime ?? '99.9' }}%
                    </span>
                </div>

                <h1 class="text-2xl font-black text-white sm:text-3xl tracking-tight leading-tight">
                    Selamat Datang, {{ auth()->user()->name ?? 'Admin Cendekia' }}
                </h1>

                <p class="mt-2 text-xs sm:text-sm text-blue-100/90 leading-relaxed max-w-2xl font-medium">
                    Platform Cendekia memantau <strong class="text-white">{{ number_format($totalAktivitas ?? 0) }} aktivitas</strong> perkuliahan hari ini. Seluruh operasional akademik berjalan normal dan terpantau dengan optimal.
                </p>
            </div>

            {{-- Quick Action Buttons in Hero --}}
            <div class="flex flex-row lg:flex-col gap-2.5 shrink-0">
                <a href="{{ route('admin.dosen.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 hover:bg-white/20 px-4 py-2 text-xs font-bold text-white backdrop-blur-sm transition text-decoration-none">
                    <i class="bi bi-person-badge text-blue-200"></i>
                    Data Dosen
                </a>
                <a href="{{ route('admin.mahasiswa.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 hover:bg-white/20 px-4 py-2 text-xs font-bold text-white backdrop-blur-sm transition text-decoration-none">
                    <i class="bi bi-people-fill text-blue-200"></i>
                    Data Mahasiswa
                </a>
                <a href="{{ route('admin.program-studi.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 bg-white/10 hover:bg-white/20 px-4 py-2 text-xs font-bold text-white backdrop-blur-sm transition text-decoration-none">
                    <i class="bi bi-diagram-3 text-blue-200"></i>
                    Program Studi
                </a>
            </div>
        </div>
    </div>

    {{-- STATS SUMMARY KPI CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Mahasiswa --}}
        <a href="{{ route('admin.mahasiswa.index') }}" class="group relative flex flex-col justify-between rounded-2xl border border-slate-200/90 dark:border-slate-700/80 bg-white dark:bg-slate-800 p-5 shadow-sm hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-md transition-all duration-200 text-decoration-none">
            <div class="flex items-center justify-between mb-4">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-950/60 text-[#002B6B] dark:text-blue-300 border border-blue-100 dark:border-blue-900/50 group-hover:scale-105 transition-transform">
                    <i class="bi bi-people-fill text-lg"></i>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">
                    Aktif
                </span>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-1">Total Mahasiswa</p>
                <p class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tight leading-none mb-3">{{ number_format($totalMahasiswa ?? 0) }}</p>
                <div class="flex items-center justify-between pt-2.5 border-t border-slate-100 dark:border-slate-700/60">
                    <span class="text-xs font-semibold text-blue-600 dark:text-blue-400">Kelola Mahasiswa</span>
                    <i class="bi bi-arrow-right text-xs text-blue-600 dark:text-blue-400 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        {{-- Total Dosen --}}
        <a href="{{ route('admin.dosen.index') }}" class="group relative flex flex-col justify-between rounded-2xl border border-slate-200/90 dark:border-slate-700/80 bg-white dark:bg-slate-800 p-5 shadow-sm hover:border-emerald-400 dark:hover:border-emerald-500 hover:shadow-md transition-all duration-200 text-decoration-none">
            <div class="flex items-center justify-between mb-4">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-300 border border-emerald-100 dark:border-emerald-900/50 group-hover:scale-105 transition-transform">
                    <i class="bi bi-person-badge text-lg"></i>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                    Pengajar
                </span>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-1">Total Dosen</p>
                <p class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tight leading-none mb-3">{{ number_format($totalDosen ?? 0) }}</p>
                <div class="flex items-center justify-between pt-2.5 border-t border-slate-100 dark:border-slate-700/60">
                    <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Kelola Dosen</span>
                    <i class="bi bi-arrow-right text-xs text-emerald-600 dark:text-emerald-400 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        {{-- Mata Kuliah --}}
        <a href="{{ route('admin.mata-kuliah.index') }}" class="group relative flex flex-col justify-between rounded-2xl border border-slate-200/90 dark:border-slate-700/80 bg-white dark:bg-slate-800 p-5 shadow-sm hover:border-amber-400 dark:hover:border-amber-500 hover:shadow-md transition-all duration-200 text-decoration-none">
            <div class="flex items-center justify-between mb-4">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-300 border border-amber-100 dark:border-amber-900/50 group-hover:scale-105 transition-transform">
                    <i class="bi bi-book-fill text-lg"></i>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-amber-50 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                    Kurikulum
                </span>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-1">Mata Kuliah</p>
                <p class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tight leading-none mb-3">{{ number_format($totalMataKuliah ?? $totalMatkul ?? 0) }}</p>
                <div class="flex items-center justify-between pt-2.5 border-t border-slate-100 dark:border-slate-700/60">
                    <span class="text-xs font-semibold text-amber-600 dark:text-amber-400">Daftar Kurikulum</span>
                    <i class="bi bi-arrow-right text-xs text-amber-600 dark:text-amber-400 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>

        {{-- Program Studi --}}
        <a href="{{ route('admin.program-studi.index') }}" class="group relative flex flex-col justify-between rounded-2xl border border-slate-200/90 dark:border-slate-700/80 bg-white dark:bg-slate-800 p-5 shadow-sm hover:border-rose-400 dark:hover:border-rose-500 hover:shadow-md transition-all duration-200 text-decoration-none">
            <div class="flex items-center justify-between mb-4">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-300 border border-rose-100 dark:border-rose-900/50 group-hover:scale-105 transition-transform">
                    <i class="bi bi-diagram-3 text-lg"></i>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-rose-50 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">
                    Jurusan
                </span>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 mb-1">Program Studi</p>
                <p class="text-2xl sm:text-3xl font-black text-slate-800 dark:text-white tracking-tight leading-none mb-3">{{ number_format($totalProgramStudi ?? $totalProdi ?? 0) }}</p>
                <div class="flex items-center justify-between pt-2.5 border-t border-slate-100 dark:border-slate-700/60">
                    <span class="text-xs font-semibold text-rose-600 dark:text-rose-400">Kelola Program Studi</span>
                    <i class="bi bi-arrow-right text-xs text-rose-600 dark:text-rose-400 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
        </a>
    </div>

    {{-- 2-COLUMN ANALYTICS GRID --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Aktivitas Pengguna (Chart Card - 2 Cols) --}}
        <div class="lg:col-span-2 rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm transition-colors duration-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 pb-4 border-b border-slate-100 dark:border-slate-700/60">
                <div>
                    <h2 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                        Aktivitas Pengguna
                    </h2>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Grafik interaksi mahasiswa dan dosen berdasarkan data aktivitas portal LMS</p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 bg-slate-50 dark:bg-slate-900/50 px-3 py-1.5 rounded-xl border border-slate-200/60 dark:border-slate-700">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total:</span>
                        <strong id="chartTotalValue" class="text-xs font-black text-[#002B6B] dark:text-blue-400">0</strong>
                    </div>

                    <div class="chart-toggle" id="chartRangeToggle">
                        <button type="button" data-range="mingguan">Mingguan</button>
                        <button type="button" data-range="bulanan" class="active">Bulanan</button>
                    </div>
                </div>
            </div>

            <div class="chart-canvas-wrap" style="height: 320px;">
                <canvas id="aktivitasChart"></canvas>
            </div>
        </div>

        {{-- Sebaran Mahasiswa (Insight Card - 1 Col) --}}
        <div class="rounded-2xl border border-slate-200/80 dark:border-slate-700 bg-white dark:bg-slate-800 p-6 shadow-sm transition-colors duration-200">
            <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100 dark:border-slate-700/60">
                <div>
                    <h2 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Sebaran Mahasiswa
                    </h2>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Jumlah mahasiswa per prodi</p>
                </div>
                <span class="rounded-xl bg-blue-50 dark:bg-blue-950/50 px-3 py-1 text-xs font-bold text-[#002B6B] dark:text-blue-300">
                    {{ number_format($totalMahasiswa ?? 0) }} Total
                </span>
            </div>

            <div class="space-y-4">
                @forelse ($mahasiswaPerProdi as $prodi)
                    @php
                        $value = (int) ($prodi['value'] ?? 0);
                        $percentage = ($totalMahasiswa ?? 0) > 0 ? round(($value / $totalMahasiswa) * 100) : 0;
                    @endphp
                    <div class="p-3 rounded-xl border border-slate-100 dark:border-slate-700/60 bg-slate-50/50 dark:bg-slate-900/40">
                        <div class="flex items-center justify-between text-xs font-bold mb-1.5">
                            <span class="text-slate-700 dark:text-slate-300 truncate pr-2">{{ $prodi['label'] }}</span>
                            <span class="text-[#002B6B] dark:text-blue-400 shrink-0">{{ number_format($value) }} ({{ $percentage }}%)</span>
                        </div>
                        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2 overflow-hidden">
                            <div class="bg-gradient-to-r from-[#002B6B] to-[#38BDF8] h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-slate-400 dark:text-slate-500 text-xs text-center py-6">Belum ada data program studi.</p>
                @endforelse
            </div>
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
        if (totalValue) {
            totalValue.textContent = formatNumber(values.reduce((sum, value) => sum + value, 0));
        }
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
                    ticks: { color: '#64748B', font: { family: 'Figtree', size: 12, weight: 600 } },
                    border: { display: false },
                },
                y: {
                    beginAtZero: true,
                    grace: '12%',
                    grid: { color: 'rgba(0, 43, 107, 0.06)', drawTicks: false },
                    ticks: { color: '#94A3B8', padding: 10, font: { family: 'Figtree' }, callback: value => formatNumber(value) },
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