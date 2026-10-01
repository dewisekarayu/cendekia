<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Dosen - Cendekia</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #1e293b;
            background: #fff;
            padding: 20px;
        }

        .header {
            background-color: #321270;
            color: white;
            padding: 16px 20px;
            margin-bottom: 20px;
            border-radius: 6px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
            border: none;
            padding: 0;
        }
        .header h1 { font-size: 18px; font-weight: 700; margin-bottom: 3px; color: #ffffff; }
        .header p { font-size: 10px; opacity: 0.85; color: #ffffff; }
        .header-meta { text-align: right; font-size: 9px; opacity: 0.9; color: #ffffff; }

        .stats-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-bottom: 16px;
        }
        .stats-table td {
            padding: 0;
            vertical-align: top;
        }
        .stat-box {
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
        }
        .stat-box .label {
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 3px;
        }
        .stat-box .value { font-size: 16px; font-weight: 800; color: #0f172a; }
        .stat-purple { border-left: 4px solid #321270; }
        .stat-green { border-left: 4px solid #059669; }
        .stat-red { border-left: 4px solid #dc2626; }

        .filter-info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 7px 12px;
            font-size: 9px;
            color: #64748b;
            margin-bottom: 14px;
        }

        .data-table { width: 100%; border-collapse: collapse; font-size: 9.5px; }
        .data-table thead th {
            background: #321270;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-weight: 700;
            font-size: 8.5px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }
        .data-table thead th:first-child { width: 32px; text-align: center; }
        .data-table tbody tr { border-bottom: 1px solid #f1f5f9; }
        .data-table tbody tr:nth-child(even) { background: #f8fafc; }
        .data-table tbody td { padding: 7px 10px; vertical-align: middle; }
        .data-table tbody td:first-child { text-align: center; color: #94a3b8; font-weight: 600; font-size: 9px; }
        .td-nip { font-family: monospace; font-weight: 700; font-size: 9px; color: #0f172a; }
        .td-name { font-weight: 600; color: #1e293b; }
        .td-email { color: #475569; font-size: 9px; }
        .td-prodi { color: #7c3aed; font-weight: 600; font-size: 9px; }

        .badge {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 999px;
            font-size: 8px;
            font-weight: 700;
        }
        .badge-aktif { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
        .badge-non_aktif { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

        .footer-table {
            width: 100%;
            margin-top: 18px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 8.5px;
            color: #94a3b8;
        }
        .footer-table td {
            border: none;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="text-align: left;">
                    <h1>📋 Laporan Data Dosen</h1>
                    <p>Sistem Informasi Akademik — Cendekia</p>
                </td>
                <td class="header-meta">
                    <div>Dicetak: {{ now()->translatedFormat('d F Y, H:i') }}</div>
                    <div>Total Data: {{ $dosen->count() }} dosen</div>
                    @if($filters['search'] || $filters['prodi'])
                        <div style="margin-top:2px; font-weight: bold;">Filter Aktif</div>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <table class="stats-table">
        <tr>
            <td style="width: 33.33%;">
                <div class="stat-box stat-purple">
                    <div class="label">Total Dosen</div>
                    <div class="value">{{ $dosen->count() }}</div>
                </div>
            </td>
            <td style="width: 33.33%;">
                <div class="stat-box stat-green">
                    <div class="label">Aktif</div>
                    <div class="value">{{ $dosen->where('status','aktif')->count() }}</div>
                </div>
            </td>
            <td style="width: 33.33%;">
                <div class="stat-box stat-red">
                    <div class="label">Non-Aktif</div>
                    <div class="value">{{ $dosen->where('status','non_aktif')->count() }}</div>
                </div>
            </td>
        </tr>
    </table>

    @if($filters['search'] || $filters['prodi'])
    <div class="filter-info">
        Filter diterapkan:
        @if($filters['search']) <strong>Pencarian:</strong> "{{ $filters['search'] }}" @endif
        @if($filters['prodi']) &nbsp;| <strong>Prodi:</strong> {{ $filters['prodi'] }} @endif
    </div>
    @endif

    <table class="data-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>NIDN/NIP</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th>Program Studi</th>
                <th>Status</th>
                <th>Tgl. Daftar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dosen as $i => $d)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="td-nip">{{ $d->nip_nim ?? '-' }}</td>
                <td class="td-name">{{ $d->name }}</td>
                <td class="td-email">{{ $d->email }}</td>
                <td>{{ $d->telepon ?? '-' }}</td>
                <td class="td-prodi">{{ $d->programStudi?->nama_prodi ?? '-' }}</td>
                <td>
                    @php $s = $d->status ?? 'aktif'; @endphp
                    <span class="badge badge-{{ $s }}">
                        {{ $s === 'aktif' ? 'Aktif' : 'Non-Aktif' }}
                    </span>
                </td>
                <td>{{ $d->created_at?->format('d/m/Y') ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding:20px; color:#94a3b8; font-style:italic;">
                    Tidak ada data dosen yang sesuai filter.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <table class="footer-table">
        <tr>
            <td style="text-align: left;">© {{ date('Y') }} Cendekia Academic Information System</td>
            <td style="text-align: right;">Dokumen ini digenerate secara otomatis oleh sistem.</td>
        </tr>
    </table>
</body>
</html>
