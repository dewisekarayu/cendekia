@extends('layouts.admin')

@section('title', 'Riwayat Aktivitas: ' . $kalenderAkademik->judul)

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="page-title mb-1">Riwayat Aktivitas Kalender Akademik</h1>
            <p class="page-subtitle mb-0">{{ $kalenderAkademik->judul }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.kalender-akademik.show', $kalenderAkademik) }}" class="btn btn-secondary fw-semibold px-4 py-2"
               style="border-radius: 0.5rem;">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Detail
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 1rem; background: white;">
        <div class="card-header bg-white border-0 pb-0">
            <h5 class="mb-0 fw-bold" style="color: var(--primary-blue);"><i class="bi bi-clock-history me-2"></i>Log Aktivitas ({{ $logs->total() }} record)</h5>
        </div>
        <div class="card-body p-0">
            @if($logs->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-clock-history" style="font-size: 3rem; color: #cbd5e1;"></i>
                    <h5 class="mt-3 text-muted">Belum ada aktivitas</h5>
                    <p class="text-muted mb-0">Agenda ini belum memiliki riwayat perubahan</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="min-width: 900px;">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 fw-semibold small text-uppercase text-muted" style="color: var(--primary-blue) !important;">Waktu</th>
                                <th class="py-3 fw-semibold small text-uppercase text-muted" style="color: var(--primary-blue) !important;">Event</th>
                                <th class="py-3 fw-semibold small text-uppercase text-muted" style="color: var(--primary-blue) !important;">Oleh</th>
                                <th class="py-3 fw-semibold small text-uppercase text-muted" style="color: var(--primary-blue) !important;">Keterangan</th>
                                <th class="pe-4 py-3 fw-semibold small text-uppercase text-muted" style="color: var(--primary-blue) !important;">Detail Perubahan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td class="ps-4">
                                        <small class="fw-medium">{{ $log->occurred_at->format('d M Y H:i:s') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge px-3 py-2 rounded-pill d-inline-flex align-items-center gap-1"
                                               style="background-color: {{ $log->event_color_bg }}; color: {{ $log->event_color }};">
                                            <i class="bi {{ $log->event_icon }}"></i>
                                            {{ $log->event_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="bg-primary-soft text-primary rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 32px; height: 32px; font-size: 0.8rem; font-weight: 700;">
                                                {{ $log->user ? strtoupper($log->user->name[0]) : '?' }}
                                            </div>
                                            <div>
                                                <span class="fw-medium small">{{ $log->user->name ?? 'Sistem' }}</span>
                                                @if($log->user)
                                                    <br><small class="text-muted">{{ $log->user->email }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $log->description }}</small>
                                            @if($log->ip_address)
                                                <br><small class="text-muted">IP: {{ $log->ip_address }}</small>
                                            @endif
                                        </td>
                                        <td class="pe-4">
                                            <button type="button" class="btn btn-sm btn-outline-secondary action-btn-view"
                                                    data-bs-toggle="modal" data-bs-target="#logDetailModal"
                                                    data-log='@json($log)'
                                                    title="Detail Perubahan" style="border-radius: 0.35rem;">
                                                <i class="bi bi-chevron-double-down"></i>
                                            </button>
                                        </td>
                                    </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="card-footer bg-white border-0 px-4 py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted small">
                            Menampilkan {{ $logs->firstItem() }} - {{ $logs->lastItem() }} dari {{ $logs->total() }} data
                        </div>
                        <div>
                            {{ $logs->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Log Detail Modal --}}
<div class="modal fade" id="logDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 1rem; border: none;">
            <div class="modal-header bg-light" style="border-radius: 1rem 1rem 0 0;">
                <h5 class="modal-title fw-bold" style="color: var(--primary-blue);"><i class="bi bi-clipboard-data me-2"></i>Detail Perubahan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3" id="logDetailContent"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logModal = document.getElementById('logDetailModal');
        if (logModal) {
            logModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const logData = JSON.parse(button.getAttribute('data-log'));
                const content = document.getElementById('logDetailContent');

                let html = `
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Event</label>
                        <span class="badge px-3 py-2" style="background-color: ${logData.event_color_bg}; color: ${logData.event_color_text};">
                            <i class="bi ${logData.event_icon} me-1"></i> ${logData.event_label}
                        </span>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Waktu</label>
                        <p class="mb-0">${logData.occurred_at_formatted || new Date(logData.occurred_at).toLocaleString()}</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Oleh</label>
                        <p class="mb-0">${logData.user?.name ?? 'Sistem'}</p>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold small">Keterangan</label>
                        <p class="mb-0">${logData.description}</p>
                    </div>
                `;

                if (logData.old_values && Object.keys(logData.old_values).length > 0) {
                    html += `
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Nilai Sebelumnya</label>
                            <pre class="bg-dark text-light p-3 rounded small" style="max-height: 300px; overflow: auto;">${JSON.stringify(logData.old_values, null, 2)}</pre>
                        </div>
                    `;
                }

                if (logData.new_values && Object.keys(logData.new_values).length > 0) {
                    html += `
                        <div class="col-12">
                            <label class="form-label fw-semibold small">Nilai Baru</label>
                            <pre class="bg-dark text-light p-3 rounded small" style="max-height: 300px; overflow: auto;">${JSON.stringify(logData.new_values, null, 2)}</pre>
                        </div>
                    `;
                }

                content.innerHTML = html;
            });
        }
    });
</script>
@endpush
@endsection