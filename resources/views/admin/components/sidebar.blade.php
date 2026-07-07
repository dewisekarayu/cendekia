<aside class="admin-sidebar" id="adminSidebar">
    <div class="logo">
        <i class="bi bi-mortarboard-fill"></i>
        <div>
            <span>Cendekia Admin</span>
            <span class="logo-sub">LIVE ADMINISTRATION</span>
        </div>
    </div>

    <nav class="nav-menu">
        <!-- Dashboard -->
        <div class="nav-section-title">MENU UTAMA</div>

        <div class="nav-item">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- Master Data -->
        <div class="nav-section-title">MASTER DATA</div>

        <div class="nav-item">
            <a href="{{ route('admin.dosen.index') }}"
               class="nav-link {{ request()->routeIs('admin.dosen.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i>
                <span>Data Dosen</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.mahasiswa.index') }}"
               class="nav-link {{ request()->routeIs('admin.mahasiswa.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Data Mahasiswa</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.mata-kuliah.index') }}"
               class="nav-link {{ request()->routeIs('admin.mata-kuliah.*') ? 'active' : '' }}">
                <i class="bi bi-book-fill"></i>
                <span>Mata Kuliah</span>
            </a>
        </div>

        <div class="nav-item">
            <a href="{{ route('admin.program-studi.index') }}"
               class="nav-link {{ request()->routeIs('admin.program-studi.*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3"></i>
                <span>Program Studi</span>
            </a>
        </div>

        <!-- Konten -->
        <div class="nav-section-title">KONTEN</div>

        <div class="nav-item">
            <a href="{{ route('admin.pengumuman.index') }}"
               class="nav-link {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}">
                <i class="bi bi-megaphone-fill"></i>
                <span>Pengumuman</span>
            </a>
        </div>

        <hr style="border-color: rgba(255,255,255,0.08); margin: 1.5rem 12px;">

        <!-- Logout -->
        <div class="nav-item">
            <form method="POST" action="{{ route('logout') }}" class="w-100">
                @csrf
                <button type="submit" class="nav-link w-100 text-start border-0 bg-transparent">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>
</aside>
