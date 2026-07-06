<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Dashboard' }} - {{ config('app.name', 'Cendekia') }}</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --primary-blue: #002B6B;
            --secondary-blue: #001f4d;
            --light-blue: #CDDCFF;
            --border-light: #e5e7eb;
        }

        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f3f4f6;
            overflow-x: hidden;
        }

        /* Sidebar */
        .admin-sidebar {
            background: linear-gradient(135deg, #001f4d 0%, #002B6B 100%);
            height: 100vh;
            padding: 1.25rem 0.75rem;
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
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
            background: rgba(255, 255, 255, 0.25);
        }

        .admin-sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* Scrollable nav menu */
        .admin-sidebar .nav-menu {
            flex: 1;
            overflow-y: auto;
            padding-bottom: 1rem;
        }

        .admin-sidebar .nav-menu hr {
            margin-top: auto;
            margin-bottom: 1rem;
        }

        .admin-sidebar .logo {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 1.25rem;
            gap: 0.5rem;
        }

        .admin-sidebar .logo i {
            font-size: 1.5rem;
        }

        .admin-sidebar .nav-item {
            margin-bottom: 0.5rem;
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .admin-sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .admin-sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
            border-left: 3px solid white;
        }

        .admin-sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        .admin-sidebar .nav-section-title {
            font-size: 0.7rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.6);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 1rem;
            margin-bottom: 0.4rem;
            padding-left: 0.75rem;
        }

        .admin-sidebar .nav-section-title:first-child {
            margin-top: 0;
        }

        /* Main Content */
        .admin-content {
            margin-left: 280px;
            padding: 0;
            min-height: 100vh;
        }

        .admin-topbar {
            background: white;
            border-bottom: 1px solid var(--border-light);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .admin-topbar-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .admin-topbar-search {
            background-color: #f3f4f6;
            border: 1px solid var(--border-light);
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            width: 300px;
        }

        .admin-topbar-search::placeholder {
            color: #9ca3af;
        }

        .admin-topbar-right {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .admin-topbar-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
        }

        .admin-topbar-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #CDDCFF;
            padding: 0.25rem;
        }

        .admin-topbar-profile-text {
            display: flex;
            flex-direction: column;
            font-size: 0.85rem;
        }

        .admin-topbar-profile-text .name {
            font-weight: 600;
            color: #1f2937;
        }

        .admin-topbar-profile-text .role {
            color: #9ca3af;
            font-size: 0.8rem;
        }

        .admin-main {
            padding: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .page-subtitle {
            color: #9ca3af;
            font-size: 0.95rem;
            margin-bottom: 2rem;
        }

        /* Stats Cards */
        .stat-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stat-card:hover {
            border-color: var(--primary-blue);
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.1);
        }

        .stat-card-icon {
            width: 60px;
            height: 60px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
        }

        .stat-card-icon.blue {
            background-color: #CDDCFF;
            color: #002B6B;
        }

        .stat-card-icon.green {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .stat-card-icon.yellow {
            background-color: #fef3c7;
            color: #f59e0b;
        }

        .stat-card-icon.red {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .stat-card-content h3 {
            font-size: 0.75rem;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .stat-card-content .number {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1f2937;
        }

        /* Chart Container */
        .chart-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 1px solid var(--border-light);
            margin-top: 2rem;
        }

        .chart-card h5 {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .chart-card p {
            color: #9ca3af;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        /* Table Styles */
        .table-card {
            background: white;
            border-radius: 0.75rem;
            border: 1px solid var(--border-light);
            overflow: hidden;
            margin-top: 1.5rem;
        }

        .table-card-header {
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-light);
        }

        .table-card-header h5 {
            margin: 0;
            font-weight: 600;
            color: #1f2937;
        }

        .table-card .table {
            margin-bottom: 0;
        }

        .table-card .table th {
            background-color: #f9fafb;
            border-top: none;
            border-bottom: 2px solid var(--border-light);
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table-card .table td {
            vertical-align: middle;
            border-color: var(--border-light);
            padding: 1rem 1.5rem;
        }

        .table-card .table tr:hover {
            background-color: #f9fafb;
        }

        /* Badge Styles */
        .badge-status {
            padding: 0.4rem 0.8rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-aktif {
            background-color: #dcfce7;
            color: #16a34a;
        }

        .badge-tidak-aktif {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .badge-high {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .badge-medium {
            background-color: #fef3c7;
            color: #f59e0b;
        }

        .badge-low {
            background-color: #e0f2fe;
            color: #0284c7;
        }

        /* Button Styles */
        .btn-primary {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        .btn-primary:hover {
            background-color: var(--secondary-blue);
            border-color: var(--secondary-blue);
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
            background-color: #e0f2fe;
            color: #0284c7;
        }

        .action-btn-view:hover {
            background-color: #bae6fd;
        }

        .action-btn-edit {
            background-color: #fef3c7;
            color: #f59e0b;
        }

        .action-btn-edit:hover {
            background-color: #fde68a;
        }

        .action-btn-delete {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .action-btn-delete:hover {
            background-color: #fecaca;
        }

        /* Pagination */
        .pagination {
            margin-top: 1.5rem;
            justify-content: center;
        }

        .pagination .page-link {
            color: var(--primary-blue);
            border-color: var(--border-light);
        }

        .pagination .page-link:hover {
            background-color: var(--light-blue);
            border-color: var(--primary-blue);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-blue);
            border-color: var(--primary-blue);
        }

        /* Form Styles */
        .form-label {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control, .form-select {
            border-color: var(--border-light);
            border-radius: 0.5rem;
            padding: 0.6rem 1rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.1);
        }

        /* Modal */
        .modal-content {
            border: 1px solid var(--border-light);
            border-radius: 0.75rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid var(--border-light);
            background-color: #f9fafb;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 100%;
                max-height: 0;
                padding: 0;
                transition: max-height 0.3s ease;
                position: relative;
            }

            .admin-sidebar.show {
                max-height: 500px;
                padding: 2rem 1rem;
            }

            .admin-content {
                margin-left: 0;
            }

            .admin-topbar-search {
                width: 100%;
            }

            .stat-card {
                flex-direction: column;
                text-align: center;
            }

            .stat-card-icon {
                margin-bottom: 1rem;
            }

            .action-buttons {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex" style="min-height: 100vh;">
        <!-- Sidebar -->
        @include('admin.components.sidebar')

        <!-- Main Content -->
        <div class="admin-content w-100">
            <!-- Top Bar -->
            @include('admin.components.topbar')

            <!-- Page Content -->
            <div class="admin-main">
                {{ $slot }}
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
            
            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
        });
    </script>
</body>
</html>
