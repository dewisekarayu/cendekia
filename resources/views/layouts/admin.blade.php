<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $title ?? 'Admin Dashboard') - Cendekia</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo.jpg') }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.jpg') }}">
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
            --primary-blue: #012428;
            --secondary-blue: #012428;
            --navy-blue: #012428;
            --light-blue: #e6f4f1;
            --border-light: rgba(1, 36, 40, 0.08);
            --primary-green: #012428;
            --primary-green-hover: #033f46;
            --light-green: #e6f4f1;
        }

        .text-primary {
            color: #012428 !important;
        }
        .bg-primary {
            background-color: #012428 !important;
        }
        .border-primary {
            border-color: #012428 !important;
        }
        .btn-outline-primary {
            color: #012428 !important;
            border-color: #012428 !important;
            background-color: transparent !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            transition: all 0.2s ease !important;
        }
        .btn-outline-primary:hover {
            background-color: #012428 !important;
            border-color: #012428 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(1, 36, 40, 0.2) !important;
        }
        .btn-outline-secondary {
            color: #334155 !important;
            border-color: #e2e8f0 !important;
            background-color: #ffffff !important;
            border-radius: 0.75rem !important;
            font-weight: 600 !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }
        .btn-outline-secondary:hover {
            background-color: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
            box-shadow: 0 4px 12px rgba(1, 36, 40, 0.06) !important;
        }

        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
            background-color: #f8fafc;
            color: #1e293b;
            overflow-x: clip;
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
            background-color: #0b1120 !important;
            color: #cbd5e1 !important;
        }

        html.dark body {
            background-color: #012428 !important;
            color: #d1e7dc !important;
        }
        html.dark .bg-white,
        html.dark .bg-slate-800,
        html.dark .bg-gray-800,
        html.dark .bg-light {
            background-color: #102820 !important;
            color: #e5f5ed !important;
        }
        html.dark .bg-slate-900,
        html.dark .bg-gray-900,
        html.dark .bg-slate-50,
        html.dark .bg-gray-50,
        html.dark .bg-gray-100,
        html.dark .bg-slate-100 {
            background-color: #012428 !important;
        }
        html.dark :is([class*="dark:bg-slate-"], [class*="dark:bg-gray-"]) {
            background-color: #102820 !important;
        }
        html.dark :is([class*="dark:bg-slate-900"], [class*="dark:bg-slate-950"], [class*="dark:bg-gray-900"], [class*="dark:bg-gray-950"]) {
            background-color: #012428 !important;
        }
        html.dark [class*="dark:bg-blue-"] {
            background-color: rgba(16, 185, 129, 0.16) !important;
        }
        html.dark [class*="bg-blue-"] {
            background-color: rgba(16, 185, 129, 0.16) !important;
        }
        html.dark [class*="dark:hover:bg-slate-"]:hover,
        html.dark [class*="dark:hover:bg-gray-"]:hover {
            background-color: #17382b !important;
        }
        html.dark [class*="border-slate-"],
        html.dark [class*="border-gray-"] {
            border-color: #25463a !important;
        }
        html.dark aside {
            background-color: #012428 !important;
        }
        html.dark aside .sidebar-link-active {
            background-color: #145943 !important;
            color: #f0fdf4 !important;
        }
        html.dark .settings-container,
        html.dark .admin-confirm-popup,
        html.dark .modal-content,
        html.dark .swal2-popup {
            background-color: #102820 !important;
            border-color: #25463a !important;
            color: #e5f5ed !important;
        }
        html.dark .settings-header h3,
        html.dark .form-label {
            color: #e5f5ed !important;
        }
        html.dark :is([class~="bg-blue-50"], [class~="bg-blue-100"], [class~="bg-blue-200"], [class~="dark:bg-blue-50"], [class~="dark:bg-blue-100"], [class~="dark:bg-blue-200"]) {
            background-color: rgba(16, 185, 129, 0.16) !important;
        }
        html.dark :is([class~="bg-blue-500"], [class~="bg-blue-600"], [class~="bg-blue-700"], [class~="bg-blue-800"], [class~="bg-blue-900"], [class~="dark:bg-blue-500"], [class~="dark:bg-blue-600"], [class~="dark:bg-blue-700"], [class~="dark:bg-blue-800"], [class~="dark:bg-blue-900"]) {
            background-color: #145943 !important;
        }
        html.dark :is([class~="text-blue-500"], [class~="text-blue-600"], [class~="text-blue-700"], [class~="text-blue-800"], [class~="text-blue-900"], [class~="dark:text-blue-300"], [class~="dark:text-blue-400"], [class~="text-primary"]) {
            color: #6ee7b7 !important;
        }
        html.dark :is([class~="border-blue-100"], [class~="border-blue-200"], [class~="border-blue-300"], [class~="dark:border-blue-700"]) {
            border-color: rgba(52, 211, 153, 0.3) !important;
        }
        html.dark [class*="text-blue-"] {
            color: #6ee7b7 !important;
        }
        html.dark input,
        html.dark select,
        html.dark textarea,
        html.dark .form-control,
        html.dark .form-select {
            background-color: #091f19 !important;
            border-color: #315747 !important;
            color: #e5f5ed !important;
        }
        aside nav {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        aside nav::-webkit-scrollbar {
            display: none;
            width: 0;
            height: 0;
        }

        html.dark .bg-white {
            background-color: #151f32 !important;
        }

        html.dark .bg-slate-50,
        html.dark .bg-gray-50,
        html.dark .bg-gray-50\/50,
        html.dark .bg-light {
            background-color: #0b1120 !important;
        }

        html.dark .border-slate-200,
        html.dark .border-slate-100,
        html.dark .border-gray-200,
        html.dark .border-gray-100,
        html.dark .border-gray-50,
        html.dark .border-gray-250 {
            border-color: #23334d !important;
        }

        html.dark .text-slate-800,
        html.dark .text-gray-900,
        html.dark .text-gray-850,
        html.dark .text-gray-800,
        html.dark .text-dark,
        html.dark .text-black,
        html.dark .text-body {
            color: #f8fafc !important;
        }

        html.dark .text-slate-500,
        html.dark .text-gray-500,
        html.dark .text-muted {
            color: #94a3b8 !important;
        }

        html.dark .text-blue-900 {
            color: #60a5fa !important;
        }

        html.dark .text-primary {
            color: #60a5fa !important;
        }

        html.dark .bg-blue-900 {
            background-color: #2563eb !important;
            color: #ffffff !important;
        }

        /* All table headers and light containers in dark mode */
        html.dark thead,
        html.dark thead th,
        html.dark thead.table-light,
        html.dark .table-light,
        html.dark thead[style*="background-color: #f8fafc"],
        html.dark [style*="background-color: #f8fafc"],
        html.dark [style*="background-color:#f8fafc"] {
            background-color: #0b1120 !important;
            color: #94a3b8 !important;
            border-color: #23334d !important;
        }

        html.dark thead tr {
            background-color: #0b1120 !important;
            border-color: #23334d !important;
        }

        html.dark table thead th {
            background-color: #0b1120 !important;
            color: #94a3b8 !important;
            border-bottom: 1px solid #23334d !important;
        }

        /* Badges & Pills in Dark Mode */
        html.dark .badge.bg-indigo-50,
        html.dark .badge.bg-indigo-100,
        html.dark .bg-indigo-50 {
            background-color: rgba(99, 102, 241, 0.18) !important;
            color: #a5b4fc !important;
            border-color: rgba(99, 102, 241, 0.35) !important;
        }

        html.dark .badge.bg-slate-100,
        html.dark .badge.bg-gray-100,
        html.dark .badge.bg-light,
        html.dark .bg-gray-100,
        html.dark .bg-slate-100 {
            background-color: rgba(51, 65, 85, 0.6) !important;
            color: #e2e8f0 !important;
            border-color: rgba(71, 85, 105, 0.5) !important;
        }

        html.dark .badge.bg-blue-50,
        html.dark .badge.bg-blue-100,
        html.dark .bg-blue-50 {
            background-color: rgba(37, 99, 235, 0.18) !important;
            color: #93c5fd !important;
            border-color: rgba(59, 130, 246, 0.35) !important;
        }

        html.dark .badge.bg-orange-50,
        html.dark .badge.bg-orange-100,
        html.dark .bg-orange-50 {
            background-color: rgba(234, 88, 12, 0.18) !important;
            color: #fdba74 !important;
            border-color: rgba(234, 88, 12, 0.35) !important;
        }

        html.dark .badge.bg-emerald-50,
        html.dark .badge.bg-emerald-100,
        html.dark .badge.bg-green-50,
        html.dark .bg-emerald-50 {
            background-color: rgba(16, 185, 129, 0.18) !important;
            color: #6ee7b7 !important;
            border-color: rgba(16, 185, 129, 0.35) !important;
        }

        html.dark .text-indigo-700 {
            color: #a5b4fc !important;
        }
        html.dark .text-slate-700 {
            color: #cbd5e1 !important;
        }
        html.dark .text-blue-700 {
            color: #93c5fd !important;
        }
        html.dark .text-orange-700,
        html.dark .text-orange-600 {
            color: #fdba74 !important;
        }
        html.dark .text-emerald-700,
        html.dark .text-emerald-600 {
            color: #6ee7b7 !important;
        }
        html.dark .border-indigo-100 {
            border-color: rgba(99, 102, 241, 0.3) !important;
        }
        html.dark .border-blue-250,
        html.dark .border-blue-200 {
            border-color: rgba(59, 130, 246, 0.3) !important;
        }
        html.dark .border-orange-200 {
            border-color: rgba(234, 88, 12, 0.3) !important;
        }
        html.dark .border-emerald-250,
        html.dark .border-emerald-200 {
            border-color: rgba(16, 185, 129, 0.3) !important;
        }

        /* Form Inputs & Selects in Dark Mode */
        html.dark input[type="text"],
        html.dark input[type="email"],
        html.dark input[type="password"],
        html.dark input[type="number"],
        html.dark input[type="search"],
        html.dark input[type="date"],
        html.dark input[type="time"],
        html.dark select,
        html.dark textarea,
        html.dark .form-control,
        html.dark .form-select {
            background-color: #0b1120 !important;
            border-color: #23334d !important;
            color: #f8fafc !important;
        }

        html.dark input::placeholder,
        html.dark textarea::placeholder {
            color: #64748b !important;
        }

        html.dark select option {
            background-color: #151f32 !important;
            color: #f8fafc !important;
        }

        html.dark .form-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3E%3C/svg%3E") !important;
        }

        /* Bootstrap Modals & Forms Dark Mode */
        html.dark .modal-content {
            background-color: #151f32 !important;
            border-color: #23334d !important;
            color: #f8fafc !important;
        }
        
        html.dark .modal-header,
        html.dark .modal-footer {
            border-color: #23334d !important;
        }
        
        html.dark .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
        
        html.dark .form-label {
            color: #cbd5e1 !important;
        }

        /* Bootstrap Card Dark Mode */
        html.dark .card,
        html.dark .card.bg-white {
            background-color: #151f32 !important;
            border-color: #23334d !important;
            color: #f8fafc !important;
        }

        html.dark .card .fw-bold,
        html.dark .card .fw-semibold,
        html.dark .card h1,
        html.dark .card h2,
        html.dark .card h3,
        html.dark .card h4,
        html.dark .card h5,
        html.dark .card h6 {
            color: #f1f5f9 !important;
        }

        html.dark .card .text-secondary,
        html.dark .card .fw-semibold.text-secondary {
            color: #94a3b8 !important;
        }

        /* Fix judul warna #012428 & #002B6B (hardcoded inline style) */
        html.dark [style*="color: #012428"],
        html.dark [style*="color:#012428"],
        html.dark [style*="color: #002B6B"],
        html.dark [style*="color:#002B6B"] {
            color: #6ee7b7 !important;
        }

        /* Fix btn-light & btn-outline-primary di dark mode */
        html.dark .btn-light {
            background-color: #151f32 !important;
            border-color: #23334d !important;
            color: #93c5fd !important;
        }

        html.dark .btn-light:hover {
            background-color: #23334d !important;
            border-color: #3b82f6 !important;
            color: #bfdbfe !important;
        }

        html.dark .btn-outline-primary {
            color: #93c5fd !important;
            border-color: #3b82f6 !important;
        }
        html.dark .btn-outline-primary:hover {
            background-color: #2563eb !important;
            color: #ffffff !important;
        }

        /* Fix border-top pada card pengumuman */
        html.dark .card[style*="border-left"] {
            border-left-color: inherit !important;
        }

        /* Fix alert dark mode */
        html.dark .alert-success {
            background-color: rgba(6, 78, 59, 0.3) !important;
            border-color: rgba(5, 150, 105, 0.4) !important;
            color: #6ee7b7 !important;
        }

        /* Fix text-slate-600, text-slate-700 in dark */
        html.dark .text-slate-600,
        html.dark .text-slate-700,
        html.dark .text-gray-600,
        html.dark .text-gray-700 {
            color: #cbd5e1 !important;
        }

        html.dark .text-secondary {
            color: #94a3b8 !important;
        }

        /* Fix fw-bold text that isn't explicit text-slate classes */
        html.dark .fw-bold:not([class*="text-"]):not([style*="color"]):not(.badge),
        html.dark .fw-semibold:not([class*="text-"]):not([style*="color"]):not(.badge) {
            color: #f1f5f9 !important;
        }

        /* Table name and sub-text fix */
        html.dark .table .fw-bold {
            color: #f1f5f9 !important;
        }

        html.dark small.text-slate-400 {
            color: #64748b !important;
        }

        /* Semantic classes for table rows (works regardless of Tailwind JIT) */
        html.dark .dosen-name {
            color: #f1f5f9 !important;
        }

        html.dark .dosen-email {
            color: #64748b !important;
        }

        /* Row number in table */
        html.dark .table .font-monospace.fw-bold {
            color: #64748b !important;
        }

        /* badge-code (NIDN/NIM badge) dark fix */
        html.dark .badge-code {
            background-color: rgba(30, 58, 138, 0.3) !important;
            color: #93c5fd !important;
            border-color: rgba(59, 130, 246, 0.3) !important;
        }

        /* =========================================================
           PREMIUM TABLE & CARD STYLING FOR ALL ADMIN MODULES
           ========================================================= */
        .page-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: #012428;
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
            color: #012428;
        }
        .breadcrumb-item.active {
            color: #012428;
            font-weight: 700;
        }
        html.dark .breadcrumb-item a {
            color: #94a3b8;
        }
        html.dark .breadcrumb-item.active {
            color: #6ee7b7;
        }

        /* Stat Cards */
        .stat-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 4px 16px -2px rgba(1, 36, 40, 0.04);
            transition: all 0.2s ease;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px -4px rgba(1, 36, 40, 0.08);
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
            background-color: #e6f4f1;
            color: #012428;
            border: 1px solid #c2e5de;
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
        html.dark .stat-card-icon.blue { background-color: rgba(6, 78, 59, 0.3); color: #6ee7b7; border-color: rgba(5, 150, 105, 0.4); }
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
            box-shadow: 0 4px 20px -2px rgba(1, 36, 40, 0.04);
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
            --bs-table-bg: transparent;
        }
        html.dark .table {
            color: #cbd5e1;
            --bs-table-bg: transparent;
            --bs-table-color: #cbd5e1;
            --bs-table-striped-bg: rgba(30, 41, 59, 0.5);
            --bs-table-hover-bg: rgba(30, 41, 59, 0.7);
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
        html.dark .table tbody tr {
            background-color: transparent !important;
        }
        .table tbody td {
            padding: 1.1rem 1.25rem;
            font-size: 0.875rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            background-color: transparent !important;
        }
        html.dark .table tbody td {
            background-color: transparent !important;
            border-bottom-color: #334155;
            color: #cbd5e1 !important;
        }

        /* Explicitly force all text inside table cells to be readable in dark mode */
        html.dark .table tbody td *:not(.badge-status):not(.status-dot):not(.action-btn):not(.badge-code) {
            color: inherit;
        }

        html.dark .table tbody td .fw-bold,
        html.dark .table tbody td .fw-semibold {
            color: #f1f5f9 !important;
        }

        html.dark .table tbody td small {
            color: #64748b !important;
        }

        html.dark .dosen-name {
            color: #f1f5f9 !important;
        }

        html.dark .dosen-email {
            color: #64748b !important;
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
            background-color: #e6f4f1;
            color: #012428;
            font-weight: 700;
            font-size: 0.8rem;
            border: 1px solid #c2e5de;
        }
        html.dark .badge-code {
            background-color: rgba(6, 78, 59, 0.3);
            color: #6ee7b7;
            border-color: rgba(5, 150, 105, 0.4);
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
            border-color: #012428;
            box-shadow: 0 0 0 3.5px rgba(1, 36, 40, 0.12);
            outline: none;
        }
        html.dark .form-control, html.dark .form-select {
            background-color: #0f172a;
            border-color: #475569;
            color: #f8fafc;
        }
        html.dark .form-control::placeholder, html.dark .form-select::placeholder {
            color: #94a3b8 !important;
        }
        html.dark .form-control:focus, html.dark .form-select:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3.5px rgba(16, 185, 129, 0.2);
        }

        /* ===== Dark mode: Bootstrap utility harmonization ===== */
        [data-pg-card] { border-left: 4px solid #10b981 !important; transition: transform .2s, box-shadow .2s; }
        [data-pg-card]:hover { transform: translateY(-2px); box-shadow: 0 10px 24px -8px rgba(1,36,40,.18) !important; }
        [data-pg-footer] { border-color: #f1f5f9 !important; }
        .pg-title { color: #012428; }
        .pg-target { font-size: .7rem; border-radius: 6px; letter-spacing: .5px; }
        .pg-target-all { background-color: #ECFDF5; color: #059669 !important; }
        .pg-target-group { background-color: #F1F5F9; color: #64748b !important; }
        html.dark [data-pg-card] { border-left-color: #60a5fa !important; }
        html.dark [data-pg-card]:hover { box-shadow: 0 10px 28px -10px rgba(0,0,0,.6) !important; }
        html.dark [data-pg-footer] { border-color: #334155 !important; }
        html.dark .pg-title { color: #f1f5f9; }
        html.dark .pg-target-all { background-color: rgba(16,185,129,.15) !important; color: #6ee7b7 !important; border: 1px solid rgba(16,185,129,.3); }
        html.dark .pg-target-group { background-color: rgba(148,163,184,.12) !important; color: #cbd5e1 !important; border: 1px solid #334155; }
        /* Generic: inline light-colored badges become subtle in dark mode */
        html.dark .badge[style*="background-color: #F1F5F9"], html.dark .badge[style*="background-color: #f1f5f9"] {
            background-color: rgba(148,163,184,.12) !important; color: #cbd5e1 !important;
        }
        html.dark .badge[style*="background-color: #ECFDF5"] { background-color: rgba(16,185,129,.15) !important; color: #6ee7b7 !important; }
        html.dark .badge[style*="background-color: #eff6ff"], html.dark .badge[style*="background-color: #EFF6FF"] { background-color: rgba(59,130,246,.15) !important; color: #93c5fd !important; }
        html.dark .form-control, html.dark .form-select {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        html.dark .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2394a3b8' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
        }
        html.dark .form-select option { background: #0f172a; color: #e2e8f0; }
        html.dark input[type="date"]::-webkit-calendar-picker-indicator,
        html.dark input[type="time"]::-webkit-calendar-picker-indicator { filter: invert(0.75); }
        html.dark .input-group-text {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #94a3b8 !important;
        }
        html.dark .form-label, html.dark label { color: #cbd5e1; }

        html.dark .card, html.dark .card.bg-white, html.dark .bg-white:not(.bg-opacity-10):not(.bg-opacity-20):not(.bg-opacity-25) {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: #e2e8f0;
        }
        html.dark .bg-white.bg-opacity-10 { background-color: rgba(255,255,255,.08) !important; }
        html.dark .bg-white.bg-opacity-20 { background-color: rgba(255,255,255,.15) !important; }
        html.dark .bg-light {
            background-color: #0f172a !important;
            border-color: #334155 !important;
        }
        html.dark .badge.bg-light {
            background-color: rgba(59, 130, 246, 0.12) !important;
            color: #93c5fd !important;
            border: 1px solid rgba(59, 130, 246, 0.3) !important;
        }
        html.dark .text-dark { color: #e2e8f0 !important; }
        html.dark .text-secondary, html.dark .text-muted { color: #94a3b8 !important; }
        html.dark .border, html.dark .border-bottom, html.dark .border-top { border-color: #334155 !important; }
        html.dark hr { border-color: #334155; opacity: 1; }

        html.dark .btn-light {
            background-color: transparent !important;
            border: 1px solid #334155 !important;
            color: #cbd5e1 !important;
        }
        html.dark .btn-light:hover { background-color: #334155 !important; color: #fff !important; }
        html.dark .btn-check:checked + .btn, html.dark .btn-group .btn.active { color: #fff !important; }
        html.dark .btn-group .btn:not(.active):not(:checked) { color: #94a3b8; }

        /* Select2 */
        html.dark .select2-container--default .select2-selection,
        html.dark .select2-dropdown {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        html.dark .select2-container--default .select2-selection__rendered { color: #e2e8f0 !important; }
        html.dark .select2-container--default .select2-selection__choice {
            background-color: rgba(59,130,246,.15) !important;
            border-color: rgba(59,130,246,.35) !important;
            color: #bfdbfe !important;
        }
        html.dark .select2-search__field { background: transparent !important; color: #e2e8f0 !important; }
        html.dark .select2-results__option { color: #cbd5e1; }
        html.dark .select2-results__option--highlighted { background-color: #1d4ed8 !important; color: #fff !important; }

        .btn-primary {
            background: #012428 !important;
            border: none !important;
            font-weight: 700 !important;
            border-radius: 0.75rem !important;
            padding: 0.65rem 1.25rem !important;
            box-shadow: 0 4px 14px rgba(1, 36, 40, 0.25) !important;
            transition: all 0.2s ease !important;
        }
        .btn-primary:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(1, 36, 40, 0.35) !important;
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
            color: #012428;
            border-color: #cbd5e1;
        }
        .page-item.active .page-link {
            background-color: #012428 !important;
            border-color: #012428 !important;
            color: #ffffff !important;
            box-shadow: 0 4px 10px rgba(1, 36, 40, 0.25);
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
            background-color: #10b981 !important;
            border-color: #10b981 !important;
            color: #ffffff !important;
        }

        /* Chart & Insight Card */
        .chart-card, .insight-card {
            background: #ffffff;
            border-radius: 1.25rem;
            padding: 1.5rem;
            border: 1px solid rgba(226, 232, 240, 0.9);
            box-shadow: 0 4px 20px -2px rgba(1, 36, 40, 0.04);
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
            background-color: #012428;
            color: white;
            box-shadow: 0 4px 12px rgba(1, 36, 40, 0.2);
        }

        html.dark .chart-toggle button.active {
            background-color: #10b981;
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
            background-color: #011d20 !important;
            border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
        }
        html.dark aside .sidebar-link {
            color: rgba(255, 255, 255, 0.75) !important;
        }
        html.dark aside .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
        }
        html.dark aside .sidebar-link-active {
            background-color: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
        }
    </style>
    @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-slate-900 transition-colors duration-200">
    @php
        $sidebarBg = '#012428';
        $sidebarText = 'rgba(255,255,255,0.85)';
        $sidebarTitle = '#FFFFFF';
        $sidebarMuted = 'rgba(255,255,255,0.55)';
        $sidebarBorder = 'rgba(255,255,255,0.08)';
        $sidebarHover = 'rgba(255,255,255,0.08)';
        $activeBg = 'rgba(255,255,255,0.15)';
        $activeText = '#FFFFFF';
    @endphp

    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex overflow-x-hidden">

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
            class="fixed inset-y-0 left-0 z-50 flex h-screen w-72 max-w-[86vw] -translate-x-full flex-col overflow-hidden transition-transform duration-200 lg:fixed lg:top-0 lg:left-0 lg:z-50 lg:h-screen lg:w-64 lg:max-w-none lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            style="background-color: {{ $sidebarBg }}; border-right: 1px solid {{ $sidebarBorder }};">

            <!-- Logo Header -->
            <div class="px-6 py-4" style="border-bottom: 1px solid {{ $sidebarBorder }};">
                <div class="flex items-center justify-between gap-3">
                    <a href="/" class="flex min-w-0 items-center gap-2 text-decoration-none" style="gap: 0.5rem;">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0">
                            <img src="{{ asset('images/logo.jpg') }}" alt="Cendekia" class="rounded-full object-cover" />
                        </div>
                        <div class="min-w-0">
                            <div class="text-base font-bold leading-tight truncate notranslate" translate="no" style="color: {{ $sidebarTitle }};">Cendekia</div>
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
            <nav class="min-h-0 flex-1 overflow-y-auto px-3 py-4 space-y-1">
                @php $isActive = request()->routeIs('admin.dashboard'); @endphp
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition text-decoration-none {{ $isActive ? 'sidebar-link-active' : '' }}"
                    style="{{ $isActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard</span>
                </a>

                <div x-data="{ open: {{ request()->routeIs('admin.dosen.*', 'admin.mahasiswa.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-sm font-medium transition cursor-pointer border-0 bg-transparent text-left sidebar-link" style="color: {{ $sidebarText }}; --hover-bg: {{ $sidebarHover }};" x-bind:style="open ? 'color: {{ $activeText }};' : 'color: {{ $sidebarText }};'">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-4a4 4 0 10-8 0 4 4 0 008 0zm6 0a4 4 0 10-8 0 4 4 0 008 0z"/></svg>
                            <span>Data Pengguna</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" x-collapse class="pl-10 pr-2 py-1 space-y-1">
                        @php $isActive = request()->routeIs('admin.dosen.*'); @endphp
                        <a href="{{ route('admin.dosen.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg transition text-decoration-none sidebar-link {{ $isActive ? 'sidebar-link-active' : '' }}" style="{{ $isActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">Data Dosen</a>
                        @php $isActive = request()->routeIs('admin.mahasiswa.*'); @endphp
                        <a href="{{ route('admin.mahasiswa.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg transition text-decoration-none sidebar-link {{ $isActive ? 'sidebar-link-active' : '' }}" style="{{ $isActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">Data Mahasiswa</a>
                    </div>
                </div>

                <div x-data="{ open: {{ request()->routeIs('admin.fakultas.*', 'admin.program-studi.*', 'admin.tahun-akademik.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-sm font-medium transition cursor-pointer border-0 bg-transparent text-left sidebar-link" style="color: {{ $sidebarText }}; --hover-bg: {{ $sidebarHover }};" x-bind:style="open ? 'color: {{ $activeText }};' : 'color: {{ $sidebarText }};'">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>Data Akademik</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" x-collapse class="pl-10 pr-2 py-1 space-y-1">
                        @php $isActive = request()->routeIs('admin.fakultas.*'); @endphp
                        <a href="{{ route('admin.fakultas.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg transition text-decoration-none sidebar-link {{ $isActive ? 'sidebar-link-active' : '' }}" style="{{ $isActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">Fakultas</a>
                        @php $isActive = request()->routeIs('admin.program-studi.*'); @endphp
                        <a href="{{ route('admin.program-studi.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg transition text-decoration-none sidebar-link {{ $isActive ? 'sidebar-link-active' : '' }}" style="{{ $isActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">Program Studi</a>
                        @php $isActive = request()->routeIs('admin.tahun-akademik.*'); @endphp
                        <a href="{{ route('admin.tahun-akademik.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg transition text-decoration-none sidebar-link {{ $isActive ? 'sidebar-link-active' : '' }}" style="{{ $isActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">Tahun Akademik</a>
                    </div>
                </div>

                <div x-data="{ open: {{ request()->routeIs('admin.mata-kuliah.*', 'admin.kelas.*') ? 'true' : 'false' }} }" class="space-y-1">
                    <button @click="open = !open" type="button" class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-lg text-sm font-medium transition cursor-pointer border-0 bg-transparent text-left sidebar-link" style="color: {{ $sidebarText }}; --hover-bg: {{ $sidebarHover }};" x-bind:style="open ? 'color: {{ $activeText }};' : 'color: {{ $sidebarText }};'">
                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            <span>Perkuliahan</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="open" x-collapse class="pl-10 pr-2 py-1 space-y-1">
                        @php $isActive = request()->routeIs('admin.kelas.*'); @endphp
                        <a href="{{ route('admin.kelas.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg transition text-decoration-none sidebar-link {{ $isActive ? 'sidebar-link-active' : '' }}" style="{{ $isActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">Kelas Perkuliahan</a>
                        @php $isActive = request()->routeIs('admin.mata-kuliah.*'); @endphp
                        <a href="{{ route('admin.mata-kuliah.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg transition text-decoration-none sidebar-link {{ $isActive ? 'sidebar-link-active' : '' }}" style="{{ $isActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">Mata Kuliah</a>
                    </div>
                </div>

                @php $isActive = request()->routeIs('admin.kalender-akademik.*'); @endphp
                <a href="{{ route('admin.kalender-akademik.index') }}"
                    class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition text-decoration-none {{ $isActive ? 'sidebar-link-active' : '' }}"
                    style="{{ $isActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Kalender Akademik</span>
                </a>

                @php $isActive = request()->routeIs('admin.pengumuman.*'); @endphp
                <a href="{{ route('admin.pengumuman.index') }}"
                    class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition text-decoration-none {{ $isActive ? 'sidebar-link-active' : '' }}"
                    style="{{ $isActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    <span>Pengumuman</span>
                </a>

                <!-- Bottom Links Section (Matching Dosen layout) -->
                <div class="pt-4 mt-4 space-y-1" style="border-top: 1px solid {{ $sidebarBorder }};">
                    @php $isPrefActive = request()->routeIs('admin.setting*') || request()->routeIs('profile.*'); @endphp
                    <a
                        href="{{ route('admin.setting') }}"
                        class="sidebar-link flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-medium transition text-decoration-none {{ $isPrefActive ? 'sidebar-link-active' : '' }}"
                        style="{{ $isPrefActive ? 'background-color: '.$activeBg.'; color: '.$activeText.';' : 'color: '.$sidebarText.';' }} --hover-bg: {{ $sidebarHover }};">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        </svg>
                        <span>Pengaturan</span>
                    </a>

                    <!-- Kelola Akun Pengguna dihapus sesuai permintaan -->

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
        <div class="flex-1 min-w-0 w-full lg:ml-64">

            <!-- Topbar Header -->
            <header class="sticky top-0 z-30 bg-white dark:bg-slate-800 border-b border-gray-100 dark:border-slate-700 px-3 py-3 sm:px-6 lg:px-8 lg:py-4 flex items-center justify-between gap-2.5 sm:gap-3 transition-colors duration-200">

                <button
                    type="button"
                    @click="sidebarOpen = !sidebarOpen"
                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-gray-200 dark:border-slate-700 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-slate-700 transition lg:hidden"
                    aria-label="Toggle menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="min-w-0 flex-1">
                    <h1 class="text-sm sm:text-base font-bold text-gray-800 dark:text-white truncate m-0" title="@yield('title', 'Admin Dashboard')">
                        @yield('title', 'Admin Dashboard')
                    </h1>
                </div>

                <div class="flex items-center justify-end gap-1.5 sm:gap-3 shrink-0">
                    {{-- Quick Language Switcher --}}
                    @php 
                        $currentLocale = app()->getLocale(); 
                        $targetLocale = $currentLocale === 'en' ? 'id' : 'en';
                    @endphp
                    <a href="{{ route('admin.switch-language', $targetLocale) }}"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition text-decoration-none shadow-xs notranslate shrink-0"
                       translate="no"
                       onclick="if ('{{ $targetLocale }}' === 'id') { document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;'; document.cookie = 'googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=.' + window.location.hostname + ';'; }"
                       title="{{ $currentLocale === 'en' ? 'Ubah ke Bahasa Indonesia' : 'Switch to English' }}">
                        <svg class="w-3.5 h-3.5 text-slate-500 dark:text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        <span>{{ strtoupper($currentLocale) }}</span>
                    </a>

                    {{-- Notification Bell --}}
                    @php
                        $recentNotifs = auth()->check() ? auth()->user()->notifikasi()->with(['kelasPerkuliahan.mataKuliah'])->orderBy('created_at', 'desc')->take(7)->get() : collect();
                        $unreadCount = auth()->check() ? auth()->user()->notifikasi()->whereNull('dibaca_pada')->count() : 0;
                    @endphp
                    <div class="relative shrink-0" 
                         x-data="{ 
                            notifOpen: false, 
                            unreadCount: {{ $unreadCount }},
                            pollNotifications() {
                                fetch('{{ route('notifikasi.poll') }}', { headers: { 'Accept': 'application/json' } })
                                    .then(r => r.json())
                                    .then(data => {
                                        if (data && typeof data.unread_count !== 'undefined') {
                                            this.unreadCount = data.unread_count;
                                        }
                                    })
                                    .catch(() => {});
                            },
                            init() {
                                setInterval(() => this.pollNotifications(), 25000);
                            }
                         }">
                        {{-- Tombol Lonceng Admin Proporsional & Elegan --}}
                        <button @click="notifOpen = !notifOpen" @click.away="notifOpen = false" 
                                type="button"
                                class="relative inline-flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-700/80 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition focus:outline-none" 
                                aria-label="Notifikasi">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700 dark:text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            <span x-show="unreadCount > 0"
                                  x-text="unreadCount > 99 ? '99+' : unreadCount"
                                  class="bg-rose-600 text-white font-black rounded-full ring-2 ring-white dark:ring-slate-800 shadow-xs pointer-events-none"
                                  style="position: absolute !important; top: -4px !important; right: -4px !important; min-width: 18px !important; height: 18px !important; padding: 0 4px !important; font-size: 10px !important; line-height: 18px !important; text-align: center !important; z-index: 10 !important; {{ $unreadCount > 0 ? 'display: inline-block !important;' : 'display: none !important;' }}">
                                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            </span>
                        </button>

                        <div x-show="notifOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                             class="absolute right-0 mt-2.5 w-84 sm:w-96 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-gray-100 dark:border-slate-800 overflow-hidden z-50 text-left"
                             style="display: none;">
                            
                            {{-- Header --}}
                            <div class="px-4 py-3 bg-slate-50/80 dark:bg-slate-800/80 border-b border-gray-100 dark:border-slate-800 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm font-black text-slate-800 dark:text-white m-0">Notifikasi</h3>
                                    <template x-if="unreadCount > 0">
                                        <span class="px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300 text-[11px] font-bold"
                                              x-text="unreadCount + ' Baru'">
                                            {{ $unreadCount }} Baru
                                        </span>
                                    </template>
                                </div>
                                @if($unreadCount > 0)
                                    <form action="{{ route('notifikasi.baca-semua') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 hover:underline transition bg-transparent border-0 p-0 cursor-pointer">
                                            Tandai semua dibaca
                                        </button>
                                    </form>
                                @endif
                            </div>

                            {{-- List --}}
                            <div class="max-h-[380px] overflow-y-auto divide-y divide-gray-50 dark:divide-slate-800/60">
                                @forelse($recentNotifs as $notif)
                                    @php 
                                        $isUnread = is_null($notif->dibaca_pada);
                                        $meta = $notif->getTypeMeta();
                                        $namaMk = $notif->kelasPerkuliahan->mataKuliah->nama_mk ?? null;
                                    @endphp
                                    <a href="{{ route('notifikasi.baca', $notif->id) }}" 
                                       class="flex items-start gap-3 p-3.5 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition group text-decoration-none {{ $isUnread ? 'bg-blue-50/40 dark:bg-slate-800/40' : '' }}">
                                        
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 shadow-xs {{ $meta['icon_bg'] }}">
                                            @if($notif->tipe === 'tugas')
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                                </svg>
                                            @elseif($notif->tipe === 'materi')
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                </svg>
                                            @elseif($notif->tipe === 'presensi')
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @elseif($notif->tipe === 'nilai')
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @elseif($notif->tipe === 'pengumuman' || $notif->tipe === 'informasi')
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                </svg>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-2">
                                                <h4 class="text-xs font-bold {{ $isUnread ? 'text-slate-900 dark:text-white' : 'text-slate-700 dark:text-slate-300' }} truncate leading-snug group-hover:text-blue-600 dark:group-hover:text-blue-400 transition m-0">
                                                    {{ $notif->judul }}
                                                </h4>
                                                <span class="text-[10px] text-gray-400 dark:text-slate-500 font-medium shrink-0">
                                                    {{ $notif->created_at->diffForHumans(null, true) }}
                                                </span>
                                            </div>

                                            <p class="text-[11px] {{ $isUnread ? 'text-slate-600 dark:text-slate-300' : 'text-slate-400 dark:text-slate-500' }} truncate mt-1 m-0">
                                                @if($namaMk)
                                                    <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $namaMk }}</span>
                                                    <span class="text-slate-300 dark:text-slate-600 mx-1">&bull;</span>
                                                @endif
                                                <span>{{ Str::limit($notif->pesan, 65) }}</span>
                                            </p>
                                        </div>

                                        @if($isUnread)
                                            <span class="w-2 h-2 mt-1.5 shrink-0 rounded-full bg-blue-600 ring-2 ring-blue-100 dark:ring-blue-900/50"></span>
                                        @endif
                                    </a>
                                @empty
                                    <div class="px-4 py-8 text-center">
                                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-slate-50 dark:bg-slate-800 text-slate-400 mb-2.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                            </svg>
                                        </div>
                                        <p class="text-xs font-bold text-slate-700 dark:text-slate-300 m-0">Belum Ada Notifikasi</p>
                                        <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-0.5 m-0">Pembaruan sistem akan tampil di sini</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="p-2 bg-slate-50/80 dark:bg-slate-800/80 border-t border-gray-100 dark:border-slate-800">
                                <a href="{{ route('notifikasi.index') }}" 
                                   class="flex items-center justify-center gap-1.5 w-full py-2 text-center text-xs font-bold text-slate-700 dark:text-slate-200 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-white dark:hover:bg-slate-700/60 rounded-xl transition shadow-2xs text-decoration-none">
                                    <span>Lihat Semua Notifikasi</span>
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="min-w-0 flex items-center gap-2 bg-gray-50 dark:bg-slate-700 rounded-full pl-1 pr-1.5 sm:pr-4 py-1 shrink-0">
                        <div class="w-7 h-7 rounded-full bg-[#012428] flex items-center justify-center text-white text-xs font-semibold notranslate overflow-hidden shrink-0" translate="no">
                            @if (auth()->user()->foto)
                                <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Foto profil" class="w-full h-full object-cover">
                            @else
                                {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                            @endif
                        </div>
                        <span class="hidden max-w-[11rem] truncate text-sm font-medium text-gray-700 dark:text-gray-200 sm:inline notranslate" translate="no">{{ auth()->user()->name ?? 'Admin' }}</span>
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

    <!-- Toast Notification -->
    <x-toast />

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
        window.adminConfirm = function (message, title = 'Konfirmasi') {
            return Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                focusCancel: true,
                buttonsStyling: false,
                customClass: {
                    popup: 'admin-confirm-popup',
                    title: 'admin-confirm-title',
                    htmlContainer: 'admin-confirm-message',
                    confirmButton: 'admin-confirm-accept',
                    cancelButton: 'admin-confirm-cancel',
                    actions: 'admin-confirm-actions'
                }
            }).then(result => result.isConfirmed);
        };

        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('submit', function(event) {
                const form = event.target.closest('form[data-confirm]');
                if (!form) return;

                if (form.dataset.confirmed === 'true') {
                    delete form.dataset.confirmed;
                    return;
                }

                event.preventDefault();
                event.stopImmediatePropagation();
                if (form.dataset.confirmPending === 'true') return;
                form.dataset.confirmPending = 'true';

                const submitter = event.submitter;
                window.adminConfirm(form.dataset.confirm).then(confirmed => {
                    delete form.dataset.confirmPending;
                    if (!confirmed) return;

                    form.dataset.confirmed = 'true';
                    if (submitter) {
                        form.requestSubmit(submitter);
                    } else {
                        form.requestSubmit();
                    }
                });
            }, true);

            document.addEventListener('click', function(event) {
                const button = event.target.closest('button[data-confirm]');
                if (!button) return;

                if (button.dataset.confirmed === 'true') {
                    delete button.dataset.confirmed;
                    return;
                }

                event.preventDefault();
                event.stopImmediatePropagation();
                if (button.dataset.confirmPending === 'true') return;
                button.dataset.confirmPending = 'true';

                window.adminConfirm(button.dataset.confirm).then(confirmed => {
                    delete button.dataset.confirmPending;
                    if (!confirmed) return;

                    button.dataset.confirmed = 'true';
                    button.click();
                });
            }, true);

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
    <style>
        .admin-confirm-popup {
            border-radius: 1rem;
            padding: 1.5rem;
        }
        .admin-confirm-title {
            color: #0f172a;
            font-size: 1.125rem;
            font-weight: 700;
        }
        .admin-confirm-message {
            color: #64748b;
            font-size: 0.925rem;
        }
        .admin-confirm-actions {
            gap: 0.625rem;
        }
        .admin-confirm-accept,
        .admin-confirm-cancel {
            border: 0;
            border-radius: 0.625rem;
            padding: 0.625rem 1rem;
            font-weight: 600;
        }
        .admin-confirm-accept {
            background: #012428;
            color: #fff;
        }
        .admin-confirm-cancel {
            background: #f1f5f9;
            color: #334155;
        }
        html.dark .admin-confirm-popup {
            background: #1e293b;
        }
        html.dark .admin-confirm-title {
            color: #f8fafc;
        }
        html.dark .admin-confirm-message {
            color: #cbd5e1;
        }
        html.dark .admin-confirm-cancel {
            background: #334155;
            color: #f8fafc;
        }

        /* Keep the entire admin dark theme in the same green family as its sidebar. */
        html.dark body,
        html.dark main,
        html.dark .bg-gray-50,
        html.dark .bg-slate-50,
        html.dark .bg-gray-100,
        html.dark .bg-slate-100,
        html.dark .bg-gray-900,
        html.dark .bg-slate-900,
        html.dark [class*="dark:bg-gray-900"],
        html.dark [class*="dark:bg-slate-900"],
        html.dark [class*="dark:bg-gray-950"],
        html.dark [class*="dark:bg-slate-950"] {
            background-color: #012428 !important;
        }

        html.dark .bg-white,
        html.dark .bg-gray-800,
        html.dark .bg-slate-800,
        html.dark .bg-gray-700,
        html.dark .bg-slate-700,
        html.dark [class*="dark:bg-gray-800"],
        html.dark [class*="dark:bg-slate-800"],
        html.dark [class*="dark:bg-gray-700"],
        html.dark [class*="dark:bg-slate-700"],
        html.dark .card,
        html.dark .chart-card,
        html.dark .insight-card,
        html.dark .admin-confirm-popup,
        html.dark .modal-content,
        html.dark .swal2-popup {
            background-color: #103b32 !important;
            color: #e5f5ed !important;
        }

        html.dark [class*="dark:bg-slate-"],
        html.dark [class*="dark:bg-gray-"] {
            background-color: #103b32 !important;
        }

        html.dark [class*="dark:bg-slate-900"],
        html.dark [class*="dark:bg-gray-900"],
        html.dark [class*="dark:bg-slate-950"],
        html.dark [class*="dark:bg-gray-950"] {
            background-color: #012428 !important;
        }

        html.dark [class*="dark:bg-emerald-"],
        html.dark [class*="dark:bg-green-"] {
            background-color: #145943 !important;
        }

        html.dark [class*="bg-gradient"] {
            background-image: none !important;
            background-color: #103b32 !important;
        }

        html.dark .admin-dashboard-hero {
            background: #064e3b !important;
        }

        html.dark [class*="border-slate-"],
        html.dark [class*="border-gray-"] {
            border-color: #315747 !important;
        }

        html.dark [class*="text-slate-400"],
        html.dark [class*="text-slate-500"],
        html.dark [class*="text-gray-400"],
        html.dark [class*="text-gray-500"],
        html.dark [class*="text-gray-600"],
        html.dark [class*="dark:text-slate-400"],
        html.dark [class*="dark:text-slate-500"],
        html.dark [class*="dark:text-gray-400"],
        html.dark [class*="dark:text-gray-500"] {
            color: #c1d5ca !important;
        }

        html.dark [class*="text-slate-700"],
        html.dark [class*="text-slate-800"],
        html.dark [class*="text-gray-700"],
        html.dark [class*="text-gray-800"],
        html.dark [class*="text-gray-900"],
        html.dark [class*="dark:text-slate-700"],
        html.dark [class*="dark:text-slate-800"],
        html.dark [class*="dark:text-gray-700"],
        html.dark [class*="dark:text-gray-800"] {
            color: #e5f5ed !important;
        }

        html.dark input,
        html.dark select,
        html.dark textarea,
        html.dark .form-control,
        html.dark .form-select {
            background-color: #082e28 !important;
            border-color: #315747 !important;
            color: #e5f5ed !important;
        }

        html.dark .table,
        html.dark .table tbody tr,
        html.dark .table tbody td {
            --bs-table-bg: #103b32;
            --bs-table-color: #e5f5ed;
            color: #e5f5ed;
            border-color: #315747;
        }

        html.dark .table thead th,
        html.dark .table-light {
            --bs-table-bg: #012428;
            --bs-table-color: #d1e7dc;
            background-color: #012428 !important;
            color: #d1e7dc !important;
        }
    </style>

    @stack('scripts')
    @include('partials.translator')
</body>
</html>
