<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Cendekia Admin</title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <!-- Admin Theme -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
</head>
<body>

    <div class="admin-wrapper">

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        @include('partials.admin-sidebar')

        <div class="admin-main">
            @include('partials.admin-topbar')

            <div class="admin-content">
                @yield('content')
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
                toggleBtn.addEventListener('click', function () {
                    sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
                });
            }

            overlay.addEventListener('click', closeSidebar);

            sidebar.addEventListener('click', function (e) {
                if (e.target.closest('.nav-link') && window.innerWidth <= 991) {
                    closeSidebar();
                }
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 991) closeSidebar();
            });
        });
    </script>

    @stack('scripts')
</body>
</html>