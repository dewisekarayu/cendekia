<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e($title ?? 'Admin Dashboard'); ?> - <?php echo e(config('app.name', 'Cendekia')); ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <style>
        :root {
            --primary-blue: #002B6B;
            --secondary-blue: #002B6B;
            --navy-blue: #002B6B;
            --light-blue: #E6EEFF;
            --border-light: rgba(0, 43, 107, 0.08);
        }

        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #F4F7FB;
            overflow-x: hidden;
        }

        /* Sidebar */
        .admin-sidebar {
            background: #002B6B;
            height: 100vh;
            padding: 1.5rem 1rem;
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            color: white;
            box-shadow: 4px 0 25px rgba(0, 43, 107, 0.18);
            z-index: 1050;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.3s ease;
        }

        /* Custom scrollbar for sidebar */
        .admin-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .admin-sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: transparent;
            border-radius: 4px;
        }

        .admin-sidebar:hover::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
        }

        .admin-sidebar .nav-menu {
            flex: 1;
            padding-bottom: 1rem;
        }

        .admin-sidebar .logo {
            display: flex;
            align-items: center;
            margin-bottom: 2.5rem;
            font-weight: 700;
            font-size: 1.35rem;
            gap: 0.75rem;
            padding-left: 0.5rem;
        }

        .admin-sidebar .logo i {
            font-size: 2rem;
            color: white;
        }

        .admin-sidebar .logo .logo-sub {
            display: block;
            font-size: 0.65rem;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: -2px;
        }

        .admin-sidebar .nav-item {
            margin-bottom: 0.35rem;
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .admin-sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: white;
        }

        .admin-sidebar .nav-link.active {
            background-color: white !important;
            color: var(--navy-blue) !important;
            font-weight: 600;
            border-left: none;
            box-shadow: 0 4px 12px rgba(0, 43, 107, 0.15);
        }

        .admin-sidebar .nav-link.active i {
            color: var(--secondary-blue) !important;
        }

        .admin-sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .admin-sidebar .nav-section-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.45);
            text-transform: uppercase;
            letter-spacing: 0.75px;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
            padding-left: 1rem;
        }

        /* Overlay backdrop untuk sidebar mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.45);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        /* Main Content */
        .admin-content {
            margin-left: 280px;
            padding: 0;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .admin-topbar {
            background: #002B6B;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 43, 107, 0.18);
            color: white;
            flex-wrap: wrap;
            gap: 0.75rem;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .admin-topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            min-width: 0;
        }

        /* Tombol hamburger (khusus mobile) */
        .sidebar-toggle-btn {
            display: none;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 0.5rem;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            flex-shrink: 0;
        }

        .sidebar-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .admin-topbar-search {
            background-color: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            width: 280px;
            max-width: 100%;
            color: white;
            transition: all 0.3s;
        }

        .admin-topbar-search:focus {
            background-color: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            outline: none;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.15);
        }

        .admin-topbar-search::placeholder {
            color: rgba(255, 255, 255, 0.45);
        }

        .admin-topbar-right {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex-shrink: 0;
        }

        .topbar-notif {
            position: relative;
            cursor: pointer;
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.85);
            transition: color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .topbar-notif:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .topbar-notif .dot {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 8px;
            height: 8px;
            background-color: #ef4444;
            border: 1.5px solid var(--secondary-blue);
            border-radius: 50%;
        }

        .admin-topbar-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            padding: 0.35rem 0.75rem;
            border-radius: 2rem;
            background-color: rgba(255, 255, 255, 0.05);
            transition: background-color 0.2s;
        }

        .admin-topbar-profile:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .admin-topbar-profile img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #CDDCFF;
            padding: 0.15rem;
        }

        .admin-topbar-profile-text {
            display: flex;
            flex-direction: column;
            font-size: 0.85rem;
            text-align: left;
        }

        .admin-topbar-profile-text .name {
            font-weight: 600;
            color: white;
            line-height: 1.2;
        }

        .admin-topbar-profile-text .role {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.7rem;
            font-weight: 600;
            margin-top: 1px;
        }

        .admin-topbar-profile i {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .admin-main {
            padding: 2rem;
            min-width: 0;
        }

        .admin-dashboard-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.9fr) minmax(280px, 0.9fr);
            gap: 1.5rem;
            align-items: stretch;
            margin-top: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #002B6B;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #6b7280;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: #002B6B;
            border-radius: 1rem;
            padding: 2.25rem;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px rgba(0, 43, 107, 0.1);
        }

        .welcome-banner h2 {
            font-size: 1.85rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-banner p {
            font-size: 0.975rem;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 0;
            max-width: 650px;
            line-height: 1.5;
        }

        /* Stat Grid */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        @media (min-width: 576px) {
            .stat-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1200px) {
            .stat-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.5rem;
            border: none;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 43, 107, 0.05);
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 43, 107, 0.1);
        }

        .stat-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            width: 100%;
        }

        .stat-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
        }

        .stat-card-icon.blue {
            background-color: #E6EEFF;
            color: #002B6B;
        }

        .stat-card-icon.green {
            background-color: #E6FAF0;
            color: #10b981;
        }

        .stat-card-icon.orange {
            background-color: #FFF3EB;
            color: #f97316;
        }

        .stat-card-icon.red {
            background-color: #FFEBEB;
            color: #ef4444;
        }

        .stat-card-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 2rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stat-card-badge.up {
            background-color: #E6FAF0;
            color: #10b981;
        }

        .stat-card-badge.down {
            background-color: #FFEBEB;
            color: #ef4444;
        }

        .stat-card-badge.flat {
            background-color: #F1F3F9;
            color: #8A94A6;
        }

        .stat-card .label {
            font-size: 0.75rem;
            color: #8A94A6;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .stat-card .number {
            font-size: 1.75rem;
            font-weight: 700;
            color: #002B6B;
        }

        /* Chart Container */
        .chart-card {
            background: linear-gradient(180deg, #FFFFFF 0%, #F8FBFF 100%);
            border-radius: 1rem;
            padding: 1.75rem;
            border: 1px solid rgba(0, 43, 107, 0.08);
            box-shadow: 0 16px 45px rgba(0, 43, 107, 0.08);
            margin-top: 0;
            position: relative;
            overflow: hidden;
            min-width: 0;
        }

        .chart-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .chart-card h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #002B6B;
            margin-bottom: 0.25rem;
        }

        .chart-card p {
            color: #8A94A6;
            font-size: 0.875rem;
            margin-bottom: 0;
        }

        .chart-summary {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-left: auto;
        }

        .chart-summary-item {
            min-width: 108px;
            padding: 0.65rem 0.8rem;
            border: 1px solid rgba(0, 43, 107, 0.08);
            border-radius: 0.75rem;
            background: rgba(255, 255, 255, 0.72);
        }

        .chart-summary-item .summary-label {
            display: block;
            color: #64748B;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.35px;
            line-height: 1;
            margin-bottom: 0.35rem;
        }

        .chart-summary-item strong {
            display: block;
            color: #002B6B;
            font-size: 1.15rem;
            line-height: 1.1;
        }

        .chart-toggle {
            display: flex;
            background-color: #EAF1FF;
            padding: 0.25rem;
            border-radius: 0.75rem;
            gap: 0.25rem;
            border: 1px solid rgba(0, 43, 107, 0.08);
        }

        .chart-toggle button {
            border: none;
            background: transparent;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: #50627D;
            border-radius: 0.55rem;
            transition: all 0.2s;
        }

        .chart-toggle button.active {
            background-color: #002B6B;
            color: white;
            box-shadow: 0 8px 18px rgba(0, 43, 107, 0.18);
        }

        .chart-canvas-wrap {
            position: relative;
            height: 340px;
            width: 100%;
            padding: 0.75rem 0.25rem 0;
        }

        .insight-card {
            background: #FFFFFF;
            border: 1px solid rgba(0, 43, 107, 0.08);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 16px 45px rgba(15, 23, 42, 0.06);
            min-width: 0;
        }

        .insight-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .insight-card-header h3 {
            color: #0F172A;
            font-size: 1.05rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .insight-card-header p {
            color: #64748B;
            font-size: 0.85rem;
            margin-bottom: 0;
            line-height: 1.4;
        }

        .insight-card-header span {
            display: inline-flex;
            min-width: 58px;
            justify-content: center;
            border-radius: 0.75rem;
            background: #EEF4FF;
            color: #002B6B;
            padding: 0.55rem 0.75rem;
            font-weight: 800;
        }

        .insight-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .insight-row span {
            color: #475569;
            font-size: 0.88rem;
            font-weight: 700;
        }

        .insight-row strong {
            color: #0F172A;
            font-size: 0.9rem;
        }

        .insight-progress {
            height: 0.55rem;
            background: #E8EEF8;
            border-radius: 999px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .insight-progress div {
            height: 100%;
            min-width: 0.4rem;
            border-radius: inherit;
            background: linear-gradient(90deg, #002B6B 0%, #0EA5E9 55%, #10B981 100%);
        }

        /* Table Styles */
        .table-card {
            background: white;
            border-radius: 1rem;
            border: none;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 43, 107, 0.05);
            margin-top: 1.5rem;
        }

        .table-card-header {
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
            border-bottom: 1px solid var(--border-light);
        }

        .table-card-header h5 {
            margin: 0;
            font-weight: 700;
            color: #002B6B;
        }

        /* Wrapper agar tabel bisa di-scroll horizontal di layar kecil */
        .table-responsive-wrap {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-card .table {
            margin-bottom: 0;
            min-width: 600px;
        }

        .table-card .table th {
            background-color: #F8FAFC;
            border-top: none;
            border-bottom: 2px solid var(--border-light);
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 1rem 1.5rem;
            white-space: nowrap;
        }

        .table-card .table td {
            vertical-align: middle;
            border-color: var(--border-light);
            padding: 1rem 1.5rem;
            color: #334155;
            font-size: 0.9rem;
        }

        .table-card .table tr:hover {
            background-color: #F8FAFC;
        }

        /* Badge Styles */
        .badge-status {
            padding: 0.4rem 0.8rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-aktif {
            background-color: #E6FAF0;
            color: #10b981;
        }

        .badge-tidak-aktif {
            background-color: #FFEBEB;
            color: #ef4444;
        }

        .badge-high {
            background-color: #FFEBEB;
            color: #ef4444;
        }

        .badge-medium {
            background-color: #FFF3EB;
            color: #f97316;
        }

        .badge-low {
            background-color: #E6EEFF;
            color: #002B6B;
        }

        /* Button Styles */
        .btn-primary {
            background-color: #002B6B;
            border-color: #002B6B;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #001f4d;
            border-color: #001f4d;
        }

        .btn-sm {
            padding: 0.35rem 0.75rem;
            font-size: 0.85rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            font-size: 0.85rem;
        }

        .action-btn-view {
            background-color: #E6EEFF;
            color: #002B6B;
        }

        .action-btn-view:hover {
            background-color: #cddcff;
        }

        .action-btn-edit {
            background-color: #FFF3EB;
            color: #f97316;
        }

        .action-btn-edit:hover {
            background-color: #fed7aa;
        }

        .action-btn-delete {
            background-color: #FFEBEB;
            color: #ef4444;
        }

        .action-btn-delete:hover {
            background-color: #fecaca;
        }

        /* Pagination */
        .pagination {
            margin-top: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .pagination .page-link {
            color: #002B6B;
            border-color: #e2e8f0;
        }

        .pagination .page-link:hover {
            background-color: #E6EEFF;
            border-color: #002B6B;
        }

        .pagination .page-item.active .page-link {
            background-color: #002B6B;
            border-color: #002B6B;
            color: white;
        }

        /* Form Styles */
        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control, .form-select {
            border-color: #cbd5e1;
            border-radius: 0.5rem;
            padding: 0.6rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #002B6B;
            box-shadow: 0 0 0 0.2rem rgba(0, 43, 107, 0.15);
        }

        /* Modal */
        .modal-content {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid #e2e8f0;
            background-color: #F8FAFC;
        }

        /* ============ RESPONSIVE (TABLET) ============ */
        @media (max-width: 991px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
            }

            .admin-content {
                margin-left: 0;
            }

            .sidebar-toggle-btn {
                display: flex;
            }

            .admin-dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ============ RESPONSIVE (MOBILE) ============ */
        @media (max-width: 768px) {
            .admin-topbar {
                padding: 0.75rem 1rem;
            }

            .admin-topbar-left {
                order: 1;
                flex: 1 1 auto;
            }

            .admin-topbar-right {
                order: 2;
                gap: 0.5rem;
            }

            /* Search bar pindah ke baris sendiri di HP */
            .admin-topbar-search {
                order: 3;
                width: 100%;
                flex-basis: 100%;
            }

            .admin-topbar-profile-text {
                display: none;
            }

            .admin-main {
                padding: 1rem;
            }

            .container-fluid {
                padding-left: 0;
                padding-right: 0;
            }

            .admin-main > .container-fluid > .d-flex:first-child,
            .admin-main > .container-fluid > .mb-4.d-flex,
            .admin-main > .container-fluid > .row > [class*="col-"] > .mb-4.d-flex {
                flex-direction: column;
                align-items: stretch !important;
                gap: 1rem;
            }

            .admin-main > .container-fluid > .d-flex:first-child .btn,
            .admin-main > .container-fluid > .mb-4.d-flex .btn {
                width: 100%;
                justify-content: center;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .welcome-banner {
                padding: 1.5rem;
            }

            .welcome-banner h2 {
                font-size: 1.4rem;
            }

            .chart-card {
                padding: 1.25rem;
            }

            .chart-card-header {
                align-items: stretch;
            }

            .chart-summary {
                order: 2;
                width: 100%;
                margin-left: 0;
            }

            .chart-summary-item {
                flex: 1;
                min-width: 0;
            }

            .chart-toggle {
                order: 3;
                width: 100%;
            }

            .chart-toggle button {
                flex: 1;
            }

            .action-buttons {
                flex-wrap: nowrap;
            }

            .table-card-header {
                padding: 1rem;
            }

            .table-card {
                border-radius: 0.85rem;
                margin-top: 1rem;
            }

            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table-responsive > .table {
                min-width: 760px;
            }

            .table-card .table th,
            .table-card .table td {
                padding: 0.85rem 0.75rem;
                font-size: 0.85rem;
            }

            .form-select,
            .form-control {
                width: 100% !important;
                min-width: 0 !important;
            }

            .pagination {
                flex-wrap: wrap;
                gap: 0.35rem;
            }
        }

        @media (max-width: 400px) {
            .admin-topbar-right {
                gap: 0.35rem;
            }

            .admin-topbar-profile {
                padding: 0.3rem 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex" style="min-height: 100vh;">
        <!-- Overlay gelap saat sidebar terbuka di mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <?php echo $__env->make('admin.components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Main Content -->
        <div class="admin-content w-100">
            <!-- Top Bar -->
            <?php echo $__env->make('admin.components.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Page Content -->
            <div class="admin-main">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
            <div class="modal-content text-center p-4 shadow-lg" style="border-radius: 1rem; border: none;">
                <div class="modal-body p-0">
                    <div class="mb-3 d-inline-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background-color: #FEE2E2; border-radius: 50%;">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size: 1.5rem; color: #dc2626;"></i>
                    </div>
                    <h5 class="mb-2" style="font-weight: 700; color: #1e293b;">Hapus Data?</h5>
                    <p class="text-muted mb-4" style="font-size: 0.9rem; line-height: 1.5;">Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.</p>
                    <div class="d-flex gap-2 w-100">
                        <button type="button" class="btn btn-light flex-grow-1 py-2.5" data-bs-dismiss="modal" style="border: 1px solid #cbd5e1; border-radius: 0.5rem; font-weight: 600; color: #475569; background-color: white; font-size: 0.9rem;">Batal</button>
                        <button type="button" id="confirmDeleteBtn" class="btn btn-danger flex-grow-1 py-2.5" style="background-color: #991b1b; border: none; border-radius: 0.5rem; font-weight: 600; font-size: 0.9rem; box-shadow: 0 4px 12px rgba(153, 27, 27, 0.15);">Ya, Hapus</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.querySelector('[data-sidebar-toggle]');
            const sidebar = document.querySelector('.admin-sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            function openSidebar() {
                sidebar.classList.add('show');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    if (sidebar.classList.contains('show')) {
                        closeSidebar();
                    } else {
                        openSidebar();
                    }
                });
            }

            // Klik overlay untuk menutup sidebar
            overlay.addEventListener('click', closeSidebar);

            // Tutup sidebar otomatis saat memilih menu (di mobile)
            sidebar.addEventListener('click', function(e) {
                if (e.target.closest('.nav-link') && window.innerWidth <= 991) {
                    closeSidebar();
                }
            });

            // Reset state kalau layar di-resize ke desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 991) {
                    closeSidebar();
                }
            });

            // Listen to delete action buttons to show custom delete modal
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
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\laragon\www\cendekia\resources\views/layouts/admin.blade.php ENDPATH**/ ?>