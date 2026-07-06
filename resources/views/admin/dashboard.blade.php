
<x-admin-layout>
    <div class="container-fluid">
        <!-- Header -->
        <div class="mb-4">
            <h1 class="page-title">Selamat Datang, {{ auth()->user()->name }}</h1>
            <p class="page-subtitle">Platform Cendekia membantu Anda mengelola 12,450 aktivitas hari ini. Semua berjalan optimal dengan tingkat uptime sistem 99.9%</p>
        </div>

        <!-- Stats Cards Row -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <x-admin.stat-card 
                    icon="people-fill" 
                    color="blue" 
                    title="Total Mahasiswa" 
                    value="{{ number_format($totalMahasiswa, 0, ',', '.') }}">
                </x-admin.stat-card>
            </div>
            <div class="col-lg-3 col-md-6">
                <x-admin.stat-card 
                    icon="person-badge" 
                    color="green" 
                    title="Total Dosen" 
                    value="{{ number_format($totalDosen, 0, ',', '.') }}">
                </x-admin.stat-card>
            </div>
            <div class="col-lg-3 col-md-6">
                <x-admin.stat-card 
                    icon="book-fill" 
                    color="yellow" 
                    title="Mata Kuliah" 
                    value="{{ number_format($totalMataKuliah, 0, ',', '.') }}">
                </x-admin.stat-card>
            </div>
            <div class="col-lg-3 col-md-6">
                <x-admin.stat-card 
                    icon="diagram-3" 
                    color="red" 
                    title="Program Studi" 
                    value="{{ number_format($totalProgramStudi, 0, ',', '.') }}">
                </x-admin.stat-card>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="chart-card">
            <h5>Aktivitas Pengguna</h5>
            <p>Grafik interaksi pengguna dokter dokter</p>
            <div style="position: relative; height: 300px;">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="row mt-4 g-4">
            <div class="col-lg-8">
                <div class="table-card">
                    <div class="table-card-header">
                        <h5>Aktivitas Terbaru</h5>
                        <a href="#" class="btn btn-sm btn-primary">
                            <i class="bi bi-eye"></i> Lihat Semua
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Pengguna</th>
                                    <th>Aktivitas</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Dr. Ahmad Subagyo</td>
                                    <td>Membuat Kelas Baru</td>
                                    <td>10 menit lalu</td>
                                    <td><span class="badge badge-status badge-aktif">Berhasil</span></td>
                                </tr>
                                <tr>
                                    <td>Aditya Pratama</td>
                                    <td>Upload Materi Kuliah</td>
                                    <td>1 jam lalu</td>
                                    <td><span class="badge badge-status badge-aktif">Berhasil</span></td>
                                </tr>
                                <tr>
                                    <td>Siti Aminah</td>
                                    <td>Mengumpulkan Tugas</td>
                                    <td>2 jam lalu</td>
                                    <td><span class="badge badge-status badge-aktif">Berhasil</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="table-card">
                    <div class="table-card-header">
                        <h5>Status Sistem</h5>
                    </div>
                    <div style="padding: 1.5rem;">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Database</span>
                            <span class="badge bg-success">Online</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Server</span>
                            <span class="badge bg-success">Online</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>API</span>
                            <span class="badge bg-success">Online</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Email</span>
                            <span class="badge bg-success">Online</span>
                        </div>
                        <hr class="my-3">
                        <div class="text-center">
                            <small class="text-muted">Uptime: 99.9%</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Activity Chart
        const ctxActivity = document.getElementById('activityChart').getContext('2d');
        const activityChart = new Chart(ctxActivity, {
            type: 'bar',
            data: {
                labels: ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AUG', 'SEP', 'OKT', 'NOV', 'DES'],
                datasets: [{
                    label: 'Aktivitas',
                    data: [30, 45, 60, 55, 75, 85, 70, 65, 80, 75, 85, 90],
                    backgroundColor: '#1e40af',
                    borderColor: '#0f3a7d',
                    borderWidth: 0,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: '#e5e7eb'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</x-admin-layout>