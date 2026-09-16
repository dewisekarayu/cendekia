@extends('layouts.admin')

@section('title', 'Log Aktivitas Pengguna')

@section('content')
<style>
    .log-container {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .log-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .log-header h3 {
        margin: 0;
        font-weight: 700;
        color: #0f172a;
    }
    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }
    .badge-dosen { background: #3b82f6; color: white; }
    .badge-mahasiswa { background: #10b981; color: white; }
    .badge-admin { background: #f59e0b; color: white; }
</style>

<div class="log-container">
    <div class="log-header">
        <div>
            <h3>🕵️‍♂️ Log Aktivitas Pengguna</h3>
            <p class="text-muted mb-0 mt-1">Pantau semua tindakan (Audit Trail) yang terjadi di dalam Cendekia LMS.</p>
        </div>
        
        <form action="{{ route('admin.aktivitas.index') }}" method="GET" class="d-flex gap-2">
            <select name="role" class="form-select form-select-sm" style="width: auto;">
                <option value="">Semua Peran</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="dosen" {{ request('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="mahasiswa" {{ request('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            </select>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama atau aktivitas..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-dark btn-sm px-3">Cari</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th class="py-3">Waktu</th>
                    <th>Pengguna</th>
                    <th>Peran</th>
                    <th>Aksi</th>
                    <th>Deskripsi Lengkap</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td class="text-nowrap text-muted" style="font-size: 13.5px;">
                            {{ $log->terjadi_pada->format('d M Y, H:i:s') }}
                        </td>
                        <td class="fw-medium text-dark">
                            {{ $log->user->name ?? 'User Dihapus' }}
                        </td>
                        <td>
                            @php
                                $role = $log->user ? $log->user->roles->first()->name ?? 'N/A' : 'N/A';
                                $badgeClass = match($role) {
                                    'dosen' => 'badge-dosen',
                                    'mahasiswa' => 'badge-mahasiswa',
                                    'admin' => 'badge-admin',
                                    default => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} rounded-pill px-3">{{ ucfirst($role) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $log->aksi }}</span>
                        </td>
                        <td style="max-width: 300px; white-space: normal;">
                            {{ $log->deskripsi }}
                        </td>
                        <td class="text-muted" style="font-family: monospace; font-size: 13px;">
                            {{ $log->ip_address }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada rekam jejak aktivitas pengguna saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {{ $logs->links() }}
    </div>
</div>
@endsection
