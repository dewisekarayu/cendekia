<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $title ?? 'Admin Dashboard') - Cendekia</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        (function() {
            const theme = "{{ auth()->check() ? auth()->user()->theme : 'light' }}";
            if (theme === 'dark' || (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-blue: #002B6B;
            --secondary-blue: #002B6B;
            --navy-blue: #002B6B;
            --light-blue: #CDDCFF;
            --border-light: rgba(0, 43, 107, 0.08);
        }

        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
            background-color: #f8fafc;
            color: #1e293b;
            overflow-x: hidden;
        }

        /* Hide scrollbar on sidebar for clean modern look */
        aside {
            -ms-overflow-style: none !important; /* IE and Edge */
            scrollbar-width: none !important; /* Firefox */
        }
        aside::-webkit-scrollbar {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        h1, h2, h3, h4, h5, h6, .page-title, .welcome-banner h2 {
            font-family: 'Figtree', sans-serif !important;
            letter-spacing: -0.025em;
        }

        /* Dark mode compatibility */
        html.dark body {
            background-color: #0f172a !important;
            color: #cbd5e1 !important;
        }

        html.dark .bg-white {
            background-color: #1e293b !important;
        }

        html.dark .border-slate-200,
        html.dark .border-gray-200 {
            border-color: #334155 !important;
        }

        html.dark .text-slate-800,
        html.dark .text-gray-900,
        html.dark .text-gray-800 {
            color: #f8fafc !important;
        }

        html.dark .text-slate-500,
        html.dark .text-gray-500 {
            color: #94a3b8 !important;
        }

        /* =========================================================
           PREMIUM TABLE & CARD STYLING FOR ALL ADMIN MODULES
           ========================================================= */
        .page-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: #002B6B;
            letter-spacing: -0.02em;
        }
        html.dark .page-title {
            color: #f8fafc;
        }

        .breadcrumb {
            font-size: 0.825rem;
            font-weight: 500;
        }
        .breadcrumb-item a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.15s;
        }
        .breadcrumb-item a:hover {
            color: #002B6B;
        }
        .breadcrumb-item.active {
            color: #002B6B;
            font-weight: 700;
        }
        html.dark .breadcrumb-item a {
            color: #94a3b8;
        }
        html.dark .breadcrumb-item.active {
            color: #93c5fd;
        }

        /* Stat Cards */
        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 4px 16px -2px rgba(0, 43, 107, 0.04);
            transition: all 0.2s ease;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px -4px rgba(0, 43, 107, 0.08);
            border-color: #cbd5e1;
        }
        html.dark .stat-card {
            background: #1e293b;
            border-color: #334155;
            box-shadow: none;
        }
        .stat-card-icon {
            width: 46px;
            height: 46px;
            border-radius: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .stat-card .d-flex {
            min-width: 0;
        }
        .stat-card-icon.blue {
            background-color: #eff6ff;
            color: #002B6B;
            border: 1px solid #dbeafe;
        }
        .stat-card-icon.green {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }
        .stat-card-icon.red {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .stat-card-icon.amber, .stat-card-icon.yellow {
            background-color: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
        }
        html.dark .stat-card-icon.blue { background-color: rgba(30, 58, 138, 0.3); color: #93c5fd; border-color: rgba(59, 130, 246, 0.3); }
        html.dark .stat-card-icon.green { background-color: rgba(6, 78, 59, 0.3); color: #6ee7b7; border-color: rgba(5, 150, 105, 0.3); }
        html.dark .stat-card-icon.red { background-color: rgba(153, 27, 27, 0.3); color: #fca5a5; border-color: rgba(220, 38, 38, 0.3); }

        .stat-card .label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            margin-bottom: 0.25rem;
        }
        .stat-card .number {
            font-size: 1.5rem;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.1;
        }
        html.dark .stat-card .label { color: #94a3b8; }
        html.dark .stat-card .number { color: #f8fafc; }

        /* Per-page select styling - fix for "10" being clipped, no double arrow */
        select[name="per_page"],
        select#perPageSelect {
            width: 80px !important;
            min-height: 36px !important;
            height: 36px !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            padding-left: 10px !important;
            padding-right: 28px !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
            line-height: 36px !important;
            border-radius: 0.5rem !important;
            -webkit-appearance: none !important;
            appearance: none !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 8px center !important;
            background-size: 14px 14px !important;
        }

        /* Table Card Container */
        .table-card {
            background: #ffffff;
            border-radius: 1.25rem;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 4px 20px -2px rgba(0, 43, 107, 0.04);
            overflow: hidden;
            transition: all 0.2s ease;
        }
        html.dark .table-card {
            background: #1e293b;
            border-color: #334155;
            box-shadow: none;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
            border-collapse: collapse;
            color: #334155;
            vertical-align: middle;
        }
        html.dark .table {
            color: #cbd5e1;
        }

        .table thead th {
            background-color: #f8fafc;
            color: #475569;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 1.1rem 1.25rem;
            border-bottom: 1.5px solid #e2e8f0;
            white-space: nowrap;
        }
        html.dark .table thead th {
            background-color: #0f172a;
            color: #94a3b8;
            border-bottom-color: #334155;
        }

        .table tbody tr {
            transition: all 0.15s ease;
        }
        .table tbody td {
            padding: 1.1rem 1.25rem;
            font-size: 0.875rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        html.dark .table tbody td {
            border-bottom-color: #334155;
        }

        .table tbody tr:hover {
            background-color: rgba(240, 246, 255, 0.6) !important;
        }
        html.dark .table tbody tr:hover {
            background-color: rgba(30, 41, 59, 0.7) !important;
        }

        /* Action Buttons */
        .action-buttons {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .action-btn {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.625rem;
            border: 1px solid transparent;
            font-size: 0.875rem;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .action-btn-edit {
            background-color: #eff6ff;
            color: #2563eb;
            border-color: #dbeafe;
        }
        .action-btn-edit:hover {
            background-color: #2563eb;
            color: #ffffff;
            border-color: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
        }
        .action-btn-delete {
            background-color: #fef2f2;
            color: #dc2626;
            border-color: #fee2e2;
        }
        .action-btn-delete:hover {
            background-color: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(220, 38, 38, 0.25);
        }
        .action-btn-view {
            background-color: #f8fafc;
            color: #475569;
            border-color: #e2e8f0;
        }
        .action-btn-view:hover {
            background-color: #334155;
            color: #ffffff;
            border-color: #334155;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(51, 65, 85, 0.25);
        }
        html.dark .action-btn-edit {
            background-color: rgba(37, 99, 235, 0.15);
            color: #93c5fd;
            border-color: rgba(37, 99, 235, 0.3);
        }
        html.dark .action-btn-delete {
            background-color: rgba(220, 38, 38, 0.15);
            color: #fca5a5;
            border-color: rgba(220, 38, 38, 0.3);
        }
        html.dark .action-btn-view {
            background-color: rgba(148, 163, 184, 0.15);
            color: #cbd5e1;
            border-color: rgba(148, 163, 184, 0.3);
        }

        /* Modern Status & Meta Badges */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            line-height: 1;
        }
        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }
        .badge-status-aktif {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #a7f3d0;
        }
        .badge-status-aktif .status-dot {
            background-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }
        .badge-status-nonaktif {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .badge-status-nonaktif .status-dot {
            background-color: #ef4444;
            box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
        }
        .badge-status-cuti {
            background-color: #fffbeb;
            color: #d97706;
            border: 1px solid #fde68a;
        }
        .badge-status-cuti .status-dot {
            background-color: #f59e0b;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
        }
        html.dark .badge-status-aktif {
            background-color: rgba(6, 78, 59, 0.3);
            color: #6ee7b7;
            border-color: rgba(5, 150, 105, 0.4);
        }
        html.dark .badge-status-nonaktif {
            background-color: rgba(153, 27, 27, 0.3);
            color: #fca5a5;
            border-color: rgba(220, 38, 38, 0.4);
        }
        html.dark .badge-status-cuti {
            background-color: rgba(180, 83, 9, 0.3);
            color: #fcd34d;
            border-color: rgba(217, 119, 6, 0.4);
        }

        /* Pill Kode & Akreditasi */
        .badge-code {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.6rem;
            border-radius: 0.5rem;
            background-color: #eff6ff;
            color: #002B6B;
            font-weight: 700;
            font-size: 0.8rem;
            border: 1px solid #dbeafe;
        }
        html.dark .badge-code {
            background-color: rgba(30, 58, 138, 0.3);
            color: #93c5fd;
            border-color: rgba(59, 130, 246, 0.3);
        }

        .badge-akreditasi {
            display: inline-flex;
            align-items: center;
            padding: 0.3rem 0.65rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            background-color: #f8fafc;
            color: #334155;
            border: 1px solid #e2e8f0;
        }
        html.dark .badge-akreditasi {
            background-color: #0f172a;
            color: #cbd5e1;
            border-color: #334155;
        }

        /* Form Controls & Buttons */
        .form-control, .form-select {
            border: 1.5px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            padding: 0.65rem 1rem;
            color: #1e293b;
            background-color: #ffffff;
            transition: all 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #002B6B;
            box-shadow: 0 0 0 3.5px rgba(0, 43, 107, 0.1);
            outline: none;
        }
        html.dark .form-control, html.dark .form-select {
            background-color: #0f172a;
            border-color: #334155;
            color: #f8fafc;
        }
        html.dark .form-control:focus, html.dark .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3.5px rgba(59, 130, 246, 0.2);
        }

        .btn-primary {
            background: linear-gradient(135deg, #002B6B 0%, #001f4d 100%) !important;
            border: none !important;
            font-weight: 700 !important;
            border-radius: 0.75rem !important;
            padding: 0.65rem 1.25rem !important;
            box-shadow: 0 4px 14px rgba(0, 43, 107, 0.2) !important;
            transition: all 0.2s ease !important;
        }
        .btn-primary:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(0, 43, 107, 0.28) !important;
        }

        /* Modern Pagination */
        .pagination {
            display: flex;
            gap: 0.35rem;
            align-items: center;
            margin-bottom: 0;
        }
        .page-item .page-link {
            border-radius: 0.5rem !important;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.45rem 0.85rem;
            transition: all 0.15s;
        }
        .page-item .page-link:hover {
            background-color: #f1f5f9;
            color: #002B6B;
            border-color: #cbd5e1;
        }
        .page-item.active .page-link {
            background-color: #002B6B !important;
            border-color: #002B6B !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(0, 43, 107, 0.2);
        }
        .page-item.disabled .page-link {
            color: #94a3b8;
            background-color: #f8fafc;
            border-color: #e2e8f0;
        }
        html.dark .page-item .page-link {
            background-color: #1e293b;
            border-color: #334155;
            color: #94a3b8;
        }
        html.dark .page-item .page-link:hover {
            background-color: #334155;
            color: #f8fafc;
        }
        html.dark .page-item.active .page-link {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
            color: #ffffff !important;
        }

        /* Chart & Insight Card */
        .chart-card, .insight-card {
            background: #ffffff;
            border-radius: 1.25rem;
            padding: 1.5rem;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 4px 20px -2px rgba(0, 43, 107, 0.04);
        }

        html.dark .chart-card, html.dark .insight-card {
            background: #1e293b;
            border-color: #334155;
            box-shadow: none;
        }

        .chart-card-header, .insight-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid rgba(241, 245, 249, 1);
        }

        html.dark .chart-card-header, html.dark .insight-card-header {
            border-bottom-color: #334155;
        }

        .chart-toggle {
            display: flex;
            background-color: #f1f5f9;
            padding: 0.25rem;
            border-radius: 0.75rem;
            gap: 0.25rem;
        }

        html.dark .chart-toggle {
            background-color: #0f172a;
        }

        .chart-toggle button {
            border: none;
            background: transparent;
            padding: 0.4rem 0.85rem;
            font-size: 0.8rem;
            font-weight: 700;
            color: #64748b;
            border-radius: 0.5rem;
            transition: all 0.15s;
        }

        .chart-toggle button.active {
            background-color: #002B6B;
            color: white;
            box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);
        }

        html.dark .chart-toggle button.active {
            background-color: #3b82f6;
        }

        /* Sidebar link active & hover */
        .sidebar-link {
            transition: all 0.15s ease;
        }
        .sidebar-link:hover {
            background-color: var(--hover-bg, rgba(255, 255, 255, 0.08)) !important;
            color: #ffffff !important;
        }

        html.dark aside {
            background-color: #0f172a !important;
            border-right: 1px solid #1e293b !important;
        }
        html.dark aside .sidebar-link {
            color: #94a3b8 !important;
        }
        html.dark aside .sidebar-link:hover {
            background-color: #1e293b !important;
            color: #f8fafc !important;
        }
        html.dark aside .sidebar-link-active {
            background-color: rgba(255, 255, 255, 0.14) !important;
            color: #ffffff !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-slate-900 transition-colors duration-200">
    @php
        $sidebarBg = '#002B6B';
        $sidebarText = 'rgba(255,255,255,0.75)';
        $sidebarTitle = '#FFFFFF';
        $sidebarMuted = 'rgba(255,255,255,0.5)';
        $sidebarBorder = 'rgba(255,255,255,0.12)';
        $sidebarHover = 'rgba(255,255,255,0.08)';
        $activeBg = 'rgba(255,255,255,0.14)';
        $activeText = '#FFFFFF';
    @endphp

    <div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" class="min-h-screen flex overflow-x-hidden">

        <!-- Mobile Backdrop Overlay -->
        <div
            x-cloak
            x-show="sidebarOpen"
            x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-gray-900/45 lg:hidden"
            aria-hidden="true"></div>

        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 flex h-screen w-72 max-w-[86vw] flex-col overflow-y-auto transition-transform duration-200 lg:w-64"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:-translate-x-full'"
            style="background-color: {{ $sidebarBg }}; border-right: 1px solid {{ $sidebarBorder }};">

            <!-- Logo Header -->
            <div class="px-6 py-4" style="border-bottom: 1px solid {{ $sidebarBorder }};">
                <div class="flex items-center justify-between gap-3">
                    <a href="/" class="flex min-w-0 items-center gap-2 text-decoration-none" style="gap: 0.5rem;">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="Cendekia" class="" />
                        </div>
                        <div class="min-w-0">
                            <div class="text-base font-bold leading-tight truncate" style="color: {{ $sidebarTitle }};">Cendekia</div>
                            <div class="text-[11px] leading-tight truncate" style="color: {{ $sidebarMuted }};">Academic Portal</div>
                        </div>
                    </a>

                    <button
                        type="button"
                        @click="sidebarOpen = false"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg lg:hidden"
                        style="color: {{ $sidebarText }};"
                        aria-label="Tutup menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-3 py-4 space-y-1">
                @php
                    $menu = [
                        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                        ['label' => 'Data Dosen', 'route' => 'admin.dosen.index', 'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z'],
                        ['label' => 'Data Mahasiswa', 'route' => 'admin.mahasiswa.index', 'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z'],
                        ['label' => 'Mata Kuliah', 'route' => 'admin.mata-kuliah.index', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                        ['label' => 'Program Studi', 'route' => 'admin.program-studi.index', 'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A12.02 12.02 0 0112 21.5a12.02 12.02 0 01-6.16-10.92L12 14z'],
                        ['label' => 'Kalender Akademik', 'route' => 'admin.kalender-akademik.index', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['label' => 'Pengumuman', 'route' => 'admin.pengumuman.index', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
                    ];
                @endphp

                @foreach ($menu as $item)
                    @php
                        $activeLabel = View::yieldContent('activeMenu') ?: '';
                        $isActive = ($item['label'] === $activeLabel) || (
                            ($item['route'] === 'admin.dashboard' && request()->routeIs('admin.dashboard')) ||
                            ($item['route'] === 'admin.dosen.index' && request()->routeIs('admin.dosen.*')) ||
                            ($item['route'] === 'admin.mahasiswa.index' && request()->routeIs('admin.mahasiswa.*')) ||
                            ($item['route'] === 'admin.mata-kuliah.index' && request()->routeIs('admin.mata-kuliah.*')) ||
                            ($item['route'] === 'admin.program-studi.index' && request()->routeIs('admin.program-studi.*')) ||
                            ($item['route'] === 'admin.kalender-akademik.index' && request()->routeIs('admin.kalender-akademik.*')) ||
                            ($item['route'] === 'admin.pengumuman.index' && request()->routeIs('admin.pengumuman.*'))
                        );
                    @endphp
                    <a
                        href="{{ route($item['route']) }}"
                        class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition text-decoration-none {{ $isActive ? 'sidebar-link-active' : '' }}"
                        style="{{ $isActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                        </svg>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach

                <!-- Bottom Links Section (Matching Dosen layout) -->
                <div class="pt-4 mt-4 space-y-1" style="border-top: 1px solid {{ $sidebarBorder }};">
                    @php $isPrefActive = request()->routeIs('admin.setting*') || request()->routeIs('admin.notification-preferences.*') || request()->routeIs('admin.user.*') || request()->routeIs('profile.*'); @endphp
                    <a
                        href="{{ route('admin.setting') }}"
                        class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition text-decoration-none {{ $isPrefActive ? 'sidebar-link-active' : '' }}"
                        style="{{ $isPrefActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        </svg>
                        <span>Pengaturan</span>
                    </a>

                    @php $isHelpActive = request()->routeIs('admin.help-center.*') || request()->routeIs('help-center.*'); @endphp
                    <a
                        href="{{ route('admin.help-center.dashboard') }}"
                        class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition text-decoration-none {{ $isHelpActive ? 'sidebar-link-active' : '' }}"
                        style="{{ $isHelpActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Pusat Bantuan</span>
                    </a>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="sidebar-link w-full flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition border-none bg-transparent cursor-pointer text-left" style="color: {{ $sidebarText }}; --hover-bg: {{ $sidebarHover }};">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 min-w-0 w-full transition-all duration-200" :class="sidebarOpen ? 'lg:ml-64' : 'lg:ml-0'">

            <!-- Topbar Header -->
            <header class="sticky top-0 z-30 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 px-4 py-3 sm:px-6 lg:px-8 lg:py-4 flex items-center justify-between gap-3 transition-colors duration-200">

                <button
                    type="button"
                    @click="sidebarOpen = !sidebarOpen"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition"
                    aria-label="Toggle menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="min-w-0 flex-1 lg:hidden">
                    <p class="truncate text-sm font-bold text-gray-800 dark:text-white m-0">@yield('title', 'Admin Dashboard')</p>
                </div>

                <div class="hidden lg:block flex-1"></div>

                <div class="flex items-center justify-end gap-2 sm:gap-4">
                    <button class="relative w-9 h-9 rounded-full border border-gray-200 dark:border-slate-700 flex items-center justify-center text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <div class="min-w-0 flex items-center gap-2 bg-gray-50 dark:bg-slate-700 rounded-full pl-1 pr-2 sm:pr-4 py-1">
                        <div class="w-7 h-7 rounded-full bg-[#002B6B] flex items-center justify-center text-white text-xs font-semibold">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span class="hidden max-w-[11rem] truncate text-sm font-medium text-gray-700 dark:text-gray-200 sm:inline">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </div>
                </div>
            </header>

            <main class="w-full min-w-0 overflow-x-hidden p-4 sm:p-6 lg:p-8">
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content text-center p-4 shadow-lg" style="border-radius: 1.25rem; border: none;">
                <div class="modal-body p-0">
                    <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: #FEE2E2; border-radius: 50%;">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.5rem; color: #dc2626;"></i>
                    </div>
                    <h5 class="mb-2 font-bold text-slate-800">Hapus Data?</h5>
                    <p class="text-slate-500 mb-4 text-sm leading-relaxed">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="d-flex gap-2 w-100">
                        <button type="button" class="btn btn-light flex-grow-1 py-2 text-sm font-semibold" data-bs-dismiss="modal" style="border: 1px solid #cbd5e1; border-radius: 0.6rem; color: #475569;">Batal</button>
                        <button type="button" id="confirmDeleteBtn" class="btn btn-danger flex-grow-1 py-2 text-sm font-semibold" style="background-color: #dc2626; border: none; border-radius: 0.6rem;">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', function(e) {
                const deleteBtn = e.target.closest('.action-btn-delete');
                if (deleteBtn) {
                    e.preventDefault();
                    const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                    modal.show();

                    document.getElementById('confirmDeleteBtn').onclick = function() {
                        const form = deleteBtn.closest('form');
                        if (form) {
                            form.submit();
                        } else {
                            modal.hide();
                        }
                    };
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
